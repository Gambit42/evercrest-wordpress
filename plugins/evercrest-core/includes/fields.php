<?php
/** Evercrest content fields and persistence. */

defined( 'ABSPATH' ) || exit;

function evercrest_core_fields( $post_type ) {
	$fields = array(
		'region'    => array( 'homes' => 'Homes built (number, e.g. 24)' ),
		'testimonial' => array( 'attribution' => 'Role or attribution (e.g. Owner, The Aspen Residence)' ),
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

function evercrest_core_meta( $post_id, $key ) {
	return get_post_meta( $post_id, "_ec_$key", true );
}

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'residence', 'region', 'testimonial' ) as $post_type ) {
			add_meta_box( 'evercrest-details', 'Details', 'evercrest_core_details_box', $post_type, 'normal', 'high' );
		}
	}
);

function evercrest_core_details_box( $post ) {
	wp_nonce_field( 'evercrest_details', 'evercrest_details_nonce' );
	echo '<datalist id="evercrest-status"><option>Available<option>Under offer<option>In construction<option>Sold</datalist>';
	foreach ( evercrest_core_fields( $post->post_type ) as $key => $label ) {
		$value = evercrest_core_meta( $post->ID, $key );
		if ( in_array( $key, array( 'featured', 'spotlight' ), true ) ) {
			printf( '<p><label><input type="checkbox" name="evercrest[%s]" value="1"%s> %s</label></p>', esc_attr( $key ), checked( $value, '1', false ), esc_html( $label ) );
			continue;
		}
		printf(
			'<p><label>%s<br><input class="widefat" name="evercrest[%s]" value="%s"%s></label></p>',
			esc_html( $label ),
			esc_attr( $key ),
			esc_attr( $value ),
			'status' === $key ? ' list="evercrest-status"' : ''
		);
	}
}

add_action( 'save_post_residence', 'evercrest_core_save_details' );
add_action( 'save_post_region', 'evercrest_core_save_details' );
add_action( 'save_post_testimonial', 'evercrest_core_save_details' );

function evercrest_core_save_details( $post_id ) {
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['evercrest_details_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['evercrest_details_nonce'] ), 'evercrest_details' )
		|| ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$post_type = get_post_type( $post_id );
	$input     = isset( $_POST['evercrest'] ) ? wp_unslash( (array) $_POST['evercrest'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized below.
	foreach ( array_keys( evercrest_core_fields( $post_type ) ) as $key ) {
		$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, "_ec_$key" );
		} else {
			update_post_meta( $post_id, "_ec_$key", $value );
		}
	}
}
