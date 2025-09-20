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

        // $otp = random_int(100000, 999999);
        // $expira = date("Y-m-d H:i:s", time() + 300);

        // $sqlOtp = "INSERT INTO user_otps (user_id, otp_code, expires_at, created_at) VALUES (?, ?, ?, NOW())";
        // $stmtOtp = $this->conn->prepare($sqlOtp);
        // $stmtOtp->bind_param("iis", $row['id'], $otp, $expira);

        // if ($stmtOtp->execute()) {
        //     $stmtOtp->close();

        //     // Envio email via Python
        //     $escapedEmail = escapeshellarg($email);
        //     $escapedOtp = escapeshellarg($otp);
        //     $pythonPath = __DIR__ . '/AuthMailSender.py';
        //     $command = "python $pythonPath $escapedEmail $escapedOtp 2>&1";
        //     $output = shell_exec($command);
        //     if (strpos($output ?? '', 'Erro') !== false) {
        //         error_log("Falha email OTP: $output");
        //     }

        //     $_SESSION['pending_user_id'] = $this->criptografia->criptografar($row['id']);
        //     $_SESSION['user_email'] = $this->criptografia->criptografar($row['email']);
        //     $_SESSION['role'] = $this->criptografia->criptografar(strtolower(trim($row['role'] ?? '')));
        //     error_log("DEBUG - AuthController: Role: '" . $this->criptografia->criptografar($row['role']) . "' para {$email}");
        //     header("Location: /estagio/View/Auth/ValidarUser.php");
        //     exit();
        // } else {
        //     $this->error = "Erro ao gerar OTP.";
        //     return $this->error;
        // }

        return $erros;
    }
}

$erros = '';
$registro = new AuthRegisterController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = $registro->registrar();
}
