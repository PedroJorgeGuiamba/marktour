<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

// Verificar se o usuário está logado e é do tipo empresa
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: /marktour/View/Auth/Login.php");
    exit();
}

$id_utilizador = $_SESSION['usuario_id'];
$conexao = new Conector();
$conn = $conexao->getConexao();

function recuperarInformacoes($conn, $id_utilizador) {
    $dados = [];

    $sql_utilizador = "SELECT nome, email FROM utilizador WHERE id_utilizador = ?";
    $stmt_utilizador = $conn->prepare($sql_utilizador);
    $stmt_utilizador->bind_param("i", $id_utilizador);
    $stmt_utilizador->execute();
    $dados['utilizador'] = $stmt_utilizador->get_result()->fetch_assoc();
    $stmt_utilizador->close();

    return $dados;
}


$conn->close();
?>