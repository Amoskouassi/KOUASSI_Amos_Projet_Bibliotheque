========================================
PROJET FINAL — Bibliothèque en Ligne
========================================
Nom : KOUASSI Amos
Formation : Développement Web Intermédiaire
Date : Septembre 2026

========================================
CONTENU DU ZIP
========================================

01_Cahier_des_Charges/
  └── Cahier_des_Charges.txt

02_Wireframing/
  └── Wireframing.txt

03_Documentation/
  └── Documentation_Technique.txt

04_Code_Source/
  ├── public/
  ├── app/
  ├── sql/
  └── README.txt

========================================
INSTALLATION
========================================

1. Extraire le ZIP
2. Copier le dossier 04_Code_Source/ dans C:\laragon\www\bibliotheque\
3. Démarrer Laragon (Apache + MySQL)
4. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
5. Importer le fichier sql/schema.sql
6. Ouvrir http://localhost/bibliotheque/

========================================
TECHNOLOGIES UTILISÉES
========================================

- HTML5 (sémantique)
- CSS3 (Grid, Flexbox, variables, responsive)
- JavaScript (Fetch API, DOM manipulation)
- PHP 8.x (PDO, requêtes préparées)
- MySQL 8.x (base relationnelle)
- Laragon (serveur local)

========================================
FONCTIONNALITÉS
========================================

1. Page d'accueil avec recherche
2. Détails de chaque livre
3. Ajout de livres à la collection
4. Modification de livres
5. Suppression avec confirmation
6. Liste de lecture personnelle
7. API RESTful (CRUD complet)

========================================
BASE DE DONNÉES
========================================

Tables : livres, lecteurs, liste_lecture
Données : 12 livres, 3 lecteurs

========================================
ÉCOCONCEPTION
========================================

- Pas de frameworks externes
- Code vanilla (HTML/CSS/JS)
- Requêtes SQL optimisées
- Assets légers
