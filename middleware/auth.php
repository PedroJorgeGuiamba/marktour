<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Helpers/auth.php';

$auth = new AuthMiddleware();
if (!$auth->verificarAutenticacao()) {
    header("Location: /marktour/View/Auth/Login.php");
    exit();
}