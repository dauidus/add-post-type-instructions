<?php
/**
 * Add Post Type Instructions.
 *
 * For A Better UX
 *
 * @package   Add_Post_Type_Instructions
 * @author    Dave Winter (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 *
 * @wordpress-plugin
 * Plugin Name:       Add Post Type Instructions
 * Plugin URI:        http://dauid.us
 * Description:       Allows admins to easily set instructional text for pages, posts or custom post types.  Currently supports inserting text below the title field, setting content within the WYSIWYG editor and adding instructional text inside the following metaboxes: author, featured image, excerpt, trackbacks, custom fields, page attributes, post format, categories, tags.  Only adds options for the metaboxes supported for each post type.  Works with multisite.
 * Version:           1.0.3
 * Author:            Dave Winter
 * Author URI:        http://dauid.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/dauidus/add-post-type-instructions
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-add-post-type-instructions.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Add_Post_Type_Instructions', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Add_Post_Type_Instructions', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Add_Post_Type_Instructions', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-add-post-type-instructions-admin.php' );
	add_action( 'plugins_loaded', array( 'Add_Post_Type_Instructions_Admin', 'get_instance' ) );

}

