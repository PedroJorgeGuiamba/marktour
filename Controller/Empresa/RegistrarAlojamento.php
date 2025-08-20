<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Model/Alojamento.php';

class RegistrarAlojamento
{
    public function registrar()
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                
                $alojamento = new Alojamento();

                $id_empresa = isset($_POST['id_empresa']) ? (int)$_POST['id_empresa'] : null; // Agora vem do POST

                if (!$id_empresa) {
                    throw new Exception("IDs de empresa não fornecidos.");
                }

                $nome = $_POST['nome'] ?? '';
                $tipo = $_POST['tipo'] ?? 'hotel'; // padrão
                $descricao = $_POST['descricao'] ?? '';
                $precoPorNoite = $_POST['precoPorNoite'] ?? null;
                $numQuartos = $_POST['numeroDeQuartos'] ?? null;

                $alojamento->setId_empresa( $id_empresa );
                $alojamento->setNome( $nome );
                $alojamento->setTipo( $tipo );
                $alojamento->setDescricao( $descricao );
                $alojamento->setPrecoPorNoite( $precoPorNoite );
                $alojamento->setNumQuartos( $numQuartos );

                if ($alojamento->salvar()) {
                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "Alojamento da empresa criado", "CRIACAO");
                    }

                    header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
                    exit();
                } else {
                    echo "Erro ao criar Alojamento da empresa.";
                }
            } catch (Exception $e) {
                echo "Erro no sistema: " . $e->getMessage();
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$erros = '';
$registro = new RegistrarAlojamento();
$registro->registrar();