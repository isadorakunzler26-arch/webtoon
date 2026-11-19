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
    genero VARCHAR(80) NULL,
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

CREATE TABLE IF NOT EXISTS seguidores (
    seguidor_id INT UNSIGNED NOT NULL,
    artista_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (seguidor_id, artista_id),
    CONSTRAINT fk_seguidores_seguidor FOREIGN KEY (seguidor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_seguidores_artista FOREIGN KEY (artista_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS favoritos (
    usuario_id INT UNSIGNED NOT NULL,
    obra_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, obra_id),
    CONSTRAINT fk_favoritos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_favoritos_obra FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS notificacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    atualizacao_id INT UNSIGNED NOT NULL,
    mensagem VARCHAR(500) NOT NULL,
    link VARCHAR(255) NOT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notificacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_notificacoes_atualizacao FOREIGN KEY (atualizacao_id) REFERENCES atualizacoes(id) ON DELETE CASCADE,
    INDEX idx_notificacoes_usuario_data (usuario_id, criada_em),
    INDEX idx_notificacoes_usuario_lida (usuario_id, lida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

CREATE TRIGGER trg_atualizacao_notificacoes
AFTER INSERT ON atualizacoes
FOR EACH ROW
BEGIN
    IF NEW.publicada = 1 THEN
        INSERT INTO notificacoes (usuario_id, atualizacao_id, mensagem, link)
        SELECT destinatarios.usuario_id,
               NEW.id,
               CONCAT('@', autor.usuario, ' publicou uma atualização: ', NEW.titulo),
               CASE
                   WHEN NEW.obra_id IS NOT NULL THEN CONCAT('obra.php?id=', NEW.obra_id)
                   ELSE CONCAT('artista.php?id=', NEW.artista_id)
               END
        FROM (
            SELECT usuario_id
            FROM favoritos
            WHERE obra_id = NEW.obra_id

            UNION

            SELECT seguidor_id AS usuario_id
            FROM seguidores
            WHERE artista_id = NEW.artista_id
        ) AS destinatarios
        INNER JOIN usuarios autor ON autor.id = NEW.artista_id
        WHERE destinatarios.usuario_id <> NEW.artista_id;
    END IF;
END$$

DELIMITER ;
