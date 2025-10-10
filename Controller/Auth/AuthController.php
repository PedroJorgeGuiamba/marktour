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

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'secret' => getenv('RECAPTCHA'),
                'response' => $_POST['g-recaptcha-response'] ?? ''
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $responseArray = json_decode($response, true);
        if (!isset($responseArray['success']) || !$responseArray['success']) {
            $this->error = "Falha na validação do reCAPTCHA.";
            $this->loginAttempts++;
            $_SESSION['login_attempts'] = $this->loginAttempts;
            
            // Opcional: Registrar códigos de erro do reCAPTCHA, se houver
            $errorCodes = $responseArray['error-codes'] ?? ['unknown-error'];
            registrarAtividade(null, "Falha no reCAPTCHA: " . implode(', ', $errorCodes), "LOGIN_FAILED");
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


        