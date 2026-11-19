
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);

if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$o = [
    'titulo' => '',
    'descricao' => '',
    'genero' => '',
    'status' => 'em_andamento',
    'local_leitura' => '',
    'capa' => ''
];

if ($id) {

    $s = $pdo->prepare("
        SELECT *
        FROM obras
        WHERE id = ? AND artista_id = ?
    ");

    $s->execute([$id, $u['id']]);

    $o = $s->fetch();

    if (!$o) {
        exit('Obra não encontrada');
    }
}

$erro = flash('erro');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    csrf_validar();

    $t = trim($_POST['titulo'] ?? '');
    $d = trim($_POST['descricao'] ?? '');
    $g = trim($_POST['genero'] ?? '');
    $st = $_POST['status'] ?? 'em_andamento';
    $url = trim($_POST['local_leitura'] ?? '');

    $er = [];

    if ($t === '' || mb_strlen($t) > 150) {
        $er[] = 'Título obrigatório (até 150 caracteres).';
    }

    if (!in_array($st, ['em_andamento', 'concluida', 'pausada'], true)) {
        $er[] = 'Status inválido.';
    }

    if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
        $er[] = 'Link inválido.';
    }

    $c = $o['capa'];

    $up = !empty($_FILES['capa']) &&
        $_FILES['capa']['error'] !== UPLOAD_ERR_NO_FILE;

    if ($up && !$er) {

        $f = $_FILES['capa'];

        $mime = $f['error'] === 0
            ? (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name'])
            : '';

        $types = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $ext = $types[$mime] ?? null;

        if (
            !$ext ||
            $f['size'] > 4194304 ||
            !getimagesize($f['tmp_name'])
        ) {

            $er[] = 'Capa inválida (JPG, PNG ou WEBP até 4 MB).';

        } else {

            $dir = __DIR__ . '/uploads/obras/';

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $fn = bin2hex(random_bytes(16)) . '.' . $ext;

            if (move_uploaded_file($f['tmp_name'], $dir . $fn)) {
                $c = 'uploads/obras/' . $fn;
            } else {
                $er[] = 'Falha ao salvar capa.';
            }
        }
    }

    if ($er) {

        $erro = implode(' ', $er);

        $o = array_merge($o, [
            'titulo' => $t,
            'descricao' => $d,
            'genero' => $g,
            'status' => $st,
            'local_leitura' => $url
        ]);

    } else {

        if ($id) {

            $s = $pdo->prepare("
                UPDATE obras
                SET
                    titulo = ?,
                    descricao = ?,
                    genero = ?,
                    status = ?,
                    local_leitura = ?,
                    capa = ?
                WHERE id = ? AND artista_id = ?
            ");

            $s->execute([
                $t,
                $d,
                $g,
                $st,
                $url,
                $c,
                $id,
                $u['id']
            ]);

        } else {

            $s = $pdo->prepare("
                INSERT INTO obras (
                    artista_id,
                    titulo,
                    descricao,
                    genero,
                    status,
                    local_leitura,
                    capa,
                    publicada
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ");

            $s->execute([
                $u['id'],
                $t,
                $d,
                $g,
                $st,
                $url,
                $c
            ]);
        }

        flash('ok', 'Obra salva.');

        redirecionar('painel_artista.php');
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Obra</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            padding: 25px;
            background: linear-gradient(135deg, #12091a, #24102f);
            font-family: Arial, sans-serif;
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
            <?= $id ? 'Editar obra' : 'Cadastrar obra' ?>
        </h1>

        <?php if ($erro): ?>

            <p><?= $erro ?></p>

        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= h(csrf_token()) ?>"
            >

            <label>Título</label>

            <input
                name="titulo"
                maxlength="150"
                required
                value="<?= h($o['titulo']) ?>"
            >

            <label>Descrição</label>

            <textarea name="descricao"><?= h($o['descricao']) ?></textarea>

            <label>Gênero</label>

            <input
                name="genero"
                maxlength="80"
                value="<?= h($o['genero'] ?? '') ?>"
            >

            <label>Status</label>

            <select name="status">

                <?php foreach ([
                    'em_andamento' => 'Em andamento',
                    'concluida' => 'Concluída',
                    'pausada' => 'Pausada'
                ] as $k => $v): ?>

                    <option
                        value="<?= $k ?>"
                        <?= $o['status'] === $k ? 'selected' : '' ?>
                    >
                        <?= $v ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Link de leitura</label>

            <input
                type="url"
                name="local_leitura"
                value="<?= h($o['local_leitura']) ?>"
            >

            <label>
                Capa (JPG, PNG ou WEBP até 4 MB)
            </label>

            <input
                type="file"
                name="capa"
                accept="image/jpeg,image/png,image/webp"
            >

            <p>

                <button class="btn">
                    Salvar obra
                </button>

                <a class="btn" href="painel_artista.php">
                    Cancelar
                </a>

            </p>

        </form>

    </main>

</body>

</html>
s