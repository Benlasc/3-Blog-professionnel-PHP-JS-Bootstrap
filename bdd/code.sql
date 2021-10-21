SET NAMES UTF8MB4;

DROP DATABASE IF EXISTS blog;

CREATE DATABASE blog;

USE blog;

CREATE TABLE user (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(70) NOT NULL,
    nom VARCHAR(60) NOT NULL,
    prenom VARCHAR(60) NOT NULL,
    mail VARCHAR(60) NOT NULL,
    dateInscription DATETIME NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    password VARCHAR(255) NOT NULL,
    confirmed BOOLEAN NOT NULL DEFAULT FALSE,
    confirmationToken VARCHAR(60) DEFAULT NULL,
    resetToken VARCHAR(60) DEFAULT NULL,
    resetAt DATETIME DEFAULT NULL,
    avatar VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE post (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    idAuteur SMALLINT UNSIGNED DEFAULT NULL,
    titre VARCHAR(100) NOT NULL,
    image VARCHAR(100) NOT NULL,
    chapo TEXT NOT NULL,
    contenu TEXT NOT NULL,
    slug VARCHAR(50) NOT NULL,
    dateCreation DATETIME NOT NULL,
    dateModif DATETIME NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE comment (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    idAuteur SMALLINT UNSIGNED DEFAULT NULL,
    idArticle SMALLINT UNSIGNED NOT NULL,
    idParent SMALLINT UNSIGNED DEFAULT NULL,
    contenu TEXT NOT NULL,
    dateCreation DATETIME NOT NULL,
    valid BOOLEAN NOT NULL DEFAULT FALSE,
    depth SMALLINT UNSIGNED DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

ALTER TABLE post
ADD CONSTRAINT fk_post_id_auteur FOREIGN KEY (idAuteur) REFERENCES user (id) ON DELETE SET NULL;

ALTER TABLE comment
ADD CONSTRAINT fk_comment_id_auteur FOREIGN KEY (idAuteur) REFERENCES user (id) ON DELETE SET NULL;

ALTER TABLE comment
ADD CONSTRAINT fk_id_article FOREIGN KEY (idArticle) REFERENCES post (id) ON DELETE CASCADE;

ALTER TABLE comment
ADD CONSTRAINT fk_id_parent FOREIGN KEY (idParent) REFERENCES comment (id) ON DELETE CASCADE;

CREATE UNIQUE INDEX ind_uni_pseudo ON user (pseudo); 

CREATE UNIQUE INDEX ind_uni_mail ON user (mail); 