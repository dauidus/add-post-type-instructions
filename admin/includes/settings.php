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
				sprintf( __( 'Set options for %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'instruction',
				__( '<br /><br /><br /><br /><br />Set Instructional Content:', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$section,
				$pt,
				$args
			);

			if ( post_type_supports( $pt, 'editor' )) {

				add_settings_field(
					'content',
					__( '<br /><br /><br /><br /><br /><br />Set Default Content:', $this->plugin_slug ),
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

		$settings = array( 
			'textarea_name' => $output,
			'textarea_rows' => '5'
		);

		$html = '<textarea id="instruction" name="' .$output. '" rows="7" cols="150" type="textarea">' .$output. '</textarea>';

		$html = wp_editor( $output, 'instruction', $settings );

		$html .= '<p class="description">' . __( 'Enter content to display below the title field, such as special instructions for this post type.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end instruction_callback

	public function content_callback( $args ) {

		$output = $args[0].'[content]';

		$settings = array( 
			'textarea_name' => $output, 
			'textarea_rows' => '5'
		);

		$html = '<textarea id="content" name="' .$output. '" rows="7" cols="150" type="textarea">' .$output. '</textarea>';

		$html = wp_editor( $output, 'content', $settings );

		$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing".', $this->plugin_slug ) . '</p><br />';

		echo $html;

	} // end content_callback

	public function error_callback( $args ) {

		$section = $this->plugin_slug . '_' . $pt;

		$html = '<br><br>This post type does not include support for the \'editor\' feature.';

		echo $html;

	} // end error_callback


}

Default_Content_Editor_Value_Settings::get_instance();
