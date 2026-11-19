
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);

if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

$id = filter_input(INPUT_GET, 'obra_id', FILTER_VALIDATE_INT);

$s = $pdo->prepare('
    SELECT u.nome, u.usuario
    FROM seguidores s
    JOIN usuarios u ON u.id = s.seguidor_id
    WHERE s.artista_id = ?
    ORDER BY s.criado_em DESC
');

$s->execute([$u['id']]);
$seguidores = $s->fetchAll();

$sql = '
    SELECT o.titulo, u.usuario
    FROM favoritos f
    JOIN obras o ON o.id = f.obra_id
    JOIN usuarios u ON u.id = f.usuario_id
    WHERE o.artista_id = ?
';

$args = [$u['id']];

if ($id) {
    $sql .= ' AND o.id = ?';
    $args[] = $id;
}

$s = $pdo->prepare($sql);
$s->execute($args);
$favoritos = $s->fetchAll();

?>

<!doctype html>
<html lang="pt-BR">
<meta charset="utf-8">
<title>Seguidores e favoritos</title>

<style>
    body {
        padding: 24px;
        background: #160d20;
        color: white;
        font-family: Arial;
    }

    main {
        max-width: 800px;
        margin: auto;
    }

    .item {
        padding: 14px;
        margin: 10px 0;
        border: 1px solid #e3d9fc33;
        border-radius: 9px;
    }

    .btn {
        padding: 10px 14px;
        background: #BF40FA;
        color: white;
        text-decoration: none;
        border-radius: 8px;
    }
</style>

<main>
    <h1>Seguidores e favoritos</h1>

    <a class="btn" href="painel_artista.php">Voltar</a>

    <h2>Seguidores (<?=count($seguidores)?>)</h2>

    <?php foreach ($seguidores as $s): ?>
        <p class="item">
            @<?=h($s['usuario'])?> · <?=h($s['nome'])?>
        </p>
    <?php endforeach; ?>

    <?php if (!$seguidores): ?>
        <p>Nenhum seguidor ainda.</p>
    <?php endif; ?>

    <h2>Favoritos nas suas obras (<?=count($favoritos)?>)</h2>

    <?php foreach ($favoritos as $f): ?>
        <p class="item">
            <?=h($f['titulo'])?> — favoritada por @<?=h($f['usuario'])?>
        </p>
    <?php endforeach; ?>

    <?php if (!$favoritos): ?>
        <p>Nenhuma obra favoritada ainda.</p>
    <?php endif; ?>
</main>

</html>
