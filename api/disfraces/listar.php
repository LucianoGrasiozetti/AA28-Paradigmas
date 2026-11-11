<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('GET');
try {
    $lista = $pdo->query('SELECT id, nombre, descripcion, votos FROM disfraces WHERE eliminado = 0 ORDER BY votos DESC, nombre')->fetchAll();
    foreach ($lista as &$d) { $d['id'] = (int) $d['id']; $d['votos'] = (int) $d['votos']; }
    unset($d);
    foreach ($lista as &$d) $d['foto'] = '../api/disfraces/imagen.php?id=' . $d['id'];
    $votados = [];
    if (!empty($_SESSION['id'])) {
        $st = $pdo->prepare('SELECT id_disfraz FROM votos WHERE id_usuario = ?');
        $st->execute([$_SESSION['id']]);
        $votados = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
    }
    responder(200, ['disfraces' => $lista, 'votados' => $votados]);
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
