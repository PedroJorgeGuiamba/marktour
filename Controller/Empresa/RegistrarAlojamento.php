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

                session_start();

                if (!isset($_SESSION['id_utilizador'])) {
                    throw new Exception("Utilizador não autenticado.");
                }

                $id_utilizador = $_SESSION['id_utilizador'];

                // Buscar empresa pelo utilizador logado
                $conexao = new Conector();
                $conn = $conexao->getConexao();

                $stmt = $conn->prepare("SELECT id_empresa FROM empresa WHERE id_utilizador = ?");
                $stmt->bind_param("i", $id_utilizador);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    throw new Exception("Nenhuma empresa associada ao utilizador.");
                }

                $empresa = $result->fetch_assoc();
                $id_empresa = $empresa['id_empresa'];
                
                $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
                if (empty($nome)) {
                    throw new Exception("O nome do alojamento é obrigatório.");
                }
                $tipo = $_POST['tipo'] ?? 'hotel'; // padrão
                $descricao = $_POST['descricao'] ?? '';
                $precoPorNoite = $_POST['precoPorNoite'] ?? null;
                $numQuartos = $_POST['numeroDeQuartos'] ?? null;

                $alojamento = new Alojamento();
                $alojamento->setId_empresa( $id_empresa );
                $alojamento->setNome( $nome);
                $alojamento->setTipo( $tipo);
                $alojamento->setDescricao( $descricao);
                $alojamento->setPrecoPorNoite( $precoPorNoite);
                $alojamento->setNumQuartos( $numQuartos);

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