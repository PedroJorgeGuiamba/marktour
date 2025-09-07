<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marktour</title>

    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="Style/home.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
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
                    <a class="nav-link" aria-current="page" href="../Empresa/promocoes.php">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
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
        <!-- Motor de buscas -->
        <section class="busca my-6">
            <div class="container">
                <h2 class="text-center mb-10" style="padding-top: 100px;">Encontre sua Acomodação</h2>
                <form id="formBusca" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="localizacao" class="form-control" placeholder="Digite o destino" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="checkin" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="checkout" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="hospedes" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>

                <!-- Resultados -->
                <div id="resultadosBusca" class="row row-cols-1 row-cols-md-3 g-4 mt-4"></div>
            </div>
        </section>

        <!-- Noticias -->
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
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        // AJAX para buscar hotéis
        document.getElementById("formBusca").addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("Controller/Hotel/", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    let container = document.getElementById("resultadosBusca");
                    container.innerHTML = "";

                    if (data.length === 0) {
                        container.innerHTML = "<p class='text-center text-muted'>Nenhum resultado encontrado.</p>";
                        return;
                    }

                    data.forEach(hotel => {
                        container.innerHTML += `
                        <div class="col">
                            <div class="card h-100">
                                <img src="${hotel.imagem}" class="card-img-top" alt="${hotel.nome}">
                                <div class="card-body">
                                    <h5 class="card-title">${hotel.nome}</h5>
                                    <p class="card-text">${hotel.descricao}</p>
                                    <p><strong>Preço:</strong> ${hotel.preco} MT / noite</p>
                                </div>
                            </div>
                        </div>
                    `;
                    });
                })
                .catch(err => console.error("Erro: ", err));
        });
    </script>

</body>

</html>