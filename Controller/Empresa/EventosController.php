<?php
session_start();

require_once __DIR__ . '/../../Model/Evento.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Conexao/conector.php';

class EventosController
{
    public function criarEvento()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $evento = new Evento();
                $id_utilizador = $_SESSION['usuario_id'] ?? null;

                if (!$id_utilizador) {
                    throw new Exception("ID do utilizador não encontrado.");
                }

                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $data_evento = trim($_POST['data_evento'] ?? '');
                $hora_inicio = trim($_POST['hora_inicio'] ?? '');
                $hora_fim = trim($_POST['hora_fim'] ?? '');
                $local = trim($_POST['local'] ?? '');
                $organizador = trim($_POST['organizador'] ?? '');
                $status = trim($_POST['status'] ?? 'ativo');

                $evento->setNome($nome);
                $evento->setDescricao($descricao);
                $evento->setData_evento($data_evento);
                $evento->setHora_inicio($hora_inicio);
                $evento->setHora_fim($hora_fim);
                $evento->setLocal($local);
                $evento->setOrganizador($organizador);
                $evento->setStatus($status);

                $conexao = new Conector();
                $conn = $conexao->getConexao();

                if ($evento->salvar($conn)) {
                    $id_evento = $conn->insert_id;

                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "Evento criado", "CRIACAO");
                    }

                    header("Location: /marktour/View/Empresa/empresa.php?id_utilizador=" . $id_utilizador . "&id_evento=" . $id_evento);
                    exit();
                } else {
                    echo "Erro ao criar evento. Verifique os dados ou a conexão.";
                }
            } catch (Exception $e) {
                echo "Erro no sistema: " . $e->getMessage();
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$controller = new EventosController();
$controller->criarEvento();