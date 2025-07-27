<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/auth-jwt.php';


$validStatus = ['aberto', 'processando', 'enviado', 'entregue', 'cancelado'];

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    protectEndpoint();

    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || empty($data['cliente_nome'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "ID e cliente_nome são obrigatórios para atualização"
        ]);
        exit;
    }

    if (!in_array($data['status'], $validStatus)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Status inválido. Valores permitidos: " . implode(', ', $validStatus)
        ]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE pedidos 
            SET cliente_nome = :cliente_nome, 
                descricao = :descricao, 
                status = :status,
                data_atualizacao = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->bindParam(':cliente_nome', $data['cliente_nome']);
        $stmt->bindParam(':descricao', $data['descricao']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Pedido atualizado com sucesso"
        ]);
    } catch (Exception $error) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Erro ao atualizar pedido: " . $error->getMessage()
        ]);
    }

    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    protectEndpoint();

    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['cliente_nome'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Nome do cliente (cliente_nome) é obrigatório"
        ]);
        exit;
    }

    if (!in_array($data['status'], $validStatus)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Status inválido. Valores permitidos: " . implode(', ', $validStatus)
        ]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO pedidos (cliente_nome, descricao, status)
            VALUES (:cliente_nome, :descricao, :status)
        ");

        $stmt->bindParam(':cliente_nome', $data['cliente_nome']);
        $stmt->bindParam(':descricao', $data['descricao']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Pedido inserido com sucesso"
        ]);
    } catch (Exception $error) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Erro ao inserir pedido: " . $error->getMessage()
        ]);
    }

    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    protectEndpoint();
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Pedido não encontrado"
                ]);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "data" => $order
            ]);
        } catch (Exception $error) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Erro ao buscar pedido: " . $error->getMessage()
            ]);
        }
    } else {
        try {
            $stmt = $pdo->query("SELECT * FROM pedidos");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "data" => $orders
            ]);
        } catch (Exception $error) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Erro ao buscar pedidos: " . $error->getMessage()
            ]);
        }
    }

    exit;
}
