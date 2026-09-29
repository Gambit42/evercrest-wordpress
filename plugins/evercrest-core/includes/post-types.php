<?php
/** Evercrest post types and taxonomies. */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'evercrest_core_register_content_types' );

function evercrest_core_register_content_types() {
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
			'hierarchical'      => true,
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

	register_post_type(
		'testimonial',
		array(
			'label'        => 'Testimonials',
			'labels'       => array(
				'singular_name' => 'Testimonial',
				'add_new_item'  => 'Add testimonial',
				'edit_item'     => 'Edit testimonial',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		)
	);
}
