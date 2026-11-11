<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
requerirAdmin();
$id = (int) (entrada()['id'] ?? 0);
try {
    $st = $pdo->prepare('UPDATE disfraces SET eliminado = 1 WHERE id = ? AND eliminado = 0');
    $st->execute([$id]);
    if ($st->rowCount() === 0) responder(404, ['mensaje' => 'El disfraz no existe.']);
    responder(200, ['mensaje' => 'Disfraz eliminado.']);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
