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

require_once( plugin_dir_path( __FILE__ ) . 'public/class-set-helper-content.php' );

$plugin = Set_Helper_Content::get_instance();
$post_types = $plugin->supported_post_types();
foreach ( $post_types as $pt ) {
	delete_option( $plugin->get_plugin_slug() . '_' . $pt );
}

delete_option( 'shc-display-activation-message' );
/**
 * @todo Delete options in whole network
 */
