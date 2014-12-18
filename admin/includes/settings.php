<?php
class add_post_type_instructions_settings {
	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 * @var      string
	 */
	protected $plugin_slug = null;
	/**
	 * Instance of this class.
	 *
	 * @since    1.0
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
		add_action( 'admin_print_styles', array( $this, 'is_settings_page' ) );
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
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
	 * enqueue styles
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.1
	 */
	public function is_settings_page(){

		wp_enqueue_style('apti-settings-style', plugins_url( '../css/apti-settings.css', __FILE__ ) );

	} // end is_settings_page

	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {
		$plugin = add_post_type_instructions::get_instance();
		$post_types = $plugin->supported_post_types();
		$defaults = array(
				// order defined by Parameters reference at http://codex.wordpress.org/Function_Reference/post_type_supports
				'instruction' => '',
				'editor' => '',
				'author' => '',
				'thumbnail' => '',
				'excerpt' => '',
				'trackbacks' => '',
				'custom-fields' => '',
				// 'comments' => '',
				// 'revisions' => '',
				'page-attributes' => '',
				'post-formats' => '',
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
				sprintf( __( 'Set Instructions for %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'instruction_check',
				__( 'Below Title Field:', $this->plugin_slug ),
				array( $this, 'instruction_check_callback' ),
				$section,
				$pt,
				$args
			);
			add_settings_field(
				'instruction',
				__( '', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$section,
				$pt,
				$args
			);

			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'editor_input',
					__( 'WYSIWYG Editor Content:', $this->plugin_slug ),
					array( $this, 'editor_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'editor',
					__( '', $this->plugin_slug ),
					array( $this, 'editor_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'author' )) {
				add_settings_field(
					'author_input',
					__( 'Author Metabox:', $this->plugin_slug ),
					array( $this, 'author_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'author',
					__( '', $this->plugin_slug ),
					array( $this, 'author_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail_input',
					__( 'Featured Image Metabox:', $this->plugin_slug ),
					array( $this, 'thumbnail_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'thumbnail',
					__( '', $this->plugin_slug ),
					array( $this, 'thumbnail_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'excerpt' )) {
				add_settings_field(
					'excerpt_input',
					__( '', $this->plugin_slug ),
					array( $this, 'excerpt_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'excerpt',
					__( 'Excerpt Metabox:', $this->plugin_slug ),
					array( $this, 'excerpt_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'trackbacks' )) {
				add_settings_field(
					'trackbacks_input',
					__( 'Trackbacks Metabox:', $this->plugin_slug ),
					array( $this, 'trackbacks_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'trackbacks',
					__( '', $this->plugin_slug ),
					array( $this, 'trackbacks_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'custom-fields' )) {
				add_settings_field(
					'customfields_input',
					__( 'Custom Fields Metabox:', $this->plugin_slug ),
					array( $this, 'customfields_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'customfields',
					__( '', $this->plugin_slug ),
					array( $this, 'customfields_callback' ),
					$section,
					$pt,
					$args
				);
			}

		/*	if ( post_type_supports( $pt, 'comments' )) {
				add_settings_field(
					'comments',
					__( '<br />Comments Metabox:', $this->plugin_slug ),
					array( $this, 'comments_callback' ),
					$section,
					$pt,
					$args
				);
			}	*/

			if ( post_type_supports( $pt, 'page-attributes' )) {
				add_settings_field(
					'pageattributes_input',
					__( 'Page Attributes Metabox:', $this->plugin_slug ),
					array( $this, 'pageattributes_input_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'pageattributes',
					__( '', $this->plugin_slug ),
					array( $this, 'pageattributes_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( !($pt == 'page') ) {

				if ( post_type_supports( $pt, 'post-formats' )) {
					add_settings_field(
						'postformats_input',
						__( 'Post Format Metabox:', $this->plugin_slug ),
						array( $this, 'postformats_input_callback' ),
						$section,
						$pt,
						$args
					);
					add_settings_field(
						'postformats',
						__( '', $this->plugin_slug ),
						array( $this, 'postformats_callback' ),
						$section,
						$pt,
						$args
					);
				}
/*
				if ( taxonomy_exists( 'category' )) {
					add_settings_field(
						'categories',
						__( '<br />Categories Metabox:', $this->plugin_slug ),
						array( $this, 'categories_callback' ),
						$section,
						$pt,
						$args
					);
				}

				if ( taxonomy_exists( 'post_tag' )) {
					add_settings_field(
						'tags',
						__( '<br />Tags Metabox:', $this->plugin_slug ),
						array( $this, 'tags_callback' ),
						$section,
						$pt,
						$args
					);
				}
*/
				
			}

			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init

	public function instruction_check_callback( $args ) {

		$output = $args[0].'[instruction_check]';
		$value  = isset( $args[1]['instruction_check'] ) ? $args[1]['instruction_check'] : '';

		$checkhtml = '<input type="checkbox" id="instruction_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="instruction_check"> check to enable</label>';

		echo $checkhtml;

	} // end instruction_check_callback

		public function instruction_callback( $args ) {

		    $output = $args[0].'[instruction]';
			$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';

			$textareahtml = '<textarea id="instruction" name="' .$output. '" rows="4" type="textarea">' .$value. '</textarea>';

			$html = '<p class="description">' . __( 'Enter content to display below the title field, such as special instructions for this post type. HTML allowed.', $this->plugin_slug ) . '</p><hr>';

			echo $textareahtml, $html;

		} // end instruction_callback

	public function editor_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="editor_check" name="editor_input" value="1" />';
		$checkhtml .= '<label for="editor_check"> check to enable</label>';

		echo $checkhtml;

	} // end editor_input_callback

		public function editor_callback( $args ) {
			
			$output = $args[0].'[editor]';
			$value  = isset( $args[1]['editor'] ) ? $args[1]['editor'] : '';
			// $id = 'textarea_one';
			/* $settings = array( 
				'textarea_name' => $output, 
				'textarea_rows' => '5'
			);	*/

			$html = '<textarea id="textarea_one" name="' .$output. '" rows="6" type="textarea">' .$value. '</textarea>';
			//wp_editor( $value, $id, $settings );
			$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing".  This will be displayed only when no other content has been entered. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end editor_callback

	public function author_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="author_check" name="author_input" value="1" />';
		$checkhtml .= '<label for="author_check"> check to enable</label>';

		echo $checkhtml;

	} // end author_input_callback

		public function author_callback( $args ) {
			
			$output = $args[0].'[author]';
			$value  = isset( $args[1]['author'] ) ? $args[1]['author'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the author metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end author_callback

	public function thumbnail_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="thumbnail_check" name="thumbnail_input" value="1" />';
		$checkhtml .= '<label for="thumbnail_check"> check to enable</label>';

		echo $checkhtml;

	} // end thumbnail_input_callback

		public function thumbnail_callback( $args ) {
			
			$output = $args[0].'[thumbnail]';
			$value  = isset( $args[1]['thumbnail'] ) ? $args[1]['thumbnail'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the featured image metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end thumbnail_callback

	public function excerpt_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="excerpt_check" name="excerpt_input" value="1" />';
		$checkhtml .= '<label for="excerpt_check"> check to enable</label>';

		echo $checkhtml;

	} // end excerpt_input_callback

		public function excerpt_callback( $args ) {
			
			$output = $args[0].'[excerpt]';
			$value  = isset( $args[1]['excerpt'] ) ? $args[1]['excerpt'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the excerpt metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end excerpt_callback

	public function trackbacks_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="trackbacks_check" name="trackbacks_input" value="1" />';
		$checkhtml .= '<label for="trackbacks_check"> check to enable</label>';

		echo $checkhtml;

	} // end trackbacks_input_callback

		public function trackbacks_callback( $args ) {
			
			$output = $args[0].'[trackbacks]';
			$value  = isset( $args[1]['trackbacks'] ) ? $args[1]['trackbacks'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the trackbacks metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end trackbacks_callback

	public function customfields_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="customfields_check" name="customfields_input" value="1" />';
		$checkhtml .= '<label for="customfields_check"> check to enable</label>';

		echo $checkhtml;

	} // end customfields_input_callback

		public function customfields_callback( $args ) {
			
			$output = $args[0].'[customfields]';
			$value  = isset( $args[1]['customfields'] ) ? $args[1]['customfields'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the custom fields metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end customfields_callback

/*	public function comments_callback( $args ) {
		
		$output = $args[0].'[comments]';
		$value  = isset( $args[1]['comments'] ) ? $args[1]['comments'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the comments metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
		echo $html;

	} // end comments_callback
*/

	public function pageattributes_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="pageattributes_check" name="pageattributes_input" value="1" />';
		$checkhtml .= '<label for="pageattributes_check"> check to enable</label>';

		echo $checkhtml;

	} // end pageattributes_input_callback

		public function pageattributes_callback( $args ) {
			
			$output = $args[0].'[pageattributes]';
			$value  = isset( $args[1]['pageattributes'] ) ? $args[1]['pageattributes'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the page attributes metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end pageattributes_callback

	public function postformats_input_callback( $args ) {

		$checkhtml = '<input type="checkbox" id="postformats_check" name="postformats_input" value="1" />';
		$checkhtml .= '<label for="postformats_check"> check to enable</label>';

		echo $checkhtml;

	} // end postformats_input_callback

		public function postformats_callback( $args ) {
			
			$output = $args[0].'[postformats]';
			$value  = isset( $args[1]['postformats'] ) ? $args[1]['postformats'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the post format metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end postformats_callback

/*	public function categories_callback( $args ) {
		
		$output = $args[0].'[categories]';
		$value  = isset( $args[1]['categories'] ) ? $args[1]['categories'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the post format metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
		echo $html;

	} // end categories_callback

	public function tags_callback( $args ) {
		
		$output = $args[0].'[tags]';
		$value  = isset( $args[1]['tags'] ) ? $args[1]['tags'] : '';

		$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
		$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the post format metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
		echo $html;

	} // end tags_callback
*/


}
add_post_type_instructions_settings::get_instance();
