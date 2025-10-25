<?php
session_start();
include '../../Conexao/conector.php';
include '../../Controller/Utilizador/Home.php';
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
    <title>Política de Privacidade - MarkTour Consultoria & Serviços</title>
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
        .privacy-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .privacy-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .privacy-content h1, .privacy-content h2 {
            color: #3a4c91;
            margin-bottom: 20px;
        }
        .privacy-content p, .privacy-content ul {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .privacy-content ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        .privacy-content ol {
            list-style-type: decimal;
            padding-left: 20px;
        }
        .privacy-content strong {
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
                    <a class="nav-link active" aria-current="page" href="Home.php">Home</a>
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
                    </ul>
                </li>
                <?php if ($userId > 0): ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="perfil.php">Perfil</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Hero Section -->
        <section class="hero-section text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">Política de Privacidade</h1>
                <p class="lead">Sua privacidade é importante para nós. Leia nossa política para entender como protegemos seus dados.</p>
            </div>
        </section>

        <!-- Seção de Política de Privacidade -->
        <section class="privacy-section">
            <div class="container">
                <div class="privacy-content">
                    <h1>Política de Privacidade – Marktour</h1>
                    <p>Marktour, pessoa jurídica de direito privado leva a sua privacidade a sério e zela pela segurança e proteção de dados de todos os seus clientes, parceiros, fornecedores e usuários do site domínio https://marktour.co.mz/ e qualquer outro site, loja ou aplicativo operado pelo lojista.</p>
                    <p>Esta Política de Privacidade destina-se a informá-lo sobre o modo como nós utilizamos e divulgamos informações coletadas em suas visitas à nossa loja e em mensagens que trocamos com você.</p>
                    <p>Esta Política de Privacidade aplica-se somente a informações coletadas por meio da loja.</p>
                    <p>AO ACESSAR A PLATAFORMA, ENVIAR COMUNICAÇÕES OU FORNECER QUALQUER TIPO DE DADO PESSOAL, VOCÊ DECLARA ESTAR CIENTE COM RELAÇÃO AOS TERMOS AQUI PREVISTOS E DE ACORDO COM A POLÍTICA DE PRIVACIDADE, A QUAL DESCREVE AS FINALIDADES E FORMAS DE TRATAMENTO DE SEUS DADOS PESSOAIS QUE VOCÊ DISPONIBILIZAR NA LOJA.</p>
                    <p>Esta Política de Privacidade fornece uma visão geral de nossas práticas de privacidade e das escolhas que você pode fazer, bem como direitos que você pode exercer em relação aos Dados Pessoais tratados por nós. Se você tiver alguma dúvida sobre o uso de Dados Pessoais, entre em contato com manueldinisjunior@yahoo.com.</p>
                    <p>Além disso, a Política de Privacidade não se aplica a quaisquer aplicativos, produtos, serviços, site ou recursos de mídia social de terceiros que possam ser oferecidos ou acessados por meio da loja. O acesso a esses links fará com que você deixe o nosso site e poderá resultar na coleta ou compartilhamento de informações sobre você por terceiros. Nós não controlamos, endossamos ou fazemos quaisquer representações sobre sites de terceiros ou suas práticas de privacidade, que podem ser diferentes das nossas. Recomendamos que você revise a política de privacidade de qualquer site com o qual você interaja antes de permitir a coleta e o uso de seus Dados Pessoais.</p>
                    <p>Caso você nos envie Dados Pessoais referentes a outras pessoas físicas, você declara ter a competência para fazê-lo e declara ter obtido o consentimento necessário para autorizar o uso de tais informações nos termos desta Política de Privacidade.</p>

                    <h2>Seção 1 – Definições</h2>
                    <p>Para os fins desta Política de Privacidade:</p>
                    <ol>
                        <li>“Dados Pessoais“: significa qualquer informação que, direta ou indiretamente, identifique ou possa identificar uma pessoa natural, como por exemplo, nome, CPF, data de nascimento, endereço IP, dentre outros;</li>
                        <li>“Dados Pessoais Sensíveis“: significa qualquer informação que revele, em relação a uma pessoa natural, origem racial ou étnica, convicção religiosa, opinião política, filiação a sindicato ou a organização de caráter religioso, filosófico ou político, dado referente à saúde ou à vida sexual, dado genético ou biométrico;</li>
                        <li>“Tratamento de Dados Pessoais“: significa qualquer operação efetuada no âmbito dos Dados Pessoais, por meio de meios automáticos ou não, tal como a recolha, gravação, organização, estruturação, armazenamento, adaptação ou alteração, recuperação, consulta, utilização, divulgação por transmissão, disseminação ou, alternativamente, disponibilização, harmonização ou associação, restrição, eliminação ou destruição. Também é considerado Tratamento de Dados Pessoais qualquer outra operação prevista nos termos da legislação aplicável;</li>
                        <li>“Leis de Proteção de Dados“: significa todas as disposições legais que regulam o Tratamento de Dados Pessoais, incluindo, porém sem se limitar, a Lei nº 13.709/18, Lei Geral de Proteção de Dados Pessoais (“LGPD“).</li>
                    </ol>

                    <h2>Seção 2 – Uso de Dados Pessoais</h2>
                    <p>Coletamos e usamos Dados Pessoais para gerenciar seu relacionamento conosco e melhor atendê-lo quando você estiver adquirindo produtos e/ou serviços na loja, personalizando e melhorando sua experiência. Exemplos de como usamos os dados incluem:</p>
                    <ol>
                        <li>Viabilizar que você adquira produtos e/ou serviços na loja;</li>
                        <li>Para confirmar ou corrigir as informações que temos sobre você;</li>
                        <li>Para enviar informações que acreditamos ser do seu interesse;</li>
                        <li>Para personalizar sua experiência de uso da loja;</li>
                        <li>Para entrarmos em contato por um número de telefone e/ou endereço de e-mail fornecido. Podemos entrar em contato com você pessoalmente, por mensagem de voz, através de equipamentos de discagem automática, por mensagens de texto (SMS), por e-mail, ou por qualquer outro meio de comunicação que seu dispositivo seja capaz de receber, nos termos da lei e para fins comerciais razoáveis.</li>
                    </ol>
                    <p>Além disso, os Dados Pessoais fornecidos também podem ser utilizados na forma que julgarmos necessária ou adequada: (a) nos termos das Leis de Proteção de Dados; (b) para atender exigências de processo judicial; (c) para cumprir decisão judicial, decisão regulatória ou decisão de autoridades competentes, incluindo autoridades fora do país de residência; (d) para aplicar nossos Termos e Condições de Uso; (e) para proteger nossas operações; (f) para proteger direitos, privacidade, segurança nossos, seus ou de terceiros; (g) para detectar e prevenir fraude; (h) permitir-nos usar as ações disponíveis ou limitar danos que venhamos a sofrer; e (i) de outros modos permitidos por lei.</p>

                    <h2>Seção 3 – Não fornecimento de Dados Pessoais</h2>
                    <p>Não há obrigatoriedade em compartilhar os Dados Pessoais que solicitamos. No entanto, se você optar por não os compartilhar, em alguns casos, não poderemos fornecer a você acesso completo à loja, alguns recursos especializados ou ser capaz de prestar a assistência necessária ou, ainda, viabilizar a entrega do produto ou prestar o serviço contratado por você.</p>

                    <h2>Seção 4 – Dados coletados</h2>
                    <p>O público em geral poderá navegar na loja sem necessidade de qualquer cadastro e envio de Dados Pessoais. No entanto, algumas das funcionalidades da loja poderão depender de cadastro e envio de Dados Pessoais como concluir a compra/contratação do serviço e/ou a viabilizar a entrega do produto/prestação do serviço por nós.</p>
                    <p>No contato a loja, nós podemos coletar:</p>
                    <ol>
                        <li>Dados de contato: nome, sobrenome, número de telefone, endereço, cidade, estado e endereço de e-mail;</li>
                        <li>Informações enviadas: informações que você envia via formulário (dúvidas, reclamações, sugestões, críticas, elogios etc.).</li>
                    </ol>
                    <p>Na navegação geral na loja, nós poderemos coletar:</p>
                    <ol>
                        <li>Dados de localização: dados de geolocalização quando você acessa a loja;</li>
                        <li>Preferências: informações sobre suas preferências e interesses em relação aos produtos/serviços (quando você nos diz o que eles são ou quando os deduzimos do que sabemos sobre você);</li>
                        <li>Dados de navegação na loja: informações sobre suas visitas e atividades, incluindo o conteúdo (e quaisquer anúncios) com os quais você visualiza e interage, informações sobre o navegador e o dispositivo que você está usando, seu endereço IP, sua localização, o endereço do site a partir do qual você chegou. Algumas dessas informações são coletadas usando nossas Ferramentas de Coleta Automática de Dados, que incluem cookies, web beacons e links da web incorporados. Para saber mais, leia como nós usamos Ferramentas de Coleta Automática de Dados na seção 7 abaixo;</li>
                        <li>Dados anônimos ou agregados: respostas anônimas para pesquisas ou informações anônimas e agregadas sobre como a loja é usufruída. Durante nossas operações, em certos casos, aplicamos um processo de desidentificação ou pseudonimização aos seus dados para que seja razoavelmente improvável que você identifique você através do uso desses dados com a tecnologia disponível;</li>
                        <li>Outras informações que podemos coletar: informações que não revelem especificamente a sua identidade ou que não são diretamente relacionadas a um indivíduo, tais como informações sobre navegador e dispositivo; dados de uso da Loja; e informações coletadas por meio de cookies, pixel tags e outras tecnologias.</li>
                    </ol>
                    <p>Nós não coletamos Dados Pessoais Sensíveis.</p>

                    <h2>Seção 5 – Compartilhamento de Dados Pessoais com terceiros</h2>
                    <p>Nós poderemos compartilhar seus Dados Pessoais:</p>
                    <ol>
                        <li>Com a(s) empresa(s) parceira(s) que você selecionar ou optar em enviar os seus dados, dúvidas, perguntas etc., bem como com provedores de serviços ou parceiros para gerenciar ou suportar certos aspectos de nossas operações comerciais em nosso nome. Esses provedores de serviços ou parceiros podem estar localizados nos Estados Unidos, na Argentina, no Brasil ou em outros locais globais, incluindo servidores para homologação e produção, e prestadores de serviços de hospedagem e armazenamento de dados, gerenciamento de fraudes, suporte ao cliente, vendas em nosso nome, atendimento de pedidos, personalização de conteúdo, atividades de publicidade e marketing (incluindo publicidade digital e personalizada) e serviços de TI, por exemplo;</li>
                        <li>Com terceiros, com o objetivo de nos ajudar a gerenciar a loja;</li>
                        <li>Com terceiros, caso ocorra qualquer reorganização, fusão, venda, joint venture, cessão, transmissão ou transferência de toda ou parte da nossa empresa, ativo ou capital (incluindo os relativos à falência ou processos semelhantes).</li>
                    </ol>

                    <h2>Seção 6 – Transferências internacionais de dados</h2>
                    <p>Dados Pessoais e informações de outras naturezas coletadas por nós podem ser transferidos ou acessados por entidades pertencentes ao grupo corporativo das empresas parceiras em todo o mundo de acordo com esta Política de Privacidade.</p>

                    <h2>Seção 7 – Coleta automática de Dados Pessoais</h2>
                    <p>Quando você visita a loja, ela pode armazenar ou recuperar informações em seu navegador, principalmente na forma de cookies, que são arquivos de texto contendo pequenas quantidades de informação. Essas informações podem ser sobre você, suas preferências ou seu dispositivo e são usadas principalmente para que a loja funcione como você espera. As informações geralmente não o identificam diretamente, mas podem oferecer uma experiência na internet mais personalizada.</p>
                    <p>De acordo com esta Política de Privacidade, nós e nossos prestadores de serviços terceirizados, mediante seu consentimento, podemos coletar seus Dados Pessoais de diversas formas, incluindo, entre outros:</p>
                    <ol>
                        <li>Por meio do navegador ou do dispositivo: algumas informações são coletadas pela maior parte dos navegadores ou automaticamente por meio de dispositivos de acesso à internet, como o tipo de computador, resolução da tela, nome e versão do sistema operacional, modelo e fabricante do dispositivo, idioma, tipo e versão do navegador de Internet que está utilizando. Podemos utilizar essas informações para assegurar que a loja funcione adequadamente.</li>
                        <li>Uso de cookies: informações sobre o seu uso da loja podem ser coletadas por terceiros a partir de cookies. Cookies são informações armazenadas diretamente no computador que você está utilizando. Os cookies permitem a coleta de informações tais como o tipo de navegador, o tempo despendido na loja, as páginas visitadas, as preferências de idioma, e outros dados de tráfego anônimos. Nós e nossos prestadores de serviços utilizamos informações para proteção de segurança, para facilitar a navegação, exibir informações de modo mais eficiente, e personalizar sua experiência ao utilizar a loja, assim como para rastreamento online. Também coletamos informações estatísticas sobre o uso da loja para aprimoramento contínuo do nosso design e funcionalidade, para entender como a loja é utilizada e para auxiliá-lo a solucionar questões relativas à loja.<br>
                        Caso não deseje que suas informações sejam coletadas por meio de cookies, há um procedimento simples na maior parte dos navegadores que permite que os cookies sejam automaticamente rejeitados, ou oferece a opção de aceitar ou rejeitar a transferência de um cookie (ou cookies) específico(s) de um site determinado para o seu computador. Entretanto, isso pode gerar inconvenientes no uso da loja.<br>
                        As definições que escolher podem afetar a sua experiência de navegação e o funcionamento que exige a utilização de cookies. Neste sentido, rejeitamos qualquer responsabilidade pelas consequências resultantes do funcionamento limitado da loja provocado pela desativação de cookies no seu dispositivo (incapacidade de definir ou ler um cookie).</li>
                        <li>Uso de pixel tags e outras tecnologias similares: pixel tags (também conhecidos como Web beacons e GIFs invisíveis) podem ser utilizados para rastrear ações de usuários da loja (incluindo destinatários de e-mails), medir o sucesso das nossas campanhas de marketing e coletar dados estatísticos sobre o uso da loja e taxas de resposta, e ainda para outros fins não especificados. Podemos contratar empresas de publicidade comportamental, para obter relatórios sobre os anúncios da loja em toda a internet. Para isso, essas empresas utilizam cookies, pixel tags e outras tecnologias para coletar informações sobre a sua utilização, ou sobre a utilização de outros usuários, da nossa loja e de site de terceiros. Nós não somos responsáveis por pixel tags, cookies e outras tecnologias similares utilizadas por terceiros.</li>
                    </ol>

                    <h2>Seção 8 – Categorias de cookies</h2>
                    <p>Os cookies utilizados na nossa loja estão de acordo com os requisitos legais e são enquadrados nas seguintes categorias:</p>
                    <ol>
                        <li>Estritamente necessários: estes cookies permitem que você navegue pelo site e desfrute de recursos essenciais com segurança. Um exemplo são os cookies de segurança, que autenticam os usuários, protegem os seus dados e evitam a criação de logins fraudulentos.</li>
                        <li>Desempenho: os cookies desta categoria coletam informações de forma codificada e anônima relacionadas à nossa loja virtual, como, por exemplo, o número de visitantes de uma página específica, origem das visitas ao site e quais as páginas acessadas pelo usuário. Todos os dados coletados são utilizados apenas para eventuais melhorias no site e para medir a eficácia da nossa comunicação.</li>
                        <li>Funcionalidade: estes cookies são utilizados para lembrar definições de preferências do usuário com o objetivo de melhorar a sua visita no nosso site, como, por exemplo, configurações aplicadas no layout do site ou suas respostas para pop-ups de promoções e cadastros -; dessa forma, não será necessário perguntar inúmeras vezes.</li>
                        <li>Publicidade: utilizamos cookies com o objetivo de criar campanhas segmentadas e entregar anúncios de acordo com o seu perfil de consumo na nossa loja virtual.</li>
                    </ol>

                    <h2>Seção 9 – Direitos do Usuário</h2>
                    <p>Você pode, a qualquer momento, requerer: (i) confirmação de que seus Dados Pessoais estão sendo tratados; (ii) acesso aos seus Dados Pessoais; (iii) correções a dados incompletos, inexatos ou desatualizados; (iv) anonimização, bloqueio ou eliminação de dados desnecessários, excessivos ou tratados em desconformidade com o disposto em lei; (v) portabilidade de Dados Pessoais a outro prestador de serviços, contanto que isso não afete nossos segredos industriais e comerciais; (vi) eliminação de Dados Pessoais tratados com seu consentimento, na medida do permitido em lei; (vii) informações sobre as entidades às quais seus Dados Pessoais tenham sido compartilhados; (viii) informações sobre a possibilidade de não fornecer o consentimento e sobre as consequências da negativa; e (ix) revogação do consentimento. Os seus pedidos serão tratados com especial cuidado de forma a que possamos assegurar a eficácia dos seus direitos. Poderá lhe ser pedido que faça prova da sua identidade de modo a assegurar que a partilha dos Dados Pessoais é apenas feita com o seu titular.</p>
                    <p>Você deverá ter em mente que, em certos casos (por exemplo, devido a requisitos legais), o seu pedido poderá não ser imediatamente satisfeito, além de que nós poderemos não conseguir atendê-lo por conta de cumprimento de obrigações legais.</p>

                    <h2>Seção 10 – Segurança dos Dados Pessoais</h2>
                    <p>Buscamos adotar as medidas técnicas e organizacionais previstas pelas Leis de Proteção de Dados adequadas para proteção dos Dados Pessoais na nossa organização. Infelizmente, nenhuma transmissão ou sistema de armazenamento de dados tem a garantia de serem 100% seguros. Caso tenha motivos para acreditar que sua interação conosco tenha deixado de ser segura (por exemplo, caso acredite que a segurança de qualquer uma de suas contas foi comprometida), favor nos notificar imediatamente.</p>

                    <h2>Seção 11 – Links de hipertexto para outros sites e redes sociais</h2>
                    <p>A Loja poderá, de tempos a tempos, conter links de hipertexto que redirecionará você para sites das redes dos nossos parceiros, anunciantes, fornecedores etc. Se você clicar em um desses links para qualquer um desses sites, lembre-se que cada site possui as suas próprias práticas de privacidade e que não somos responsáveis por essas políticas. Consulte as referidas políticas antes de enviar quaisquer Dados Pessoais para esses sites.</p>
                    <p>Não nos responsabilizamos pelas políticas e práticas de coleta, uso e divulgação (incluindo práticas de proteção de dados) de outras organizações, tais como Facebook, Apple, Google, Microsoft, ou de qualquer outro desenvolvedor de software ou provedor de aplicativo, loja de mídia social, sistema operacional, prestador de serviços de internet sem fio ou fabricante de dispositivos, incluindo todos os Dados Pessoais que divulgar para outras organizações por meio dos aplicativos, relacionadas a tais aplicativos, ou publicadas em nossas páginas em mídias sociais. Nós recomendamos que você se informe sobre a política de privacidade de cada site visitado ou de cada prestador de serviço utilizado.</p>

                    <h2>Seção 12 – Atualizações desta Política de Privacidade</h2>
                    <p>Se modificarmos nossa Política de Privacidade, publicaremos o novo texto na loja, com a data de revisão atualizada. Podemos alterar esta Política de Privacidade a qualquer momento. Caso haja alteração significativa nos termos desta Política de Privacidade, podemos informá-lo por meio das informações de contato que tivermos em nosso banco de dados ou por meio de notificação em nossa loja.</p>
                    <p>Recordamos que nós temos como compromisso não tratar os seus Dados Pessoais de forma incompatível com os objetivos descritos acima, exceto se de outra forma requerido por lei ou ordem judicial.</p>
                    <p>Sua utilização da loja após as alterações significa que aceitou as Políticas de Privacidade revisadas. Caso, após a leitura da versão revisada, você não esteja de acordo com seus termos, favor encerrar o acesso à loja.</p>

                    <h2>Seção 13 – Encarregado do tratamento dos Dados Pessoais</h2>
                    <p>Caso pretenda exercer qualquer um dos direitos previstos, inclusive retirar o seu consentimento, nesta Política de Privacidade e/ou nas Leis de Proteção de Dados, ou resolver quaisquer dúvidas relacionadas ao Tratamento de seus Dados Pessoais, favor contatar-nos em marktourmoz@gmail.com.</p>
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
</body>
</html>