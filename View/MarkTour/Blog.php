<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Marktour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../Style/MarkTour.css">
</head>

<body>
    <header>
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
                                <a href="https://www.instagram.com/marktourreservasonline/" class="me-3 text-white fs-4"><i class="fa-brands fa-square-instagram" style="color: #000000;"></i></a>

                            </li>
                            <li class="nav-item">
                                <a href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#" class="me-3 text-white fs-4"><i class="fa-brands fa-facebook" style="color: #000000;"></i></a>

                            </li>
                            <li class="nav-item">
                                <a href="/Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" href="portalDoUtilizador.php">Home</a>
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
                        <li><a class="dropdown-item" href="#">A Pe</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMarkTour" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMarkTour">
                        <li><a class="dropdown-item" href="Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="faq.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="Blog.php">Blog</a></li>
                        <li><a class="dropdown-item" href="Reviews.php">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>


    <main class="container my-5">
        <h1 class="page-title text-center mb-5">Nosso Blog</h1>

        <div class="row g-4">
            <!-- Post 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="https://source.unsplash.com/600x400/?travel,beach" class="card-img-top" alt="Post 1">
                    <div class="card-body">
                        <h5 class="card-title">Descubra as melhores praias de Moçambique</h5>
                        <p class="card-text text-muted">
                            Uma viagem inesquecível pelas águas cristalinas e areia branca das praias mais bonitas...
                        </p>
                        <a href="#" class="btn btn-primary">Ler mais</a>
                    </div>
                </div>
            </div>

            <!-- Post 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="https://source.unsplash.com/600x400/?hotel,resort" class="card-img-top" alt="Post 2">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 Resorts de Luxo</h5>
                        <p class="card-text text-muted">
                            Conheça os resorts mais incríveis para relaxar, com conforto e experiências únicas...
                        </p>
                        <a href="#" class="btn btn-primary">Ler mais</a>
                    </div>
                </div>
            </div>

            <!-- Post 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="https://source.unsplash.com/600x400/?adventure,travel" class="card-img-top" alt="Post 3">
                    <div class="card-body">
                        <h5 class="card-title">Passeios de aventura que não pode perder</h5>
                        <p class="card-text text-muted">
                            Desde mergulho a passeios de barco, viva experiências emocionantes e memoráveis...
                        </p>
                        <a href="#" class="btn btn-primary">Ler mais</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

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