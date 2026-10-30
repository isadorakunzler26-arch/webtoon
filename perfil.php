<?php
// perfil do usuário
require __DIR__ . '/auth.php';
require __DIR__ . '/conexao.php';

$siteName = "Webtoonsz";
$u = requer_login($pdo);
$editar = isset($_GET['editar']) && $_GET['editar'] === '1';
$flash = pegar_flash();
$ehArtista = $u['tipo'] === 'artista';

// (provisório) favoritadas ainda estáticas
$favoritadas = [
    "Nome da obra",
    "A Última Estrela",
    "Entre Dois Mundos",
    "Coração de Papel"
];

function avatar_html(array $u, bool $editar): string
{
    if (!empty($u['foto'])) {
        return '<img id="previewFoto" src="uploads/perfis/' . htmlspecialchars($u['foto']) . '" alt="Foto de perfil">';
    }
    return $editar ? '<span id="previewPlaceholder">✎</span>' : '●';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Meu perfil</title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248, 245, 255, .62);
            --border: rgba(227, 217, 252, .16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);

            background:
                radial-gradient(circle at 8% 15%, rgba(73, 40, 194, .72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191, 64, 250, .32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91, 42, 98, .75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);

            padding: 24px;
        }

        .page {
            width: min(900px, 100%);
            margin: auto;
        }

        .profile-card {
            position: relative;
            min-height: 520px;

            padding: 18px 25px 30px;

            border: 1px solid var(--border);
            border-radius: 20px;

            background: rgba(4, 6, 7, .62);
            box-shadow:
                0 25px 60px rgba(0,0,0,.28),
                inset 0 1px 0 rgba(255,255,255,.04);
        }

      
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .top-button {
            min-width: 72px;
            height: 34px;
            padding: 0 15px;

            border: 0;
            border-radius: 8px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            color: var(--white);
            background: var(--black);

            font-size: .78rem;
            text-decoration: none;
            cursor: pointer;

            transition: .2s ease;
        }

        .top-button:hover {
            background: var(--hyper-magenta);
            transform: translateY(-1px);
        }

        .save-button {
            background: var(--hyper-magenta);
        }

     
        .profile-content {
            padding-top: 25px;
        }

        .profile-main {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 28px;
            align-items: center;
        }

        .avatar-area {
            display: flex;
            justify-content: center;
        }

        .avatar {
            width: 105px;
            height: 105px;

            display: grid;
            place-items: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    145deg,
                    rgba(227,217,252,.68),
                    rgba(91,42,98,.72)
                );

            border: 2px solid rgba(227,217,252,.18);

            color: var(--ultrasonic-blue);
            font-size: 2.2rem;
        }

        .avatar-edit {
            position: relative;
        }

        .avatar-edit .avatar {
            background: rgba(227,217,252,.65);
            color: #777;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 90px 1fr;
            align-items: center;
            gap: 12px;
        }

        .info-row label {
            font-size: .83rem;
            font-weight: 700;
        }

        .info-value {
            min-height: 32px;
            padding: 8px 12px;

            display: flex;
            align-items: center;

            border-radius: 8px;
            background: rgba(227,217,252,.10);

            color: var(--muted);
            font-size: .78rem;
        }

  
        .edit-input,
        .edit-textarea {
            width: 100%;
            min-height: 32px;
            padding: 7px 12px;

            border: 1px solid rgba(227,217,252,.12);
            border-radius: 8px;
            outline: none;

            color: var(--text-light);
            background: rgba(227,217,252,.10);

            font-family: inherit;
            font-size: .78rem;
        }

        .edit-textarea {
            resize: vertical;
            min-height: 48px;
        }

        .edit-input:focus,
        .edit-textarea:focus {
            border-color: var(--hyper-magenta);
            box-shadow: 0 0 0 3px rgba(191,64,250,.10);
        }

        
        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;

            margin-top: 30px;
        }

        .action-button {
            width: min(100%, 430px);
            min-height: 38px;

            border: 0;
            border-radius: 9px;

            color: var(--white);
            background: var(--black);

            font-size: .78rem;
            cursor: pointer;
            text-decoration: none;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: .2s ease;
        }

        .action-button:hover {
            background: var(--velvet-purple);
            transform: translateY(-1px);
        }

        .artist-button {
            background: var(--hyper-magenta);
        }

        .artist-button:hover {
            background: #cf5dff;
        }

     
        .favorites {
            margin-top: 34px;
        }

        .favorites-title {
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);

            font-size: .95rem;
        }

        .favorites-title span {
            color: var(--hyper-magenta);
        }

        .favorite-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;

            margin-top: 14px;
        }

        .favorite {
            text-decoration: none;
            color: var(--text-light);
        }

        .cover {
            height: 125px;

            border: 1px solid rgba(227,217,252,.12);
            border-radius: 10px;

            background:
                linear-gradient(
                    145deg,
                    rgba(73,40,194,.70),
                    rgba(191,64,250,.28),
                    rgba(91,42,98,.75)
                );

            transition: .2s ease;
        }

        .favorite:hover .cover {
            transform: translateY(-3px);
            border-color: rgba(191,64,250,.5);
            box-shadow: 0 10px 25px rgba(191,64,250,.12);
        }

        .favorite-name {
            margin-top: 6px;
            font-size: .72rem;
            font-weight: 700;
        }

        .role {
            color: var(--hyper-magenta);
            font-size: .66rem;
            font-weight: 700;
        }

        @media (max-width: 650px) {
            body {
                padding: 12px;
            }

            .profile-card {
                padding: 15px;
            }

            .profile-main {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .profile-info {
                width: 100%;
            }

            .favorite-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .cover {
                height: 145px;
            }
        }

        @media (max-width: 430px) {
            .info-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .favorite-list {
                gap: 9px;
            }
        }
    
        .avatar { overflow: hidden; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-edit label { cursor: pointer; display: block; }
        .avatar-edit input[type=file] { display: none; }
        .avatar-hint { margin-top: 8px; text-align: center; color: var(--muted); font-size: .66rem; }
        .flash { margin-top: 16px; padding: 10px 14px; border-radius: 9px; font-size: .78rem; }
        .flash.ok { background: rgba(191,64,250,.12); border: 1px solid rgba(191,64,250,.3); color: var(--periwinkle); }
        .flash.erro { background: rgba(255,80,80,.10); border: 1px solid rgba(255,80,80,.3); color: #ffb3b3; }
        .actions form { width: min(100%, 430px); display: flex; }
        .actions form .action-button { width: 100%; }
    </style>
</head>

<body>

<div class="page">

    <main class="profile-card">

        <header class="topbar">

            <a href="home.php" class="top-button">Voltar</a>

            <?php if ($editar): ?>
                <button type="submit" form="formPerfil" class="top-button save-button">Salvar</button>
            <?php else: ?>
                <a href="perfil.php?editar=1" class="top-button">Editar perfil</a>
            <?php endif; ?>

        </header>

        <?php if ($flash): ?>
            <div class="flash <?= $flash['tipo'] === 'ok' ? 'ok' : 'erro' ?>">
                <?= htmlspecialchars($flash['mensagem']) ?>
            </div>
        <?php endif; ?>

        <section class="profile-content">

            <?php if ($editar): ?>
            <form id="formPerfil" action="perfil_salvar.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <?php endif; ?>

            <div class="profile-main">

                <div>
                    <div class="<?= $editar ? 'avatar-area avatar-edit' : 'avatar-area' ?>">
                        <?php if ($editar): ?>
                            <label for="foto" title="Alterar foto">
                                <div class="avatar"><?= avatar_html($u, true) ?></div>
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                        <?php else: ?>
                            <div class="avatar"><?= avatar_html($u, false) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($editar): ?>
                        <p class="avatar-hint">Clique para trocar (JPG, PNG ou WEBP, até 2 MB)</p>
                    <?php endif; ?>
                </div>

                <div class="profile-info">

                    <?php if ($editar): ?>

                        <div class="info-row">
                            <label for="nome">Nome:</label>
                            <input class="edit-input" id="nome" name="nome" type="text" maxlength="100" required
                                   value="<?= htmlspecialchars($u['nome']) ?>">
                        </div>

                        <div class="info-row">
                            <label for="usuario">Usuário:</label>
                            <input class="edit-input" id="usuario" name="usuario" type="text" maxlength="30" required
                                   value="<?= htmlspecialchars($u['usuario']) ?>">
                        </div>

                        <div class="info-row">
                            <label for="biografia">Biografia:</label>
                            <textarea class="edit-textarea" id="biografia" name="biografia" maxlength="300"><?= htmlspecialchars($u['biografia'] ?? '') ?></textarea>
                        </div>

                        <div class="info-row">
                            <label for="email">Email:</label>
                            <input class="edit-input" id="email" name="email" type="email" required
                                   value="<?= htmlspecialchars($u['email']) ?>">
                        </div>

                        <div class="info-row">
                            <label for="senha">Senha:</label>
                            <input class="edit-input" id="senha" name="senha" type="password"
                                   placeholder="Deixe em branco para manter a atual" autocomplete="new-password">
                        </div>

                    <?php else: ?>

                        <div class="info-row">
                            <label>Nome:</label>
                            <div class="info-value"><?= htmlspecialchars($u['nome']) ?></div>
                        </div>

                        <div class="info-row">
                            <label>Usuário:</label>
                            <div class="info-value"><?= htmlspecialchars($u['usuario']) ?></div>
                        </div>

                        <div class="info-row">
                            <label>Biografia:</label>
                            <div class="info-value"><?= htmlspecialchars($u['biografia'] ?: 'Sem biografia ainda.') ?></div>
                        </div>

                        <div class="info-row">
                            <label>Email:</label>
                            <div class="info-value"><?= htmlspecialchars($u['email']) ?></div>
                        </div>

                        <div class="info-row">
                            <label>Conta:</label>
                            <div class="info-value"><?= $ehArtista ? 'Artista' : 'Leitor' ?></div>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <?php if ($editar): ?>
            </form>
            <?php endif; ?>


            <div class="actions">

                <?php if ($ehArtista): ?>
                    <a href="obra_artista.php" class="action-button artist-button">
                        Ir para minha área de artista
                    </a>
                <?php else: ?>
                    <form action="evoluir_artista.php" method="post"
                          onsubmit="return confirm('Evoluir sua conta para artista?');">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <button type="submit" class="action-button artist-button">
                            Evoluir a conta para artista
                        </button>
                    </form>
                <?php endif; ?>

                <a href="logout.php" class="action-button">Sair</a>

            </div>


            <section class="favorites">

                <h2 class="favorites-title">Favoritadas</h2>

                <div class="favorite-list">

                    <?php foreach ($favoritadas as $obra): ?>
                        <a href="#" class="favorite">
                            <div class="cover"></div>
                            <p class="favorite-name"><?= htmlspecialchars($obra) ?></p>
                            <p class="role">@nome_artista</p>
                        </a>
                    <?php endforeach; ?>

                </div>

            </section>

        </section>

    </main>

</div>

<?php if ($editar): ?>
<script>
    // pré-visualização da nova foto antes de salvar
    document.getElementById('foto').addEventListener('change', function () {
        const arquivo = this.files[0];
        if (!arquivo) return;

        const avatar = document.querySelector('.avatar');
        let img = document.getElementById('previewFoto');

        if (!img) {
            avatar.innerHTML = '';
            img = document.createElement('img');
            img.id = 'previewFoto';
            avatar.appendChild(img);
        }
        img.src = URL.createObjectURL(arquivo);
    });
</script>
<?php endif; ?>

</body>
</html>
