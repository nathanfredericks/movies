<?php
if (!isset($_GET["id"])) {
    header("HTTP/1.0 404 Not Found");
    include "404.html";
    exit();
}
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL =>
        "http://api.themoviedb.org/3/movie/" .
        htmlspecialchars($_GET["id"]) .
        "?language=en-US",
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
    $movie = json_decode($response, true);
}
$host = $_ENV["MYSQL_HOST"] ?? "localhost";
$user = $_ENV["MYSQL_USER"] ?? "reviews_user";
$password = $_ENV["MYSQL_PASSWORD"] ?? "m0v13s";
$db = $_ENV["MYSQL_DATABASE"] ?? "reviews_db";
$con = mysqli_connect($host, $user, $password, $db);
// Check connection
if (mysqli_connect_errno()) {
    include "500.html";
    exit();
}

$sql_statement =
    "SELECT id, username, title, review, added FROM review WHERE movie_id='" .
    htmlspecialchars($_GET["id"]) .
    "' ORDER BY added DESC;";
$result = mysqli_query($con, $sql_statement);
if (!$result) {
    include "500.html";
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $movie["title"]; ?> | Movies</title>
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
                    <a class="nav-link" href="/"><i class="bi bi-tv-fill"></i> Now Playing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?list=popular"><i class="bi bi-graph-up"></i> Popular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?list=top_rated"><i class="bi bi-chat-left-heart-fill"></i> Top Rated</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?list=upcoming"><i class="bi bi-graph-up-arrow"></i> Upcoming</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/search.html"><i class="bi bi-search"></i> Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-3">
    <div class="row">
        <div class="col-3 text-center">
            <?php
            if ($movie["poster_path"]) {
                echo "<img class=\"img-fluid rounded\" src=\"https://image.tmdb.org/t/p/w342/" . $movie["poster_path"] . "\">";
            } else {
                echo "<img class=\"img-fluid rounded\" src=\"placeholder.jpg\">";
            }
            ?>
        </div>
        <div class="col-9">
            <h1>
                <?php echo $movie["title"]; ?>
                <?php 
                    if ($movie["imdb_id"]) {
                        echo "<a class=\"btn btn-primary\" href=\"https://www.imdb.com/title/" . $movie["imdb_id"] . "\" target=\"_blank\">";
                        echo "View on IMDB";
                        echo "</a>";
                    }
                ?>
            </h1>
            <h2><?php echo $movie["tagline"]; ?></h2>
            <p><?php echo $movie["overview"]; ?></p>
            <table class="table">
                <tr>
                    <th>Release Date</th>
                    <td>
                    <?php
                    $release_date = new DateTimeImmutable(
                        $movie["release_date"]
                    );
                    echo $release_date->format("F n, Y");
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>Runtime</th>
                    <td><?php echo date(
                        "G:i",
                        mktime(0, $movie["runtime"])
                    ); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <h1 class="mt-3">
        Reviews
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-review">
            Add Review
        </button>
    </h1>

    <div class="modal fade" id="add-review" tabindex="-1" aria-labelledby="add-review" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="add-review-title">Add Review</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="create_review.php" method="post">
                <div class="modal-body">
                    <input name="movie_id" id="movie_id" type="hidden" value="<?php echo $movie[
                        "id"
                    ]; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea class="form-control" id="review" name="review" rows="3" required maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add review</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <?php
    $rowcount = mysqli_num_rows($result);
    if ($rowcount < 1) {
        echo "<p>Be the first to write a review!</p>";
    } else {
        echo "<div class=\"row row-cols-3\">";
        while ($row = mysqli_fetch_array($result)) {
            $review_id = $row["id"];
            $username = $row["username"];
            $title = $row["title"];
            $review = $row["review"];
            $added = $row["added"];
            echo "<div class=\"col mb-3 \">";
            echo "<div class=\"card\">";
            echo "<div class=\"card-body\">";
            echo "<h5 class=\"card-title\">" . $title . "</h5>";
            echo "<h6 class=\"card-subtitle mb-2 text-body-secondary\">" . $username . "</h6>";
            echo "<p class=\"card-text\">" . $review ."</p>";
            echo "<form action=\"delete_review.php\" method=\"post\">";
            echo "<input name=\"movie_id\" id=\"movie_id\" type=\"hidden\" value=\"" . htmlspecialchars($_GET["id"]) . "\">";
            echo "<input name=\"review_id\" id=\"review_id\" type=\"hidden\" value=\"" . $review_id . "\">";
            echo "<button type=\"submit\" class=\"btn btn-danger\">Delete review</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    }
    // Free result set
    mysqli_free_result($result);
    mysqli_close($con);
    ?>
</div>
<script crossorigin="anonymous"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>
</body>
</html>
