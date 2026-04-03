<?php
require_once("fonctions.php");

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : 'movie';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 2) { echo '[]'; exit; }

if ($type === 'movie') {
    $results = searchMovies($q);
    $out = [];
    foreach (array_slice($results, 0, 6) as $m) {
        $out[] = ['id' => $m['id'], 'name' => $m['title'], 'img' => $m['poster_path']];
    }
} else {
    $results = searchActors($q);
    $out = [];
    foreach (array_slice($results, 0, 6) as $a) {
        $out[] = ['id' => $a['id'], 'name' => $a['name'], 'img' => $a['profile_path']];
    }
}

echo json_encode($out);
