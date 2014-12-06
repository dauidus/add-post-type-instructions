<?php
class Set_Helper_Content_Settings {
	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;
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
		$plugin = Set_Helper_Content::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		// Add settings page
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
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
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {
		$plugin = Set_Helper_Content::get_instance();
		$post_types = $plugin->supported_post_types();
		$defaults = array(
				'instruction' => '',
				'content' => ''
			);
		foreach ( $post_types as $pt ) {
			$post_object = get_post_type_object( $pt );
			$section = $this->plugin_slug . '_' . $pt;
			if ( false == get_option( $section ) ) {
				add_option( $section, apply_filters( $section . '_default_settings', $defaults ) );
			}
			$args = array( $section, get_option( $section ) );
			add_settings_section(
				$pt,
				sprintf( __( 'Set helper options for %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);
			add_settings_field(
				'instruction',
				__( '<br />Set Instructional Content:', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$section,
				$pt,
				$args
			);
			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'content',
					__( '<br />Set Default Content:', $this->plugin_slug ),
					array( $this, 'content_callback' ),
					$section,
					$pt,
					$args
				);
			} else { 
				add_settings_field(
					'error',
					__( '', $this->plugin_slug ),
					array( $this, 'error_callback' ),
					$section,
					$pt,
					$args
				);
			}
			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init
	public function instruction_callback( $args ) {
		$output = $args[0].'[instruction]';
		$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';
	/*	$settings = array( 
			'textarea_name' => $output,
			'textarea_rows' => '5'
		);	*/
		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="4" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter content to display below the title field, such as special instructions for this post type. HTML allowed.', $this->plugin_slug ) . '</p>';
		echo $html . '<br />';
	} // end instruction_callback
	public function content_callback( $args ) {
		$output = $args[0].'[content]';
		$value  = isset( $args[1]['content'] ) ? $args[1]['content'] : '';
	/*	$id = $args[0].'_content';
		$settings = array( 
			'textarea_name' => $output, 
			'textarea_rows' => '5'
		);	*/
		//wp_editor( $value, $id, $settings );
		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="6" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing". HTML and shortcodes allowed.', $this->plugin_slug ) . '</p><br />';
		echo $html;
	} // end content_callback
	public function error_callback( $args ) {
		$html = '<p><br>' . __( 'This post type does not include support for the WYSIWYG editor feature.', $this->plugin_slug ) . '</p>';
		echo $html;
	} // end error_callback
}
Set_Helper_Content_Settings::get_instance();
