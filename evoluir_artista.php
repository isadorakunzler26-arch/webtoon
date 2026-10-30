<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('perfil.php');
}

csrf_validar();
$usuario = requer_login($pdo);

if ($usuario['tipo'] === 'artista') {
    flash('erro', 'Sua conta já é de artista.');
    redirecionar('perfil.php');
}

$stmt = $pdo->prepare("UPDATE usuarios SET tipo = 'artista' WHERE id = ?");
$stmt->execute([$usuario['id']]);

flash('ok', 'Parabéns! Sua conta agora é de artista.');
redirecionar('perfil.php');
