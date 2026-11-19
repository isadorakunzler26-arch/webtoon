<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$u = requer_login($pdo);
$stmt = $pdo->prepare("
    SELECT DISTINCT a.id, a.titulo, a.mensagem, a.tipo, a.data_evento, a.plataforma_url, a.criado_em,
           o.id AS obra_id, o.titulo AS obra_titulo, ar.usuario AS artista
    FROM atualizacoes a
    INNER JOIN usuarios ar ON ar.id = a.artista_id
    LEFT JOIN obras o ON o.id = a.obra_id
    WHERE a.publicada = 1
      AND (
        EXISTS (SELECT 1 FROM favoritos f WHERE f.usuario_id = ? AND f.obra_id = a.obra_id)
        OR EXISTS (SELECT 1 FROM seguidores s WHERE s.seguidor_id = ? AND s.artista_id = a.artista_id)
      )
    ORDER BY a.criado_em DESC
");
$stmt->execute([$u['id'], $u['id']]);
$lista = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Atualizações que acompanho — Webtoonsz</title>
<style>
:root{--pink:#BF40FA;--light:#F8F5FF;--muted:#c9bfd7;--border:#e3d9fc2b}*{box-sizing:border-box}body{margin:0;min-height:100vh;padding:24px;font-family:Arial,sans-serif;color:var(--light);background:radial-gradient(circle at 8% 15%,#4928c2b8,transparent 30%),radial-gradient(circle at 92% 20%,#bf40fa52,transparent 28%),linear-gradient(135deg,#12091a,#24102f 48%,#08090c)}.page{max-width:850px;margin:auto}.top{display:flex;justify-content:space-between;gap:12px;margin-bottom:25px}.btn{padding:11px 15px;border:1px solid var(--border);border-radius:9px;background:#08090c;color:white;text-decoration:none}.item{padding:17px;margin:12px 0;border:1px solid var(--border);border-radius:13px;background:#04060799}.item small{color:var(--pink)}.item h2{font-size:1.05rem;margin:8px 0}.item p{color:var(--muted);line-height:1.5}.item a{display:inline-block;margin-top:10px;color:#E3D9FC}.empty{padding:20px;border:1px dashed var(--border);border-radius:10px;color:var(--muted)}
</style></head><body><main class="page"><header class="top"><div><h1>Atualizações que acompanho</h1><p style="color:var(--muted);margin-top:6px">Avisos de obras favoritas e artistas seguidos.</p></div><a class="btn" href="perfil.php">Voltar</a></header>
<?php if($lista): ?><?php foreach($lista as $item): ?><article class="item"><small>@<?= h($item['artista']) ?> · <?= h(ucfirst(str_replace('_',' ',$item['tipo']))) ?> · <?= h(date('d/m/Y H:i',strtotime($item['criado_em']))) ?></small><h2><?= h($item['titulo']) ?></h2><p><?= nl2br(h($item['mensagem'])) ?></p><?php if($item['data_evento']): ?><p><strong>Data:</strong> <?= h(date('d/m/Y',strtotime($item['data_evento']))) ?></p><?php endif; ?><?php if($item['obra_id']): ?><a href="obra.php?id=<?= (int)$item['obra_id'] ?>">Ver <?= h($item['obra_titulo']) ?></a><?php endif; ?><?php if($item['plataforma_url']): ?><a href="<?= h($item['plataforma_url']) ?>" target="_blank" rel="noopener noreferrer"> · Onde ler</a><?php endif; ?></article><?php endforeach; ?><?php else: ?><div class="empty">Ainda não há atualizações das obras favoritas ou dos artistas que você segue.</div><?php endif; ?>
</main></body></html>
