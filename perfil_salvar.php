<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('perfil.php');
}

csrf_validar();
$usuario = requer_login($pdo);

$nome      = trim($_POST['nome'] ?? '');
$user      = trim($_POST['usuario'] ?? '');
$biografia = trim($_POST['biografia'] ?? '');
$email     = trim($_POST['email'] ?? '');
$senha     = $_POST['senha'] ?? '';

$erros = [];

if ($nome === '' || mb_strlen($nome) > 100) {
    $erros[] = 'Informe um nome (até 100 caracteres).';
}
if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $user)) {
    $erros[] = 'Usuário: 3 a 30 caracteres (letras, números e _).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'Email inválido.';
}
if (mb_strlen($biografia) > 300) {
    $erros[] = 'A biografia pode ter no máximo 300 caracteres.';
}
if ($senha !== '' && strlen($senha) < 6) {
    $erros[] = 'A nova senha deve ter pelo menos 6 caracteres.';
}

// usuário/email já em uso por outra pessoa?
if (!$erros) {
    $stmt = $pdo->prepare('SELECT 1 FROM usuarios WHERE (usuario = ? OR email = ?) AND id <> ?');
    $stmt->execute([$user, $email, $usuario['id']]);
    if ($stmt->fetch()) {
        $erros[] = 'Esse usuário ou email já está em uso.';
    }
}

// validação da imagem
$ext = null;
$temFoto = !empty($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE;

if (!$erros && $temFoto) {
    $f = $_FILES['foto'];

    if ($f['error'] !== UPLOAD_ERR_OK) {
        $erros[] = 'Falha ao enviar a imagem.';
    } elseif ($f['size'] > 2 * 1024 * 1024) {
        $erros[] = 'A imagem deve ter no máximo 2 MB.';
    } else {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $ext = $permitidos[$mime] ?? null;

        if ($ext === null || @getimagesize($f['tmp_name']) === false) {
            $erros[] = 'Formato de imagem inválido (use JPG, PNG ou WEBP).';
        }
    }
}

if ($erros) {
    flash('erro', implode(' ', $erros));
    redirecionar('perfil.php?editar=1');
}

// salva a imagem
$novaFoto = $usuario['foto'];
if ($temFoto) {
    $dir = __DIR__ . '/uploads/perfis/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $arquivo = bin2hex(random_bytes(16)) . '.' . $ext;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $arquivo)) {
        flash('erro', 'Não foi possível salvar a imagem.');
        redirecionar('perfil.php?editar=1');
    }

    // apaga a foto antiga
    if (!empty($usuario['foto'])) {
        $antiga = $dir . basename($usuario['foto']);
        if (is_file($antiga)) {
            unlink($antiga);
        }
    }

    $novaFoto = $arquivo;
}

// atualiza o banco
if ($senha !== '') {
    $sql = 'UPDATE usuarios SET nome=?, usuario=?, email=?, biografia=?, foto=?, senha_hash=? WHERE id=?';
    $params = [$nome, $user, $email, $biografia, $novaFoto, password_hash($senha, PASSWORD_DEFAULT), $usuario['id']];
} else {
    $sql = 'UPDATE usuarios SET nome=?, usuario=?, email=?, biografia=?, foto=? WHERE id=?';
    $params = [$nome, $user, $email, $biografia, $novaFoto, $usuario['id']];
}

$pdo->prepare($sql)->execute($params);

flash('ok', 'Perfil atualizado com sucesso!');
redirecionar('perfil.php');
