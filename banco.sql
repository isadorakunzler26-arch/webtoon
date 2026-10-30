CREATE DATABASE IF NOT EXISTS webtoonsz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webtoonsz;

CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    usuario     VARCHAR(30)  NOT NULL UNIQUE,
    email       VARCHAR(150) NOT NULL UNIQUE,
    senha_hash  VARCHAR(255) NOT NULL,
    biografia   VARCHAR(300) NULL,
    foto        VARCHAR(255) NULL,
    tipo        ENUM('leitor','artista') NOT NULL DEFAULT 'leitor',
    criado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
