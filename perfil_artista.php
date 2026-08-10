<?php
// Webtoonsz — Perfil do artista
$siteName = "Webtoonsz";

$obras = [
    ["titulo" => "Nome da obra", "autor" => "@nome_do_artista"],
    ["titulo" => "A Última Estrela", "autor" => "@nome_do_artista"],
    ["titulo" => "Entre Dois Mundos", "autor" => "@nome_do_artista"],
    ["titulo" => "Fragmentos", "autor" => "@nome_do_artista"]
];

$favoritadas = [
    ["titulo" => "Nome da obra", "autor" => "@nome_Autor"],
    ["titulo" => "Coração de Papel", "autor" => "@nome_Autor"],
    ["titulo" => "Além do Horizonte", "autor" => "@nome_Autor"]
];

$editar = isset($_GET['editar']) && $_GET['editar'] === '1';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Perfil do artista</title>

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
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);

            background:
                radial-gradient(circle at 8% 15%, rgba(73,40,194,.72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191,64,250,.32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91,42,98,.75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);
        }

        .page {
            width: min(920px, 100%);
            margin: auto;
        }

        .profile-card {
            padding: 18px 26px 30px;
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

        .top-button {
            min-width: 76px;
            height: 34px;
            padding: 0 14px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 8px;

            color: var(--white);
            background: var(--black);

            font-size: .77rem;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .top-button:hover {
            background: var(--hyper-magenta);
            transform: translateY(-1px);
        }

        .artist-label {
            position: absolute;
            margin-top: 112px;
            margin-left: 2px;

            color: var(--hyper-magenta);
            font-size: .68rem;
            font-weight: 700;
        }

        /* PERFIL */
        .profile-content {
            padding-top: 22px;
        }

        .profile-main {
            display: grid;
            grid-template-columns: 115px 1fr;
            gap: 25px;
            align-items: center;
        }

        .avatar {
            width: 105px;
            height: 105px;

            display: grid;
            place-items: center;

            border-radius: 50%;
            border: 2px solid rgba(227,217,252,.15);

            background:
                linear-gradient(
                    145deg,
                    rgba(227,217,252,.65),
                    rgba(91,42,98,.7)
                );

            color: var(--ultrasonic-blue);
            font-size: 2rem;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 75px 1fr;
            align-items: center;
            gap: 10px;
        }

        .info-row label {
            font-size: .8rem;
            font-weight: 700;
        }

        .info-value {
            min-height: 31px;
            padding: 7px 11px;

            display: flex;
            align-items: center;

            border-radius: 8px;
            background: rgba(227,217,252,.09);

            color: var(--muted);
            font-size: .76rem;
        }

        /* SEÇÕES */
        .section {
            margin-top: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .section-title {
            font-size: .95rem;
        }

        .section-title span {
            color: var(--hyper-magenta);
        }

        .section-link {
            color: var(--muted);
            font-size: .72rem;
            text-decoration: none;
        }

        .section-link:hover {
            color: var(--hyper-magenta);
        }

        /* OBRAS */
        .works-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 14px;
        }

        .work {
            position: relative;
            min-width: 0;
            text-decoration: none;
            color: var(--text-light);
        }

        .cover {
            position: relative;
            height: 145px;
            overflow: hidden;

            border: 1px solid rgba(227,217,252,.12);
            border-radius: 11px;

            background:
                linear-gradient(
                    145deg,
                    rgba(73,40,194,.75),
                    rgba(191,64,250,.28),
                    rgba(91,42,98,.75)
                );

            transition: .22s ease;
        }

        .cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                transparent 45%,
                rgba(4,6,7,.55)
            );
        }

        .work:hover .cover {
            transform: translateY(-4px);
            border-color: rgba(191,64,250,.5);
            box-shadow: 0 12px 28px rgba(191,64,250,.15);
        }

        .work-name {
            margin-top: 7px;
            font-size: .72rem;
            font-weight: 700;
        }

        .work-author {
            margin-top: 3px;
            color: var(--hyper-magenta);
            font-size: .64rem;
        }

        /* BOTÃO + */
        .add-work {
            height: 145px;
            border: 1px dashed rgba(227,217,252,.28);
            border-radius: 11px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            color: var(--periwinkle);
            background: rgba(227,217,252,.04);

            text-decoration: none;
            transition: .22s ease;
        }

        .add-work .plus {
            width: 48px;
            height: 48px;

            display: grid;
            place-items: center;

            margin-bottom: 7px;

            border-radius: 50%;
            background: rgba(227,217,252,.55);
            color: var(--velvet-purple);

            font-size: 1.8rem;
            font-weight: 300;
        }

        .add-work span:last-child {
            font-size: .65rem;
            font-weight: 700;
        }

        .add-work:hover {
            border-color: var(--hyper-magenta);
            color: var(--hyper-magenta);
            background: rgba(191,64,250,.08);
            transform: translateY(-4px);
        }

        /* FAVORITADAS */
        .favorites {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 14px;
        }

        .favorite .cover {
            height: 125px;
        }

        /* AÇÕES */
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 32px;
        }

        .action-button {
            min-width: 210px;
            height: 38px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 9px;

            color: var(--white);
            background: var(--black);

            font-size: .75rem;
            text-decoration: none;
            transition: .2s ease;
        }

        .action-button:hover {
            background: var(--velvet-purple);
            transform: translateY(-1px);
        }

        .artist-button {
            background: var(--hyper-magenta);
        }

        .artist-button:hover {
            background: #cf5dff;
        }

        @media (max-width: 700px) {
            body {
                padding: 12px;
            }

            .profile-card {
                padding: 15px;
            }

            .profile-main {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .avatar {
                margin: auto;
            }

            .works-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .favorites {
                grid-template-columns: repeat(2, 1fr);
            }

            .actions {
                flex-direction: column;
            }

            .action-button {
                width: 100%;
            }
        }

        @media (max-width: 430px) {
            .info-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .cover,
            .add-work {
                height: 135px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <main class="profile-card">

        <!-- TOPO -->
        <header class="topbar">

            <a href="home.php" class="top-button">
                Voltar
            </a>

            <a href="perfil_artista.php?editar=1" class="top-button">
                Editar perfil
            </a>

        </header>


        <section class="profile-content">

            <!-- DADOS DO ARTISTA -->
            <div class="profile-main">

                <div>
                    <div class="avatar">✦</div>
                    <span class="artist-label">• Artista</span>
                </div>

                <div class="profile-info">

                    <div class="info-row">
                        <label>Nome:</label>
                        <div class="info-value">Nome</div>
                    </div>

                    <div class="info-row">
                        <label>Usuário:</label>
                        <div class="info-value">@nome_do_artista</div>
                    </div>

                    <div class="info-row">
                        <label>Biografia:</label>
                        <div class="info-value">
                            Artista e criador de webtoons.
                        </div>
                    </div>

                </div>

            </div>


            <!-- MINHAS OBRAS -->
            <section class="section">

                <div class="section-header">
                    <h2 class="section-title">
                        Minhas obras
                    </h2>

                    <a href="#" class="section-link">
                        Ver todas
                    </a>
                </div>

                <div class="works-list">

                    <?php foreach ($obras as $obra): ?>

                        <a href="obra.php" class="work">

                            <div class="cover"></div>

                            <p class="work-name">
                                <?= htmlspecialchars($obra['titulo']) ?>
                            </p>

                            <p class="work-author">
                                <?= htmlspecialchars($obra['autor']) ?>
                            </p>

                        </a>

                    <?php endforeach; ?>


                    <!-- ADICIONAR OBRA -->
                    <a href="adicionar_obra.php" class="add-work">

                        <span class="plus">+</span>

                        <span>Adicionar obra</span>

                    </a>

                </div>

            </section>


            <!-- FAVORITADAS -->
            <section class="section">

                <div class="section-header">
                    <h2 class="section-title">
                        Favoritadas
                    </h2>
                </div>

                <div class="favorites">

                    <?php foreach ($favoritadas as $obra): ?>

                        <a href="obra.php" class="work favorite">

                            <div class="cover"></div>

                            <p class="work-name">
                                <?= htmlspecialchars($obra['titulo']) ?>
                            </p>

                            <p class="work-author">
                                <?= htmlspecialchars($obra['autor']) ?>
                            </p>

                        </a>

                    <?php endforeach; ?>

                </div>

            </section>


            <!-- AÇÕES -->
            <div class="actions">

                <a href="adicionar_obra.php" class="action-button artist-button">
                    + Adicionar nova obra
                </a>

                <a href="index.php" class="action-button">
                    Sair
                </a>

            </div>

        </section>

    </main>

</div>

</body>
</html>
