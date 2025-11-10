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
    <link rel="stylesheet" href="../../Style/empresas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script> <!-- For extension validation -->
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
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 text-white fs-4">
                                    <i class="fa-brands fa-square-instagram" style="color: #3a4c91;"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#" class="me-3 text-white fs-4">
                                    <i class="fa-brands fa-facebook" style="color: #3a4c91;"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="View/Auth/Login.php" class="btn btn-danger">Login</a>
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
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownAcomodacoes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownAcomodacoes">
                        <li><a class="dropdown-item" href="#">Hoteis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                        <li><a class="dropdown-item" href="#">Lounges</a></li>
                        <li><a class="dropdown-item" href="#">Casas De Praia</a></li>
                        <li><a class="dropdown-item" href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownPasseios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownPasseios">
                        <li><a class="dropdown-item" href="#">A Pé</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../Empresa/promocoes.php">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMarkTour" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMarkTour">
                        <li><a class="dropdown-item" href="View/MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="View/MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="View/MarkTour/faq.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="View/MarkTour/Blog.php">Blog</a></li>
                        <li><a class="dropdown-item" href="View/MarkTour/Reviews.php">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="formulario" style="padding-top: 100px; margin: 70px;">
            <form action="../../Controller/Empresa/RegistrarAlojamento.php" method="post" id="formularioAlojamento" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="nome" class="form-label">Nome:</label>
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
                        <label for="descricao" class="form-label">Descrição:</label>
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
                    <div class="form-group col-md-4">
                        <label for="imagem" class="form-label">Capa do Alojamento:</label>
                        <input type="file" name="imagem" class="form-control" id="imagem" accept="image/jpeg,image/png,image/jpg,image/gif">
                        <span class="error_form" id="imagem_error_message"></span>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3" style="padding-top: 20px;">
                                <button type="submit" class="btn btn-success form-control">Register</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </main>
    <!-- Scripts do BootStrap -->
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
                    required: true
                },
                descricao: {
                    minlength: 2
                },
                precoPorNoite: {
                    minlength: 2
                },
                numeroDeQuartos: {
                    digits: true
                },
                imagem: {
                    extension: "jpg|jpeg|png|gif"
                }
            },
            messages: {
                nome: {
                    required: "Informe o nome.",
                    minlength: "O nome deve ter pelo menos 2 caracteres."
                },
                descricao: {
                    minlength: "O descricao deve ter pelo menos 2 caracteres."
                },
                precoPorNoite: {
                    minlength: "O preço por noite deve ter pelo menos 2 digitos."
                },
                imagem: {
                    extension: "Por favor, selecione uma imagem válida (jpg, jpeg, png, gif)."
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