<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';

class AuthController
{
    public function verificar()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['password'] ?? '';
        $erros = '';

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
