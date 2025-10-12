<?php include_once '../../Controller/Auth/AuthController.php'; ?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../Style/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Estilo padrão para mensagens de erro, caso login.css não defina */
        .error_form {
            color: #dc3545; /* Vermelho do Bootstrap para erros */
            font-size: 0.875em;
            margin-top: 0.25rem;
            display: block;
        }
        .is-invalid ~ .error_form {
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="../View/Login.php">Entrar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container custom-container">
        <h2>LOGIN</h2>
        <hr />
        <form method="post" id="loginForm">
            <?php if (isset($erros)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erros) ?></div>
            <?php endif; ?>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="email" class="form-label">Endereço de Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="exemplo@dominio.com">
                            <span class="error_form" id="email_error_message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="senha" class="form-label">Palavra-passe</label>
                            <input type="password" name="senha" class="form-control" id="senha" placeholder="**********">
                            <span class="error_form" id="senha_error_message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <div class="g-recaptcha" data-sitekey="6LfkWeUrAAAAALLsp633wAUdfij0ooPeMKvwkH-6"></div>
                            <span class="error_form" id="recaptcha_error_message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <a href="Register.php" class="text-info">Não tenho uma conta</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success form-control">Entrar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- jQuery e jQuery Validate -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Adicionar método personalizado para validar reCAPTCHA
        $.validator.addMethod("recaptcha", function(value, element) {
            return grecaptcha.getResponse() !== "";
        }, "Por favor, selecione a caixa 'Não sou um robô'.");

        $("#loginForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                senha: {
                    required: true,
                    minlength: 2
                },
                "g-recaptcha-response": {
                    recaptcha: true
                }
            },
            messages: {
                email: {
                    required: "Por favor, insira o seu endereço de email.",
                    email: "Por favor, insira um endereço de email válido."
                },
                senha: {
                    required: "Por favor, insira a sua palavra-passe.",
                    minlength: "A palavra-passe deve ter pelo menos 5 caracteres."
                },
                "g-recaptcha-response": {
                    recaptcha: "Por favor, selecione a caixa 'Não sou um robô'."
                }
            },
            errorClass: "is-invalid",
            validClass: "is-valid",
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "g-recaptcha-response") {
                    error.appendTo("#recaptcha_error_message");
                } else if (element.attr("name") === "email") {
                    error.appendTo("#email_error_message");
                } else if (element.attr("name") === "senha") {
                    error.appendTo("#senha_error_message");
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
</body>

</html>