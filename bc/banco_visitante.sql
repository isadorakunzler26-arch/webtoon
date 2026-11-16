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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS obras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    artista_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NULL,
    capa VARCHAR(500) NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'em_andamento',
    local_leitura VARCHAR(500) NULL,
    publicada TINYINT(1) NOT NULL DEFAULT 1,
    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_obras_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_obras_titulo (titulo),
    INDEX idx_obras_publicada (publicada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS atualizacoes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    artista_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NULL,
    titulo VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('aviso','lancamento','pausa','retorno','plataforma','geral') NOT NULL DEFAULT 'geral',
    data_evento DATE NULL,
    plataforma_url VARCHAR(500) NULL,
    publicada TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_atualizacoes_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_atualizacoes_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE SET NULL,
    INDEX idx_atualizacoes_data (criado_em),
    INDEX idx_atualizacoes_publicada (publicada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
