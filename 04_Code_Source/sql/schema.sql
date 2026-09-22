-- sql/schema.sql
CREATE DATABASE IF NOT EXISTS bibliotheque
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE bibliotheque;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','lecteur') DEFAULT 'lecteur',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table livres
CREATE TABLE IF NOT EXISTS livres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  auteur VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  maison_edition VARCHAR(100) NOT NULL,
  nombre_exemplaire INT NOT NULL DEFAULT 1
);

-- Table liste_lecture
CREATE TABLE IF NOT EXISTS liste_lecture (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_livre INT NOT NULL,
  id_user INT NOT NULL,
  date_emprunt DATE NOT NULL,
  date_retour DATE NULL,
  FOREIGN KEY (id_livre) REFERENCES livres(id) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
);

-- Utilisateurs de test
-- admin123 pour admin, lecteur123 pour les autres
INSERT INTO users (nom, prenom, email, password_hash, role) VALUES
('KOUASSI', 'Amos', 'admin@biblio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('DIALLO', 'Fatou', 'fatou@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecteur'),
('KONE', 'Moussa', 'moussa@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecteur');

-- Livres de test
INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un petit prince voyage de planète en planète et rencontre des adultes étranges. Une œuvre poétique et philosophique.', 'Gallimard', 5),
('L\'Étranger', 'Albert Camus', 'Meursault, un homme indifférent au monde qui l''entoure, commet un meurtre sans raison apparente sous un soleil écrasant.', 'Gallimard', 3),
('Les Misérables', 'Victor Hugo', 'L''histoire de Jean Valjean, ancien forçat, qui cherche la rédemption dans la France du XIXe siècle.', 'A. Lacroix', 4),
('Dune', 'Frank Herbert', 'Paul Atréides mène une révolte sur la planète désertique d''Arrakis, source de l''épice la plus précieuse.', 'L''Atalante', 2),
('1984', 'George Orwell', 'Dans un monde totalitaire, Winston Smith tente de résister au contrôle absolu du Grand Frère.', 'Gallimard', 3),
('Fondation', 'Isaac Asimov', 'Un mathématicien prédit la chute de l''Empire galactique et crée un plan pour raccourcir les siècles de barbarie.', 'Presses-Pocket', 2),
('Fahrenheit 451', 'Ray Bradbury', 'Un pompier dont le métier est de brûler les livres commence à remettre en question son rôle.', 'Denoël', 2),
('Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Frodon le Hobbit doit détruire un anneau magique pour sauver la Terre du Milieu.', 'Bourgois', 3),
('Harry Potter à l''école des sorciers', 'J.K. Rowling', 'Un jeune orphelin découvre qu''il est sorcier et intègre l''école Poudlard.', 'Gallimard', 5),
('Candide', 'Voltaire', 'Un jeune homme naïf voyage à travers le monde et découvre les cruautés de la vie.', 'Flammarion', 2),
('Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Edmond Dantès s''échappe du château d''If et poursuit sa vengeance en tant que comte mystérieux.', 'Lafitte', 3),
('Vingt mille lieues sous les mers', 'Jules Verne', 'Le professeur Aronnax explore les océans à bord du Nautilus du capitaine Nemo.', 'Hetzel', 2);
