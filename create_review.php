<?php
    $con = mysqli_connect("localhost", "reviews_user", "m0v13s", "reviews_db");

    // Check connection
    if (mysqli_connect_errno()) {
        include "500.html";
        exit();
    }

    $movie_id = $_POST["movie_id"];
    $name = $_POST["name"];
    $review = $_POST["review"];

    $sql_statement = "INSERT INTO review (movie_id, name, review) VALUES ('$movie_id', '$name', '$review')";
    $result = mysqli_query($con, $sql_statement);
    mysqli_free_result($result);
    if (mysqli_affected_rows($con) == 1) {
        mysqli_close($con);
        header("Location: /movies/movie.php?id=" . $movie_id);
        exit();
    } else {
        mysqli_close($con);
        include "500.html";
        exit();
    }
?>