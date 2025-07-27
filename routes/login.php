<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/auth-jwt.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$usuario = $data['usuario'] ?? '';
$senha = $data['senha'] ?? '';

if ($usuario === $_ENV['LOGIN_USUARIO'] && $senha === $_ENV['LOGIN_SENHA']) {
    $token = generateJWT(['usuario' => $usuario]);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'token' => $token
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Credenciais inválidas'
    ]);
}
