CREATE DATABASE gestion_menage CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gestion_menage;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'femme') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chambres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_chambre VARCHAR(50) NOT NULL UNIQUE,
    etage VARCHAR(20),
    statut_menage ENUM('menage', 'non_menage') DEFAULT 'non_menage',
    statut_disponibilite ENUM('disponible', 'occupee') DEFAULT 'disponible',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    chambre_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    date_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (chambre_id) REFERENCES chambres(id) ON DELETE CASCADE
);

CREATE TABLE menage_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chambre_id INT NOT NULL,
    user_id INT NOT NULL,
    action ENUM('menage', 'non_menage', 'disponible', 'occupee') NOT NULL,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chambre_id) REFERENCES chambres(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
