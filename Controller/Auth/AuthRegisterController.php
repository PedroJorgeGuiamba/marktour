<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';

class AuthRegisterController
{
    public function registrar()
    {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['password'] ?? '';
        $tipo = $_POST['tipo'] ?? 'cliente'; // padrão

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

        // Inserir novo usuário
        $sql = "INSERT INTO utilizador (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);
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
                header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
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
