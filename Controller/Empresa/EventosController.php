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
                if (!isset($_SESSION['id_utilizador'])) {
                    throw new Exception("Utilizador não autenticado.");
                }

                $id_utilizador = $_SESSION['id_utilizador'];

                $evento = new Evento();
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

                $conn->begin_transaction();

                $stmt = $conn->prepare("SELECT id_empresa FROM empresa WHERE id_utilizador = ?");
                $stmt->bind_param("i", $id_utilizador);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows === 0) {
                    throw new Exception("Nenhuma empresa associada ao utilizador.");
                }

                $empresa = $result->fetch_assoc();
                $id_empresa = $empresa['id_empresa'];
                $evento->setId_empresa($id_empresa);

                $uploadDir = __DIR__ . '/../../uploads/eventos/';
                if (!file_exists($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        throw new Exception("Erro ao criar o diretório de upload: $uploadDir");
                    }
                }

                if (!is_writable($uploadDir)) {
                    throw new Exception("O diretório de upload não tem permissões de escrita: $uploadDir");
                }

                $midias = [];
                $uploadedFiles = [];
                if (!empty($_FILES['midias']['name'][0])) {
                    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $allowedVideoTypes = ['video/mp4', 'video/mpeg', 'video/webm'];
                    $maxFileSize = 10 * 1024 * 1024; // 10MB

                    foreach ($_FILES['midias']['name'] as $key => $name) {
                        $tmp_name = $_FILES['midias']['tmp_name'][$key];
                        $type = $_FILES['midias']['type'][$key];
                        $size = $_FILES['midias']['size'][$key];
                        $error = $_FILES['midias']['error'][$key];

                        if ($error === UPLOAD_ERR_OK && $size <= $maxFileSize) {
                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            $filename = uniqid('evento_') . '.' . $ext;
                            $destination = $uploadDir . $filename;

                            if (in_array($type, $allowedImageTypes)) {
                                $tipo = 'imagem';
                            } elseif (in_array($type, $allowedVideoTypes)) {
                                $tipo = 'video';
                            } else {
                                throw new Exception("Tipo de arquivo não permitido: $name");
                            }

                            if (!move_uploaded_file($tmp_name, $destination)) {
                                throw new Exception("Erro ao mover o arquivo: $name");
                            }

                            $uploadedFiles[] = $destination;

                            $midias[] = [
                                'tipo' => $tipo,
                                'path' => '/marktour/uploads/eventos/' . $filename
                            ];
                        } elseif ($error !== UPLOAD_ERR_NO_FILE) {
                            throw new Exception("Erro no upload do arquivo: $name (Erro: $error)");
                        }
                    }
                }

                $id_evento = $evento->salvar($conn);
                if (!$id_evento) {
                    throw new Exception("Erro ao salvar o evento no banco de dados.");
                }

                if (!empty($midias)) {
                    $evento->salvarMidias($conn, $id_evento, $midias);
                }

                if (isset($_SESSION['sessao_id'])) {
                    registrarAtividade($_SESSION['sessao_id'], "Evento criado com " . count($midias) . " mídias", "CRIACAO");
                }

                $conn->commit();

                header("Location: /marktour/View/Empresa/portalDaEmpresa.php");
                exit();
            } catch (Exception $e) {
                if (isset($conn)) {
                    $conn->rollback();
                }

                foreach ($uploadedFiles as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }

                error_log("Erro no EventosController: " . $e->getMessage());
                $erroMsg = urlencode($e->getMessage());
                header("Location: /marktour/View/Erro/erro.php?msg=$erroMsg");
                exit();
            } finally {
                if (isset($conn)) {
                    $conn->close();
                }
            }
        } else {
            $erroMsg = urlencode("Método inválido.");
            header("Location: /marktour/View/Erro/erro.php?msg=$erroMsg");
            exit();
        }
    }
}

$controller = new EventosController();
$controller->criarEvento();