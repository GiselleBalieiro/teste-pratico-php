<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Orders.php';

$orderModel = new OrderModel($pdo);
$result = $orderModel->ensureColumnsExist();

echo json_encode($result);