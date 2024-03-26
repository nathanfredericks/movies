<?php
    $con = mysqli_connect("localhost", "reviews_user", "m0v13s", "reviews_db");

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
        header("Location: /movies/movie.php?id=" . $movie_id);
        exit();
    } else {
        mysqli_close($con);
        include "500.html";
        exit();
    }
?>