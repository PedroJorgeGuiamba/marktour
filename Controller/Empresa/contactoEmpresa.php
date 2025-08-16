<?php
session_start();

require_once __DIR__ . '/../../Model/Empresa.php';
require_once __DIR__ . '/../../Model/Contacto.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Conexao/conector.php';

class FormularioDeEmpresa
{
    public function criarEmpresa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                
                $contato = new Contato_empresa();

                $id_empresa = isset($_POST['id_empresa']) ? (int)$_POST['id_empresa'] : null; // Agora vem do POST

                if (!$id_empresa) {
                    throw new Exception("IDs de empresa não fornecidos.");
                }

                $telefone1 = trim($_POST['telefone1'] ?? '');
                $telefone2 = trim($_POST['telefone2'] ?? '');
                $telefone3 = trim($_POST['telefone3'] ?? '');
                $fax1 = trim($_POST['fax1'] ?? '');
                $fax2 = trim($_POST['fax2'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $website = trim($_POST['website'] ?? '');

                $contato->setId_empresa($id_empresa);
                $contato->setTelefone1($telefone1);
                $contato->setTelefone2($telefone2);
                $contato->setTelefone3($telefone3);
                $contato->setFax1($fax1);
                $contato->setFax2($fax2);
                $contato->setEmail($email);
                $contato->setWebsite($website);

                if ($contato->salvar()) {
                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "Contato da empresa criado", "CRIACAO");
                    }

                    header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
                    exit();
                } else {
                    echo "Erro ao criar contato da empresa.";
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
