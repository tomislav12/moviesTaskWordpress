<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php
		$html = '';
		if ( ! empty( $attributes['quote']) ) {
			$html .= '<h3>Favourite quote:</h3>';
			$html .= '<div>' . esc_html($attributes['quote']) . '</div>';
		}
		if ( ! empty( $attributes['movie']) ) {
			$html .= '<div>Movie: ' . esc_html($attributes['movie']) . '</div>';
		}
		if ( ! empty( $attributes['author']) ) {
			$html .= '<div>Author: ' . esc_html($attributes['author']) . '</div>';
		}
		echo $html;
	?>
</p>
