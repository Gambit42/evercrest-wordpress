<?php
/** Reusable Evercrest content queries. */

defined( 'ABSPATH' ) || exit;

function evercrest_core_featured_residences( $count = 6, $order = 'manual' ) {
	$orders = array(
		'manual' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'newest' => array( 'date' => 'DESC' ),
		'random' => 'rand',
	);
	return get_posts(
		array(
			'post_type'      => 'residence',
			'posts_per_page' => max( 1, (int) $count ),
			'meta_key'       => '_ec_featured', // phpcs:ignore WordPress.DB.SlowDBQuery -- small collection.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => $orders[ $order ] ?? $orders['manual'],
		)
	);
}

function evercrest_core_spotlight_residence() {
	return get_posts(
		array(
			'post_type'      => 'residence',
			'posts_per_page' => 1,
			'meta_key'       => '_ec_spotlight', // phpcs:ignore WordPress.DB.SlowDBQuery -- small collection.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'modified',
		)
	)[0] ?? null;
}

function evercrest_core_regions() {
	return get_posts(
		array(
			'post_type'      => 'region',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
}

function evercrest_core_homes( $post_id ) {
	$homes = evercrest_core_meta( $post_id, 'homes' );
	return '' === $homes ? '' : sprintf( '%s %s', $homes, '1' === $homes ? 'home' : 'homes' );
}

/** Return testimonials in their editorial order. */
function evercrest_core_testimonials() {
	return get_posts(
		array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		)
	);
}

/**
 * Convert the original theme samples into editable testimonial records once.
 * Existing testimonial content is never overwritten.
 */
function evercrest_core_seed_testimonials() {
	$existing = get_posts(
		array(
			'post_type'      => 'testimonial',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return;
	}

	$testimonials = array(
		array(
			'title'       => 'Daniel Whitmore',
			'content'     => 'We came to Evercrest with a site and a feeling. Thirty months later we have a house that answers every question the mountain asks of it — and a winter heating bill a third of what we used to pay.',
			'attribution' => 'Owner, The Aspen Residence',
			'thumbnail'   => 40,
		),
		array(
			'title'       => 'Claire Ashford',
			'content'     => 'They walked the bluff at dawn, at noon and at dusk before drawing a single line. That patience is in every room. When the fog rolls in, the house simply belongs to it.',
			'attribution' => 'Owner, Glasshouse on the Bluff',
			'thumbnail'   => 41,
		),
		array(
			'title'       => 'Elena Marchetti',
			'content'     => 'What surprised us most was the calm. One team, one point of contact, no surprises on the budget — and a garden already growing the day we moved in.',
			'attribution' => 'Owner, Villa Serena',
			'thumbnail'   => 42,
		),
	);

	foreach ( $testimonials as $order => $testimonial ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'testimonial',
				'post_status'  => 'publish',
				'post_title'   => $testimonial['title'],
				'post_content' => $testimonial['content'],
				'menu_order'   => $order,
			),
			true
		);
		if ( is_wp_error( $post_id ) ) {
			continue;
		}
		update_post_meta( $post_id, '_ec_attribution', $testimonial['attribution'] );
		if ( wp_attachment_is_image( $testimonial['thumbnail'] ) ) {
			set_post_thumbnail( $post_id, $testimonial['thumbnail'] );
		}
	}
}
