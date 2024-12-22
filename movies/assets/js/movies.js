(function($) {    
    $(document).ready(function() {

        // Movies ajax
        function loadMoviesAjax(term_id = 0, initial = false) {
            
            let queryParamGenre = '';
            term_id == 0 ? queryParamGenre = '' : queryParamGenre = "genre=" + term_id;
            $.observable(app).setProperty("loadingData", true);
            $.ajax({
                type : "GET",
                dataType : "JSON",
                url : "/wp-json/wp/v2/movies?_embed&" + queryParamGenre,
                error: function(response, error) {
                    alert("Error occured while loading genres. Try again later.");
                },
                success : function(response) {                    
                    $.observable(movies).refresh(response);      
                    $.observable(app).setProperty("loadingData", false);
                    // Change url
                    if(!initial) updateURL('genre-id', term_id);              
                }
            });
        }

        // Genre picker (ajax rest call)
        $('.movies-genre-picker').change(function(e){
            let termId = parseInt($(this).val(), 10);            
            loadMoviesAjax(termId);
        });
        
        let movies = [];
        var app = {
            movies: movies,
            loadingData: false,
        };
        var helpers = {
            movies: {
                getFeaturedImage: function(movie) {
                    console.log(movie);
                    if(typeof movie["_embedded"] !== "undefined") {
                        if(typeof movie["_embedded"]["wp:featuredmedia"] !== "undefined") {
                            return movie["_embedded"]["wp:featuredmedia"][0].source_url;
                        }
                    }
                    return 'https://via.assets.so/img.jpg?w=183&h=275';
                },                    
            }
        };

        var myTemplate = $.templates("#movie-card-template");                
        myTemplate.link(".movies-card-wrapper", app, helpers);  
        
        const urlParams = new URLSearchParams(window.location.search);
        let genreId = urlParams.get('genre-id');
        if(typeof genreId === "undefined" || genreId == null) genreId = 0;
        loadMoviesAjax(genreId, true);

        // Swiperjs
        var swiper = new Swiper('.swiper-container', {
            direction: 'horizontal',
            loop: true,
            slidesPerView: 1,
            spaceBetween: 10,
            breakpoints: {               
                768: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                },
            },
        });

        //update URL Parameter
        function updateURL(key,val){
            var url = window.location.href;
            var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");

            if(reExp.test(url)) {
                // update
                var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
                var delimiter = reExp.exec(url)[0].charAt(0);
                url = url.replace(reExp, delimiter + key + "=" + val);
            } else {
                // add
                var newParam = key + "=" + val;
                if(!url.indexOf('?')){url += '?';}

                if(url.indexOf('#') > -1){
                    var urlparts = url.split('#');
                    url = urlparts[0] +  "?" + newParam +  (urlparts[1] ?  "#" +urlparts[1] : '');
                } else {
                    url += "?" + newParam;
                }
            }
            window.history.pushState(null, document.title, url);
        }

    });
})(jQuery);