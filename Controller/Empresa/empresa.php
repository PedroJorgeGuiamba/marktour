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

                $id_utilizador = isset($_POST['id_utilizador']) ? (int)$_POST['id_utilizador'] : null; // Agora vem do POST
                $id_localizacao = isset($_POST['id_localizacao']) ? (int)$_POST['id_localizacao'] : null; // Agora vem do POST

                if (!$id_utilizador || !$id_localizacao) {
                    throw new Exception("IDs de utilizador ou localização não fornecidos.");
                }

                $empresa = new Empresa();
                $conexao = new Conector();
                $conn = $conexao->getConexao();

                $empresa->setId_utilizador($id_utilizador);
                $empresa->setId_localizacao($id_localizacao);
                $empresa->setNome($nome);
                $empresa->setNuit($nuit);
                $empresa->setDescricao($descricao);
                $empresa->setEstado('aprovado');

                if ($empresa->salvar($conn)) { // Passar a conexão como parâmetro
                    $id_empresa = $conn->insert_id; // Obter o ID da mesma conexão

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
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$controller = new FormularioDeEmpresa();
$controller->criarEmpresa();
