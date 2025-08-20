<?php
session_start();
include '../../Controller/Empresa/Home.php';
// require_once __DIR__ . '/../../middleware/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alojamentos</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/home.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header>
        <!-- Nav principal -->
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
                            <!-- Instagram -->
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="https://www.instagram.com/marktourreservasonline/">Instagram</a>
                            </li>
                            <!-- Facebook -->
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#">Facebook</a>
                            </li>
                            <li class="nav-item">
                                <a href="/Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Nav Secundária -->
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">Hoteis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                        <li><a class="dropdown-item" href="#">Lounges</a></li>
                        <li><a class="dropdown-item" href="#">Casas De Praia</a></li>
                        <li><a class="dropdown-item" href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">A Pe</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">Sobre</a></li>
                        <li><a class="dropdown-item" href="#">Contactos</a></li>
                        <li><a class="dropdown-item" href="#">FAQ</a></li>
                        <li><a class="dropdown-item" href="#">Blog</a></li>
                        <li><a class="dropdown-item" href="#">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="formulario">
            <form action="../../Controller/Empresa/RegistrarAlojamento.php" method="post" id="formularioAlojamento">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="nome" class="form-label">nome:</label>
                        <input type="text" name="nome" class="form-control" id="nome"
                            placeholder="Suite Presidencial">
                        <span class="error_form" id="nome_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select name="tipo" class="form-control" id="tipo">
                            <option value="apartamento" selected>Apartamento</option>
                            <option value="hotel">Hotel</option>
                            <option value="lounges">Lounges</option>
                            <option value="resorts">Resorts</option>
                            <option value="casaDePraia">Casa De Praia</option>
                        </select>
                        <span class="error_form" id="tipo_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="descricao" class="form-label">Descricao:</label>
                        <input type="text" name="descricao" class="form-control" id="descricao"
                            placeholder="Suite">
                        <span class="error_form" id="descricao_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="precoPorNoite" class="form-label">Preço por noite:</label>
                        <input type="text" name="precoPorNoite" class="form-control" id="precoPorNoite"
                            placeholder="200000">
                        <span class="error_form" id="preco_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="numeroDeQuartos" class="form-label">Numero De Quartos:</label>
                        <input type="number" name="numeroDeQuartos" class="form-control" id="numeroDeQuartos">
                        <span class="error_form" id="numeroDeQuartos_error_message"></span>
                    </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success form-control">Register</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </main>


    <!-- Scripts do BootStrap -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        //Validation
        $("#formularioAlojamento").validate({
            rules: {
                nome: {
                    required: true,
                    minlength: 2
                },
                tipo: {
                    required: true,
                    minlength: 2
                },
                descricao: {
                    required: false,
                    minlength: 2
                },
                precoPorNoite: {
                    required: false,
                    digits: true,
                    minlength: 2
                },
                numeroDeQuartos: {
                    required: false,
                    digits: true,
                    minlength: 2
                }
            },
            messages: {
                nome: {
                    required: "Informe o nome.",
                    minlength: "O nome deve ter pelo menos 2 caracteres."
                },
                tipo: {
                    required: "Informe o tipo.",
                    minlength: "O tipo deve ter pelo menos 2 caracteres."
                },
                descricao: {
                    minlength: "O descricao deve ter pelo menos 2 caracteres."
                },
                precoPorNoite: {
                    minlength: "O preço por noite deve ter pelo menos 2 digitos."
                },
                numeroDeQuartos: {
                    minlength: "O numero de quartos deve ter pelo menos 2 caracteres."
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
                error.insertAfter(element);
            }
        });
    </script>
</body>

</html>