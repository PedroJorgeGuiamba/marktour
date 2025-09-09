<?php
// session_start();
// include '../../Controller/Empresa/Home.php';
// require_once __DIR__ . '/../../middleware/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa - Cadastro de Eventos</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/empresa.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="formulario" style="margin: 150px;">
            <form action="../../Controller/Empresa/EventosController.php" method="post" id="formularioEventos">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome" class="form-label">Nome do Evento:</label>
                        <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome do Evento">
                        <span class="error_form" id="nome_error_message"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <textarea name="descricao" class="form-control" id="descricao" placeholder="Descrição do evento"></textarea>
                        <span class="error_form" id="descricao_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="data_evento" class="form-label">Data do Evento:</label>
                        <input type="date" name="data_evento" class="form-control" id="data_evento">
                        <span class="error_form" id="data_evento_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="hora_inicio" class="form-label">Hora de Início:</label>
                        <input type="time" name="hora_inicio" class="form-control" id="hora_inicio">
                        <span class="error_form" id="hora_inicio_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="hora_fim" class="form-label">Hora de Fim:</label>
                        <input type="time" name="hora_fim" class="form-control" id="hora_fim">
                        <span class="error_form" id="hora_fim_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="local" class="form-label">Local:</label>
                        <input type="text" name="local" class="form-control" id="local" placeholder="Local do evento">
                        <span class="error_form" id="local_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="organizador" class="form-label">Organizador:</label>
                        <input type="text" name="organizador" class="form-control" id="organizador" placeholder="Nome do organizador">
                        <span class="error_form" id="organizador_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" class="form-control" id="status">
                            <option value="ativo">Ativo</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="concluido">Concluído</option>
                        </select>
                        <span class="error_form" id="status_error_message"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success form-control">Cadastrar Evento</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <div class="container-footer">
            <p>
                Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservadors
            </p>
        </div>
    </footer>
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
        // Validação do formulário
        $("#formularioEventos").validate({
            rules: {
                nome: {
                    required: true,
                    minlength: 3
                },
                descricao: {
                    required: false,
                    minlength: 5
                },
                data_evento: {
                    required: true
                },
                hora_inicio: {
                    required: false
                },
                hora_fim: {
                    required: false
                },
                local: {
                    required: false,
                    minlength: 3
                },
                organizador: {
                    required: false,
                    minlength: 3
                },
                status: {
                    required: true
                }
            },
            messages: {
                nome: {
                    required: "Informe o nome do evento.",
                    minlength: "O nome deve ter pelo menos 3 caracteres."
                },
                descricao: {
                    minlength: "A descrição deve ter pelo menos 5 caracteres."
                },
                data_evento: {
                    required: "Informe a data do evento."
                },
                local: {
                    minlength: "O local deve ter pelo menos 3 caracteres."
                },
                organizador: {
                    minlength: "O organizador deve ter pelo menos 3 caracteres."
                },
                status: {
                    required: "Selecione o status do evento."
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