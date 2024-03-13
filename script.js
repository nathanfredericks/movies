$.ajaxSetup({
    headers:{
        "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI1ZTBiN2QzMTJlNDhmMzI4YzRlMDIwMDMwODgzNjM1YiIsInN1YiI6IjY1ZGE3OGRkZTljMGRjMDE4NmMxMTBjOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkU_XT1ZU-NvCvuaMTC8VKD7dQSWWR3N4wpahoH-Ry0"
    }
})

function search(){
    const movie = $("#movie").val();
    $.get("https://api.themoviedb.org/3/search/movie", {query: movie})
    .done(function(movies) {
        $("#results").empty();
        if(movies.length == 0){
            $("#results").append("No search results");
        }
        console.log(movies)
        movies.results.forEach(function(element){
            if(element.poster_path){
            $("#results").append(`<img src="https://image.tmdb.org/t/p/original/${element.poster_path}" width="200" alt="${element.title}"/><br>${element.title}<br>`);
            }
            else{
                $("#results").append(`no image <br> ${element.title} <br>`)
            }
        })
    })
}