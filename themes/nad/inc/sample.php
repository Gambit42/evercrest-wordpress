<?php
/**
 * SAMPLE / LEARNING FILE, NOT LOADED.
 *
 * The same structure as collections.php (Residences), boiled down to one post type
 * ("Projects") with three kinds of custom fields: text, checkbox, dropdown.
 *
 * To try it: add this line to functions.php, then look for "Projects" in wp-admin.
 *     require get_parent_theme_file_path( 'inc/sample.php' );
 *
 * The flow is 5 steps:
 *   1. Register the post type         → WordPress builds the admin menu + list + editor
 *   2. Define the fields ("schema")    → one array, the single source of truth
 *   3. Draw the fields (meta box)      → the form on the edit screen
 *   4. Save the fields (save_post)     → the submit handler, writes to wp_postmeta
 *   5. Read the fields                 → use them anywhere on the front end
 */

/*
 * ---------------------------------------------------------------------------
 * 1. REGISTER THE POST TYPE
 * ---------------------------------------------------------------------------
 * Must run on the `init` hook (WordPress's "app is booting" moment).
 * This is the whole "model" declaration: rows go into wp_posts with post_type = 'project'.
 * Everything in wp-admin (sidebar item, list table, Add New, Trash, editor) is generated from it.
 */
add_action(
	'init',
	function () {
		register_post_type(
			'project', // Internal name, stored in wp_posts.post_type. Max 20 chars, no capitals/spaces.
			array(
				'label'        => 'Projects',                    // Sidebar menu text.
				'labels'       => array(
					'singular_name' => 'Project',
					'add_new_item'  => 'Add project',            // The "Add New" button/screen title.
				),
				'public'       => true,                          // Has front-end URLs (/projects/my-project/).
				'show_in_rest' => true,                          // Required for the block editor + gives /wp-json/wp/v2/project.
				'has_archive'  => 'projects',                    // /projects/ lists them all.
				'rewrite'      => array( 'slug' => 'projects' ), // URL prefix for single items.
				'menu_icon'    => 'dashicons-portfolio',         // https://developer.wordpress.org/resource/dashicons/
				'supports'     => array( 'title', 'editor', 'thumbnail' ), // Built-in fields to show (see previous notes).
			)
		);
	}
);

/*
 * ---------------------------------------------------------------------------
 * 2. DEFINE THE CUSTOM FIELDS (the "metafield definitions")
 * ---------------------------------------------------------------------------
 * key => [ label, type ]. Steps 3 and 4 both loop over this, so adding a field = adding a line here.
 * Each value is stored in wp_postmeta as meta_key "_project_{key}".
 * The leading underscore makes it "protected": hidden from the generic Custom Fields panel,
 * so only our box edits it.
 */
function sample_fields() {
	return array(
		'client'   => array( 'Client name', 'text' ),
		'featured' => array( 'Show on the homepage', 'checkbox' ),
		'status'   => array( 'Status', array( 'Planning', 'In progress', 'Done' ) ), // Array = dropdown options.
	);
}

/*
 * ---------------------------------------------------------------------------
 * 3. DRAW THE FIELDS: a meta box on the edit screen
 * ---------------------------------------------------------------------------
 * add_meta_box( id, title, callback that prints the HTML, post type, position, priority ).
 * With the block editor it shows as a panel under the content.
 */
add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'sample-details', 'Project details', 'sample_details_box', 'project', 'normal', 'high' );
	}
);

function sample_details_box( $post ) {
	// A nonce is a one-time token proving the save came from THIS form (CSRF protection). Checked in step 4.
	wp_nonce_field( 'sample_details', 'sample_details_nonce' );

	foreach ( sample_fields() as $key => list( $label, $type ) ) {
		$value = get_post_meta( $post->ID, "_project_$key", true ); // Current saved value ('' if none).
		$name  = "sample[$key]";                                    // All fields post back as one array: $_POST['sample'].

		echo '<p><label>';
		if ( 'checkbox' === $type ) {
			// Checked box sends '1'; unchecked sends nothing at all (that's how HTML forms work).
			printf( '<input type="checkbox" name="%s" value="1"%s> %s', esc_attr( $name ), checked( $value, '1', false ), esc_html( $label ) );
		} elseif ( is_array( $type ) ) {
			printf( '%s<br><select name="%s"><option value="">—</option>', esc_html( $label ), esc_attr( $name ) );
			foreach ( $type as $option ) {
				printf( '<option%s>%s</option>', selected( $value, $option, false ), esc_html( $option ) );
			}
			echo '</select>';
		} else {
			printf( '%s<br><input class="widefat" name="%s" value="%s">', esc_html( $label ), esc_attr( $name ), esc_attr( $value ) );
		}
		echo '</label></p>';
	}
	// Always escape output: esc_html() for text, esc_attr() inside attributes. Never echo raw DB values.
}

/*
 * ---------------------------------------------------------------------------
 * 4. SAVE THE FIELDS: the submit handler
 * ---------------------------------------------------------------------------
 * save_post_{post_type} fires only for this type, whenever the post is saved (Update / Publish).
 * WordPress saves title/content/thumbnail itself; we only handle our own fields.
 */
add_action(
	'save_post_project',
	function ( $post_id ) {
		// Security gate: valid nonce (came from our form) + user is allowed to edit this post.
		if ( ! isset( $_POST['sample_details_nonce'] )
			|| ! wp_verify_nonce( sanitize_key( $_POST['sample_details_nonce'] ), 'sample_details' )
			|| ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$input = isset( $_POST['sample'] ) ? wp_unslash( (array) $_POST['sample'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized below.

		foreach ( array_keys( sample_fields() ) as $key ) {
			// Sanitize input: never trust $_POST. Missing key (e.g. unchecked checkbox) becomes ''.
			$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';

			// Empty = delete the row (keeps wp_postmeta clean); otherwise insert-or-update.
			if ( '' === $value ) {
				delete_post_meta( $post_id, "_project_$key" );
			} else {
				update_post_meta( $post_id, "_project_$key", $value );
			}
		}
	}
);

/*
 * ---------------------------------------------------------------------------
 * 5. READ THE FIELDS: using them on the front end
 * ---------------------------------------------------------------------------
 * get_post_meta( id, key, true ) → the value as a string ('' if unset). `true` = single value, not an array.
 * The query below is the "findMany where featured = true" equivalent.
 */
function sample_featured_projects_html() {
	$projects = get_posts(
		array(
			'post_type'      => 'project',
			'posts_per_page' => 6,
			'meta_key'       => '_project_featured', // phpcs:ignore WordPress.DB.SlowDBQuery -- small collection.
			'meta_value'     => '1',                 // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);

	$html = '';
	foreach ( $projects as $project ) {
		$html .= sprintf(
			'<article>%s<h3><a href="%s">%s</a></h3><p>%s · %s</p></article>',
			get_the_post_thumbnail( $project, 'medium' ),                        // Built-in field (supports: thumbnail).
			esc_url( get_permalink( $project ) ),                                // Built-in URL.
			esc_html( get_the_title( $project ) ),                               // Built-in field (supports: title).
			esc_html( get_post_meta( $project->ID, '_project_client', true ) ), // Custom field.
			esc_html( get_post_meta( $project->ID, '_project_status', true ) )  // Custom field.
		);
	}
	return $html;
}
// To put it on a page, wrap it in a block (like collections.php does with register_block_type +
// render_callback) or, quickest for testing, a shortcode:
// add_shortcode( 'featured_projects', 'sample_featured_projects_html' );  → type [featured_projects] in any page.
