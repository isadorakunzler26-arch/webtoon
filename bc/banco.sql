<<<<<<< HEAD
CREATE DATABASE IF NOT EXISTS webtoonsz
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE webtoonsz;

-- garante que os acentos sejam interpretados como utf8mb4 na importação
SET NAMES utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notificacoes;
DROP TABLE IF EXISTS atualizacoes;
DROP TABLE IF EXISTS comentarios;
DROP TABLE IF EXISTS avaliacoes;
DROP TABLE IF EXISTS favoritos;
DROP TABLE IF EXISTS seguidores;
DROP TABLE IF EXISTS capitulos;
DROP TABLE IF EXISTS obra_genero;
DROP TABLE IF EXISTS generos;
DROP TABLE IF EXISTS codigos_email;
DROP TABLE IF EXISTS obras;
DROP TABLE IF EXISTS avisos;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(30) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    biografia VARCHAR(300) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    tipo ENUM('usuario', 'artista', 'admin') NOT NULL DEFAULT 'usuario',
    email_verificado TINYINT(1) NOT NULL DEFAULT 0,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_usuario (usuario),
    UNIQUE KEY uq_usuarios_email (email),
    KEY idx_usuarios_tipo (tipo),
    KEY idx_usuarios_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE generos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(80) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uq_generos_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE obras (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    artista_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT DEFAULT NULL,
    capa VARCHAR(500) DEFAULT NULL,
    status ENUM('em_andamento', 'concluida', 'pausada') NOT NULL DEFAULT 'em_andamento',
    classificacao ENUM('livre', '10', '12', '14', '16', '18') NOT NULL DEFAULT 'livre',
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    publicada TINYINT(1) NOT NULL DEFAULT 1,
    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_obras_artista (artista_id),
    KEY idx_obras_titulo (titulo),
    KEY idx_obras_publicada (publicada),
    CONSTRAINT fk_obras_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE obra_genero (
    obra_id INT UNSIGNED NOT NULL,
    genero_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (obra_id, genero_id),
    CONSTRAINT fk_obra_genero_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE,
    CONSTRAINT fk_obra_genero_genero FOREIGN KEY (genero_id) REFERENCES generos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE capitulos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    obra_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    numero DECIMAL(7,2) NOT NULL,
    conteudo LONGTEXT DEFAULT NULL,
    arquivo_pdf VARCHAR(500) DEFAULT NULL,
    publicado TINYINT(1) NOT NULL DEFAULT 1,
    publicado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_capitulo_numero (obra_id, numero),
    KEY idx_capitulos_obra (obra_id),
    CONSTRAINT fk_capitulos_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favoritos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_favorito_usuario_obra (usuario_id, obra_id),
    KEY idx_favoritos_obra (obra_id),
    CONSTRAINT fk_favoritos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_favoritos_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE seguidores (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    seguidor_id INT UNSIGNED NOT NULL,
    artista_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_seguidor_artista (seguidor_id, artista_id),
    KEY idx_seguidores_artista (artista_id),
    CONSTRAINT fk_seguidores_usuario FOREIGN KEY (seguidor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_seguidores_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comentarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    obra_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    capitulo_id INT UNSIGNED DEFAULT NULL,
    comentario TEXT NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_comentarios_obra (obra_id),
    KEY idx_comentarios_usuario (usuario_id),
    KEY idx_comentarios_capitulo (capitulo_id),
    CONSTRAINT fk_comentarios_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE,
    CONSTRAINT fk_comentarios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_comentarios_capitulo FOREIGN KEY (capitulo_id) REFERENCES capitulos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE avaliacoes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    obra_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    nota TINYINT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_avaliacao_usuario_obra (usuario_id, obra_id),
    KEY idx_avaliacoes_obra (obra_id),
    CONSTRAINT chk_avaliacao_nota CHECK (nota BETWEEN 1 AND 5),
    CONSTRAINT fk_avaliacoes_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE,
    CONSTRAINT fk_avaliacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE atualizacoes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    artista_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED DEFAULT NULL,
    titulo VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    imagem VARCHAR(500) DEFAULT NULL,
    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_atualizacoes_artista (artista_id),
    KEY idx_atualizacoes_obra (obra_id),
    CONSTRAINT fk_atualizacoes_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_atualizacoes_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notificacoes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    remetente_id INT UNSIGNED DEFAULT NULL,
    obra_id INT UNSIGNED DEFAULT NULL,
    tipo VARCHAR(50) NOT NULL,
    mensagem VARCHAR(500) NOT NULL,
    link VARCHAR(500) DEFAULT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_notificacoes_usuario_lida (usuario_id, lida),
    CONSTRAINT fk_notificacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_notificacoes_remetente FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_notificacoes_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE codigos_email (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    finalidade ENUM('verificar_email', 'recuperar_senha') NOT NULL,
    codigo_hash VARCHAR(255) NOT NULL,
    expira_em DATETIME NOT NULL,
    usado_em DATETIME DEFAULT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_codigos_usuario_finalidade (usuario_id, finalidade),
    CONSTRAINT fk_codigos_email_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE avisos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(500) DEFAULT NULL,
    link VARCHAR(500) DEFAULT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    inicio_em DATETIME DEFAULT NULL,
    fim_em DATETIME DEFAULT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_avisos_ativo_periodo (ativo, inicio_em, fim_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO generos (nome) VALUES
('Ação'),
('Aventura'),
('Comédia'),
('Drama'),
('Fantasia'),
('Ficção científica'),
('Mistério'),
('Romance'),
('Suspense'),
('Terror'),
('Sobrenatural'),
('Slice of Life'),
('Isekai'),
('Psicológico'),
('Esporte'),
('Histórico');

-- ============================================================
-- Dados de exemplo
-- Senha de todos os usuários de teste: senha123
-- ============================================================

INSERT INTO usuarios (nome, usuario, email, senha_hash, biografia, tipo, email_verificado) VALUES
('Luna Andrade', 'luna_art', 'luna@webtoonsz.test', '$2y$10$nU8QcTfpd7EWltS4fZEkWOhL9Wj7.uoVdU6MmGlnJ5otKVV6jCehy', 'Ilustradora de fantasia. Publico capítulos novos toda segunda-feira.', 'artista', 1),
('Mika Tanaka', 'mika_draws', 'mika@webtoonsz.test', '$2y$10$nU8QcTfpd7EWltS4fZEkWOhL9Wj7.uoVdU6MmGlnJ5otKVV6jCehy', 'Desenho histórias sobre escolhas, amizade e mundos conectados.', 'artista', 1),
('Nina Costa', 'nina_comics', 'nina@webtoonsz.test', '$2y$10$nU8QcTfpd7EWltS4fZEkWOhL9Wj7.uoVdU6MmGlnJ5otKVV6jCehy', 'Romance e cotidiano, com muito café no meio.', 'artista', 1),
('Leitor Demo', 'leitor_demo', 'leitor@webtoonsz.test', '$2y$10$nU8QcTfpd7EWltS4fZEkWOhL9Wj7.uoVdU6MmGlnJ5otKVV6jCehy', 'Só leio, mas leio tudo.', 'usuario', 1);

SET @luna = (SELECT id FROM usuarios WHERE usuario = 'luna_art');
SET @mika = (SELECT id FROM usuarios WHERE usuario = 'mika_draws');
SET @nina = (SELECT id FROM usuarios WHERE usuario = 'nina_comics');

INSERT INTO obras (artista_id, titulo, descricao, status, classificacao, destaque) VALUES
(@luna, 'A Última Estrela', 'Uma aventura fantástica em busca de uma estrela perdida, capaz de reacender a magia do mundo. Acompanhe a jornada de Iris e seus companheiros por reinos esquecidos.', 'em_andamento', '12', 1),
(@luna, 'Além do Horizonte', 'Uma jornada por terras desconhecidas, onde cada mapa esconde um novo mistério e cada aliado pode ser outra coisa.', 'pausada', '14', 0),
(@mika, 'Entre Dois Mundos', 'Uma história sobre escolhas, amizade e duas realidades conectadas por um portal que ninguém deveria ter aberto.', 'em_andamento', '10', 1),
(@mika, 'Fragmentos', 'Memórias, encontros e pequenos acontecimentos que mudam uma vida para sempre.', 'em_andamento', 'livre', 0),
(@nina, 'Coração de Papel', 'Romance e cotidiano acompanhando uma nova descoberta a cada capítulo.', 'concluida', 'livre', 0),
(@nina, 'Café das Seis', 'Encontros improváveis numa cafeteria que só abre ao amanhecer.', 'em_andamento', '10', 0);

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'A Última Estrela' AND g.nome IN ('Fantasia', 'Aventura', 'Ação');

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'Além do Horizonte' AND g.nome IN ('Aventura', 'Mistério', 'Ficção científica');

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'Entre Dois Mundos' AND g.nome IN ('Fantasia', 'Drama', 'Isekai');

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'Fragmentos' AND g.nome IN ('Drama', 'Slice of Life', 'Psicológico');

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'Coração de Papel' AND g.nome IN ('Romance', 'Slice of Life');

INSERT INTO obra_genero (obra_id, genero_id)
SELECT o.id, g.id FROM obras o, generos g
WHERE o.titulo = 'Café das Seis' AND g.nome IN ('Romance', 'Comédia', 'Slice of Life');

-- avisos e atualizações publicados pelos artistas
INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Calendário de publicação',
       'Os capítulos novos de A Última Estrela saem toda segunda e quarta-feira. Datas de lançamento especiais são avisadas aqui.',
       DATE_SUB(NOW(), INTERVAL 1 DAY)
FROM obras o WHERE o.titulo = 'A Última Estrela';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Novo capítulo disponível',
       'O capítulo 12 já está no ar. Leia também pelo aplicativo oficial da plataforma.',
       DATE_SUB(NOW(), INTERVAL 2 DAY)
FROM obras o WHERE o.titulo = 'A Última Estrela';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Pausa de duas semanas',
       'Entre Dois Mundos entra em pausa por duas semanas para o retrabalho dos capítulos 18 e 19. Voltamos com novidades.',
       DATE_SUB(NOW(), INTERVAL 3 DAY)
FROM obras o WHERE o.titulo = 'Entre Dois Mundos';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Aviso de pausa prolongada',
       'Além do Horizonte segue em pausa. O retorno está previsto para o início do próximo semestre.',
       DATE_SUB(NOW(), INTERVAL 6 DAY)
FROM obras o WHERE o.titulo = 'Além do Horizonte';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Obra concluída',
       'O último capítulo de Coração de Papel foi publicado. Obrigada por acompanhar até aqui!',
       DATE_SUB(NOW(), INTERVAL 9 DAY)
FROM obras o WHERE o.titulo = 'Coração de Papel';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
SELECT o.artista_id, o.id, 'Estreia hoje',
       'Café das Seis estreia oficialmente com os três primeiros capítulos disponíveis.',
       DATE_SUB(NOW(), INTERVAL 11 DAY)
FROM obras o WHERE o.titulo = 'Café das Seis';

INSERT INTO atualizacoes (artista_id, obra_id, titulo, mensagem, criada_em)
VALUES (@nina, NULL, 'Novas plataformas de leitura',
        'A partir do mês que vem, minhas obras também estarão disponíveis em outras plataformas de leitura.',
        DATE_SUB(NOW(), INTERVAL 5 DAY));

=======
CREATE DATABASE IF NOT EXISTS webtoonsz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webtoonsz;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    biografia VARCHAR(300) NULL,
    foto VARCHAR(255) NULL,
    tipo ENUM('leitor','artista') NOT NULL DEFAULT 'leitor',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS obras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    artista_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    sinopse TEXT NOT NULL,
    genero VARCHAR(80) NULL,
    capa VARCHAR(255) NULL,
    status ENUM('em_andamento','concluida','hiato') NOT NULL DEFAULT 'em_andamento',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_obras_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS capitulos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    obra_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    numero DECIMAL(6,2) NOT NULL,
    conteudo LONGTEXT NULL,
    imagem VARCHAR(255) NULL,
    publicado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_capitulos_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS favoritos (
    usuario_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, obra_id),
    CONSTRAINT fk_favoritos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_favoritos_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comentarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NOT NULL,
    texto TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comentarios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_comentarios_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS atualizacoes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    artista_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NULL,
    texto TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_atualizacoes_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_atualizacoes_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacoes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    texto VARCHAR(255) NOT NULL,
    link VARCHAR(255) NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notificacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_obras_titulo ON obras(titulo);
CREATE INDEX idx_capitulos_obra ON capitulos(obra_id);
CREATE INDEX idx_notificacoes_usuario ON notificacoes(usuario_id, lida);
>>>>>>> cc21d46c4ef1943831d6cb804870ba3ea6b84abc
