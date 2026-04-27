-- ============================================================
--  Portfolio Sonia Habibi — Schema BDD
-- ============================================================

CREATE DATABASE IF NOT EXISTS portfolio
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE portfolio;

-- ─── Projets ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS projects (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    title_fr    VARCHAR(150)    NOT NULL,
    title_en    VARCHAR(150)    NOT NULL,
    desc_fr     VARCHAR(600)    NOT NULL,
    desc_en     VARCHAR(600)    NOT NULL,
    slug        VARCHAR(100)    NOT NULL UNIQUE,
    tags        VARCHAR(300)    NOT NULL DEFAULT '',   -- CSV : "PHP MVC,SCSS,MySQL"
    github_url  VARCHAR(300)    DEFAULT NULL,
    demo_url    VARCHAR(300)    DEFAULT NULL,
    thumbnail   VARCHAR(300)    DEFAULT NULL,          -- chemin relatif : /assets/images/projects/amana.webp
    is_featured TINYINT(1)      NOT NULL DEFAULT 0,
    is_wip      TINYINT(1)      NOT NULL DEFAULT 0,
    sort_order  SMALLINT        NOT NULL DEFAULT 0,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Messages de contact ─────────────────────────────────
CREATE TABLE IF NOT EXISTS messages (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL,
    message    TEXT          NOT NULL,
    is_read    TINYINT(1)    NOT NULL DEFAULT 0,
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Admin ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,               -- bcrypt
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Données de démo ─────────────────────────────────────
INSERT INTO projects (title_fr, title_en, desc_fr, desc_en, slug, tags, github_url, demo_url, thumbnail, is_featured, is_wip, sort_order) VALUES

('Agent IA — en cours',
 'AI Agent — in progress',
 'Projet d\'intégration LLM en cours de développement. Agent contextuel avec mémoire et outils.',
 'LLM integration project currently in development. Contextual agent with memory and tools.',
 'agent-ia',
 'Python,LLM,Claude API',
 NULL, NULL, NULL, 1, 1, 1),

('Professeure équestre',
 'Equestrian teacher',
 'Site vitrine et espace client pour une professeure équestre. Réservation de cours, galerie, blog.',
 'Showcase website and client space for an equestrian teacher. Lesson booking, gallery, blog.',
 'professeure-equestre',
 'PHP MVC,JavaScript,SCSS,MySQL',
 NULL, NULL, '/assets/images/projects/equestre.webp', 1, 0, 2),

('Amanéa Voyage',
 'Amanéa Voyage',
 'Plateforme de voyage sur mesure. 11 modèles, 14 contrôleurs, router objet, espace client, back-office, HTTPS production.',
 'Custom travel platform. 11 models, 14 controllers, object router, client space, back-office, HTTPS production.',
 'amanea-voyage',
 'PHP MVC,SCSS,MySQL,API Pexels',
 'https://github.com/sonia-habibi/amanea', NULL, '/assets/images/projects/amanea.webp', 1, 0, 3),

('Météo App',
 'Weather App',
 'Application météo temps réel avec interface intuitive. Données par ville ou géolocalisation.',
 'Real-time weather app with an intuitive interface. Data by city or geolocation.',
 'meteo-app',
 'JavaScript,API REST,HTML,CSS',
 'https://github.com/sonia-habibi/meteo', NULL, '/assets/images/projects/meteo.webp', 1, 0, 4),

('La Ferme de Basile',
 'La Ferme de Basile',
 'Site vitrine responsive pour une ferme locale. Galerie, présentation des produits, formulaire de contact.',
 'Responsive showcase website for a local farm. Gallery, product presentation, contact form.',
 'ferme-de-basile',
 'JavaScript,HTML,CSS',
 NULL, NULL, '/assets/images/projects/ferme.webp', 0, 0, 5);
