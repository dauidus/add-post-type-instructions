<?php
/**
 * Set Helper Content.
 *
 * For A Better UX
 *
 * @package   Set_Helper_Content
 * @author    dauidus (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-add-post-type-instructions.php' );

$plugin = Add_Post_Type_Instructions::get_instance();
$post_types = $plugin->supported_post_types();
foreach ( $post_types as $pt ) {
	delete_option( $plugin->get_plugin_slug() . '_' . $pt );
}

	/**
	 * Change tags metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 2.0
	 */
	delete_option( 'apti-display-uninstallation-message' );

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {

		if ( $network_wide ) {

			// Get all blog ids
			$blog_ids = self::get_blog_ids();

			foreach ( $blog_ids as $blog_id ) {

				delete_option( $plugin->get_plugin_slug() . '_' . $pt );

			}

			restore_current_blog();

		} else {
			delete_option( $plugin->get_plugin_slug() . '_' . $pt );
		}

	} else {
		delete_option( $plugin->get_plugin_slug() . '_' . $pt );
	}
