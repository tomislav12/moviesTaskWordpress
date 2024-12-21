<?php
    include_once( plugin_dir_path( __FILE__ ) . 'parts/header.php' );
?>

<div class="movies-wrapper">

<a class="movies-navigation" href="/movies/"> All movies </a>

<h1> <?= get_the_title() ?> </h1>
<div class="featured-image">
<?php 
                if(has_post_thumbnail()) {
                    echo get_the_post_thumbnail( get_the_ID(), 'post-thumbnail', [
                        'alt' => get_the_title(),
                    ]);
                }
                else {
                    echo '<img src="https://via.assets.so/img.jpg?w=300&h=300"/>';
                }
            ?>
</div>
<?php the_content(); ?>

<?php
$terms = get_the_terms( $post->ID, MOVIES_TAXONOMY_GENRE );

if($terms) {
    $terms_list = ' ';
    foreach($terms as $term) {        
        echo $terms_list . "<a href=\"" . esc_url( get_term_link( $term )) . "\">" . $term->name . "</a>";
        $terms_list = ", ";
    }
} else echo "No genres.";

?>

<section class="swiper-slider-1">
    <!-- Swiper Container -->
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide"><img src="https://picsum.photos/seed/sadass/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/fdssdd/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/fhhfaa/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/sadash/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/sadddd/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/sadeee/400"/></div>
            <div class="swiper-slide"><img src="https://picsum.photos/seed/sadfff/400"/></div>
            <!-- ... -->
        </div>
        <!-- If you want pagination (bullets) -->
        <!-- <div class="swiper-pagination"></div> -->
        <!-- If you want navigation buttons
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
         -->
    </div>
</section>

</div>

<?php
    include_once( plugin_dir_path( __FILE__ ) . 'parts/footer.php' );
?>