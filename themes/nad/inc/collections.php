<?php
/**
 * Evercrest collections: Residences (with types) and Regions, plus the PHP-only blocks
 * that list them on the front page. Blocks use WP 7.0+ `autoRegister`: the editor shows a
 * server-rendered preview and builds the Settings panel from `autoGenerateControl` attributes.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

/** Meta box fields per post type. Keys are stored as `_ec_{key}`; checkboxes hold '1'. */
function nad_fields( $post_type ) {
	$fields = array(
		'region'    => array( 'homes' => 'Homes built (number, e.g. 24)' ),
		'residence' => array(
			'location'  => 'Location (e.g. Carmel, California)',
			'bedrooms'  => 'Bedrooms',
			'bathrooms' => 'Bathrooms',
			'interior'  => 'Interior (e.g. 7,200 ft²)',
			'grounds'   => 'Grounds (e.g. 4.2 ac)',
			'price'     => 'Price (e.g. $8,450,000)',
			'status'    => 'Status',
			'completed' => 'Completed (year)',
			'featured'  => 'Featured — show in “Select Residences” on the front page',
			'spotlight' => 'Spotlight — the “Residence in focus” section (tick one)',
		),
	);
	return $fields[ $post_type ] ?? array();
}

function nad_meta( $post_id, $key ) {
	return get_post_meta( $post_id, "_ec_$key", true );
}

add_action(
	'init',
	function () {
		register_post_type(
			'residence',
			array(
				'label'        => 'Residences',
				'labels'       => array( 'singular_name' => 'Residence', 'add_new_item' => 'Add residence' ),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => 'residences',
				'rewrite'      => array( 'slug' => 'residences' ),
				'menu_icon'    => 'dashicons-building',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			)
		);
		register_taxonomy(
			'residence_type',
			'residence',
			array(
				'label'             => 'Types',
				'labels'            => array( 'singular_name' => 'Type' ),
				'hierarchical'      => true, // Checkbox UI in the editor.
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'residence-type' ),
			)
		);
		register_post_type(
			'region',
			array(
				'label'        => 'Regions',
				'labels'       => array( 'singular_name' => 'Region', 'add_new_item' => 'Add region' ),
				'public'       => false,
				'show_ui'      => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-location',
				'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		);

		$block = function ( $name, $title, $render, $attributes = array() ) {
			register_block_type(
				"nad/$name",
				array(
					'title'           => $title,
					'category'        => 'theme',
					'attributes'      => $attributes,
					'supports'        => array( 'autoRegister' => true, 'html' => false ),
					'render_callback' => $render,
				)
			);
		};
		$block( 'residence-tabs', 'Residence type tabs', 'nad_render_residence_tabs' );
		$block(
			'residences',
			'Featured residences',
			'nad_render_residences',
			array(
				'count' => array( 'type' => 'integer', 'default' => 6, 'label' => 'How many to show', 'autoGenerateControl' => true ),
				'order' => array( 'type' => 'string', 'default' => 'manual', 'enum' => array( 'manual', 'newest', 'random' ), 'label' => 'Order (manual = each residence’s Order field)', 'autoGenerateControl' => true ),
			)
		);
		$block( 'spotlight', 'Residence spotlight', 'nad_render_spotlight' );
		$block( 'regions', 'Regions list', 'nad_render_regions' );
		$block( 'region-media', 'Region photo', 'nad_render_region_media' );
		$block(
			'journal',
			'Journal entries',
			'nad_render_journal',
			array( 'count' => array( 'type' => 'integer', 'default' => 6, 'label' => 'How many posts', 'autoGenerateControl' => true ) )
		);
	}
);

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'residence', 'region' ) as $type ) {
			add_meta_box( 'nad-details', 'Details', 'nad_details_box', $type, 'normal', 'high' );
		}
	}
);

function nad_details_box( $post ) {
	wp_nonce_field( 'nad_details', 'nad_details_nonce' );
	echo '<datalist id="nad-status"><option>Available<option>Under offer<option>In construction<option>Sold</datalist>';
	foreach ( nad_fields( $post->post_type ) as $key => $label ) {
		$value = nad_meta( $post->ID, $key );
		if ( in_array( $key, array( 'featured', 'spotlight' ), true ) ) {
			printf( '<p><label><input type="checkbox" name="nad[%s]" value="1"%s> %s</label></p>', esc_attr( $key ), checked( $value, '1', false ), esc_html( $label ) );
			continue;
		}
		printf(
			'<p><label>%s<br><input class="widefat" name="nad[%s]" value="%s"%s></label></p>',
			esc_html( $label ),
			esc_attr( $key ),
			esc_attr( $value ),
			'status' === $key ? ' list="nad-status"' : ''
		);
	}
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( $_POST['nad_details_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nad_details_nonce'] ), 'nad_details' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$input = isset( $_POST['nad'] ) ? wp_unslash( (array) $_POST['nad'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized per field below.
		foreach ( array_keys( nad_fields( $post->post_type ) ) as $key ) {
			$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
			'' === $value ? delete_post_meta( $post_id, "_ec_$key" ) : update_post_meta( $post_id, "_ec_$key", $value );
		}
	},
	10,
	2
);

/** Featured image filling an .ec-ph box, or the striped placeholder label when there is none. */
function nad_photo( $post_id, $label ) {
	return has_post_thumbnail( $post_id )
		? get_the_post_thumbnail( $post_id, 'large' )
		: '<p>' . esc_html( $label ) . '</p>';
}

function nad_render_residence_tabs() {
	$terms = get_terms( array( 'taxonomy' => 'residence_type', 'orderby' => 'term_id' ) ); // Creation order.
	$html  = '<button type="button" data-ec-filter="all" aria-pressed="true">All</button>';
	foreach ( is_array( $terms ) ? $terms : array() as $term ) {
		$html .= sprintf( '<button type="button" data-ec-filter="%s" aria-pressed="false">%s</button>', esc_attr( $term->slug ), esc_html( $term->name ) );
	}
	return sprintf( '<div %s role="group" aria-label="Filter residences">%s</div>', get_block_wrapper_attributes( array( 'class' => 'ec-tabs' ) ), $html );
}

function nad_render_residences( $attributes ) {
	$orders = array(
		'manual' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'newest' => array( 'date' => 'DESC' ),
		'random' => 'rand',
	);
	$posts  = get_posts(
		array(
			'post_type'      => 'residence',
			'posts_per_page' => max( 1, (int) $attributes['count'] ),
			'meta_key'       => '_ec_featured', // phpcs:ignore WordPress.DB.SlowDBQuery -- tiny collection.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => $orders[ $attributes['order'] ] ?? $orders['manual'],
		)
	);
	$cards  = '';
	foreach ( $posts as $post ) {
		$types = wp_get_post_terms( $post->ID, 'residence_type', array( 'fields' => 'slugs' ) );
		$cats  = implode( ' ', array_map( fn( $slug ) => 'ec-cat-' . sanitize_html_class( $slug ), is_array( $types ) ? $types : array() ) );
		$specs = array_filter( array( nad_meta( $post->ID, 'bedrooms' ) ? nad_meta( $post->ID, 'bedrooms' ) . ' bd' : '', nad_meta( $post->ID, 'interior' ) ) );
		$place = implode( ' · ', array_filter( array( nad_meta( $post->ID, 'location' ), implode( ' · ', $specs ) ) ) );
		$link  = get_permalink( $post );
		$title = get_the_title( $post );
		$cards .= '<div class="wp-block-group is-layout-flow ec-res-card ' . esc_attr( $cats ) . '">'
			. '<div class="wp-block-group is-layout-flow ec-ph ec-res-media">' . nad_photo( $post->ID, $title . ' · photo' )
			. ( nad_meta( $post->ID, 'status' ) ? '<p class="ec-res-status">' . esc_html( nad_meta( $post->ID, 'status' ) ) . '</p>' : '' )
			. '</div><div class="wp-block-group is-layout-flow ec-res-meta"><div class="wp-block-group is-layout-flow">'
			. '<p class="ec-res-name"><a href="' . esc_url( $link ) . '">' . esc_html( $title ) . '</a></p>'
			. '<p class="ec-res-place">' . esc_html( $place ) . '</p></div>'
			. '<p class="ec-res-price">' . esc_html( nad_meta( $post->ID, 'price' ) ) . '</p></div></div>';
	}
	if ( ! $cards ) {
		$cards = '<p>No featured residences yet — tick “Featured” on a residence in Residences.</p>';
	}
	return sprintf( '<div %s>%s</div>', get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-layout-flow ec-res-grid ec-reveal-group' ) ), $cards );
}

/** Front page: the residence ticked "Spotlight". On a residence's own page: that residence, as the page hero. */
function nad_render_spotlight() {
	$single = is_singular( 'residence' );
	$post   = $single ? get_queried_object() : get_posts(
		array(
			'post_type'      => 'residence',
			'posts_per_page' => 1,
			'meta_key'       => '_ec_spotlight', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'modified',
		)
	)[0] ?? null;
	if ( ! $post ) {
		return '';
	}
	$title = get_the_title( $post );
	$where = implode( ' — ', array_filter( array( nad_meta( $post->ID, 'location' ), nad_meta( $post->ID, 'completed' ) ? 'Completed ' . nad_meta( $post->ID, 'completed' ) : '' ) ) );
	$specs = '';
	foreach ( array( 'bedrooms' => 'Bedrooms', 'bathrooms' => 'Bathrooms', 'interior' => 'Interior', 'grounds' => 'Grounds' ) as $key => $label ) {
		if ( nad_meta( $post->ID, $key ) ) {
			$specs .= '<div class="wp-block-group is-layout-flow"><p class="ec-mono">' . $label . '</p><p class="ec-spot-value">' . esc_html( nad_meta( $post->ID, $key ) ) . '</p></div>';
		}
	}
	$photo = has_post_thumbnail( $post )
		? get_the_post_thumbnail( $post, 'full' )
		: '<p class="ec-mono ec-spot-label">full-bleed exterior · ' . esc_html( $title ) . '</p>';
	return sprintf(
		'<section %1$s>%2$s<div class="wp-block-group is-layout-flow ec-wrap ec-spot-inner"><div class="wp-block-group is-layout-flow ec-mono ec-spot-top"><p>%3$s</p><p>%4$s</p></div>'
		. '<div class="wp-block-group is-layout-flow ec-spot-bottom"><%5$s class="wp-block-heading ec-spot-title ec-reveal">%6$s</%5$s><div class="wp-block-group is-layout-flow ec-spot-card ec-reveal">'
		. '<div class="wp-block-group is-layout-flow ec-spot-specs">%7$s</div><div class="wp-block-group is-layout-flow ec-spot-foot"><p class="ec-spot-price">%8$s</p>'
		. '<div class="wp-block-buttons ec-btn-sm is-layout-flex"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="%9$s">%10$s</a></div></div>'
		. '</div></div></div></div></section>',
		get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-layout-flow alignfull ec-spotlight' ) ),
		$photo,
		$single ? esc_html( nad_meta( $post->ID, 'status' ) ?: 'Residence' ) : '(02) Residence in focus',
		esc_html( $where ),
		$single ? 'h1' : 'h2',
		esc_html( $title ),
		$specs,
		esc_html( nad_meta( $post->ID, 'price' ) ),
		$single ? '#contact' : esc_url( get_permalink( $post ) ),
		$single ? 'Book a viewing' : 'View residence'
	);
}

function nad_regions() {
	return get_posts(
		array(
			'post_type'      => 'region',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
}

function nad_homes( $post_id ) {
	$homes = nad_meta( $post_id, 'homes' );
	return '' === $homes ? '' : sprintf( '%s %s', $homes, '1' === $homes ? 'home' : 'homes' );
}

function nad_render_regions() {
	$rows = '';
	foreach ( nad_regions() as $i => $post ) {
		$rows .= sprintf(
			'<div class="wp-block-group is-layout-flow ec-region%s" data-img="%s"><p class="ec-mono">%02d</p><p class="ec-region-name"><a href="#index">%s</a></p><p class="ec-region-count">%s</p><p class="ec-region-arrow">→</p></div>',
			0 === $i ? ' is-active' : '',
			esc_url( (string) get_the_post_thumbnail_url( $post, 'large' ) ),
			$i + 1,
			esc_html( get_the_title( $post ) ),
			esc_html( nad_homes( $post->ID ) )
		);
	}
	return sprintf( '<div %s>%s</div>', get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-layout-flow ec-regions ec-reveal' ) ), $rows );
}

/** The sticky photo + caption beside the list; starts on the first region, evercrest.js swaps it on hover. */
function nad_render_region_media() {
	$first = nad_regions()[0] ?? null;
	if ( ! $first ) {
		return '';
	}
	$name = get_the_title( $first );
	$src  = get_the_post_thumbnail_url( $first, 'large' );
	return sprintf(
		'<div %s><div class="wp-block-group is-layout-flow ec-ph ec-index-img"><p>%s · landscape photo</p><img alt=""%s></div><div class="wp-block-group is-layout-flow ec-index-caption"><p>%s</p><p>%s</p></div></div>',
		get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-layout-flow ec-index-media ec-reveal' ) ),
		esc_html( strtolower( $name ) ),
		$src ? ' src="' . esc_url( $src ) . '"' : '',
		esc_html( $name ),
		esc_html( nad_homes( $first->ID ) )
	);
}

/** Latest posts as carousel slides: first category · month, title, featured image. */
function nad_render_journal( $attributes ) {
	$slides = '';
	foreach ( get_posts( array( 'posts_per_page' => max( 1, (int) $attributes['count'] ) ) ) as $post ) {
		$cat     = get_the_category( $post->ID )[0]->name ?? '';
		$title   = get_the_title( $post );
		$slides .= '<div class="wp-block-group is-layout-flow ec-journal-card swiper-slide">'
			. '<div class="wp-block-group is-layout-flow ec-ph ec-journal-img">' . nad_photo( $post->ID, $title . ' · photo' ) . '</div>'
			. '<p class="ec-mono ec-journal-meta">' . esc_html( implode( ' · ', array_filter( array( $cat, get_the_date( 'M Y', $post ) ) ) ) ) . '</p>'
			. '<p class="ec-journal-title"><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $title ) . '</a></p></div>';
	}
	return sprintf( '<div %s>%s</div>', get_block_wrapper_attributes( array( 'class' => 'wp-block-group is-layout-flow swiper-wrapper ec-reveal-group' ) ), $slides );
}
