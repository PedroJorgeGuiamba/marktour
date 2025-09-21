<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';

class AuthController
{
    private $conn;
    private $error;
    private $loginAttempts = 0;
    private $criptografia;

    public function __construct() {
        $conexao = new Conector();
        $this->conn = $conexao->getConexao();
        $this->error = '';
        $this->loginAttempts = $_SESSION['login_attempts'] ?? 0;
        $this->criptografia = new Criptografia();
    }
    public function verificar()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return "Método inválido.";
        }

        if ($this->loginAttempts >= 5) {
            $this->error = "Muitas tentativas. Espere 5 minutos.";
            $_SESSION['login_attempts'] = $this->loginAttempts;
            return $this->error;
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $erros = '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error = "Email inválido.";
            $this->loginAttempts++;
            $_SESSION['login_attempts'] = $this->loginAttempts;
            registrarAtividade(null, "Email inválido: " . $this->criptografia->criptografar($email), "LOGIN_FAILED");
            // registrarAtividade(null, "Email inválido: ", "LOGIN_FAILED");
            return $this->error;
        }

        if (empty($senha)) {
            $this->error = "Senha obrigatória.";
            $this->loginAttempts++;
            $_SESSION['login_attempts'] = $this->loginAttempts;
            return $this->error;
        }

        $conexao = new Conector();
        $conn = $conexao->getConexao();

        $sql = "SELECT * FROM utilizador WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // $sql = "SELECT id_utilizador, email, senha, tipo FROM utilizador";
        // $result = $this->conn->query($sql);

        // $userEncontrado = false;
        // $row = null;

        // if ($result && $result->num_rows > 0) {
        //     while ($row = $result->fetch_assoc()) {
        //         $email_descriptografado = $this->criptografia->descriptografar($row['email']);
        //         if ($email_descriptografado === $email) {
        //             $userEncontrado = true;
        //             break;
        //         }
        //     }
        // }

        // if ($userEncontrado && $row) {
        //     if (password_verify($senha, $row['senha'])) {
        //         $_SESSION['login_attempts'] = 0; // Reset

        //         $otp = random_int(100000, 999999);
        //         $expira = date("Y-m-d H:i:s", time() + 300);

        //         $sqlOtp = "INSERT INTO user_otps (user_id, otp_code, expires_at, created_at) VALUES (?, ?, ?, NOW())";
        //         $stmtOtp = $this->conn->prepare($sqlOtp);
        //         $stmtOtp->bind_param("iis", $row['id'], $otp, $expira);

        //         if ($stmtOtp->execute()) {
        //             $stmtOtp->close();

        //             // Envio email via Python
        //             $escapedEmail = escapeshellarg($email);
        //             $escapedOtp = escapeshellarg($otp);
        //             $pythonPath = __DIR__ . '/AuthMailSender.py';
        //             $command = "python $pythonPath $escapedEmail $escapedOtp 2>&1";
        //             $output = shell_exec($command);
        //             if (strpos($output ?? '', 'Erro') !== false) {
        //                 error_log("Falha email OTP: $output");
        //             }

        //             $_SESSION['pending_user_id'] = $this->criptografia->criptografar($row['id']);
        //             $_SESSION['user_email'] = $this->criptografia->criptografar($row['email']);
        //             $_SESSION['tipo'] = $this->criptografia->criptografar(strtolower(trim($row['tipo'] ?? '')));
        //             error_log("DEBUG - AuthController: tipo: '" . $this->criptografia->criptografar($row['tipo']) . "' para {$email}");
        //             header("Location: /marktour/View/Auth/ValidarUser.php");
        //             exit();
        //         } else {
        //             $this->error = "Erro ao gerar OTP.";
        //             return $this->error;
        //         }
        //     } else {
        //         $this->error = "Senha incorreta.";
        //         $this->loginAttempts++;
        //         $_SESSION['login_attempts'] = $this->loginAttempts;
        //         registrarAtividade(null, "Senha errada para " . $this->criptografia->criptografar($email), "LOGIN_FAILED");
        //         return $this->error;
        //     }
        // } else {
        //     $this->error = "Email não encontrado.";
        //     $this->loginAttempts++;
        //     $_SESSION['login_attempts'] = $this->loginAttempts;
        //     // registrarAtividade(null, "Email não registrado: " . $this->criptografia->criptografar($email), "LOGIN_FAILED");
        //     return $this->error;
        // }
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($senha === $row['senha']) {
                session_start();
                $_SESSION['email'] = $row['email'];
                $_SESSION['tipo'] = $row['tipo'];
                $_SESSION['usuario_id'] = $row['id_utilizador'];

                setcookie('user_email', $email, time() + 3600, "/");

                $sessaoId = iniciarSessao($row['id_utilizador']);
                registrarAtividade($sessaoId, "Login realizado com sucesso", "LOGIN");

                $tipo = strtolower($row['tipo']);

                if ($tipo === 'admin') {
                    header("Location: /marktour/View/Admin/portalDoAdmin.php");
                    exit();
                } elseif ($tipo === 'cliente') {
                    header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
                    exit();
                } elseif ($tipo === 'empresa') {
                    header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
                    exit();
                } else {
                        $erros .= "Tipo de usuário desconhecido Ou Senha incorreta..<br>";
                }
            } else {
                $erros .= "Email não encontrado.<br>";
            }
        
            return $erros;
        }



    }
}

$erros = '';
$login = new AuthController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = $login->verificar();
}


        