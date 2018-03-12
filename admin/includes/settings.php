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
		add_action( 'contextual_help', array( $this, 'apti_help_tab' ) );
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
	 *
	 * Add help tab to plugin page
	 * @since     3.0
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function apti_help_tab() {
	    $plugin = add_post_type_instructions::get_instance();
	    $slug = $this->plugin_slug;
	    $screen = get_current_screen();
	    // Add my_help_tab if current screen is My Admin Page
	    if ( $screen->id === "settings_page_apti" ) {

		    $screen->add_help_tab( array(
		        'id'	=> 'apti_help_overview',
		        'title'	=> __( 'Overview', $slug ),
		        'content'	=> '<p>' . __( 'First thing.', $slug ) . '</p><p>' . __( 'Second.', $slug ) . '</p><p>' . __( 'Third thing.', $slug ) . '</p>',
		    ) );

		    $screen->add_help_tab( array(
		        'id'	=> 'apti_help_specific',
		        'title'	=> __( 'Demo Video', $slug ),
		        'content'	=> '<p>' . __( 'More Content.', $slug ) . '</p><iframe width="355" height="200" src="https://www.youtube.com/embed/5mZovjRlkWs" frameborder="0" allowfullscreen></iframe>',
		    ) );

		}
	}


	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {
		$plugin = add_post_type_instructions::get_instance();
		$slug = $this->plugin_slug;
		$post_types = $plugin->supported_post_types();

		foreach ( $post_types as $pt ) {
			$post_object = get_post_type_object( $pt );
			$section = $this->plugin_slug . '_' . $pt;
			$name = $post_object->labels->name;

			$args = array( $section, get_option( $section ) );

			// section
			add_settings_section(
				'check_' . $pt,
				' ',
				'',
				$section
			);

			// enable for post type
			add_settings_field(
				'enable_check',
				__( 'Enable for', $slug ) . ' ' . $name . ':',
				array( $this, 'enable_check_callback' ),
				$section,
				'check_' . $pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[enable_check]',
					'name' => 'enable_check'
				)
			);


			add_settings_section(
				$pt,
				__( 'General Instructional Content (appears at top of page)', $slug ) .':',
				'',
				$section
			);

			if ( post_type_supports( $pt, 'title' )) {
				add_settings_field(
					'top_check',
					__( 'Above Title Field', $slug ).':',
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
					__( '', $slug ),
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
				__( 'Above WYSIWYG Editor', $slug ).':',
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
				__( '', $slug ),
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
            
            
            if ( !($pt == 'page') ) {
                
                // section
                add_settings_section(
                    'taxes_' . $pt,
                    __( 'Instructional Content for Taxonomies (appears in metaboxes)', $slug ) .':',
                    '',
                    $section
                );
				
				if ( is_object_in_taxonomy( $pt, 'category' ) ) {
					add_settings_field(
						'categories_check',
						__( 'Categories', $slug ).':',
						array( $this, 'check_callback' ),
						$section,
						'taxes_' . $pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[categories_check]',
							'name' => 'categories_check'
						)
					);
					add_settings_field(
						'categories',
						__( '', $slug ),
						array( $this, 'textarea_callback' ),
						$section,
						'taxes_' . $pt,
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
						__( 'Tags', $slug).':',
						array( $this, 'check_callback' ),
						$section,
						'taxes_' . $pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[tags_check]',
							'name' => 'tags_check'
						)
					);
					add_settings_field(
						'tags',
						__( '', $slug ),
						array( $this, 'textarea_callback' ),
						$section,
						'taxes_' . $pt,
						array( 
							$section, 
							get_option( $section ),
							'field' => $section.'[tags]',
							'name' => 'tags',
							'parent' => 'tags_check'
						)
					);
				}
                
                
                /* CUSTOM TAXONOMIES
                * get all custom taxonomies in a post type
                * @since    3.0
	            */
                $argums = array(
                    'public'   => true,
                    '_builtin' => false
                ); 
                $outputs = 'names'; // or objects
                $operators = 'and'; // 'and' or 'or'
                $taxonomy_names = get_taxonomies( $argums, $outputs, $operators );
                foreach ( $taxonomy_names as $tn ) {

                    // get that taxonomy's objects (so we can output the label later for plural name)
                    $thingargums = array(
                      'name' => $tn
                    );
                    $thingoutputs = 'objects'; // or names
                    $things = get_taxonomies( $thingargums, $thingoutputs ); 

                    foreach ($things as $thing ) {

                        if ( is_object_in_taxonomy( $pt, $tn ) ) {
                            // categories are hierarchical
                            if ( is_taxonomy_hierarchical( $tn ) ) {
                                add_settings_field(
                                    'hierarchical_check_'.$tn.'_'.$x,
                                    $thing->label .' <span>(' . __('category', $slug ) .'):</span>',
                                    array( $this, 'check_callback' ),
                                    $section,
                                    'taxes_' . $pt,
                                    array( 
                                        $section, 
                                        get_option( $section ),
                                        'field' => $section.'[hierarchical_check_'.$tn.']',
									    'name' => 'hierarchical_check_'.$tn
                                    )
                                );
                                add_settings_field(
                                    'hierarchical_'.$tn,
                                    __( '', $slug ),
                                    array( $this, 'textarea_callback' ),
                                    $section,
                                    'taxes_' . $pt,
                                    array( 
                                        $section, 
                                        get_option( $section ),
                                        'field' => $section.'[hierarchical_'.$tn.']',
                                        'name' => 'hierarchical_'.$tn,
                                        'parent' => 'hierarchical_check_'.$tn
                                    )
                                );

                            }
                            

                            // tags are flat
                            else {
                                add_settings_field(
                                    'flat_check_'.$tn,
                                    $thing->label .' <span>(' . __('tag', $slug ) .'):</span>',
                                    array( $this, 'check_callback' ),
                                    $section,
                                    'taxes_' . $pt,
                                    array( 
                                        $section, 
                                        get_option( $section ),
                                        'field' => $section.'[flat_check_'.$tn.']',
                                        'name' => 'flat_check_'.$tn
                                    )
                                );
                                add_settings_field(
                                    'flat_'.$tn,
                                    __( '', $slug ),
                                    array( $this, 'textarea_callback' ),
                                    $section,
                                    'taxes_' . $pt,
                                    array( 
                                        $section, 
                                        get_option( $section ),
                                        'field' => $section.'[flat_'.$tn.']',
                                        'name' => 'flat_'.$tn,
                                        'parent' => 'flat_check_'.$tn
                                    )
                                ); 

                            }
                        }
                    }
                }
			}


			// section
			add_settings_section(
				'metabox_' . $pt,
				__( 'Specific Instructional Content (appears in metaboxes)', $slug ) .':',
				'',
				$section
			);

			add_settings_field(
				'publish_check',
				__( 'Publish', $slug ).':',
				array( $this, 'check_callback' ),
				$section,
				'metabox_' . $pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[publish_check]',
					'name' => 'publish_check'
				)
			);
			add_settings_field(
				'publish',
				__( '', $slug ),
				array( $this, 'textarea_callback' ),
				$section,
				'metabox_' . $pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[publish]',
					'name' => 'publish',
					'parent' => 'publish_check'
				)
			);

			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail_check',
					__( 'Featured Image', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[thumbnail_check]',
						'name' => 'thumbnail_check'
					)
				);
				add_settings_field(
					'thumbnail',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Excerpt', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[excerpt_check]',
						'name' => 'excerpt_check'
					)
				);
				add_settings_field(
					'excerpt',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[excerpt]',
						'name' => 'excerpt',
						'parent' => 'excerpt_check'
					)
				);
			}


			if ( post_type_supports( $pt, 'post-formats' )) {
                add_settings_field(
                    'postformats_check',
                    __( 'Post Format', $slug ).':',
                    array( $this, 'check_callback' ),
                    $section,
                    'metabox_' . $pt,
                    array( 
                        $section, 
                        get_option( $section ),
                        'field' => $section.'[postformats_check]',
                        'name' => 'postformats_check'
                    )
                );
                add_settings_field(
                    'postformats',
                    __( '', $slug ),
                    array( $this, 'textarea_callback' ),
                    $section,
                    'metabox_' . $pt,
                    array( 
                        $section, 
                        get_option( $section ),
                        'field' => $section.'[postformats]',
                        'name' => 'postformats',
                        'parent' => 'postformats_check'
                    )
                );
            }


			if ( post_type_supports( $pt, 'author' )) {
				add_settings_field(
					'author_check',
					__( 'Author', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[author_check]',
						'name' => 'author_check'
					)
				);
				add_settings_field(
					'author',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[author]',
						'name' => 'author',
						'parent' => 'author_check'
					)
				);
			}

			

			if ( post_type_supports( $pt, 'trackbacks' )) {
				add_settings_field(
					'trackbacks_check',
					__( 'Trackbacks', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
					$section, 
						get_option( $section ),
						'field' => $section.'[trackbacks_check]',
						'name' => 'trackbacks_check'
					)
				);
				add_settings_field(
					'trackbacks',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Custom Fields', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[customfields_check]',
						'name' => 'customfields_check'
					)
				);
				add_settings_field(
					'customfields',
					__( '', $slug),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Comments', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[comments_check]',
						'name' => 'comments_check'
					)
				);
				add_settings_field(
					'comments',
					__( '', $slug),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Discussion', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[discussion_check]',
						'name' => 'discussion_check'
					)
				);
				add_settings_field(
					'discussion',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Revisions', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[revisions_check]',
						'name' => 'revisions_check'
					)
				);
				add_settings_field(
					'revisions',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
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
					__( 'Page Attributes', $slug ).':',
					array( $this, 'check_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[pageattributes_check]',
						'name' => 'pageattributes_check'
					)
				);
				add_settings_field(
					'pageattributes',
					__( '', $slug ),
					array( $this, 'textarea_callback' ),
					$section,
					'metabox_' . $pt,
					array( 
						$section, 
						get_option( $section ),
						'field' => $section.'[pageattributes]',
						'name' => 'pageattributes',
						'parent' => 'pageattributes_check'
					)
				);
			}

			

			add_settings_field(
				'slug_check',
				__( 'Slug', $slug ).':',
				array( $this, 'check_callback' ),
				$section,
				'metabox_' . $pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[slug_check]',
					'name' => 'slug_check'
				)
			);
			add_settings_field(
				'slug',
				__( '', $slug ),
				array( $this, 'textarea_callback' ),
				$section,
				'metabox_' . $pt,
				array( 
					$section, 
					get_option( $section ),
					'field' => $section.'[slug]',
					'name' => 'slug',
					'parent' => 'slug_check'
				)
			);


			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init





// --------------------------------------------------------
// Default Content Requirements Section

// ENABLE FOR POST TYPE
	public function enable_check_callback( $args ) {
		$name = $args['name'];
		$field = $args['field'];
		$value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';

		$html = '<div class="switch">';
		$html .= '<input type="checkbox" id="' . $name . '" class="switch-checkbox" name="' . $field . '" value="1" onclick="check_pt_is_enabled()"' . checked( 1, $value, false ) . ' />';
		$html .= '<label for="' . $name . '" class="switch-label">';
		$html .= '<span class="switch-inner"></span><span class="switch-switch"></span>';
		$html .= '</label>';
		$html .= '</div>';
		echo $html;

		/**
		 * hide settings when not enabled
		 * uses $name and $parent values from array (set in API Settings)
		 * uses WP jQuery library
		 * @since 3.0
		 */
		?>
		<script>
			function check_pt_is_enabled() {
				if (document.getElementById('<?php echo $name; ?>').checked){
					jQuery('table.form-table').slideDown("slow");
                    jQuery('form h2').slideDown("slow");
				} else {
					jQuery('table.form-table').slideUp("fast");
                    jQuery('form h2').slideUp("fast");
				}
			}
			window.onload = check_pt_is_enabled;
		</script>
		<?php
	} // end 




	public function check_callback( $args ) {
		$name = $args['name'];
		$field = $args['field'];
		$value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';

		$html = '<input type="checkbox" id="' . $name . '" name="' . $field . '" value="1"' . checked( 1, $value, false ) . ' />';
		$html .= '<label for="' . $name . '"> ' . __( 'enable', 'aptrc' ) . '</label>';
		echo $html;

	} // end check_callback

    
    public function textarea_callback( $args ) {
        $name = $args['name'];
        $field = $args['field'];
        $value  = isset( $args[1][''.$name.''] ) ? $args[1][''.$name.''] : '';
        $parent = $args['parent'];

        $html = '<div class="' . $parent . '">';
        $html .= '<div id="' . $name . '" class="textarea"><textarea id="' . $name . '_input" name="' .$field. '" type="textarea">' .$value. '</textarea></div>';
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
    } // end textarea_callback



}
add_post_type_instructions_settings::get_instance();
