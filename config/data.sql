-- Création de la base de données
CREATE DATABASE cosmos_x_doc;
USE cosmos_x_doc;

-- Création de la table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_admin TINYINT(1) not null default 0,
    filiere VARCHAR(100),
    niveau_etudes ENUM('licence1', 'licence2', 'licence3', 'master1', 'master2'),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Création de la table des fichiers
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    level ENUM('licence1', 'licence2', 'licence3', 'master1', 'master2') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table des téléchargements
CREATE TABLE downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_id INT NOT NULL,
    download_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
);

-- Ajout d'exemples d'utilisateurs
INSERT INTO users (first_name, last_name, email, filiere, niveau_etudes, password) VALUES
('Jean', 'Dupont', 'jean.dupont@example.com', 'Informatique', 'licence1', SHA2('password123', 256)),
('Marie', 'Curie', 'marie.curie@example.com', 'Physique Chimie', 'master2', SHA2('password123', 256));

-- Ajout d'exemples de fichiers
INSERT INTO files (name, description, level, file_path) VALUES
('Cours d\'algorithmique', 'Cours complet sur les bases de l\'algorithmique.', 'licence1', '/uploads/algorithmique.pdf'),
('Projet de physique', 'Documentation du projet de physique.', 'master2', '/uploads/physique_project.zip');

-- Ajout d'exemples de téléchargements
INSERT INTO downloads (user_id, file_id) VALUES
(1, 1),
(2, 2);
