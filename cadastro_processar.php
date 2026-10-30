<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('cadastro.php');
}

$email     = trim($_POST['email'] ?? '');
$usuario   = trim($_POST['usuario'] ?? '');
$senha     = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

$erro = null;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = 'Email inválido.';
} elseif (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $usuario)) {
    $erro = 'Usuário: 3 a 30 caracteres (letras, números e _).';
} elseif (strlen($senha) < 6) {
    $erro = 'A senha deve ter pelo menos 6 caracteres.';
} elseif ($senha !== $confirmar) {
    $erro = 'As senhas não conferem.';
}

if (!$erro) {
    $stmt = $pdo->prepare('SELECT 1 FROM usuarios WHERE email = ? OR usuario = ?');
    $stmt->execute([$email, $usuario]);
    if ($stmt->fetch()) {
        $erro = 'Email ou usuário já cadastrado.';
    }
}

if ($erro) {
    flash('erro', $erro);
    redirecionar('cadastro.php');
}

$stmt = $pdo->prepare(
    'INSERT INTO usuarios (nome, usuario, email, senha_hash) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$usuario, $usuario, $email, password_hash($senha, PASSWORD_DEFAULT)]);

session_regenerate_id(true);
$_SESSION['usuario_id'] = (int) $pdo->lastInsertId();

redirecionar('home.php');
