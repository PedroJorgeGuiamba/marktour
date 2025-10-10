<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';

class AuthRegisterController
{
    
    private $conn;
    private $criptografia;

    public function __construct() {
        $conexao = new Conector();
        $this->conn = $conexao->getConexao();
        $this->criptografia = new Criptografia();
    }

    public function registrar()
    {
        $nome = filter_var($_POST['nome'] ?? '', FILTER_SANITIZE_EMAIL);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $senha = filter_var($_POST['password'] ?? '', FILTER_SANITIZE_EMAIL);
        $tipo = filter_var($_POST['tipo'] ?? 'cliente', FILTER_SANITIZE_EMAIL);

        $erros = '';

        if (empty($nome) || empty($email) || empty($senha)) {
            return "Preencha todos os campos obrigatórios.<br>";
        }

        $conexao = new Conector();
        $conn = $conexao->getConexao();

        // Verificar se o e-mail já existe
        $sql = "SELECT id_utilizador FROM utilizador WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return "E-mail já cadastrado.<br>";
        }

        $stmt->close();

        $email_encripted = $this->criptografia->criptografar($email);
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir novo usuário
        $sql = "INSERT INTO utilizador (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email_encripted, $hashedPassword, $tipo);
        $sucesso = $stmt->execute();

        if ($sucesso) {
            $id = $stmt->insert_id;

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['tipo'] = $tipo;
            $_SESSION['usuario_id'] = $id;

            setcookie('user_email', $email, time() + 3600, "/");

            $sessaoId = iniciarSessao($id);
            registrarAtividade($sessaoId, "Cadastro realizado com sucesso", "REGISTRO");

            if ($tipo === 'cliente') {
                header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
                exit();
            } elseif ($tipo === 'empresa') {
                header("Location: /marktour/View/Empresa/localizacaoEmpresa.php");
                exit();
            } else {
                $erros .= "Tipo de usuário desconhecido Ou Senha incorreta..<br>";
            }
        } else {
            $erros .= "Erro ao registrar usuário.<br>";
        }
        return $erros;
    }
}

$erros = '';
$registro = new AuthRegisterController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = $registro->registrar();
}
