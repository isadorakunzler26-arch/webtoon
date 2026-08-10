<?php
// Webtoonsz — Área da obra do artista
// Front-end navegável, sem back-end nesta etapa.

$modo = $_GET['modo'] ?? 'obra';

if (!in_array($modo, ['obra', 'editar'], true)) {
    $modo = 'obra';
}

$obra = [
    'titulo' => 'Nome da obra',
    'sinopse' => 'Esta é uma sinopse de exemplo para representar a obra do artista. Aqui poderá ser apresentada a descrição da história, seus personagens, o universo da obra e outras informações importantes para os leitores.'
];

$atualizacoes = [
    [
        'autor' => '@nome_do_artista',
        'texto' => 'Nessa semana os capítulos novos serão postados na segunda e quarta-feira.'
    ],
    [
        'autor' => '@nome_do_artista',
        'texto' => 'Texto de exemplo para uma nova atualização da obra.'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webtoonsz — <?= htmlspecialchars($obra['titulo']) ?></title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248,245,255,.62);
            --border: rgba(227,217,252,.16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            padding: 25px;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);

            background:
                radial-gradient(circle at 8% 15%, rgba(73,40,194,.72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191,64,250,.32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91,42,98,.75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);
        }

        .page {
            width: min(950px, 100%);
            margin: auto;
        }

        .work-card {
            padding: 18px 25px 28px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: rgba(4,6,7,.64);
            box-shadow: 0 25px 60px rgba(0,0,0,.28);
        }

        /* TOPO */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .button {
            min-width: 75px;
            height: 35px;
            padding: 0 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 8px;

            color: var(--white);
            background: var(--black);

            font-size: .78rem;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .button:hover {
            background: var(--velvet-purple);
            transform: translateY(-1px);
        }

        .primary {
            background: var(--hyper-magenta);
        }

        .primary:hover {
            background: #cf5dff;
        }

        /* OBRA */
        .work-content {
            display: grid;
            grid-template-columns: 270px 1fr;
            gap: 28px;
            padding-top: 24px;
        }

        .cover-area {
            min-width: 0;
        }

        .cover {
            width: 100%;
            height: 360px;

            display: grid;
            place-items: center;

            border-radius: 14px;
            border: 1px solid rgba(227,217,252,.14);

            background:
                radial-gradient(circle at 35% 25%, rgba(191,64,250,.55), transparent 25%),
                linear-gradient(145deg, #4928C2, #5B2A62 55%, #040607);

            color: rgba(227,217,252,.75);
            font-size: 3.5rem;
        }

        .work-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .work-title h1 {
            font-size: 1.25rem;
        }

        .edit-icon {
            color: var(--hyper-magenta);
            font-size: .9rem;
        }

        .author {
            margin-top: 3px;
            color: var(--hyper-magenta);
            font-size: .7rem;
            font-weight: 700;
        }

        .details {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .label {
            margin-bottom: 8px;
            color: var(--periwinkle);
            font-size: .78rem;
            font-weight: 800;
        }

        .synopsis {
            color: var(--muted);
            font-size: .82rem;
            line-height: 1.55;
        }

        .updates-button {
            width: 100%;
            min-height: 42px;
            margin-top: auto;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 9px;

            background: var(--black);
            color: var(--white);

            font-size: .78rem;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .updates-button:hover {
            background: var(--hyper-magenta);
        }

        /* EDIÇÃO */
        .edit-content {
            display: grid;
            grid-template-columns: 270px 1fr;
            gap: 28px;
            padding-top: 24px;
        }

        .edit-cover {
            position: relative;
            height: 360px;
            display: grid;
            place-items: center;

            border-radius: 14px;
            background: rgba(227,217,252,.25);
            border: 1px solid rgba(227,217,252,.14);

            color: #777;
            font-size: 3.5rem;
        }

        .edit-cover::after {
            content: "Alterar capa";
            position: absolute;
            bottom: 15px;
            padding: 7px 12px;

            border-radius: 7px;
            background: rgba(4,6,7,.75);
            color: var(--white);

            font-size: .68rem;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 17px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .field label {
            color: var(--periwinkle);
            font-size: .78rem;
            font-weight: 700;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 10px 12px;

            border: 1px solid rgba(227,217,252,.14);
            border-radius: 9px;
            outline: none;

            background: rgba(227,217,252,.09);
            color: var(--text-light);

            font-family: inherit;
            font-size: .8rem;
        }

        .field textarea {
            min-height: 180px;
            resize: vertical;
            line-height: 1.5;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: var(--hyper-magenta);
            box-shadow: 0 0 0 3px rgba(191,64,250,.10);
        }

        .save {
            margin-top: auto;
            width: 100%;
        }

        /* ATUALIZAÇÕES */
        .updates {
            margin-top: 25px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
        }

        .updates h2 {
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .update-list {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .update {
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(227,217,252,.08);
            border: 1px solid rgba(227,217,252,.10);
        }

        .update-author {
            color: var(--hyper-magenta);
            font-size: .68rem;
            font-weight: 700;
        }

        .update p {
            margin-top: 5px;
            color: var(--muted);
            font-size: .75rem;
            line-height: 1.45;
        }

        .add-update {
            margin-top: 10px;
        }

        @media (max-width: 700px) {
            body {
                padding: 13px;
            }

            .work-card {
                padding: 15px;
            }

            .work-content,
            .edit-content {
                grid-template-columns: 1fr;
            }

            .cover,
            .edit-cover {
                height: 330px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <main class="work-card">

        <header class="topbar">

            <a href="perfil_artista.php" class="button">
                Voltar
            </a>

            <?php if ($modo === 'obra'): ?>

                <a href="obra_artista.php?modo=editar" class="button">
                    Editar obra
                </a>

            <?php else: ?>

                <a href="obra_artista.php" class="button primary">
                    Salvar
                </a>

            <?php endif; ?>

        </header>


        <?php if ($modo === 'obra'): ?>

            <!-- PARTE DA OBRA — VISUALIZAÇÃO DO ARTISTA -->

            <section class="work-content">

                <div class="cover-area">

                    <div class="cover">✦</div>

                    <div class="work-title">
                        <h1><?= htmlspecialchars($obra['titulo']) ?></h1>
                        <span class="edit-icon">✎</span>
                    </div>

                    <p class="author">@nome_do_artista</p>

                </div>


                <div class="details">

                    <p class="label">Sinopse</p>

                    <p class="synopsis">
                        <?= htmlspecialchars($obra['sinopse']) ?>
                    </p>

                    <a href="#atualizacoes" class="updates-button">
                        Últimas atualizações
                    </a>

                </div>

            </section>


            <section class="updates" id="atualizacoes">

                <h2>Últimas atualizações</h2>

                <div class="update-list">

                    <?php foreach ($atualizacoes as $atualizacao): ?>

                        <article class="update">

                            <span class="update-author">
                                <?= htmlspecialchars($atualizacao['autor']) ?>
                            </span>

                            <p>
                                <?= htmlspecialchars($atualizacao['texto']) ?>
                            </p>

                        </article>

                    <?php endforeach; ?>

                </div>

                <a href="#adicionar" class="updates-button add-update">
                    Adicionar atualizações
                </a>

            </section>


        <?php else: ?>

            <!-- PARTE DA OBRA — EDIÇÃO -->

            <section class="edit-content">

                <div class="edit-cover">
                    ✎
                </div>


                <form class="edit-form" action="obra_artista.php" method="get">

                    <input type="hidden" name="modo" value="obra">

                    <div class="field">

                        <label for="titulo">
                            Nome da obra
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            value="<?= htmlspecialchars($obra['titulo']) ?>"
                        >

                    </div>


                    <div class="field">

                        <label for="sinopse">
                            Sinopse
                        </label>

                        <textarea
                            id="sinopse"
                            name="sinopse"
                        ><?= htmlspecialchars($obra['sinopse']) ?></textarea>

                    </div>


                    <button type="submit" class="button primary save">
                        Salvar
                    </button>

                </form>

            </section>

        <?php endif; ?>

    </main>

</div>

</body>
</html>
