<?php
session_start();
include '../../Conexao/conector.php';
// include '../../Controller/Utilizador/Home.php';
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$conexao = new Conector();
$conn = $conexao->getConexao();
$userId = $_SESSION['id_utilizador'] ?? 0; // 0 if not logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId > 0) {
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

// Fetch unread count only if logged in
$unreadCount = 0;
$notifications = [];
if ($userId > 0) {
    $sql = "SELECT COUNT(*) as count FROM notificacao WHERE id_utilizador = $userId AND lida = 0 ";
    $result = $conn->query($sql);
    $unreadCount = $result ? $result->fetch_assoc()['count'] : 0;

    // Fetch all notifications for dropdown
    $sql = "SELECT * FROM notificacao WHERE id_utilizador = $userId ORDER BY lida ASC, data DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições - MarkTour Consultoria & Serviços</title>
    <!-- BootStrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1800&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
        }
        .terms-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .terms-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .terms-content h1, .terms-content h2 {
            color: #3a4c91;
            margin-bottom: 20px;
        }
        .terms-content p, .terms-content ul {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .terms-content ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        .terms-content strong {
            color: #3a4c91;
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
                            <?php if ($userId > 0): ?>
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
                            <?php else: ?>
                                <li class="nav-item">
                                    <a href="login.php" class="btn btn-primary">Login</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Nav Secundária -->
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $_SESSION['tipo'] === 'admin'
                                                                ? '../../View/Admin/portalDoAdmin.php'
                                                                : ($_SESSION['tipo'] === 'cliente'
                                                                    ? '../../View/Utilizador/portalDoUtilizador.php'
                                                                    : ($_SESSION['tipo'] === 'empresa'
                                                                    ? '../../View/Empresa/portalDaEmpresa.php'
                                                                    : 'index.php')); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../Utilizador/MeusAlojamentos.php">Acomodações</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../Utilizador/MeusAlojamentos.php">Passeios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../Utilizador/Eventos.php">Eventos</a>
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
                <?php if ($userId > 0): ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <div id="google_translate_element"></div>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Hero Section -->
        <section class="hero-section text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">Termos e Condições</h1>
                <p class="lead">Leia atentamente os nossos termos de uso antes de utilizar o site.</p>
            </div>
        </section>

        <!-- Seção de Termos e Condições -->
        <section class="terms-section">
            <div class="container">
                <div class="terms-content">
                    <h1>Termos e Condições</h1>
                    <p>Bem-vindo à MarkTour Consultoria & Serviços!</p>
                    <p>Estes termos e condições descrevem as regras e regulamentos para o uso do site da MarkTour Consultoria & Serviços, localizado em <a href="https://marktour.co.mz/">https://marktour.co.mz/</a>.</p>
                    <p>Ao aceder a este site, presumimos que aceita estes termos e condições. Não continue a usar MarkTour Consultoria & Serviços se não concordar com todos os termos e condições declarados nesta página.</p>

                    <h2>Cookies</h2>
                    <p>O site usa cookies para ajudar a personalizar a sua experiência online. Ao aceder MarkTour Consultoria & Serviços, concordou em usar os cookies necessários.</p>
                    <p>Um cookie é um arquivo de texto colocado no seu disco rígido por um servidor de páginas web. Os cookies não podem ser usados para executar programas ou enviar vírus para o seu computador. Os cookies são atribuídos exclusivamente a si e só podem ser lidos por um servidor web no domínio que emitiu o cookie.</p>
                    <p>Podemos usar cookies para recolher, armazenar e rastrear informações para fins estatísticos ou de marketing para operar o nosso site. Tem a opção de aceitar ou recusar Cookies opcionais. Existem alguns Cookies necessários para o funcionamento do nosso site. Esses cookies não exigem o seu consentimento. Lembre-se de que, ao aceitar os cookies necessários, também aceita cookies de terceiros, que podem ser usados por meio de serviços fornecidos por terceiros se usar esses serviços no nosso site, por exemplo, uma janela de exibição de vídeo fornecida por terceiros e integrada no nosso site.</p>

                    <h2>Licença</h2>
                    <p>Salvo indicação em contrário, MarkTour Consultoria & Serviços e/ou os seus licenciados possuem os direitos de propriedade intelectual de todo o material em MarkTour Consultoria & Serviços. Todos os direitos de propriedade intelectual são reservados. Pode aceder de MarkTour Consultoria & Serviços para o seu uso pessoal sujeito às restrições definidas nestes termos e condições.</p>
                    <p><strong>Não deve:</strong></p>
                    <ul>
                        <li>Copiar ou republicar o material de MarkTour Consultoria & Serviços</li>
                        <li>Vender, alugar ou sublicenciar o material de MarkTour Consultoria & Serviços</li>
                        <li>Reproduzir, duplicar ou copiar o material de MarkTour Consultoria & Serviços</li>
                        <li>Redistribuir o conteúdo de MarkTour Consultoria & Serviços</li>
                    </ul>
                    <p>Este acordo deve iniciar na data deste documento.</p>
                    <p>Partes deste site oferecem aos utilizadores a oportunidade de postar e trocar opiniões e informações em certas áreas do site. MarkTour Consultoria & Serviços não filtra, edita, publica ou analisa os comentários no site. Os comentários não refletem as visões e opiniões de MarkTour Consultoria & Serviços, seus agentes e/ou afiliados. Os comentários refletem os pontos de vista e as opiniões da pessoa que posta os seus pontos de vista e opiniões. Na medida do permitido pelas leis aplicáveis, MarkTour Consultoria & Serviços não será responsável pelos comentários ou qualquer responsabilidade, danos ou despesas causados e/ou sofridos como resultado de qualquer uso e/ou publicação e/ou aparência de comentários neste site.</p>
                    <p>MarkTour Consultoria & Serviços reserva-se o direito de monitorizar todos os comentários e remover quaisquer comentários que possam ser considerados inadequados, ofensivos ou que causem violação destes Termos e Condições.</p>
                    <p><strong>Garante e declara que:</strong></p>
                    <ul>
                        <li>Tem o direito de postar os comentários no nosso site e tem todas as licenças e consentimentos necessários para fazê-lo;</li>
                        <li>Os comentários não invadem nenhum direito de propriedade intelectual, incluindo, sem limitação, direitos de autor, patentes ou marcas comerciais de terceiros;</li>
                        <li>Os comentários não contêm nenhum material difamatório, calunioso, ofensivo, indecente ou de outra forma ilegal, o que constitui uma invasão de privacidade.</li>
                        <li>Os comentários não serão usados para solicitar ou promover negócios ou customizar ou apresentar atividades comerciais ou atividades ilegais.</li>
                    </ul>
                    <p>Concede a MarkTour Consultoria & Serviços uma licença não exclusiva para usar, reproduzir, editar e autorizar outros a usar, reproduzir e editar qualquer um de seus comentários em todas e quaisquer formas, formatos, ou mídia.</p>

                    <h2>Hiperlinks para o nosso conteúdo</h2>
                    <p>As seguintes organizações podem vincular-se ao nosso site sem aprovação prévia por escrito:</p>
                    <ul>
                        <li>Agências governamentais;</li>
                        <li>Mecanismos de pesquisa;</li>
                        <li>Organizações de notícias;</li>
                        <li>Os distribuidores de diretórios online podem ter links para o nosso site da mesma maneira que fazem um hiperlink para sites de outras empresas listadas; e</li>
                        <li>Negócios credenciados em todo o sistema, exceto a solicitação de organizações sem fins lucrativos, centros de caridade e grupos de angariação de fundos de caridade que não podem ter um link para o nosso site.</li>
                    </ul>
                    <p>Essas organizações podem incluir links para a nossa página inicial, publicações ou outras informações do site, desde que o link: (a) não seja de forma alguma enganoso; (b) não implique falsamente um patrocínio, endosso ou aprovação da parte vinculante e de seus produtos e/ou serviços; e (c) se enquadra no contexto do site da parte vinculante.</p>
                    <p>Podemos considerar e aprovar outras solicitações de links dos seguintes tipos de organizações:</p>
                    <ul>
                        <li>Fontes de informações comerciais e / ou de consumidores comumente conhecidas;</li>
                        <li>Sites da comunidade dot.com;</li>
                        <li>Associações ou outros grupos que representam instituições de caridade;</li>
                        <li>Distribuidores de diretórios online;</li>
                        <li>Portais de internet;</li>
                        <li>Firmas de contabilidade, advocacia e consultoria; e</li>
                        <li>Instituições educacionais e associações comerciais.</li>
                    </ul>
                    <p>Aprovaremos as solicitações de link dessas organizações se decidirmos que: (a) o link não nos faria parecer desfavoravelmente para nós mesmos ou para nossos negócios credenciados; (b) a organização não possui registros negativos conosco; (c) o benefício para nós da visibilidade do hiperlink compensa a ausência de MarkTour Consultoria & Serviços; e (d) o link está no contexto de informações gerais de recursos.</p>
                    <p>Essas organizações podem ter um link para nossa página inicial, desde que o link: (a) não seja de forma alguma enganoso; (b) não implique falsamente em patrocínio, endosso ou aprovação da parte vinculante e de seus produtos ou serviços; e (c) se enquadra no contexto do site da parte vinculante.</p>
                    <p>Se é uma das organizações listadas no parágrafo 2 acima e está interessado em criar um link para o nosso site, deve-nos informar enviando um email para MarkTour Consultoria & Serviços. Inclua o seu nome, o nome da sua organização, informações de contato, bem como o URL do seu site, uma lista de todos os URLs dos quais pretende criar um link para o nosso site e uma lista dos URLs do nosso site aos quais gostaria de linkar. Espere 2 a 3 semanas por uma resposta.</p>
                    <p>Organizações aprovadas podem ter um hiperlink para nosso site da seguinte forma:</p>
                    <ul>
                        <li>Pelo uso do nosso nome corporativo; ou</li>
                        <li>Pelo uso do localizador uniforme de recursos ao qual está vinculado; ou</li>
                        <li>Usando qualquer outra descrição do nosso site vinculado que faça sentido dentro do contexto e formato do conteúdo do site da parte vinculante.</li>
                    </ul>
                    <p>Não será permitido o uso do logotipo da MarkTour Consultoria & Serviços ou outra arte para criar links na ausência de um contrato de licença de marca registada.</p>

                    <h2>Responsabilidade pelo conteúdo</h2>
                    <p>Não seremos responsabilizados por qualquer conteúdo que apareça no seu site. Concorda em proteger-nos e defender contra todas as reclamações levantadas no seu site. Nenhum link deve aparecer em qualquer site que possa ser interpretado como calunioso, obsceno ou criminoso, ou que infrinja, de outra forma viole ou defenda a violação ou outra violação de quaisquer direitos de terceiros.</p>

                    <h2>Reserva de direitos</h2>
                    <p>Reservamos o direito de solicitar que remova todos os links ou qualquer link específico para o nosso site. Aprova a remoção imediata de todos os links para nosso site, mediante solicitação. Também nos reservamos o direito de alterar estes termos e condições e a sua política de vinculação a qualquer momento. Ao conectar-se continuamente ao nosso site, concorda em cumprir e seguir estes termos e condições de vinculação.</p>

                    <h2>Remoção de links do nosso site</h2>
                    <p>Se encontrar algum link no nosso site que seja ofensivo por qualquer motivo, pode contactar-nos e informar-nos a qualquer momento. Consideraremos as solicitações de remoção de links, mas não somos obrigados a fazê-lo ou a responder diretamente.</p>
                    <p>Não garantimos que as informações neste site sejam corretas. Não garantimos sua integridade ou precisão, nem prometemos garantir que o site permaneça disponível ou que o material nele contido seja mantido atualizado.</p>

                    <h2>Isenção de responsabilidade</h2>
                    <p>Na extensão máxima permitida pela lei aplicável, excluímos todas as representações, garantias e condições relacionadas ao nosso site e ao uso deste site. Nada nesta isenção de responsabilidade:</p>
                    <ul>
                        <li>Limitará ou excluirá a nossa responsabilidade por morte ou danos pessoais;</li>
                        <li>Limitar ou excluir a nossa responsabilidade ou a sua responsabilidade por fraude ou deturpação fraudulenta;</li>
                        <li>Limitar qualquer uma das nossas responsabilidades de qualquer forma que não seja permitida pela lei aplicável; ou</li>
                        <li>Excluir qualquer uma das nossas ou suas responsabilidades que não possam ser excluídas sob a lei aplicável.</li>
                    </ul>
                    <p>As limitações e proibições de responsabilidade definidas nesta seção e em outras partes desta isenção de responsabilidade: (a) estão sujeitas ao parágrafo anterior; e (b) regem todas as responsabilidades decorrentes da isenção de responsabilidade, incluindo responsabilidades decorrentes de contratos, atos ilícitos e por violação de dever estatutário.</p>
                    <p>Desde que o site e as informações e serviços nele fornecidos sejam gratuitos, não seremos responsáveis por perdas ou danos de qualquer natureza.</p>
                </div>
            </div>
        </section>
    </main>
    <!-- Rodapé -->
    <footer>
        <div class="container-footer">
            <p>
                Copyright 2023 © <span>Marktour</span> | Todos Direitos Reservados
            </p>
        </div>
    </footer>
    <!-- Scripts do BootStrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'pt',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>