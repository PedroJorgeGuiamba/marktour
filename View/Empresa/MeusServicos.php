<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Model/Passeio.php'; // Assuming this is relevant, but might need adjustment if not

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$conexao = new Conector();
$conn = $conexao->getConexao();

$userId = $_SESSION['id_utilizador'] ?? 0; // Assume logged in
$empresaId = 0;

// Get user's empresa ID if applicable
if ($userId > 0) {
    $sql = "SELECT id_empresa FROM empresa WHERE id_utilizador = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $empresa = mysqli_fetch_assoc($result);
    if ($empresa) {
        $empresaId = $empresa['id_empresa'];
    }
    mysqli_stmt_close($stmt);
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_actividade = intval($_GET['id']);
    // Check if belongs to user
    $sql = "SELECT id_empresa FROM actividade WHERE id_actividade = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_actividade);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $actividade = mysqli_fetch_assoc($result);
    if ($actividade && $actividade['id_empresa'] == $empresaId) {
        $sql = "DELETE FROM actividade WHERE id_actividade = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_actividade);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF']); // Reload page
    exit;
}

// Handle edit submission
$editMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_actividade'])) {
    $id_actividade = intval($_POST['id_actividade']);
    // Check ownership
    $sql = "SELECT id_empresa, imagem_path FROM actividade WHERE id_actividade = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_actividade);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $actividade = mysqli_fetch_assoc($result);
    if ($actividade && $actividade['id_empresa'] == $empresaId) {
        $nome = trim($_POST['nome'] ?? '');
        if (empty($nome)) {
            $editMessage = '<div class="alert alert-danger">O nome da actividade é obrigatório.</div>';
        } else {
            $descricao = $_POST['descricao'] ?? '';
            $local = $_POST['local'] ?? '';
            $data_hora = $_POST['data_hora'] ?? '';
            $duracao = $_POST['duracao'] ?? '';
            $preco = floatval($_POST['preco'] ?? 0);
            $imagem_path = $actividade['imagem_path']; // Keep existing if no new upload

            // Handle image upload
            if (isset($_FILES['imagem_path']) && $_FILES['imagem_path']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '/marktour/uploads/actividades/'; // Web root relative path
                $uploadFullDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir; // Full server path
                if (!file_exists($uploadFullDir)) {
                    mkdir($uploadFullDir, 0777, true); // Create directory if not exists
                }
                $fileName = basename($_FILES['imagem_path']['name']);
                $imagemExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($imagemExt, $allowedExts)) {
                    $newImagemName = uniqid() . '.' . $imagemExt;
                    $targetFile = $uploadFullDir . $newImagemName;
                    if (move_uploaded_file($_FILES['imagem_path']['tmp_name'], $targetFile)) {
                        $imagem_path = $uploadDir . $newImagemName; // Save web path
                    } else {
                        $editMessage = '<div class="alert alert-danger">Erro ao fazer upload da imagem.</div>';
                    }
                } else {
                    $editMessage = '<div class="alert alert-danger">Formato de imagem não permitido.</div>';
                }
            }

            if (empty($editMessage)) {
                $sql = "UPDATE actividade SET nome = ?, descricao = ?, local = ?, data_hora = ?, duracao = ?, preco = ?, imagem_path = ? WHERE id_actividade = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssdis", $nome, $descricao, $local, $data_hora, $duracao, $preco, $imagem_path, $id_actividade);
                if (mysqli_stmt_execute($stmt)) {
                    $editMessage = '<div class="alert alert-success">Actividade atualizada com sucesso!</div>';
                } else {
                    $editMessage = '<div class="alert alert-danger">Erro ao atualizar actividade.</div>';
                }
                mysqli_stmt_close($stmt);
            }
        }
    } else {
        $editMessage = '<div class="alert alert-danger">Actividade não encontrada ou sem permissão.</div>';
    }
    // No redirect, show message on page
}

class RecuperarActividades
{
    public function listar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        // Consulta para recuperar todos os actividades
        $sql = "SELECT * FROM actividade";
        $result = $conn->query($sql);
        $actividades = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $actividades[] = $row;
            }
        }
        return $actividades;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Actividades - MarkTour</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <style>
        .cart-icon {
            position: relative;
            margin-left: 15px;
        }
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .destaques{
            padding-top: 100px;
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 text-white fs-4">
                                    <i class="fa-brands fa-square-instagram" style="color: #3a4c91;"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#" class="me-3 text-white fs-4">
                                    <i class="fa-brands fa-facebook" style="color: #3a4c91;"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Sair</a>
                            </li>
                            <li class="nav-item">
                                <a href="carrinho.php" class="cart-icon">
                                    <i class="fas fa-shopping-cart fs-4" style="color: #3a4c91;"></i>
                                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Nav Secundária -->
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../Empresa/portalDaEmpresa.php">Início</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="Registraractividade.php">Registrar Actividade</a></li>
                        <li><a class="dropdown-item" href="Meusactividades.php">Ver Actividades</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="RegistrarPasseio.php">Registrar Passeios</a></li>
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
                        <li><a class="dropdown-item" href="#">Sobre</a></li>
                        <li><a class="dropdown-item" href="#">Contactos</a></li>
                        <li><a class="dropdown-item" href="#">FAQ</a></li>
                        <li><a class="dropdown-item" href="#">Blog</a></li>
                        <li><a class="dropdown-item" href="#">Avaliações</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main class="destaques py-5 bg-light">
        <div class="container">
            <?php echo $editMessage; // Display edit message if any ?>
            <!-- Meus Actividades (if empresa) -->
            <?php if ($empresaId > 0): ?>
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Minhas Actividades</h2>
                        <p class="text-muted">Gerencie suas actividades cadastradas</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    $recuperar = new RecuperarActividades();
                    $actividades = $recuperar->listar();
                    $myActividades = array_filter($actividades, function($actividade) use ($empresaId) {
                        return $actividade['id_empresa'] == $empresaId;
                    });
                    $dataHoraAtual = date("h:i A T, l, F d, Y");
                    if (empty($myActividades)) {
                        echo "<div class='col text-center text-muted'>Nenhuma actividade sua registrada.</div>";
                    } else {
                        foreach ($myActividades as $actividade) {
                            echo "
                            <div class='col'>
                                <div class='card h-100 shadow-sm border-0'>
                                    <div class='position-relative overflow-hidden'>
                                        <img src='" . htmlspecialchars($actividade['imagem_path'] ?? '/uploads/actividades/placeholder.png') . "' class='card-img-top' alt='Imagem da {$actividade['nome']}' style='max-height: 200px; object-fit: cover;'>
                                        <div class='position-absolute top-0 end-0 m-3'>
                                            <span class='badge bg-primary'>Minhas Actividades</span>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>" . htmlspecialchars($actividade['nome']) . "</h5>
                                        <p class='card-text text-muted'>" . htmlspecialchars($actividade['descricao']) . "</p>
                                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($actividade['preco']) . " MZN</span>
                                        </div>
                                        <p class='card-text mb-2'><small class='text-muted'>Descrição: " . htmlspecialchars($actividade['descricao']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Local: " . htmlspecialchars($actividade['local']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Data/Hora: " . htmlspecialchars($actividade['data_hora']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Duração: " . htmlspecialchars($actividade['duracao']) . "</small></p>
                                        <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                        <div class='d-flex gap-2'>
                                            <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$actividade['id_actividade']}'>Editar</button>
                                            <a href='?action=delete&id={$actividade['id_actividade']}' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                            // Edit Modal for this actividade
                            echo "
                            <div class='modal fade' id='editModal{$actividade['id_actividade']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='editModalLabel'>Editar Actividade</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <form method='POST' enctype='multipart/form-data'>
                                                <input type='hidden' name='edit_actividade' value='1'>
                                                <input type='hidden' name='id_actividade' value='{$actividade['id_actividade']}'>
                                                <div class='mb-3'>
                                                    <label for='nome' class='form-label'>Nome</label>
                                                    <input type='text' class='form-control' id='nome' name='nome' value='" . htmlspecialchars($actividade['nome']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='descricao' class='form-label'>Descrição</label>
                                                    <textarea class='form-control' id='descricao' name='descricao'>" . htmlspecialchars($actividade['descricao']) . "</textarea>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='local' class='form-label'>Local</label>
                                                    <input type='text' class='form-control' id='local' name='local' value='" . htmlspecialchars($actividade['local']) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='data_hora' class='form-label'>Data/Hora</label>
                                                    <input type='datetime-local' class='form-control' id='data_hora' name='data_hora' value='" . htmlspecialchars(date('Y-m-d\TH:i', strtotime($actividade['data_hora']))) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='duracao' class='form-label'>Duração</label>
                                                    <input type='text' class='form-control' id='duracao' name='duracao' value='" . htmlspecialchars($actividade['duracao']) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='preco' class='form-label'>Preço</label>
                                                    <input type='number' step='0.01' class='form-control' id='preco' name='preco' value='" . htmlspecialchars($actividade['preco']) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='imagem_path' class='form-label'>Imagem (opcional)</label>
                                                    <input type='file' class='form-control' id='imagem_path' name='imagem_path' accept='image/*'>
                                                </div>
                                                <button type='submit' class='btn btn-primary'>Salvar Alterações</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
            <!-- Outras Actividades -->
            <div class="row mb-5 mt-5">
                <div class="col text-center">
                    <h2 class="fw-bold">Outras Actividades em Destaque</h2>
                    <p class="text-muted">As actividades mais procuradas pelos nossos viajantes</p>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $otherActividades = array_filter($actividades, function($actividade) use ($empresaId) {
                    return $actividade['id_empresa'] != $empresaId;
                });
                if (empty($otherActividades)) {
                    echo "<div class='col text-center text-muted'>Nenhuma outra actividade disponível.</div>";
                } else {
                    foreach ($otherActividades as $actividade) {
                        echo "
                        <div class='col'>
                            <div class='card h-100 shadow-sm border-0'>
                                <div class='position-relative overflow-hidden'>
                                    <img src='" . htmlspecialchars($actividade['imagem_path'] ?? '/uploads/actividades/placeholder.png') . "' class='card-img-top' alt='Imagem da {$actividade['nome']}' style='max-height: 200px; object-fit: cover;'>
                                    <div class='position-absolute top-0 end-0 m-3'>
                                        <span class='badge bg-primary'>Actividades</span>
                                    </div>
                                </div>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . htmlspecialchars($actividade['nome']) . "</h5>
                                    <p class='card-text text-muted'>" . htmlspecialchars($actividade['descricao']) . "</p>
                                    <div class='d-flex justify-content-between align-items-center mb-2'>
                                        <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($actividade['preco']) . " MZN</span>
                                    </div>
                                    <p class='card-text mb-2'><small class='text-muted'>Descrição: " . htmlspecialchars($actividade['descricao']) . "</small></p>
                                    <p class='card-text mb-2'><small class='text-muted'>Local: " . htmlspecialchars($actividade['local']) . "</small></p>
                                    <p class='card-text mb-2'><small class='text-muted'>Data/Hora: " . htmlspecialchars($actividade['data_hora']) . "</small></p>
                                    <p class='card-text mb-2'><small class='text-muted'>Duração: " . htmlspecialchars($actividade['duracao']) . "</small></p>
                                    <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                    <div class='d-flex gap-2'>
                                        <a href='Carrinho.php?action=add&id=" . htmlspecialchars($actividade['id_actividade']) . "' class='btn btn-primary'>Adicionar ao Carrinho</a>
                                        <a href='reservar.php?id=" . htmlspecialchars($actividade['id_actividade']) . "' class='btn btn-success'>Reservar</a>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="container-footer">
            <p>Copyright 2023 © <span>MarkTour</span> | Todos os Direitos Reservados</p>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>