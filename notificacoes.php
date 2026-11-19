
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$usuario = requer_login($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    csrf_validar();

    $acao = $_POST['acao'] ?? '';

    if ($acao === 'marcar_todas') {

        $stmt = $pdo->prepare("
            UPDATE notificacoes
            SET lida = 1
            WHERE usuario_id = ?
        ");

        $stmt->execute([$usuario['id']]);

    } elseif ($acao === 'marcar') {

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {

            $stmt = $pdo->prepare("
                UPDATE notificacoes
                SET lida = 1
                WHERE id = ? AND usuario_id = ?
            ");

            $stmt->execute([$id, $usuario['id']]);

        }
    }

    redirecionar('notificacoes.php');
}

$stmt = $pdo->prepare("
    SELECT
        n.id,
        n.mensagem,
        n.link,
        n.lida,
        n.criada_em,
        a.titulo,
        u.usuario AS artista
    FROM notificacoes n
    INNER JOIN atualizacoes a ON a.id = n.atualizacao_id
    INNER JOIN usuarios u ON u.id = a.artista_id
    WHERE n.usuario_id = ?
    ORDER BY n.criada_em DESC
");

$stmt->execute([$usuario['id']]);

$notificacoes = $stmt->fetchAll();

$naoLidas = count(
    array_filter(
        $notificacoes,
        static fn($item) => !(int) $item['lida']
    )
);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notificações — Webtoonsz</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 24px;
            font-family: Arial, sans-serif;
            color: #F8F5FF;
            background:
                radial-gradient(circle at 8% 15%, #4928c2b8, transparent 30%),
                radial-gradient(circle at 92% 20%, #bf40fa52, transparent 28%),
                linear-gradient(135deg, #12091a, #24102f 48%, #08090c);
        }

        main {
            width: min(900px, 100%);
            margin: auto;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 11px 15px;
            border: 0;
            border-radius: 9px;
            background: #BF40FA;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .item {
            padding: 18px;
            margin: 12px 0;
            border: 1px solid #e3d9fc2b;
            border-radius: 14px;
            background: #04060799;
        }

        .item.unread {
            border-color: #BF40FA;
        }

        .item small {
            color: #E3D9FC;
        }

        .item p {
            line-height: 1.5;
            color: #c9bfd7;
        }

        .muted {
            color: #c9bfd7;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .empty {
            padding: 22px;
            border: 1px dashed #e3d9fc55;
            border-radius: 12px;
            color: #c9bfd7;
        }
    </style>

</head>

<body>

    <main>

        <header class="top">

            <div>

                <h1>Notificações</h1>

                <p class="muted">
                    <?= $naoLidas ?> não lida(s)
                </p>

            </div>

            <div class="actions">

                <a class="btn" href="perfil.php">
                    Voltar ao perfil
                </a>

                <?php if ($naoLidas): ?>

                    <form method="post">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= h(csrf_token()) ?>"
                        >

                        <input
                            type="hidden"
                            name="acao"
                            value="marcar_todas"
                        >

                        <button class="btn" type="submit">
                            Marcar todas como lidas
                        </button>

                    </form>

                <?php endif; ?>

            </div>

        </header>

        <?php if ($notificacoes): ?>

            <?php foreach ($notificacoes as $item): ?>

                <article class="item <?= $item['lida'] ? '' : 'unread' ?>">

                    <small>
                        @<?= h($item['artista']) ?>
                        ·
                        <?= h(date('d/m/Y H:i', strtotime($item['criada_em']))) ?>
                    </small>

                    <h2>
                        <?= h($item['titulo']) ?>
                    </h2>

                    <p>
                        <?= h($item['mensagem']) ?>
                    </p>

                    <div class="actions">

                        <a class="btn" href="<?= h($item['link']) ?>">
                            Abrir
                        </a>

                        <?php if (!$item['lida']): ?>

                            <form method="post">

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= h(csrf_token()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="acao"
                                    value="marcar"
                                >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $item['id'] ?>"
                                >

                                <button class="btn" type="submit">
                                    Marcar como lida
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty">
                Você ainda não tem notificações. Quando artistas que você segue ou obras favoritas receberem atualizações, elas aparecerão aqui.
            </div>

        <?php endif; ?>

    </main>

</body>

</html>
