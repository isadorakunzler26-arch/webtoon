
<?php

session_start();

require_once __DIR__ . '/conexao.php';

$logado = isset($_SESSION['usuario_id']);
$siteName = 'Webtoonsz';

$stmt = $pdo->query("
    SELECT
        o.id,
        o.titulo,
        o.descricao,
        o.capa,
        o.status,
        u.id AS artista_id,
        u.usuario AS artista
    FROM obras o
    INNER JOIN usuarios u ON u.id = o.artista_id
    WHERE o.publicada = 1
    ORDER BY o.criada_em DESC
    LIMIT 12
");

$obras = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT
        a.id,
        a.titulo,
        a.mensagem,
        a.tipo,
        a.data_evento,
        a.plataforma_url,
        a.criado_em,
        o.id AS obra_id,
        o.titulo AS obra_titulo,
        u.id AS artista_id,
        u.usuario AS artista
    FROM atualizacoes a
    INNER JOIN usuarios u ON u.id = a.artista_id
    LEFT JOIN obras o ON o.id = a.obra_id
    WHERE a.publicada = 1
    ORDER BY a.criado_em DESC
    LIMIT 10
");

$atualizacoes = $stmt->fetchAll();

function h($valor): string
{
    return htmlspecialchars(
        (string) ($valor ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= h($siteName) ?> — Explorar</title>

    <style>
        :root {
            --lav: #E3D9FC;
            --pink: #BF40FA;
            --blue: #4928C2;
            --purple: #5B2A62;
            --ink: #040607;
            --white: #F8F5FF;
            --muted: rgba(248, 245, 255, .68);
            --border: rgba(227, 217, 252, .16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 24px;
            font-family: Arial, sans-serif;
            color: var(--white);
            background:
                radial-gradient(circle at 8% 15%, rgba(73, 40, 194, .65), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191, 64, 250, .28), transparent 28%),
                linear-gradient(135deg, #12091A, #24102F 48%, #08090C);
        }

        .page {
            width: min(1180px, 100%);
            margin: auto;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0 18px;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-size: 1.2rem;
            font-weight: 800;
            margin-right: auto;
        }

        .nav {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 11px 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: rgba(4, 6, 7, .65);
            color: var(--white);
            text-decoration: none;
            font-size: .85rem;
        }

        .btn.primary {
            background: var(--pink);
            border-color: var(--pink);
        }

        h1 {
            font-size: 2rem;
            margin: 32px 0 8px;
        }

        .sub {
            color: var(--muted);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .section {
            margin-top: 34px;
        }

        .section h2 {
            font-size: 1.25rem;
            margin-bottom: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 16px;
        }

        .work {
            color: var(--white);
            text-decoration: none;
            min-width: 0;
        }

        .cover {
            height: 205px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: linear-gradient(
                145deg,
                rgba(73, 40, 194, .8),
                rgba(191, 64, 250, .35),
                rgba(91, 42, 98, .8)
            );
            overflow: hidden;
        }

        .cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .work h3 {
            font-size: .9rem;
            margin-top: 8px;
        }

        .work p {
            font-size: .75rem;
            color: var(--muted);
            margin-top: 4px;
        }

        .updates {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 13px;
        }

        .update {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: rgba(4, 6, 7, .55);
        }

        .update small {
            color: var(--pink);
        }

        .update h3 {
            font-size: 1rem;
            margin: 8px 0;
        }

        .update p {
            color: var(--muted);
            font-size: .85rem;
            line-height: 1.5;
        }

        .update a {
            display: inline-block;
            margin-top: 10px;
            color: var(--lav);
            font-size: .8rem;
        }

        .empty {
            padding: 22px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            color: var(--muted);
        }

        .footer {
            margin-top: 45px;
            padding: 20px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: .8rem;
        }

        @media (max-width: 600px) {
            body {
                padding: 14px;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }

            .logo {
                margin-right: 0;
            }

            .cover {
                height: 180px;
            }
        }
    </style>

</head>

<body>

    <div class="page">

        <header class="header">

            <a class="logo" href="home.php">
                ✦ Webtoonsz
            </a>

            <nav class="nav">

                <a class="btn" href="busca.php">
                    Buscar obras
                </a>

                <?php if ($logado): ?>

                    <a class="btn" href="favoritos.php">
                        Favoritos
                    </a>

                    <a class="btn" href="atualizacoes.php">
                        Atualizações
                    </a>

                    <a class="btn" href="notificacoes.php">
                        Notificações
                    </a>

                    <a class="btn" href="perfil.php">
                        Meu perfil
                    </a>

                    <a class="btn" href="logout.php">
                        Sair
                    </a>

                <?php else: ?>

                    <a class="btn" href="login.php">
                        Entrar
                    </a>

                    <a class="btn primary" href="cadastro.php">
                        Cadastrar-se
                    </a>

                <?php endif; ?>

            </nav>

        </header>

        <main>

            <h1>Explore webtoons</h1>

            <p class="sub">
                Descubra histórias, conheça artistas e acompanhe avisos sobre lançamentos, pausas e novidades.
            </p>

            <section class="section">

                <h2>Obras divulgadas</h2>

                <?php if ($obras): ?>

                    <div class="grid">

                        <?php foreach ($obras as $obra): ?>

                            <a
                                class="work"
                                href="obra.php?id=<?= (int) $obra['id'] ?>"
                            >

                                <div class="cover">

                                    <?php if (!empty($obra['capa'])): ?>

                                        <img
                                            src="<?= h($obra['capa']) ?>"
                                            alt="Capa de <?= h($obra['titulo']) ?>"
                                        >

                                    <?php endif; ?>

                                </div>

                                <h3>
                                    <?= h($obra['titulo']) ?>
                                </h3>

                                <p>
                                    por @<?= h($obra['artista']) ?>
                                </p>

                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty">
                        Ainda não há obras publicadas. Volte em breve para conhecer novas histórias.
                    </div>

                <?php endif; ?>

            </section>

            <section class="section">

                <h2>Últimos avisos e atualizações</h2>

                <?php if ($atualizacoes): ?>

                    <div class="updates">

                        <?php foreach ($atualizacoes as $item): ?>

                            <article class="update">

                                <small>
                                    @<?= h($item['artista']) ?>
                                    ·
                                    <?= h(ucfirst(str_replace('_', ' ', $item['tipo']))) ?>
                                </small>

                                <h3>
                                    <?= h($item['titulo']) ?>
                                </h3>

                                <p>
                                    <?= nl2br(h($item['mensagem'])) ?>
                                </p>

                                <?php if (!empty($item['data_evento'])): ?>

                                    <p>
                                        <strong>Data:</strong>
                                        <?= h(date('d/m/Y', strtotime($item['data_evento']))) ?>
                                    </p>

                                <?php endif; ?>

                                <?php if (!empty($item['plataforma_url'])): ?>

                                    <a
                                        href="<?= h($item['plataforma_url']) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        Onde ler
                                    </a>

                                <?php endif; ?>

                                <?php if (!empty($item['obra_id'])): ?>

                                    <a href="obra.php?id=<?= (int) $item['obra_id'] ?>">
                                        Ver obra
                                    </a>

                                <?php endif; ?>

                                <a href="artista.php?id=<?= (int) $item['artista_id'] ?>">
                                    Ver perfil do artista
                                </a>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty">
                        Ainda não há avisos publicados.
                    </div>

                <?php endif; ?>

            </section>

        </main>

        <footer class="footer">
            Webtoonsz — espaço de divulgação de webtoons e comunicação entre artistas e leitores.
        </footer>

    </div>

</body>

</html>
