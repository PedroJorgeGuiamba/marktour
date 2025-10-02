<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Marktour</title>
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

    <div class="container">
        <h1 class="page-title">Sobre Nós</h1>

        <div class="card-1">
            <h1>Quem somos nós?</h1>
            <p>Companhia moçambicana que presta serviços
                de consultoria na área de marketing e
                comercialização de serviços turisticos. Criada
                com intuíto de ser o Elo de ligação entre
                todos intervenientes cadeia do
                desenvolvimento do turismo, com
                modernidade, facilidade, e simplicidade e
                promover o destino turistico em
                Moçambique
            </p>
        </div>

        <div class="card-2">
            <h1>O que fazemos</h1>
            <div>
                <h2>Consultoria em Hotelaria e Turismo</h2>
                <p>
                    No ambito da consultoria, efectuamos uma analise
                    do funcionamento da sua empresa e verificamos áreas em
                    defice de funcionamento. Efectuamos as respectivas melhorias da
                    área em questão devolvendo as melhores performances.
                </p>
                <h2>Formação e Treinamento</h2>
                <p>Estamos orientados em formação técnico profissional de
                    curta duração nas áreas de desenvolvimento das empresas atraves do capital humano
                </p>
                <h2>Reservas Online</h2>
                <p>Providenciamos serviços de passeios turísticos e acomodação turística.</p>
            </div>
        </div>

        <div class="card-3">
            <div class="vision-mission-values">
                <h2><i class="fas fa-eye"></i> Visão</h2>
                <p>
                    Tornar-mo-nos a referencia a nível nacional nas áreas em formação técnico
                    profissional e em marketing na promoção de destinos turisticos e por ultimo,
                    como elo de ligação entre produtores de serviços hoteleiros e publico em geral.
                </p>
            </div>

            <div class="vision-mission-values">
                <h2><i class="fas fa-bullseye"></i> Missão</h2>
                <p>
                    Oferecer competências válidas de gestão e actuais a nível nacional e internacional,
                    nas áreas em Hotelaria, Turismo, Marketing para atender as necessidades dos nossos clientes,
                    aliando a excelência a técnica visando a qualidade de serviços prestados.
                </p>
            </div>

            <div class="vision-mission-values">
                <h2><i class="fas fa-heart"></i> Valores</h2>
                <p>
                    Promovemos os valores de bom senso, beleza, respeito mutuo, transparencia, reciprocidade,
                    cortesia, dedicação, equidade e responsabilidade.
                </p>
            </div>
        </div>
    </div>

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