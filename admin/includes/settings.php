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
				'author' => '',
				'thumbnail' => '',
				'excerpt' => '',
				'trackbacks' => '',
				'custom-fields' => '',
				'comments' => '',
				'revisions' => '',
				'page-attributes' => '',
				'post-formats' => '',
				'categories' => '',
				'tags' => '',
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
				sprintf( __( 'Set instructional content for all %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			if ( post_type_supports( $pt, 'title' )) {
				add_settings_field(
					'top_check',
					__( 'Above Title Field:', $this->plugin_slug ),
					array( $this, 'top_check_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'top',
					__( '', $this->plugin_slug ),
					array( $this, 'top_callback' ),
					$section,
					$pt,
					$args
				);
			}

			add_settings_field(
				'instruction_check',
				__( 'Above WYSIWYG Editor:', $this->plugin_slug ),
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
					__( 'WYSIWYG Content:', $this->plugin_slug ),
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
				
				if ( is_object_in_taxonomy( $pt, 'category' ) ) {
					add_settings_field(
						'categories_check',
						__( '<br />Categories Metabox:', $this->plugin_slug ),
						array( $this, 'categories_check_callback' ),
						$section,
						$pt,
						$args
					);
					add_settings_field(
						'categories',
						__( '', $this->plugin_slug ),
						array( $this, 'categories_callback' ),
						$section,
						$pt,
						$args
					);
				}

				if ( is_object_in_taxonomy( $pt, 'post_tag' ) ) {
					add_settings_field(
						'tags_check',
						__( '<br />Tags Metabox:', $this->plugin_slug ),
						array( $this, 'tags_check_callback' ),
						$section,
						$pt,
						$args
					);
					add_settings_field(
						'tags',
						__( '', $this->plugin_slug ),
						array( $this, 'tags_callback' ),
						$section,
						$pt,
						$args
					);
				}

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

			}

			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init

	public function top_check_callback( $args ) {

		$output = $args[0].'[top_check]';
		$value  = isset( $args[1]['top_check'] ) ? $args[1]['top_check'] : '';

		$checkhtml = '<input type="checkbox" id="top_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="top_check"> check to enable</label>';
		echo $checkhtml;

	} // end top_check_callback

		public function top_callback( $args ) {

		    $output = $args[0].'[top]';
			$value  = isset( $args[1]['top'] ) ? $args[1]['top'] : '';

			$textareahtml = '<div id="top"><textarea id="top_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			echo $textareahtml;

			$html = '<p class="description" id="instdesc">' . __( 'Enter assistive text to be displayed above the title field, such as general instructions for this type of content.  This content will span the width of the page.  HTML allowed.', $this->plugin_slug ) . '</p><hr>';

			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	top = doc.getElementById('top'),
			        	topinput = doc.getElementById('top_input'),
			        	topnode = doc.createTextNode('');		    
			    	top.appendChild(topnode);			    
			    	function updateTop() {
			       		topnode.nodeValue = topinput.value + '\n\n';
			    	}			    
			    	topinput.onkeypress = topinput.onkeyup = topinput.onchange = updateTop;			    
			    	updateTop();
			  	})();
			</script>
		<?php 
		} // end top_callback

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

			$textareahtml = '<div id="instruction"><textarea id="instruction_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			echo $textareahtml;

			$html = '<p class="description" id="instdesc">' . __( 'Enter assistive text to be displayed below the title field, such as general instructions for the WYSIWYG editor.  HTML allowed.', $this->plugin_slug ) . '</p><hr>';

			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	instruction = doc.getElementById('instruction'),
			        	instructioninput = doc.getElementById('instruction_input'),
			        	instructionnode = doc.createTextNode('');		    
			    	instruction.appendChild(instructionnode);			    
			    	function updateInstruction() {
			       		instructionnode.nodeValue = instructioninput.value + '\n\n';
			    	}			    
			    	instructioninput.onkeypress = instructioninput.onkeyup = instructioninput.onchange = updateInstruction;
			    	updateInstruction();
			  	})();
			</script>
		<?php 
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
			$id = 'textarea_one';
			$settings = array( 
				'textarea_name' => $output, 
				'textarea_rows' => '10'
			);
			wp_editor( $value, $id, $settings );
			// $html .= '<p class="editordescription">* This will only display when content is created. It will </p>';
			$html = '<p class="description" id="editdesc">' . __( 'Enter default content to be displayed within the WYSIWYG editor, such as "delete this, then start writing".  This will only be included when page/post content is first created.  Once changed and content has been saved, this message will not be included again for that page/post.  HTML and shortcodes allowed.', $this->plugin_slug ) . '</p><hr>';

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

			$html = '<div id="author"><textarea id="author_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description" id="authdesc">' . __( 'Enter assistive text to be displayed within the author metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	author = doc.getElementById('author'),
			        	authorinput = doc.getElementById('author_input'),
			        	authornode = doc.createTextNode('');		    
			    	author.appendChild(authornode);			    
			    	function updateAuthor() {
			       		authornode.nodeValue = authorinput.value + '\n\n';
			    	}			    
			    	authorinput.onkeypress = authorinput.onkeyup = authorinput.onchange = updateAuthor;
			    	updateAuthor();
			  	})();
			</script>
		<?php 
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

			$html = '<div id="thumbnail"><textarea id="thumbnail_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the featured image metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	thumbnail = doc.getElementById('thumbnail'),
			        	thumbnailinput = doc.getElementById('thumbnail_input'),
			        	thumbnailnode = doc.createTextNode('');		    
			    	thumbnail.appendChild(thumbnailnode);			    
			    	function updateThumbnail() {
			       		thumbnailnode.nodeValue = thumbnailinput.value + '\n\n';
			    	}			    
			    	thumbnailinput.onkeypress = thumbnailinput.onkeyup = thumbnailinput.onchange = updateThumbnail;
			    	updateThumbnail();
			  	})();
			</script>
		<?php 
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

			$html = '<div id="excerpt"><textarea id="excerpt_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the excerpt metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	excerpt = doc.getElementById('excerpt'),
			        	excerptinput = doc.getElementById('excerpt_input'),
			        	excerptnode = doc.createTextNode('');		    
			    	excerpt.appendChild(excerptnode);			    
			    	function updateExcerpt() {
			       		excerptnode.nodeValue = excerptinput.value + '\n\n';
			    	}			    
			    	excerptinput.onkeypress = excerptinput.onkeyup = excerptinput.onchange = updateExcerpt;
			    	updateExcerpt();
			  	})();
			</script>
		<?php 

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

			$html = '<div id="trackbacks"><textarea id="trackbacks_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the trackbacks metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	trackbacks = doc.getElementById('trackbacks'),
			        	trackbacksinput = doc.getElementById('trackbacks_input'),
			        	trackbacksnode = doc.createTextNode('');		    
			    	trackbacks.appendChild(trackbacksnode);			    
			    	function updateTrackbacks() {
			       		trackbacksnode.nodeValue = trackbacksinput.value + '\n\n';
			    	}			    
			    	trackbacksinput.onkeypress = trackbacksinput.onkeyup = trackbacksinput.onchange = updateTrackbacks;
			    	updateTrackbacks();
			  	})();
			</script>
		<?php 

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

			$html = '<div id="customfields"><textarea id="customfields_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the custom fields metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	customfields = doc.getElementById('customfields'),
			        	customfieldsinput = doc.getElementById('customfields_input'),
			        	customfieldsnode = doc.createTextNode('');		    
			    	customfields.appendChild(customfieldsnode);			    
			    	function updateCustomfields() {
			       		customfieldsnode.nodeValue = customfieldsinput.value + '\n\n';
			    	}			    
			    	customfieldsinput.onkeypress = customfieldsinput.onkeyup = customfieldsinput.onchange = updateCustomfields;
			    	updateCustomfields();
			  	})();
			</script>
		<?php 

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

			$html = '<div id="comments"><textarea id="comments_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the comments metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	comments = doc.getElementById('comments'),
			        	commentsinput = doc.getElementById('comments_input'),
			        	commentsnode = doc.createTextNode('');		    
			    	comments.appendChild(commentsnode);			    
			    	function updateComments() {
			       		commentsnode.nodeValue = commentsinput.value + '\n\n';
			    	}			    
			    	commentsinput.onkeypress = commentsinput.onkeyup = commentsinput.onchange = updateComments;
			    	updateComments();
			  	})();
			</script>
		<?php 

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

			$html = '<div id="revisions"><textarea id="revisions_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the revisions metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	revisions = doc.getElementById('revisions'),
			        	revisionsinput = doc.getElementById('revisions_input'),
			        	revisionsnode = doc.createTextNode('');		    
			    	revisions.appendChild(revisionsnode);			    
			    	function updateRevisions() {
			       		revisionsnode.nodeValue = revisionsinput.value + '\n\n';
			    	}			    
			    	revisionsinput.onkeypress = revisionsinput.onkeyup = revisionsinput.onchange = updateRevisions;
			    	updateRevisions();
			  	})();
			</script>
		<?php 

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

			$html = '<div id="pageattributes"><textarea id="pageattributes_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the page attributes metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	pageattributes = doc.getElementById('pageattributes'),
			        	pageattributesinput = doc.getElementById('pageattributes_input'),
			        	pageattributesnode = doc.createTextNode('');		    
			    	pageattributes.appendChild(pageattributesnode);			    
			    	function updatePageattributes() {
			       		pageattributesnode.nodeValue = pageattributesinput.value + '\n\n';
			    	}			    
			    	pageattributesinput.onkeypress = pageattributesinput.onkeyup = pageattributesinput.onchange = updatePageattributes;
			    	updatePageattributes();
			  	})();
			</script>
		<?php 

		} // end pageattributes_callback

	public function categories_check_callback( $args ) {
		
		$output = $args[0].'[categories_check]';
		$value  = isset( $args[1]['categories_check'] ) ? $args[1]['categories_check'] : '';

		$checkhtml = '<input type="checkbox" id="categories_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="categories_check"> check to enable</label>';

		echo $checkhtml;

	} // end categories_check_callback

		public function categories_callback( $args ) {
			
			$output = $args[0].'[categories]';
			$value  = isset( $args[1]['categories'] ) ? $args[1]['categories'] : '';

			$html = '<div id="categories"><textarea id="categories_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the categories metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	categories = doc.getElementById('categories'),
			        	categoriesinput = doc.getElementById('categories_input'),
			        	categoriesnode = doc.createTextNode('');		    
			    	categories.appendChild(categoriesnode);			    
			    	function updateCategories() {
			       		categoriesnode.nodeValue = categoriesinput.value + '\n\n';
			    	}			    
			    	categoriesinput.onkeypress = categoriesinput.onkeyup = categoriesinput.onchange = updateCategories;
			    	updateCategories();
			  	})();
			</script>
		<?php 

		} // end categories_callback

	public function tags_check_callback( $args ) {
		
		$output = $args[0].'[tags_check]';
		$value  = isset( $args[1]['tags_check'] ) ? $args[1]['tags_check'] : '';

		$checkhtml = '<input type="checkbox" id="tags_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="tags_check"> check to enable</label>';

		echo $checkhtml;

	} // end tags_check_callback

		public function tags_callback( $args ) {
			
			$output = $args[0].'[tags]';
			$value  = isset( $args[1]['tags'] ) ? $args[1]['tags'] : '';

			$html = '<div id="tags"><textarea id="tags_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the tags metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	tags = doc.getElementById('tags'),
			        	tagsinput = doc.getElementById('tags_input'),
			        	tagsnode = doc.createTextNode('');		    
			    	tags.appendChild(tagsnode);			    
			    	function updateTags() {
			       		tagsnode.nodeValue = tagsinput.value + '\n\n';
			    	}			    
			    	tagsinput.onkeypress = tagsinput.onkeyup = tagsinput.onchange = updateTags;
			    	updateTags();
			  	})();
			</script>
		<?php 

		} // end tags_callback

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

			$html = '<div id="postformats"><textarea id="postformats_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			$html .= '<p class="description">' . __( 'Enter assistive text to be displayed within the post format metabox. HTML allowed.', $this->plugin_slug ) . '</p><hr>';
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	postformats = doc.getElementById('postformats'),
			        	postformatsinput = doc.getElementById('postformats_input'),
			        	postformatsnode = doc.createTextNode('');		    
			    	postformats.appendChild(postformatsnode);			    
			    	function updatePostformats() {
			       		postformatsnode.nodeValue = postformatsinput.value + '\n\n';
			    	}			    
			    	postformatsinput.onkeypress = postformatsinput.onkeyup = postformatsinput.onchange = updatePostformats;
			    	updatePostformats();
			  	})();
			</script>
		<?php 

		} // end postformats_callback

}
add_post_type_instructions_settings::get_instance();
