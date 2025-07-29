<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");

require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['cliente_nome'])) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Nome do cliente (cliente_nome) é obrigatório"
            ]);
            exit;
        }

        $nome = $_POST['cliente_nome'];

        $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_nome) VALUES (:cliente_nome)");
        $stmt->bindParam(':cliente_nome', $nome);
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Pedido criado!"
        ]);
    } catch (Exception $error) { 
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Erro ao criar pedido: " . $error->getMessage()
        ]);
    }
}