<?php
require_once __DIR__ . '/helpers.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
}
function requerirLogin(): int {
    if (empty($_SESSION['id'])) responder(401, ['mensaje' => 'Debés iniciar sesión.']);
    return (int) $_SESSION['id'];
}
function requerirAdmin(): void {
    requerirLogin();
    if (empty($_SESSION['admin'])) responder(403, ['mensaje' => 'Acceso solo para administradores.']);
}
