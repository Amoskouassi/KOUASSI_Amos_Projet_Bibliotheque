<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
  $stmt = $pdo->prepare('SELECT * FROM livres WHERE titre LIKE :q OR auteur LIKE :q ORDER BY titre');
  $stmt->execute([':q' => "%$q%"]);
} else {
  $stmt = $pdo->query('SELECT * FROM livres ORDER BY titre');
}
$livres = $stmt->fetchAll();
$count = count($livres);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title>Bibliothèque en Ligne</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","surface-container":"#eaedff","surface-container-high":"#e2e7ff","surface-container-highest":"#dae2fd","on-surface":"#131b2e","on-surface-variant":"#434655","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff","tertiary":"#006242","tertiary-container":"#007d55","on-tertiary":"#ffffff","error":"#ba1a1a","error-container":"#ffdad6","on-error-container":"#93000a","primary-fixed":"#dbe1ff","on-primary-fixed":"#00174b","outline":"#737686","inverse-surface":"#283044","inverse-on-surface":"#eef0ff"},fontFamily:{sans:["Inter","system-ui","sans-serif"]},spacing:{"gutter-sm":"1rem","space-sm":"0.5rem","space-lg":"1.5rem","space-xs":"0.25rem","gutter":"1.5rem","space-md":"1rem"}}}}};
  </script>
  <style>
    body{font-family:'Inter',system-ui,sans-serif;background:#faf8ff;color:#131b2e}
    .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  </style>
</head>
<body class="bg-surface flex flex-col min-h-screen">
  <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-16 px-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container">
          <span class="material-symbols-outlined text-[20px]">auto_stories</span>
        </div>
        <span class="text-lg font-semibold tracking-tight text-on-surface">Bibliothèque</span>
      </div>
      <div class="flex items-center gap-2">
        <?php if (isset($_SESSION['user_id'])): ?>
          <span class="text-sm text-secondary"><?php echo e($_SESSION['user_prenom']); ?></span>
          <a href="api_auth.php?action=logout" class="text-sm text-primary font-semibold">Déconnexion</a>
        <?php else: ?>
          <a href="login.php" class="text-sm text-primary font-semibold">Connexion</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main class="flex-1 w-full pt-16 pb-28 px-4 bg-surface flex flex-col">
    <div class="flex flex-col w-full">
      <!-- Hero -->
      <section class="flex flex-col items-center text-center pt-6 pb-4 px-4">
        <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed mb-2 shadow-sm">
          <span class="material-symbols-outlined text-[16px]">menu_book</span>
          <span class="text-xs font-semibold">Espace de Lecture Ouvert</span>
        </div>
        <h1 class="text-2xl font-semibold tracking-tight max-w-[320px]">Bienvenue à la Bibliothèque</h1>
        <p class="text-sm text-secondary max-w-[420px] mt-2">Découvrez notre collection de livres. Recherchez, consultez, et gérez votre liste de lecture.</p>
        <div class="flex items-center justify-center gap-6 mt-6 w-full max-w-sm">
          <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-primary shadow-sm">
              <span class="material-symbols-outlined text-[22px]">search</span>
            </div>
            <span class="text-xs font-semibold mt-2">Rechercher</span>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-primary shadow-sm">
              <span class="material-symbols-outlined text-[22px]">auto_stories</span>
            </div>
            <span class="text-xs font-semibold mt-2">Consulter</span>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-primary shadow-sm">
              <span class="material-symbols-outlined text-[22px]">bookmarks</span>
            </div>
            <span class="text-xs font-semibold mt-2">Gérer</span>
          </div>
        </div>
      </section>

      <!-- Recherche -->
      <section class="w-full px-4 mt-2">
        <form method="GET" class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex flex-col gap-3">
          <div class="relative w-full flex items-center">
            <span class="material-symbols-outlined text-secondary absolute left-4 text-[20px]">search</span>
            <input name="q" value="<?php echo e($q); ?>" class="w-full h-12 pl-11 pr-4 rounded-full bg-surface-container-low text-on-surface text-sm placeholder:text-secondary focus:outline-none focus:bg-surface-container-lowest shadow-inner transition-colors" placeholder="Rechercher par titre ou auteur..." type="text"/>
          </div>
          <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 h-10 rounded-full bg-primary-container text-on-primary text-sm font-medium flex items-center justify-center gap-1 shadow-sm">
              <span class="material-symbols-outlined text-[18px]">manage_search</span>
              <span>Rechercher</span>
            </button>
            <a href="index.php" class="px-4 h-10 rounded-full bg-surface-container-high text-on-surface-variant text-sm font-medium hover:bg-surface-container-highest transition-colors flex items-center justify-center">Réinitialiser</a>
          </div>
        </form>
      </section>

      <!-- Collection -->
      <section class="flex flex-col px-4 pt-6 pb-4">
        <div class="flex items-baseline justify-between mb-4">
          <div class="flex items-center gap-1">
            <h2 class="text-lg font-semibold text-on-surface">Collection</h2>
            <span class="w-2 h-2 rounded-full bg-primary-container"></span>
          </div>
          <span class="text-sm text-secondary"><?php echo $count; ?> ouvrage<?php echo $count > 1 ? 's' : ''; ?> <?php echo $q ? 'trouvé' . ($count > 1 ? 's' : '') : 'répertorié' . ($count > 1 ? 's' : ''); ?></span>
        </div>

        <?php if ($count === 0): ?>
          <div class="flex flex-col items-center justify-center text-center p-8 bg-surface-container-lowest rounded-xl shadow-sm">
            <div class="w-14 h-14 rounded-full bg-surface-container flex items-center justify-center text-secondary mb-3">
              <span class="material-symbols-outlined text-[28px]">search_off</span>
            </div>
            <h4 class="text-base font-semibold">Aucun livre trouvé</h4>
            <p class="text-sm text-secondary mt-1">Vérifiez l'orthographe du titre ou du nom de l'auteur.</p>
          </div>
        <?php else: ?>
          <div class="flex flex-col gap-4">
            <?php foreach ($livres as $livre): ?>
              <article class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col gap-4 hover:-translate-y-0.5 transition-transform duration-200">
                <div class="flex gap-4 items-start">
                  <div class="w-20 h-28 rounded-lg bg-surface-container-high shrink-0 shadow-sm flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-secondary">book</span>
                  </div>
                  <div class="flex flex-col flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-on-surface truncate"><?php echo e($livre['titre']); ?></h3>
                    <p class="text-xs text-secondary"><?php echo e($livre['auteur']); ?></p>
                    <div class="mt-2">
                      <?php if ($livre['nombre_exemplaire'] > 0): ?>
                        <span class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">
                          <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                          <?php echo $livre['nombre_exemplaire']; ?> exemplaire<?php echo $livre['nombre_exemplaire'] > 1 ? 's' : ''; ?>
                        </span>
                      <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-700 px-2 py-0.5 rounded-full">
                          <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                          Épuisé
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <p class="text-xs text-on-surface-variant line-clamp-2"><?php echo e($livre['description']); ?></p>
                <div class="flex items-center gap-2 pt-0">
                  <a href="details.php?id=<?php echo $livre['id']; ?>" class="flex-1 h-9 rounded-lg bg-primary-container text-on-primary text-sm font-medium flex items-center justify-center gap-1 shadow-sm hover:opacity-90 transition-all">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>Voir détails</span>
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <footer class="mt-auto py-6 flex items-center justify-center text-center">
    <p class="text-sm text-secondary">KOUASSI Amos — Bibliothèque en Ligne — 2026</p>
  </footer>

  <nav class="fixed bottom-0 w-full z-50 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around h-16 px-4">
      <a href="index.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-primary-container font-semibold">
        <span class="material-symbols-outlined text-[22px]">local_library</span>
        <span class="text-[11px] font-semibold">Accueil</span>
      </a>
      <a href="ajouter.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-secondary hover:text-on-surface transition-colors">
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
