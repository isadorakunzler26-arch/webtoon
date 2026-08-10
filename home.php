<?php
//  principal do usuário logado
$siteName = "Webtoonsz";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Explorar</title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248, 245, 255, 0.65);
            --border: rgba(227, 217, 252, 0.18);
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

            background:
                radial-gradient(circle at 10% 15%, rgba(73, 40, 194, .72), transparent 30%),
                radial-gradient(circle at 90% 25%, rgba(191, 64, 250, .32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91, 42, 98, .75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);

            padding: 24px;
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
            display: flex;
            align-items: center;
            gap: 9px;
            margin-right: auto;

            font-size: 1.15rem;
            font-weight: 800;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: var(--periwinkle);
            color: var(--ultrasonic-blue);
        }

        .header-button {
            height: 38px;
            padding: 0 15px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            border: 1px solid rgba(227, 217, 252, .16);
            border-radius: 10px;

            color: var(--text-light);
            background: rgba(4, 6, 7, .75);

            font-size: .86rem;
            text-decoration: none;

            transition: .2s ease;
        }

        .header-button:hover {
            border-color: var(--hyper-magenta);
            background: rgba(191, 64, 250, .16);
            transform: translateY(-1px);
        }

        .notification {
            position: relative;
            width: 40px;
            height: 38px;
            padding: 0;
        }

        .bell {
            font-size: 1.15rem;
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;

            min-width: 16px;
            height: 16px;
            padding: 0 4px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: var(--hyper-magenta);
            color: white;

            font-size: .62rem;
            font-weight: 800;

            border: 2px solid #171020;
        }

        section {
            margin-top: 25px;
        }

        .section-title {
            margin-bottom: 10px;
            font-size: 1.35rem;
        }

        .section-subtitle {
            margin-top: -4px;
            margin-bottom: 17px;
            color: var(--muted);
            font-size: .85rem;
        }

      
        .cards {
            display: grid;
            grid-template-columns: repeat(6, minmax(130px, 1fr));
            gap: 12px;

            overflow-x: auto;
            padding-bottom: 5px;
        }

        .card {
            min-width: 130px;
            text-decoration: none;
            color: var(--text-light);
        }

        .cover {
            height: 180px;
            border-radius: 13px;

            background:
                linear-gradient(145deg, rgba(227,217,252,.22), rgba(91,42,98,.42));

            border: 1px solid rgba(227,217,252,.12);

            position: relative;
            overflow: hidden;

            transition: .25s ease;
        }

        .cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                transparent 55%,
                rgba(4,6,7,.72)
            );
        }

        .card:hover .cover {
            transform: translateY(-5px);
            border-color: rgba(191,64,250,.55);
            box-shadow: 0 12px 30px rgba(191,64,250,.15);
        }

        .card-name {
            margin-top: 7px;
            font-size: .78rem;
            font-weight: 700;
        }

        .artist {
            margin-top: 3px;
            color: var(--muted);
            font-size: .68rem;
        }

   
        .news-cover {
            height: 170px;
        }

        .news-info {
            margin-top: 7px;
        }

        .news-title {
            font-size: .78rem;
            font-weight: 700;
        }

        .news-text {
            margin-top: 3px;
            color: var(--muted);
            font-size: .67rem;
        }

        @media (max-width: 800px) {
            body {
                padding: 15px;
            }

            .logo span {
                display: none;
            }

            .header-button {
                padding: 0 11px;
            }

            .cards {
                grid-template-columns: repeat(6, 125px);
            }

            .cover {
                height: 165px;
            }
        }

        @media (max-width: 480px) {
            .header {
                gap: 7px;
            }

            .header-button {
                font-size: .76rem;
                padding: 0 9px;
            }

            .notification {
                width: 38px;
            }

            .cards {
                grid-template-columns: repeat(6, 110px);
            }

            .cover {
                height: 150px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <header class="header">

        <a href="home.php" class="logo">
            <span class="logo-icon">✦</span>
            <span><?= $siteName ?></span>
        </a>

        <a href="#" class="header-button">
            🔍 Buscar
        </a>

 
        <a href="notificacoes.php" class="header-button notification" title="Notificações" aria-label="Notificações">
            <span class="bell">🔔</span>
            <span class="notification-badge">3</span>
        </a>

        <a href="perfil.php" class="header-button">
            Meu perfil
        </a>

    </header>



    <section>

        <h1 class="section-title">Explorar</h1>

        <div class="cards">

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

            <a href="#" class="card">
                <div class="cover"></div>
                <p class="card-name">Nome da obra</p>
                <p class="artist">@nome_artista</p>
            </a>

        </div>

    </section>



    <section>

        <h2 class="section-title">Novidades</h2>
        <p class="section-subtitle">
            Atualizações recentes das obras que você acompanha
        </p>

        <div class="cards">

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Novo capítulo</p>
                    <p class="news-text">@nome_artista publicou uma atualização.</p>
                </div>
            </a>

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Aviso do artista</p>
                    <p class="news-text">Novo capítulo nesta quarta-feira.</p>
                </div>
            </a>

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Nova atualização</p>
                    <p class="news-text">@nome_artista publicou um aviso.</p>
                </div>
            </a>

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Novo capítulo</p>
                    <p class="news-text">Capítulo 12 já está disponível.</p>
                </div>
            </a>

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Aviso</p>
                    <p class="news-text">A obra entrará em pausa.</p>
                </div>
            </a>

            <a href="#" class="card">
                <div class="cover news-cover"></div>
                <div class="news-info">
                    <p class="news-title">Atualização</p>
                    <p class="news-text">Confira as novidades da obra.</p>
                </div>
            </a>

        </div>

    </section>

</div>

</body>
</html>
