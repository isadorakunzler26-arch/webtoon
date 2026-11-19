<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);
if ($u['tipo'] !== 'leitor') {
    flash('erro', 'Esta funcionalidade está disponível para contas de leitor.');
    redirecionar('perfil.php');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('home.php');
csrf_validar();

$artistaId = filter_input(INPUT_POST, 'artista_id', FILTER_VALIDATE_INT);
$acao = $_POST['acao'] ?? 'seguir';
if (!$artistaId || $artistaId === (int) $u['id'] || !in_array($acao, ['seguir', 'deixar'], true)) {
    flash('erro', 'Artista inválido.');
    redirecionar('home.php');
}

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ? AND tipo = 'artista' LIMIT 1");
$stmt->execute([$artistaId]);
if (!$stmt->fetch()) {
    flash('erro', 'Artista não encontrado.');
    redirecionar('home.php');
}

if ($acao === 'seguir') {
    $stmt = $pdo->prepare('INSERT IGNORE INTO seguidores (seguidor_id, artista_id) VALUES (?, ?)');
    $stmt->execute([$u['id'], $artistaId]);
    flash('ok', 'Você está seguindo este artista.');
} else {
    $stmt = $pdo->prepare('DELETE FROM seguidores WHERE seguidor_id = ? AND artista_id = ?');
    $stmt->execute([$u['id'], $artistaId]);
    flash('ok', 'Você deixou de seguir este artista.');
}

$voltar = $_POST['voltar'] ?? 'artista.php?id=' . $artistaId;
if (!preg_match('/^(artista\.php\?id=\d+|perfil\.php)$/', $voltar)) $voltar = 'artista.php?id=' . $artistaId;
redirecionar($voltar);
