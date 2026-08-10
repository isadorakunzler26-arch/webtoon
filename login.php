<?php
//recuperação de senha


$etapa = $_GET['etapa'] ?? 'login';

$etapasValidas = ['login', 'email', 'codigo', 'nova-senha'];

if (!in_array($etapa, $etapasValidas, true)) {
    $etapa = 'login';
}

$siteName = "Webtoonsz";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — <?= $etapa === 'login' ? 'Entrar' : 'Recuperar senha' ?></title>

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

        .login-container {
            width: min(100%, 470px);
        }

        .login-card {
            position: relative;
            padding: 42px 45px 36px;

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
            width: 50px;
            height: 50px;
            margin: 0 auto 18px;

            display: grid;
            place-items: center;

            border-radius: 15px;
            background: var(--periwinkle);
            color: var(--ultrasonic-blue);

            font-size: 1.6rem;

            box-shadow: 0 0 25px rgba(227, 217, 252, 0.18);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;

            font-size: clamp(1.8rem, 5vw, 2.2rem);
            letter-spacing: -0.8px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .field {
            display: grid;
            grid-template-columns: 82px 1fr;
            align-items: center;
            gap: 12px;
        }

        .field label {
            font-size: 0.88rem;
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

            font-size: 0.86rem;

            transition: 0.2s ease;
        }

        .field input::placeholder {
            color: rgba(248, 245, 255, 0.42);
        }

        .field input:focus {
            border-color: var(--hyper-magenta);
            background: rgba(227, 217, 252, 0.14);
            box-shadow: 0 0 0 3px rgba(191, 64, 250, 0.12);
        }

        .main-button {
            width: 100%;
            height: 45px;
            margin-top: 8px;

            border: 0;
            border-radius: 10px;

            color: var(--white);
            background: var(--hyper-magenta);

            font-size: 0.92rem;
            font-weight: 700;

            cursor: pointer;

            box-shadow: 0 8px 25px rgba(191, 64, 250, 0.25);
            transition: 0.2s ease;
        }

        .main-button:hover {
            transform: translateY(-2px);
            background: #cf5dff;
            box-shadow: 0 12px 32px rgba(191, 64, 250, 0.4);
        }

        .forgot {
            display: block;
            width: fit-content;
            margin: 21px auto 0;

            color: var(--periwinkle);
            font-size: 0.84rem;
            font-weight: 700;
            text-decoration: none;

            transition: 0.2s ease;
        }

        .forgot:hover {
            color: var(--hyper-magenta);
        }

        .bottom-links {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-top: 25px;
            padding-top: 22px;

            border-top: 1px solid var(--border);
        }

        .bottom-links a {
            color: var(--muted);
            font-size: 0.82rem;
            text-decoration: none;
        }

        .bottom-links a:hover {
            color: var(--periwinkle);
        }

        .step-text {
            text-align: center;
            margin: -17px auto 27px;

            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 7px;
            margin-bottom: 27px;
        }

        .step-indicator span {
            width: 28px;
            height: 4px;
            border-radius: 99px;
            background: rgba(227, 217, 252, 0.18);
        }

        .step-indicator span.active {
            background: var(--hyper-magenta);
        }

        .back {
            display: block;
            width: fit-content;
            margin: 22px auto 0;

            color: var(--periwinkle);
            font-size: 0.84rem;
            text-decoration: none;
        }

        .back:hover {
            color: var(--hyper-magenta);
        }

        @media (max-width: 500px) {
            .login-card {
                padding: 34px 24px 30px;
            }

            .field {
                grid-template-columns: 1fr;
                gap: 7px;
            }

            .field label {
                font-size: 0.84rem;
            }

            .field input {
                height: 45px;
            }
        }
    </style>
</head>

<body>

<main class="login-container">

    <section class="login-card">

        <div class="logo">✦</div>

        <?php if ($etapa === 'login'): ?>

            

            <h1>Entrar</h1>

            <form class="form" action="home.php" method="get">

                <div class="field">
                    <label for="usuario">Usuário</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="Insira seu email/usuário"
                    >
                </div>

                <div class="field">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Insira sua senha"
                    >
                </div>

                <button type="submit" class="main-button">
                    Entrar
                </button>

            </form>

            <a href="login.php?etapa=email" class="forgot">
                Esqueci a senha
            </a>

            <div class="bottom-links">
                <a href="index.php">← Início</a>
                <a href="cadastro.php">Cadastrar-se</a>
            </div>


        <?php elseif ($etapa === 'email'): ?>

           

            <h1>Recuperar senha</h1>

            <div class="step-indicator">
                <span class="active"></span>
                <span></span>
                <span></span>
            </div>

            <p class="step-text">
                Informe seu email para receber o código de recuperação.
            </p>

            <form class="form" action="login.php" method="get">

                <input type="hidden" name="etapa" value="codigo">

                <div class="field">
                    <label for="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Email"
                    >
                </div>

                <button type="submit" class="main-button">
                    Enviar código
                </button>

            </form>

            <a href="login.php" class="back">
                ← Voltar para entrar
            </a>


        <?php elseif ($etapa === 'codigo'): ?>

        

            <h1>Recuperar senha</h1>

            <div class="step-indicator">
                <span class="active"></span>
                <span class="active"></span>
                <span></span>
            </div>

            <p class="step-text">
                Digite o código enviado para o seu email.
            </p>

            <form class="form" action="login.php" method="get">

                <input type="hidden" name="etapa" value="nova-senha">

                <div class="field">
                    <label for="codigo">Código:</label>
                    <input
                        type="text"
                        id="codigo"
                        name="codigo"
                        placeholder="Código"
                    >
                </div>

                <button type="submit" class="main-button">
                    Verificar código
                </button>

            </form>

            <a href="login.php?etapa=email" class="back">
                ← Voltar
            </a>


        <?php else: ?>

            
            <h1>Recuperar senha</h1>

            <div class="step-indicator">
                <span class="active"></span>
                <span class="active"></span>
                <span class="active"></span>
            </div>

            <p class="step-text">
                Crie uma nova senha para acessar sua conta.
            </p>

            <form class="form" action="login.php" method="get">

                <input type="hidden" name="etapa" value="login">

                <div class="field">
                    <label for="novaSenha">Nova senha</label>
                    <input
                        type="password"
                        id="novaSenha"
                        name="novaSenha"
                        placeholder="Nova senha"
                    >
                </div>

                <div class="field">
                    <label for="confirmarSenha">Nova senha</label>
                    <input
                        type="password"
                        id="confirmarSenha"
                        name="confirmarSenha"
                        placeholder="Confirmar senha"
                    >
                </div>

                <button type="submit" class="main-button">
                    Salvar senha
                </button>

            </form>

            <a href="login.php" class="back">
                ← Voltar para entrar
            </a>

        <?php endif; ?>

    </section>

</main>

</body>
</html>
