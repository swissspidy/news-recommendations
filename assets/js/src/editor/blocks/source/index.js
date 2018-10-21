/**
 * WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';
import {
	registerBlockType,
} from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.css';

registerBlockType( 'news-recommendations/recommendation', {
	title: _x( 'Recommendation ', 'block name', 'news-recommendations' ),

	description: __( 'Enter the information for this recommendation.', 'news-recommendations' ),

	icon: 'paperclip',

	category: 'common',

	keywords: [
		__( 'recommendation', 'news-recommendations' ),
		__( 'source', 'news-recommendations' ),
	],

	supports: {
		customClassName: false,
		html: false,
		multiple: false,
	},

	attributes: {
		source: {
			type: 'string',
			source: 'meta',
			meta: '_recommendation_source'
		},
		url: {
			type: 'string',
			source: 'meta',
			meta: '_recommendation_url'
		},
	},

	edit( { className, attributes: { source, url }, setAttributes } ) {
		return (
			<div className={className}>
				<TextControl
					label={__( 'Source', 'news-recommendations' )}
					help={__( 'Type the name of the originating publication, e.g. New York Times', 'news-recommendations' )}
					value={source}
					onChange={( newValue ) => {
						setAttributes( { source: newValue } );
					}}
				/>
				<TextControl
					label={__( 'URL', 'news-recommendations' )}
					help={__( 'Enter the URL to the news story', 'news-recommendations' )}
					value={url}
					type="url"
					onChange={( newValue ) => {
						setAttributes( { source: newValue } );
					}}
				/>
			</div>
		);
	},

	save() {
		// No public facing block.
		return null;
	},
} );
