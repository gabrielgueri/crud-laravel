<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=laravel', 'laravel', 'secret');
    $dbStatus = 'Conectado com sucesso ao PostgreSQL!';
} catch (Exception $e) {
    $dbStatus = 'Erro ao conectar: ' . $e->getMessage();
}

echo json_encode([
    'status' => 'online',
    'php_version' => PHP_VERSION,
    'database' => $dbStatus
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
