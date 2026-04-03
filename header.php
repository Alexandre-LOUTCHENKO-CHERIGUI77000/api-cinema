<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <title>Movies</title>
  <style>
    .autocomplete-wrapper { position: relative; }
    .autocomplete-list {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      z-index: 9999;
      max-height: 280px;
      overflow-y: auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .autocomplete-list a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      text-decoration: none;
      color: #333;
      border-bottom: 1px solid #f0f0f0;
    }
    .autocomplete-list a:hover { background: #f8f9fa; }
    .autocomplete-list img { width: 36px; height: 54px; object-fit: cover; border-radius: 4px; background:#eee; }
    .autocomplete-list .no-img { width:36px; height:54px; background:#ddd; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#999; font-size:18px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow py-2">
  <div class="container">
    <a href="popular.php" class="navbar-brand d-flex align-items-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-film" viewBox="0 0 16 16">
        <path d="M0 1a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1zm4 0v6h8V1H4zm8 8H4v6h8V9zM1 1v2h2V1H1zm2 3H1v2h2V4zM1 7v2h2V7H1zm2 3H1v2h2v-2zm-2 3v2h2v-2H1z"/>
      </svg>
      <strong class="p-2">Films</strong>
    </a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="popular.php">Top films</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="topRated.php">Top Rated</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Genre</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="genreMovies.php?id=28">Action</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=12">Aventure</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=16">Animation</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=35">Comédie</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=80">Crime</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=99">Documentaire</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=18">Drame</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=10751">Famille</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=14">Fantaisie</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=36">Histoire</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=27">Horreur</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=10402">Musique</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=878">Science-fiction</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=53">Thriller</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=10752">Guerre</a></li>
            <li><a class="dropdown-item" href="genreMovies.php?id=37">Western</a></li>
          </ul>
        </li>
      </ul>

      <ul class="navbar-nav gap-2">
        <!-- Recherche Films -->
        <li class="nav-item">
          <div class="autocomplete-wrapper">
            <input type="text" id="searchFilm" class="form-control rounded-pill" placeholder="Search Films" autocomplete="off" style="max-width:200px;">
            <div class="autocomplete-list" id="filmList" style="display:none;"></div>
          </div>
        </li>
        <!-- Recherche Acteurs -->
        <li class="nav-item">
          <div class="autocomplete-wrapper">
            <input type="text" id="searchActor" class="form-control rounded-pill" placeholder="Search Acteurs" autocomplete="off" style="max-width:200px;">
            <div class="autocomplete-list" id="actorList" style="display:none;"></div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main>

<script>
function debounce(fn, delay) {
  let timer;
  return function(...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

function setupAutocomplete(inputId, listId, type) {
  const input = document.getElementById(inputId);
  const list = document.getElementById(listId);

  const search = debounce(async function(query) {
    if (query.length < 2) { list.style.display = 'none'; return; }
    try {
      const res = await fetch(`autocomplete.php?type=${type}&q=${encodeURIComponent(query)}`);
      const items = await res.json();
      if (!items.length) { list.style.display = 'none'; return; }
      list.innerHTML = items.map(item => {
        const img = item.img
          ? `<img src="https://image.tmdb.org/t/p/w92${item.img}" alt="">`
          : `<div class="no-img"><i class="bi bi-person-fill"></i></div>`;
        const href = type === 'movie' ? `movie.php?id=${item.id}` : `actor.php?id=${item.id}`;
        return `<a href="${href}">${img}<span>${item.name}</span></a>`;
      }).join('');
      list.style.display = 'block';
    } catch(e) { list.style.display = 'none'; }
  }, 300);

  input.addEventListener('input', () => search(input.value.trim()));
  document.addEventListener('click', e => {
    if (!input.contains(e.target) && !list.contains(e.target)) list.style.display = 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
  setupAutocomplete('searchFilm', 'filmList', 'movie');
  setupAutocomplete('searchActor', 'actorList', 'actor');
});
</script>
