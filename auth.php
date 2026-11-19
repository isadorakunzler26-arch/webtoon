<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function redirecionar(string $destino): void {
    header('Location: ' . $destino);
    exit;
}

function flash(string $tipo, ?string $mensagem = null): ?string {
    if ($mensagem !== null) {
        $_SESSION['flash'][$tipo] = $mensagem;
        return null;
    }
    $valor = $_SESSION['flash'][$tipo] ?? null;
    unset($_SESSION['flash'][$tipo]);
    return $valor;
}

function requer_login(PDO $pdo): array {
    if (empty($_SESSION['usuario_id'])) {
        flash('erro', 'Entre na sua conta para acessar essa funcionalidade.');
        redirecionar('login.php');
    }

    $stmt = $pdo->prepare('SELECT id, nome, usuario, email, biografia, foto, tipo FROM usuarios WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $_SESSION = [];
        session_destroy();
        redirecionar('login.php');
    }

    return $usuario;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_validar(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }
}

function h($valor): string {
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}
