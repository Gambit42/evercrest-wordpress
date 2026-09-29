<?php
/**
 * Plugin Name: Evercrest Core
 * Description: Core content types, fields, and queries for the Evercrest website.
 * Version: 1.1.0
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: Evercrest
 * Text Domain: evercrest-core
 */

defined( 'ABSPATH' ) || exit;

define( 'EVERCREST_CORE_VERSION', '1.1.0' );
define( 'EVERCREST_CORE_PATH', plugin_dir_path( __FILE__ ) );

require_once EVERCREST_CORE_PATH . 'includes/post-types.php';
require_once EVERCREST_CORE_PATH . 'includes/fields.php';
require_once EVERCREST_CORE_PATH . 'includes/queries.php';

register_activation_hook(
	__FILE__,
	function () {
		evercrest_core_register_content_types();
		evercrest_core_seed_testimonials();
		update_option( 'evercrest_core_version', EVERCREST_CORE_VERSION );
		flush_rewrite_rules();
	}
);

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

add_action(
	'init',
	function () {
		if ( EVERCREST_CORE_VERSION === get_option( 'evercrest_core_version' ) ) {
			return;
		}
		evercrest_core_seed_testimonials();
		update_option( 'evercrest_core_version', EVERCREST_CORE_VERSION );
	},
	20
);
