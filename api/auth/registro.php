<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
$e = entrada();
$nombre = trim($e['nombre'] ?? '');
$clave = $e['clave'] ?? '';
if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 50) responder(400, ['mensaje' => 'El usuario debe tener entre 3 y 50 caracteres.']);
if (strlen($clave) < 8) responder(400, ['mensaje' => 'La contraseña debe tener al menos 8 caracteres.']);
if (strtolower($nombre) === 'admin') responder(409, ['mensaje' => 'Ese nombre de usuario no está disponible.']);
try {
    $pdo->prepare('INSERT INTO usuarios (nombre, clave) VALUES (?, ?)')
        ->execute([$nombre, password_hash($clave, PASSWORD_BCRYPT)]);
    responder(201, ['mensaje' => 'Cuenta creada. Ya podés iniciar sesión.']);
} catch (PDOException $ex) {
    if ($ex->getCode() === '23000') responder(409, ['mensaje' => 'Ese nombre de usuario no está disponible.']);
    error_log($ex->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
