<?php
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../config/db.php';
$st = $pdo->prepare('SELECT foto_blob FROM disfraces WHERE id = ? AND eliminado = 0');
$st->execute([(int) ($_GET['id'] ?? 0)]);
$blob = $st->fetchColumn();
if ($blob === false) responder(404, ['mensaje' => 'Imagen no encontrada.']);
header('Content-Type: ' . (new finfo(FILEINFO_MIME_TYPE))->buffer($blob));
header('Cache-Control: public, max-age=3600');
echo $blob;
