<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/auth-jwt.php';

header("Content-Type: application/json");

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
