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
$mensagem = '';

// Aprovar ou recusar uma reserva
if (isset($_POST['update_reserva'])) {
    $id_reserva = (int)($_POST['id_reserva'] ?? 0);
    $novo_estado = trim($_POST['novo_estado'] ?? '');

    if ($id_reserva <= 0 || !in_array($novo_estado, ['confirmada', 'cancelada'])) {
        $mensagem = ['tipo' => 'danger', 'texto' => 'ID da reserva ou estado inválidos.'];
    } else {
        try {
            mysqli_begin_transaction($conn);
            $stmt = $conn->prepare("UPDATE reserva SET estado = ? WHERE id_reserva = ?");
            $stmt->bind_param("si", $novo_estado, $id_reserva);
            if (!$stmt->execute()) {
                throw new Exception("Erro ao atualizar reserva: " . $conn->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("Nenhuma reserva encontrada ou já atualizada.");
            }
            $stmt->close();
            mysqli_commit($conn);
            $mensagem = ['tipo' => 'success', 'texto' => 'Reserva atualizada com sucesso.'];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Erro ao atualizar reserva: " . $e->getMessage());
            $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
        }
    }
}

// Listar reservas pendentes
try {
    $sql_reservas = "SELECT id_reserva, data_reserva, id_utilizador, data_checkin, data_checkout, total FROM reserva WHERE estado = 'pendente' ORDER BY data_reserva DESC";
    $result_reservas = $conn->query($sql_reservas);
    if (!$result_reservas) {
        throw new Exception("Erro ao listar reservas pendentes: " . $conn->error);
    }
    $reservas = $result_reservas->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao listar reservas: " . $e->getMessage());
    $reservas = [];
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerir Reservas</title>
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
        <h2 class="text-center">Gerir Reservas</h2>

        <!-- Mensagem de feedback -->
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensagem['texto']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Lista de Reservas Pendentes -->
        <h3>Reservas Pendentes</h3>
        <?php if (empty($reservas)): ?>
            <p class="text-center">Nenhuma reserva pendente no momento.</p>
        <?php else: ?>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Data da Reserva</th>
                        <th>ID Utilizador</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['data_reserva']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['id_utilizador']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['data_checkin']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['data_checkout']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['total']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                    <input type="hidden" name="novo_estado" value="confirmada">
                                    <button type="submit" name="update_reserva" class="btn btn-success btn-sm" onclick="return confirm('Tem certeza que deseja aprovar esta reserva?');">Aprovar</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                    <input type="hidden" name="novo_estado" value="cancelada">
                                    <button type="submit" name="update_reserva" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja recusar esta reserva?');">Recusar</button>
                                </form>
                            </td>
                        </tr>
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
</body>
</html>