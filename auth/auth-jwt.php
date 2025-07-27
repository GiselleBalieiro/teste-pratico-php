<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

function generateJWT($data) {
    $payload = [
        'iss' => 'backend-api',
        'iat' => time(),
        'exp' => time() + 3600,
        'user' => $data
    ];

    return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
}

function verifyJWT($token) {
    try {
        return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}

function protectEndpoint() {
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token não fornecido']);
        exit;
    }

    $jwt = $matches[1];
    $decoded = verifyJWT($jwt);

    if (!$decoded) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token inválido ou expirado']);
        exit;
    }

    return $decoded;
}
