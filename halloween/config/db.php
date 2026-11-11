<?php
require_once __DIR__ . '/../includes/helpers.php';
try {
    $pdo = new PDO('mysql:host=localhost;dbname=halloween;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['mensaje' => 'No se pudo conectar con la base de datos.']);
}
