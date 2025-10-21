<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

// Verificar se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin' || !isset($_SESSION['id_utilizador'])) {
    header("Location: /marktour/Controller/Auth/LoginController.php");
    exit();
}

$conexao = new Conector();
$conn = $conexao->getConexao();
$mensagem = ''; // Para feedback ao usuário

// Adicionar nova FAQ
if (isset($_POST['add_faq'])) {
    $pergunta = trim($_POST['pergunta'] ?? '');
    $resposta = trim($_POST['resposta'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '') ?: null;
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    if (empty($pergunta) || empty($resposta)) {
        $mensagem = ['tipo' => 'danger', 'texto' => 'A pergunta e a resposta são obrigatórias.'];
    } else {
        try {
            mysqli_begin_transaction($conn);
            $stmt = $conn->prepare("INSERT INTO faq (pergunta, resposta, categoria, visivel, id_utilizador, ultima_atualizacao) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssii", $pergunta, $resposta, $categoria, $visivel, $_SESSION['id_utilizador']);
            if (!$stmt->execute()) {
                throw new Exception("Erro ao adicionar FAQ: " . $conn->error);
            }
            $stmt->close();
            mysqli_commit($conn);
            $mensagem = ['tipo' => 'success', 'texto' => 'FAQ adicionada com sucesso.'];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Erro ao adicionar FAQ: " . $e->getMessage());
            $mensagem = ['tipo' => 'danger', 'texto' => 'Erro ao adicionar FAQ.'];
        }
    }
}

// Responder a uma submissão de usuário
if (isset($_POST['respond_submission'])) {
    $id_faq = (int)($_POST['id_faq'] ?? 0);
    $resposta = trim($_POST['resposta'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '') ?: null;
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    if ($id_faq <= 0 || empty($resposta)) {
        $mensagem = ['tipo' => 'danger', 'texto' => 'ID da FAQ ou resposta inválidos.'];
    } else {
        try {
            mysqli_begin_transaction($conn);
            $stmt = $conn->prepare("UPDATE faq SET resposta = ?, categoria = ?, visivel = ?, ultima_atualizacao = NOW() WHERE id_faq = ? AND id_utilizador IS NOT NULL AND resposta IS NULL");
            $stmt->bind_param("ssii", $resposta, $categoria, $visivel, $id_faq);
            if (!$stmt->execute()) {
                throw new Exception("Erro ao responder FAQ: " . $conn->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("Nenhuma FAQ encontrada ou já respondida.");
            }
            $stmt->close();
            mysqli_commit($conn);
            $mensagem = ['tipo' => 'success', 'texto' => 'Submissão respondida com sucesso.'];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Erro ao responder FAQ: " . $e->getMessage());
            $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
        }
    }
}

// Editar FAQ
if (isset($_POST['edit_faq'])) {
    $id_faq = (int)($_POST['id_faq'] ?? 0);
    $pergunta = trim($_POST['pergunta'] ?? '');
    $resposta = trim($_POST['resposta'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '') ?: null;
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    if ($id_faq <= 0 || empty($pergunta) || empty($resposta)) {
        $mensagem = ['tipo' => 'danger', 'texto' => 'ID da FAQ, pergunta ou resposta inválidos.'];
    } else {
        try {
            mysqli_begin_transaction($conn);
            $stmt = $conn->prepare("UPDATE faq SET pergunta = ?, resposta = ?, categoria = ?, visivel = ?, ultima_atualizacao = NOW() WHERE id_faq = ?");
            $stmt->bind_param("sssii", $pergunta, $resposta, $categoria, $visivel, $id_faq);
            if (!$stmt->execute()) {
                throw new Exception("Erro ao editar FAQ: " . $conn->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("Nenhuma FAQ encontrada com o ID fornecido.");
            }
            $stmt->close();
            mysqli_commit($conn);
            $mensagem = ['tipo' => 'success', 'texto' => 'FAQ editada com sucesso.'];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Erro ao editar FAQ: " . $e->getMessage());
            $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
        }
    }
}

// Excluir FAQ
if (isset($_GET['delete_id'])) {
    $id_faq = (int)($_GET['delete_id'] ?? 0);
    if ($id_faq <= 0) {
        $mensagem = ['tipo' => 'danger', 'texto' => 'ID da FAQ inválido.'];
    } else {
        try {
            mysqli_begin_transaction($conn);
            $stmt = $conn->prepare("DELETE FROM faq WHERE id_faq = ?");
            $stmt->bind_param("i", $id_faq);
            if (!$stmt->execute()) {
                throw new Exception("Erro ao excluir FAQ: " . $conn->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("Nenhuma FAQ encontrada com o ID fornecido.");
            }
            $stmt->close();
            mysqli_commit($conn);
            $mensagem = ['tipo' => 'success', 'texto' => 'FAQ excluída com sucesso.'];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Erro ao excluir FAQ: " . $e->getMessage());
            $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
        }
    }
}

// Listar FAQs
try {
    $sql = "SELECT * FROM faq ORDER BY data_criacao DESC";
    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception("Erro ao listar FAQs: " . $conn->error);
    }
    $faqs = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao listar FAQs: " . $e->getMessage());
    $faqs = [];
}

// Listar submissões pendentes (perguntas sem resposta)
try {
    $sql_submissions = "SELECT id_faq, pergunta, data_criacao FROM faq WHERE resposta IS NULL AND id_utilizador IS NOT NULL ORDER BY data_criacao DESC";
    $result_submissions = $conn->query($sql_submissions);
    if (!$result_submissions) {
        throw new Exception("Erro ao listar submissões pendentes: " . $conn->error);
    }
    $submissions = $result_submissions->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao listar submissões: " . $e->getMessage());
    $submissions = [];
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerir FAQs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
            display: none;
        }
        .is-invalid ~ .error-message {
            display: block;
        }
    </style>
</head>

<body>
    <header>
        <!-- Nav principal -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp" alt="Marktour Logo" height="30">
                <div class="nav-modal">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 fs-4"><i class="fa-brands fa-square-instagram" style="color: #3a4c91;"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr" class="me-3 fs-4"><i class="fa-brands fa-facebook" style="color: #3a4c91;"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Sair</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="portalDoAdmin.php">Início</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownAcomodacoes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownAcomodacoes">
                        <li><a class="dropdown-item" href="#">Hotéis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                        <li><a class="dropdown-item" href="#">Lounges</a></li>
                        <li><a class="dropdown-item" href="#">Casas de Praia</a></li>
                        <li><a class="dropdown-item" href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownPasseios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownPasseios">
                        <li><a class="dropdown-item" href="#">A Pé</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMarkTour" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMarkTour">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="AdminFaqs.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Blog.php">Blog</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Reviews.php">Avaliações</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-5">
        <h2 class="text-center">Gerir FAQs</h2>

        <!-- Mensagem de feedback -->
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensagem['texto']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Formulário para adicionar nova FAQ -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Adicionar Nova FAQ</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="addFaqForm">
                    <div class="mb-3">
                        <label for="pergunta" class="form-label">Pergunta</label>
                        <input type="text" class="form-control" id="pergunta" name="pergunta" required>
                        <div class="error-message">A pergunta é obrigatória.</div>
                    </div>
                    <div class="mb-3">
                        <label for="resposta" class="form-label">Resposta</label>
                        <textarea class="form-control" id="resposta" name="resposta" rows="3" required></textarea>
                        <div class="error-message">A resposta é obrigatória.</div>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="categoria" name="categoria">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="visivel" name="visivel" checked>
                        <label class="form-check-label" for="visivel">Visível</label>
                    </div>
                    <button type="submit" name="add_faq" class="btn btn-primary">Adicionar</button>
                </form>
            </div>
        </div>

        <!-- Lista de Submissões de Utilizadores Pendentes -->
        <h3>Submissões de Utilizadores Pendentes</h3>
        <?php if (empty($submissions)): ?>
            <p class="text-center">Nenhuma submissão pendente no momento.</p>
        <?php else: ?>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>Pergunta</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($submission['pergunta']); ?></td>
                            <td><?php echo htmlspecialchars($submission['data_criacao']); ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#respondModal<?php echo $submission['id_faq']; ?>">Responder</button>
                                <a href="?delete_id=<?php echo $submission['id_faq']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja rejeitar esta submissão?');">Rejeitar</a>
                            </td>
                        </tr>

                        <!-- Modal para responder submissão -->
                        <div class="modal fade" id="respondModal<?php echo $submission['id_faq']; ?>" tabindex="-1" aria-labelledby="respondLabel<?php echo $submission['id_faq']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="respondLabel<?php echo $submission['id_faq']; ?>">Responder Pergunta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" id="respondForm<?php echo $submission['id_faq']; ?>">
                                            <input type="hidden" name="id_faq" value="<?php echo $submission['id_faq']; ?>">
                                            <div class="mb-3">
                                                <label for="resposta_<?php echo $submission['id_faq']; ?>" class="form-label">Resposta</label>
                                                <textarea class="form-control" id="resposta_<?php echo $submission['id_faq']; ?>" name="resposta" rows="3" required></textarea>
                                                <div class="error-message">A resposta é obrigatória.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="categoria_<?php echo $submission['id_faq']; ?>" class="form-label">Categoria</label>
                                                <input type="text" class="form-control" id="categoria_<?php echo $submission['id_faq']; ?>" name="categoria">
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="visivel_<?php echo $submission['id_faq']; ?>" name="visivel" checked>
                                                <label class="form-check-label" for="visivel_<?php echo $submission['id_faq']; ?>">Visível</label>
                                            </div>
                                            <button type="submit" name="respond_submission" class="btn btn-primary">Salvar e Responder</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Lista de FAQs -->
        <h3>FAQs Existentes</h3>
        <?php if (empty($faqs)): ?>
            <p class="text-center">Nenhuma FAQ registada no momento.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pergunta</th>
                        <th>Resposta</th>
                        <th>Categoria</th>
                        <th>Visível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($faq['pergunta']); ?></td>
                            <td><?php echo htmlspecialchars($faq['resposta'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($faq['categoria'] ?? ''); ?></td>
                            <td><?php echo $faq['visivel'] ? 'Sim' : 'Não'; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $faq['id_faq']; ?>">Editar</button>
                                <a href="?delete_id=<?php echo $faq['id_faq']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta FAQ?');">Excluir</a>
                            </td>
                        </tr>

                        <!-- Modal para editar FAQ -->
                        <div class="modal fade" id="editModal<?php echo $faq['id_faq']; ?>" tabindex="-1" aria-labelledby="editLabel<?php echo $faq['id_faq']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLabel<?php echo $faq['id_faq']; ?>">Editar FAQ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" id="editForm<?php echo $faq['id_faq']; ?>">
                                            <input type="hidden" name="id_faq" value="<?php echo $faq['id_faq']; ?>">
                                            <div class="mb-3">
                                                <label for="pergunta_<?php echo $faq['id_faq']; ?>" class="form-label">Pergunta</label>
                                                <input type="text" class="form-control" id="pergunta_<?php echo $faq['id_faq']; ?>" name="pergunta" value="<?php echo htmlspecialchars($faq['pergunta']); ?>" required>
                                                <div class="error-message">A pergunta é obrigatória.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="resposta_<?php echo $faq['id_faq']; ?>" class="form-label">Resposta</label>
                                                <textarea class="form-control" id="resposta_<?php echo $faq['id_faq']; ?>" name="resposta" rows="3" required><?php echo htmlspecialchars($faq['resposta'] ?? ''); ?></textarea>
                                                <div class="error-message">A resposta é obrigatória.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="categoria_<?php echo $faq['id_faq']; ?>" class="form-label">Categoria</label>
                                                <input type="text" class="form-control" id="categoria_<?php echo $faq['id_faq']; ?>" name="categoria" value="<?php echo htmlspecialchars($faq['categoria'] ?? ''); ?>">
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="visivel_<?php echo $faq['id_faq']; ?>" name="visivel" <?php echo $faq['visivel'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="visivel_<?php echo $faq['id_faq']; ?>">Visível</label>
                                            </div>
                                            <button type="submit" name="edit_faq" class="btn btn-primary">Salvar Alterações</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer class="text-center mt-5">
        <p>Copyright 2023 © MarkTour | Todos os Direitos Reservados</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // Validação do formulário de adicionar FAQ
            $("#addFaqForm").validate({
                rules: {
                    pergunta: {
                        required: true,
                        minlength: 3
                    },
                    resposta: {
                        required: true,
                        minlength: 3
                    },
                    categoria: {
                        maxlength: 50
                    }
                },
                messages: {
                    pergunta: {
                        required: "A pergunta é obrigatória.",
                        minlength: "A pergunta deve ter pelo menos 3 caracteres."
                    },
                    resposta: {
                        required: "A resposta é obrigatória.",
                        minlength: "A resposta deve ter pelo menos 3 caracteres."
                    },
                    categoria: {
                        maxlength: "A categoria não pode exceder 50 caracteres."
                    }
                },
                errorClass: "is-invalid",
                validClass: "is-valid",
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                },
                errorPlacement: function(error, element) {
                    error.appendTo(element.next(".error-message"));
                }
            });

            // Validação dos formulários de responder e editar
            $("form[id^='respondForm'], form[id^='editForm']").each(function() {
                $(this).validate({
                    rules: {
                        pergunta: {
                            required: true,
                            minlength: 3
                        },
                        resposta: {
                            required: true,
                            minlength: 3
                        },
                        categoria: {
                            maxlength: 50
                        }
                    },
                    messages: {
                        pergunta: {
                            required: "A pergunta é obrigatória.",
                            minlength: "A pergunta deve ter pelo menos 3 caracteres."
                        },
                        resposta: {
                            required: "A resposta é obrigatória.",
                            minlength: "A resposta deve ter pelo menos 3 caracteres."
                        },
                        categoria: {
                            maxlength: "A categoria não pode exceder 50 caracteres."
                        }
                    },
                    errorClass: "is-invalid",
                    validClass: "is-valid",
                    highlight: function(element) {
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    },
                    unhighlight: function(element) {
                        $(element).removeClass("is-invalid").addClass("is-valid");
                    },
                    errorPlacement: function(error, element) {
                        error.appendTo(element.next(".error-message"));
                    }
                });
            });
        });
    </script>
</body>

</html>