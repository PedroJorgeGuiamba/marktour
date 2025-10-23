<?php
session_start();

// Include the connector
require_once __DIR__ . '/../../Conexao/conector.php'; // Adjust path if needed
$conexao = new Conector();
$conn = $conexao->getConexao();

// Check if user is logged in
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: /View/Auth/Login.php'); // Redirect to login if not logged in
    exit;
}

$userId = $_SESSION['id_utilizador'];

// Handle password update if form submitted
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (strlen($newPass) < 6 || $newPass !== $confirmPass) {
        $updateMessage = '<div class="alert alert-danger">As senhas não coincidem ou são muito curtas.</div>';
    } else {
        // Fetch current hashed password
        $stmt = mysqli_prepare($conn, "SELECT senha FROM utilizador WHERE id_utilizador = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($currentPass, $user['senha'])) {
            // Update with new hash
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE utilizador SET senha = ? WHERE id_utilizador = ?");
            mysqli_stmt_bind_param($stmt, "si", $newHash, $userId);
            if (mysqli_stmt_execute($stmt)) {
                $updateMessage = '<div class="alert alert-success">Senha atualizada com sucesso!</div>';
            } else {
                $updateMessage = '<div class="alert alert-danger">Erro ao atualizar senha.</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            $updateMessage = '<div class="alert alert-danger">Senha atual incorreta.</div>';
        }
    }
}

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Fetch empresa if applicable
$empresa = null;
if ($user && $user['tipo'] === 'empresa') {
    $stmt = mysqli_prepare($conn, "SELECT * FROM empresa WHERE id_utilizador = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $empresa = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Fetch reservas
$reservas = [];
$stmt = mysqli_prepare($conn, "SELECT * FROM reserva WHERE id_utilizador = ? ORDER BY data_reserva DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $reservas[] = $row;
}
mysqli_stmt_close($stmt);

// Fetch actividades (if empresa)
$actividades = [];
if ($empresa) {
    $empresaId = $empresa['id_empresa'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM actividade WHERE id_empresa = ? ORDER BY nome ASC");
    mysqli_stmt_bind_param($stmt, "i", $empresaId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $actividades[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Informações - MarkTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Style/index.css">
    <style>
        .card { margin-bottom: 20px; }
        .no-data { color: #dc3545; }
        .modal .form-group { margin-bottom: 1rem; }
        .modal-footer .btn { margin: 0 5px; }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp">
                <div class="nav-modal">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.instagram.com/marktourreservasonline/">Instagram</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#">Facebook</a>
                            </li>
                            <li class="nav-item">
                                <a href="/Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="portalDoUtilizador.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="MeusAlojamentos.php">Acomodações</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="Passeios.php">Passeios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="Eventos.php">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMarkTour" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMarkTour">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/faq.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Blog.php">Blog</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="container mt-5" id="infoContainer" style="padding-top: 100px;">
        <h2>Informações Recuperadas</h2>
        <?php echo $updateMessage; // Display update message if any ?>
        <?php if (!$user): ?>
            <div class="alert alert-warning">Nenhum utilizador encontrado.</div>
        <?php else: ?>
            <!-- Dados do Utilizador -->
            <div class="card mb-4">
                <div class="card-header"><strong>Dados do Utilizador</strong></div>
                <div class="card-body">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">Atualizar Senha</button>
                </div>
            </div>

            <?php if ($empresa): ?>
                <!-- Dados da Empresa -->
                <div class="card mb-4">
                    <div class="card-header"><strong>Dados da Empresa</strong></div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> <?php echo htmlspecialchars($empresa['nome']); ?></p>
                        <p><strong>NUIT:</strong> <?php echo htmlspecialchars($empresa['nuit']); ?></p>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($empresa['descricao']); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Minhas Reservas -->
            <div class="card mb-4">
                <div class="card-header"><strong>Minhas Reservas</strong></div>
                <div class="card-body">
                    <?php if (empty($reservas)): ?>
                        <p class="no-data">Nenhuma reserva encontrada.</p>
                    <?php else: ?>
                        <?php foreach ($reservas as $reserva): ?>
                            <div class="mb-3">
                                <p><strong>ID Reserva:</strong> <?php echo htmlspecialchars($reserva['id_reserva']); ?></p>
                                <p><strong>Data Reserva:</strong> <?php echo htmlspecialchars($reserva['data_reserva']); ?></p>
                                <p><strong>Estado:</strong> <?php echo htmlspecialchars($reserva['estado']); ?></p>
                                <p><strong>Total:</strong> <?php echo htmlspecialchars($reserva['total']); ?> MZN</p>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Minhas Actividades -->
            <div class="card mb-4">
                <div class="card-header"><strong>Minhas Actividades</strong></div>
                <div class="card-body">
                    <?php if (empty($actividades)): ?>
                        <p class="no-data">Nenhuma actividade encontrada.</p>
                    <?php else: ?>
                        <?php foreach ($actividades as $actividade): ?>
                            <div class="mb-3">
                                <p><strong>Nome:</strong> <?php echo htmlspecialchars($actividade['nome']); ?></p>
                                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($actividade['descricao']); ?></p>
                                <p><strong>Local:</strong> <?php echo htmlspecialchars($actividade['local']); ?></p>
                                <p><strong>Preço:</strong> <?php echo htmlspecialchars($actividade['preco']); ?> MZN</p>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <!-- Modal para Atualizar Senha -->
    <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordLabel">Atualizar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="updatePasswordForm">
                        <input type="hidden" name="update_password" value="1">
                        <div class="form-group">
                            <label for="currentPassword">Senha Atual</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Nova Senha</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirmação da Nova Senha</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required minlength="6">
                        </div>
                        <div id="passwordError" class="text-danger" style="display:none;">As senhas não coincidem ou são muito curtas.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Scripts (keep Bootstrap JS, remove AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Client-side validation for password form (optional, since PHP handles it)
        document.getElementById('updatePasswordForm').addEventListener('submit', function(e) {
            let newPassword = document.getElementById('newPassword').value;
            let confirmPassword = document.getElementById('confirmPassword').value;
            if (newPassword.length < 6 || newPassword !== confirmPassword) {
                document.getElementById('passwordError').style.display = 'block';
                e.preventDefault();
            }
        });
    </script>
</body>
</html>