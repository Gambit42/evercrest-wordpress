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
		add_editor_style( array( 'assets/css/editor-style.css', 'assets/css/evercrest.css' ) );
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

		// Swiper (vendored, https://swiperjs.com) drives the testimonial and journal carousels.
		wp_register_style( 'swiper', get_parent_theme_file_uri( 'assets/vendor/swiper/swiper.min.css' ), array(), '14.2.0' );
		wp_register_script( 'swiper', get_parent_theme_file_uri( 'assets/vendor/swiper/swiper-bundle.min.js' ), array(), '14.2.0', array( 'strategy' => 'defer' ) );

		wp_enqueue_style(
			'nad-evercrest',
			get_parent_theme_file_uri( 'assets/css/evercrest.css' ),
			array( 'nad-style', 'swiper' ),
			filemtime( get_parent_theme_file_path( 'assets/css/evercrest.css' ) )
		);

		wp_enqueue_script(
			'nad-evercrest',
			get_parent_theme_file_uri( 'assets/js/evercrest.js' ),
			array( 'swiper' ),
			filemtime( get_parent_theme_file_path( 'assets/js/evercrest.js' ) ),
			array( 'strategy' => 'defer' )
		);
		// Runs in <head> so reveal targets are hidden before first paint (no flash, no jump).
		wp_add_inline_script( 'nad-evercrest', 'document.documentElement.classList.add("ec-js");', 'before' );
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

/**
 * Meta description + Open Graph/Twitter tags. Copy comes from wp-admin:
 * the page/post excerpt, else Settings → General → Tagline. Image is the featured
 * image, else the first hero shot (attachment 10).
 */
add_action( 'init', fn() => add_post_type_support( 'page', 'excerpt' ) );
add_action(
	'wp_head',
	function () {
		$singular = is_singular();
		$desc     = ( $singular && has_excerpt() ) ? get_the_excerpt() : get_bloginfo( 'description' );
		$image    = ( $singular && has_post_thumbnail() ) ? get_the_post_thumbnail_url( null, 'large' ) : wp_get_attachment_image_url( 10, 'large' );
		$tags     = array(
			'description'    => $desc,
			'og:type'        => is_single() ? 'article' : 'website',
			'og:site_name'   => get_bloginfo( 'name' ),
			'og:title'       => wp_get_document_title(),
			'og:description' => $desc,
			'og:url'         => $singular ? get_permalink() : home_url( add_query_arg( null, null ) ),
			'og:image'       => $image,
			'twitter:card'   => 'summary_large_image',
		);
		foreach ( array_filter( $tags ) as $key => $value ) {
			printf( '<meta %s="%s" content="%s" />' . "\n", str_starts_with( $key, 'og:' ) ? 'property' : 'name', esc_attr( $key ), esc_attr( wp_strip_all_tags( $value ) ) );
		}
	},
	1
);

// Evercrest Core owns the content model; the theme owns its visual blocks.
if ( function_exists( 'evercrest_core_register_content_types' ) ) {
	require get_parent_theme_file_path( 'inc/collections.php' );
} else {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>'
				. esc_html__( 'The NAD theme requires the Evercrest Core plugin for residences, regions, and their blocks.', 'nad' )
				. '</p></div>';
		}
	);
}
