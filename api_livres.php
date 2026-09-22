<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action){

  // READ — liste ou recherche
  case 'list':
  case 'search':
    $q = trim($_GET['q'] ?? '');
    if($q !== ''){
      $stmt = $pdo->prepare('SELECT * FROM livres WHERE titre LIKE :q OR auteur LIKE :q ORDER BY titre');
      $stmt->execute([':q'=>"%$q%"]);
    } else {
      $stmt = $pdo->query('SELECT * FROM livres ORDER BY titre');
    }
    echo json_encode($stmt->fetchAll());
    break;

  // READ — détail
  case 'get':
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM livres WHERE id = :id');
    $stmt->execute([':id'=>$id]);
    $livre = $stmt->fetch();
    echo json_encode($livre ?: null);
    break;

  // CREATE
  case 'add':
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison = trim($_POST['maison_edition'] ?? '');
    $nb = max(1, (int)($_POST['nombre_exemplaire'] ?? 1));
    if($titre === '' || $auteur === ''){
      echo json_encode(['ok'=>false,'msg'=>'Titre et auteur requis.']);
      break;
    }
    $stmt = $pdo->prepare('INSERT INTO livres (titre,auteur,description,maison_edition,nombre_exemplaire) VALUES (:t,:a,:d,:m,:n)');
    $stmt->execute([':t'=>$titre,':a'=>$auteur,':d'=>$description,':m'=>$maison,':n'=>$nb]);
    echo json_encode(['ok'=>true,'id'=>$pdo->lastInsertId()]);
    break;

  // UPDATE
  case 'update':
    $id = (int)($_POST['id'] ?? 0);
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison = trim($_POST['maison_edition'] ?? '');
    $nb = max(0, (int)($_POST['nombre_exemplaire'] ?? 0));
    if($titre === '' || $auteur === ''){
      echo json_encode(['ok'=>false,'msg'=>'Titre et auteur requis.']);
      break;
    }
    $stmt = $pdo->prepare('UPDATE livres SET titre=:t,auteur=:a,description=:d,maison_edition=:m,nombre_exemplaire=:n WHERE id=:id');
    $stmt->execute([':t'=>$titre,':a'=>$auteur,':d'=>$description,':m'=>$maison,':n'=>$nb,':id'=>$id]);
    echo json_encode(['ok'=>true]);
    break;

  // DELETE
  case 'delete':
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM livres WHERE id=:id');
    $stmt->execute([':id'=>$id]);
    echo json_encode(['ok'=>true]);
    break;

  default:
    echo json_encode(['error'=>'action inconnue']);
}
