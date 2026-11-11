<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
$idUsuario = requerirLogin();
$idDisfraz = (int) (entrada()['id_disfraz'] ?? 0);
try {
    $ex = $pdo->prepare('SELECT COUNT(*) FROM disfraces WHERE id = ? AND eliminado = 0');
    $ex->execute([$idDisfraz]);
    if (!$ex->fetchColumn()) responder(404, ['mensaje' => 'El disfraz no existe.']);

    $pdo->beginTransaction();
    $pdo->prepare('INSERT INTO votos (id_usuario, id_disfraz) VALUES (?, ?)')->execute([$idUsuario, $idDisfraz]);
    $pdo->prepare('UPDATE disfraces SET votos = votos + 1 WHERE id = ?')->execute([$idDisfraz]);
    $pdo->commit();
    $v = $pdo->prepare('SELECT votos FROM disfraces WHERE id = ?');
    $v->execute([$idDisfraz]);
    responder(200, ['mensaje' => 'Voto registrado.', 'votos' => (int) $v->fetchColumn()]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($e->getCode() === '23000') responder(409, ['mensaje' => 'Ya votaste por este disfraz.']);
    error_log($e->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
