<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

include '../../Controller/Empresa/Home.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal da Empresa</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../Style/empresas.css">
    <style>
        .cart-icon {
            position: relative;
            margin-left: 15px;
        }

        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
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
                                <a href="Carrinho.php" class="cart-icon me-3">
                                    <i class="fas fa-shopping-cart fs-4" style="color: #3a4c91;"></i>
                                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
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
                        <li><a class="dropdown-item" href="MeusServicos.php">Hoteis</a></li>
                        <li><a class="dropdown-item" href="MeusServicos.php">Resorts</a></li>
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
                    <a class="nav-link" aria-current="page" href="eventos.php">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/faq.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Blog.php">Blog</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Reviews.php">Reviews</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="RegistrarAlojamento.php">Registrar Alojamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="MeusAlojamentos.php">Ver Alojamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="RegistrarPasseio.php">Registrar Passeios</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Seção Hero / Apresentação -->
        <section class="hero d-flex align-items-center text-center text-white" style="background: url('https://www.visitarafrica.com/media/k2/items/cache/5e58b7b25b6a8f9a7ad4b2e96a6e51bc_XL.jpg') center/cover no-repeat; height: 80vh;">
            <div class="container">
                <h1 class="display-4 fw-bold">Descubra o Melhor de Moçambique</h1>
                <p class="lead mb-4">Aventure-se por praias paradisíacas, cultura vibrante e experiências inesquecíveis com a Marktour.</p>
                <a href="#pacotes" class="btn btn-primary btn-lg">Explorar Pacotes</a>
            </div>
        </section>

        <!-- Seção de Busca de Acomodações -->
        <section class="busca my-5">
            <div class="container">
                <h2 class="text-center mb-4">Encontre a Sua Próxima Aventura</h2>
                <form id="formBusca" class="row g-3 justify-content-center text-center">
                    <div class="col-md-3">
                        <input type="text" name="localizacao" class="form-control" placeholder="Destino" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="checkin" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="checkout" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="hospedes" class="form-control" min="1" value="1" placeholder="Hóspedes" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
                <div id="resultadosBusca" class="row row-cols-1 row-cols-md-3 g-4 mt-4"></div>
            </div>
        </section>

        <!-- Seção de Pacotes Turísticos -->
        <section id="pacotes" class="pacotes py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-5">Pacotes Mais Populares</h2>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="Tofo">
                            <div class="card-body">
                                <h5 class="card-title">Praias de Tofo</h5>
                                <p class="card-text">Mergulhe nas águas cristalinas de Tofo, com passeios de barco e observação de baleias.</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-outline-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://cdn.britannica.com/65/162165-050-40AE51D2/Maputo-Mozambique.jpg" class="card-img-top" alt="Maputo">
                            <div class="card-body">
                                <h5 class="card-title">Tour Cultural em Maputo</h5>
                                <p class="card-text">Descubra a arte, gastronomia e arquitetura da vibrante capital moçambicana.</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-outline-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://www.african-meccasafaris.com/wp-content/uploads/mozambique-gorongosa-park.jpg" class="card-img-top" alt="Gorongosa">
                            <div class="card-body">
                                <h5 class="card-title">Safari em Gorongosa</h5>
                                <p class="card-text">Explore a vida selvagem e paisagens incríveis do Parque Nacional da Gorongosa.</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-outline-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção de Notícias / Blog -->
        <section class="noticias py-5">
            <div class="container">
                <h2 class="text-center mb-5">Notícias e Dicas de Viagem</h2>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="Tofo">
                            <div class="card-body">
                                <h5 class="card-title">5 Motivos para Visitar Inhambane</h5>
                                <p class="card-text">História, praias, mergulho e hospitalidade local — um destino imperdível!</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="Ilha de Moçambique">
                            <div class="card-body">
                                <h5 class="card-title">Ilha de Moçambique: Património Mundial</h5>
                                <p class="card-text">Conheça o berço histórico do país e suas atrações culturais únicas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="Vilanculos">
                            <div class="card-body">
                                <h5 class="card-title">Aventura em Vilanculos</h5>
                                <p class="card-text">Descubra o arquipélago de Bazaruto e as experiências únicas de mergulho.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <!-- Rodapé -->
    <footer>
        <div class="container-footer">
            <p>Copyright 2023 © Marktour | Todos Direitos Reservados <span>MARKTOUR.</span></p>
        </div>
    </footer>
    </main>

    <!-- Scripts do BootStrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
</body>

</html>