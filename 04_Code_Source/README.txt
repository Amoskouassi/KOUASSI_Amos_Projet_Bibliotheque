========================================
README — Bibliothèque en Ligne
========================================
Nom : KOUASSI Amos
Date : Septembre 2026

========================================
DESCRIPTION
========================================

Application web de gestion de bibliothèque permettant de
rechercher, consulter, ajouter, modifier et supprimer des
livres. Gestion d'une liste de lecture personnelle.
Authentification avec rôles (admin/lecteur).
Interface d'administration pour gérer la collection.

========================================
TECHNOLOGIES
========================================

- HTML5 (sémantique, accessibilité)
- CSS3 (Grid, Flexbox, responsive)
- JavaScript (Fetch API, DOM)
- PHP 8.x (PDO, sessions)
- MySQL 8.x (base relationnelle)
- Laragon (serveur local)

========================================
INSTALLATION
========================================

Prérequis :
- Laragon (PHP 8.x + MySQL)
- Navigateur moderne

Étapes :
1. Extraire le ZIP
2. Copier 04_Code_Source/ dans C:\laragon\www\bibliotheque\
3. Démarrer Laragon (Apache + MySQL)
4. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
5. Créer la base "bibliotheque"
6. Importer sql/schema.sql
7. Ouvrir http://localhost/bibliotheque/

Identifiants MySQL :
- Hôte : localhost
- Utilisateur : root
- Mot de passe : (vide)

========================================
STRUCTURE DU PROJET
========================================

Projet_Final_Bibliotheque/
├── 01_Cahier_des_Charges/
│   └── Cahier_des_Charges.txt
├── 02_Wireframing/
│   └── (fichiers Stitch)
├── 03_Documentation/
│   ├── Documentation_Technique.txt
│   └── Demonstration.txt
├── 04_Code_Source/
│   ├── public/
│   │   ├── index.html          (accueil)
│   │   ├── details.html        (détails livre)
│   │   ├── ajouter.html        (ajout livre)
│   │   ├── modifier.html       (modification)
│   │   ├── wishlist.html       (liste de lecture)
│   │   ├── login.html          (connexion)
│   │   ├── register.html       (inscription)
│   │   ├── admin.php           (administration)
│   │   ├── api_livres.php      (CRUD livres)
│   │   ├── api_liste.php       (liste de lecture)
│   │   ├── api_auth.php        (authentification)
│   │   ├── api_admin.php       (stats admin)
│   │   └── assets/
│   │       ├── css/style.css
│   │       └── js/app.js
│   ├── app/
│   │   ├── config.php
│   │   ├── db.php
│   │   ├── auth.php
│   │   └── helpers.php
│   └── sql/
│       └── schema.sql
└── README.txt

========================================
FONCTIONNALITÉS
========================================

Public :
- Consultation du catalogue
- Recherche par titre/auteur
- Détails de chaque livre
- Inscription / Connexion

Utilisateur (lecteur) :
- Ajouter à ma liste de lecture
- Retirer de la liste
- Consulter ses emprunts

Administrateur :
- Statistiques (livres, emprunts, utilisateurs)
- Gestion CRUD des livres
- Liste des utilisateurs
- Accès au panel admin

========================================
BASE DE DONNÉES
========================================

Tables :
- users (id, nom, prenom, email, password_hash, role)
- livres (id, titre, auteur, description, maison_edition, nombre_exemplaire)
- liste_lecture (id, id_livre, id_user, date_emprunt, date_retour)

========================================
COMPTES DE TEST
========================================

Admin :
  Email : admin@biblio.com
  Mot de passe : admin123

Lecteur :
  Email : fatou@mail.com
  Mot de passe : lecteur123

========================================
SÉCURITÉ
========================================

- Requêtes préparées PDO
-htmlspecialchars() (anti XSS)
- password_hash() / password_verify()
- Vérification des rôles
- Sessions sécurisées

========================================
WORKFLOW GIT
========================================

Branches :
- main : version stable
- develop : développement
- feature/* : fonctionnalités

Conventions :
- Commits : verbe + description courte
- PR : revue avant merge
- Tags : v1.0, v1.1, etc.

========================================
ÉCOCONCEPTION
========================================

- Code vanilla (pas de frameworks)
- CSS sans bibliothèques externes
- JavaScript léger
- Requêtes SQL optimisées
- Pas d'images lourdes
