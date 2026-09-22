// app.js — Bibliothèque en ligne

const API = 'api_livres.php';
const API_LISTE = 'api_liste.php';

// ===== LIVRES =====

function searchLivres(q = '') {
  const action = q ? 'search' : 'list';
  fetch(`${API}?action=${action}&q=${encodeURIComponent(q)}`)
    .then(r => r.json())
    .then(data => renderLivres(data));
}

function renderLivres(livres) {
  const grid = document.getElementById('bookGrid');
  if (!grid) return;
  if (livres.length === 0) {
    grid.innerHTML = '<p>Aucun livre trouvé.</p>';
    return;
  }
  grid.innerHTML = livres.map(l => `
    <div class="book-card">
      <h3>${esc(l.titre)}</h3>
      <div class="meta">${esc(l.auteur)} — ${esc(l.maison_edition)}</div>
      <p>${esc(l.description).substring(0, 120)}…</p>
      <p><span class="badge ${l.nombre_exemplaire > 0 ? 'badge-success' : 'badge-danger'}">
        ${l.nombre_exemplaire} exemplaire(s)
      </span></p>
      <a class="btn btn-primary btn-sm" href="details.html?id=${l.id}">Voir détails</a>
    </div>
  `).join('');
}

function getLivre(id) {
  return fetch(`${API}?action=get&id=${id}`).then(r => r.json());
}

function addLivre(form) {
  const fd = new FormData(form);
  fd.append('action', 'add');
  fetch(API, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) { alert('Livre ajouté !'); window.location.href = 'index.html'; }
      else alert(d.msg || 'Erreur');
    });
}

function updateLivre(form) {
  const fd = new FormData(form);
  fd.append('action', 'update');
  fetch(API, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) { alert('Livre mis à jour !'); window.location.href = 'index.html'; }
      else alert(d.msg || 'Erreur');
    });
}

function deleteLivre(id) {
  if (!confirm('Supprimer ce livre ?')) return;
  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('id', id);
  fetch(API, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) { alert('Supprimé.'); window.location.href = 'index.html'; }
    });
}

// ===== LISTE DE LECTURE =====

function loadListe() {
  fetch(`${API_LISTE}?action=list`)
    .then(r => r.json())
    .then(data => renderListe(data));
}

function renderListe(items) {
  const div = document.getElementById('listeContent');
  if (!div) return;
  if (items.length === 0) {
    div.innerHTML = '<p>Votre liste de lecture est vide.</p>';
    return;
  }
  div.innerHTML = `
    <table>
      <tr><th>Titre</th><th>Auteur</th><th>Emprunt</th><th>Retour</th><th>Action</th></tr>
      ${items.map(i => `
        <tr>
          <td>${esc(i.titre)}</td>
          <td>${esc(i.auteur)}</td>
          <td>${esc(i.date_emprunt)}</td>
          <td>${i.date_retour ? esc(i.date_retour) : '—'}</td>
          <td><button class="btn btn-danger btn-sm" onclick="removeFromListe(${i.id_livre})">Retirer</button></td>
        </tr>
      `).join('')}
    </table>
  `;
}

function addToListe(idLivre) {
  const fd = new FormData();
  fd.append('action', 'add');
  fd.append('id_livre', idLivre);
  fetch(API_LISTE, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) { alert('Ajouté à votre liste !'); checkInListe(idLivre); }
      else alert(d.msg || 'Erreur');
    });
}

function removeFromListe(idLivre) {
  const fd = new FormData();
  fd.append('action', 'remove');
  fd.append('id_livre', idLivre);
  fetch(API_LISTE, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) { alert('Retiré.'); loadListe(); }
    });
}

function checkInListe(idLivre) {
  fetch(`${API_LISTE}?action=check&id_livre=${idLivre}`)
    .then(r => r.json())
    .then(d => {
      const btn = document.getElementById('btnListe');
      if (btn) {
        if (d.in_list) {
          btn.textContent = 'Dans ma liste ✓';
          btn.disabled = true;
          btn.className = 'btn btn-secondary';
        } else {
          btn.textContent = 'Ajouter à ma liste';
          btn.disabled = false;
          btn.className = 'btn btn-success';
        }
      }
    });
}

// ===== UTILS =====

function esc(s) {
  const d = document.createElement('div');
  d.textContent = s || '';
  return d.innerHTML;
}
