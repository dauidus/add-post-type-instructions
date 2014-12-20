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
 */

class add_post_type_instructions {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0
	 *
	 * @var     string
	 */
	const VERSION = '1.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'apti';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0
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
	 * @since    1.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {

		return $this->plugin_slug;
	}

	/**
	 * Get post types with show_ui support and exclude media
	 *
	 * @return array supported post types
	 *
	 * @since 1.0
	 */
	public function supported_post_types() {

		$args = array(
		   'show_ui' => true,
		);

		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		$post_types = get_post_types( $args, $output, $operator );
		unset( $post_types['attachment'] );

		$results = array();

		foreach ( $post_types as $pt ) {

			$results[] = $pt;			
		}

		return $results;

	} // end supported_post_types

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
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
	 * @since    1.0.1
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
	 * @since    1.0.1
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
	 * @since    1.0.1
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
	 * @since    1.0.1
	 */
	private static function single_activate() {

		if ( false == get_option( 'apti-display-activation-message' ) ) {
			add_option( 'apti-display-activation-message', true );
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.1
	 */
	private static function single_deactivate() {

		delete_option( 'apti-display-activation-message' );

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.1
	 */
	private static function single_uninstall() {

		delete_option( 'apti-display-uninstallation-message' );

	}

	/**
	 * Display notice message when activating the plugin.
	 *
	 * @since 1.0.1
	 */
	public function admin_notice_activation() {

		$screen = get_current_screen();

		if ( true == get_option( 'apti-display-activation-message' ) && 'plugins' == $screen->id ) {
			$plugin = self::get_instance();

			$html  = '<div class="updated">';
			$html .= '<p>';
				$html .= sprintf( __( 'Plugin activated! Set instructional and default content for your site <strong><a href="%s">here</a></strong>.', $plugin->get_plugin_slug() ), admin_url( 'options-general.php?page=' . $plugin->get_plugin_slug() ) );
			$html .= '</p>';
			$html .= '</div><!-- /.updated -->';

			echo $html;

			delete_option( 'apti-display-activation-message' );

		}
	}

	/**
	 * Display notice message when activating the plugin.
	 *
	 * @since 1.0.1
	 */
	public function admin_notice_uninstallation() {

		$screen = get_current_screen();

		if ( true == get_option( 'apti-display-uninstallation-message' ) && 'plugins' == $screen->id ) {
			$plugin = self::get_instance();

			$html  = '<div class="updated">';
			$html .= '<p>';
				$html .= sprintf( __( 'You have deleted the Add Post Type Instructions plugin. All settings associated with the plugin have been removed from the database.', $plugin->get_plugin_slug() ), admin_url( 'options-general.php?page=' . $plugin->get_plugin_slug() ) );
			$html .= '</p>';
			$html .= '</div><!-- /.updated -->';

			echo $html;

			delete_option( 'apti-display-uninstallation-message' );

		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_PLUGIN_DIR ) . 'languages/' . $locale . '.mo' );

	}

}
