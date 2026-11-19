USE webtoonsz;

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
