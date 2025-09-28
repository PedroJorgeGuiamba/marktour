<?php
session_start();
// include './Controller/Utilizador/Home.php';
include '../../Controller/Utilizador/Home.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Utilizador</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/home.css">
    <style>
        /* ====== Motor de Busca ====== */
        .busca {
            padding: 40px 20px;
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .busca h2 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #000000ff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .busca form .form-control {
            border-radius: 8px;
            border: none;
            padding: 12px;
            font-size: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .busca form .form-control:focus {
            border: 2px solid #fff;
            outline: none;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.7);
        }

        .busca form button {
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            background: #023e8a;
            border: none;
            color: #fff;
            transition: background 0.3s ease;
        }

        .busca form button:hover {
            background: #03045e;
        }

        /* ====== Resultados ====== */
        #resultadosBusca {
            margin-top: 30px;
        }

        #resultadosBusca .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        #resultadosBusca .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        #resultadosBusca .card img {
            height: 200px;
            object-fit: cover;
        }

        #resultadosBusca .card-body {
            padding: 20px;
        }

        #resultadosBusca .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #0077b6;
        }

        #resultadosBusca .card-text {
            font-size: 14px;
            color: #555;
        }
    </style>

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
            </ul>
        </nav>
    </header>

    <section class="busca my-6">
        <div class="container">
            <h2 class="text-center mb-10" style="padding-top: 100px;">Encontre sua Acomodação</h2>

            <form id="formBusca" class="row g-3 justify-content-center text-center">
                <div class="col-md-3">
                    <label style="color:black"></label>
                    <input type="text" name="localizacao" class="form-control" placeholder="Digite o destino" required>
                </div>
                <div class="col-md-2">
                    <label style="color:black">Check-in</label>
                    <input type="date" name="checkin" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label style="color:black">Check-out</label>
                    <input type="date" name="checkout" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label style="color:black">Hóspedes</label>
                    <input type="number" name="hospedes" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-2 d-grid align-items-end">
                    <button ltype="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>

            <!-- Resultados -->
            <div id="resultadosBusca" class="row row-cols-1 row-cols-md-3 g-4 mt-4"></div>
        </div>
    </section>
    <main>
        <section class="noticias">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://www.moz.life/wp-content/uploads/2017/03/bars-in-tofo.jpg.optimal-822x548.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional
                                content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rodapé -->
        <footer>
            <div class="container-footer">
                <p>
                    Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservadors
                </p>
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