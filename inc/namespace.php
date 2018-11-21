<?php
/**
 * Main plugin functionality.
 *
 * @package NewsRecommendations
 */

namespace NewsRecommendations;

use ArrayIterator;
use CachingIterator;
use WP_Block_Type_Registry;
use WP_Post;
use WP_Term;

const POST_TYPE_NAME = 'recommendation';

/**
 * Initializes the plugin.
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\load_textdomain' );
	add_action( 'init', __NAMESPACE__ . '\register_post_type' );
	add_action( 'init', __NAMESPACE__ . '\register_post_meta' );
	add_action( 'init', __NAMESPACE__ . '\register_block_types' );
	add_action( 'init', __NAMESPACE__ . '\register_editor_assets' );

	add_action( 'widgets_init', __NAMESPACE__ . '\register_widget' );

	add_filter( 'allowed_block_types', __NAMESPACE__ . '\filter_allowed_block_types', 10, 2 );

	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\limit_editor_assets_per_post_type', 8 );

	add_filter( 'post_type_link', __NAMESPACE__ . '\filter_post_type_link', 10, 2 );
}

/**
 * Loads translations.
 */
function load_textdomain(): void {
	load_plugin_textdomain(
		'news-recommendations',
		false,
		\dirname( plugin_basename( __DIR__ ) ) . '/languages'
	);
}

/**
 * Registers the recommendation custom post type.
 */
function register_post_type() {
	$labels = [
		'name'               => _x( 'Recommendations', 'Post Type General Name', 'news-recommendations' ),
		'singular_name'      => _x( 'Recommendation', 'Post Type Singular Name', 'news-recommendations' ),
		'menu_name'          => __( 'Recommendations', 'news-recommendations' ),
		'all_items'          => __( 'All Recommendations', 'news-recommendations' ),
		'view_item'          => __( 'View Recommendation', 'news-recommendations' ),
		'add_new_item'       => __( 'Add New Recommendation', 'news-recommendations' ),
		'add_new'            => __( 'New Recommendation', 'news-recommendations' ),
		'edit_item'          => __( 'Edit Recommendation', 'news-recommendations' ),
		'update_item'        => __( 'Update Recommendation', 'news-recommendations' ),
		'search_items'       => __( 'Search recommendations', 'news-recommendations' ),
		'not_found'          => __( 'No recommendations found', 'news-recommendations' ),
		'not_found_in_trash' => __( 'No recommendations found in Trash', 'news-recommendations' ),
	];

	$args = [
		'label'             => __( 'Recommendation', 'news-recommendations' ),
		'description'       => __( 'Daily Recommendations', 'news-recommendations' ),
		'labels'            => $labels,
		'supports'          => [ 'title', 'editor', 'custom-fields' ],
		'menu_icon'         => 'dashicons-paperclip',
		'hierarchical'      => false,
		'public'            => false,
		'show_in_rest'      => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_admin_bar' => false,
		'rewrite'           => false,
		'template'          => [
			[ 'news-recommendations/recommendation' ],
		],
		'template_lock'     => 'all',
	];

	\register_post_type( POST_TYPE_NAME, $args );
}

/**
 * Registers the custom post meta fields needed by the post type.
 */
function register_post_meta() {
	\register_post_meta(
		POST_TYPE_NAME,
		'_recommendation_source',
		[
			'show_in_rest'      => true,
			'type'              => 'string',
			'description'       => __( 'Source', 'news-recommendations' ),
			'sanitize_callback' => 'sanitize_text_field',
			'single'            => true,
		]
	);

	\register_post_meta(
		POST_TYPE_NAME,
		'_recommendation_url',
		[
			'show_in_rest'      => true,
			'type'              => 'string',
			'description'       => __( 'URL', 'news-recommendations' ),
			'sanitize_callback' => 'sanitize_text_field',
			'single'            => true,
		]
	);
}

/**
 * Registers the custom block types for server side rendering.
 */
function register_block_types(): void {
	if ( ! \function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type(
		'news-recommendations/recommendation',
		[
			'editor_script' => 'news-recommendations',
			'editor_style'  => 'news-recommendations',
		]
	);
}

/**
 * Registers JavaScript and CSS for the block editor.
 */
function register_editor_assets(): void {
	if ( ! \function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_register_script(
		'news-recommendations',
		plugins_url( 'assets/js/editor.js', __DIR__ ),
		[
			'wp-blocks',
			'wp-components',
			'wp-data',
			'wp-edit-post',
			'wp-editor',
			'wp-element',
			'wp-i18n',
			'wp-plugins',
		],
		'20181021',
		true
	);

	wp_register_style(
		'news-recommendations',
		plugins_url( 'assets/css/editor.css', __DIR__ ),
		[],
		'20181021'
	);

	if ( \function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'news-recommendations', 'news-recommendations', \dirname( __DIR__ ) . '/languages' );
	}
}

/**
 * Workaround to only enqueue the JavaScript and CSS when needed.
 *
 * Limits the assets for the recommendation block to the recommendation post type.
 *
 * This we the registered block type is still available for whatever plugin uses that information,
 * yet the block cannot be added for any other post type in the editor.
 *
 * @link https://github.com/WordPress/gutenberg/issues/9855
 *
 * @see gutenberg_enqueue_registered_block_scripts_and_styles()
 */
function limit_editor_assets_per_post_type() {
	global $post;

	if ( POST_TYPE_NAME !== get_post_type( $post ) ) {
		if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'news-recommendations/recommendation' ) ) {
			return;
		}

		$block_type = unregister_block_type( 'news-recommendations/recommendation' );

		if ( ! $block_type ) {
			return;
		}

		// Re-register right after assets have been enqueued.
		add_action(
			'enqueue_block_editor_assets',
			function () use ( $block_type ) {
				register_block_type( $block_type );
			},
			11
		);
	}
}

/**
 * Registers a custom widget to display ads.
 */
function register_widget(): void {
	\register_widget( new Widget() );
}

/**
 * Filters the allowed block types for recommendations.
 *
 * @param array|true $allowed_block_types List of allowed block types.
 * @param \WP_Post   $post                The current post object.
 * @return array|true Modified list of allowed block types, or true to indicate all block types are allowed.
 */
function filter_allowed_block_types( $allowed_block_types, WP_Post $post ) {
	if ( POST_TYPE_NAME === $post->post_type ) {
		return [ 'news-recommendations/recommendation' ];
	}

	return $allowed_block_types;
}

/**
 * Filter post type permalink to point to recommended URL.
 *
 * @param string  $url  The post URL
 * @param WP_Post $post The post object
 *
 * @return string The filtered permalink.
 */
function filter_post_type_link( $url, WP_Post $post ) {
	if ( POST_TYPE_NAME === $post->post_type ) {
		return get_post_meta( $post->ID, '_recommendation_url', true );
	}

	return $url;
}
