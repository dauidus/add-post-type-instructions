<?php

class Custom_Featured_Image_Metabox_Settings {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    0.5.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    0.5.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.5.0
	 */
	private function __construct() {

		$plugin = Custom_Featured_Image_Metabox::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add settings page
		add_action( 'admin_init', array( $this, 'admin_init' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.5.0
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

		$plugin = Custom_Featured_Image_Metabox::get_instance();
		$post_types = $plugin->supported_post_types();

		$defaults = array(
				'title' => '',
				'instruction' => '',
				'set_text' => '',
				'remove_text' => '',
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
				sprintf( __( 'Featured Image Metabox in %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'title',
				__( 'Title Text', $this->plugin_slug ),
				array( $this, 'title_callback' ),
				$section,
				$pt,
				$args
			);

			add_settings_field(
				'instruction',
				__( 'Instruction', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$section,
				$pt,
				$args
			);

			add_settings_field(
				'set_text',
				__( 'Set Text', $this->plugin_slug ),
				array( $this, 'set_text_callback' ),
				$section,
				$pt,
				$args
			);

			add_settings_field(
				'remove_text',
				__( 'Remove Text', $this->plugin_slug ),
				array( $this, 'remove_text_callback' ),
				$section,
				$pt,
				$args
			);

			register_setting(
				$section,
				$section,
				array( $this, 'validate_inputs' )
			);
		}

	} // end admin_init

	public function title_callback( $args ) {

		$value  = isset( $args[1]['title'] ) ? $args[1]['title'] : '';

		$html = '<input type="text" id="title" name="' . $args[0] . '[title]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter your custom title for Featured Image Metabox.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end title_callback

	public function instruction_callback( $args ) {

		$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';

		$html = '<input type="text" id="instruction" name="' . $args[0] . '[instruction]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter the instructions for Featured Image, like image dimensions.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end instruction_callback

	public function set_text_callback( $args ) {

		$value  = isset( $args[1]['set_text'] ) ? $args[1]['set_text'] : '';

		$html = '<input type="text" id="set_text" name="' . $args[0] . '[set_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom text to replace the default "%s".', $this->plugin_slug ), __( 'Set featured image' ) ) . '</p>';

		echo $html;

	} // end set_text_callback

	public function remove_text_callback( $args ) {

		$value  = isset( $args[1]['remove_text'] ) ? $args[1]['remove_text'] : '';

		$html = '<input type="text" id="remove_text" name="' . $args[0] . '[remove_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom text to replace the default "%s".', $this->plugin_slug ), __( 'Remove featured image' ) ) . '</p>';

		echo $html;

	} // end remove_text_callback

	/**
	 * Validate inputs
	 *
	 * @return array Sanitized data
	 *
	 * @since 0.7.0
	 */
	public function validate_inputs( $inputs ) {

		$outputs = array();

		foreach( $inputs as $key => $value ) {
			$outputs[$key] = sanitize_text_field( $value );
		}

		return apply_filters( 'cfim_validate_inputs', $outputs, $inputs );

	} // end validate_inputs
}

Custom_Featured_Image_Metabox_Settings::get_instance();