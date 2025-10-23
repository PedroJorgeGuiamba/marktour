<?php
session_start();
require_once __DIR__ . '/../../lib/stripe/init.php';
require_once __DIR__ . '/../../Conexao/conector.php';

$secret_stripe_apiKey = getenv('STRIPE_SECRET_KEY');
\Stripe\Stripe::setApiKey($secret_stripe_apiKey);

if (!isset($_SESSION['id_utilizador'])) {
    header("Location: /marktour/Controller/Auth/LoginController.php");
    exit();
}

if (!isset($_GET['session_id'])) {
    $_SESSION['cart_error'] = "ID da sessão de pagamento não fornecido.";
    header("Location: /marktour/View/Utilizador/carrinho.php");
    exit();
}

try {
    $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
    if ($session->payment_status !== 'paid') {
        $_SESSION['cart_error'] = "O pagamento não foi concluído com sucesso.";
        header("Location: /marktour/View/Utilizador/carrinho.php");
        exit();
    }

    $payment_intent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
    $line_items = \Stripe\Checkout\Session::allLineItems($session->id, ['limit' => 100]);

    $conexao = new Conector();
    $conn = $conexao->getConexao();

    $conn->begin_transaction();

    $total = $session->amount_total / 100;
    $stmt_reserva = $conn->prepare("INSERT INTO reserva (id_utilizador, data_reserva, total, estado) VALUES (?, NOW(), ?, 'pendente')");
    $stmt_reserva->bind_param("id", $_SESSION['id_utilizador'], $total);
    $stmt_reserva->execute();
    $id_reserva = $stmt_reserva->insert_id; // Usar insert_id do statement para maior precisão

    $stmt_item = $conn->prepare("INSERT INTO reserva_item (id_reserva, tipo_item, id_item, quantidade, preco_unitario) VALUES (?, 'alojamento', ?, ?, ?)");
    foreach ($_SESSION['cart'] as $id => $item) {
        $stmt_item->bind_param("iiid", $id_reserva, $id, $item['quantidade'], $item['preco_noite']);
        $stmt_item->execute();
    }

    $valor = $total;
    $metodo = 'Cartão';
    $referencia = $payment_intent->id; // Usar ID do Stripe como referência
    $stmt_pagamento = $conn->prepare("INSERT INTO pagamento (metodo_pagamento, valor, data_pagamento, estado, referencia_stripe) VALUES (?, ?, NOW(), 'pago', ?)");
    $stmt_pagamento->bind_param("sds", $metodo, $valor, $referencia);
    $stmt_pagamento->execute();
    $id_pagamento = $stmt_pagamento->insert_id; // Usar insert_id do statement para maior precisão

    $stmt_reserva_pagamento = $conn->prepare("INSERT INTO reserva_pagamento (id_pagamento, id_reserva) VALUES (?, ?)");
    $stmt_reserva_pagamento->bind_param("ii", $id_pagamento, $id_reserva);
    $stmt_reserva_pagamento->execute();

    $mensagem = "Seu pagamento foi processado com sucesso. A reserva #$id_reserva está pendente de verificação por um administrador.";
    $stmt_notificacao = $conn->prepare("INSERT INTO notificacao (id_utilizador, mensagem) VALUES (?, ?)");
    $stmt_notificacao->bind_param("is", $_SESSION['id_utilizador'], $mensagem);
    $stmt_notificacao->execute();

    $conn->commit();

    unset($_SESSION['cart']);
} catch (\Stripe\Exception\ApiErrorException $e) {
    if (isset($conn)) $conn->rollback();
    $_SESSION['cart_error'] = "Erro ao recuperar detalhes do pagamento: " . $e->getMessage();
    header("Location: /marktour/View/Utilizador/carrinho.php");
    exit();
} catch (mysqli_sql_exception $e) {
    if (isset($conn)) $conn->rollback();
    $_SESSION['cart_error'] = "Erro ao armazenar a reserva: " . $e->getMessage();
    header("Location: /marktour/View/Utilizador/carrinho.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovativo de Pagamento - MarkTour</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Style/empresa.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Comprovativo de Pagamento</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pagamento Confirmado</h5>
                <p class="card-text"><strong>ID do Pagamento:</strong> <?php echo htmlspecialchars($payment_intent->id); ?></p>
                <p class="card-text"><strong>ID do Usuário:</strong> <?php echo htmlspecialchars($session->client_reference_id); ?></p>
                <p class="card-text"><strong>ID da Reserva:</strong> <?php echo $id_reserva; ?></p>
                <p class="card-text"><strong>Data do Pagamento:</strong> <?php echo date('d/m/Y H:i:s', $payment_intent->created); ?></p>
                <p class="card-text"><strong>Valor Total:</strong> <?php echo number_format($session->amount_total / 100, 2); ?> MZN</p>
                <h6>Itens Comprados:</h6>
                <ul>
                    <?php foreach ($line_items->data as $item): ?>
                        <li>
                            <?php echo htmlspecialchars($item->description); ?> - 
                            Quantidade: <?php echo $item->quantity; ?> - 
                            Valor: <?php echo number_format($item->amount_total / 100, 2); ?> MZN
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="card-text"><small class="text-muted">Obrigado pela sua compra! Sua reserva está pendente de verificação por um administrador.</small></p>
                <a href="../Utilizador/portalDoUtilizador.php" class="btn btn-primary">Voltar ao Início</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>