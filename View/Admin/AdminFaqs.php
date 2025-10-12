<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

// Verificar se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: /marktour/Controller/Auth/LoginController.php");
    exit();
}

$conexao = new Conector();
$conn = $conexao->getConexao();

// Adicionar nova FAQ
if (isset($_POST['add_faq'])) {
    $pergunta = trim($_POST['pergunta']);
    $resposta = trim($_POST['resposta']);
    $categoria = trim($_POST['categoria']);
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO faq (pergunta, resposta, categoria, visivel, id_utilizador) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $pergunta, $resposta, $categoria, $visivel, $_SESSION['user_id']);
    $stmt->execute();
}

// Responder a uma submissão de usuário
if (isset($_POST['respond_submission'])) {
    $id_submission = (int)$_POST['id_submission'];
    $resposta = trim($_POST['resposta']);
    $categoria = trim($_POST['categoria']);
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    // Obter a pergunta da submissão
    $stmt_submission = $conn->prepare("SELECT pergunta FROM faq_submissions WHERE id_submission = ?");
    $stmt_submission->bind_param("i", $id_submission);
    $stmt_submission->execute();
    $result_submission = $stmt_submission->get_result();
    $submission = $result_submission->fetch_assoc();

    if ($submission) {
        $pergunta = $submission['pergunta'];
        $stmt_faq = $conn->prepare("INSERT INTO faq (pergunta, resposta, categoria, visivel, id_utilizador) VALUES (?, ?, ?, ?, ?)");
        $stmt_faq->bind_param("sssii", $pergunta, $resposta, $categoria, $visivel, $_SESSION['user_id']);
        $stmt_faq->execute();

        // Atualizar status da submissão
        $stmt_update = $conn->prepare("UPDATE faq_submissions SET status = 'respondida' WHERE id_submission = ?");
        $stmt_update->bind_param("i", $id_submission);
        $stmt_update->execute();
    }
}

// Editar FAQ
if (isset($_POST['edit_faq'])) {
    $id_faq = (int)$_POST['id_faq'];
    $pergunta = trim($_POST['pergunta']);
    $resposta = trim($_POST['resposta']);
    $categoria = trim($_POST['categoria']);
    $visivel = isset($_POST['visivel']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE faq SET pergunta = ?, resposta = ?, categoria = ?, visivel = ? WHERE id_faq = ?");
    $stmt->bind_param("sssii", $pergunta, $resposta, $categoria, $visivel, $id_faq);
    $stmt->execute();
}

// Excluir FAQ
if (isset($_GET['delete_id'])) {
    $id_faq = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM faq WHERE id_faq = ?");
    $stmt->bind_param("i", $id_faq);
    $stmt->execute();
}

// Rejeitar submissão de usuário
if (isset($_GET['reject_submission'])) {
    $id_submission = (int)$_GET['reject_submission'];
    $stmt = $conn->prepare("UPDATE faq_submissions SET status = 'rejeitada' WHERE id_submission = ?");
    $stmt->bind_param("i", $id_submission);
    $stmt->execute();
}

// Listar FAQs
$sql = "SELECT * FROM faq ORDER BY data_criacao DESC";
$result = $conn->query($sql);
$faqs = $result->fetch_all(MYSQLI_ASSOC);

// Listar submissões pendentes
// $sql_submissions = "SELECT * FROM faq_submissions WHERE status = 'pendente' ORDER BY data_submissao DESC";
// $result_submissions = $conn->query($sql_submissions);
// $submissions = $result_submissions->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar FAQs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../Style/empresa.css">
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
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 text-white fs-4"><i class="fa-brands fa-square-instagram" style="color: #3a4c91;"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#" class="me-3 text-white fs-4"><i class="fa-brands fa-facebook" style="color: #3a4c91;"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="portalDoAdmin.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">Hoteis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                        <li><a class="dropdown-item" href="#">Lounges</a></li>
                        <li><a class="dropdown-item" href="#">Casas De Praia</a></li>
                        <li><a class="dropdown-item" href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">A Pe</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="AdminFaqs.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Blog.php">Blog</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Reviews.php">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-5">
        <h2 class="text-center">Gerenciar FAQs</h2>

        <!-- Formulário para adicionar nova FAQ -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Adicionar Nova FAQ</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="pergunta" class="form-label">Pergunta</label>
                        <input type="text" class="form-control" id="pergunta" name="pergunta" required>
                    </div>
                    <div class="mb-3">
                        <label for="resposta" class="form-label">Resposta</label>
                        <textarea class="form-control" id="resposta" name="resposta" rows="3" required></textarea>
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

        <!-- Lista de Submissões de Usuários Pendentes -->
        <h3>Submissões de Usuários Pendentes</h3>
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                    <th>Pergunta</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['pergunta']); ?></td>
                        <td><?php echo htmlspecialchars($submission['data_submissao']); ?></td>
                        <td>
                            <!-- Botão para responder (modal) -->
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#respondModal<?php echo $submission['id_submission']; ?>">Responder</button>
                            <a href="?reject_submission=<?php echo $submission['id_submission']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja rejeitar?');">Rejeitar</a>
                        </td>
                    </tr>

                    <!-- Modal para responder submissão -->
                    <div class="modal fade" id="respondModal<?php echo $submission['id_submission']; ?>" tabindex="-1" aria-labelledby="respondLabel<?php echo $submission['id_submission']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="respondLabel<?php echo $submission['id_submission']; ?>">Responder Pergunta</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="id_submission" value="<?php echo $submission['id_submission']; ?>">
                                        <div class="mb-3">
                                            <label for="resposta" class="form-label">Resposta</label>
                                            <textarea class="form-control" id="resposta" name="resposta" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoria</label>
                                            <input type="text" class="form-control" id="categoria" name="categoria">
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="visivel" name="visivel" checked>
                                            <label class="form-check-label" for="visivel">Visível</label>
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

        <!-- Lista de FAQs -->
        <h3>FAQs Existentes</h3>
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
                        <td><?php echo htmlspecialchars($faq['resposta']); ?></td>
                        <td><?php echo htmlspecialchars($faq['categoria']); ?></td>
                        <td><?php echo $faq['visivel'] ? 'Sim' : 'Não'; ?></td>
                        <td>
                            <!-- Botão para editar (modal) -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $faq['id_faq']; ?>">Editar</button>
                            <a href="?delete_id=<?php echo $faq['id_faq']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
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
                                    <form method="POST">
                                        <input type="hidden" name="id_faq" value="<?php echo $faq['id_faq']; ?>">
                                        <div class="mb-3">
                                            <label for="pergunta" class="form-label">Pergunta</label>
                                            <input type="text" class="form-control" id="pergunta" name="pergunta" value="<?php echo htmlspecialchars($faq['pergunta']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="resposta" class="form-label">Resposta</label>
                                            <textarea class="form-control" id="resposta" name="resposta" rows="3" required><?php echo htmlspecialchars($faq['resposta']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoria</label>
                                            <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo htmlspecialchars($faq['categoria']); ?>">
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="visivel" name="visivel" <?php echo $faq['visivel'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="visivel">Visível</label>
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
    </main>

    <footer class="text-center mt-5">
        <p>Copyright 2023 © MarkTour | Todos Direitos Reservados</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>