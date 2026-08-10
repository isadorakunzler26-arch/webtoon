<?php
// Webtoonsz — Detalhes da obra e últimas atualizações
$siteName = "Webtoonsz";

$pagina = $_GET['pagina'] ?? 'obra';

if (!in_array($pagina, ['obra', 'atualizacoes'], true)) {
    $pagina = 'obra';
}

$obra = [
    'titulo' => 'Nome da obra',
    'autor' => '@nome_Autor',
    'sinopse' => 'Uma história envolvente que acompanha seus personagens em uma jornada cheia de descobertas, conflitos e momentos inesperados. Acompanhe a obra e fique por dentro das próximas atualizações.',
];

$atualizacoes = [
    [
        'autor' => '@nome_Autor',
        'tipo' => 'Aviso',
        'texto' => 'Nessa semana os capítulos novos serão postados na segunda e quarta-feira.',
        'tempo' => 'Há 2 dias'
    ],
    [
        'autor' => '@nome_Autor',
        'tipo' => 'Atualização',
        'texto' => 'Texto texto texto. Confira as novidades e acompanhe a obra para não perder os próximos capítulos.',
        'tempo' => 'Há 5 dias'
    ],
    [
        'autor' => '@nome_Autor',
        'tipo' => 'Aviso',
        'texto' => 'Uma nova atualização da obra foi publicada pelo artista.',
        'tempo' => 'Há 1 semana'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — <?= htmlspecialchars($obra['titulo']) ?></title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248,245,255,.65);
            --border: rgba(227,217,252,.16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);
            padding: 25px;

            background:
                radial-gradient(circle at 8% 15%, rgba(73,40,194,.72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191,64,250,.32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91,42,98,.75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);
        }

        .page {
            width: min(1000px, 100%);
            margin: auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 13px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 28px;
        }

        .back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 75px;
            height: 35px;
            padding: 0 14px;
            border-radius: 8px;
            background: var(--black);
            color: var(--white);
            text-decoration: none;
            font-size: .78rem;
            transition: .2s ease;
        }

        .back:hover {
            background: var(--hyper-magenta);
            transform: translateY(-1px);
        }

        .breadcrumb {
            color: var(--muted);
            font-size: .75rem;
        }

        .obra-card {
            display: grid;
            grid-template-columns: 285px 1fr;
            gap: 30px;
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: rgba(4,6,7,.62);
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
        }

        .cover-area {
            min-width: 0;
        }

        .cover {
            width: 100%;
            height: 390px;
            border-radius: 15px;
            border: 1px solid rgba(227,217,252,.15);

            background:
                radial-gradient(circle at 35% 25%, rgba(191,64,250,.55), transparent 25%),
                linear-gradient(145deg, #4928C2, #5B2A62 55%, #040607);

            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(227,217,252,.72);
            font-size: 3rem;

            box-shadow: 0 15px 35px rgba(0,0,0,.25);
        }

        .obra-title {
            margin-top: 10px;
            font-size: 1.25rem;
        }

        .author {
            margin-top: 4px;
            color: var(--hyper-magenta);
            font-size: .76rem;
            font-weight: 700;
        }

        .save {
            float: right;
            margin-top: -20px;
            border: 0;
            background: transparent;
            color: var(--periwinkle);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .save:hover {
            color: var(--hyper-magenta);
        }

        .details {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .label {
            margin-bottom: 8px;
            color: var(--periwinkle);
            font-size: .8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .synopsis {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .updates-button {
            margin-top: auto;
            width: 100%;
            min-height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            background: var(--hyper-magenta);
            color: var(--white);
            font-size: .85rem;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .updates-button:hover {
            background: #cf5dff;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(191,64,250,.28);
        }

        /* ATUALIZAÇÕES */
        .updates-page {
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: rgba(4,6,7,.62);
        }

        .updates-header {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 22px;
        }

        .updates-header h1 {
            font-size: 1.5rem;
        }

        .updates-header p {
            margin-top: 5px;
            color: var(--muted);
            font-size: .78rem;
        }

        .updates-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .update {
            display: grid;
            grid-template-columns: 55px 1fr auto;
            gap: 15px;
            padding: 17px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: rgba(227,217,252,.06);
            transition: .2s ease;
        }

        .update:hover {
            border-color: rgba(191,64,250,.4);
            background: rgba(91,42,98,.18);
        }

        .update-icon {
            width: 50px;
            height: 50px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: rgba(73,40,194,.35);
            color: var(--periwinkle);
            font-size: 1.15rem;
        }

        .update:nth-child(2) .update-icon {
            background: rgba(191,64,250,.16);
            color: var(--hyper-magenta);
        }

        .update-info h2 {
            font-size: .88rem;
            margin-bottom: 4px;
        }

        .update-info .type {
            color: var(--hyper-magenta);
            font-size: .68rem;
            font-weight: 700;
        }

        .update-info p {
            margin-top: 7px;
            color: var(--muted);
            font-size: .78rem;
            line-height: 1.5;
        }

        .update-time {
            color: rgba(248,245,255,.4);
            font-size: .68rem;
            white-space: nowrap;
        }

        @media (max-width: 700px) {
            body {
                padding: 14px;
            }

            .obra-card {
                grid-template-columns: 1fr;
                padding: 18px;
            }

            .cover {
                height: 330px;
            }

            .updates-page {
                padding: 18px;
            }

            .update {
                grid-template-columns: 45px 1fr;
            }

            .update-icon {
                width: 43px;
                height: 43px;
            }

            .update-time {
                grid-column: 2;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <header class="topbar">
        <a href="home.php" class="back">Voltar</a>
        <span class="breadcrumb"><?= $siteName ?> / <?= htmlspecialchars($obra['titulo']) ?></span>
    </header>

    <?php if ($pagina === 'obra'): ?>

        <!-- DETALHES DA OBRA -->
        <main class="obra-card">

            <div class="cover-area">
                <div class="cover">✦</div>

                <button class="save" type="button" title="Salvar obra" onclick="salvarObra()">
                    ♡
                </button>

                <h1 class="obra-title">
                    <?= htmlspecialchars($obra['titulo']) ?>
                </h1>

                <p class="author">
                    <?= htmlspecialchars($obra['autor']) ?>
                </p>
            </div>

            <div class="details">

                <p class="label">Sinopse</p>

                <p class="synopsis">
                    <?= htmlspecialchars($obra['sinopse']) ?>
                </p>

                <a href="obra.php?pagina=atualizacoes" class="updates-button">
                    Últimas atualizações
                </a>

            </div>

        </main>

    <?php else: ?>

        <!-- ÚLTIMAS ATUALIZAÇÕES -->
        <main class="updates-page">

            <div class="updates-header">

                <div>
                    <h1>Últimas atualizações</h1>
                    <p>
                        Avisos e novidades publicados por
                        <?= htmlspecialchars($obra['autor']) ?>.
                    </p>
                </div>

                <a href="obra.php" class="back">Voltar</a>

            </div>

            <section class="updates-list">

                <?php foreach ($atualizacoes as $atualizacao): ?>

                    <article class="update">

                        <div class="update-icon">
                            <?= $atualizacao['tipo'] === 'Aviso' ? '📢' : '✦' ?>
                        </div>

                        <div class="update-info">
                            <h2><?= htmlspecialchars($atualizacao['autor']) ?></h2>
                            <span class="type">
                                <?= htmlspecialchars($atualizacao['tipo']) ?>
                            </span>
                            <p>
                                <?= htmlspecialchars($atualizacao['texto']) ?>
                            </p>
                        </div>

                        <span class="update-time">
                            <?= htmlspecialchars($atualizacao['tempo']) ?>
                        </span>

                    </article>

                <?php endforeach; ?>

            </section>

        </main>

    <?php endif; ?>

</div>

<script>
    function salvarObra() {
        const botao = document.querySelector('.save');

        if (botao) {
            botao.textContent = '♥';
            botao.style.color = '#BF40FA';
        }
    }
</script>

</body>
</html>
