<?php
 

 error_reporting(E_ALL);

 ini_set('display_errors', 1);

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "http://api.themoviedb.org/3/movie/" . htmlspecialchars($_GET["id"]) . "?language=en-US",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI1ZTBiN2QzMTJlNDhmMzI4YzRlMDIwMDMwODgzNjM1YiIsInN1YiI6IjY1ZGE3OGRkZTljMGRjMDE4NmMxMTBjOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkU_XT1ZU-NvCvuaMTC8VKD7dQSWWR3N4wpahoH-Ry0",
        "accept: application/json"
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
        $movie = json_decode($response, true);
    }

    $con = mysqli_connect("localhost", "reviews_user", "m0v13s", "reviews_db");

    // Check connection
    if (mysqli_connect_errno()) {
        include "500.html";
        exit();
    }

    $sql_statement = "SELECT name, review FROM review WHERE movie_id='" . htmlspecialchars($_GET["id"]) . "' ORDER BY added DESC LIMIT 10;";
    $result = mysqli_query($con, $sql_statement);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/movies/">Movies</a>
        <button aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
                data-bs-target="#navbarNavDropdown" data-bs-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a aria-current="page" class="nav-link active" href="/movies/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-tv-fill"></i> Now Playing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-graph-up"></i> Popular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-chat-left-heart-fill"></i> Top Rated</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-graph-up-arrow"></i> Upcoming</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <img class="img-fluid rounded mb-3" src="https://image.tmdb.org/t/p/original/<?php echo $movie["backdrop_path"] ?>"  >
    <h1><?php echo $movie["title"]; ?></h1>
    <h2><?php echo $movie["tagline"]; ?></h2>
    <?php
        $release_date = new DateTimeImmutable($movie["release_date"]);
        echo "<p><strong>Release Date: </strong>" . $release_date->format('F n, Y') . "</p>";
    ?>
    <h1>Most Recent Reviews</h1>
    <ul>
<?php
while($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $review = $row['review'];
    echo "<li>";
    echo "<p>Reviewer: " . $name . "</p>";
    echo "<p>" . $review . "</p>";
    echo "</li>";
}
// Free result set
mysqli_free_result($result);
mysqli_close($con);
?>
</ul>
    <form action="create_review.php" method="post">
        <input name="movie_id" id="movie_id" type="hidden" value="<?php echo $movie["id"] ?>">

        <label for="name">Name:</label>
        <input name="name" id="name" type="text">

        <label for="review">Review:</label>
        <textarea name="review" id="review"></textarea>

        <button type="submit">Add Review</button>
    </form>
</div>
<script crossorigin="anonymous"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>
<script src="script.js"></script>
</body>
</html>
