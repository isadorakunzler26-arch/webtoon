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
