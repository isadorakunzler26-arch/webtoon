    <?php
    require __DIR__ . '/auth.php';
    require __DIR__ . '/conexao.php';

    $u = requer_login($pdo);
    if ($u['tipo'] !== 'leitor') {
        flash('erro', 'Esta funcionalidade está disponível para contas de leitor.');
        redirecionar('perfil.php');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('home.php');
    csrf_validar();

    $obraId = filter_input(INPUT_POST, 'obra_id', FILTER_VALIDATE_INT);
    $acao = $_POST['acao'] ?? 'adicionar';
    if (!$obraId || !in_array($acao, ['adicionar', 'remover'], true)) {
        flash('erro', 'Obra inválida.');
        redirecionar('home.php');
    }

    $stmt = $pdo->prepare('SELECT id FROM obras WHERE id = ? AND publicada = 1 LIMIT 1');
    $stmt->execute([$obraId]);
    if (!$stmt->fetch()) {
        flash('erro', 'Obra não encontrada.');
        redirecionar('home.php');
    }

    if ($acao === 'adicionar') {
        $stmt = $pdo->prepare('INSERT IGNORE INTO favoritos (usuario_id, obra_id) VALUES (?, ?)');
        $stmt->execute([$u['id'], $obraId]);
        flash('ok', 'Obra adicionada aos favoritos.');
    } else {
        $stmt = $pdo->prepare('DELETE FROM favoritos WHERE usuario_id = ? AND obra_id = ?');
        $stmt->execute([$u['id'], $obraId]);
        flash('ok', 'Obra removida dos favoritos.');
    }

    $voltar = $_POST['voltar'] ?? 'obra.php?id=' . $obraId;
    if (!preg_match('/^(obra\.php\?id=\d+|favoritos\.php|home\.php)$/', $voltar)) $voltar = 'obra.php?id=' . $obraId;
    redirecionar($voltar);
