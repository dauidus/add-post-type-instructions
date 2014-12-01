<?php
/**
 * Custom Featured Image Metabox.
 *
 * @package   Custom_Featured_Image_Metabox_Admin
 * @author    1fixdotio <1fixdotio@gmail.com>
 * @license   GPL-2.0+
 * @link      http://1fix.io
 * @copyright 2014 1Fix.io
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-custom-featured-image-metabox.php`
 *
 * @package Custom_Featured_Image_Metabox_Admin
 * @author  1fixdotio <1fixdotio>
 */
class Custom_Featured_Image_Metabox_Admin {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    0.8.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = Custom_Featured_Image_Metabox::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page and menu item.
		require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'custom-featured-image-metabox.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		add_action( 'add_meta_boxes', array( $this, 'change_metabox_title' ) );
		add_filter( 'admin_post_thumbnail_html', array( $this, 'change_metabox_content' ) );
		add_filter( 'media_view_strings', array( $this, 'change_media_strings' ), 10, 2 );

	}

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
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.1.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Custom Featured Image Metabox', $this->plugin_slug ),
			__( 'Custom Featured Image Metabox', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.1.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.1.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings' ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Get post type
	 *
	 * @return string Post type
	 *
	 * @since 0.9.5
	 */
	public function get_post_type() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_REQUEST['post_id'] ) ) {
				$post = get_post( $_REQUEST['post_id'] );
				return $post->post_type;
			}
		}

		$screen = get_current_screen();

		return $screen->post_type;

	} // end get_post_type

	/**
	 * Change the title of Featured Image Metabox
	 *
	 * @return null
	 *
	 * @since 0.8.0
	 */
	public function change_metabox_title() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['title'] ) && ! empty( $options['title'] ) ) {
			//remove original featured image metabox
			remove_meta_box( 'postimagediv', $post_type, 'side' );

			//add our customized metabox
			add_meta_box( 'postimagediv', $options['title'], 'post_thumbnail_meta_box', $post_type, 'side', 'low' );
		}

	} // end change_metabox_title

	/**
	 * Change metabox content
	 *
	 * @param  string $content HTML string
	 * @return string Modified content
	 *
	 * @since 0.8.0
	 */
	public function change_metabox_content( $content ) {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['instruction'] ) && ! empty( $options['instruction'] ) ) {
			$instruction = '<p class="cfim-instruction">' . $options['instruction'] . '</p>';

			$content = $instruction . $content;
		}

		if ( isset( $options['set_text'] ) && ! empty( $options['set_text'] ) ) {
			$content = str_replace( __( 'Set featured image' ), $options['set_text'], $content );
		}

		if ( isset( $options['remove_text'] ) && ! empty( $options['remove_text'] ) ) {
			$content = str_replace( __( 'Remove featured image' ), $options['remove_text'], $content );
		}

		return $content;

	} // end change_metabox_content

	/**
	 * Change the strings in media manager
	 *
	 * @param  array $strings Strings array
	 * @param  object $post   Post object
	 * @return array          Modified strings array
	 *
	 * @since 0.8.0
	 */
	public function change_media_strings( $strings, $post ) {

		if ( is_object( $post ) && ! empty( $post ) ) {
			$post_type = $post->post_type;
			$options = get_option( $this->plugin_slug . '_' . $post_type );

			if ( isset( $options['set_text'] ) && ! empty( $options['set_text'] ) ) {
				$strings['setFeaturedImage']      = $options['set_text'];
				$strings['setFeaturedImageTitle'] = $options['set_text'];
			}

		}

		return $strings;

	} // end change_media_strings

}
