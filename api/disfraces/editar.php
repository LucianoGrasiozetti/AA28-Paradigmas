<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
metodo('POST');
requerirAdmin();
$id = (int) ($_POST['id_disfraz'] ?? 0);
[$nombre, $desc] = datosDisfraz();
$foto = leerFoto();
try {
    if ($foto) {
        $st = $pdo->prepare('UPDATE disfraces SET nombre = ?, descripcion = ?, foto = ?, foto_blob = ? WHERE id = ? AND eliminado = 0');
        $st->bindValue(1, $nombre);
        $st->bindValue(2, $desc);
        $st->bindValue(3, $foto['nombre']);
        $st->bindValue(4, $foto['blob'], PDO::PARAM_LOB);
        $st->bindValue(5, $id, PDO::PARAM_INT);
        $st->execute();
    } else {
        $st = $pdo->prepare('UPDATE disfraces SET nombre = ?, descripcion = ? WHERE id = ? AND eliminado = 0');
        $st->execute([$nombre, $desc, $id]);
    }
    if ($st->rowCount() === 0 && !$foto) {
        $ex = $pdo->prepare('SELECT COUNT(*) FROM disfraces WHERE id = ? AND eliminado = 0');
        $ex->execute([$id]);
        if (!$ex->fetchColumn()) responder(404, ['mensaje' => 'El disfraz no existe.']);
    }
    responder(200, ['mensaje' => "Disfraz «$nombre» actualizado."]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['mensaje' => 'Error interno del servidor.']);
}
