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
				'top' => '',
				'instruction' => '',
				'editor' => '',
				'publish' => '',
				'author' => '',
				'thumbnail' => '',
				'excerpt' => '',
				'trackbacks' => '',
				'custom-fields' => '',
				'comments' => '',
				'discussion' => '',
				'revisions' => '',
				'page-attributes' => '',
				'categories' => '',
				'tags' => '',
				'post-formats' => '',
				'slug' => '',
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
				sprintf( __( 'Set instructional content for all %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			if ( post_type_supports( $pt, 'title' )) {
				add_settings_field(
					'top_check',
					__( 'Above Title Field:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[top_check]',
						'name' => 'top_check'
					)
				);

				add_settings_field(
					'top',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[top]',
						'name' => 'top',
						'parent' => 'top_check'
					)
				);
			}

			add_settings_field(
				'instruction_check',
				__( 'Above WYSIWYG Editor:', $this->plugin_slug ),
				array( $this, 'check_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[instruction_check]',
					'name' => 'instruction_check'
				)
			);
			add_settings_field(
				'instruction',
				__( '', $this->plugin_slug ),
				array( $this, 'textarea_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[instruction]',
					'name' => 'instruction',
					'parent' => 'instruction_check'
				)
			);

			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'editor_check',
					__( 'WYSIWYG Content:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[editor_check]',
						'name' => 'editor_check'
					)
				);
				add_settings_field(
					'editor',
					__( '', $this->plugin_slug ),
					array( $this, 'wysiwyg_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[editor]',
						'name' => 'editor',
						'parent' => 'editor_check'
					)
				);
			}

			add_settings_field(
				'publish_check',
				__( 'Publish Metabox:', $this->plugin_slug ),
				array( $this, 'check_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[publish_check]',
					'name' => 'publish_check'
				)
			);
			add_settings_field(
				'publish',
				__( '', $this->plugin_slug ),
				array( $this, 'textarea_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[publish]',
					'name' => 'publish',
					'parent' => 'publish_check'
				)
			);

			if ( post_type_supports( $pt, 'author' )) {
				add_settings_field(
					'author_check',
					__( 'Author Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[author_check]',
						'name' => 'author_check'
					)
				);
				add_settings_field(
					'author',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[author]',
						'name' => 'author',
						'parent' => 'author_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail_check',
					__( 'Featured Image Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[thumbnail_check]',
						'name' => 'thumbnail_check'
					)
				);
				add_settings_field(
					'thumbnail',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[thumbnail]',
						'name' => 'thumbnail',
						'parent' => 'thumbnail_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'excerpt' )) {
				add_settings_field(
					'excerpt_check',
					__( 'Excerpt Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[excerpt_check]',
						'name' => 'excerpt_check'
					)
				);
				add_settings_field(
					'excerpt',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[excerpt]',
						'name' => 'excerpt',
						'parent' => 'excerpt_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'trackbacks' )) {
				add_settings_field(
					'trackbacks_check',
					__( 'Trackbacks Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
					$section, 
						get_option( $section ),
						'field' => $section.'[trackbacks_check]',
						'name' => 'trackbacks_check'
					)
				);
				add_settings_field(
					'trackbacks',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[trackbacks]',
						'name' => 'trackbacks',
						'parent' => 'trackbacks_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'custom-fields' )) {
				add_settings_field(
					'customfields_check',
					__( 'Custom Fields Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[customfields_check]',
						'name' => 'customfields_check'
					)
				);
				add_settings_field(
					'customfields',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[customfields]',
						'name' => 'customfields',
						'parent' => 'customfields_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'comments' )) {
				add_settings_field(
					'comments_check',
					__( 'Comments Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[comments_check]',
						'name' => 'comments_check'
					)
				);
				add_settings_field(
					'comments',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[comments]',
						'name' => 'comments',
						'parent' => 'comments_check'
					)
				);

				add_settings_field(
					'discussion_check',
					__( 'Discussion Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[discussion_check]',
						'name' => 'discussion_check'
					)
				);
				add_settings_field(
					'discussion',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[discussion]',
						'name' => 'discussion',
						'parent' => 'discussion_check'
					)
				);
			}	

			if ( post_type_supports( $pt, 'revisions' )) {
				add_settings_field(
					'revisions_check',
					__( 'Revisions Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[revisions_check]',
						'name' => 'revisions_check'
					)
				);
				add_settings_field(
					'revisions',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[revisions]',
						'name' => 'revisions',
						'parent' => 'revisions_check'
					)
				);
			}

			if ( post_type_supports( $pt, 'page-attributes' )) {
				add_settings_field(
					'pageattributes_check',
					__( 'Page Attributes Metabox:', $this->plugin_slug ),
					array( $this, 'check_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[pageattributes_check]',
						'name' => 'pageattributes_check'
					)
				);
				add_settings_field(
					'pageattributes',
					__( '', $this->plugin_slug ),
					array( $this, 'textarea_callback' ),
					$section,
					$pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[pageattributes]',
						'name' => 'pageattributes',
						'parent' => 'pageattributes_check'
					)
				);
			}

			if ( !($pt == 'page') ) {
				
				if ( is_object_in_taxonomy( $pt, 'category' ) ) {
					add_settings_field(
						'categories_check',
						__( 'Categories Metabox:', $this->plugin_slug ),
						array( $this, 'check_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[categories_check]',
							'name' => 'categories_check'
						)
					);
					add_settings_field(
						'categories',
						__( '', $this->plugin_slug ),
						array( $this, 'textarea_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[categories]',
							'name' => 'categories',
							'parent' => 'categories_check'
						)
					);
				}

				if ( is_object_in_taxonomy( $pt, 'post_tag' ) ) {
					add_settings_field(
						'tags_check',
						__( 'Tags Metabox:', $this->plugin_slug ),
						array( $this, 'check_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[tags_check]',
							'name' => 'tags_check'
						)
					);
					add_settings_field(
						'tags',
						__( '', $this->plugin_slug ),
						array( $this, 'textarea_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[tags]',
							'name' => 'tags',
							'parent' => 'tags_check'
						)
					);
				}

				// custom taxonomies

				if ( post_type_supports( $pt, 'post-formats' )) {
					add_settings_field(
						'postformats_check',
						__( 'Post Format Metabox:', $this->plugin_slug ),
						array( $this, 'check_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[postformats_check]',
							'name' => 'postformats_check'
						)
					);
					add_settings_field(
						'postformats',
						__( '', $this->plugin_slug ),
						array( $this, 'textarea_callback' ),
						$section,
						$pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[postformats]',
							'name' => 'postformats',
							'parent' => 'postformats_check'
						)
					);
				}

			}

			add_settings_field(
				'slug_check',
				__( 'Slug Metabox:', $this->plugin_slug ),
				array( $this, 'check_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[slug_check]',
					'name' => 'slug_check'
				)
			);
			add_settings_field(
				'slug',
				__( '', $this->plugin_slug ),
				array( $this, 'textarea_callback' ),
				$section,
				$pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[slug]',
					'name' => 'slug',
					'parent' => 'slug_check'
				)
			);

			add_settings_field(
				'filler',
				__( '', $this->plugin_slug ),
				array( $this, 'filler' ),
				$section,
				$pt,
				$args
			);

			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init

	public function check_callback( $args ) {
		$name = $args['name'];
		$field = $args['field'];
		$value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';

		$html = '<input type="checkbox" id="' . $name . '" name="' . $field . '" value="1"' . checked( 1, $value, false ) . ' />';
		$html .= '<label for="' . $name . '"> ' . __( 'enable', 'aptrc' ) . '</label>';
		echo $html;

	} // end top_check_callback

		public function textarea_callback( $args ) {
			$name = $args['name'];
			$field = $args['field'];
			$value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';
			$parent = $args['parent'];

			$html = '<div class="' . $parent . '">';
			$html .= '<div id="' . $name . '"><textarea id="' . $name . '_input" name="' .$field. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<div>';
			echo $html; ?>

			<script>
				(function() {
			    	var doc = document,
			        	txt = doc.getElementById('<?php echo $name; ?>'),
			        	txtinput = doc.getElementById('<?php echo $name; ?>_input'),
			        	txtnode = doc.createTextNode('');		    
			    	txt.appendChild(txtnode);			    
			    	function updateIt() {
			       		txtnode.nodeValue = txtinput.value + '\n\n';
			    	}			    
			    	txtinput.onkeypress = txtinput.onkeyup = txtinput.onchange = updateIt;			    
			    	updateIt();
			  	})();

			  	function check_is_enabled() {
					if (document.getElementById('<?php echo $parent; ?>').checked){
						jQuery('.<?php echo $parent; ?>').slideDown("fast");
					} else {
						jQuery('.<?php echo $parent; ?>').slideUp("fast");
					}
				}
				check_is_enabled();
				jQuery( "#<?php echo $parent; ?>" ).on('change', check_is_enabled );
			</script>
		<?php 
		} // end top_callback






		public function wysiwyg_callback( $args ) {
			$name = $args['name'];
			$field = $args['field'];
			$value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';
			$parent = $args['parent'];

			$id = $name;
			$settings = array( 
				'textarea_name' => $field, 
				'textarea_rows' => '10'
			);
			echo '<div class="' . $parent . '">';
			wp_editor( $value, $id, $settings );
			echo '<div>';
			?>

			<script>
			  	function check_is_enabled() {
					if (document.getElementById('<?php echo $parent; ?>').checked){
						jQuery('.<?php echo $parent; ?>').slideDown("fast");
					} else {
						jQuery('.<?php echo $parent; ?>').slideUp("fast");
					}
				}
				check_is_enabled();
				jQuery( "#<?php echo $parent; ?>" ).on('change', check_is_enabled );
			</script>

			<?php
		} // end editor_callback


		public function filler( $args ) {

			echo '<div class="filler">';
			echo '<div>';

		} // end filler



}
add_post_type_instructions_settings::get_instance();
