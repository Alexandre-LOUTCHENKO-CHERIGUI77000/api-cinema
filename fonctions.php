<?php

require_once("get-proxy.php");

$API_KEY = "9e43f45f94705cc8e1d5a0400d19a7b7";
$BASE_URL = "https://api.themoviedb.org/3";

function apiGet($endpoint) {
    global $API_KEY, $BASE_URL;
    $sep = strpos($endpoint, '?') !== false ? '&' : '?';
    $url = $BASE_URL . $endpoint . $sep . "api_key=" . $API_KEY . "&language=fr-FR";
    $response = getProxy($url);
    return json_decode($response, true);
}

function popularMovies() {
    $result = apiGet("/movie/popular");
    return $result['results'];
}

function topRatedMovies() {
    $result = apiGet("/movie/top_rated");
    return $result['results'];
}

function filmParGenre($id) {
    $result = apiGet("/discover/movie?with_genres=$id");
    return $result['results'];
}

function movieDetails($id) {
    return apiGet("/movie/$id");
}

function movieCredits($id) {
    return apiGet("/movie/$id/credits");
}

function searchMovies($query) {
    $q = urlencode($query);
    $result = apiGet("/search/movie?query=$q");
    return $result['results'];
}

function searchActors($query) {
    $q = urlencode($query);
    $result = apiGet("/search/person?query=$q");
    return $result['results'];
}

function actorDetails($id) {
    return apiGet("/person/$id");
}

function actorMovies($id) {
    $result = apiGet("/person/$id/movie_credits");
    return isset($result['cast']) ? $result['cast'] : [];
}
?>
