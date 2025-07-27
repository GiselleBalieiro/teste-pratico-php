<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Orders.php';

$orderModel = new OrderModel($pdo);
$result = $orderModel->ensureColumnsExist();

echo json_encode($result);