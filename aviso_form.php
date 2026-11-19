
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);

if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$a = [
    'obra_id' => '',
    'titulo' => '',
    'mensagem' => '',
    'tipo' => 'geral',
    'data_evento' => '',
    'plataforma_url' => ''
];

if ($id) {
    $s = $pdo->prepare("
        SELECT *
        FROM atualizacoes
        WHERE id = ? AND artista_id = ?
    ");

    $s->execute([$id, $u['id']]);
    $a = $s->fetch();

    if (!$a) {
        exit('Aviso não encontrado');
    }
}

$s = $pdo->prepare("
    SELECT id, titulo
    FROM obras
    WHERE artista_id = ?
    ORDER BY titulo
");

$s->execute([$u['id']]);
$obras = $s->fetchAll();

$erro = flash('erro');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    csrf_validar();

    $oid = ($_POST['obra_id'] ?? '') !== ''
        ? (int) $_POST['obra_id']
        : null;

    $t = trim($_POST['titulo'] ?? '');
    $m = trim($_POST['mensagem'] ?? '');
    $tipo = $_POST['tipo'] ?? 'geral';
    $data = $_POST['data_evento'] ?: null;
    $url = trim($_POST['plataforma_url'] ?? '');

    $er = [];

    if ($t === '' || mb_strlen($t) > 150) {
        $er[] = 'Título obrigatório.';
    }

    if ($m === '') {
        $er[] = 'Mensagem obrigatória.';
    }

    if (!in_array($tipo, ['aviso', 'lancamento', 'pausa', 'retorno', 'plataforma', 'geral'], true)) {
        $er[] = 'Tipo inválido.';
    }

    if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
        $er[] = 'URL inválida.';
    }

    if ($oid) {
        $s = $pdo->prepare("
            SELECT id
            FROM obras
            WHERE id = ? AND artista_id = ?
        ");

        $s->execute([$oid, $u['id']]);

        if (!$s->fetch()) {
            $er[] = 'Obra inválida.';
        }
    }

    if ($er) {

        $erro = implode(' ', $er);

        $a = array_merge($a, [
            'obra_id' => $oid,
            'titulo' => $t,
            'mensagem' => $m,
            'tipo' => $tipo,
            'data_evento' => $data,
            'plataforma_url' => $url
        ]);

    } else {

        if ($id) {

            $s = $pdo->prepare("
                UPDATE atualizacoes
                SET obra_id = ?,
                    titulo = ?,
                    mensagem = ?,
                    tipo = ?,
                    data_evento = ?,
                    plataforma_url = ?
                WHERE id = ? AND artista_id = ?
            ");

            $s->execute([
                $oid,
                $t,
                $m,
                $tipo,
                $data,
                $url,
                $id,
                $u['id']
            ]);

        } else {

            $s = $pdo->prepare("
                INSERT INTO atualizacoes (
                    artista_id,
                    obra_id,
                    titulo,
                    mensagem,
                    tipo,
                    data_evento,
                    plataforma_url,
                    publicada
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ");

            $s->execute([
                $u['id'],
                $oid,
                $t,
                $m,
                $tipo,
                $data,
                $url
            ]);
        }

        flash('ok', 'Publicação salva.');
        redirecionar('meus_avisos.php');
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Aviso</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            padding: 25px;
            background: linear-gradient(135deg, #12091a, #24102f);
            font-family: Arial;
            color: white;
        }

        main {
            max-width: 680px;
            margin: auto;
            padding: 25px;
            background: #040607cc;
            border-radius: 16px;
        }

        label {
            display: block;
            margin: 14px 0 6px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            background: #21172b;
            color: white;
            border: 1px solid #e3d9fc44;
            border-radius: 8px;
        }

        textarea {
            min-height: 120px;
        }

        .btn {
            display: inline-block;
            padding: 12px 16px;
            background: #BF40FA;
            color: white;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>

</head>

<body>

    <main>

        <h1>
            <?= $id ? 'Editar publicação' : 'Publicar aviso ou atualização' ?>
        </h1>

        <?php if ($erro): ?>

            <p><?= $erro ?></p>

        <?php endif; ?>

        <form method="post">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= h(csrf_token()) ?>"
            >

            <label>Obra ou recado geral</label>

            <select name="obra_id">

                <option value="">Recado geral</option>

                <?php foreach ($obras as $o): ?>

                    <option
                        value="<?= (int)$o['id'] ?>"
                        <?= ((string)$a['obra_id'] === (string)$o['id']) ? 'selected' : '' ?>
                    >
                        <?= h($o['titulo']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Título</label>

            <input
                name="titulo"
                required
                maxlength="150"
                value="<?= h($a['titulo']) ?>"
            >

            <label>Tipo</label>

            <select name="tipo">

                <?php foreach ([
                    'aviso' => 'Aviso',
                    'lancamento' => 'Lançamento de capítulo',
                    'pausa' => 'Pausa',
                    'retorno' => 'Retorno',
                    'plataforma' => 'Plataforma de leitura',
                    'geral' => 'Recado geral'
                ] as $k => $v): ?>

                    <option
                        value="<?= $k ?>"
                        <?= $a['tipo'] === $k ? 'selected' : '' ?>
                    >
                        <?= $v ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Mensagem</label>

            <textarea name="mensagem" required><?= h($a['mensagem']) ?></textarea>

            <label>Data do evento</label>

            <input
                type="date"
                name="data_evento"
                value="<?= h($a['data_evento']) ?>"
            >

            <label>Link da plataforma</label>

            <input
                type="url"
                name="plataforma_url"
                value="<?= h($a['plataforma_url']) ?>"
            >

            <p>
                <button class="btn">Publicar</button>

                <a class="btn" href="meus_avisos.php">
                    Cancelar
                </a>
            </p>

        </form>

    </main>

</body>
</html>
