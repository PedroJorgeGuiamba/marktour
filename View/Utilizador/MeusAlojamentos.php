<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';
require_once __DIR__ . '/../../Model/Alojamento.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

class RecuperarAlojamentos
{
    public function listar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();

        $sql = "SELECT * FROM alojamento";
        $result = $conn->query($sql);

        $alojamentos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alojamentos[] = $row;
            }
        }

        return $alojamentos;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Alojamentos - MarkTour</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
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
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
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
                    <a class="nav-link active" href="../Utilizador/portalDoUtilizador.php">Home</a>
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
                    <a class="nav-link" href="#">Eventos</a>
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
            </ul>
        </nav>
    </header>

    <main class="container mt-5" style="padding-top: 100px;">
        <h2>Alojamentos Registrados</h2>

        <?php
            $recuperar = new RecuperarAlojamentos();
            $alojamentos = $recuperar->listar();

            // Usar a data e hora atual
            $dataHoraAtual = date("h:i A T, l, F d, Y");

            if (empty($alojamentos)) {
                echo "<div class='alert alert-warning' role='alert'>Nenhum alojamento registrado.</div>";
            } else {
                foreach ($alojamentos as $alojamento) {
                    echo "
                    <div class='col'>
                        <div class='card mb-3' style='max-width: 540px;'>
                            <img src='" . htmlspecialchars($alojamento['imagem_path'] ?? '/uploads/alojamentos/placeholder.png') . "' class='card-img-top' alt='Imagem do {$alojamento['nome']}' style='max-height: 200px; object-fit: cover;'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . htmlspecialchars($alojamento['nome']) . "</h5>
                                <p class='card-text'><strong>Tipo:</strong> " . htmlspecialchars($alojamento['tipo']) . "</p>
                                <p class='card-text'><strong>Descrição:</strong> " . htmlspecialchars($alojamento['descricao']) . "</p>
                                <p class='card-text'><strong>Preço por Noite:</strong> " . htmlspecialchars($alojamento['preco_noite']) . " MZN</p>
                                <p class='card-text'><strong>Número de Quartos:</strong> " . htmlspecialchars($alojamento['num_quartos']) . "</p>
                                <p class='card-text'><strong>Empresa ID:</strong> " . htmlspecialchars($alojamento['id_empresa']) . "</p>
                                <p class='card-text'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                <div class='d-flex gap-2'>
                                    <a href='Carrinho.php?action=add&id=" . htmlspecialchars($alojamento['id_alojamento']) . "' class='btn btn-primary'>Adicionar ao Carrinho</a>
                                    <a href='reservar.php?id=" . htmlspecialchars($alojamento['id_alojamento']) . "' class='btn btn-success'>Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
            }
        ?>

    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>