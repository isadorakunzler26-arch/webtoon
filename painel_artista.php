
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);

if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

$s = $pdo->prepare("
    SELECT *
    FROM obras
    WHERE artista_id = ?
    ORDER BY criada_em DESC
");

$s->execute([$u['id']]);

$obras = $s->fetchAll();

$s = $pdo->prepare("
    SELECT COUNT(*)
    FROM seguidores
    WHERE artista_id = ?
");

$s->execute([$u['id']]);

$seguidores = $s->fetchColumn();

$s = $pdo->prepare("
    SELECT COUNT(*)
    FROM favoritos f
    JOIN obras o ON o.id = f.obra_id
    WHERE o.artista_id = ?
");

$s->execute([$u['id']]);

$favoritos = $s->fetchColumn();

$s = $pdo->prepare("
    SELECT
        a.*,
        o.titulo AS obra
    FROM atualizacoes a
    LEFT JOIN obras o ON o.id = a.obra_id
    WHERE a.artista_id = ?
    ORDER BY a.criado_em DESC
");

$s->execute([$u['id']]);

$avisos = $s->fetchAll();

$ok = flash('ok');
$erro = flash('erro');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Painel do artista</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            color: #F8F5FF;
            background: linear-gradient(135deg, #12091a, #24102f, #08090c);
        }

        main {
            max-width: 1000px;
            margin: auto;
        }

        .bar,
        .actions {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 11px 14px;
            border-radius: 9px;
            background: #BF40FA;
            color: white;
            text-decoration: none;
            border: 0;
            cursor: pointer;
        }

        .box {
            padding: 16px;
            margin: 12px 0;
            border: 1px solid #e3d9fc33;
            border-radius: 12px;
            background: #04060799;
        }

        .muted {
            color: #c9bfd7;
        }

        .stats {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .stats .box {
            flex: 1;
            min-width: 150px;
        }

        .num {
            font-size: 2rem;
            color: #BF40FA;
        }

        .cover {
            width: 60px;
            height: 80px;
            object-fit: cover;
            float: left;
            margin-right: 12px;
            border-radius: 7px;
        }

        a {
            color: #E3D9FC;
        }
    </style>

</head>

<body>

    <main>

        <header class="bar">

            <div>

                <h1>Painel do artista</h1>

                <p class="muted">
                    Olá, <?= h($u['nome']) ?>
                </p>

            </div>

            <a class="btn" href="perfil.php">
                Meu perfil
            </a>

        </header>

        <?php if ($ok): ?>

            <p class="box">
                <?= $ok ?>
            </p>

        <?php endif; ?>

        <?php if ($erro): ?>

            <p class="box">
                <?= $erro ?>
            </p>

        <?php endif; ?>

        <section class="stats">

            <div class="box">

                <span class="num">
                    <?= count($obras) ?>
                </span>

                <p>Obras</p>

            </div>

            <div class="box">

                <span class="num">
                    <?= $seguidores ?>
                </span>

                <p>Seguidores</p>

            </div>

            <div class="box">

                <span class="num">
                    <?= $favoritos ?>
                </span>

                <p>Favoritos nas obras</p>

            </div>

        </section>

        <nav class="actions">

            <a class="btn" href="obra_form.php">
                Cadastrar obra
            </a>

            <a class="btn" href="aviso_form.php">
                Publicar aviso
            </a>

            <a class="btn" href="meus_avisos.php">
                Gerenciar avisos
            </a>

            <a class="btn" href="estatisticas_artista.php">
                Seguidores e favoritos
            </a>

            <a class="btn" href="editar_perfil_artista.php">
                Editar perfil público
            </a>

            <a class="btn" href="artista.php?id=<?= (int) $u['id'] ?>">
                Ver perfil público
            </a>

        </nav>

        <h2>Minhas obras</h2>

        <?php foreach ($obras as $o): ?>

            <article class="box">

                <?php if ($o['capa']): ?>

                    <img
                        class="cover"
                        src="<?= h($o['capa']) ?>"
                        alt=""
                    >

                <?php endif; ?>

                <b>
                    <?= h($o['titulo']) ?>
                </b>

                <p class="muted">
                    <?= h($o['genero'] ?? '') ?>
                    ·
                    <?= h($o['status']) ?>
                </p>

                <a href="obra_form.php?id=<?= (int) $o['id'] ?>">
                    Editar
                </a>

                ·

                <a href="obra.php?id=<?= (int) $o['id'] ?>">
                    Ver
                </a>

                <form
                    method="post"
                    action="obra_excluir.php"
                    style="display: inline"
                    onsubmit="return confirm('Excluir esta obra?')"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= h(csrf_token()) ?>"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $o['id'] ?>"
                    >

                    <button
                        style="background: none; border: 0; color: #E3D9FC; cursor: pointer"
                    >
                        Excluir
                    </button>

                </form>

                <div style="clear: both"></div>

            </article>

        <?php endforeach; ?>

        <?php if (!$obras): ?>

            <p class="box muted">
                Nenhuma obra cadastrada.
            </p>

        <?php endif; ?>

        <h2>Últimos avisos</h2>

        <?php foreach ($avisos as $a): ?>

            <article class="box">

                <?= h($a['titulo']) ?>

                ·

                <?= h($a['obra'] ?? 'Recado geral') ?>

                —

                <a href="aviso_form.php?id=<?= (int) $a['id'] ?>">
                    Editar
                </a>

            </article>

        <?php endforeach; ?>

    </main>

</body>

</html>
