<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);
$erro = flash('erro');
$sucesso = flash('ok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $nome = trim($_POST['nome'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $biografia = trim($_POST['biografia'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $erros = [];

    if ($nome === '' || mb_strlen($nome) > 100) $erros[] = 'Informe um nome com até 100 caracteres.';
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $usuario)) $erros[] = 'O usuário deve ter de 3 a 30 caracteres, usando letras, números ou underline.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
    if (mb_strlen($biografia) > 300) $erros[] = 'A biografia deve ter no máximo 300 caracteres.';
    if ($senha !== '' && strlen($senha) < 6) $erros[] = 'A nova senha deve ter pelo menos 6 caracteres.';

    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE (usuario = ? OR email = ?) AND id <> ? LIMIT 1');
    $stmt->execute([$usuario, $email, $u['id']]);
    if ($stmt->fetch()) $erros[] = 'Este usuário ou e-mail já está em uso.';

    $foto = $u['foto'];
    $temFoto = isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE;

    if ($temFoto && !$erros) {
        $f = $_FILES['foto'];
        if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 2 * 1024 * 1024) {
            $erros[] = 'A foto deve ter até 2 MB.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
            $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $ext = $permitidos[$mime] ?? null;
            if (!$ext || getimagesize($f['tmp_name']) === false) {
                $erros[] = 'Envie uma imagem JPG, PNG ou WEBP válida.';
            } else {
                $dir = __DIR__ . '/uploads/perfis/';
                if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) $erros[] = 'Não foi possível criar a pasta de fotos.';
                if (!$erros) {
                    $novo = bin2hex(random_bytes(16)) . '.' . $ext;
                    if (!move_uploaded_file($f['tmp_name'], $dir . $novo)) $erros[] = 'Não foi possível salvar a foto.';
                    else $foto = $novo;
                }
            }
        }
    }

    if ($erros) {
        $erro = implode(' ', $erros);
        $u = array_merge($u, compact('nome', 'usuario', 'email', 'biografia'));
    } else {
        if ($senha !== '') {
            $stmt = $pdo->prepare('UPDATE usuarios SET nome=?, usuario=?, email=?, biografia=?, foto=?, senha_hash=? WHERE id=?');
            $stmt->execute([$nome, $usuario, $email, $biografia, $foto, password_hash($senha, PASSWORD_DEFAULT), $u['id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE usuarios SET nome=?, usuario=?, email=?, biografia=?, foto=? WHERE id=?');
            $stmt->execute([$nome, $usuario, $email, $biografia, $foto, $u['id']]);
        }
        if ($temFoto && !empty($u['foto']) && $u['foto'] !== $foto) {
            $antiga = __DIR__ . '/uploads/perfis/' . basename($u['foto']);
            if (is_file($antiga)) unlink($antiga);
        }
        flash('ok', 'Perfil atualizado com sucesso.');
        redirecionar('perfil.php');
    }
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar perfil — Webtoonsz</title>
<style>
:root{--pink:#BF40FA;--light:#F8F5FF;--muted:#c9bfd7;--border:#e3d9fc2b}*{box-sizing:border-box}body{margin:0;min-height:100vh;padding:24px;display:grid;place-items:center;font-family:Arial,sans-serif;color:var(--light);background:radial-gradient(circle at 8% 15%,#4928c2b8,transparent 30%),radial-gradient(circle at 92% 20%,#bf40fa52,transparent 28%),linear-gradient(135deg,#12091a,#24102f 48%,#08090c)}.card{width:min(600px,100%);padding:25px;border:1px solid var(--border);border-radius:18px;background:#040607c2}h1{margin-top:0}.field{display:flex;flex-direction:column;gap:7px;margin:15px 0}label{font-size:.86rem;font-weight:bold}input,textarea{width:100%;padding:12px;border:1px solid var(--border);border-radius:9px;background:#e3d9fc15;color:var(--light);font:inherit}textarea{min-height:95px;resize:vertical}.buttons{display:flex;gap:10px;margin-top:20px}.btn{padding:12px 16px;border:0;border-radius:9px;background:#171020;color:white;text-decoration:none;cursor:pointer}.primary{background:var(--pink)}.error{padding:12px;border-radius:9px;background:#a51d4b33;border:1px solid #ff75a555}.hint{color:var(--muted);font-size:.78rem}
</style></head><body><main class="card"><h1>Editar perfil</h1><?php if($erro): ?><p class="error"><?= h($erro) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
<div class="field"><label for="nome">Nome</label><input id="nome" name="nome" maxlength="100" required value="<?= h($u['nome']) ?>"></div>
<div class="field"><label for="usuario">Nome de usuário</label><input id="usuario" name="usuario" minlength="3" maxlength="30" pattern="[A-Za-z0-9_]{3,30}" required value="<?= h($u['usuario']) ?>"></div>
<div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" maxlength="150" required value="<?= h($u['email']) ?>"></div>
<div class="field"><label for="biografia">Biografia</label><textarea id="biografia" name="biografia" maxlength="300"><?= h($u['biografia']) ?></textarea></div>
<div class="field"><label for="foto">Foto de perfil</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"><span class="hint">JPG, PNG ou WEBP, até 2 MB.</span></div>
<div class="field"><label for="senha">Nova senha (opcional)</label><input id="senha" name="senha" type="password" minlength="6" autocomplete="new-password"><span class="hint">Deixe em branco para manter a senha atual.</span></div>
<div class="buttons"><a class="btn" href="perfil.php">Cancelar</a><button class="btn primary" type="submit">Salvar alterações</button></div>
</form></main></body></html>
