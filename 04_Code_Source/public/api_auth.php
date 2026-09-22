<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/auth.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action){

  case 'login':
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email'=>$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['password_hash'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_nom'] = $user['nom'];
      $_SESSION['user_prenom'] = $user['prenom'];
      $_SESSION['role'] = $user['role'];
      echo json_encode(['ok'=>true,'role'=>$user['role'],'prenom'=>$user['prenom']]);
    } else {
      echo json_encode(['ok'=>false,'msg'=>'Email ou mot de passe incorrect.']);
    }
    break;

  case 'register':
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if($nom==='' || $prenom==='' || $email==='' || $pass===''){
      echo json_encode(['ok'=>false,'msg'=>'Tous les champs sont requis.']); break;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      echo json_encode(['ok'=>false,'msg'=>'Email invalide.']); break;
    }
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nom,prenom,email,password_hash,role) VALUES (:n,:p,:e,:h,"lecteur")');
    try {
      $stmt->execute([':n'=>$nom,':p'=>$prenom,':e'=>$email,':h'=>$hash]);
      echo json_encode(['ok'=>true]);
    } catch (PDOException $ex) {
      echo json_encode(['ok'=>false,'msg'=>'Cet email existe déjà.']);
    }
    break;

  case 'logout':
    session_destroy();
    echo json_encode(['ok'=>true]);
    break;

  case 'current':
    if(is_logged_in()){
      echo json_encode(['logged'=>true,'role'=>$_SESSION['role'],'prenom'=>$_SESSION['user_prenom'],'nom'=>$_SESSION['user_nom']]);
    } else {
      echo json_encode(['logged'=>false]);
    }
    break;

  default:
    echo json_encode(['error'=>'action inconnue']);
}
