// perfil.php
<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../Conexao/conector.php'; // Adjust path if needed
$conexao = new Conector();
$conn = $conexao->getConexao();

$response = ['utilizador' => null, 'empresa' => null];

if (!isset($_SESSION['id_utilizador'])) {
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['id_utilizador'];

// Fetch user with prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user) {
    $response['utilizador'] = $user;
    
    // If type is 'empresa', fetch company
    if ($user['tipo'] === 'empresa') {
        $stmt = mysqli_prepare($conn, "SELECT * FROM empresa WHERE id_utilizador = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $empresa = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if ($empresa) {
            $response['empresa'] = $empresa;
        }
    }
}

echo json_encode($response);
?>