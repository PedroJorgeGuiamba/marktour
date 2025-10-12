<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';
// Prefer vendor autoload if present (the library was vendored into lib/.../vendor)
$vendorAutoload = __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
} else {
    // Fallback: simple PSR-4-like autoloader for the bundled Google client
    spl_autoload_register(function ($class) {
        $base = __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/';
        $prefixes = [
            'Google\\Service\\' => $base . 'vendor/google/apiclient-services/src/',
            'Google\\' => $base . 'src/',
            'GuzzleHttp\\' => $base . 'vendor/guzzlehttp/guzzle/src/',
            'Monolog\\' => $base . 'vendor/monolog/monolog/src/',
            'Psr\\' => $base . 'vendor/psr/',
        ];

        foreach ($prefixes as $prefix => $dir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) continue;
            $relative = substr($class, $len);
            $file = $dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        return false;
    });
}

// Incluir dependências manuais (ex.: Guzzle)
$guzzlePath = __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/guzzlehttp/guzzle/src/functions.php';
if (file_exists($guzzlePath)) {
    require_once $guzzlePath;
}

// Import namespaced Google classes to help static analyzers and simplify usage
use Google\Client;
use Google\Service\Oauth2 as Oauth2Service;

// Intelephense / static analysis helper: declare legacy global class names used by
// some examples (for example `Google_Service_Oauth2`). This block never runs at
// runtime but lets the language server know the class exists so it stops
// reporting P1009 undefined-type errors in this file.
if (!class_exists('Google_Service_Oauth2')) {
    /** @noinspection PhpUndefinedClassInspection */
    class Google_Service_Oauth2 {}
}

class GoogleAuthController
{
    protected $client;
    protected $conn;
    protected $criptografia;

    public function __construct()
    {
        $conexao = new Conector();
        $this->conn = $conexao->getConexao();
        $this->criptografia = new Criptografia();

        // Configurar o cliente Google (classe namespaced)
        if (!class_exists(Client::class)) {
            throw new Exception('Classe \Google\Client não encontrada. Verifique a instalação em lib/google-api-php-client-v2.18.3-PHP8.3.');
        }
        $this->client = new Client();
        // Procurar automaticamente por um ficheiro de credenciais do Google
        $configDir = realpath(__DIR__ . '/../../config');
        $clientSecretPath = null;
        if ($configDir !== false && is_dir($configDir)) {
            // aceitar vários nomes gerados pelo Google, por exemplo client_secret-*.json
            $matches = glob($configDir . DIRECTORY_SEPARATOR . 'client_secret*.json');
            if (!empty($matches)) {
                // escolher o primeiro encontrado
                $clientSecretPath = $matches[0];
            }
        }

        if ($clientSecretPath === null) {
            // Não encontrou ficheiro de credenciais — lançar exceção controlada com instruções
            throw new Exception('Arquivo de credenciais do Google não encontrado em config/. Coloque o ficheiro JSON (ex: client_secret.json) em config/ conforme descrito no README.');
        }

        // Apenas chamar setAuthConfig se o ficheiro existir (evita fatal error em Client.php)
        if (file_exists($clientSecretPath)) {
            $this->client->setAuthConfig($clientSecretPath);
        } else {
            throw new Exception('Arquivo de credenciais do Google encontrado mas não acessível: ' . $clientSecretPath);
        }
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->setRedirectUri('http://localhost/marktour/Controller/Auth/GoogleCallback.php');
        if (class_exists('\GuzzleHttp\Client')) {
            $this->client->setHttpClient(new \GuzzleHttp\Client());
        }
    }

    public function iniciarAutenticacao()
    {
        $state = bin2hex(random_bytes(16)); // Gera um state único
        // Guardar state na sessão (mais portátil e evita depender de métodos do client)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['oauth2state'] = $state;

        // Criar URL de autenticação e anexar explicitamente o state se necessário
        $authUrl = $this->client->createAuthUrl();
        // Se o state não estiver presente na query, anexa-lo
        if (strpos($authUrl, 'state=') === false) {
            $authUrl .= (strpos($authUrl, '?') === false ? '?' : '&') . 'state=' . urlencode($state);
        }

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
                // Preferir a classe namespaced; o serviço Oauth2 está em \Google\Service\Oauth2
                if (class_exists(Oauth2Service::class)) {
                    $googleService = new Oauth2Service($this->client);
                } elseif (class_exists('Google_Service_Oauth2')) {
                    // fallback para alias legada, caso exista
                    // Tenta incluir manualmente o arquivo da classe se não estiver carregado
                    $oauth2Path = __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/google/apiclient-services/src/Google/Service/Oauth2.php';
                    if (!class_exists('Google_Service_Oauth2')) {
                        if (file_exists($oauth2Path)) {
                            require_once $oauth2Path;
                        } else {
                            throw new Exception('Arquivo Oauth2.php não encontrado em: ' . $oauth2Path);
                        }
                    }
                    $googleService = new \Google_Service_Oauth2($this->client);
                } else {
                    throw new Exception('Servico Oauth2 do Google nao encontrado. Verifique lib/google-api-php-client-v2.18.3-PHP8.3/vendor.');
                }
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
                header("Location: /marktour/View/Auth/Register.php?erros=Erro na autenticação com Google");
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