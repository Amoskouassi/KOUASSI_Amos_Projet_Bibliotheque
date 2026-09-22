<?php
require_once __DIR__ . '/../app/helpers.php';
$pdo = require __DIR__ . '/../app/db.php';
start_session();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM livres WHERE id = :id');
$stmt->execute([':id' => $id]);
$livre = $stmt->fetch();

if (!$livre) {
  header('Location: index.php');
  exit;
}

// Vérifier si dans la liste de lecture
$id_user = $_SESSION['user_id'] ?? 1;
$check = $pdo->prepare('SELECT id FROM liste_lecture WHERE id_livre=:l AND id_user=:u');
$check->execute([':l' => $id, ':u' => $id_user]);
$dans_liste = $check->fetch() !== false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title><?php echo e($livre['titre']); ?> — Bibliothèque</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config={theme:{extend:{colors:{surface:"#faf8ff","surface-container-lowest":"#ffffff","surface-container-low":"#f2f3ff","surface-container":"#eaedff","surface-container-high":"#e2e7ff","on-surface":"#131b2e","on-surface-variant":"#434655","secondary":"#5a5f62","primary":"#004ac6","primary-container":"#2563eb","on-primary":"#ffffff","tertiary":"#006242","tertiary-container":"#007d55","on-tertiary":"#ffffff","error":"#ba1a1a","on-error":"#ffffff","inverse-surface":"#283044","inverse-on-surface":"#eef0ff","outline":"#737686"},fontFamily:{sans:["Inter","system-ui","sans-serif"]},spacing:{"gutter-sm":"1rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"}}}}};
  </script>
  <style>body{font-family:'Inter',system-ui,sans-serif;background:#faf8ff;color:#131b2e}</style>
</head>
<body class="bg-surface flex flex-col min-h-screen">
  <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-16 px-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <button onclick="history.back()" class="w-11 h-11 -ml-2 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors">
          <span class="material-symbols-outlined text-[22px]">arrow_back</span>
        </button>
        <h1 class="text-base font-semibold text-on-surface truncate">Détails Du Livre</h1>
      </div>
    </div>
  </header>

  <main class="flex-1 w-full pt-16 pb-6 px-4 bg-surface flex flex-col items-center">
    <div class="w-full max-w-[700px] flex flex-col">
      <article class="bg-surface-container-lowest rounded-xl shadow-md overflow-hidden flex flex-col">
        <div class="h-2 w-full bg-gradient-to-r from-primary to-primary-container"></div>
        <div class="p-6 flex flex-col">
          <div class="flex items-center justify-between gap-2 mb-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-container-high text-primary text-xs font-semibold uppercase tracking-wide">Livre</span>
          </div>

          <h2 class="text-2xl font-semibold tracking-tight"><?php echo e($livre['titre']); ?></h2>
          <p class="text-sm text-secondary mt-1">Par <span class="text-base font-semibold text-on-surface"><?php echo e($livre['auteur']); ?></span></p>

          <div class="w-full h-48 rounded-lg bg-surface-container mt-4 shadow-sm flex items-center justify-center">
            <span class="material-symbols-outlined text-6xl text-secondary">book</span>
          </div>

          <div class="h-px w-full bg-surface-container my-4"></div>

          <div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-3 mb-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <div class="flex flex-col">
                <span class="text-xs text-secondary uppercase tracking-wider">Auteur</span>
                <span class="text-base font-semibold text-on-surface"><?php echo e($livre['auteur']); ?></span>
              </div>
              <?php if ($livre['nombre_exemplaire'] > 0): ?>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-sm font-medium">
                  <span class="w-2 h-2 rounded-full bg-green-600"></span>
                  <?php echo $livre['nombre_exemplaire']; ?> exemplaire<?php echo $livre['nombre_exemplaire'] > 1 ? 's' : ''; ?>
                </div>
              <?php else: ?>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-sm font-medium">
                  <span class="w-2 h-2 rounded-full bg-red-600"></span>
                  Épuisé
                </div>
              <?php endif; ?>
            </div>
            <?php if ($livre['maison_edition']): ?>
              <div class="flex flex-col">
                <span class="text-xs text-secondary uppercase tracking-wider">Maison d'édition</span>
                <span class="text-sm text-on-surface"><?php echo e($livre['maison_edition']); ?></span>
              </div>
            <?php endif; ?>
          </div>

          <div class="flex flex-col mb-6">
            <div class="flex items-center gap-1 mb-1">
              <span class="material-symbols-outlined text-primary text-[20px]">auto_stories</span>
              <h3 class="text-base font-semibold">Synopsis &amp; Résumé</h3>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed text-justify"><?php echo e($livre['description']); ?></p>
          </div>

          <div class="h-px w-full bg-surface-container mb-4"></div>

          <div class="flex flex-col gap-3">
            <button onclick="toggleListe(<?php echo $livre['id']; ?>)" id="btn-liste" class="w-full h-11 px-4 rounded-lg <?php echo $dans_liste ? 'bg-green-600 text-white' : 'bg-tertiary-container text-on-tertiary'; ?> text-sm font-medium flex items-center justify-center gap-2 shadow-sm active:scale-[0.99] transition-all">
              <span class="material-symbols-outlined text-[20px]"><?php echo $dans_liste ? 'bookmark_check' : 'bookmark_add'; ?></span>
              <span id="btn-liste-label"><?php echo $dans_liste ? 'Dans votre liste' : 'Ajouter à ma liste'; ?></span>
            </button>
            <div class="grid grid-cols-2 gap-3">
              <a href="modifier.php?id=<?php echo $livre['id']; ?>" class="w-full h-11 px-4 rounded-lg bg-primary-container text-on-primary text-sm font-medium flex items-center justify-center gap-1 shadow-sm hover:opacity-90 transition-all">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                <span>Modifier</span>
              </a>
              <button onclick="supprimer(<?php echo $livre['id']; ?>)" class="w-full h-11 px-4 rounded-lg bg-error text-on-error text-sm font-medium flex items-center justify-center gap-1 shadow-sm active:scale-[0.99] transition-all">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                <span>Supprimer</span>
              </button>
            </div>
            <button onclick="history.back()" class="w-full h-11 px-4 rounded-lg bg-surface-container-low text-on-surface text-sm font-medium flex items-center justify-center gap-1 active:scale-[0.99] transition-all">
              <span class="material-symbols-outlined text-[18px]">arrow_back</span>
              <span>Retour</span>
            </button>
          </div>
        </div>
      </article>
    </div>

    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 opacity-0 pointer-events-none transition-all duration-200 z-40">
      <span class="material-symbols-outlined text-[18px] text-green-400">check_circle</span>
      <span id="toast-msg" class="text-sm truncate">Action confirmée</span>
    </div>
  </main>

  <footer class="py-6 flex items-center justify-center text-center">
    <p class="text-sm text-secondary">KOUASSI Amos — Bibliothèque en Ligne — 2026</p>
  </footer>

  <script>
    function showToast(msg) {
      const t = document.getElementById('toast');
      document.getElementById('toast-msg').textContent = msg;
      t.classList.remove('opacity-0','pointer-events-none');
      t.classList.add('opacity-100');
      setTimeout(() => { t.classList.remove('opacity-100'); t.classList.add('opacity-0','pointer-events-none'); }, 2400);
    }

    <?php if ($dans_liste): ?>
    let dansListe = true;
    <?php else: ?>
    let dansListe = false;
    <?php endif; ?>

    async function toggleListe(id) {
      const btn = document.getElementById('btn-liste');
      const label = document.getElementById('btn-liste-label');
      const fd = new FormData();
      fd.append('id_livre', id);
      const action = dansListe ? 'remove' : 'add';
      await fetch('api_liste.php?action=' + action, { method: 'POST', body: fd });
      dansListe = !dansListe;
      if (dansListe) {
        btn.className = 'w-full h-11 px-4 rounded-lg bg-green-600 text-white text-sm font-medium flex items-center justify-center gap-2 shadow-sm active:scale-[0.99] transition-all';
        btn.querySelector('.material-symbols-outlined').textContent = 'bookmark_check';
        label.textContent = 'Dans votre liste';
        showToast('Ajouté à votre liste !');
      } else {
        btn.className = 'w-full h-11 px-4 rounded-lg bg-tertiary-container text-on-tertiary text-sm font-medium flex items-center justify-center gap-2 shadow-sm active:scale-[0.99] transition-all';
        btn.querySelector('.material-symbols-outlined').textContent = 'bookmark_add';
        label.textContent = 'Ajouter à ma liste';
        showToast('Retiré de votre liste');
      }
    }

    async function supprimer(id) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) return;
      const fd = new FormData();
      fd.append('id', id);
      await fetch('api_livres.php?action=delete', { method: 'POST', body: fd });
      showToast('Livre supprimé');
      setTimeout(() => window.location.href = 'index.php', 1000);
    }
  </script>
</body>
</html>
