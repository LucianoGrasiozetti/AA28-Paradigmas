<?php
require_once __DIR__ . '/../../includes/auth.php';
metodo('POST');
$_SESSION = [];
session_destroy();
responder(200, ['mensaje' => 'Sesión cerrada.']);
