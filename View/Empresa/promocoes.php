<?php
// session_start();
// include '../../Controller/Empresa/Home.php';
// require_once __DIR__ . '/../../middleware/auth.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoções - MarkTour</title>
    <link rel="stylesheet" href="../../Style/empresas.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    <!-- Hero -->
    <main id="main1">
        <div class="content">
            <h1>Promoções Exclusivas</h1>
            <p>Descubra pacotes especiais em hotéis, resorts e experiências únicas</p>
            <a href="#promocoes" class="btn btn-lg btn-warning mt-3">Ver Promoções</a>
        </div>
    </main>

    <!-- Promoções -->
    <main class="container my-5" id="promocoes">
        <h2 class="text-center mb-4">Ofertas Imperdíveis</h2>
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="promo-card">
                    <img src="https://images.unsplash.com/photo-1501117716987-c8e1ecb210d7" alt="Hotel 1">
                    <div class="p-3">
                        <h5>Resort Beira-Mar</h5>
                        <p class="text-muted">Pacote 3 noites com tudo incluído.</p>
                        <p><strong>Preço: 15.000 MZN</strong></p>
                        <a href="#" class="btn btn-primary w-100">Reservar Agora</a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="promo-card">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb" alt="Hotel 2">
                    <div class="p-3">
                        <h5>Hotel Urbano Lux</h5>
                        <p class="text-muted">Fim de semana romântico na cidade.</p>
                        <p><strong>Preço: 9.500 MZN</strong></p>
                        <a href="#" class="btn btn-primary w-100">Reservar Agora</a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="promo-card">
                    <img src="https://images.unsplash.com/photo-1496417263034-38ec4f0b665a" alt="Hotel 3">
                    <div class="p-3">
                        <h5>Casa de Praia Privada</h5>
                        <p class="text-muted">Experiência exclusiva junto ao mar.</p>
                        <p><strong>Preço: 20.000 MZN</strong></p>
                        <a href="#" class="btn btn-primary w-100">Reservar Agora</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container-footer">
            <p>
                Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservadors
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>