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

			add_settings_field(
				'publish_check',
				__( 'Publish Metabox:', $this->plugin_slug ),
				array( $this, 'publish_check_callback' ),
				$section,
				$pt,
				$args
			);
			add_settings_field(
				'publish',
				__( '', $this->plugin_slug ),
				array( $this, 'publish_callback' ),
				$section,
				$pt,
				$args
			);

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

				add_settings_field(
					'discussion_check',
					__( 'Discussion Metabox:', $this->plugin_slug ),
					array( $this, 'discussion_check_callback' ),
					$section,
					$pt,
					$args
				);
				add_settings_field(
					'discussion',
					__( '', $this->plugin_slug ),
					array( $this, 'discussion_callback' ),
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
						__( 'Categories Metabox:', $this->plugin_slug ),
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
						__( 'Tags Metabox:', $this->plugin_slug ),
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

				// custom taxonomies

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

			add_settings_field(
				'slug_check',
				__( 'Slug Metabox:', $this->plugin_slug ),
				array( $this, 'slug_check_callback' ),
				$section,
				$pt,
				$args
			);
			add_settings_field(
				'slug',
				__( '', $this->plugin_slug ),
				array( $this, 'slug_callback' ),
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

	public function top_check_callback( $args ) {

		$output = $args[0].'[top_check]';
		$value  = isset( $args[1]['top_check'] ) ? $args[1]['top_check'] : '';

		$checkhtml = '<input type="checkbox" id="top_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="top_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';
		echo $checkhtml; 

	} // end top_check_callback

		public function top_callback( $args ) {

		    $output = $args[0].'[top]';
			$value  = isset( $args[1]['top'] ) ? $args[1]['top'] : '';

			$html = '<div id="top"><textarea id="top_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="instruction_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';
		echo $checkhtml;

	} // end instruction_check_callback

		public function instruction_callback( $args ) {

		    $output = $args[0].'[instruction]';
			$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';

			$html = '<div id="instruction"><textarea id="instruction_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="editor_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

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

		} // end editor_callback

	public function publish_check_callback( $args ) {

		$output = $args[0].'[publish_check]';
		$value  = isset( $args[1]['publish_check'] ) ? $args[1]['publish_check'] : '';

		$checkhtml = '<input type="checkbox" id="publish_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="publish_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end publish_check_callback

		public function publish_callback( $args ) {
			
			$output = $args[0].'[publish]';
			$value  = isset( $args[1]['publish'] ) ? $args[1]['publish'] : '';

			$html = '<div id="publish"><textarea id="publish_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	publish = doc.getElementById('publish'),
			        	publishinput = doc.getElementById('publish_input'),
			        	publishnode = doc.createTextNode('');		    
			    	publish.appendChild(publishnode);			    
			    	function updatePublish() {
			       		publishnode.nodeValue = publishinput.value + '\n\n';
			    	}			    
			    	publishinput.onkeypress = publishinput.onkeyup = publishinput.onchange = updatePublish;
			    	updatePublish();
			  	})();
			</script>
		<?php 
		} // end publish_callback

	public function author_check_callback( $args ) {

		$output = $args[0].'[author_check]';
		$value  = isset( $args[1]['author_check'] ) ? $args[1]['author_check'] : '';

		$checkhtml = '<input type="checkbox" id="author_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="author_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end author_check_callback

		public function author_callback( $args ) {
			
			$output = $args[0].'[author]';
			$value  = isset( $args[1]['author'] ) ? $args[1]['author'] : '';

			$html = '<div id="author"><textarea id="author_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="thumbnail_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end thumbnail_check_callback

		public function thumbnail_callback( $args ) {
			
			$output = $args[0].'[thumbnail]';
			$value  = isset( $args[1]['thumbnail'] ) ? $args[1]['thumbnail'] : '';

			$html = '<div id="thumbnail"><textarea id="thumbnail_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="excerpt_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end excerpt_check_callback

		public function excerpt_callback( $args ) {
			
			$output = $args[0].'[excerpt]';
			$value  = isset( $args[1]['excerpt'] ) ? $args[1]['excerpt'] : '';

			$html = '<div id="excerpt"><textarea id="excerpt_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="trackbacks_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end trackbacks_check_callback

		public function trackbacks_callback( $args ) {
			
			$output = $args[0].'[trackbacks]';
			$value  = isset( $args[1]['trackbacks'] ) ? $args[1]['trackbacks'] : '';

			$html = '<div id="trackbacks"><textarea id="trackbacks_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="customfields_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end customfields_check_callback

		public function customfields_callback( $args ) {
			
			$output = $args[0].'[customfields]';
			$value  = isset( $args[1]['customfields'] ) ? $args[1]['customfields'] : '';

			$html = '<div id="customfields"><textarea id="customfields_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="comments_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end comments_check_callback

		public function comments_callback( $args ) {
			
			$output = $args[0].'[comments]';
			$value  = isset( $args[1]['comments'] ) ? $args[1]['comments'] : '';

			$html = '<div id="comments"><textarea id="comments_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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

	public function discussion_check_callback( $args ) {

		$output = $args[0].'[discussion_check]';
		$value  = isset( $args[1]['discussion_check'] ) ? $args[1]['discussion_check'] : '';

		$checkhtml = '<input type="checkbox" id="discussion_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="discussion_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end discussion_check_callback

		public function discussion_callback( $args ) {
			
			$output = $args[0].'[discussion]';
			$value  = isset( $args[1]['discussion'] ) ? $args[1]['discussion'] : '';

			$html = '<div id="discussion"><textarea id="discussion_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	discussion = doc.getElementById('discussion'),
			        	discussioninput = doc.getElementById('discussion_input'),
			        	discussionnode = doc.createTextNode('');		    
			    	discussion.appendChild(discussionnode);			    
			    	function updateDiscussion() {
			       		discussionnode.nodeValue = discussioninput.value + '\n\n';
			    	}			    
			    	discussioninput.onkeypress = discussioninput.onkeyup = discussioninput.onchange = updateDiscussion;
			    	updateDiscussion();
			  	})();
			</script>
		<?php 

		} // end discussion_callback	

	public function revisions_check_callback( $args ) {

		$output = $args[0].'[revisions_check]';
		$value  = isset( $args[1]['revisions_check'] ) ? $args[1]['revisions_check'] : '';

		$checkhtml = '<input type="checkbox" id="revisions_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="revisions_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end revisions_check_callback

		public function revisions_callback( $args ) {
			
			$output = $args[0].'[revisions]';
			$value  = isset( $args[1]['revisions'] ) ? $args[1]['revisions'] : '';

			$html = '<div id="revisions"><textarea id="revisions_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="pageattributes_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end pageattributes_check_callback

		public function pageattributes_callback( $args ) {
			
			$output = $args[0].'[pageattributes]';
			$value  = isset( $args[1]['pageattributes'] ) ? $args[1]['pageattributes'] : '';

			$html = '<div id="pageattributes"><textarea id="pageattributes_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="categories_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end categories_check_callback

		public function categories_callback( $args ) {
			
			$output = $args[0].'[categories]';
			$value  = isset( $args[1]['categories'] ) ? $args[1]['categories'] : '';

			$html = '<div id="categories"><textarea id="categories_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="tags_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end tags_check_callback

		public function tags_callback( $args ) {
			
			$output = $args[0].'[tags]';
			$value  = isset( $args[1]['tags'] ) ? $args[1]['tags'] : '';

			$html = '<div id="tags"><textarea id="tags_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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
		$checkhtml .= '<label for="postformats_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end postformats_check_callback

		public function postformats_callback( $args ) {
			
			$output = $args[0].'[postformats]';
			$value  = isset( $args[1]['postformats'] ) ? $args[1]['postformats'] : '';

			$html = '<div id="postformats"><textarea id="postformats_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';

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

	public function slug_check_callback( $args ) {

		$output = $args[0].'[slug_check]';
		$value  = isset( $args[1]['slug_check'] ) ? $args[1]['slug_check'] : '';

		$checkhtml = '<input type="checkbox" id="slug_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="slug_check"> ' . __( 'check to enable', $this->plugin_slug ) . '</label>';

		echo $checkhtml;

	} // end slug_check_callback

		public function slug_callback( $args ) {
			
			$output = $args[0].'[slug]';
			$value  = isset( $args[1]['slug'] ) ? $args[1]['slug'] : '';

			$html = '<div id="slug"><textarea id="slug_input" name="' .$output. '" type="textarea">' .$value. '</textarea></div>';
			
			echo $html; ?>
			<script>
				(function() {
			    	var doc = document,
			        	slug = doc.getElementById('slug'),
			        	sluginput = doc.getElementById('slug_input'),
			        	slugnode = doc.createTextNode('');		    
			    	slug.appendChild(slugnode);			    
			    	function updateSlug() {
			       		slugnode.nodeValue = sluginput.value + '\n\n';
			    	}			    
			    	sluginput.onkeypress = sluginput.onkeyup = sluginput.onchange = updateSlug;
			    	updateSlug();
			  	})();
			</script>
		<?php 

		} // end slug_callback

}
add_post_type_instructions_settings::get_instance();
