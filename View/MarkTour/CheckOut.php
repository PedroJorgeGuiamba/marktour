<?php
session_start();
require_once __DIR__ . '/../../lib/stripe/init.php';



$secret_stripe_apiKey = getEnv('STRIPE_SECRET_KEY'); ;










\Stripe\Stripe::setApiKey($secret_stripe_apiKey);

// Verificar se o carrinho existe e não está vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart_error'] = "Seu carrinho está vazio. Adicione itens antes de prosseguir com o pagamento.";
    header("Location: carrinho.php");
    exit();
}

// Construir os itens para o checkout
$line_items = [];
foreach ($_SESSION['cart'] as $id => $item) {
    $line_items[] = [
        "quantity" => $item['quantidade'],
        "price_data" => [
            "currency" => "mzn",
            "unit_amount" => $item['preco_noite'] * 100, // Converter para centavos
            "product_data" => [
                "name" => $item['nome']
            ]
        ]
    ];
}

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost/marktour/View/MarkTour/Success.php",
        "cancel_url" => "http://localhost/marktour/View/Empresa/carrinho.php",
        "line_items" => $line_items
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);
} catch (\Stripe\Exception\ApiErrorException $e) {
    $_SESSION['cart_error'] = "Erro ao processar o pagamento: " . $e->getMessage();
    header("Location: carrinho.php");
    exit();
}
?>
