-- sql/schema.sql
CREATE DATABASE IF NOT EXISTS bibliotheque
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE bibliotheque;

-- Table utilisateurs (authentification + rôles)
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

-- Utilisateurs de test (mot de passe: admin123 / lecteur123)
INSERT INTO users (nom, prenom, email, password_hash, role) VALUES
('KOUASSI', 'Amos', 'admin@biblio.com', '$2y$10$YourHashHere', 'admin'),
('DIALLO', 'Fatou', 'fatou@mail.com', '$2y$10$YourHashHere', 'lecteur'),
('KONE', 'Moussa', 'moussa@mail.com', '$2y$10$YourHashHere', 'lecteur');

-- Livres de test
INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un petit prince voyage de planète en planète et rencontre des adultes étranges.', 'Gallimard', 5),
('L\'Étranger', 'Albert Camus', 'Meursault, un homme indifférent au monde qui l\'entoure, commet un meurtre.', 'Gallimard', 3),
('Les Misérables', 'Victor Hugo', 'L\'histoire de Jean Valjean qui cherche la rédemption.', 'A. Lacroix', 4),
('Dune', 'Frank Herbert', 'Paul Atréides mène une révolte sur la planète Arrakis.', 'L\'Atalante', 2),
('1984', 'George Orwell', 'Winston Smith tente de résister au Grand Frère.', 'Gallimard', 3),
('Fondation', 'Isaac Asimov', 'Un mathématicien prédit la chute de l\'Empire galactique.', 'Presses-Pocket', 2),
('Fahrenheit 451', 'Ray Bradbury', 'Un pompier remet en question son métier de brûleur de livres.', 'Denoël', 2),
('Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Frodon doit détruire un anneau magique.', 'Bourgois', 3),
('Harry Potter', 'J.K. Rowling', 'Un jeune orphelin découvre qu\'il est sorcier.', 'Gallimard', 5),
('Candide', 'Voltaire', 'Un jeune homme naïf voyage à travers le monde.', 'Flammarion', 2),
('Monte-Cristo', 'Alexandre Dumas', 'Edmond Dantès poursuit sa vengeance.', 'Lafitte', 3),
('Vingt mille lieues', 'Jules Verne', 'Le professeur Aronnax explore les océans.', 'Hetzel', 2);
