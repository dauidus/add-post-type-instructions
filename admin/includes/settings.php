<?php

class Default_Content_Editor_Value_Settings {

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

		$plugin = Default_Content_Editor_Value::get_instance();
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

		$plugin = Default_Content_Editor_Value::get_instance();
		$post_types = $plugin->supported_post_types();

		$defaults = array(
				'content' => '',
				'instruction' => '',
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
				sprintf( __( 'Set options for %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'content',
				__( 'Default Content:', $this->plugin_slug ),
				array( $this, 'content_callback' ),
				$section,
				$pt,
				$args
			);

			add_settings_field(
				'instruction',
				__( 'Text Above Editor', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
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

	public function content_callback( $args ) {

		$value = isset( $args[1]['content'] ) ? $args[1]['content'] : '';

		$output = $args[0].'[content]';

		$settings = array( 
			'textarea_name' => $output,
			'textarea_rows' => '5'
		);

		$html = wp_editor( $output, 'content', $settings );

		$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end content_callback

	public function instruction_callback( $args ) {

		$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';

		$html = wp_editor( $args[1].'[instruction]', 'instruction', $settings = array( 'textarea_rows'=>'5', 'textarea_name'=>$args[0].'[instruction]'));

		$html .= '<p class="description">' . __( 'Enter content to display above the editor.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end instruction_callback

	/**
	 * Validate inputs
	 *
	 * @return array Sanitized data
	 *
	 * @since 1.0
	 */
	public function validate_inputs( $inputs ) {

		$outputs = array();

		foreach( $inputs as $key => $value ) {
			$outputs[$key] = $value;
		}

		return apply_filters( 'dcev_validate_inputs', $outputs, $inputs );

	} // end validate_inputs
}

Default_Content_Editor_Value_Settings::get_instance();
