<?php
$list = $_GET["list"] ?? "now_playing";
$list_pretty = "";
switch ($list) {
    case "now_playing":
        $list_pretty = "Now Playing";
        break;
    case "popular":
        $list_pretty = "Popular";
        break;
    case "top_rated":
        $list_pretty = "Top Rated";
        break;
    case "upcoming":
        $list_pretty = "Upcoming";
        break;
}
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "http://api.themoviedb.org/3/movie/" . $list . "?language=en-US&page=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI1ZTBiN2QzMTJlNDhmMzI4YzRlMDIwMDMwODgzNjM1YiIsInN1YiI6IjY1ZGE3OGRkZTljMGRjMDE4NmMxMTBjOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkU_XT1ZU-NvCvuaMTC8VKD7dQSWWR3N4wpahoH-Ry0",
        "accept: application/json",
    ],
]);
$response = curl_exec($curl);
$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($status_code !== 200) {
    echo curl_error($curl);
    header("HTTP/1.0 404 Not Found");
    include "404.html";
    exit();
}
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    include "500.html";
    exit();
} else {
    $movies = json_decode($response, true);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $list_pretty ?> | Movies</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Movies</a>
        <button aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
                data-bs-target="#navbarNavDropdown" data-bs-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link<?php echo $list == 'now_playing' ? ' active' : ''; ?>" href="/"><i class="bi bi-tv-fill"></i> Now Playing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo $list == 'popular' ? ' active' : ''; ?>" href="/?list=popular"><i class="bi bi-graph-up"></i> Popular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo $list == 'top_rated' ? ' active' : ''; ?>" href="/?list=top_rated"><i class="bi bi-chat-left-heart-fill"></i> Top Rated</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo $list == 'upcoming' ? ' active' : ''; ?>" href="/?list=upcoming"><i class="bi bi-graph-up-arrow"></i> Upcoming</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" href="/search.html"><i class="bi bi-search"></i> Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1><?php echo $list_pretty ?></h1>
    <div id="results">
        <?php
            if (isset($movies['results']) && !empty($movies['results'])) {
                echo '<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5">';
                foreach ($movies['results'] as $movie) {
                    echo '<div class="col">';
                    echo '<a href="/movie.php?id=' . $movie["id"] . '">';
                    echo '<img class="img-fluid rounded" src="' . ($movie["poster_path"] ? 'https://image.tmdb.org/t/p/w342/' . $movie["poster_path"] : 'placeholder.jpg') . '" alt="' . $movie["title"] . ' poster" />';
                    echo '</a>';
                    echo '<h1 class="my-1 fs-5 fw-bold">' . $movie["title"] . '</h1>';
                    $release_date = new DateTimeImmutable(
                        $movie["release_date"]
                    );
                    echo '<p class="text-body-secondary">';
                    echo $release_date->format("F n, Y");
                    echo '</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </div>
</div>
<script crossorigin="anonymous"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
</body>
</html>
