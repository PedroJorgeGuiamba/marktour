<?php
session_start();
include '../../Conexao/conector.php';
include '../../Controller/Utilizador/Home.php';
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$conexao = new Conector();
$conn = $conexao->getConexao();
$userId = $_SESSION['id_utilizador'] ?? 0; // Assume logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'mark_read' && isset($_POST['id_notificacao'])) {
            $notifId = intval($_POST['id_notificacao']);
            $sql = "UPDATE notificacao SET lida = 1 WHERE id_notificacao = $notifId AND id_utilizador = $userId";
            $conn->query($sql);
        } elseif ($_POST['action'] === 'mark_all_read') {
            $sql = "UPDATE notificacao SET lida = 1 WHERE id_utilizador = $userId AND deleted = 0";
            $conn->query($sql);
        } elseif ($_POST['action'] === 'clear_all') {
            $sql = "UPDATE notificacao SET deleted = 1 WHERE id_utilizador = $userId";
            $conn->query($sql);
        }
    }
    // Reload the page after action
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch unread count
$sql = "SELECT COUNT(*) as count FROM notificacao WHERE id_utilizador = $userId AND lida = 0 ";
$result = $conn->query($sql);
$unreadCount = $result ? $result->fetch_assoc()['count'] : 0;

// Fetch all notifications for dropdown
$sql = "SELECT * FROM notificacao WHERE id_utilizador = $userId ORDER BY lida ASC, data DESC";
$result = $conn->query($sql);
$notifications = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
class RecuperarAlojamentos
{
    public function listarAlojamentos()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();

        // Consulta para recuperar todos os alojamentos
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
    public function listarActividades()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        // Consulta para recuperar todas as actividades (passeios)
        $sql = "SELECT * FROM actividade";
        $result = $conn->query($sql);
        $actividades = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $actividades[] = $row;
            }
        }
        return $actividades;
    }
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="../../Style/index.css">
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

        .notification-icon {
            position: relative;
            margin-left: 15px;
        }
        .notification-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .dropdown-menu.notifications {
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }
        .notification-item.unread {
            background-color: #f8f9fa;
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
                                <a href="carrinho.php" class="cart-icon me-3">
                                    <i class="fas fa-shopping-cart fs-4" style="color: #3a4c91;"></i>
                                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                                </a>
                            </li>
                            <!-- Notification Bell -->
                            <li class="nav-item dropdown">
                                <a href="#" class="notification-icon me-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell fs-4" style="color: #3a4c91;"></i>
                                    <?php if ($unreadCount > 0): ?>
                                        <span class="notification-count"><?php echo $unreadCount; ?></span>
                                    <?php endif; ?>
                                </a>
                                <ul class="dropdown-menu notifications dropdown-menu-end">
                                    <?php if (empty($notifications)): ?>
                                        <li class="notification-item text-center">Nenhuma notificação.</li>
                                    <?php else: ?>
                                        <?php foreach ($notifications as $notif): ?>
                                            <li class="notification-item <?php echo $notif['lida'] == 0 ? 'unread' : ''; ?>">
                                                <p><?php echo htmlspecialchars($notif['mensagem']); ?></p>
                                                <small class="text-muted"><?php echo htmlspecialchars($notif['data']); ?></small>
                                                <?php if ($notif['lida'] == 0): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="action" value="mark_read">
                                                        <input type="hidden" name="id_notificacao" value="<?php echo $notif['id_notificacao']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-link">Marcar como lida</button>
                                                    </form>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <li class="dropdown-divider"></li>
                                    <li class="text-center">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="mark_all_read">
                                            <button type="submit" class="btn btn-sm btn-primary">Marcar todas como lidas</button>
                                        </form>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="clear_all">
                                            <button type="submit" class="btn btn-sm btn-danger">Limpar todas</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
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
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="MeusAlojamentos.php">Acomodações</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="MeusAlojamentos.php">Passeios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="Eventos.php">Eventos</a>
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
                        <li><a class="dropdown-item" href="../MarkTour/TermosECondicoes.php">Termos E Condições</a></li>
                        <li><a class="dropdown-item" href="../MarkTour/PoliticasDePricacidade.php">Política de Privacidade</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                </li>
            </ul>
        </nav>
    </header>

<main>
        <!-- Hero Section com imagem de fundo e busca -->
        <section class="hero-section position-relative" style="background: url('https://www.visitarafrica.com/media/k2/items/cache/5e58b7b25b6a8f9a7ad4b2e96a6e51bc_XL.jpg') center/cover no-repeat; height: 80vh;">
            <div class="hero-bg"></div>
            <div class="container position-relative">
                <div class="row justify-content-center text-center text-white py-5">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold mb-4">Descubra Lugares Incríveis</h1>
                        <p class="lead mb-5">Explore destinos únicos e viva experiências inesquecíveis</p>
                    </div>
                </div>

                <!-- Formulário de busca destacado -->
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="search-box bg-white rounded shadow p-4">
                            <h3 class="text-center mb-4">Encontre sua Acomodação Ideal</h3>
                            <form id="formBusca" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Destino</label>
                                    <input type="text" name="localizacao" class="form-control" placeholder="Para onde você vai?" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Check-in</label>
                                    <input type="date" name="checkin" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Check-out</label>
                                    <input type="date" name="checkout" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Hóspedes</label>
                                    <select name="hospedes" class="form-select" required>
                                        <option value="1">1 Hóspede</option>
                                        <option value="2" selected>2 Hóspedes</option>
                                        <option value="3">3 Hóspedes</option>
                                        <option value="4">4 Hóspedes</option>
                                        <option value="5">5+ Hóspedes</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid align-self-end">
                                    <button type="submit" class="btn btn-primary btn-lg">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- Alojamentos em Destaque -->
        <section class="alojamentos py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Alojamentos em Destaque</h2>
                            <p class="text-muted">Descubra as melhores opções de alojamento para a sua viagem</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    $recuperar = new RecuperarAlojamentos();
                    $alojamentos = $recuperar->listarAlojamentos();

                    // Usar a data e hora atual
                    $dataHoraAtual = date("h:i A T, l, F d, Y");

                    if (empty($alojamentos)) {
                        echo "<div class='col text-center text-muted'>Nenhum alojamento registado.</div>";
                    } else {
                        foreach ($alojamentos as $alojamento) {
                            echo "
                            <div class='col'>
                                <div class='card h-100 shadow-sm border-0'>
                                    <div class='position-relative overflow-hidden'>
                                        <img src='" . htmlspecialchars($alojamento['imagem_path'] ?? '/uploads/alojamentos/placeholder.png') . "' class='card-img-top' alt='Imagem do {$alojamento['nome']}' style='max-height: 200px; object-fit: cover;'>
                                        <div class='position-absolute top-0 end-0 m-3'>
                                            <span class='badge bg-primary'>Alojamentos</span>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>" . htmlspecialchars($alojamento['nome']) . "</h5>
                                        <p class='card-text text-muted'>" . htmlspecialchars($alojamento['descricao']) . "</p>
                                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($alojamento['preco_noite']) . " MZN</span>
                                        </div>
                                        <p class='card-text mb-2'><small class='text-muted'>Tipo: " . htmlspecialchars($alojamento['tipo']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Número de Quartos: " . htmlspecialchars($alojamento['num_quartos']) . "</small></p>
                                        <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
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
                </div>
            </div>

        </section>
        
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
        <!-- Passeios em Destaque -->
        <section class="passeios py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Passeios em Destaque</h2>
                        <p class="text-muted">Descubra os melhores passeios e atividades para a sua aventura</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    $recuperar = new RecuperarAlojamentos();
                    $actividades = $recuperar->listarActividades();
                    // Usar a data e hora atual
                    $dataHoraAtual = date("h:i A T, l, F d, Y");
                    if (empty($actividades)) {
                        echo "<div class='col text-center text-muted'>Nenhuma actividade registada.</div>";
                    } else {
                        foreach ($actividades as $actividade) {
                            echo "
                            <div class='col'>
                                <div class='card h-100 shadow-sm border-0'>
                                    <div class='position-relative overflow-hidden'>
                                        <img src='" . htmlspecialchars($actividade['imagem_path'] ?? '/uploads/actividades/placeholder.png') . "' class='card-img-top' alt='Imagem da {$actividade['nome']}' style='max-height: 200px; object-fit: cover;'>
                                        <div class='position-absolute top-0 end-0 m-3'>
                                            <span class='badge bg-primary'>Passeios</span>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>" . htmlspecialchars($actividade['nome']) . "</h5>
                                        <p class='card-text text-muted'>" . htmlspecialchars($actividade['descricao']) . "</p>
                                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='h5 text-primary mb-0'>A partir de " . htmlspecialchars($actividade['preco']) . " MZN</span>
                                        </div>
                                        <p class='card-text mb-2'><small class='text-muted'>Local: " . htmlspecialchars($actividade['local']) . "</small></p>
                                        <p class='card-text mb-2'><small class='text-muted'>Duração: " . htmlspecialchars($actividade['duracao']) . "</small></p>
                                        <p class='card-text mb-3'><small class='text-muted'>Última atualização: " . htmlspecialchars($dataHoraAtual) . "</small></p>
                                        <div class='d-flex gap-2'>
                                            <a href='Carrinho.php?action=add&id=" . htmlspecialchars($actividade['id_actividade']) . "' class='btn btn-primary'>Adicionar ao Carrinho</a>
                                            <a href='reservar.php?id=" . htmlspecialchars($actividade['id_actividade']) . "' class='btn btn-success'>Reservar</a>
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

        <!-- Resultados da busca -->
        <section class="resultados-busca py-5">
            <div class="container">
                <div id="resultadosBusca" class="row row-cols-1 row-cols-md-3 g-4"></div>
            </div>
        </section>

        <!-- Dicas e notícias de viagem -->
        <section class="dicas-noticias py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="fw-bold">Dicas e Notícias de Viagem</h2>
                        <p class="text-muted">Fique por dentro das últimas tendências e novidades do mundo do turismo</p>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <!-- Notícia 1 -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Viagem sustentável">
                            <div class="card-body">
                                <span class="badge bg-success mb-2">Sustentabilidade</span>
                                <h5 class="card-title">Como viajar de forma mais sustentável</h5>
                                <p class="card-text text-muted">Descubra dicas práticas para reduzir seu impacto ambiental durante suas viagens e contribuir para um turismo mais responsável.</p>
                                <a href="#" class="btn btn-link text-primary p-0">Ler mais →</a>
                            </div>
                        </div>
                    </div>
                    <!-- Notícia 2 -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Destinos em alta">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">Tendências</span>
                                <h5 class="card-title">Os destinos mais procurados para 2023</h5>
                                <p class="card-text text-muted">Confira a lista dos lugares que estão ganhando popularidade entre os viajantes e prepare sua próxima aventura.</p>
                                <a href="#" class="btn btn-link text-primary p-0">Ler mais →</a>
                            </div>
                        </div>
                    </div>
                    <!-- Notícia 3 -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Gastronomia">
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2">Gastronomia</span>
                                <h5 class="card-title">Sabores regionais: uma viagem pelo paladar</h5>
                                <p class="card-text text-muted">Explore a riqueza da culinária local em cada destino e descubra pratos típicos que vão surpreender seu paladar.</p>
                                <a href="#" class="btn btn-link text-primary p-0">Ler mais →</a>
                            </div>
                        </div>
                    </div>
                    <!-- Notícia 4 -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1539635278303-d4002c07eae3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Fotografia">
                            <div class="card-body">
                                <span class="badge bg-info mb-2">Fotografia</span>
                                <h5 class="card-title">Dicas para fotos incríveis em suas viagens</h5>
                                <p class="card-text text-muted">Aprenda técnicas simples para registrar momentos especiais e criar memórias visuais que vão durar para sempre.</p>
                                <a href="#" class="btn btn-link text-primary p-0">Ler mais →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1800&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
        }

        .search-box {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
    <!-- Rodapé -->
    <footer>
        <div class="container-footer">
            <p>
                Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservadors
            </p>
        </div>
    </footer>

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