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
                $empresa = new Empresa();
                $conexao = new Conector();
                $conn = $conexao->getConexao();
                $id_utilizador = $conn->insert_id;

                $empresa->setId_utilizador($id_utilizador);
                $empresa->setNome($nome);
                $empresa->setNuit($nuit);
                $empresa->setDescricao($descricao);
                $empresa->setEstado('ativo');

                if ($empresa->salvar()) {
                    $id_empresa = $conn->insert_id;

                    header("Location: /View/estagio/formulario_localizacao.php?id=" . $id_empresa);
                    exit();
                } else {
                    echo "Erro ao criar empresa.";
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
