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
        if(movies.length == 0){
            $("#results").append("No search results")
        }
        console.log(movies)
        var finalresults = "";
        finalresults += `<div class="row">`
        movies.results.forEach(function(element){
            counter++;
            if(element.poster_path){
                finalresults += `<div class="col"> <a href="/movies/movie.php?id=${element.id}"> <img src="https://image.tmdb.org/t/p/original/${element.poster_path}" height="300" width="200" alt="${element.title}"/><br>${element.title}<br> </a> </div>`
            }
            else{
                finalresults += `<div class="col"> <a href="/movies/movie.php?id=${element.id}"> <img src="PlaceholderMovieImg.jpg" alt="${element.title}"> <br> ${element.title} <br> </a> </div>`
            }
            if(counter % 4 == 0 && counter != 0){
                finalresults += `</div> <div class="row">`
            }
        })
        finalresults += "</div>"
        $("#results").html(finalresults);

    })
}
