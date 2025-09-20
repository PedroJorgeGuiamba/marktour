<?php
    session_start();
    include '../../Controller/Empresa/Home.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/empresa.css">
    <title>Home</title>
</head>
<body>
    <h1>Hello, <?php echo $_SESSION['email']; echo $_SESSION['role'] ?></h1>
</body>
</html>