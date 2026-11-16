<?php
session_start();
require_once __DIR__ . '/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(404);
    exit('Artista não encontrado.');
}

$stmt = $pdo->prepare("SELECT id, nome, usuario, biografia, foto, criado_em FROM usuarios WHERE id = ? AND tipo = 'artista' LIMIT 1");
$stmt->execute([$id]);
$artista = $stmt->fetch();

if (!$artista) {
    http_response_code(404);
    exit('Artista não encontrado.');
}

$stmt = $pdo->prepare("SELECT id, titulo, descricao, capa, status FROM obras WHERE artista_id = ? AND publicada = 1 ORDER BY criada_em DESC");
$stmt->execute([$id]);
$obras = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT a.id, a.titulo, a.mensagem, a.tipo, a.data_evento, a.plataforma_url,
           a.criado_em, o.id AS obra_id, o.titulo AS obra_titulo
    FROM atualizacoes a
    LEFT JOIN obras o ON o.id = a.obra_id
    WHERE a.artista_id = ? AND a.publicada = 1
    ORDER BY a.criado_em DESC
");
$stmt->execute([$id]);
$avisos = $stmt->fetchAll();

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
<title>Artista @<?= h($artista['usuario']) ?> — Webtoonsz</title>
<style>
:root{--lav:#E3D9FC;--pink:#BF40FA;--white:#F8F5FF;--muted:rgba(248,245,255,.68);--border:rgba(227,217,252,.16)}
*{box-sizing:border-box;margin:0;padding:0}body{min-height:100vh;padding:24px;font-family:Arial,sans-serif;color:var(--white);background:radial-gradient(circle at 8% 15%,rgba(73,40,194,.65),transparent 30%),radial-gradient(circle at 92% 20%,rgba(191,64,250,.28),transparent 28%),linear-gradient(135deg,#12091A,#24102F 48%,#08090C)}.page{width:min(1050px,100%);margin:auto}.header{display:flex;gap:10px;padding:14px 0 18px;border-bottom:1px solid var(--border)}.header a{padding:10px 13px;border:1px solid var(--border);border-radius:9px;color:var(--white);text-decoration:none}.header a:first-child{margin-right:auto;border:0;font-weight:bold}.profile{display:flex;align-items:center;gap:20px;padding:32px 0;border-bottom:1px solid var(--border)}.avatar{width:115px;height:115px;border-radius:50%;object-fit:cover;background:linear-gradient(145deg,#E3D9FC,#5B2A62)}.profile h1{font-size:1.8rem}.handle{color:var(--pink);margin:5px 0 12px}.bio{color:var(--muted);line-height:1.5}.section{margin:30px 0}.section h2{margin-bottom:15px}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:15px}.work{color:var(--white);text-decoration:none}.cover{height:205px;border-radius:12px;background:linear-gradient(145deg,rgba(73,40,194,.8),rgba(191,64,250,.35));object-fit:cover;width:100%}.work h3{font-size:.9rem;margin-top:8px}.work p{color:var(--muted);font-size:.78rem;margin-top:4px}.update{padding:16px;margin-bottom:12px;border:1px solid var(--border);border-radius:12px;background:rgba(4,6,7,.55)}.update small{color:var(--pink)}.update h3{margin:7px 0}.update p{color:var(--muted);line-height:1.5}.update a{display:inline-block;margin-top:8px;color:var(--lav)}.empty{padding:20px;border:1px dashed var(--border);border-radius:12px;color:var(--muted)}@media(max-width:560px){body{padding:14px}.profile{align-items:flex-start;flex-direction:column}}
</style>
</head>
<body>
<div class="page">
<header class="header">
<a href="home.php">✦ Webtoonsz</a>
<a href="busca.php">Buscar</a>
<?php if ($logado): ?><a href="perfil.php">Meu perfil</a><?php else: ?><a href="login.php">Entrar</a><a href="cadastro.php">Cadastrar-se</a><?php endif; ?>
</header>
<main>
<section class="profile">
<?php if ($artista['foto']): ?><img class="avatar" src="<?= h($artista['foto']) ?>" alt="Foto de @<?= h($artista['usuario']) ?>"><?php else: ?><div class="avatar"></div><?php endif; ?>
<div>
<h1><?= h($artista['nome']) ?></h1>
<p class="handle">@<?= h($artista['usuario']) ?> · Artista</p>
<p class="bio"><?= nl2br(h($artista['biografia'] ?: 'Este artista ainda não adicionou uma biografia.')) ?></p>
</div>
</section>
<section class="section">
<h2>Obras publicadas</h2>
<?php if ($obras): ?><div class="grid">
<?php foreach ($obras as $obra): ?>
<a class="work" href="obra.php?id=<?= (int)$obra['id'] ?>">
<?php if ($obra['capa']): ?><img class="cover" src="<?= h($obra['capa']) ?>" alt="Capa de <?= h($obra['titulo']) ?>"><?php else: ?><div class="cover"></div><?php endif; ?>
<h3><?= h($obra['titulo']) ?></h3><p><?= h(mb_strimwidth($obra['descricao'] ?? '',0,100,'...','UTF-8')) ?></p>
</a>
<?php endforeach; ?>
</div><?php else: ?><div class="empty">Este artista ainda não publicou obras.</div><?php endif; ?>
</section>
<section class="section">
<h2>Avisos e atualizações</h2>
<?php if ($avisos): ?>
<?php foreach ($avisos as $aviso): ?>
<article class="update">
<small><?= h(ucfirst(str_replace('_',' ',$aviso['tipo']))) ?> · <?= h(date('d/m/Y H:i',strtotime($aviso['criado_em']))) ?></small>
<h3><?= h($aviso['titulo']) ?></h3>
<p><?= nl2br(h($aviso['mensagem'])) ?></p>
<?php if ($aviso['data_evento']): ?><p><strong>Data informada:</strong> <?= h(date('d/m/Y',strtotime($aviso['data_evento']))) ?></p><?php endif; ?>
<?php if ($aviso['plataforma_url']): ?><a href="<?= h($aviso['plataforma_url']) ?>" target="_blank" rel="noopener noreferrer">Onde ler</a><?php endif; ?>
<?php if ($aviso['obra_id']): ?><a href="obra.php?id=<?= (int)$aviso['obra_id'] ?>">Ver <?= h($aviso['obra_titulo']) ?></a><?php endif; ?>
</article>
<?php endforeach; ?>
<?php else: ?><div class="empty">Este artista ainda não publicou avisos.</div><?php endif; ?>
</section>
</main>
</div>
</body>
</html>
