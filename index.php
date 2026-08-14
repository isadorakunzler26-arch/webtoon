<?php
// inicial 


$siteName = "Webtoonsz";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Início</title>

    <style>
        
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;

            --white: #FFFFFF;
            --text-dark: #160D1B;
            --text-light: #F8F5FF;
            --border: rgba(227, 217, 252, 0.18);
        }

       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);

           
            background:
                radial-gradient(circle at 12% 18%, rgba(73, 40, 194, 0.75), transparent 30%),
                radial-gradient(circle at 82% 20%, rgba(191, 64, 250, 0.35), transparent 28%),
                radial-gradient(circle at 55% 90%, rgba(91, 42, 98, 0.8), transparent 35%),
                linear-gradient(135deg, #12091A 0%, #24102F 45%, #08090C 100%);

            overflow-x: hidden;
        }

        
        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.25;
            pointer-events: none;
            z-index: -1;
        }

        body::before {
            background: var(--hyper-magenta);
            top: 35%;
            left: -180px;
        }

        body::after {
            background: var(--ultrasonic-blue);
            right: -160px;
            bottom: 5%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

       
        .page {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
            padding: 24px 0 60px;
        }

       
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;

            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;

            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;

            display: grid;
            place-items: center;

            color: var(--black);
            background: var(--periwinkle);

            box-shadow:
                0 0 20px rgba(227, 217, 252, 0.28),
                inset 0 0 0 1px rgba(255,255,255,0.3);
        }

        .logo-icon::before {
            content: "✦";
            font-size: 1.45rem;
            color: var(--ultrasonic-blue);
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;

            min-width: 130px;
            padding: 13px 24px;

            border: 1px solid transparent;
            border-radius: 12px;

            font-size: 0.98rem;
            font-weight: 700;

            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(227, 217, 252, 0.22);
        }

        .btn-register {
            background: var(--hyper-magenta);
            color: var(--white);

            box-shadow: 0 8px 25px rgba(191, 64, 250, 0.25);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-login:hover {
            background: rgba(227, 217, 252, 0.16);
            box-shadow: 0 8px 25px rgba(227, 217, 252, 0.12);
        }

        .btn-register:hover {
            background: #cf5dff;
            box-shadow: 0 10px 30px rgba(191, 64, 250, 0.42);
        }

        
        .hero {
            text-align: center;
            padding: 75px 20px 55px;
        }

        .hero-badge {
            display: inline-block;
            margin-bottom: 18px;
            padding: 7px 14px;

            border: 1px solid rgba(191, 64, 250, 0.4);
            border-radius: 999px;

            color: var(--periwinkle);
            background: rgba(91, 42, 98, 0.25);

            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 850px;
            margin: 0 auto 18px;

            font-size: clamp(2.3rem, 6vw, 4.6rem);
            line-height: 1.02;
            letter-spacing: -2.5px;
        }

        .hero h1 span {
            color: var(--hyper-magenta);
            text-shadow: 0 0 30px rgba(191, 64, 250, 0.3);
        }

        .hero p {
            max-width: 680px;
            margin: 0 auto;

            color: rgba(248, 245, 255, 0.76);
            font-size: 1.08rem;
            line-height: 1.7;
        }

        
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .info-card {
            position: relative;
            min-height: 300px;
            padding: 30px;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;

            overflow: hidden;

            border: 1px solid rgba(227, 217, 252, 0.15);
            border-radius: 22px;

            background: linear-gradient(
                145deg,
                rgba(227, 217, 252, 0.10),
                rgba(91, 42, 98, 0.25)
            );

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255,255,255,0.06);

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .info-card::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            top: -80px;
            right: -60px;

            border-radius: 50%;
            background: var(--ultrasonic-blue);
            filter: blur(45px);
            opacity: 0.45;
        }

        .info-card:nth-child(2)::before {
            background: var(--hyper-magenta);
        }

        .info-card:nth-child(3)::before {
            background: var(--periwinkle);
        }

        .info-card:hover {
            transform: translateY(-7px);
            border-color: rgba(191, 64, 250, 0.55);
            box-shadow:
                0 25px 55px rgba(0, 0, 0, 0.35),
                0 0 35px rgba(191, 64, 250, 0.12);
        }

        .card-number {
            position: absolute;
            top: 22px;
            left: 25px;

            color: rgba(227, 217, 252, 0.4);
            font-size: 0.85rem;
            font-weight: 800;
        }

        .card-icon {
            position: absolute;
            top: 20px;
            right: 22px;

            width: 48px;
            height: 48px;

            display: grid;
            place-items: center;

            border-radius: 14px;

            background: rgba(227, 217, 252, 0.10);
            border: 1px solid rgba(227, 217, 252, 0.15);

            font-size: 1.35rem;
        }

        .info-card h2 {
            position: relative;
            margin-bottom: 12px;

            font-size: 1.7rem;
        }

        .info-card p {
            position: relative;

            color: rgba(248, 245, 255, 0.72);
            line-height: 1.6;
            font-size: 0.98rem;
        }

        .card-link {
            position: relative;
            display: inline-block;
            margin-top: 22px;

            color: var(--periwinkle);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .card-link::after {
            content: " →";
            color: var(--hyper-magenta);
        }

        
        .footer {
            margin-top: 55px;
            padding-top: 25px;

            border-top: 1px solid var(--border);

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;

            color: rgba(248, 245, 255, 0.48);
            font-size: 0.85rem;
        }

        .footer strong {
            color: var(--hyper-magenta);
        }

        
        @media (max-width: 800px) {
            .page {
                width: min(100% - 24px, 650px);
                padding-top: 12px;
            }

            .header {
                padding: 14px 8px 18px;
            }

            .logo span {
                display: none;
            }

            .btn {
                min-width: auto;
                padding: 11px 16px;
                font-size: 0.9rem;
            }

            .hero {
                padding: 55px 8px 40px;
            }

            .hero h1 {
                letter-spacing: -1.5px;
            }

            .hero p {
                font-size: 1rem;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .info-card {
                min-height: 250px;
            }

            .footer {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 430px) {
            .header {
                gap: 12px;
            }

            .nav {
                gap: 7px;
            }

            .btn {
                padding: 10px 12px;
            }

            .hero h1 {
                font-size: 2.25rem;
            }

            .info-card {
                min-height: 235px;
                padding: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="page">

       
        <header class="header">

            <a href="index.php" class="logo" aria-label="Página inicial do Webtoonsz">
                <span class="logo-icon"></span>
                <span><?= $siteName ?></span>
            </a>

            <nav class="nav">
                <a href="login.php" class="btn btn-login">Entrar</a>
                <a href="cadastro.php" class="btn btn-register">Cadastrar-se</a>
            </nav>

        </header>


        
        <main>

            <section class="hero">

                <div class="hero-badge">Seu espaço para webtoons</div>

                <h1>
                    Descubra histórias.<br>
                    <span>Acompanhe artistas.</span>
                </h1>

                <p>
                    O Webtoonsz é um espaço dedicado à divulgação de webtoons,
                    onde leitores podem descobrir novas obras e artistas podem
                    compartilhar suas histórias e atualizações.
                </p>

            </section>


          
            <section class="cards" aria-label="Informações sobre o site">

                <article class="info-card">
                    <span class="card-number">01</span>
                    <span class="card-icon">✦</span>

                    <h2>Sobre o site</h2>

                    <p>
                        Conheça a proposta do Webtoonsz e descubra como a
                        plataforma facilita a divulgação e o acompanhamento
                        de webtoons.
                    </p>

                    <a href="#" class="card-link">Saiba mais</a>
                </article>


                <article class="info-card">
                    <span class="card-number">02</span>
                    <span class="card-icon">◈</span>

                    <h2>Leitor</h2>

                    <p>
                        Explore obras, acompanhe seus webtoons favoritos e
                        receba informações sobre novos capítulos e atualizações
                        dos artistas.
                    </p>

                    <a href="cadastro.php" class="card-link">Quero ser leitor</a>
                </article>


                <article class="info-card">
                    <span class="card-number">03</span>
                    <span class="card-icon">✎</span>

                    <h2>Artista</h2>

                    <p>
                        Divulgue suas obras, publique avisos sobre capítulos,
                        pausas, plataformas de leitura e outras novidades.
                    </p>

                    <a href="cadastro.php" class="card-link">Quero ser artista</a>
                </article>

            </section>

        </main>


 
        <footer class="footer">
            <span>© 2026 <?= $siteName ?> — Projeto Integrador</span>
            <span>Feito para <strong>artistas e leitores</strong></span>
        </footer>

    </div>

</body>
</html>
