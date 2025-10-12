<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Helpers/Criptografia.php';

class ProcessarTipoController
{
    private $conn;
    private $criptografia;

    public function __construct()
    {
        $conexao = new Conector();
        $this->conn = $conexao->getConexao();
        $this->criptografia = new Criptografia();
    }

    public function processar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['google_email'])) {
            $email = $_POST['email'];
            $nome = $_POST['nome'];
            $tipo = filter_var($_POST['tipo'] ?? 'cliente', FILTER_SANITIZE_STRING);

            $sql = "SELECT id_utilizador FROM utilizador WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                header("Location: /marktour/View/Auth/Register.php?erros=E-mail já cadastrado");
                exit();
            }

            $stmt->close();

            $email_encripted = $this->criptografia->criptografar($email);
            $hashedPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilizador (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $email_encripted, $hashedPassword, $tipo);
            $sucesso = $stmt->execute();

            if ($sucesso) {
                $id = $stmt->insert_id;

                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = $tipo;
                $_SESSION['usuario_id'] = $id;

                setcookie('user_email', $email, time() + 3600, "/");

                $sessaoId = iniciarSessao($id);
                registrarAtividade($sessaoId, "Cadastro com Google realizado com sucesso", "REGISTRO_GOOGLE");

                if ($tipo === 'cliente') {
                    header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
                    exit();
                } elseif ($tipo === 'empresa') {
                    header("Location: /marktour/View/Empresa/localizacaoEmpresa.php");
                    exit();
                }
            } else {
                header("Location: /marktour/View/Auth/Register.php?erros=Erro ao registrar usuário");
                exit();
            }
        } else {
            header("Location: /marktour/View/Auth/Register.php");
            exit();
        }
    }
}

$controller = new ProcessarTipoController();
$controller->processar();