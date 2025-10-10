<?php
session_start();
require_once __DIR__ . '/../../config.php';
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../Style/home.css">
    <style>
        .facebook-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #4267B2;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .facebook-btn i {
            margin-bottom: 5px;
            font-size: 24px;
        }
        .facebook-btn:hover {
            background-color: #365899;
            color: white;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp">
                <div class="nav-modal">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li>
                                <a class="nav-link" href="../View/Login.php">Entrar</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </nav>
    </header>

    <div class="container custom-container">
        <h2>REGISTRO</h2>
        <hr />

        <form method="post" action="../../Controller/Auth/AuthRegisterController.php">
            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger"><?= $erros ?></div>
            <?php endif; ?>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome completo</label>
                            <input type="text" name="nome" class="form-control" id="nome" placeholder="Seu nome/ Nome da empresa ">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="email" class="form-label">Endereço de Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="seuemail@dominio.com">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="********">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de utilizador</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="cliente" selected>Cliente</option>
                                <option value="empresa">Empresa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12 mb-3">
                <button class="btn btn-primary form-control" onclick="fbLogin()">Entrar com Facebook</button>
            </div>

            <div class="form-group">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary form-control">Registar</button>
                </div>
            </div>
        </form>

        <div class="form-group col-md-12 mb-3">
            <a href="https://www.facebook.com/v20.0/dialog/oauth?client_id=<?= FACEBOOK_APP_ID ?>&redirect_uri=<?= urlencode(FACEBOOK_REDIRECT_URI) ?>&scope=email,public_profile" class="facebook-btn">
                <i class="fab fa-facebook-f"></i>
                <span>Sign Up</span>
            </a>
        </div>
    </div>

</body>

</html>
