<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action){

  // Lecteur connecté (démo : id=1)
  $id_lecteur = 1;

  // Liste de lecture de l'utilisateur
  case 'list':
    $stmt = $pdo->prepare('
      SELECT ll.*, l.titre, l.auteur, l.description
      FROM liste_lecture ll
      JOIN livres l ON ll.id_livre = l.id
      WHERE ll.id_lecteur = :lid
      ORDER BY ll.date_emprunt DESC
    ');
    $stmt->execute([':lid'=>$id_lecteur]);
    echo json_encode($stmt->fetchAll());
    break;

  // Ajouter à la liste
  case 'add':
    $id_livre = (int)($_POST['id_livre'] ?? 0);
    if($id_livre <= 0){ echo json_encode(['ok'=>false,'msg'=>'ID invalide']); break; }

    // Vérifier si déjà présent
    $check = $pdo->prepare('SELECT id FROM liste_lecture WHERE id_livre=:l AND id_lecteur=:u');
    $check->execute([':l'=>$id_livre,':u'=>$id_lecteur]);
    if($check->fetch()){
      echo json_encode(['ok'=>false,'msg'=>'Déjà dans votre liste.']); break;
    }

    $stmt = $pdo->prepare('INSERT INTO liste_lecture (id_livre,id_lecteur,date_emprunt) VALUES (:l,:u,CURDATE())');
    $stmt->execute([':l'=>$id_livre,':u'=>$id_lecteur]);
    echo json_encode(['ok'=>true]);
    break;

  // Retirer de la liste
  case 'remove':
    $id_livre = (int)($_POST['id_livre'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM liste_lecture WHERE id_livre=:l AND id_lecteur=:u');
    $stmt->execute([':l'=>$id_livre,':u'=>$id_lecteur]);
    echo json_encode(['ok'=>true]);
    break;

  // Vérifier si un livre est dans la liste
  case 'check':
    $id_livre = (int)($_GET['id_livre'] ?? 0);
    $stmt = $pdo->prepare('SELECT id FROM liste_lecture WHERE id_livre=:l AND id_lecteur=:u');
    $stmt->execute([':l'=>$id_livre,':u'=>$id_lecteur]);
    echo json_encode(['in_list'=>$stmt->fetch() !== false]);
    break;

  default:
    echo json_encode(['error'=>'action inconnue']);
}
