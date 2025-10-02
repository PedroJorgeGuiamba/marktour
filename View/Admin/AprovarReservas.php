<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Controller/Auth/LoginController.php");
    exit();
}

$conexao = new Conector();
$conn = $conexao->getConexao();

$sql = "SELECT * FROM reserva WHERE id_utilizador = ? AND estado = 'pendente'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$reservas = $result->fetch_all(MYSQLI_ASSOC);

// Lógica de aprovação (apenas para administradores)
if (isset($_GET['aprovar']) && isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') {
    $id_reserva = $_GET['aprovar'];
    $stmt_aprovar = $conn->prepare("UPDATE reserva SET estado = 'confirmada' WHERE id_reserva = ?");
    $stmt_aprovar->bind_param("i", $id_reserva);
    $stmt_aprovar->execute();
    header("Location: /View/Empresa/perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - MarkTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Minhas Reservas Pendente</h2>
        <?php if (empty($reservas)): ?>
            <div class="alert alert-info">Nenhuma reserva pendente encontrada.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Data Reserva</th>
                        <th>Total (MZN)</th>
                        <th>Estado</th>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <th>Ação</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo $reserva['id_reserva']; ?></td>
                            <td><?php echo $reserva['data_reserva']; ?></td>
                            <td><?php echo number_format($reserva['total'], 2); ?></td>
                            <td><?php echo $reserva['estado']; ?></td>
                            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                                <td>
                                    <a href="?aprovar=<?php echo $reserva['id_reserva']; ?>" class="btn btn-success btn-sm">Aprovar</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="../Empresa/portalDaEmpresa.php" class="btn btn-primary">Voltar ao Início</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>