
<?php

session_start();

require_once __DIR__ . '/conexao.php';

$busca = trim($_GET['busca'] ?? '');
$param = '%' . $busca . '%';

$sql = "
    SELECT
        o.id,
        o.titulo,
        o.descricao,
        o.capa,
        u.id AS artista_id,
        u.usuario AS artista
    FROM obras o
    INNER JOIN usuarios u ON u.id = o.artista_id
    WHERE o.publicada = 1
";

if ($busca !== '') {
    $sql .= " AND o.titulo LIKE :busca";
}

$sql .= " ORDER BY o.criada_em DESC";

$stmt = $pdo->prepare($sql);

if ($busca !== '') {
    $stmt->bindValue(':busca', $param, PDO::PARAM_STR);
}

$stmt->execute();
$resultados = $stmt->fetchAll();

function h($valor): string {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

$logado = isset($_SESSION['usuario_id']);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Webtoonsz — Buscar</title>

    <style>
        :root {
            --lav: #E3D9FC;
            --pink: #BF40FA;
            --white: #F8F5FF;
            --muted: rgba(248,245,255,.68);
            --border: rgba(227,217,252,.16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 24px;
            font-family: Arial, sans-serif;
            color: var(--white);
            background:
                radial-gradient(circle at 8% 15%, rgba(73,40,194,.65), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191,64,250,.28), transparent 28%),
                linear-gradient(135deg, #12091A, #24102F 48%, #08090C);
        }

        .page {
            width: min(1050px, 100%);
            margin: auto;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 0 18px;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-weight: 800;
            margin-right: auto;
            color: var(--white);
            text-decoration: none;
        }

        .btn {
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--white);
            text-decoration: none;
            background: rgba(4,6,7,.65);
        }

        h1 {
            margin: 35px 0 10px;
        }

        .muted {
            color: var(--muted);
            margin-bottom: 20px;
        }

        .form {
            display: flex;
            gap: 10px;
            margin: 20px 0 30px;
        }

        .form input {
            flex: 1;
            min-width: 0;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: rgba(4,6,7,.7);
            color: var(--white);
        }

        .form button {
            padding: 0 22px;
            border: 0;
            border-radius: 10px;
            background: var(--pink);
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 15px;
        }

        .card {
            display: grid;
            grid-template-columns: 95px 1fr;
            gap: 14px;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: rgba(4,6,7,.55);
            color: var(--white);
            text-decoration: none;
        }

        .cover {
            height: 120px;
            border-radius: 9px;
            background: linear-gradient(145deg, rgba(73,40,194,.8), rgba(191,64,250,.35));
        }

        .cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 9px;
        }

        .card h2 {
            font-size: 1rem;
            margin: 4px 0 7px;
        }

        .card p {
            font-size: .8rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .empty {
            padding: 25px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            color: var(--muted);
        }

        @media(max-width: 560px) {
            body {
                padding: 14px;
            }

            .header {
                flex-wrap: wrap;
            }

            .form button {
                padding: 0 13px;
            }
        }
    </style>

</head>

<body>

    <div class="page">

        <header class="header">

            <a class="logo" href="home.php">
                ✦ Webtoonsz
            </a>

            <a class="btn" href="home.php">
                Explorar
            </a>

            <?php if ($logado): ?>

                <a class="btn" href="perfil.php">
                    Meu perfil
                </a>

            <?php else: ?>

                <a class="btn" href="login.php">
                    Entrar
                </a>

                <a class="btn" href="cadastro.php">
                    Cadastrar-se
                </a>

            <?php endif; ?>

        </header>

        <main>

            <h1>Buscar webtoons</h1>

            <p class="muted">
                Pesquise pelo título da obra.
            </p>

            <form class="form" method="get" action="busca.php">

                <input
                    type="search"
                    name="busca"
                    value="<?= h($busca) ?>"
                    placeholder="Digite o título da obra"
                    aria-label="Título da obra"
                >

                <button type="submit">
                    Buscar
                </button>

            </form>

            <h2 style="font-size:1.1rem;margin-bottom:15px">
                <?= $busca !== '' ? 'Resultados da pesquisa' : 'Todas as obras' ?>
            </h2>

            <?php if ($resultados): ?>

                <div class="grid">

                    <?php foreach ($resultados as $obra): ?>

                        <a class="card" href="obra.php?id=<?= (int)$obra['id'] ?>">

                            <div class="cover">

                                <?php if ($obra['capa']): ?>

                                    <img
                                        src="<?= h($obra['capa']) ?>"
                                        alt="Capa de <?= h($obra['titulo']) ?>"
                                    >

                                <?php endif; ?>

                            </div>

                            <div>

                                <h2>
                                    <?= h($obra['titulo']) ?>
                                </h2>

                                <p>
                                    por @<?= h($obra['artista']) ?>
                                </p>

                                <p style="margin-top:8px">
                                    <?= h(mb_strimwidth($obra['descricao'] ?? '', 0, 115, '...', 'UTF-8')) ?>
                                </p>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty">
                    <?= $busca !== '' ? 'Nenhuma obra encontrada com esse título.' : 'Ainda não existem obras publicadas.' ?>
                </div>

            <?php endif; ?>

        </main>

    </div>

</body>
</html>
