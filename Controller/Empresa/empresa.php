<?php
session_start();

require_once __DIR__ . '/../../Model/Usuario.php';
require_once __DIR__ . '/../../Model/Empresa.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Conexao/conector.php';

class FormularioDeEmpresa
{
    public function criarEmpresa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nome = trim($_POST['nome'] ?? '');
                $nuit = trim($_POST['nuit'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $id_utilizador = isset($_POST['id_utilizador']) ? (int)$_POST['id_utilizador'] : null;
                $id_localizacao = isset($_POST['id_localizacao']) ? (int)$_POST['id_localizacao'] : null;
                $numAlvara = trim($_POST['numAlvara'] ?? '');
                if (!$id_utilizador || !$id_localizacao) {
                    throw new Exception("IDs de utilizador ou localização não fornecidos.");
                }

                $empresa = new Empresa();
                $conexao = new Conector();
                $conn = $conexao->getConexao();

                // Configuração do diretório de upload
                $uploadDirNuit = "../../uploads/empresa/nuit/";
                if (!file_exists($uploadDirNuit)) {
                    mkdir($uploadDirNuit, 0777, true);
                }

                // Processar imagem do NUIT
                $imagemNuitPath = null;
                if (isset($_FILES['imagem_nuit_path']) && $_FILES['imagem_nuit_path']['error'] == UPLOAD_ERR_OK) {
                    $imagemNuitName = basename($_FILES['imagem_nuit_path']['name']);
                    $imagemNuitExt = strtolower(pathinfo($imagemNuitName, PATHINFO_EXTENSION));
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    if (!in_array($_FILES['imagem_nuit_path']['type'], $allowedTypes)) {
                        throw new Exception("Tipo de arquivo do NUIT não permitido.");
                    }
                    $newImagemNuitName = uniqid() . '.' . $imagemNuitExt;
                    $targetFileNuit = $uploadDirNuit . $newImagemNuitName;
                    if (move_uploaded_file($_FILES['imagem_nuit_path']['tmp_name'], $targetFileNuit)) {
                        $imagemNuitPath = "/marktour/uploads/empresa/nuit/" . $newImagemNuitName;
                    } else {
                        throw new Exception("Erro ao fazer upload do documento do NUIT.");
                    }
                }

                $uploadDirAlvara = "../../uploads/empresa/alvara/";
                if (!file_exists($uploadDirAlvara)) {
                    mkdir($uploadDirAlvara, 0777, true);
                }
                // Processar imagem do Alvara
                $imagemAlvaraPath = null;
                if (isset($_FILES['imagem_alvara_path']) && $_FILES['imagem_alvara_path']['error'] == UPLOAD_ERR_OK) {
                    $imagemAlvaraName = basename($_FILES['imagem_alvara_path']['name']);
                    $imagemAlvaraExt = strtolower(pathinfo($imagemAlvaraName, PATHINFO_EXTENSION));
                    if (!in_array($_FILES['imagem_alvara_path']['type'], $allowedTypes)) {
                        throw new Exception("Tipo de arquivo do Alvara não permitido.");
                    }
                    $newImagemAlvaraName = uniqid() . '.' . $imagemAlvaraExt;
                    $targetFileAlvara = $uploadDirAlvara . $newImagemAlvaraName;
                    if (move_uploaded_file($_FILES['imagem_alvara_path']['tmp_name'], $targetFileAlvara)) {
                        $imagemAlvaraPath = "/marktour/uploads/empresa/alvara/" . $newImagemAlvaraName;
                    } else {
                        throw new Exception("Erro ao fazer upload do documento do Alvara.");
                    }
                }

                $empresa->setId_utilizador($id_utilizador);
                $empresa->setId_localizacao($id_localizacao);
                $empresa->setNome($nome);
                $empresa->setNuit($nuit);
                $empresa->setDescricao($descricao);
                $empresa->setEstado('aprovado');
                $empresa->setNumAlvara($numAlvara);
                $empresa->setImagemNuitPath($imagemNuitPath);
                $empresa->setImagemAlvaraPath($imagemAlvaraPath);

                if ($empresa->salvar($conn)) {
                    $id_empresa = $conn->insert_id;

                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "Empresa criada", "CRIACAO");
                    }
                    header("Location: /marktour/View/Empresa/contactoEmpresa.php?id_empresa=" . $id_empresa);
                    exit();
                } else {
                    echo "Erro ao criar Empresa. Verifique os dados ou a conexão.";
                }
            } catch (Exception $e) {
                echo "Erro no sistema: " . $e->getMessage();
                $erroMsg = urlencode($e->getMessage());
                header("Location: /marktour/View/Erro/erro.php?msg=" . $erroMsg);
                exit();
            }
        } else {
            echo "Método inválido.";
            header("Location: /marktour/View/Erro/erro.php?msg=" . urlencode("Método inválido."));
            exit();
        }
    }
}

$controller = new FormularioDeEmpresa();
$controller->criarEmpresa();
