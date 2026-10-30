<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('login.php');
}

$login = trim($_POST['usuario'] ?? '');   // email ou usuário
$senha = $_POST['senha'] ?? '';

$stmt = $pdo->prepare('SELECT id, senha_hash FROM usuarios WHERE usuario = ? OR email = ?');
$stmt->execute([$login, $login]);
$u = $stmt->fetch();

if (!$u || !password_verify($senha, $u['senha_hash'])) {
    flash('erro', 'Usuário ou senha incorretos.');
    redirecionar('login.php');
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = (int) $u['id'];

redirecionar('home.php');
