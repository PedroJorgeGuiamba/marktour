<?php include_once '../../Controller/Auth/AuthController.php'; ?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../Style/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function validarPost() {
            if (grecaptcha.getResponse() != "") return true;

            alert('Selecione a caixa de "Não sou um robô"');
            return false;
        }
    </script>
</head>

<body>

    <header>
        <nav class="navbar">
            <div class="nav-container">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp" alt="Marktour Logo">
                <div class="nav-modal">
                    <button class="nav-toggle" type="button">
                        <span class="nav-toggle-icon"></span>
                    </button>
                    <div class="nav-menu">
                        <ul class="nav-list">
                            <li>
                                <a class="nav-link" href="../View/Login.php">Entrar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>LOGIN</h2>

        <hr />

        <form method="post" onsubmit="return validarPost()">
            <?php if (isset($erros)): ?>
                <div class="alert-error"><?= $erros ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="example@gmail.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="senha" class="form-label">Password</label>
                            <input type="password" name="senha" class="form-control" id="senha" placeholder="**********">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6Ldecc4rAAAAACtS7KVdg59PJga_XoEXaC0xTGhg"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <a href="Register.php" class="text-link">I don't have an account</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-submit">Entrar</button>
            </div>

        </form>
    </div>

</body>

</html>