<?php
        session_start();
        require_once __DIR__ . '/auth.php';
        require_once __DIR__ . '/conexao.php';

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            exit('Obra não encontrada.');
        }

        $stmt = $pdo->prepare("
            SELECT o.*, u.id AS artista_id, u.nome AS artista_nome, u.usuario AS artista_usuario,
                u.biografia AS artista_biografia, u.foto AS artista_foto
            FROM obras o
            INNER JOIN usuarios u ON u.id = o.artista_id
            WHERE o.id = ? AND o.publicada = 1
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $obra = $stmt->fetch();

        if (!$obra) {
            http_response_code(404);
            exit('Obra não encontrada.');
        }

        $stmt = $pdo->prepare("
            SELECT id, titulo, mensagem, tipo, data_evento, plataforma_url, criado_em
            FROM atualizacoes
            WHERE obra_id = ? AND publicada = 1
            ORDER BY criado_em DESC
        ");
        $stmt->execute([$id]);
        $atualizacoes = $stmt->fetchAll();

        $logado = isset($_SESSION['usuario_id']);
        $ehLeitor = false;
        $favoritada = false;
        if ($logado) {
            $stmt = $pdo->prepare('SELECT tipo FROM usuarios WHERE id = ? LIMIT 1');
            $stmt->execute([(int)$_SESSION['usuario_id']]);
            $ehLeitor = $stmt->fetchColumn() === 'leitor';
            if ($ehLeitor) {
                $stmt = $pdo->prepare('SELECT 1 FROM favoritos WHERE usuario_id = ? AND obra_id = ?');
                $stmt->execute([(int)$_SESSION['usuario_id'], $id]);
                $favoritada = (bool)$stmt->fetchColumn();
            }
        }
        $ok = flash('ok');
        $erro = flash('erro');
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= h($obra['titulo']) ?> — Webtoonsz</title>
        <style>
        :root{--lav:#E3D9FC;--pink:#BF40FA;--white:#F8F5FF;--muted:rgba(248,245,255,.68);--border:rgba(227,217,252,.16)}
        *{box-sizing:border-box;margin:0;padding:0}body{min-height:100vh;padding:24px;font-family:Arial,sans-serif;color:var(--white);background:radial-gradient(circle at 8% 15%,rgba(73,40,194,.65),transparent 30%),radial-gradient(circle at 92% 20%,rgba(191,64,250,.28),transparent 28%),linear-gradient(135deg,#12091A,#24102F 48%,#08090C)}.page{width:min(950px,100%);margin:auto}.header{display:flex;gap:10px;padding:14px 0 18px;border-bottom:1px solid var(--border)}.header a{color:var(--white);text-decoration:none;padding:10px 13px;border:1px solid var(--border);border-radius:9px}.header a:first-child{margin-right:auto;border:0;font-weight:bold}.detail{display:grid;grid-template-columns:220px 1fr;gap:25px;margin:35px 0}.cover{width:100%;height:300px;border-radius:14px;object-fit:cover;background:linear-gradient(145deg,rgba(73,40,194,.8),rgba(191,64,250,.35))}.detail h1{font-size:2rem;margin:4px 0 12px}.muted{color:var(--muted);line-height:1.6}.artist{display:inline-block;color:var(--lav);margin:12px 0}.section{margin:30px 0}.section h2{margin-bottom:15px}.update{padding:17px;margin-bottom:12px;border:1px solid var(--border);border-radius:12px;background:rgba(4,6,7,.55)}.update small{color:var(--pink)}.update h3{margin:7px 0}.update p{color:var(--muted);line-height:1.5}.update a{display:inline-block;margin-top:9px;color:var(--lav)}.notice{padding:15px;border:1px solid var(--border);border-radius:12px;color:var(--muted)}@media(max-width:650px){body{padding:14px}.detail{grid-template-columns:1fr}.cover{width:180px;height:240px}}
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
        <?php if ($ok): ?><p class="notice" style="margin-bottom:16px"><?= h($ok) ?></p><?php endif; ?>
        <?php if ($erro): ?><p class="notice" style="margin-bottom:16px"><?= h($erro) ?></p><?php endif; ?>
        <section class="detail">
        <div>
        <?php if ($obra['capa']): ?><img class="cover" src="<?= h($obra['capa']) ?>" alt="Capa de <?= h($obra['titulo']) ?>"><?php else: ?><div class="cover"></div><?php endif; ?>
        </div>
        <div>
        <h1><?= h($obra['titulo']) ?></h1>
        <p class="muted"><?= nl2br(h($obra['descricao'])) ?></p>
        <a class="artist" href="artista.php?id=<?= (int)$obra['artista_id'] ?>">Por @<?= h($obra['artista_usuario']) ?> · Ver perfil do artista</a>
        <p class="muted">Status: <?= h(ucfirst(str_replace('_',' ',$obra['status'] ?? 'em andamento'))) ?></p>
        <?php if (!empty($obra['local_leitura'])): ?><p class="muted" style="margin-top:10px">Onde ler: <a style="color:var(--lav)" href="<?= h($obra['local_leitura']) ?>" target="_blank" rel="noopener noreferrer"><?= h($obra['local_leitura']) ?></a></p><?php endif; ?>
        <?php if ($ehLeitor): ?>
        <form method="post" action="favoritar.php" style="margin-top:16px">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="obra_id" value="<?= (int)$obra['id'] ?>">
        <input type="hidden" name="acao" value="<?= $favoritada ? 'remover' : 'adicionar' ?>">
        <input type="hidden" name="voltar" value="obra.php?id=<?= (int)$obra['id'] ?>">
        <button type="submit" style="padding:10px 15px;border:0;border-radius:9px;background:#BF40FA;color:white;cursor:pointer"><?= $favoritada ? 'Remover dos favoritos' : '★ Favoritar obra' ?></button>
        </form>
        <?php elseif (!$logado): ?>
        <p class="muted" style="margin-top:16px"><a style="color:var(--lav)" href="login.php">Entre na sua conta</a> para favoritar esta obra.</p>
        <?php endif; ?>
        </div>
        </section>

        <section class="section">
        <h2>Avisos e atualizações desta obra</h2>
        <?php if ($atualizacoes): ?>
        <?php foreach ($atualizacoes as $item): ?>
        <article class="update">
        <small><?= h(ucfirst(str_replace('_',' ',$item['tipo']))) ?> · <?= h(date('d/m/Y H:i', strtotime($item['criado_em']))) ?></small>
        <h3><?= h($item['titulo']) ?></h3>
        <p><?= nl2br(h($item['mensagem'])) ?></p>
        <?php if ($item['data_evento']): ?><p><strong>Data informada:</strong> <?= h(date('d/m/Y', strtotime($item['data_evento']))) ?></p><?php endif; ?>
        <?php if ($item['plataforma_url']): ?><a href="<?= h($item['plataforma_url']) ?>" target="_blank" rel="noopener noreferrer">Acessar plataforma de leitura</a><?php endif; ?>
        </article>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="notice">O artista ainda não publicou avisos para esta obra.</div>
        <?php endif; ?>
        </section>
        </main>
        </div>
        </body>
        </html>
