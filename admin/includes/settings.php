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
				'comments' => '',
				'revisions' => '',
				'page-attributes' => '',
				'post-formats' => '',
				// 'categories' => '',
				// 'tags' => '',
				// 'discussion' => '',
				// 'slug' => '',
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
					'editor_check',
					__( 'WYSIWYG Editor Content:', $this->plugin_slug ),
					array( $this, 'editor_check_callback' ),
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
					'author_check',
					__( 'Author Metabox:', $this->plugin_slug ),
					array( $this, 'author_check_callback' ),
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
					'thumbnail_check',
					__( 'Featured Image Metabox:', $this->plugin_slug ),
					array( $this, 'thumbnail_check_callback' ),
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
					'excerpt_check',
					__( 'Excerpt Metabox:', $this->plugin_slug ),
					array( $this, 'excerpt_check_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'excerpt',
					__( '', $this->plugin_slug ),
					array( $this, 'excerpt_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'trackbacks' )) {
				add_settings_field(
					'trackbacks_check',
					__( 'Trackbacks Metabox:', $this->plugin_slug ),
					array( $this, 'trackbacks_check_callback' ),
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
					'customfields_check',
					__( 'Custom Fields Metabox:', $this->plugin_slug ),
					array( $this, 'customfields_check_callback' ),
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

			if ( post_type_supports( $pt, 'comments' )) {
				add_settings_field(
					'comments_check',
					__( 'Comments Metabox:', $this->plugin_slug ),
					array( $this, 'comments_check_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'comments',
					__( '', $this->plugin_slug ),
					array( $this, 'comments_callback' ),
					$section,
					$pt,
					$args
				);
			}	

			if ( post_type_supports( $pt, 'revisions' )) {
				add_settings_field(
					'revisions_check',
					__( 'Revisions Metabox:', $this->plugin_slug ),
					array( $this, 'revisions_check_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'revisions',
					__( '', $this->plugin_slug ),
					array( $this, 'revisions_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'page-attributes' )) {
				add_settings_field(
					'pageattributes_check',
					__( 'Page Attributes Metabox:', $this->plugin_slug ),
					array( $this, 'pageattributes_check_callback' ),
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
						'postformats_check',
						__( 'Post Format Metabox:', $this->plugin_slug ),
						array( $this, 'postformats_check_callback' ),
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
			echo $textareahtml;

			$html = '<p class="description">' . __( 'Enter content to display below the title field, such as special instructions for this type of content. HTML allowed.', $this->plugin_slug ) . '</p><hr>';

			echo $html;

		} // end instruction_callback

	public function editor_check_callback( $args ) {

		$output = $args[0].'[editor_check]';
		$value  = isset( $args[1]['editor_check'] ) ? $args[1]['editor_check'] : '';

		$checkhtml = '<input type="checkbox" id="editor_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="editor_check"> check to enable</label>';

		echo $checkhtml;

	} // end editor_check_callback

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
			// $html .= '<p class="editordescription">* This will only display when content is created. It will </p>';
			$html .= '<p class="description">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing".  This will only be included when page/post content is first created.  Once changed and content has been saved, this message will not be included again for that page/post.  HTML allowed.', $this->plugin_slug ) . '</p><hr>';

			echo $html;

		} // end editor_callback

	public function author_check_callback( $args ) {

		$output = $args[0].'[author_check]';
		$value  = isset( $args[1]['author_check'] ) ? $args[1]['author_check'] : '';

		$checkhtml = '<input type="checkbox" id="author_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="author_check"> check to enable</label>';

		echo $checkhtml;

	} // end author_check_callback

		public function author_callback( $args ) {
			
			$output = $args[0].'[author]';
			$value  = isset( $args[1]['author'] ) ? $args[1]['author'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the author metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end author_callback

	public function thumbnail_check_callback( $args ) {

		$output = $args[0].'[thumbnail_check]';
		$value  = isset( $args[1]['thumbnail_check'] ) ? $args[1]['thumbnail_check'] : '';

		$checkhtml = '<input type="checkbox" id="thumbnail_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="thumbnail_check"> check to enable</label>';

		echo $checkhtml;

	} // end thumbnail_check_callback

		public function thumbnail_callback( $args ) {
			
			$output = $args[0].'[thumbnail]';
			$value  = isset( $args[1]['thumbnail'] ) ? $args[1]['thumbnail'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the featured image metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end thumbnail_callback

	public function excerpt_check_callback( $args ) {

		$output = $args[0].'[excerpt_check]';
		$value  = isset( $args[1]['excerpt_check'] ) ? $args[1]['excerpt_check'] : '';

		$checkhtml = '<input type="checkbox" id="excerpt_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="excerpt_check"> check to enable</label>';

		echo $checkhtml;

	} // end excerpt_check_callback

		public function excerpt_callback( $args ) {
			
			$output = $args[0].'[excerpt]';
			$value  = isset( $args[1]['excerpt'] ) ? $args[1]['excerpt'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the excerpt metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end excerpt_callback

	public function trackbacks_check_callback( $args ) {

		$output = $args[0].'[trackbacks_check]';
		$value  = isset( $args[1]['trackbacks_check'] ) ? $args[1]['trackbacks_check'] : '';

		$checkhtml = '<input type="checkbox" id="trackbacks_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="trackbacks_check"> check to enable</label>';

		echo $checkhtml;

	} // end trackbacks_check_callback

		public function trackbacks_callback( $args ) {
			
			$output = $args[0].'[trackbacks]';
			$value  = isset( $args[1]['trackbacks'] ) ? $args[1]['trackbacks'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the trackbacks metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end trackbacks_callback

	public function customfields_check_callback( $args ) {

		$output = $args[0].'[customfields_check]';
		$value  = isset( $args[1]['customfields_check'] ) ? $args[1]['customfields_check'] : '';

		$checkhtml = '<input type="checkbox" id="customfields_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="customfields_check"> check to enable</label>';

		echo $checkhtml;

	} // end customfields_check_callback

		public function customfields_callback( $args ) {
			
			$output = $args[0].'[customfields]';
			$value  = isset( $args[1]['customfields'] ) ? $args[1]['customfields'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the custom fields metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end customfields_callback

	public function comments_check_callback( $args ) {

		$output = $args[0].'[comments_check]';
		$value  = isset( $args[1]['comments_check'] ) ? $args[1]['comments_check'] : '';

		$checkhtml = '<input type="checkbox" id="comments_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="comments_check"> check to enable</label>';

		echo $checkhtml;

	} // end comments_check_callback

		public function comments_callback( $args ) {
			
			$output = $args[0].'[comments]';
			$value  = isset( $args[1]['comments'] ) ? $args[1]['comments'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the comments metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end comments_callback

	public function revisions_check_callback( $args ) {

		$output = $args[0].'[revisions_check]';
		$value  = isset( $args[1]['revisions_check'] ) ? $args[1]['revisions_check'] : '';

		$checkhtml = '<input type="checkbox" id="revisions_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="revisions_check"> check to enable</label>';

		echo $checkhtml;

	} // end revisions_check_callback

		public function revisions_callback( $args ) {
			
			$output = $args[0].'[revisions]';
			$value  = isset( $args[1]['revisions'] ) ? $args[1]['revisions'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the revisions metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end revisions_callback

	public function pageattributes_check_callback( $args ) {

		$output = $args[0].'[pageattributes_check]';
		$value  = isset( $args[1]['pageattributes_check'] ) ? $args[1]['pageattributes_check'] : '';

		$checkhtml = '<input type="checkbox" id="pageattributes_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="pageattributes_check"> check to enable</label>';

		echo $checkhtml;

	} // end pageattributes_check_callback

		public function pageattributes_callback( $args ) {
			
			$output = $args[0].'[pageattributes]';
			$value  = isset( $args[1]['pageattributes'] ) ? $args[1]['pageattributes'] : '';

			$html = '<textarea id="' .$output. '" name="' .$output. '" rows="2" type="textarea">' .$value. '</textarea>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the page attributes metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html;

		} // end pageattributes_callback

	public function postformats_check_callback( $args ) {

		$output = $args[0].'[postformats_check]';
		$value  = isset( $args[1]['postformats_check'] ) ? $args[1]['postformats_check'] : '';

		$checkhtml = '<input type="checkbox" id="postformats_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="postformats_check"> check to enable</label>';

		echo $checkhtml;

	} // end postformats_check_callback

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
