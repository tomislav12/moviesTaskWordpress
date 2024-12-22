<?php
/*
 * Plugin Name: Movies
 * Description: Simple plugin for Movies
 * Version: 1.0
 * Author: Tomislav Mihaljević
 * Author URI: https://www.logisoft.hr/
 * License: GPLv2
 * Requires Plugins: movie-fav-quotes-block
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
 }

 define( 'MOVIES_ARCHIVE_SLUG', 'movies');
 define( 'MOVIES_PLUGIN_CONTEXT', 'plugin-movies' );
 define( 'MOVIES_BLOCKS_FAV_QUOTES', 'movie-fav-quotes-block');
 define( 'MOVIES_POSTTYPES_MOVIE', 'movie' );
 define( 'MOVIES_TAXONOMY_GENRE', 'genre' );




/**
 * CPT movie
 * Loads plugin template for single post
 */
add_filter('single_template', 'movies_single_template', 99);
function movies_single_template( $single ) {
    global $post;
    /* Checks for single template by post type */
    if ( $post->post_type == MOVIES_POSTTYPES_MOVIE ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-movie.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-movie.php';
        }
    }
    return $single;
}

/**
 * CPT movie
 * Loads plugin template for archive post
 */
function movies_archive_template( $archive_template ) {
    if ( is_post_type_archive ( MOVIES_POSTTYPES_MOVIE ) ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/archive-movie.php' ) ) {
            $archive_template = plugin_dir_path( __FILE__ ) . 'templates/archive-movie.php';
        }
     }
     return $archive_template;
}
add_filter( 'archive_template', 'movies_archive_template' ) ;




 /**
 * Register the "movie" custom post type
 */
function movies_setup_post_type_movie() {
    register_post_type(MOVIES_POSTTYPES_MOVIE, [
		'label' => __('Movies', MOVIES_PLUGIN_CONTEXT),
        'has_archive' => true,
		'public' => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => MOVIES_ARCHIVE_SLUG,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-editor-video',
		'supports' => ['title', 'editor', 'thumbnail', 'author', 'revisions', 'comments'],
		'show_in_rest' => true,
		'rewrite' => ['slug' => MOVIES_ARCHIVE_SLUG],
		'labels' => [
			'singular_name' => __('Movie', MOVIES_PLUGIN_CONTEXT),
			'add_new_item' => __('Add new movie', MOVIES_PLUGIN_CONTEXT),
			'new_item' => __('New movie', MOVIES_PLUGIN_CONTEXT),
			'view_item' => __('View movie', MOVIES_PLUGIN_CONTEXT),
			'not_found' => __('No movies found', MOVIES_PLUGIN_CONTEXT),
			'not_found_in_trash' => __('No movies found in trash', MOVIES_PLUGIN_CONTEXT),
			'all_items' => __('All moview', MOVIES_PLUGIN_CONTEXT),
			'insert_into_item' => __('Insert into movie', MOVIES_PLUGIN_CONTEXT)
		],		
        'taxonomies' => ['genre'],
	]);
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules(); 
} 
add_action( 'init', 'movies_setup_post_type_movie', 5 );

/**
 * Register the "genre" taxonomy
 * and attach it to the "movie" post type
 */
function movies_setup_taxonomy_genre() {
    	register_taxonomy(MOVIES_TAXONOMY_GENRE, [MOVIES_POSTTYPES_MOVIE], [
    		'label' => __('Genres', MOVIES_PLUGIN_CONTEXT),
    		'hierarchical' => false,
    		'rewrite' => ['slug' => 'genre'],
    		'show_admin_column' => true,
    		'show_in_rest' => true,
    		'labels' => [
    			'singular_name' => __('Genre', MOVIES_PLUGIN_CONTEXT),
    			'all_items' => __('All Genres', MOVIES_PLUGIN_CONTEXT),
    			'edit_item' => __('Edit Genre', MOVIES_PLUGIN_CONTEXT),
    			'view_item' => __('View Genre', MOVIES_PLUGIN_CONTEXT),
    			'update_item' => __('Update Genre', MOVIES_PLUGIN_CONTEXT),
    			'add_new_item' => __('Add New Genre', MOVIES_PLUGIN_CONTEXT),
    			'new_item_name' => __('New Genre Name', MOVIES_PLUGIN_CONTEXT),
    			'search_items' => __('Search Genres', MOVIES_PLUGIN_CONTEXT),
    			'popular_items' => __('Popular Genres', MOVIES_PLUGIN_CONTEXT),
    			'separate_items_with_commas' => __('Separate genres with comma', MOVIES_PLUGIN_CONTEXT),
    			'choose_from_most_used' => __('Choose from most used genres', MOVIES_PLUGIN_CONTEXT),
    			'not_found' => __('No Genres found', MOVIES_PLUGIN_CONTEXT),
    		]
    	]);        
}
add_action( 'init', 'movies_setup_taxonomy_genre', 15 );

/**
 * Load css and js scripts
 */
add_action('wp_enqueue_scripts', 'movies_load_scripts');
function movies_load_scripts() {
	// CSS
	wp_register_style( 'movies-swiperjs-style', plugins_url('assets/css/swiper-bundle.min.css', __FILE__ ) );
    wp_register_style( 'movies-style', plugins_url('assets/css/movies-style.css', __FILE__ ) );
	wp_enqueue_style( 'movies-swiperjs-style' );
    wp_enqueue_style( 'movies-style' );

	// JS
    wp_enqueue_script( 'movies-swiperjs-script', plugins_url('assets/js/swiper-bundle.min.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'movies-jsviews-script', plugins_url('assets/js/jsviews.min.js', __FILE__ ) , array( 'jquery' ) );
	wp_enqueue_script( 'movies-script', plugins_url('assets/js/movies.js', __FILE__ ) , array( 'jquery' ) );
}


/**
 * Activate the plugin.
 */
function movies_activate() {
	// movies_test_insert_few_movies();
}
register_activation_hook( __FILE__, 'movies_activate' );

/**
 * Deactivation hook.
 */
function movies_deactivate() {
    unregister_taxonomy( MOVIES_TAXONOMY_GENRE );
	unregister_post_type( MOVIES_POSTTYPES_MOVIE );

	// Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'movies_deactivate' );

/**
 * Uninstall hook
 */
function movies_uninstall_plugin() {
	global $wpdb;

	// Delete the movie posts (if needed)
	/*
	$wpdb->query("
	DELETE a,b,c
    FROM wp_posts a
    LEFT JOIN wp_term_relationships b
        ON (a.ID = b.object_id)
    LEFT JOIN wp_postmeta c
        ON (a.ID = c.post_id)
    WHERE a.post_type = '" . MOVIES_POSTTYPES_MOVIE . "'
	");
	*/

	unregister_taxonomy( MOVIES_TAXONOMY_GENRE );
	unregister_post_type( MOVIES_POSTTYPES_MOVIE );
}
register_uninstall_hook(
	__FILE__,
	'movies_uninstall_plugin'
);

function movies_add_rest_excerpt($response) {
	$response->data['excerpt'] = movies_truncate(get_the_excerpt( get_the_ID() ), 150);
	return $response;
}
add_filter( "rest_prepare_movie", "movies_add_rest_excerpt" );

/**
 * Trims the input string
 * @returns Trimmed string
 */
function movies_truncate($input_string, $length) {
    return (strlen($input_string) > $length) ? substr($input_string, 0, $length) . '...' : $input_string;
}
add_action( 'init', 'movies_setup_taxonomy_genre' );

/**
 * Shortcode task
 */
function movies_shortcode_run( $atts = array(), $content = null, $tag = '' ) {
	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// override default attributes with user attributes
	$my_atts = shortcode_atts(
		array(
			// Defaults
			'genre' => 'fantasy',
			'items' => 5
		), $atts, $tag
	);	

	// get all movies cpt by genre and items
	$movies_posts = new WP_Query( array(
		'post_type'         => MOVIES_POSTTYPES_MOVIE,
		'posts_per_page'    => $my_atts['items'],
		'tax_query' => array(
			array(
				'taxonomy' => MOVIES_TAXONOMY_GENRE,
				'field' => 'slug',
				'terms' => array( $my_atts['genre'] ) // Array of service categories you wish to retrieve posts from
			)
		)
	));

	$o = '';
	if ( $movies_posts->have_posts() ) {
		$o = '<ul>';
		while ( $movies_posts->have_posts() ) {			
			$movies_posts->the_post();
			$o .= "<li>".get_the_title()."</li>";
		}
		$o .= '</ul>';
	}
	
	// Reset the `$post` data to the current post in main query.	
	wp_reset_postdata();
    return $o;
}
// Register the shortcode
add_shortcode('movies', 'movies_shortcode_run');


function movies_get_genres() {
	return get_terms(['taxonomy' => MOVIES_TAXONOMY_GENRE]);
}