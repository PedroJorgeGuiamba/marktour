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
    <title>Empresa - Cadastro</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/empresa.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header>
        <!-- Nav principal -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp" alt="Marktour Logo" height="30">
                <div class="nav-modal">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 text-white fs-4"><i class="fa-brands fa-square-instagram" style="color: #3a4c91;"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#" class="me-3 text-white fs-4"><i class="fa-brands fa-facebook" style="color: #3a4c91;"></i></a>
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
            <form action="../../Controller/Empresa/localizacaoEmpresa.php" method="post" id="formularioLocalizacao" style="padding-top:100px ;">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="provincia" class="form-label">Provincia:</label>
                        <input type="text" name="provincia" class="form-control" id="provincia"
                            placeholder="Maputo">
                        <span class="error_form" id="provincia_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="distrito" class="form-label">Distrito:</label>
                        <input type="text" name="distrito" class="form-control" id="distrito"
                            placeholder="Kampfumo">
                        <span class="error_form" id="distrito_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="bairro" class="form-label">Bairro:</label>
                        <input type="text" name="bairro" class="form-control" id="bairro"
                            placeholder="Malhangalene">
                        <span class="error_form" id="bairro_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="postoAdministrativo" class="form-label">Posto Administrativo:</label>
                        <input type="text" name="postoAdministrativo" class="form-control" id="postoAdministrativo"
                            placeholder="Kampfumo">
                        <span class="error_form" id="posto_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="localidade" class="form-label">Localidade:</label>
                        <input type="text" name="localidade" class="form-control" id="localidade"
                            placeholder="xxxxxxx">
                        <span class="error_form" id="localidade_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="avenida" class="form-label">Avenida:</label>
                        <input type="avenida" name="avenida" class="form-control" id="avenida"
                            placeholder="Av. Acordos de Lusaka">
                        <span class="error_form" id="avenida_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="rua" class="form-label">rua:</label>
                        <input type="text" name="rua" class="form-control" id="rua"
                            placeholder="Rua Das Acacias">
                        <span class="error_form" id="rua_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="andar" class="form-label">andar:</label>
                        <input type="text" name="andar" class="form-control" id="andar"
                            placeholder="Primeiro">
                        <span class="error_form" id="andar_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="endereco" class="form-label">Endereco:</label>
                        <input type="text" name="endereco" class="form-control" id="endereco"
                            placeholder="endereco">
                        <span class="error_form" id="endereco_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="codigoPostal" class="form-label">Codigo Postal:</label>
                        <input type="text" name="codigoPostal" class="form-control" id="codigoPostal"
                            placeholder="1101">
                        <span class="error_form" id="codigoPostal_error_message"></span>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label for="latitude" class="form-label">latitude:</label>
                    <input type="text" name="latitude" class="form-control" id="latitude"
                        placeholder="latitude">
                    <span class="error_form" id="latitude_error_message"></span>
                </div>

                <div class="form-group col-md-4">
                    <label for="longitude" class="form-label">longitude:</label>
                    <input type="text" name="longitude" class="form-control" id="longitude"
                        placeholder="longitude">
                    <span class="error_form" id="longitude_error_message"></span>
                </div>

                <div class="form-group col-md-4">
                    <label for="referencia" class="form-label">referencia:</label>
                    <input type="text" name="referencia" class="form-control" id="referencia"
                        placeholder="referencia">
                    <span class="error_form" id="referencia_error_message"></span>
                </div>
                <div class="row" style="padding-top: 20px;">
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
        $("#formularioLocalizacao").validate({
            rules: {
                provincia: {
                    required: true,
                    minlength: 2
                },
                distrito: {
                    required: true,
                    minlength: 2
                },
                bairro: {
                    required: false,
                    minlength: 2
                },
                postoAdministrativo: {
                    required: false,
                    minlength: 2
                },
                localidade: {
                    required: false,
                    minlength: 2
                },
                avenida: {
                    required: false,
                    minlength: 2
                },
                rua: {
                    required: false,
                    minlength: 2
                },
                andar: {
                    required: false,
                    minlength: 1
                },
                endereco: {
                    required: false,
                    minlength: 2
                },
                codigoPostal: {
                    required: false,
                    digits: true,
                    minlength: 4,
                    maxlength: 4
                },
                latitude: {
                    required: false,
                    minlength: 1
                },
                longitude: {
                    required: false,
                    minlength: 1
                },
                referencia: {
                    required: false,
                    minlength: 2
                }
            },
            messages: {
                provincia: {
                    required: "Informe a província.",
                    minlength: "A província deve ter pelo menos 2 caracteres."
                },
                distrito: {
                    required: "Informe o distrito.",
                    minlength: "O distrito deve ter pelo menos 2 caracteres."
                },
                bairro: {
                    minlength: "O bairro deve ter pelo menos 2 caracteres."
                },
                postoAdministrativo: {
                    minlength: "O posto administrativo deve ter pelo menos 2 caracteres."
                },
                localidade: {
                    minlength: "A localidade deve ter pelo menos 2 caracteres."
                },
                avenida: {
                    minlength: "A avenida deve ter pelo menos 2 caracteres."
                },
                rua: {
                    minlength: "A rua deve ter pelo menos 2 caracteres."
                },
                andar: {
                    minlength: "O andar deve ter pelo menos 1 caractere."
                },
                endereco: {
                    minlength: "O endereço deve ter pelo menos 2 caracteres."
                },
                codigoPostal: {
                    digits: "Apenas números são permitidos.",
                    minlength: "O código postal deve ter 4 dígitos.",
                    maxlength: "O código postal deve ter 4 dígitos."
                },
                latitude: {
                    minlength: "A latitude deve ter pelo menos 1 caracteres."
                },
                longitude: {
                    minlength: "A longitude deve ter pelo menos 1 caracteres."
                },
                referencia: {
                    minlength: "A referencia deve ter pelo menos 2 caracteres."
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