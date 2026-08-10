<?php
// cadastro
$siteName = "Webtoonsz";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Cadastrar-se</title>

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
                radial-gradient(circle at 10% 15%, rgba(73, 40, 194, 0.75), transparent 30%),
                radial-gradient(circle at 90% 25%, rgba(191, 64, 250, 0.35), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91, 42, 98, 0.75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);

            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 18px;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.28;
            pointer-events: none;
            z-index: -1;
        }

        body::before {
            background: var(--hyper-magenta);
            left: -160px;
            bottom: 10%;
        }

        body::after {
            background: var(--ultrasonic-blue);
            right: -150px;
            top: 10%;
        }

        .register-container {
            width: min(100%, 480px);
        }

        .register-card {
            position: relative;
            padding: 42px 45px 38px;

            border: 1px solid var(--border);
            border-radius: 24px;

            background: rgba(4, 6, 7, 0.72);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.42),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .logo {
            width: 52px;
            height: 52px;
            margin: 0 auto 18px;

            display: grid;
            place-items: center;

            border-radius: 15px;
            background: var(--periwinkle);

            color: var(--ultrasonic-blue);
            font-size: 1.65rem;

            box-shadow: 0 0 25px rgba(227, 217, 252, 0.18);
        }

        .title {
            text-align: center;
            margin-bottom: 8px;

            font-size: clamp(1.8rem, 5vw, 2.2rem);
            letter-spacing: -0.8px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 30px;

            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 17px;
        }

        .field {
            display: grid;
            grid-template-columns: 95px 1fr;
            align-items: center;
            gap: 12px;
        }

        .field label {
            font-size: 0.9rem;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            height: 43px;
            padding: 0 15px;

            border: 1px solid rgba(227, 217, 252, 0.12);
            border-radius: 10px;

            outline: none;

            color: var(--text-light);
            background: rgba(227, 217, 252, 0.10);

            font-size: 0.88rem;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .field input::placeholder {
            color: rgba(248, 245, 255, 0.42);
        }

        .field input:focus {
            border-color: var(--hyper-magenta);
            background: rgba(227, 217, 252, 0.14);

            box-shadow: 0 0 0 3px rgba(191, 64, 250, 0.12);
        }

        .submit {
            width: 100%;
            height: 46px;
            margin-top: 12px;

            border: 0;
            border-radius: 11px;

            color: var(--white);
            background: var(--hyper-magenta);

            font-size: 0.96rem;
            font-weight: 700;

            cursor: pointer;

            box-shadow: 0 8px 25px rgba(191, 64, 250, 0.25);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .submit:hover {
            transform: translateY(-2px);
            background: #cf5dff;
            box-shadow: 0 12px 32px rgba(191, 64, 250, 0.4);
        }

        .back {
            display: block;
            width: fit-content;
            margin: 23px auto 0;

            color: var(--periwinkle);
            font-size: 0.85rem;
            text-decoration: none;
        }

        .back:hover {
            color: var(--hyper-magenta);
        }

        @media (max-width: 500px) {
            .register-card {
                padding: 34px 24px 30px;
            }

            .field {
                grid-template-columns: 1fr;
                gap: 7px;
            }

            .field label {
                font-size: 0.85rem;
            }

            .field input {
                height: 45px;
            }

            .form {
                gap: 14px;
            }
        }
    </style>
</head>

<body>

    <main class="register-container">

        <section class="register-card">

            <div class="logo">✦</div>

            <h1 class="title">Cadastrar-se</h1>

            <p class="subtitle">
                Crie sua conta para acompanhar seus webtoons favoritos.
            </p>

            <form class="form" action="home.php" method="get">

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Insira seu email"
                    >
                </div>

                <div class="field">
                    <label for="usuario">Usuário</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="Crie um nome de usuário"
                    >
                </div>

                <div class="field">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Crie uma senha"
                    >
                </div>

                <div class="field">
                    <label for="confirmar">Senha</label>
                    <input
                        type="password"
                        id="confirmar"
                        name="confirmar"
                        placeholder="Confirme sua senha"
                    >
                </div>

                
                <button type="submit" class="submit">
                    Criar conta
                </button>

            </form>

            <a href="index.php" class="back">
                ← Voltar para o início
            </a>

        </section>

    </main>

</body>
</html>
