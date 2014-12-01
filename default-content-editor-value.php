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
 * Author:            dauidite
 * Author URI:        http://dauid.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/1fixdotio/custom-featured-image-metabox
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-custom-featured-image-metabox.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Custom_Featured_Image_Metabox', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Custom_Featured_Image_Metabox', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Custom_Featured_Image_Metabox', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-custom-featured-image-metabox-admin.php' );
	add_action( 'plugins_loaded', array( 'Custom_Featured_Image_Metabox_Admin', 'get_instance' ) );

}
































// get custom post types with content editor support
function supported_post_types() {

	$post_types = get_post_types();
	$results = array();

	foreach ( $post_types as $pt ) {
		if ( post_type_supports( $pt, 'editor' ) ) {
			$results[] = $pt;
		}
	}
	return $results;

} // end supported_post_types



foreach ( $post_types  as $post_type ) {

	// value for editor_content
	$post_type_edit_content = '';

	// change content in editor
	add_filter( 'the_editor_content', 'editor_content' ); 
	function editor_content( $content ) {
	    // Only return the filtered content if it's empty
	    if ( empty( $content ) ) {
	        $template  = '' . $post_type_edit_content . '';
	        return $template;
	    } else
	        return $content;
	}


}










