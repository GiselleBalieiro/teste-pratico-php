<?php

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/login') {
    require_once __DIR__ . '/routes/login.php';
    exit;
}

if ($requestUri === '/workplace') {
    require_once __DIR__ . '/routes/workplace.php';
    exit;
}

if ($requestUri === '/pedidos') {
    require_once __DIR__ . '/routes/orders.php';
    exit;
}

if (preg_match('#^/pedidos/(\d+)$#', $requestUri, $matches)) {
    $_GET['id'] = $matches[1];
    require_once __DIR__ . '/routes/orders.php';
    exit;
}

http_response_code(404);
echo json_encode([
    "success" => false,
    "message" => "Rota não encontrada"
]);