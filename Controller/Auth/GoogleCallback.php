<?php
require_once __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/autoload.php';
require_once 'GoogleAuthController.php';

class GoogleCallback extends GoogleAuthController
{
    public function __construct()
    {
        // Chama o construtor da classe pai para inicializar $client, $conn e $criptografia
        try {
            parent::__construct();
        } catch (Exception $e) {
            // Log e redirecionamento amigável caso falte o ficheiro de credenciais
            error_log("GoogleCallback::__construct exception: " . $e->getMessage());
            // Redireciona para uma página de erro amigável
            header("Location: /marktour/View/Auth/Register.php?erros=" . urlencode('Erro de configuração do Google: ' . $e->getMessage()));
            exit();
        }
    }

    public function handleCallback()
    {
        // Iniciar sessão para armazenar dados do usuário
        session_start();

        // Verificar se o código de autorização foi recebido
        if (!isset($_GET['code'])) {
            error_log("Nenhum código de autorização recebido no callback.");
            header("Location: /marktour/View/Auth/Register.php?erros=Nenhum código de autorização recebido");
            exit();
        }

        // Validar o parâmetro state (segurança CSRF) usando sessão
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_GET['state'])) {
            $receivedState = $_GET['state'];
            $expectedState = isset($_SESSION['oauth2state']) ? $_SESSION['oauth2state'] : null;
            if ($expectedState === null || $receivedState !== $expectedState) {
                error_log("Erro de validação de state: received=" . print_r($receivedState, true) . " expected=" . print_r($expectedState, true));
                header("Location: /marktour/View/Auth/Register.php?erros=Erro de validação de estado");
                exit();
            }
            // State válido; opcionalmente limpar
            unset($_SESSION['oauth2state']);
        } else {
            error_log("State não fornecido no callback.");
            header("Location: /marktour/View/Auth/Register.php?erros=State ausente no callback");
            exit();
        }

        // Verificar se $client foi inicializado
        if ($this->client === null) {
            error_log("Erro: Cliente Google não inicializado.");
            header("Location: /marktour/View/Auth/Register.php?erros=Erro interno: Cliente não inicializado");
            exit();
        }

        $stmt = null;
        try {
            // Obter o token de acesso usando o código
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['error'])) {
                error_log("Erro ao obter token: " . print_r($token, true));
                header("Location: /marktour/View/Auth/Register.php?erros=Erro ao autenticar com Google: " . urlencode($token['error_description']));
                exit();
            }

            $this->client->setAccessToken($token['access_token']);

            // Obter informações do usuário
            $googleService = new \Google\Service\Oauth2($this->client);
            $userInfo = $googleService->userinfo->get();

            $email = $userInfo->email;
            $nome = $userInfo->name;

            error_log("Usuário autenticado: Email = $email, Nome = $nome");

            // Verificar se o e-mail já existe na base de dados
            $sql = "SELECT id_utilizador, tipo FROM utilizador WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                error_log("Erro ao preparar a consulta SQL: " . $this->conn->error);
                header("Location: /marktour/View/Auth/Register.php?erros=Erro interno no banco de dados");
                exit();
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Usuário existente
                $stmt->bind_result($id, $tipo);
                $stmt->fetch();
                $stmt->close();

                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = $tipo;
                $_SESSION['usuario_id'] = $id;

                setcookie('user_email', $email, time() + 3600, "/");

                $sessaoId = iniciarSessao($id);
                registrarAtividade($sessaoId, "Login com Google realizado com sucesso", "LOGIN_GOOGLE");

                if ($tipo === 'cliente') {
                    header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
                    exit();
                } elseif ($tipo === 'empresa') {
                    header("Location: /marktour/View/Empresa/localizacaoEmpresa.php");
                    exit();
                }
            } else {
                // Novo usuário
                $_SESSION['google_email'] = $email;
                $_SESSION['google_name'] = $nome;
                header("Location: /marktour/View/SelecionarTipo.php");
                exit();
            }
        } catch (Exception $e) {
            error_log("Exceção no callback: " . $e->getMessage());
            // Se for erro de configuração (credenciais ausentes), já terá sido tratado no construtor.
            header("Location: /marktour/View/Auth/Register.php?erros=" . urlencode('Erro interno: ' . $e->getMessage()));
            exit();
        } finally {
            if ($stmt !== null && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }
}

// Instanciar e executar
$callback = new GoogleCallback();
$callback->handleCallback();