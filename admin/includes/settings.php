<?php
class add_post_type_instructions_Settings {
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
		$plugin = add_post_type_instructions::get_instance();
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
		$plugin = add_post_type_instructions::get_instance();
		$post_types = $plugin->supported_post_types();
		$defaults = array(
				'instruction' => '',
				'content' => '',
				'thumbnail' => '',
				'postformats' => '',
				'pageattributes' => '',
				'author' => ''
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
				sprintf( __( 'Set instructions for %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'instruction',
				__( '<br />Display Below Title Field:', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$section,
				$pt,
				$args
			);

			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'content',
					__( '<br />Set WYSIWYG Content:', $this->plugin_slug ),
					array( $this, 'content_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail',
					__( '<br />Featured Image Metabox:', $this->plugin_slug ),
					array( $this, 'thumbnail_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( !($pt == 'page') ) {
				if ( post_type_supports( $pt, 'post-formats' )) {
					add_settings_field(
						'postformats',
						__( '<br />Post Format Metabox:', $this->plugin_slug ),
						array( $this, 'postformats_callback' ),
						$section,
						$pt,
						$args
					);
				}
			}

			if ( post_type_supports( $pt, 'page-attributes' )) {
				add_settings_field(
					'pageattributes',
					__( '<br />Page Attributes Metabox:', $this->plugin_slug ),
					array( $this, 'pageattributes_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'author' )) {
				add_settings_field(
					'author',
					__( '<br />Author Metabox:', $this->plugin_slug ),
					array( $this, 'author_callback' ),
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
		echo $html;

	} // end instruction_callback

	public function content_callback( $args ) {
		
		$output = $args[0].'[content]';
		$value  = isset( $args[1]['content'] ) ? $args[1]['content'] : '';
		$id = 'textarea_one';
		$settings = array( 
			'textarea_name' => $output, 
			'textarea_rows' => '5'
		);	

		$html = '<textarea id="textarea_one" name="' .$output. '" rows="6" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		//wp_editor( $value, $id, $settings );

		$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing".  This will be displayed only when no other content has been entered. HTML and shortcodes allowed.', $this->plugin_slug ) . '</p>';
		echo $html;

	} // end content_callback

	public function thumbnail_callback( $args ) {
		
		$output = $args[0].'[thumbnail]';
		$value  = isset( $args[1]['thumbnail'] ) ? $args[1]['thumbnail'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the featured image metabox. HTML allowed.', $this->plugin_slug ) . '</p>';
		echo $html;

	} // end thumbnail_callback

	public function postformats_callback( $args ) {
		
		$output = $args[0].'[postformats]';
		$value  = isset( $args[1]['postformats'] ) ? $args[1]['postformats'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the post format metabox. HTML allowed.', $this->plugin_slug ) . '</p>';
		echo $html;

	} // end thumbnail_callback

	public function pageattributes_callback( $args ) {
		
		$output = $args[0].'[pageattributes]';
		$value  = isset( $args[1]['pageattributes'] ) ? $args[1]['pageattributes'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the page attributes metabox. HTML allowed.', $this->plugin_slug ) . '</p>';
		echo $html;

	} // end thumbnail_callback

	public function author_callback( $args ) {
		
		$output = $args[0].'[author]';
		$value  = isset( $args[1]['author'] ) ? $args[1]['author'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" style="width: 90%; padding: 10px;" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the authormetabox. HTML allowed.', $this->plugin_slug ) . '</p>';
		echo $html;

	} // end author_callback
}
add_post_type_instructions_Settings::get_instance();
