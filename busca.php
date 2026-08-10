<?php
//  busca
$siteName = "Webtoonsz";

$obras = [
    [
        "titulo" => "Nome da obra",
        "autor" => "@nome_artista",
        "descricao" => "Sinopse curta da obra para apresentar o conteúdo ao leitor."
    ],
    [
        "titulo" => "A Última Estrela",
        "autor" => "@luna_art",
        "descricao" => "Uma aventura fantástica em busca de uma estrela perdida."
    ],
    [
        "titulo" => "Entre Dois Mundos",
        "autor" => "@mika_draws",
        "descricao" => "Uma história sobre escolhas, amizade e dois mundos conectados."
    ],
    [
        "titulo" => "Coração de Papel",
        "autor" => "@nina_comics",
        "descricao" => "Romance e cotidiano acompanhando uma nova descoberta."
    ],
    [
        "titulo" => "Além do Horizonte",
        "autor" => "@sky_artist",
        "descricao" => "Uma jornada por terras desconhecidas e novos mistérios."
    ],
    [
        "titulo" => "Fragmentos",
        "autor" => "@yuki_webtoon",
        "descricao" => "Memórias, encontros e acontecimentos que mudam uma vida."
    ]
];

$busca = trim($_GET['busca'] ?? '');

$resultados = $obras;

if ($busca !== '') {
    $resultados = array_filter($obras, function ($obra) use ($busca) {
        return stripos($obra['titulo'], $busca) !== false
            || stripos($obra['autor'], $busca) !== false
            || stripos($obra['descricao'], $busca) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Buscar</title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248, 245, 255, 0.62);
            --border: rgba(227, 217, 252, 0.16);
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
                radial-gradient(circle at 8% 15%, rgba(73, 40, 194, .72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191, 64, 250, .32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91, 42, 98, .75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);

            padding: 24px;
        }

        .page {
            width: min(1050px, 100%);
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

            color: var(--text-light);
            font-size: 1.15rem;
            font-weight: 800;
            text-decoration: none;
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
            padding: 0 14px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            border: 1px solid rgba(227, 217, 252, .16);
            border-radius: 10px;

            color: var(--text-light);
            background: rgba(4, 6, 7, .75);

            font-size: .84rem;
            text-decoration: none;

            transition: .2s ease;
        }

        .header-button:hover {
            border-color: var(--hyper-magenta);
            background: rgba(191, 64, 250, .16);
        }

        .notification-button {
            position: relative;
            width: 40px;
            padding: 0;
        }

        .badge {
            position: absolute;
            top: 4px;
            right: 4px;

            min-width: 15px;
            height: 15px;
            padding: 0 3px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            border: 2px solid #171020;

            background: var(--hyper-magenta);
            color: white;

            font-size: .58rem;
            font-weight: 800;
        }

  
        .content {
            padding: 45px 0 60px;
        }

        .title {
            text-align: center;
            font-size: clamp(1.9rem, 5vw, 2.6rem);
            letter-spacing: -1px;
        }

        .subtitle {
            max-width: 600px;
            margin: 10px auto 28px;

            color: var(--muted);
            text-align: center;
            font-size: .9rem;
            line-height: 1.5;
        }

        
        .search-form {
            width: min(760px, 100%);
            margin: 0 auto 42px;

            display: flex;
            align-items: center;
            gap: 10px;

            padding: 8px;

            border: 1px solid rgba(227, 217, 252, .18);
            border-radius: 15px;

            background: rgba(4, 6, 7, .68);
            box-shadow:
                0 15px 40px rgba(0, 0, 0, .25),
                0 0 25px rgba(73, 40, 194, .08);
        }

        .search-icon {
            padding-left: 12px;
            color: var(--periwinkle);
            font-size: 1.15rem;
        }

        .search-input {
            flex: 1;
            min-width: 0;
            height: 45px;

            border: 0;
            outline: 0;
            background: transparent;

            color: var(--text-light);
            font-size: .95rem;
        }

        .search-input::placeholder {
            color: rgba(248, 245, 255, .42);
        }

        .search-button {
            height: 45px;
            padding: 0 24px;

            border: 0;
            border-radius: 10px;

            color: var(--white);
            background: var(--hyper-magenta);

            font-weight: 700;
            cursor: pointer;

            box-shadow: 0 7px 22px rgba(191, 64, 250, .25);
            transition: .2s ease;
        }

        .search-button:hover {
            background: #cf5dff;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(191, 64, 250, .38);
        }

 
        .results-title {
            margin-bottom: 15px;
            font-size: 1.25rem;
        }

        .results-title span {
            color: var(--hyper-magenta);
        }

        .results {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .result-card {
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 15px;

            min-height: 145px;
            padding: 14px;

            border: 1px solid var(--border);
            border-radius: 16px;

            background: rgba(4, 6, 7, .60);

            color: var(--text-light);
            text-decoration: none;

            transition: .22s ease;
        }

        .result-card:hover {
            transform: translateY(-4px);
            border-color: rgba(191, 64, 250, .45);
            box-shadow: 0 15px 35px rgba(191, 64, 250, .12);
        }

        .cover {
            width: 90px;
            height: 115px;

            border-radius: 11px;

            background:
                linear-gradient(
                    145deg,
                    rgba(73, 40, 194, .75),
                    rgba(191, 64, 250, .30),
                    rgba(91, 42, 98, .75)
                );

            border: 1px solid rgba(227, 217, 252, .12);
        }

        .result-info {
            min-width: 0;
        }

        .result-info h2 {
            margin: 3px 0 6px;
            font-size: .98rem;
        }

        .author {
            color: var(--hyper-magenta);
            font-size: .76rem;
            font-weight: 700;
        }

        .description {
            margin-top: 10px;

            color: var(--muted);
            font-size: .75rem;
            line-height: 1.45;
        }

        .empty {
            padding: 45px 20px;

            border: 1px dashed var(--border);
            border-radius: 16px;

            color: var(--muted);
            text-align: center;
        }

        .empty strong {
            display: block;
            margin-bottom: 7px;
            color: var(--text-light);
            font-size: 1rem;
        }

        @media (max-width: 800px) {
            body {
                padding: 15px;
            }

            .logo span {
                display: none;
            }

            .header-button {
                padding: 0 10px;
                font-size: .75rem;
            }

            .notification-button {
                width: 38px;
            }

            .results {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 560px) {
            .content {
                padding-top: 35px;
            }

            .search-form {
                padding: 7px;
            }

            .search-button {
                padding: 0 16px;
            }

            .results {
                grid-template-columns: 1fr;
            }

            .result-card {
                grid-template-columns: 85px 1fr;
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

        <a href="busca.php" class="header-button">
            🔍 Buscar
        </a>

        <a href="notificacoes.php"
           class="header-button notification-button"
           title="Notificações"
           aria-label="Notificações">
            🔔
            <span class="badge">3</span>
        </a>

        <a href="perfil.php" class="header-button">
            Meu perfil
        </a>

    </header>


    <main class="content">

        <h1 class="title">Buscar webtoons</h1>

        <p class="subtitle">
            Encontre novas histórias, obras e artistas para acompanhar.
        </p>



        <form class="search-form" method="get" action="busca.php">

            <span class="search-icon">⌕</span>

            <input
                class="search-input"
                type="search"
                name="busca"
                value="<?= htmlspecialchars($busca) ?>"
                placeholder="Busque por nome da obra ou artista..."
                autocomplete="off"
            >

            <button class="search-button" type="submit">
                Buscar
            </button>

        </form>


        <?php if ($busca !== ''): ?>

            <h2 class="results-title">
                Resultados para:
                <span><?= htmlspecialchars($busca) ?></span>
            </h2>

        <?php else: ?>

            <h2 class="results-title">
                Obras em destaque
            </h2>

        <?php endif; ?>


        <?php if (count($resultados) > 0): ?>

            <section class="results">

                <?php foreach ($resultados as $obra): ?>

                    <a href="#" class="result-card">

                        <div class="cover"></div>

                        <div class="result-info">

                            <h2>
                                <?= htmlspecialchars($obra['titulo']) ?>
                            </h2>

                            <p class="author">
                                <?= htmlspecialchars($obra['autor']) ?>
                            </p>

                            <p class="description">
                                <?= htmlspecialchars($obra['descricao']) ?>
                            </p>

                        </div>

                    </a>

                <?php endforeach; ?>

            </section>

        <?php else: ?>

            <div class="empty">
                <strong>Nenhuma obra encontrada.</strong>
                Tente buscar por outro nome ou artista.
            </div>

        <?php endif; ?>

    </main>

</div>

</body>
</html>
