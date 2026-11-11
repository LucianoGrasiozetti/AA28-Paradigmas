<?php
function responder(int $codigo, array $datos = []): never {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}
function metodo(string $m): void {
    if ($_SERVER['REQUEST_METHOD'] !== $m) responder(405, ['mensaje' => 'Método no permitido.']);
}
function entrada(): array {
    $j = json_decode(file_get_contents('php://input'), true);
    return is_array($j) ? $j : [];
}
function datosDisfraz(): array {
    $n = trim($_POST['nombre'] ?? '');
    $d = trim($_POST['descripcion'] ?? '');
    if ($n === '' || mb_strlen($n) > 50) responder(400, ['mensaje' => 'El nombre es obligatorio (máximo 50 caracteres).']);
    if ($d === '') responder(400, ['mensaje' => 'La descripción es obligatoria.']);
    return [$n, $d];
}
function leerFoto(): ?array {
    if (empty($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES['foto'];
    if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 5242880) responder(400, ['mensaje' => 'La imagen debe pesar hasta 5 MB.']);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'][$mime] ?? null;
    if (!$ext) responder(400, ['mensaje' => 'Formato no válido. Usá JPG, PNG, WEBP o GIF.']);
    return ['blob' => file_get_contents($f['tmp_name']), 'nombre' => bin2hex(random_bytes(6)) . '.' . $ext];
}
