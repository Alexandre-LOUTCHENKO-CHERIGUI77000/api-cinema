<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>

<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $movies = filmParGenre($id);
}
?>

<div class="album py-5 bg-body-tertiary">
  <div class="container">
    <h4>Films par genre</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
      <?php if(isset($movies)): ?>
        <?php foreach($movies as $movie): ?>
          <div class="col">
            <div class="card shadow-sm h-100">
              <img src="<?= 'https://image.tmdb.org/t/p/w780/'.$movie['poster_path'] ?>" class="card-img-top" style="height:340px;object-fit:contain;background:#111;" alt="<?= htmlspecialchars($movie['title']) ?>">
              <div class="card-body lh-sm d-flex flex-column">
                <p class="lh-sm"><strong><?= htmlspecialchars($movie['title']) ?></strong></p>
                <a href="movie.php?id=<?= $movie['id'] ?>" class="btn btn-primary mt-auto">View</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require("footer.php"); ?>
