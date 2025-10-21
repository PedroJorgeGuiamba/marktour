<?php
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Helpers/Sessao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Model/Passeio.php';

class RegistrarPasseio
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

                 // Configuração do upload
                $uploadDir = "../../uploads/alojamentos/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $imagemPath = null;
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
                    $imagemName = basename($_FILES['imagem']['name']);
                    $imagemExt = strtolower(pathinfo($imagemName, PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($imagemExt, $allowedExts)) {
                        $newImagemName = uniqid() . '.' . $imagemExt;
                        $targetFile = $uploadDir . $newImagemName;
                        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                            $imagemPath = "/marktour/uploads/passeios/" . $newImagemName;
                        }
                    }
                }

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
                    throw new Exception("O nome do passeio é obrigatório.");
                }
                $duracao = $_POST['duracao'] ?? 'hotel'; // padrão
                $descricao = $_POST['descricao'] ?? '';
                $preco = $_POST['preco'] ?? null;
                $dataHora = isset($_POST['dataHora']) ? $_POST['dataHora'] : null;
                $local = $_POST['local'] ?? null;

                $dateTimeObj = new DateTime($dataHora);

                $formattedDateTime = $dateTimeObj->format('Y-m-d H:i:s');

                $passeio = new Passeio();
                $passeio->setId_empresa( $id_empresa );
                $passeio->setNome( $nome);
                $passeio->setDuracao( $duracao);
                $passeio->setDescricao( $descricao);
                $passeio->setPreco( $preco);
                $passeio->setDataHora( $formattedDateTime);
                $passeio->setLocal($local);
                $passeio->setImagemPath($imagemPath);

                if ($passeio->salvar()) {
                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "passeio da empresa criado", "CRIACAO");
                    }

                    header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
                    exit();
                } else {
                    echo "Erro ao criar passeio da empresa.";
                    exit();
                }
            } catch (Exception $e) {
                echo "Erro no sistema: " . $e->getMessage();
                exit();
            }
        } else {
            echo "Método inválido.";
            exit();
        }
    }
}

$erros = '';
$registro = new RegistrarPasseio();
$registro->registrar();