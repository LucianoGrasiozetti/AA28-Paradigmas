<?php
require_once __DIR__ . '/../../includes/auth.php';
responder(200, [
    'logueado' => !empty($_SESSION['id']),
    'nombre' => $_SESSION['nombre'] ?? null,
    'admin' => !empty($_SESSION['admin']),
]);
