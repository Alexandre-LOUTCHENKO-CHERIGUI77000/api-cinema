<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>

<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: popular.php'); exit;
}
$id = intval($_GET['id']);
$actor = actorDetails($id);
$movies = actorMovies($id);
usort($movies, fn($a, $b) => ($b['popularity'] ?? 0) <=> ($a['popularity'] ?? 0));
$movies = array_slice($movies, 0, 12);

$photo = $actor['profile_path'] ? 'https://image.tmdb.org/t/p/w500' . $actor['profile_path'] : null;
$age = '';
if ($actor['birthday']) {
    $birth = new DateTime($actor['birthday']);
    $end = $actor['deathday'] ? new DateTime($actor['deathday']) : new DateTime();
    $age = $birth->diff($end)->y . ' ans';
}
?>

<div class="bg-dark text-white py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-md-3 text-center">
        <?php if ($photo): ?>
          <img src="<?= $photo ?>" class="img-fluid rounded-circle shadow" style="width:200px;height:200px;object-fit:contain;object-position:top;">
        <?php else: ?>
          <div style="width:200px;height:200px;background:#444;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:auto;">
            <i class="bi bi-person-fill" style="font-size:5rem;color:#888;"></i>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-9">
        <h1 class="fw-bold"><?= htmlspecialchars($actor['name']) ?></h1>
        <?php if ($actor['known_for_department']): ?>
          <span class="badge bg-secondary mb-3"><?= htmlspecialchars($actor['known_for_department']) ?></span>
        <?php endif; ?>
        <div class="d-flex gap-4 mb-3 flex-wrap text-light">
          <?php if ($actor['birthday']): ?>
            <span><i class="bi bi-calendar-heart"></i> <?= $actor['birthday'] ?> (<?= $age ?>)</span>
          <?php endif; ?>
          <?php if ($actor['place_of_birth']): ?>
            <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($actor['place_of_birth']) ?></span>
          <?php endif; ?>
        </div>
        <?php if ($actor['biography']): ?>
          <p style="max-width:750px;"><?= nl2br(htmlspecialchars(substr($actor['biography'], 0, 800))) ?><?= strlen($actor['biography']) > 800 ? '...' : '' ?></p>
        <?php else: ?>
          <p class="text-muted">Aucune biographie disponible.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Filmographie -->
<?php if ($movies): ?>
<div class="container my-5">
  <h4 class="mb-4 fw-bold">Filmographie</h4>
  <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4">
    <?php foreach ($movies as $movie): ?>
    <div class="col">
      <a href="movie.php?id=<?= $movie['id'] ?>" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm border-0">
          <?php if ($movie['poster_path']): ?>
            <img src="https://image.tmdb.org/t/p/w342<?= $movie['poster_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>" style="height:280px;object-fit:contain;">
          <?php else: ?>
            <div style="height:280px;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-film" style="font-size:3rem;color:#adb5bd;"></i>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <p class="mb-1 fw-semibold small"><?= htmlspecialchars($movie['title']) ?></p>
            <?php if ($movie['character']): ?>
              <p class="text-muted mb-0" style="font-size:0.75rem;">Rôle : <?= htmlspecialchars($movie['character']) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php require("footer.php"); ?>
