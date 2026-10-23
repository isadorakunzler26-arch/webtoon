
<?php


session_start();

require_once __DIR__ . '/conexao.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    exit('Erro: verifique a conexão com o banco de dados.');
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int) $_SESSION['usuario_id'];

$erros = [];
$mensagem = '';



function h($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}




try {
    $sql = '
        SELECT id, nome, usuario, email
        FROM usuarios
        WHERE id = :id
        LIMIT 1
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$perfil) {
        http_response_code(404);
        exit('Usuário não encontrado.');
    }

} catch (PDOException $e) {
    error_log($e->getMessage());

    exit('Não foi possível carregar o perfil.');
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $nome = trim($_POST['nome'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');

    
    if ($nome === '') {
        $erros[] = 'Informe seu nome.';
    }

    
    if (!preg_match('/^[a-zA-Z0-9_.-]{3,30}$/', $usuario)) {
        $erros[] = 'O nome de usuário deve ter entre 3 e 30 caracteres.';
    }

   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

   
    $perfil['nome'] = $nome;
    $perfil['usuario'] = $usuario;
    $perfil['email'] = $email;


    
    if (empty($erros)) {

        try {
           
            $sql = '
                SELECT id
                FROM usuarios
                WHERE (usuario = :usuario OR email = :email)
                AND id <> :id
                LIMIT 1
            ';

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'usuario' => $usuario,
                'email' => $email,
                'id' => $id
            ]);

            if ($stmt->fetch()) {

                $erros[] = 'Esse nome de usuário ou e-mail já está em uso.';

            } else {

                
                $sql = '
                    UPDATE usuarios
                    SET nome = :nome,
                        usuario = :usuario,
                        email = :email
                    WHERE id = :id
                ';

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    'nome' => $nome,
                    'usuario' => $usuario,
                    'email' => $email,
                    'id' => $id
                ]);

                $mensagem = 'Perfil atualizado com sucesso!';
            }

        } catch (PDOException $e) {
            error_log($e->getMessage());

            $erros[] = 'Não foi possível salvar as alterações.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Webtoonsz — Editar perfil</title>

    <style>
       
        :root {
            --periwinkle: #E3D9FC;
            --magenta: #BF40FA;
            --blue: #4928C2;
            --purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --light: #F8F5FF;

            --muted: rgba(248, 245, 255, 0.68);
            --border: rgba(227, 217, 252, 0.18);
        }


        

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 24px;

            display: grid;
            place-items: center;

            font-family: Arial, Helvetica, sans-serif;
            color: var(--light);

            background:
                radial-gradient(
                    circle at 8% 15%,
                    rgba(73, 40, 194, 0.72),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 92% 20%,
                    rgba(191, 64, 250, 0.32),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 50% 100%,
                    rgba(91, 42, 98, 0.75),
                    transparent 38%
                ),
                linear-gradient(
                    135deg,
                    #12091A 0%,
                    #24102F 48%,
                    #08090C 100%
                );
        }


       

        .container {
            width: min(100%, 560px);
        }

        .card {
            padding: 28px;

            border: 1px solid var(--border);
            border-radius: 20px;

            background: rgba(4, 6, 7, 0.76);

            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        }


        
        .topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            padding-bottom: 18px;

            border-bottom: 1px solid var(--border);
        }

        h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .logo {
            margin-right: 7px;
            color: var(--magenta);
        }


        
        .formulario {
            display: flex;
            flex-direction: column;
            gap: 17px;

            margin-top: 24px;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        label {
            color: var(--periwinkle);
            font-size: 0.88rem;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 12px 13px;

            border: 1px solid rgba(227, 217, 252, 0.17);
            border-radius: 9px;

            outline: none;

            background: rgba(227, 217, 252, 0.09);
            color: var(--light);

            font: inherit;
        }

        input:focus {
            border-color: var(--magenta);

            box-shadow:
                0 0 0 3px rgba(191, 64, 250, 0.12);
        }


       

        .acoes {
            display: flex;
            gap: 10px;

            margin-top: 5px;
        }

        .botao {
            min-height: 42px;
            padding: 0 18px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 9px;

            background: var(--black);
            color: var(--white);

            font-size: 0.88rem;
            text-decoration: none;

            cursor: pointer;
        }

        .botao-principal {
            flex: 1;

            background: var(--magenta);
            font-weight: 700;
        }

        .botao:hover {
            filter: brightness(1.12);
        }


        
        .alerta {
            margin-top: 18px;
            padding: 12px 14px;

            border-radius: 9px;

            font-size: 0.86rem;
            line-height: 1.5;
        }

        .sucesso {
            color: #E6FFED;

            border: 1px solid rgba(100, 220, 140, 0.35);
            background: rgba(40, 160, 90, 0.15);
        }

        .erro {
            color: #FFE7F2;

            border: 1px solid rgba(255, 100, 170, 0.35);
            background: rgba(180, 30, 90, 0.15);
        }

        .erro ul {
            margin: 0;
            padding-left: 19px;
        }

        .observacao {
            margin-top: 18px;

            color: var(--muted);
            font-size: 0.75rem;
            line-height: 1.5;
        }


       
        @media (max-width: 480px) {
            body {
                padding: 13px;
            }

            .card {
                padding: 20px;
            }

            .topo {
                align-items: flex-start;
                flex-direction: column;
            }

            .acoes {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <main class="container">

        <section class="card">

            
            <header class="topo">

                <h1>
                    <span class="logo">✦</span>
                    Editar perfil
                </h1>

                <a class="botao" href="perfil.php">
                    Voltar ao perfil
                </a>

            </header>


           
            <?php if ($mensagem !== ''): ?>

                <div class="alerta sucesso" role="status">
                    <?= h($mensagem) ?>
                </div>

            <?php endif; ?>


            
            <?php if (!empty($erros)): ?>

                <div class="alerta erro" role="alert">

                    <ul>
                        <?php foreach ($erros as $erro): ?>

                            <li><?= h($erro) ?></li>

                        <?php endforeach; ?>
                    </ul>

                </div>

            <?php endif; ?>


           
            <form
                class="formulario"
                method="post"
                action="editar_perfil.php"
            >

                <div class="campo">

                    <label for="nome">
                        Nome
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="100"
                        value="<?= h($perfil['nome']) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label for="usuario">
                        Nome de usuário
                    </label>

                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        minlength="3"
                        maxlength="30"
                        pattern="[a-zA-Z0-9_.-]{3,30}"
                        value="<?= h($perfil['usuario']) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="255"
                        value="<?= h($perfil['email']) ?>"
                        required
                    >

                </div>


               
                <div class="acoes">

                    <a class="botao" href="perfil.php">
                        Cancelar
                    </a>

                    <button
                        class="botao botao-principal"
                        type="submit"
                    >
                        Salvar alterações
                    </button>

                </div>

            </form>


            <p class="observacao">
                As alterações são salvas no banco de dados.
                A senha não é alterada nesta tela.
            </p>

        </section>

    </main>

</body>
</html>
