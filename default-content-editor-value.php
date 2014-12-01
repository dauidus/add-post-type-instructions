<?php
/**
 * Default Content Editor Value.
 *
 * Allows admins to set default content to populate the editor for each active post type.
 *
 * @package   Default_Content_Editor_Value
 * @author    dauidus (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 *
 * @wordpress-plugin
 * Plugin Name:       Default Content Editor Value
 * Plugin URI:        http://dauid.us
 * Description:       Allows admins to set default content to populate the editor for each active post type.
 * Version:           1.0.0
 * Author:            dauidus
 * Author URI:        http://dauid.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/dauidus/default-content-editor-value
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-default-content-editor-value.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Default_Content_Editor_Value', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Default_Content_Editor_Value', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Default_Content_Editor_Value', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-default-content-editor-value-admin.php' );
	add_action( 'plugins_loaded', array( 'Default_Content_Editor_Value_Admin', 'get_instance' ) );

}

