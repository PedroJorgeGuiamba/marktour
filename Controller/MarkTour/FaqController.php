<?php
session_start();

require_once __DIR__ . '/../../Model/Faq.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Conexao/conector.php';

class FaqController
{
    public function registarPergunta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!isset($_SESSION['id_utilizador'])) {
                    throw new Exception("Utilizador não autenticado.");
                }

                $id_utilizador = $_SESSION['id_utilizador'];
                $pergunta = trim($_POST['pergunta'] ?? '');
                // Validar o campo pergunta
                if (empty($pergunta)) {
                    throw new Exception("O campo pergunta é obrigatório.");
                }
                
                $faq = new Faq();
                $faq->setPergunta($pergunta);
                $faq->setId_utilizador($id_utilizador);

                // Conectar ao banco de dados
                $conexao = new Conector();
                $conn = $conexao->getConexao();

                // Iniciar transação
                mysqli_begin_transaction($conn);

                // Salvar a pergunta
                if (!$faq->salvar($conn)) {
                    throw new Exception("Erro ao salvar a pergunta no banco de dados.");
                }

                // Registrar atividade
                if (isset($_SESSION['sessao_id'])) {
                    registrarAtividade($_SESSION['sessao_id'], "Pergunta criada pelo utilizador nr " . $id_utilizador, "CRIACAO");
                }

                // Confirmar transação
                mysqli_commit($conn);

                // Redirecionar para o portal do utilizador
                header("Location: /marktour/View/Utilizador/portalDoUtilizador.php");
                exit();
            } catch (Exception $e) {
                // Reverter transação
                if (isset($conn)) {
                    mysqli_rollback($conn);
                }

                error_log("Erro no FaqController: " . $e->getMessage());
                $erroMsg = urlencode($e->getMessage());
                header("Location: /marktour/View/Erro/erro.php?msg=$erroMsg");
                exit();
            }
        }
    }
}

$controller = new FaqController();
$controller->registarPergunta();