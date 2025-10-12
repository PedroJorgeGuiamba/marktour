<?php
// session_start();
require_once __DIR__ . '/../../Conexao/conector.php';

$conexao = new Conector();
$conn = $conexao->getConexao();

// Submissão de pergunta pelo usuário
if (isset($_POST['submit_pergunta'])) {
    $pergunta = trim($_POST['pergunta']);
    $id_utilizador = $_SESSION['user_id'] ?? 0; // Assumindo que o ID do usuário está na sessão

    $stmt = $conn->prepare("INSERT INTO faq (pergunta, id_utilizador) VALUES (?, ?)");
    $stmt->bind_param("si", $pergunta, $id_utilizador);
    $stmt->execute();
}

// Listar FAQs
$sql = "SELECT pergunta, resposta FROM faq WHERE visivel = TRUE ORDER BY data_criacao DESC";
$result = $conn->query($sql);

$faqs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MarkTour</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <style>
        .accordion-button {
            background-color: #f8f9fa;
            color: #333;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e9ecef;
        }
        .accordion-item {
            margin-bottom: 10px;
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

    <main class="container mt-5">
        <h2 class="text-center">FAQ</h2>

        <div class="accordion" id="faqAccordion">
            <?php if (empty($faqs)): ?>
                <p class="text-center">Nenhuma pergunta frequente disponível no momento.</p>
            <?php else: ?>
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                                <?php echo htmlspecialchars($faq['pergunta']); ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($faq['resposta'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulário para submeter pergunta -->
        <div class="mt-5">
            <h3>Faça uma Pergunta</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="pergunta" class="form-label">Sua Pergunta</label>
                    <textarea class="form-control" id="pergunta" name="pergunta" rows="3" required></textarea>
                </div>
                <button type="submit" name="submit_pergunta" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </main>

    <footer class="text-center mt-5">
        <p>Copyright 2023 © MarkTour | Todos Direitos Reservados</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>