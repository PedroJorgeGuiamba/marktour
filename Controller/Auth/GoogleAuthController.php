<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';

// Incluir autoload da biblioteca (usando vendor/)
require_once __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/autoload.php';

class GoogleAuthController
{
    private $client;
    private $conn;
    private $criptografia;

    public function __construct()
    {
        $conexao = new Conector();
        $this->conn = $conexao->getConexao();
        $this->criptografia = new Criptografia();

        // Configurar o cliente Google
        $this->client = new Google_Client();
        $this->client->setAuthConfig(__DIR__ . '/../../config/client_secret.json');
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->setRedirectUri('http://localhost/marktour/Controller/Auth/GoogleCallback.php');
    }

    public function iniciarAutenticacao()
    {
        $authUrl = $this->client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    public function callback()
    {
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (!isset($token['error'])) {
                $this->client->setAccessToken($token['access_token']);

                // Obter informações do usuário
                $googleService = new Google_Service_Oauth2($this->client);
                $userInfo = $googleService->userinfo->get();

                $email = $userInfo->email;
                $nome = $userInfo->name;

                // Verificar se o e-mail já existe
                $sql = "SELECT id_utilizador, tipo FROM utilizador WHERE email = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $tipo);
                    $stmt->fetch();
                    $stmt->close();

                    session_start();
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
                    session_start();
                    $_SESSION['google_email'] = $email;
                    $_SESSION['google_name'] = $nome;
                    header("Location: /marktour/View/SelecionarTipo.php");
                    exit();
                }
            } else {
                header("Location: /marktour/View/Registro.php?erros=Erro na autenticação com Google");
                exit();
            }
        }
    }
}

$controller = new GoogleAuthController();
if (isset($_GET['callback'])) {
    $controller->callback();
} else {
    $controller->iniciarAutenticacao();
}