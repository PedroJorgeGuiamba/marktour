<?php
session_start();
include '../../Controller/Empresa/Home.php';
// require_once __DIR__ . '/../../middleware/auth.php';
// Verificar se os IDs foram passados via query string
$id_utilizador = isset($_GET['id_utilizador']) ? (int)$_GET['id_utilizador'] : null;
$id_localizacao = isset($_GET['id_localizacao']) ? (int)$_GET['id_localizacao'] : null;

if (!$id_utilizador || !$id_localizacao) {
    die("Parâmetros inválidos. IDs de utilizador ou localização não fornecidos.");
}
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
            <form action="../../Controller/Empresa/empresa.php" method="post" id="formularioEmpresa">
                <div class="row" style="padding-top: 100px;">
                    <input type="hidden" name="id_utilizador" value="<?php echo $id_utilizador; ?>">
                    <input type="hidden" name="id_localizacao" value="<?php echo $id_localizacao; ?>">
                    <div class="form-group col-md-4">
                        <label for="nome" class="form-label">Nome da Empresa:</label>
                        <input type="tel" name="nome" class="form-control" id="nome"
                            placeholder="Marktour">
                        <span class="error_form" id="nome_error_message"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="nuit" class="form-label">Nuit:</label>
                        <input type="tel" name="nuit" class="form-control" id="nuit"
                            placeholder="85xxxxxxx">
                        <span class="error_form" id="nuit_error_message"></span>
                    </div>


                    <div class="form-group col-md-4">
                        <label for="descricao" class="form-label">Descricao:</label>
                        <input type="text" name="descricao" class="form-control" id="descricao"
                            placeholder="pedro.co.mz">
                        <span class="error_form" id="descricao_error_message"></span>
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
        $("#formularioEmpresa").validate({
            rules: {
                nome: {
                    required: true,
                    minlength: 2
                },
                nuit: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 9
                },
                descricao: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                nome: {
                    required: "Informe o nome da empresa.",
                    minlength: "O nome deve ter pelo menos 2 caracteres."
                },
                nuit: {
                    required: "Informe o NUIT.",
                    digits: "Apenas números são permitidos.",
                    minlength: "O NUIT deve ter 9 dígitos.",
                    maxlength: "O NUIT deve ter 9 dígitos."
                },
                descricao: {
                    required: "Informe a descrição.",
                    minlength: "A descrição deve ter pelo menos 2 caracteres."
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