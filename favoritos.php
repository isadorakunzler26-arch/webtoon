<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);
$stmt = $pdo->prepare("
    SELECT o.id, o.titulo, o.descricao, o.capa, o.status, u.usuario AS artista
    FROM favoritos f
    INNER JOIN obras o ON o.id = f.obra_id
    INNER JOIN usuarios u ON u.id = o.artista_id
    WHERE f.usuario_id = ? AND o.publicada = 1
    ORDER BY f.criado_em DESC
");
$stmt->execute([$u['id']]);
$obras = $stmt->fetchAll();
$ok = flash('ok');
$erro = flash('erro');
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Minhas favoritas — Webtoonsz</title>
<style>
:root{--pink:#BF40FA;--light:#F8F5FF;--muted:#c9bfd7;--border:#e3d9fc2b}*{box-sizing:border-box}body{margin:0;min-height:100vh;padding:24px;font-family:Arial,sans-serif;color:var(--light);background:radial-gradient(circle at 8% 15%,#4928c2b8,transparent 30%),radial-gradient(circle at 92% 20%,#bf40fa52,transparent 28%),linear-gradient(135deg,#12091a,#24102f 48%,#08090c)}.page{max-width:1050px;margin:auto}.top{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:28px}.btn{padding:11px 15px;border:1px solid var(--border);border-radius:9px;background:#08090c;color:white;text-decoration:none;cursor:pointer}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(175px,1fr));gap:16px}.card{padding:12px;border:1px solid var(--border);border-radius:13px;background:#04060799;color:white;text-decoration:none}.cover{height:200px;border-radius:9px;background:linear-gradient(145deg,#4928c2,#bf40fa66);object-fit:cover;width:100%}.card h2{font-size:.95rem;margin:9px 0 5px}.muted{color:var(--muted);font-size:.8rem}.remove{margin-top:10px;width:100%;padding:9px;border:0;border-radius:8px;background:#5b2a62;color:white;cursor:pointer}.empty,.notice{padding:18px;border:1px dashed var(--border);border-radius:10px;color:var(--muted)}.notice{border-style:solid;margin-bottom:14px}@media(max-width:550px){body{padding:13px}}
</style></head><body><main class="page"><header class="top"><div><h1>Minhas obras favoritas</h1><p class="muted">Obras que você salvou para acompanhar.</p></div><a class="btn" href="perfil.php">Voltar ao perfil</a></header>
<?php if($ok): ?><p class="notice"><?= h($ok) ?></p><?php endif; ?><?php if($erro): ?><p class="notice"><?= h($erro) ?></p><?php endif; ?>
<?php if($obras): ?><div class="grid"><?php foreach($obras as $obra): ?><article class="card"><?php if($obra['capa']): ?><a href="obra.php?id=<?= (int)$obra['id'] ?>"><img class="cover" src="<?= h($obra['capa']) ?>" alt="Capa de <?= h($obra['titulo']) ?>"></a><?php else: ?><a href="obra.php?id=<?= (int)$obra['id'] ?>"><div class="cover"></div></a><?php endif; ?><h2><?= h($obra['titulo']) ?></h2><p class="muted">por @<?= h($obra['artista']) ?></p><form method="post" action="favoritar.php"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="obra_id" value="<?= (int)$obra['id'] ?>"><input type="hidden" name="acao" value="remover"><input type="hidden" name="voltar" value="favoritos.php"><button class="remove" type="submit">Remover dos favoritos</button></form></article><?php endforeach; ?></div><?php else: ?><div class="empty">Você ainda não favoritou nenhuma obra. <a style="color:var(--pink)" href="home.php">Explorar obras</a></div><?php endif; ?>
</main></body></html>
