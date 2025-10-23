<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Model/Alojamento.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

class RecuperarAlojamentos
{
    public function listarEventos()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        // Consulta para recuperar todos os eventos
        $sql = "SELECT * FROM eventos";
        $result = $conn->query($sql);
        $eventos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
        }
        return $eventos;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Alojamentos - MarkTour</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
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
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Sair</a>
                            </li>
                            <li class="nav-item">
                                <a href="carrinho.php" class="cart-icon">
                                    <i class="fas fa-shopping-cart fs-4" style="color: #3a4c91;"></i>
                                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                                </a>
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
                    <a class="nav-link active" aria-current="page" href="portalDoUtilizador.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="MeusAlojamentos.php">Acomodações</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="portalDoUtilizador.php">Passeios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMarkTour" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMarkTour">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Contactos.php">Contactos</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/faq.php">FAQ</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/Blog.php">Blog</a></li>
                    </ul>
                </li>
                                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="destaques py-5 bg-light" style="padding-top: 100px;">
        <!-- Destaques e ofertas especiais -->
        <section class="eventos py-5 bg-light">
            <div class="container">
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Eventos em Destaque</h2>
                        <p class="text-muted">Descubra os melhores eventos para a sua experiência</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    $recuperar = new RecuperarAlojamentos();
                    $eventos = $recuperar->listarEventos();
                    // Usar a data e hora atual
                    $dataHoraAtual = date("h:i A T, l, F d, Y");
                    if (empty($eventos)) {
                        echo "<div class='col text-center text-muted'>Nenhum evento registado.</div>";
                    } else {
                        foreach ($eventos as $evento) {
                            echo "
                            <div class='col'>
                                <div class='card h-100 shadow-sm border-0'>
                                    <div class='position-relative overflow-hidden'>
                                        <img src='" . htmlspecialchars($evento['imagem_path'] ?? '/uploads/eventos/placeholder.png') . "' class='card-img-top' alt='Imagem do {$evento['nome']}' style='max-height: 200px; object-fit: cover;'>
                                        <div class='position-absolute top-0 end-0 m-3'>
                                            <span class='badge bg-primary'>Eventos</span>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>" . htmlspecialchars($evento['nome']) . "</h5>
                                        <p class='card-text text-muted'>" . htmlspecialchars($evento['descricao']) . "</p>
                                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='h5 text-primary mb-0'>Data: " . htmlspecialchars($evento['data_evento']) . "</span>
                                        </div>
                                        <p class='card-text mb-2'><small class='text-muted'>Local: " . htmlspecialchars($evento['local']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Organizador: " . htmlspecialchars($evento['organizador']) . "</small></p>
                                        <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                        <div class='d-flex gap-2'>
                                            <a href='Carrinho.php?action=add&id=" . htmlspecialchars($evento['id_evento']) . "' class='btn btn-primary'>Adicionar ao Carrinho</a>
                                            <a href='reservar.php?id=" . htmlspecialchars($evento['id_evento']) . "' class='btn btn-success'>Reservar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container-footer">
            <p>Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservados</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>