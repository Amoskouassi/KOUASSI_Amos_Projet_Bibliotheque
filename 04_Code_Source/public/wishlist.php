<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$id_user = $_SESSION['user_id'] ?? 1;

$stmt = $pdo->prepare('
  SELECT ll.*, l.titre, l.auteur, l.description, l.nombre_exemplaire
  FROM liste_lecture ll
  JOIN livres l ON ll.id_livre = l.id
  WHERE ll.id_user = :uid
  ORDER BY ll.date_emprunt DESC
');
$stmt->execute([':uid' => $id_user]);
$emprunts = $stmt->fetchAll();
$count = count($emprunts);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title>Ma Liste de Lecture — Bibliothèque</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","surface-container":"#eaedff","surface-container-high":"#e2e7ff","on-surface":"#131b2e","on-surface-variant":"#434655","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff","tertiary":"#006242","tertiary-container":"#007d55","on-tertiary":"#ffffff","error":"#ba1a1a","error-container":"#ffdad6","on-error-container":"#93000a","outline":"#737686"},fontFamily:{sans:["Inter","system-ui","sans-serif"]},spacing:{"gutter-sm":"1rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"}}}}};
  </script>
  <style>body{font-family:'Inter',system-ui,sans-serif;background:#faf8ff;color:#131b2e}</style>
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
        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-[18px]">person</span>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-1 w-full pt-16 pb-28 px-4 bg-surface flex flex-col">
    <div class="flex flex-col w-full pb-6">
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shadow-sm">
              <span class="material-symbols-outlined text-[22px]">collections_bookmark</span>
            </div>
            <div>
              <h1 class="text-xl font-bold tracking-tight">Ma Liste de Lecture</h1>
              <p class="text-sm text-secondary">Les livres que vous souhaitez lire</p>
            </div>
          </div>
          <span class="bg-surface-container-high text-primary px-2 py-1 rounded-full text-xs font-semibold shadow-sm"><?php echo $count; ?> emprunt<?php echo $count > 1 ? 's' : ''; ?></span>
        </div>
      </div>

      <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden w-full">
        <div class="bg-surface-container-low px-4 py-3 flex items-center justify-between">
          <span class="text-sm font-bold uppercase tracking-wider">Vos Emprunts Récents</span>
          <span class="text-xs text-secondary">Synchronisé</span>
        </div>

        <?php if ($count === 0): ?>
          <div class="p-8 text-center flex flex-col items-center">
            <div class="w-24 h-24 rounded-full bg-surface-container-low flex items-center justify-center shadow-sm mb-3">
              <span class="text-[36px]">📭</span>
            </div>
            <h3 class="text-base font-bold mb-1">Votre liste est vide</h3>
            <p class="text-sm text-secondary max-w-xs mb-4">Parcourez notre collection et ajoutez des livres à votre liste de lecture.</p>
            <a href="index.php" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-container transition-colors shadow-sm">
              <span class="material-symbols-outlined text-[18px]">explore</span>
              <span>Explorer la bibliothèque</span>
            </a>
          </div>
        <?php else: ?>
          <div class="flex flex-col" id="reading-list">
            <?php foreach ($emprunts as $i => $emp): ?>
              <div class="p-4 <?php echo $i % 2 ? 'bg-surface-container-low/40' : 'bg-surface-container-lowest'; ?> transition-all duration-300 flex flex-col gap-3" id="row-<?php echo $emp['id']; ?>">
                <div class="flex items-start justify-between gap-3">
                  <div class="flex items-start gap-3 min-w-0">
                    <div class="w-12 h-16 rounded-lg bg-surface-container-high overflow-hidden shrink-0 shadow-sm flex items-center justify-center">
                      <span class="material-symbols-outlined text-2xl text-secondary">book</span>
                    </div>
                    <div class="min-w-0">
                      <h2 class="text-base font-bold truncate"><?php echo e($emp['titre']); ?></h2>
                      <p class="text-sm text-secondary"><?php echo e($emp['auteur']); ?></p>
                      <div class="flex items-center gap-1.5 mt-1.5">
                        <span class="text-xs text-secondary">Emprunt :</span>
                        <span class="text-xs font-medium"><?php echo e($emp['date_emprunt']); ?></span>
                      </div>
                    </div>
                  </div>
                  <button onclick="retirer(<?php echo $emp['id']; ?>, '<?php echo e(addslashes($emp['titre'])); ?>')" class="h-8 px-3 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white text-xs font-semibold flex items-center gap-1 transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[16px]">bookmark_remove</span>
                    <span>Retirer</span>
                  </button>
                </div>
                <?php if ($emp['date_retour']): ?>
                  <div class="flex items-center justify-between pt-1">
                    <span class="text-xs text-secondary">Retour :</span>
                    <span class="bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-semibold"><?php echo e($emp['date_retour']); ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <?php if ($i < $count - 1): ?>
                <div class="h-[1px] bg-surface-container-high w-full"></div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div id="toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 opacity-0 pointer-events-none transition-all duration-300 z-50">
      <span class="material-symbols-outlined text-[18px] text-green-400">check_circle</span>
      <span id="toast-msg" class="text-sm font-medium">Livre retiré</span>
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
      <a href="ajouter.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-secondary hover:text-on-surface transition-colors">
        <span class="material-symbols-outlined text-[22px]">bookmark_add</span>
        <span class="text-[11px] font-semibold">Ajouter</span>
      </a>
      <a href="wishlist.php" class="flex flex-col items-center justify-center min-w-[56px] min-h-[44px] gap-1 text-primary-container font-semibold">
        <span class="material-symbols-outlined text-[22px]">collections_bookmark</span>
        <span class="text-[11px] font-semibold">Ma Liste</span>
      </a>
    </div>
  </nav>

  <script>
    function showToast(msg) {
      const t = document.getElementById('toast');
      document.getElementById('toast-msg').textContent = msg;
      t.classList.remove('opacity-0','pointer-events-none');
      t.classList.add('opacity-100');
      setTimeout(() => { t.classList.remove('opacity-100'); t.classList.add('opacity-0','pointer-events-none'); }, 3000);
    }

    async function retirer(id, titre) {
      const fd = new FormData();
      fd.append('id_livre', id);
      await fetch('api_liste.php?action=remove', { method: 'POST', body: fd });
      const row = document.getElementById('row-' + id);
      if (row) {
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => { row.remove(); showToast('"' + titre + '" retiré de votre liste.'); }, 250);
      }
    }
  </script>
</body>
</html>
