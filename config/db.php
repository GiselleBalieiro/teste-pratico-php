<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = $_ENV["DATABASE_HOST"];
$dbname = $_ENV["DATABASE"];
$username = $_ENV["DATABASE_USERNAME"];
$password = $_ENV["DATABASE_PASSWORD"];

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $error) {
    error_log("Erro de conexão com o banco: " . $error->getMessage());
    die("Erro ao conectar com o banco de dados.");
}


