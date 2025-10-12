<?php
session_start();
if (!isset($_SESSION['google_email'])) {
    header("Location: /marktour/View/Auth/Register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Tipo de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Selecione o Tipo de Usuário</h2>
        <form method="post" action="/marktour/Controller/Auth/ProcessarTipoController.php">
            <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['google_email']) ?>">
            <input type="hidden" name="nome" value="<?= htmlspecialchars($_SESSION['google_name']) ?>">
            <div class="form-group mb-3">
                <label for="tipo">Tipo de utilizador</label>
                <select name="tipo" class="form-control" id="tipo" required>
                    <option value="cliente">Cliente</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Confirmar</button>
        </form>
    </div>
</body>
</html>