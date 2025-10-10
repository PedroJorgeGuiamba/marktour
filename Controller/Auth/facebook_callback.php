<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Libraries/facebook-php-graph-sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';

$fb = new \Facebook\Facebook([
    'app_id' => getenv('FACEBOOK_APP_ID'),
    'app_secret' => getenv('FACEBOOK_APP_SECRET'),
    'default_graph_version' => getenv('FACEBOOK_GRAPH_VERSION'),
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken(FACEBOOK_REDIRECT_URI);
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    header('Location: /marktour/View/Registro.php?error=' . urlencode('Erro na resposta do Facebook: ' . $e->getMessage()));
    exit;
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    header('Location: /marktour/View/Registro.php?error=' . urlencode('Erro no SDK do Facebook: ' . $e->getMessage()));
    exit;
}

if (!isset($accessToken)) {
    header('Location: /marktour/View/Registro.php?error=access_token_missing');
    exit;
}

try {
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
    $user = $response->getGraphUser();

    $fbId = $user['id'];
    $name = $user['name'];
    $email = $user['email'] ?? 'email_nao_fornecido_' . $fbId . '@example.com';

    $conexao = new Conector();
    $conn = $conexao->getConexao();
    $criptografia = new Criptografia();

    $sql = "SELECT id_utilizador, tipo FROM utilizador WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $email_encripted = $criptografia->criptografar($email);
    $stmt->bind_param("s", $email_encripted);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $tipo);
        $stmt->fetch();
        $_SESSION['email'] = $email;
        $_SESSION['tipo'] = $tipo;
        $_SESSION['usuario_id'] = $id;

        setcookie('user_email', $email, time() + 3600, "/");
        $sessaoId = iniciarSessao($id);
        registrarAtividade($sessaoId, "Login com Facebook realizado com sucesso", "LOGIN_FACEBOOK");

        if ($tipo === 'cliente') {
            header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
        } elseif ($tipo === 'empresa') {
            header("Location: /marktour/View/Empresa/localizacaoEmpresa.php");
        }
        exit;
    }

    $tipo = 'cliente';
    $senha = password_hash(uniqid(), PASSWORD_DEFAULT);
    $sql = "INSERT INTO utilizador (nome, email, senha, tipo, facebook_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email_encripted, $senha, $tipo, $fbId);
    $sucesso = $stmt->execute();

    if ($sucesso) {
        $id = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['tipo'] = $tipo;
        $_SESSION['usuario_id'] = $id;

        setcookie('user_email', $email, time() + 3600, "/");
        $sessaoId = iniciarSessao($id);
        registrarAtividade($sessaoId, "Cadastro com Facebook realizado com sucesso", "REGISTRO_FACEBOOK");

        header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
        exit;
    } else {
        header("Location: /marktour/View/Registro.php?error=registration_failed");
        exit;
    }
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    header("Location: /marktour/View/Registro.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>