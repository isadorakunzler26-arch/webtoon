<?php
// notificações
$siteName = "Webtoonsz";

$notificacoes = [
    [
        "tipo" => "capitulo",
        "titulo" => "Novo capítulo disponível!",
        "texto" => "@nome_artista publicou um novo capítulo de Nome da obra.",
        "tempo" => "Há 10 minutos",
        "nova" => true
    ],
    [
        "tipo" => "aviso",
        "titulo" => "Atualização de uma obra acompanhada",
        "texto" => "@nome_artista publicou um novo aviso sobre a obra.",
        "tempo" => "Há 2 horas",
        "nova" => true
    ],
    [
        "tipo" => "pausa",
        "titulo" => "Aviso de pausa",
        "texto" => "Nome da obra entrará em pausa temporariamente.",
        "tempo" => "Ontem",
        "nova" => true
    ],
    [
        "tipo" => "capitulo",
        "titulo" => "Novo capítulo disponível!",
        "texto" => "O capítulo 12 de Nome da obra já pode ser lido.",
        "tempo" => "Há 3 dias",
        "nova" => false
    ],
    [
        "tipo" => "geral",
        "titulo" => "Novo aviso do artista",
        "texto" => "@nome_artista publicou uma atualização sobre sua obra.",
        "tempo" => "Há 5 dias",
        "nova" => false
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Notificações</title>

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
            width: min(900px, 100%);
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

        .notification-button .badge {
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
            padding: 35px 0 60px;
        }

        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 22px;
        }

        .title-area h1 {
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            letter-spacing: -1px;
        }

        .title-area p {
            margin-top: 7px;
            color: var(--muted);
            font-size: .9rem;
        }

        .mark-read {
            border: 0;
            background: transparent;
            color: var(--periwinkle);
            font-size: .82rem;
            cursor: pointer;
        }

        .mark-read:hover {
            color: var(--hyper-magenta);
        }


        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .notification-card {
            position: relative;

            display: grid;
            grid-template-columns: 52px 1fr auto;
            align-items: center;
            gap: 16px;

            min-height: 88px;
            padding: 16px 20px;

            border: 1px solid var(--border);
            border-radius: 16px;

            background: rgba(4, 6, 7, .60);

            transition: .2s ease;
        }

        .notification-card:hover {
            border-color: rgba(191, 64, 250, .42);
            background: rgba(91, 42, 98, .20);
            transform: translateX(3px);
        }

        .notification-card.unread {
            border-color: rgba(191, 64, 250, .28);
            background:
                linear-gradient(
                    90deg,
                    rgba(191, 64, 250, .10),
                    rgba(4, 6, 7, .62)
                );
        }

        .notification-card.unread::before {
            content: "";
            position: absolute;
            left: 0;
            top: 18px;
            bottom: 18px;
            width: 3px;

            border-radius: 0 5px 5px 0;
            background: var(--hyper-magenta);
            box-shadow: 0 0 12px rgba(191, 64, 250, .55);
        }

        .notification-icon {
            width: 52px;
            height: 52px;

            display: grid;
            place-items: center;

            border-radius: 14px;

            color: var(--periwinkle);
            background: linear-gradient(
                145deg,
                rgba(73, 40, 194, .55),
                rgba(91, 42, 98, .55)
            );

            border: 1px solid rgba(227, 217, 252, .12);

            font-size: 1.25rem;
        }

        .notification-card:nth-child(2) .notification-icon {
            background: rgba(191, 64, 250, .18);
            color: var(--hyper-magenta);
        }

        .notification-card:nth-child(3) .notification-icon {
            background: rgba(91, 42, 98, .45);
        }

        .notification-info h2 {
            margin-bottom: 5px;
            font-size: .96rem;
        }

        .notification-info p {
            color: var(--muted);
            font-size: .83rem;
            line-height: 1.45;
        }

        .time {
            align-self: start;
            padding-top: 3px;

            color: rgba(248, 245, 255, .42);
            font-size: .72rem;
            white-space: nowrap;
        }

        .empty {
            display: none;
            padding: 50px 20px;
            text-align: center;

            border: 1px dashed var(--border);
            border-radius: 16px;
            color: var(--muted);
        }

        .empty-icon {
            display: block;
            margin-bottom: 12px;
            color: var(--periwinkle);
            font-size: 2rem;
        }

        @media (max-width: 650px) {
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

            .title-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .notification-card {
                grid-template-columns: 45px 1fr;
                gap: 12px;
                padding: 14px;
            }

            .notification-icon {
                width: 45px;
                height: 45px;
            }

            .time {
                grid-column: 2;
                padding-top: 0;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <!-- HEADER -->
    <header class="header">

        <a href="home.php" class="logo">
            <span class="logo-icon">✦</span>
            <span><?= $siteName ?></span>
        </a>

        <a href="home.php" class="header-button">
            🔍 Buscar
        </a>

        <a href="notificacoes.php" class="header-button notification-button"
           title="Notificações" aria-label="Notificações">
            🔔
            <span class="badge">3</span>
        </a>

        <a href="perfil.php" class="header-button">
            Meu perfil
        </a>

    </header>


    <!-- CONTEÚDO -->
    <main class="content">

        <div class="title-row">

            <div class="title-area">
                <h1>Notificações</h1>
                <p>Veja as novidades das obras que você acompanha.</p>
            </div>

            <button class="mark-read" type="button" onclick="marcarComoLidas()">
                Marcar todas como lidas
            </button>

        </div>


        <section class="notification-list" id="notificationList">

            <?php foreach ($notificacoes as $notificacao): ?>

                <article class="notification-card <?= $notificacao['nova'] ? 'unread' : '' ?>">

                    <div class="notification-icon">
                        <?php
                            if ($notificacao['tipo'] === 'capitulo') {
                                echo '📖';
                            } elseif ($notificacao['tipo'] === 'aviso') {
                                echo '📢';
                            } elseif ($notificacao['tipo'] === 'pausa') {
                                echo '⏸';
                            } else {
                                echo '✦';
                            }
                        ?>
                    </div>

                    <div class="notification-info">
                        <h2><?= htmlspecialchars($notificacao['titulo']) ?></h2>
                        <p><?= htmlspecialchars($notificacao['texto']) ?></p>
                    </div>

                    <span class="time">
                        <?= htmlspecialchars($notificacao['tempo']) ?>
                    </span>

                </article>

            <?php endforeach; ?>

        </section>


        <div class="empty" id="emptyMessage">
            <span class="empty-icon">🔔</span>
            <p>Você não possui novas notificações.</p>
        </div>

    </main>

</div>


<script>
    function marcarComoLidas() {
        const notificacoes = document.querySelectorAll('.notification-card.unread');

        notificacoes.forEach(function (notificacao) {
            notificacao.classList.remove('unread');
        });

        const badge = document.querySelector('.badge');

        if (badge) {
            badge.style.display = 'none';
        }
    }
</script>

</body>
</html>
