
<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';


$u = requer_login($pdo);


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('painel_artista.php');
}


csrf_validar();


if ($u['tipo'] !== 'artista') {
    redirecionar('perfil.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);


$s = $pdo->prepare("
    DELETE FROM atualizacoes
    WHERE id = ? AND artista_id = ?
");

$s->execute([$id, $u['id']]);


flash(
    'ok',
    $s->rowCount() ? 'Aviso excluído.' : 'Aviso não encontrado.'
);

redirecionar('meus_avisos.php');
