<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
  $stmt->execute([':email' => $email]);
  $user = $stmt->fetch();
  if ($user && password_verify($pass, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['role'] = $user['role'];
    header('Location: index.php');
    exit;
  }
  $error = 'Email ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion — Bibliothèque</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","surface-container":"#eaedff","surface-container-high":"#e2e7ff","on-surface":"#131b2e","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff"},fontFamily:{sans:["Inter","system-ui","sans-serif"]}}}}};
  </script>
  <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="bg-gradient-to-br from-primary to-blue-800 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-[400px] bg-white rounded-2xl p-8 shadow-2xl">
    <div class="text-center mb-6">
      <div class="text-4xl mb-2">📚</div>
      <h1 class="text-xl font-bold">Bibliothèque en Ligne</h1>
      <p class="text-sm text-secondary mt-1">Connectez-vous à votre compte</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="flex flex-col gap-4">
      <div class="flex flex-col">
        <label class="text-sm font-semibold mb-1">Email</label>
        <input name="email" type="email" required class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" placeholder="votre@email.com"/>
      </div>
      <div class="flex flex-col">
        <label class="text-sm font-semibold mb-1">Mot de passe</label>
        <input name="password" type="password" required class="h-11 w-full rounded-lg border border-gray-200 px-3.5 text-sm focus:shadow-[0_0_0_2px_#2563eb] focus:outline-none transition-all" placeholder="••••••••"/>
      </div>
      <button type="submit" class="h-11 w-full rounded-lg bg-primary text-white text-sm font-semibold hover:opacity-90 transition-all">Se connecter</button>
    </form>

    <div class="text-center mt-6 text-sm">
      <p class="text-secondary">Pas encore de compte ? <a href="register.php" class="text-primary font-semibold hover:underline">S'inscrire</a></p>
      <a href="index.php" class="text-secondary text-xs hover:underline mt-2 inline-block">Retour à l'accueil</a>
    </div>
  </div>
</body>
</html>
