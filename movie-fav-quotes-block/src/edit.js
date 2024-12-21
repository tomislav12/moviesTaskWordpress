/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
//import './editor.scss';

import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { quote, movie, author } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Quote information', 'movie-fav-quotes-block' ) }>
					<TextControl
                        __nextHasNoMarginBottom
                        __next40pxDefaultSize
                        label={ __(
                            'Quote',
                            'movie-fav-quotes-block'
                        ) }
                        value={ quote || '' }
                        onChange={ ( value ) =>
                            setAttributes( { quote: value } )
                        }
                    />
					<TextControl
                        __nextHasNoMarginBottom
                        __next40pxDefaultSize
                        label={ __(
                            'Movie',
                            'movie-fav-quotes-block'
                        ) }
                        value={ movie || '' }
                        onChange={ ( value ) =>
                            setAttributes( { movie: value } )
                        }
                    />
					<TextControl
                        __nextHasNoMarginBottom
                        __next40pxDefaultSize
                        label={ __(
                            'Author',
                            'movie-fav-quotes-block'
                        ) }
                        value={ author || '' }
                        onChange={ ( value ) =>
                            setAttributes( { author: value } )
                        }
                    />
					
				</PanelBody>
            </InspectorControls>
			<p { ...useBlockProps() }>
				<h3>Favourite quote</h3>
				<p>{ quote }</p>
				<p>Movie: { movie }</p>
				<p>Author: { author }</p>
			</p>
		</>
	);
}
