-- Database: beembrug
CREATE DATABASE IF NOT EXISTS beembrug;
USE beembrug;

-- Tabel: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    wachtwoord VARCHAR(255) NOT NULL,
    rol ENUM('lid', 'admin') DEFAULT 'lid'
);

-- Tabel: reservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activiteit ENUM('tennis', 'padel', 'tafeltennis', 'instructeur') NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    status ENUM('geboekt', 'geannuleerd') DEFAULT 'geboekt',
    instructeur VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- wachtwoord voor beide gebruikers = welkom

INSERT INTO users (naam, email, wachtwoord, rol) VALUES
(
    'Test Lid',
    'lid@beembrug.nl',
    '$2y$10$YQHt6b2qKmy7eP74FZX0gOn1UhX4JwCrakFBKUkiGeYXmskpVZOV2',
    'lid'
),
(
    'Beheerder',
    'admin@beembrug.nl',
    '$2y$10$YQHt6b2qKmy7eP74FZX0gOn1UhX4JwCrakFBKUkiGeYXmskpVZOV2',
    'admin'
);