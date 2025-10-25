<?php
session_start();
require_once __DIR__ . '/../../Conexao/conector.php';
// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_review') {
    $conexao = new Conector();
    $conn = $conexao->getConexao();
    $id_utilizador = $_SESSION['id_utilizador'] ?? 0;
    if ($id_utilizador > 0) {
        $comentario = htmlspecialchars($_POST['comentario']);
        $classificacao = intval($_POST['classificacao']);
        
        $sql = "INSERT INTO review_site (comentario, classificacao, id_utilizador) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $comentario, $classificacao, $id_utilizador);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    }
    exit;
}

class RecuperarReviews
{
    public function listar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        $sql = "SELECT r.*, u.nome FROM review_site r JOIN utilizador u ON r.id_utilizador = u.id_utilizador ORDER BY r.data DESC";
        $result = $conn->query($sql);
        $reviews = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        return $reviews;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Gerais - MarkTour</title>
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
        /* Estilos inovadores para reviews */
        .review-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .stars {
            color: gold;
            font-size: 1.2rem;
        }
        /* Rating inovador com hover glow */
        .rating {
            display: inline-flex;
            flex-direction: row-reverse;
            justify-content: center;
        }
        .rating input {
            display: none;
        }
        .rating label {
            cursor: pointer;
            width: 30px;
            height: 30px;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray"%3E%3Cpath d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/%3E%3C/svg%3E');
            background-size: contain;
            transition: transform 0.2s ease, filter 0.2s ease;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gold"%3E%3Cpath d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/%3E%3C/svg%3E');
            transform: scale(1.1);
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
        }
        @keyframes star-blink {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .rating input:checked ~ label {
            animation: star-blink 0.5s ease;
        }
        /* Contador de caracteres com progress bar */
        #charCount {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .progress {
            height: 5px;
            margin-top: 5px;
        }
        .progress-bar {
            transition: width 0.3s ease;
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
                    <a class="nav-link active" aria-current="page" href="Passeios.php">Passeios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="Eventos.php">Eventos</a>
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
    <main class="destaques py-5 bg-light" >
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center">
                    <h2 class="fw-bold">Reviews Gerais do Site</h2>
                    <p class="text-muted">Compartilhe sua opinião sobre o MarkTour e veja o que outros usuários pensam</p>
                </div>
            </div>
            <!-- Form para novo review (inovador, com AJAX) -->
            <?php if (isset($_SESSION['id_utilizador'])): ?>
            <div class="card mb-5 shadow">
                <div class="card-body">
                    <h5 class="card-title">Adicione Sua Review</h5>
                    <form id="reviewForm">
                        <input type="hidden" name="action" value="submit_review">
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold">Classificação</label>
                            <div class="rating">
                                <input type="radio" id="star5" name="classificacao" value="5"><label for="star5"></label>
                                <input type="radio" id="star4" name="classificacao" value="4"><label for="star4"></label>
                                <input type="radio" id="star3" name="classificacao" value="3"><label for="star3"></label>
                                <input type="radio" id="star2" name="classificacao" value="2"><label for="star2"></label>
                                <input type="radio" id="star1" name="classificacao" value="1"><label for="star1"></label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-bold">Comentário</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="4" maxlength="500" placeholder="Compartilhe sua experiência com o site..."></textarea>
                            <div class="d-flex justify-content-between">
                                <small id="charCount">0/500 caracteres</small>
                                <div class="progress w-50">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Enviar Review</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info mb-5">
                Faça login para adicionar uma review.
            </div>
            <?php endif; ?>
            <!-- Lista de reviews -->
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                $recuperar = new RecuperarReviews();
                $reviews = $recuperar->listar();
                if (empty($reviews)) {
                    echo "<div class='col text-center text-muted'>Nenhuma review registrada ainda.</div>";
                } else {
                    foreach ($reviews as $review) {
                        $stars = str_repeat('<i class="fas fa-star"></i>', $review['classificacao']) . str_repeat('<i class="far fa-star"></i>', 5 - $review['classificacao']);
                        echo "
                        <div class='col'>
                            <div class='card review-card h-100'>
                                <div class='card-body'>
                                    <div class='d-flex justify-content-between align-items-center mb-2'>
                                        <h5 class='card-title mb-0'>" . htmlspecialchars($review['nome']) . "</h5>
                                        <span class='stars'>$stars</span>
                                    </div>
                                    <p class='card-text text-muted'>" . htmlspecialchars($review['comentario']) . "</p>
                                    <small class='text-muted'>Postado em: " . date('d/m/Y H:i', strtotime($review['data'])) . "</small>
                                </div>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>
        </div>
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
    <script>
        // Contador de caracteres inovador
        $('#comentario').on('input', function() {
            var length = $(this).val().length;
            $('#charCount').text(length + '/500 caracteres');
            var percent = (length / 500) * 100;
            $('.progress-bar').css('width', percent + '%').removeClass('bg-info bg-warning bg-danger');
            if (percent < 50) {
                $('.progress-bar').addClass('bg-info');
            } else if (percent < 80) {
                $('.progress-bar').addClass('bg-warning');
            } else {
                $('.progress-bar').addClass('bg-danger');
            }
        });

        // Submit via AJAX
        $('#reviewForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Review enviada com sucesso!');
                        location.reload(); // Recarrega para mostrar nova review
                    } else {
                        alert(response.message || 'Erro ao enviar review.');
                    }
                },
                error: function() {
                    alert('Erro na requisição.');
                }
            });
        });
    </script>
</body>
</html>