<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration — Bibliothèque</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="header">
    <div class="header-inner">
      <a href="index.html" class="logo">📚 Bibliothèque</a>
      <nav class="nav">
        <a href="index.html">Accueil</a>
        <a href="ajouter.html">Ajouter</a>
        <a href="wishlist.html">Ma Liste</a>
        <a href="admin.php" class="active">Admin</a>
        <a href="#" id="logout-link">Déconnexion</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <div class="page-title">
      <h1>Panel d'Administration</h1>
      <p class="text-muted">Gestion complète de la bibliothèque</p>
    </div>

    <div class="admin-stats">
      <div class="stat-card">
        <div class="stat-number" id="stat-livres">—</div>
        <div class="stat-label">Livres</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="stat-emprunts">—</div>
        <div class="stat-label">Emprunts en cours</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="stat-users">—</div>
        <div class="stat-label">Utilisateurs</div>
      </div>
    </div>

    <div class="admin-section">
      <div class="section-header">
        <h2>Gestion des livres</h2>
        <a href="ajouter.html" class="btn btn-primary btn-sm">+ Ajouter</a>
      </div>
      <table class="table" id="admin-table-livres">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Édition</th>
            <th>Exemplaires</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="admin-section">
      <div class="section-header">
        <h2>Utilisateurs</h2>
      </div>
      <table class="table" id="admin-table-users">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </main>

  <script>
    async function loadAdmin(){
      const res = await fetch('api_admin.php?action=stats');
      const data = await res.json();
      document.getElementById('stat-livres').textContent = data.stats.livres;
      document.getElementById('stat-emprunts').textContent = data.stats.emprunts;
      document.getElementById('stat-users').textContent = data.stats.users;

      data.livres.forEach(l => {
        document.querySelector('#admin-table-livres tbody').innerHTML += `
          <tr>
            <td>${l.id}</td>
            <td>${l.titre}</td>
            <td>${l.auteur}</td>
            <td>${l.maison_edition}</td>
            <td><span class="badge ${l.nombre_exemplaire>0?'badge-success':'badge-danger'}">${l.nombre_exemplaire}</span></td>
            <td>
              <a href="modifier.html?id=${l.id}" class="btn btn-sm btn-primary">Modifier</a>
              <button onclick="supprimer(${l.id})" class="btn btn-sm btn-danger">Supprimer</button>
            </td>
          </tr>`;
      });

      data.users.forEach(u => {
        document.querySelector('#admin-table-users tbody').innerHTML += `
          <tr>
            <td>${u.id}</td>
            <td>${u.nom}</td>
            <td>${u.prenom}</td>
            <td>${u.email}</td>
            <td><span class="badge ${u.role==='admin'?'badge-primary':'badge-success'}">${u.role}</span></td>
          </tr>`;
      });
    }

    async function supprimer(id){
      if(!confirm('Supprimer ce livre ?')) return;
      const fd = new FormData();
      fd.append('id', id);
      await fetch('api_livres.php?action=delete',{method:'POST',body:fd});
      location.reload();
    }

    document.getElementById('logout-link').addEventListener('click', async function(e){
      e.preventDefault();
      await fetch('api_auth.php?action=logout');
      window.location.href = 'login.html';
    });

    loadAdmin();
  </script>
</body>
</html>
