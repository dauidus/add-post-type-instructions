<?php
/**
 * Custom Featured Image Metabox.
 *
 * @package   Custom_Featured_Image_Metabox
 * @author    1fixdotio <1fixdotio@gmail.com>
 * @license   GPL-2.0+
 * @link      http://1fix.io
 * @copyright 2014 1Fix.io
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-custom-featured-image-metabox-admin.php`
 *
 * @package Custom_Featured_Image_Metabox
 * @author  1fixdotio <1fixdotio@gmail.com>
 */
class Custom_Featured_Image_Metabox {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cfim';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Display the admin notification
		add_action( 'admin_notices', array( $this, 'admin_notice_activation' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    0.1.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {

		return $this->plugin_slug;
	}

	/**
	 * Get post types with thumbnail support
	 *
	 * @return array supported post types
	 *
	 * @since 0.6.0
	 */
	public function supported_post_types() {

		$post_types = get_post_types();
		$results = array();

		foreach ( $post_types as $pt ) {
			if ( post_type_supports( $pt, 'thumbnail' ) ) {
				$results[] = $pt;
			}
		}

		return $results;

	} // end supported_post_types

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.1.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    0.4.0
	 */
	private static function single_activate() {

		if ( false == get_option( 'cfim-display-activation-message' ) ) {
			add_option( 'cfim-display-activation-message', true );
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.4.0
	 */
	private static function single_deactivate() {

		delete_option( 'cfim-display-activation-message' );

	}

	/**
	 * Display notice message when activating the plugin.
	 *
	 * @since 0.4.0
	 */
	public function admin_notice_activation() {

		$screen = get_current_screen();

		if ( true == get_option( 'cfim-display-activation-message' ) && 'plugins' == $screen->id ) {
			$plugin = self::get_instance();

			$html  = '<div class="updated">';
			$html .= '<p>';
				$html .= sprintf( __( 'Custom the Featured Image Metabox at <strong><a href="%s">Settings</a></strong> page.', $plugin->get_plugin_slug() ), admin_url( 'options-general.php?page=' . $plugin->get_plugin_slug() ) );
			$html .= '</p>';
			$html .= '</div><!-- /.updated -->';

			echo $html;

			delete_option( 'cfim-display-activation-message' );

		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_PLUGIN_DIR ) . 'custom-featured-image-metabox/languages/' . $locale . '.mo' );

	}

}
