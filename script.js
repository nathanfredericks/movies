$.ajaxSetup({
    headers:{
        "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI1ZTBiN2QzMTJlNDhmMzI4YzRlMDIwMDMwODgzNjM1YiIsInN1YiI6IjY1ZGE3OGRkZTljMGRjMDE4NmMxMTBjOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkU_XT1ZU-NvCvuaMTC8VKD7dQSWWR3N4wpahoH-Ry0"
    }
})

function search(){
    const movie = $("#movie").val()
    let counter = 0;
    $.get("https://api.themoviedb.org/3/search/movie", {query: movie})
    .done(function(movies) {
        $("#results").empty()
        if (movies.total_results == 0) {
            $("#results").append("<p>No search results.</p>");
            return;
        }
        var finalresults = "";
        finalresults += `<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4">`
        movies.results.forEach(function(element){
            counter++;
            if(element.poster_path){
                finalresults += `
                    <div class="col text-center">
                        <a href="/movies/movie.php?id=${element.id}">
                            <img src="https://image.tmdb.org/t/p/w185/${element.poster_path}" alt="${element.title} poster"/>
                        </a>
                        <h1 class="fs-5 fw-medium">${element.title}</h1>
                    </div>`
            }
            else{
                finalresults += `<div class="col"> <a href="/movies/movie.php?id=${element.id}"> <img src="PlaceholderMovieImg.jpg" alt="${element.title}"> <br> ${element.title} <br> </a> </div>`
            }
        })
        finalresults += "</div>"
        $("#results").html(finalresults);

    })
}
