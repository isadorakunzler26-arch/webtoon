<?php
//  atualizações da obra
$siteName = "Webtoonsz";

$atualizacoes = [
    [
        "autor" => "@nome_Autor",
        "texto" => "Nessa semana os capítulos novos serão postados na segunda e quarta-feira."
    ],
    [
        "autor" => "@nome_Autor",
        "texto" => "Texto texto texto"
    ],
    [
        "autor" => "@nome_Autor",
        "texto" => ""
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> — Adicionar atualizações</title>

    <style>
        :root {
            --periwinkle: #E3D9FC;
            --hyper-magenta: #BF40FA;
            --ultrasonic-blue: #4928C2;
            --velvet-purple: #5B2A62;
            --black: #040607;
            --white: #FFFFFF;
            --text-light: #F8F5FF;
            --muted: rgba(248,245,255,.62);
            --border: rgba(227,217,252,.16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            padding: 25px;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-light);

            background:
                radial-gradient(circle at 8% 15%, rgba(73,40,194,.72), transparent 30%),
                radial-gradient(circle at 92% 20%, rgba(191,64,250,.32), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(91,42,98,.75), transparent 38%),
                linear-gradient(135deg, #12091A 0%, #24102F 48%, #08090C 100%);
        }

        .page {
            width: min(900px, 100%);
            margin: auto;
        }

        .card {
            padding: 18px 24px 24px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: rgba(4,6,7,.64);
            box-shadow: 0 25px 60px rgba(0,0,0,.28);
        }

       
        .topbar {
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .button {
            min-width: 75px;
            height: 35px;
            padding: 0 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 8px;

            color: var(--white);
            background: var(--black);

            font-size: .78rem;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .button:hover {
            background: var(--velvet-purple);
            transform: translateY(-1px);
        }

        .content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 290px;
            gap: 18px;
            padding-top: 25px;
        }

       
        .updates-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .update {
            position: relative;
            min-height: 72px;
            padding: 12px 35px 12px 13px;

            border-radius: 10px;
            background: rgba(227,217,252,.14);
            border: 1px solid rgba(227,217,252,.08);
        }

        .update:hover {
            border-color: rgba(191,64,250,.35);
        }

        .update-author {
            display: block;
            margin-bottom: 5px;

            color: var(--text-light);
            font-size: .65rem;
            font-weight: 700;
        }

        .update p {
            color: var(--muted);
            font-size: .73rem;
            line-height: 1.4;
        }

        .delete {
            position: absolute;
            top: 11px;
            right: 10px;

            border: 0;
            background: transparent;

            color: rgba(248,245,255,.55);
            cursor: pointer;
            font-size: .85rem;
        }

        .delete:hover {
            color: var(--hyper-magenta);
        }

        .empty-update {
            height: 42px;
            border-radius: 9px;
            background: linear-gradient(
                to bottom,
                rgba(227,217,252,.12),
                rgba(227,217,252,.04)
            );
        }

       
        .new-update {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .new-update textarea {
            width: 100%;
            height: 135px;
            padding: 12px;

            resize: none;
            outline: none;

            border: 1px solid rgba(227,217,252,.20);
            border-radius: 10px;

            background: rgba(4,6,7,.55);
            color: var(--text-light);

            font-family: inherit;
            font-size: .78rem;
            line-height: 1.45;
        }

        .new-update textarea::placeholder {
            color: rgba(248,245,255,.40);
        }

        .new-update textarea:focus {
            border-color: var(--hyper-magenta);
            box-shadow: 0 0 0 3px rgba(191,64,250,.10);
        }

        .publish {
            width: 100%;
            height: 38px;

            border: 0;
            border-radius: 9px;

            background: var(--hyper-magenta);
            color: var(--white);

            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;

            transition: .2s ease;
        }

        .publish:hover {
            background: #cf5dff;
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(191,64,250,.25);
        }

        .hint {
            color: rgba(248,245,255,.4);
            font-size: .66rem;
            line-height: 1.4;
        }

        .success {
            display: none;
            margin-bottom: 10px;
            padding: 9px 11px;

            border-radius: 8px;
            background: rgba(191,64,250,.12);
            border: 1px solid rgba(191,64,250,.25);

            color: var(--periwinkle);
            font-size: .7rem;
        }

        @media (max-width: 700px) {
            body {
                padding: 13px;
            }

            .card {
                padding: 15px;
            }

            .content {
                grid-template-columns: 1fr;
            }

            .new-update {
                order: -1;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <main class="card">

        <header class="topbar">
            <a href="obra_artista.php" class="button">
                Voltar
            </a>
        </header>


        <section class="content">

           
            <div class="updates-list" id="updatesList">

                <?php foreach ($atualizacoes as $index => $atualizacao): ?>

                    <?php if ($atualizacao['texto'] !== ''): ?>

                        <article class="update">

                            <span class="update-author">
                                <?= htmlspecialchars($atualizacao['autor']) ?>
                            </span>

                            <p>
                                <?= htmlspecialchars($atualizacao['texto']) ?>
                            </p>

                            <button
                                type="button"
                                class="delete"
                                title="Excluir atualização"
                                onclick="excluirAtualizacao(this)"
                            >
                                🗑
                            </button>

                        </article>

                    <?php endif; ?>

                <?php endforeach; ?>

                <div class="empty-update"></div>

            </div>


           
            <form class="new-update" onsubmit="publicarAtualizacao(event)">

                <div class="success" id="success">
                    Atualização publicada com sucesso!
                </div>

                <textarea
                    id="textoAtualizacao"
                    placeholder="Digite aqui...."
                    maxlength="500"
                    required
                ></textarea>

                <span class="hint">
                    Publique avisos sobre capítulos, pausas, datas de lançamento
                    ou outras novidades da obra.
                </span>

                <button type="submit" class="publish">
                    publicar
                </button>

            </form>

        </section>

    </main>

</div>


<script>
    function publicarAtualizacao(event) {
        event.preventDefault();

        const campo = document.getElementById('textoAtualizacao');
        const lista = document.getElementById('updatesList');
        const mensagem = document.getElementById('success');

        const texto = campo.value.trim();

        if (!texto) {
            return;
        }

        const novaAtualizacao = document.createElement('article');
        novaAtualizacao.className = 'update';

        novaAtualizacao.innerHTML = `
            <span class="update-author">@nome_Autor</span>
            <p>${escapeHTML(texto)}</p>
            <button
                type="button"
                class="delete"
                title="Excluir atualização"
                onclick="excluirAtualizacao(this)"
            >
                🗑
            </button>
        `;

        const vazio = lista.querySelector('.empty-update');

        if (vazio) {
            vazio.remove();
        }

        lista.prepend(novaAtualizacao);

        campo.value = '';

        mensagem.style.display = 'block';

        setTimeout(function () {
            mensagem.style.display = 'none';
        }, 2500);
    }


    function excluirAtualizacao(botao) {
        const atualizacao = botao.closest('.update');

        if (atualizacao) {
            atualizacao.remove();
        }
    }


    function escapeHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }
</script>

</body>
</html>
