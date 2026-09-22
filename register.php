<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom'] ?? '');
  $prenom = trim($_POST['prenom'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  if ($nom === '' || $prenom === '' || $email === '' || $pass === '') {
    $error = 'Tous les champs sont requis.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Email invalide.';
  } elseif (strlen($pass) < 6) {
    $error = 'Le mot de passe doit faire au moins 6 caractères.';
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nom,prenom,email,password_hash,role) VALUES (:n,:p,:e,:h,"lecteur")');
    try {
      $stmt->execute([':n'=>$nom,':p'=>$prenom,':e'=>$email,':h'=>$hash]);
      $success = 'Compte créé ! Vous pouvez vous connecter.';
    } catch (PDOException $ex) {
      $error = 'Cet email est déjà utilisé.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription — Bibliothèque</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","on-surface":"#131b2e","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff"},fontFamily:{sans:["Inter","system-ui","sans-serif"]}}}}};
  </script>
  <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="bg-gradient-to-br from-primary to-blue-800 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-[400px] bg-white rounded-2xl p-8 shadow-2xl">
    <div class="text-center mb-6">
      <div class="text-4xl mb-2">📚</div>
      <h1 class="text-xl font-bold">Créer un compte</h1>
      <p class="text-sm text-secondary mt-1">Rejoignez notre bibliothèque</p>
    </div>

    <?php if ($error): ?>
      <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><?php echo e($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium"><?php echo e($success); ?></div>
    <?php endif; ?>

    <form method="POST" class="flex flex-col gap-4">
      <div class="grid grid-cols-2 gap-3">
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Nom</label>
          <input name="nom" required class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" type="text"/>
        </div>
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Prénom</label>
          <input name="prenom" required class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" type="text"/>
        </div>
      </div>
      <div class="flex flex-col">
        <label class="text-sm font-semibold mb-1">Email</label>
        <input name="email" type="email" required class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" placeholder="votre@email.com"/>
      </div>
      <div class="flex flex-col">
        <label class="text-sm font-semibold mb-1">Mot de passe</label>
        <input name="password" type="password" required minlength="6" class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" placeholder="••••••••"/>
      </div>
      <button type="submit" class="h-11 w-full rounded-lg bg-primary text-white text-sm font-semibold hover:opacity-90 transition-all">S'inscrire</button>
    </form>

    <div class="text-center mt-6 text-sm">
      <p class="text-secondary">Déjà un compte ? <a href="login.php" class="text-primary font-semibold hover:underline">Se connecter</a></p>
      <a href="index.php" class="text-secondary text-xs hover:underline mt-2 inline-block">Retour à l'accueil</a>
    </div>
  </div>
</body>
</html>
