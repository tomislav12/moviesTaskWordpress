<?php
/**
 * Plugin Name:       Movie Fav Quotes Block
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            Tomislav Mihaljević
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       movie-fav-quotes-block
 * Requires Plugins:  movies
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 *
 * Enable Favourite Movie Block 
 * to be loaded only on movies CPT
 */
function create_block_movie_fav_quotes_block_block_init() {
	// Check if this is the intended custom post type
	if (is_admin()) {
		global $pagenow;
		$typenow = '';
		if ( 'post-new.php' === $pagenow ) {
		  if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
			$typenow = $_REQUEST['post_type'];
		  };
		} elseif ( 'post.php' === $pagenow ) {
		  if ( isset( $_GET['post'] ) && isset( $_POST['post_ID'] ) && (int) $_GET['post'] !== (int) $_POST['post_ID'] ) {
			// Do nothing
		  } elseif ( isset( $_GET['post'] ) ) {
			$post_id = (int) $_GET['post'];
		  } elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = (int) $_POST['post_ID'];
		  }
		  if ( $post_id ) {
			$post = get_post( $post_id );
			$typenow = $post->post_type;
		  }
		}
		if ($typenow != 'movie') {
		  return;
		}
	  }
	  
	  // Register the block
	  $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
	  wp_register_script(
		MOVIES_BLOCKS_FAV_QUOTES,
		plugins_url( 'build/block.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	  );
	  register_block_type(  __DIR__ . '/build', array(
		'editor_script' => MOVIES_BLOCKS_FAV_QUOTES,
	  ) );
}
add_action( 'init', 'create_block_movie_fav_quotes_block_block_init' );