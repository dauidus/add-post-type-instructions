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
 *
 * @wordpress-plugin
 * Plugin Name:       Set Helper Content
 * Plugin URI:        http://dauid.us
 * Description:       Allows admins to easily set instructional text per post type.  Currently supports text below the title field and within the WYSIWYG editor.
 * Version:           1.0
 * Author:            dauidus
 * Author URI:        http://dauid.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/dauidus/set-helper-content
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-set-helper-content.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Set_Helper_Content', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Set_Helper_Content', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Set_Helper_Content', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-set-helper-content-admin.php' );
	add_action( 'plugins_loaded', array( 'Set_Helper_Content_Admin', 'get_instance' ) );

}

