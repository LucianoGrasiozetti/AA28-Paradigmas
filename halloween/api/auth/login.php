<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
$e = entrada();
try {
    $st = $pdo->prepare('SELECT id, nombre, clave FROM usuarios WHERE nombre = ?');
    $st->execute([trim($e['nombre'] ?? '')]);
    $u = $st->fetch();
    if (!$u || !password_verify($e['clave'] ?? '', $u['clave'])) {
        responder(401, ['mensaje' => 'Usuario o contraseña incorrectos.']);
    }
    session_regenerate_id(true);
    $_SESSION['id'] = (int) $u['id'];
    $_SESSION['nombre'] = $u['nombre'];
    $_SESSION['admin'] = strtolower($u['nombre']) === 'admin';
    responder(200, ['mensaje' => 'Sesión iniciada.', 'admin' => $_SESSION['admin']]);
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
