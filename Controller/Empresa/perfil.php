<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

// Verificar se o usuário está logado e é do tipo empresa
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'empresa') {
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

    $sql_empresa = "SELECT id_empresa, id_localizacao, nome, nuit, descricao, estado_verificacao, data_registo FROM empresa WHERE id_utilizador = ?";
    $stmt_empresa = $conn->prepare($sql_empresa);
    $stmt_empresa->bind_param("i", $id_utilizador);
    $stmt_empresa->execute();
    $dados['empresa'] = $stmt_empresa->get_result()->fetch_assoc();
    $stmt_empresa->close();

    if ($dados['empresa']) {
        $id_empresa = $dados['empresa']['id_empresa'];
        $id_localizacao = $dados['empresa']['id_localizacao'];

        $sql_localizacao = "SELECT provincia, distrito, bairro, posto_administrativo, localidade, avenida, rua, andar, endereco_detalhado, codigo_postal FROM localizacao WHERE id_localizacao = ?";
        $stmt_localizacao = $conn->prepare($sql_localizacao);
        $stmt_localizacao->bind_param("i", $id_localizacao);
        $stmt_localizacao->execute();
        $dados['localizacao'] = $stmt_localizacao->get_result()->fetch_assoc();
        $stmt_localizacao->close();

        $sql_contacto = "SELECT telefone1, telefone2, telefone3, fax1, fax2, email, website FROM contacto_empresa WHERE id_empresa = ?";
        // $sql_contacto = "SELECT telefone1, telefone2, telefone3, fax1, fax2, email, website FROM contacto_empresa";
        $stmt_contacto = $conn->prepare($sql_contacto);
        $stmt_contacto->bind_param("i", $id_empresa);
        $stmt_contacto->execute();
        $dados['contacto'] = $stmt_contacto->get_result()->fetch_assoc();
        $stmt_contacto->close();
    }

    return $dados;
}

function atualizarLocalizacao($conn, $id_localizacao, $dados) {
    $sql = "UPDATE localizacao SET provincia = ?, distrito = ?, bairro = ?, posto_administrativo = ?, localidade = ?, avenida = ?, rua = ?, andar = ?, endereco_detalhado = ?, codigo_postal = ? WHERE id_localizacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $dados['provincia'], $dados['distrito'], $dados['bairro'], $dados['posto_administrativo'], $dados['localidade'], $dados['avenida'], $dados['rua'], $dados['andar'], $dados['endereco_detalhado'], $dados['codigo_postal'], $id_localizacao);
    return $stmt->execute();
}

function atualizarEmpresa($conn, $id_empresa, $dados) {
    $sql = "UPDATE empresa SET nome = ?, nuit = ?, descricao = ?, estado_verificacao = ? WHERE id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $dados['nome'], $dados['nuit'], $dados['descricao'], $dados['estado_verificacao'], $id_empresa);
    return $stmt->execute();
}

function atualizarContato($conn, $id_empresa, $dados) {
    $sql = "UPDATE contacto_empresa SET telefone1 = ?, telefone2 = ?, telefone3 = ?, fax1 = ?, fax2 = ?, email = ?, website = ? WHERE id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $dados['telefone1'], $dados['telefone2'], $dados['telefone3'], $dados['fax1'], $dados['fax2'], $dados['email'], $dados['website'], $id_empresa);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $acao = $dados['acao'] ?? '';

    if ($acao === 'atualizar_localizacao') {
        $id_localizacao = $dados['id_localizacao'];
        $result = atualizarLocalizacao($conn, $id_localizacao, $dados);
        echo json_encode(['success' => $result]);
    } elseif ($acao === 'atualizar_empresa') {
        $id_empresa = $dados['id_empresa'];
        $result = atualizarEmpresa($conn, $id_empresa, $dados);
        echo json_encode(['success' => $result]);
    } elseif ($acao === 'atualizar_contato') {
        $id_empresa = $dados['id_empresa'];
        $result = atualizarContato($conn, $id_empresa, $dados);
        echo json_encode(['success' => $result]);
    }
    exit();
} else {
    $dados = recuperarInformacoes($conn, $id_utilizador);
    header('Content-Type: application/json');
    echo json_encode($dados);
}

$conn->close();
?>