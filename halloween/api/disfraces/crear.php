<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
$idUsuario = requerirLogin();
[$nombre, $desc] = datosDisfraz();
$foto = leerFoto();
if (!$foto) responder(400, ['mensaje' => 'La foto es obligatoria.']);
try {
    $st = $pdo->prepare('INSERT INTO disfraces (nombre, descripcion, votos, foto, foto_blob, id_usuario) VALUES (?, ?, 0, ?, ?, ?)');
    $st->bindValue(1, $nombre);
    $st->bindValue(2, $desc);
    $st->bindValue(3, $foto['nombre']);
    $st->bindValue(4, $foto['blob'], PDO::PARAM_LOB);
    $st->bindValue(5, $idUsuario, PDO::PARAM_INT);
    $st->execute();
    responder(201, ['mensaje' => "Disfraz «$nombre» subido. ¡Ya aparece en la lista!"]);
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
