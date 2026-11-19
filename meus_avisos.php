
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);

if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

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

$lista = $s->fetchAll();

$ok = flash('ok');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Meus avisos</title>

    <style>
        body {
            padding: 24px;
            background: #160d20;
            color: white;
            font-family: Arial, sans-serif;
        }

        main {
            max-width: 850px;
            margin: auto;
        }

        .item {
            padding: 16px;
            margin: 12px 0;
            border: 1px solid #e3d9fc33;
            border-radius: 12px;
            background: #04060799;
        }

        .btn {
            padding: 10px 14px;
            background: #BF40FA;
            color: white;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <main>

        <h1>Meus avisos</h1>

        <a class="btn" href="painel_artista.php">
            Painel
        </a>

        <a class="btn" href="aviso_form.php">
            Novo aviso
        </a>

        <?php if ($ok): ?>

            <p><?= $ok ?></p>

        <?php endif; ?>

        <?php foreach ($lista as $a): ?>

            <article class="item">

                <small>
                    <?= h($a['tipo']) ?>
                    ·
                    <?= h($a['obra'] ?? 'Recado geral') ?>
                    ·
                    <?= h($a['criado_em']) ?>
                </small>

                <h2>
                    <?= h($a['titulo']) ?>
                </h2>

                <p>
                    <?= nl2br(h($a['mensagem'])) ?>
                </p>

                <?php if ($a['data_evento']): ?>

                    <p>
                        Data: <?= h($a['data_evento']) ?>
                    </p>

                <?php endif; ?>

                <a
                    class="btn"
                    href="aviso_form.php?id=<?= (int) $a['id'] ?>"
                >
                    Editar
                </a>

                <form
                    method="post"
                    action="aviso_excluir.php"
                    style="display: inline"
                    onsubmit="return confirm('Excluir aviso?')"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= h(csrf_token()) ?>"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $a['id'] ?>"
                    >

                    <button class="btn">
                        Excluir
                    </button>

                </form>

            </article>

        <?php endforeach; ?>

        <?php if (!$lista): ?>

            <p>Nenhum aviso publicado.</p>

        <?php endif; ?>

    </main>

</body>

</html>
