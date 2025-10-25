<?php
include '../../Conexao/conector.php';
$conexao = new Conector();
$conn = $conexao->getConexao();
$userId = $_SESSION['id_utilizador'] ?? 0; // 0 if not logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId > 0) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'mark_read' && isset($_POST['id_notificacao'])) {
            $notifId = intval($_POST['id_notificacao']);
            $sql = "UPDATE notificacao SET lida = 1 WHERE id_notificacao = ? AND id_utilizador = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssi", $notifId, $userId);
            $conn->query($sql);
        } elseif ($_POST['action'] === 'mark_all_read') {
            $sql = "UPDATE notificacao SET lida = 1 WHERE id_utilizador = $userId AND deleted = 0";
            $conn->query($sql);
        } elseif ($_POST['action'] === 'clear_all') {
            $sql = "UPDATE notificacao SET deleted = 1 WHERE id_utilizador = $userId";
            $conn->query($sql);
        }
    }
    // Reload the page after action
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch unread count only if logged in
$unreadCount = 0;
$notifications = [];
if ($userId > 0) {
    $sql = "SELECT COUNT(*) as count FROM notificacao WHERE id_utilizador = $userId AND lida = 0 ";
    $result = $conn->query($sql);
    $unreadCount = $result ? $result->fetch_assoc()['count'] : 0;

    // Fetch all notifications for dropdown
    $sql = "SELECT * FROM notificacao WHERE id_utilizador = $userId ORDER BY lida ASC, data DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
    }
}