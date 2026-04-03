<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>

<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: popular.php'); exit;
}
$id = intval($_GET['id']);
$movie = movieDetails($id);
$credits = movieCredits($id);
$cast = isset($credits['cast']) ? $credits['cast'] : [];

$poster = $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'images/movie1.png';
$backdrop = $movie['backdrop_path'] ? 'https://image.tmdb.org/t/p/w1280' . $movie['backdrop_path'] : '';
$note = round($movie['vote_average'], 1);
$genres = isset($movie['genres']) ? array_map(fn($g) => $g['name'], $movie['genres']) : [];
$runtime = $movie['runtime'] ? intdiv($movie['runtime'], 60) . 'h ' . ($movie['runtime'] % 60) . 'min' : 'N/A';
?>

<?php if ($backdrop): ?>
<div style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('<?= $backdrop ?>') center/cover no-repeat; min-height:340px; display:flex; align-items:center;">
<?php else: ?>
<div style="background:#1a1a2e; min-height:340px; display:flex; align-items:center;">
<?php endif; ?>
  <div class="container py-4">
    <div class="row align-items-center g-4">
      <div class="col-md-3 text-center">
        <img src="<?= $poster ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="img-fluid rounded shadow" style="max-height:380px;">
      </div>
      <div class="col-md-9 text-white">
        <h1 class="fw-bold"><?= htmlspecialchars($movie['title']) ?></h1>
        <?php if ($movie['tagline']): ?>
          <p class="fst-italic text-warning">"<?= htmlspecialchars($movie['tagline']) ?>"</p>
        <?php endif; ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <?php foreach ($genres as $g): ?>
            <span class="badge bg-primary"><?= htmlspecialchars($g) ?></span>
          <?php endforeach; ?>
        </div>
        <div class="d-flex gap-4 mb-3 flex-wrap">
          <span><i class="bi bi-star-fill text-warning"></i> <strong><?= $note ?></strong>/10
            <small class="text-muted">(<?= number_format($movie['vote_count']) ?> votes)</small>
          </span>
          <span><i class="bi bi-clock"></i> <?= $runtime ?></span>
          <span><i class="bi bi-calendar"></i> <?= substr($movie['release_date'], 0, 4) ?></span>
        </div>
        <p style="max-width:700px;"><?= htmlspecialchars($movie['overview'] ?: 'Aucun synopsis disponible.') ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Casting -->
<?php if ($cast): ?>
<div class="container my-5">
  <h4 class="mb-4 fw-bold">Casting principal</h4>
  <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-3">
    <?php foreach ($cast as $actor): ?>
    <div class="col">
      <a href="actor.php?id=<?= $actor['id'] ?>" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm border-0 text-center">
          <?php if ($actor['profile_path']): ?>
            <img src="https://image.tmdb.org/t/p/w185<?= $actor['profile_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($actor['name']) ?>" style="height:180px;object-fit:contain;">
          <?php else: ?>
            <div style="height:180px;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-person-fill" style="font-size:3rem;color:#adb5bd;"></i>
            </div>
          <?php endif; ?>
          <div class="card-body py-2 px-1">
            <p class="mb-0 fw-semibold small"><?= htmlspecialchars($actor['name']) ?></p>
            <p class="mb-0 text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($actor['character']) ?></p>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php require("footer.php"); ?>
