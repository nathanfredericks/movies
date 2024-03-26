$.ajaxSetup({
    headers:{
        "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI1ZTBiN2QzMTJlNDhmMzI4YzRlMDIwMDMwODgzNjM1YiIsInN1YiI6IjY1ZGE3OGRkZTljMGRjMDE4NmMxMTBjOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkU_XT1ZU-NvCvuaMTC8VKD7dQSWWR3N4wpahoH-Ry0"
    }
})

function search() {
    const movie = $("#movie").val()
    $.get("https://api.themoviedb.org/3/search/movie", {query: movie})
    .done(function(movies) {
        $("#results").empty()
        if (movies.total_results == 0) {
            $("#results").append("<p>No search results.</p>");
            return;
        }
        var finalResults = "";
        finalResults += `<div class="row row-cols-2 row-cols-md-4">`
        movies.results.forEach(function(element){
            finalResults += `
                <div class="col text-center">
                    <a href="/movies/movie.php?id=${element.id}">
                        <img class=\"img-fluid rounded poster\" src="${element.poster_path ? 'https://image.tmdb.org/t/p/w185/' + element.poster_path : 'PlaceholderMovieImg.jpg'}" alt="${element.title} poster"/>
                    </a>
                    <h1 class="fs-5 fw-medium">${element.title}</h1>
                </div>`
        })
        finalResults += "</div>"
        $("#results").html(finalResults);
    })
}

$('#movie').keydown(function(e){
    if (e.keyCode == 13) {
        search();
    }
});