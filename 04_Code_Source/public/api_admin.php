<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/auth.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

require_admin();

$action = $_GET['action'] ?? '';

switch($action){
  case 'stats':
    $livres = $pdo->query('SELECT COUNT(*) FROM livres')->fetchColumn();
    $emprunts = $pdo->query('SELECT COUNT(*) FROM liste_lecture WHERE date_retour IS NULL')->fetchColumn();
    $users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $allLivres = $pdo->query('SELECT * FROM livres ORDER BY id DESC')->fetchAll();
    $allUsers = $pdo->query('SELECT id,nom,prenom,email,role FROM users ORDER BY id')->fetchAll();
    echo json_encode(['stats'=>['livres'=>$livres,'emprunts'=>$emprunts,'users'=>$users],'livres'=>$allLivres,'users'=>$allUsers]);
    break;
  default:
    echo json_encode(['error'=>'action inconnue']);
}
