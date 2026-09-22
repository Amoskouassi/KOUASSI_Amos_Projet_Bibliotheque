<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titre = trim($_POST['titre'] ?? '');
  $auteur = trim($_POST['auteur'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $maison = trim($_POST['maison_edition'] ?? '');
  $nb = max(1, (int)($_POST['nombre_exemplaire'] ?? 1));

  if ($titre === '' || $auteur === '') {
    $error = 'Titre et auteur sont requis.';
  } else {
    $stmt = $pdo->prepare('INSERT INTO livres (titre,auteur,description,maison_edition,nombre_exemplaire) VALUES (:t,:a,:d,:m,:n)');
    $stmt->execute([':t'=>$titre,':a'=>$auteur,':d'=>$description,':m'=>$maison,':n'=>$nb]);
    $success = 'Livre ajouté avec succès !';
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title>Ajouter un livre — Bibliothèque</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","surface-container":"#eaedff","surface-container-high":"#e2e7ff","on-surface":"#131b2e","on-surface-variant":"#434655","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff","tertiary":"#006242","tertiary-container":"#007d55","on-tertiary":"#ffffff","error":"#ba1a1a","on-error":"#ffffff","outline":"#737686"},fontFamily:{sans:["Inter","system-ui","sans-serif"]},spacing:{"gutter-sm":"1rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"}}}}};
  </script>
  <style>body{font-family:'Inter',system-ui,sans-serif;background:#faf8ff;color:#131b2e}</style>
</head>
<body class="bg-surface flex flex-col min-h-screen">
  <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-16 px-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <a href="index.php" class="w-11 h-11 -ml-2 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors">
          <span class="material-symbols-outlined text-[22px]">arrow_back</span>
        </a>
        <h1 class="text-base font-semibold text-on-surface truncate">Ajouter un livre</h1>
      </div>
    </div>
  </header>

  <main class="flex-1 w-full pt-16 pb-28 px-4 bg-surface flex flex-col items-center">
    <div class="w-full max-w-[600px] py-4">

      <?php if ($error): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><?php echo e($error); ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium"><?php echo e($success); ?></div>
      <?php endif; ?>

      <form method="POST" class="bg-surface-container-lowest rounded-xl shadow-md p-6 flex flex-col gap-4">
        <div class="flex items-start justify-between gap-4 mb-2">
          <div>
            <h2 class="text-lg font-semibold">Ajouter un livre</h2>
            <p class="text-xs text-secondary mt-1">Enregistrez un nouvel ouvrage dans la collection.</p>
          </div>
          <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-[22px]">library_add</span>
          </div>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1.5">Titre <span class="text-error">*</span></label>
          <input name="titre" required class="h-11 w-full rounded-lg bg-surface-container-low px-3.5 text-sm placeholder:text-secondary focus:bg-surface-container-lowest focus:shadow-[0_0_0_2px_#2563eb] transition-all outline-none" placeholder="Ex: Le Petit Prince" type="text"/>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1.5">Auteur <span class="text-error">*</span></label>
          <input name="auteur" required class="h-11 w-full rounded-lg bg-surface-container-low px-3.5 text-sm placeholder:text-secondary focus:bg-surface-container-lowest focus:shadow-[0_0_0_2px_#2563eb] transition-all outline-none" placeholder="Ex: Antoine de Saint-Exupéry" type="text"/>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="flex flex-col">
            <label class="text-sm font-semibold mb-1.5">Maison d'édition</label>
            <input name="maison_edition" class="h-11 w-full rounded-lg bg-surface-container-low px-3.5 text-sm placeholder:text-secondary focus:bg-surface-container-lowest focus:shadow-[0_0_0_2px_#2563eb] transition-all outline-none" placeholder="Ex: Gallimard" type="text"/>
          </div>
          <div class="flex flex-col">
            <label class="text-sm font-semibold mb-1.5">Nombre d'exemplaires</label>
            <input name="nombre_exemplaire" min="1" value="1" class="h-11 w-full rounded-lg bg-surface-container-low px-3.5 text-sm placeholder:text-secondary focus:bg-surface-container-lowest focus:shadow-[0_0_0_2px_#2563eb] transition-all outline-none" type="number"/>
          </div>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1.5">Description</label>
          <textarea name="description" rows="4" class="w-full rounded-lg bg-surface-container-low p-3.5 text-sm placeholder:text-secondary focus:bg-surface-container-lowest focus:shadow-[0_0_0_2px_#2563eb] transition-all outline-none resize-none" placeholder="Résumé ou présentation du livre..."></textarea>
        </div>

        <div class="pt-2 flex flex-col gap-2">
          <button type="submit" class="h-11 w-full rounded-lg bg-primary-container text-on-primary text-base font-semibold flex items-center justify-center gap-2 shadow-sm hover:opacity-95 active:scale-[0.99] transition-all">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            <span>Ajouter</span>
          </button>
          <a href="index.php" class="h-11 w-full rounded-lg bg-surface-container-low text-secondary text-base font-semibold flex items-center justify-center gap-1.5 hover:bg-surface-container-high transition-all active:scale-[0.99]">
            <span class="material-symbols-outlined text-[18px]">close</span>
            <span>Annuler</span>
          </a>
        </div>
      </form>
    </div>
  </main>

  <footer class="py-6 flex items-center justify-center text-center">
    <p class="text-sm text-secondary">KOUASSI Amos — Bibliothèque en Ligne — 2026</p>
  </footer>

  <nav class="fixed bottom-0 w-full z-50 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around h-16 px-4">
      <a href="index.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-secondary hover:text-on-surface transition-colors">
        <span class="material-symbols-outlined text-[22px]">local_library</span>
        <span class="text-[11px] font-semibold">Accueil</span>
      </a>
      <a href="ajouter.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-primary-container font-semibold">
        <span class="material-symbols-outlined text-[22px]">bookmark_add</span>
        <span class="text-[11px] font-semibold">Ajouter</span>
      </a>
      <a href="wishlist.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-secondary hover:text-on-surface transition-colors">
        <span class="material-symbols-outlined text-[22px]">collections_bookmark</span>
        <span class="text-[11px] font-semibold">Ma Liste</span>
      </a>
    </div>
  </nav>
</body>
</html>
