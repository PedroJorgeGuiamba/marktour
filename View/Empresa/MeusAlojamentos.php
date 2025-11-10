<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Model/Alojamento.php';
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
    $alojId = intval($_GET['id']);
    // Check if belongs to user
    $sql = "SELECT id_empresa FROM alojamento WHERE id_alojamento = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $alojId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $aloj = mysqli_fetch_assoc($result);
    if ($aloj && $aloj['id_empresa'] == $empresaId) {
        $sql = "DELETE FROM alojamento WHERE id_alojamento = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $alojId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF']); // Reload page
    exit;
}

// Handle edit submission
$editMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_alojamento'])) {
    $alojId = intval($_POST['id_alojamento']);
    // Check ownership
    $sql = "SELECT id_empresa, imagem_path FROM alojamento WHERE id_alojamento = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $alojId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $aloj = mysqli_fetch_assoc($result);
    if ($aloj && $aloj['id_empresa'] == $empresaId) {
        $nome = trim($_POST['nome'] ?? '');
        if (empty($nome)) {
            $editMessage = '<div class="alert alert-danger">O nome do alojamento é obrigatório.</div>';
        } else {
            $tipo = $_POST['tipo'] ?? 'hotel'; // Default
            $descricao = $_POST['descricao'] ?? '';
            $preco_noite = floatval($_POST['preco_noite'] ?? 0);
            $num_quartos = intval($_POST['num_quartos'] ?? 0);
            $imagem_path = $aloj['imagem_path']; // Keep existing if no new upload

            // Handle image upload
            if (isset($_FILES['imagem_path']) && $_FILES['imagem_path']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '/marktour/uploads/alojamentos/'; // Web root relative path
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
                $sql = "UPDATE alojamento SET nome = ?, tipo = ?, descricao = ?, preco_noite = ?, num_quartos = ?, imagem_path = ? WHERE id_alojamento = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssdiis", $nome, $tipo, $descricao, $preco_noite, $num_quartos, $imagem_path, $alojId);
                if (mysqli_stmt_execute($stmt)) {
                    $editMessage = '<div class="alert alert-success">Alojamento atualizado com sucesso!</div>';
                } else {
                    $editMessage = '<div class="alert alert-danger">Erro ao atualizar alojamento.</div>';
                }
                mysqli_stmt_close($stmt);
            }
        }
    } else {
        $editMessage = '<div class="alert alert-danger">Alojamento não encontrado ou sem permissão.</div>';
    }
    // No redirect, show message on page
}

class RecuperarAlojamentos
{
    public function listar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        // Consulta para recuperar todos os alojamentos
        $sql = "SELECT * FROM alojamento";
        $result = $conn->query($sql);
        $alojamentos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alojamentos[] = $row;
            }
        }
        return $alojamentos;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Alojamentos - MarkTour</title>
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
                        <li><a class="dropdown-item" href="RegistrarAlojamento.php">Registrar Alojamento</a></li>
                        <li><a class="dropdown-item" href="MeusAlojamentos.php">Ver Alojamentos</a></li>
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
    <main class="destaques py-5 bg-light" style="padding-top: 100px;">
        <div class="container">
            <?php echo $editMessage; // Display edit message if any ?>
            <!-- Meus Alojamentos (if empresa) -->
            <?php if ($empresaId > 0): ?>
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Meus Alojamentos</h2>
                        <p class="text-muted">Gerencie seus alojamentos cadastrados</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    $recuperar = new RecuperarAlojamentos();
                    $alojamentos = $recuperar->listar();
                    $myAlojamentos = array_filter($alojamentos, function($aloj) use ($empresaId) {
                        return $aloj['id_empresa'] == $empresaId;
                    });
                    $dataHoraAtual = date("h:i A T, l, F d, Y");
                    if (empty($myAlojamentos)) {
                        echo "<div class='col text-center text-muted'>Nenhum alojamento seu registado.</div>";
                    } else {
                        foreach ($myAlojamentos as $alojamento) {
                            echo "
                            <div class='col'>
                                <div class='card h-100 shadow-sm border-0'>
                                    <div class='position-relative overflow-hidden'>
                                        <img src='" . htmlspecialchars($alojamento['imagem_path'] ?? '/uploads/alojamentos/placeholder.png') . "' class='card-img-top' alt='Imagem do {$alojamento['nome']}' style='max-height: 200px; object-fit: cover;'>
                                        <div class='position-absolute top-0 end-0 m-3'>
                                            <span class='badge bg-primary'>Meus Alojamentos</span>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>" . htmlspecialchars($alojamento['nome']) . "</h5>
                                        <p class='card-text text-muted'>" . htmlspecialchars($alojamento['descricao']) . "</p>
                                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($alojamento['preco_noite']) . " MZN</span>
                                        </div>
                                        <p class='card-text mb-2'><small class='text-muted'>Tipo: " . htmlspecialchars($alojamento['tipo']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Número de Quartos: " . htmlspecialchars($alojamento['num_quartos']) . "</small></p>
                                        <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                        <div class='d-flex gap-2'>
                                            <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$alojamento['id_alojamento']}'>Editar</button>
                                            <a href='?action=delete&id={$alojamento['id_alojamento']}' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                            // Edit Modal for this alojamento
                            echo "
                            <div class='modal fade' id='editModal{$alojamento['id_alojamento']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='editModalLabel'>Editar Alojamento</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <form method='POST' enctype='multipart/form-data'>
                                                <input type='hidden' name='edit_alojamento' value='1'>
                                                <input type='hidden' name='id_alojamento' value='{$alojamento['id_alojamento']}'>
                                                <div class='mb-3'>
                                                    <label for='nome' class='form-label'>Nome</label>
                                                    <input type='text' class='form-control' id='nome' name='nome' value='" . htmlspecialchars($alojamento['nome']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='tipo' class='form-label'>Tipo</label>
                                                    <input type='text' class='form-control' id='tipo' name='tipo' value='" . htmlspecialchars($alojamento['tipo']) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='descricao' class='form-label'>Descrição</label>
                                                    <textarea class='form-control' id='descricao' name='descricao'>" . htmlspecialchars($alojamento['descricao']) . "</textarea>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='preco_noite' class='form-label'>Preço por Noite</label>
                                                    <input type='number' step='0.01' class='form-control' id='preco_noite' name='preco_noite' value='" . htmlspecialchars($alojamento['preco_noite']) . "'>
                                                </div>
                                                <div class='mb-3'>
                                                    <label for='num_quartos' class='form-label'>Número de Quartos</label>
                                                    <input type='number' class='form-control' id='num_quartos' name='num_quartos' value='" . htmlspecialchars($alojamento['num_quartos']) . "'>
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
            <!-- Outros Alojamentos -->
            <div class="row mb-5 mt-5">
                <div class="col text-center">
                    <h2 class="fw-bold">Outros Alojamentos em Destaque</h2>
                    <p class="text-muted">Os alojamentos mais procurados pelos nossos viajantes</p>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $otherAlojamentos = array_filter($alojamentos, function($aloj) use ($empresaId) {
                    return $aloj['id_empresa'] != $empresaId;
                });
                if (empty($otherAlojamentos)) {
                    echo "<div class='col text-center text-muted'>Nenhum outro alojamento disponível.</div>";
                } else {
                    foreach ($otherAlojamentos as $alojamento) {
                        echo "
                        <div class='col'>
                            <div class='card h-100 shadow-sm border-0'>
                                <div class='position-relative overflow-hidden'>
                                    <img src='" . htmlspecialchars($alojamento['imagem_path'] ?? '/uploads/alojamentos/placeholder.png') . "' class='card-img-top' alt='Imagem do {$alojamento['nome']}' style='max-height: 200px; object-fit: cover;'>
                                    <div class='position-absolute top-0 end-0 m-3'>
                                        <span class='badge bg-primary'>Alojamentos</span>
                                    </div>
                                </div>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . htmlspecialchars($alojamento['nome']) . "</h5>
                                    <p class='card-text text-muted'>" . htmlspecialchars($alojamento['descricao']) . "</p>
                                    <div class='d-flex justify-content-between align-items-center mb-2'>
                                        <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($alojamento['preco_noite']) . " MZN</span>
                                    </div>
                                    <p class='card-text mb-2'><small class='text-muted'>Tipo: " . htmlspecialchars($alojamento['tipo']) . "</small></p>
                                    <p class='card-text mb-2'><small class='text-muted'>Número de Quartos: " . htmlspecialchars($alojamento['num_quartos']) . "</small></p>
                                    <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                    <div class='d-flex gap-2'>
                                        <a href='Carrinho.php?action=add&id=" . htmlspecialchars($alojamento['id_alojamento']) . "' class='btn btn-primary'>Adicionar ao Carrinho</a>
                                        <a href='reservar.php?id=" . htmlspecialchars($alojamento['id_alojamento']) . "' class='btn btn-success'>Reservar</a>
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