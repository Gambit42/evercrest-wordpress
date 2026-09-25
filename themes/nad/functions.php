<?php
/**
 * nad functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

if ( ! function_exists( 'nad_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'nad_post_format_setup' );

if ( ! function_exists( 'nad_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'nad_editor_style' );

if ( ! function_exists( 'nad_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'nad-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'nad-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'nad_enqueue_styles' );

if ( ! function_exists( 'nad_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'nad' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'nad_block_styles' );

if ( ! function_exists( 'nad_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_pattern_categories() {

		register_block_pattern_category(
			'nad_page',
			array(
				'label'       => __( 'Pages', 'nad' ),
				'description' => __( 'A collection of full page layouts.', 'nad' ),
			)
		);

		register_block_pattern_category(
			'nad_post-format',
			array(
				'label'       => __( 'Post formats', 'nad' ),
				'description' => __( 'A collection of post format patterns.', 'nad' ),
			)
		);
	}
endif;
add_action( 'init', 'nad_pattern_categories' );

if ( ! function_exists( 'nad_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since nad 1.0
	 *
	 * @return void
	 */
	function nad_register_block_bindings() {
		register_block_bindings_source(
			'nad/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'nad' ),
				'get_value_callback' => 'nad_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'nad_register_block_bindings' );

if ( ! function_exists( 'nad_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since nad 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function nad_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;
