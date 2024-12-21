<?php
    include_once( plugin_dir_path( __FILE__ ) . 'parts/header.php' );
?>

<div class="movies-wrapper">

    <?php
        $selectedOption = 0;
        if(isset($_GET['genre-id'])) {
            $selectedOption = $_GET['genre-id'];            
        }
        
        $terms = getMoviesGenres();
        $o = '';
        foreach($terms as $tag) {
            $selectedAttr = $tag->term_id == $selectedOption ? 'selected' : '';
            $o .= '<option ' . $selectedAttr . ' value="' . $tag->term_id . '">' . $tag->name . '</option>';
        }        
    ?>
    <select class="movies-genre-picker">
        <option value="0" >All genres</option>
        <?= $o ?>
    </select>

    <script id="movie-card-template" type="text/x-jsrender">
        {^{for movies itemVar="~movie"}}
            <a class="movies-card" data-id="{{:id}}" href="{{:link}}">
                <div class="featured-image">
                    <img src="{{:~movies.getFeaturedImage(~movie)}}" alt="{{:title.rendered}}" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" width="257" height="388" sizes="auto, (max-width: 257px) 100vw, 257px" />
                </div>
                <div class="movies-container">
                    <h4><b>{{:title.rendered}}</b></h4>
                    <p>{{:excerpt}}</p>
                </div>
            </a>
        {{/for}}
    </script>

    <div class="movies-card-wrapper"></div>

</div>

<?php
    include_once( plugin_dir_path( __FILE__ ) . 'parts/footer.php' );
?>