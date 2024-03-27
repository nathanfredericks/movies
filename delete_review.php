<?php
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
    $movie_id = $_POST["movie_id"];
    $review_id = $_POST["review_id"];
    $sql_statement = "DELETE FROM review WHERE id='$review_id'";
    if (mysqli_query($con, $sql_statement)) {
        mysqli_close($con);
        header("Location: /movie.php?id=" . $movie_id);
        exit();
    } else {
        mysqli_close($con);
        include "500.html";
        exit();
    }
?>