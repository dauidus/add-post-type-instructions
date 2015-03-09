<?php
/**
 * Add Post Type Instructions.
 *
 * For A Better UX
 *
 * @package   Add_Post_Type_Instructions
 * @author    Dave Winter (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-custom-featured-image-metabox.php`
 *
 * @package Add_Post_Type_Instructions_Admin
 */
class Add_Post_Type_Instructions_Admin {

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
	 * Slug of the plugin screen.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = add_post_type_instructions::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page
		require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

		// Add the menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'add-post-type-instructions.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Fire functions
			add_action( 'admin_print_styles', array( $this, 'is_edit_page' ) );
			add_action( 'edit_form_top', array( $this, 'add_content_above_title' ) );
			add_action( 'edit_form_after_title', array( $this, 'add_content_above_editor' ) );
			add_filter( 'default_content', array( $this, 'change_editor_content' ) );
			add_action( 'admin_head', array( $this, 'change_publish_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_author_metabox_content' ) );
			add_filter( 'admin_post_thumbnail_html', array( $this, 'change_thumbnail_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_excerpt_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_trackbacks_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_customfields_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_comments_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_discussion_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_revisions_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_pageattributes_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_categories_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_tags_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_postformats_metabox_content' ) );
			add_action( 'admin_head', array( $this, 'change_slug_metabox_content' ) );

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
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Post Type Instructions', $this->plugin_slug ),
			__( 'Post Type Instructions', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings' ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Get post type
	 *
	 * @since 1.0
	 */
	public function get_post_type() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_REQUEST['post_id'] ) ) {
				$post = get_post( $_REQUEST['post_id'] );
				return $post->post_type;
			}
		}

		$screen = get_current_screen();

		return $screen->post_type;

	} // end get_post_type

	/**
	 * enqueue styles
	 *
	 * @since 1.0.2
	 */
	public function is_edit_page($new_edit = null){

	    global $current_screen;  // Makes the $current_screen object available           
		if ($current_screen && ($current_screen->base == "edit" || $current_screen->base == "post")) {

			wp_enqueue_style('apti-style', plugins_url( '/css/apti.css', __FILE__ ) );

		}

	} // end is_edit_page

	/**
	 * Add content above title
	 *
	 * @since 2.1
	 */
	public function add_content_above_title() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['top_check'] ) && ! empty( $options['top_check'] ) ) {
			if ( isset( $options['top'] ) && ! empty( $options['top'] ) ) { 
				$top = $options['top'];
				echo '<div id="apti-above-title">' . $top . '</div>';
			}
		}

	} // end add_content_above_title

	/**
	 * Add instruction text above the content editor
	 *
	 * @since 1.0
	 */
	public function add_content_above_editor() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['instruction_check'] ) && ! empty( $options['instruction_check'] ) ) {
			if ( isset( $options['instruction'] ) && ! empty( $options['instruction'] ) ) {
				$instruction = $options['instruction'];
				echo '<br /><div id="apti-below-title">' . $instruction . '</div>';
			}
		}

	} // end add_content_above

	/**
	 * Set the default value fot the content editor
	 *
	 * @since 1.0
	 */
	public function change_editor_content($the_content) {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['editor_check'] ) && ! empty( $options['editor_check'] ) ) {
			if ( isset( $options['editor'] ) && ! empty( $options['editor'] ) ) {
				//add our content
				$template = $options['editor'];
				if ( ! empty($the_content) ) {
	        		return $the_content;
	    		} else {
	        		$the_content = $template;
	        		return $the_content;
	        	}
			}
		}

	} // end change_editor_content

	/**
	 * Change publish metabox content
	 *
	 * @since 2.1
	 */
	public function change_publish_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['publish_check'] ) && ! empty( $options['publish_check'] ) ) {
			if ( isset( $options['publish'] ) && ! empty( $options['publish'] ) ) { 
				$publish = '<p class="apti-text apti-publish">' . $options['publish'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $publish; ?>';

					    $('' + text_to_insert + '').insertBefore('#submitdiv .inside #minor-publishing-actions')
					});
				</script>
			<?php 
			}
		}

	} // end change_publish_metabox_content

	/**
	 * Change author metabox content
	 *
	 * @since 1.0.1
	 */
	public function change_author_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['author_check'] ) && ! empty( $options['author_check'] ) ) {
			if ( isset( $options['author'] ) && ! empty( $options['author'] ) ) { 
				$author = '<p class="apti-text apti-author">' . $options['author'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $author; ?>';

					    $('' + text_to_insert + '').insertBefore('#authordiv .inside select')
					});
				</script>
			<?php 
			}
		}

	} // end change_author_metabox_content

	/**
	 * Change thumbnail metabox content
	 *
	 * @since 1.0
	 */
	public function change_thumbnail_metabox_content( $content ) {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['thumbnail_check'] ) && ! empty( $options['thumbnail_check'] ) ) {
			if ( isset( $options['thumbnail'] ) && ! empty( $options['thumbnail'] ) ) {
				$thumbnail = '<p class="apti-text apti-thumbnail">' . $options['thumbnail'] . '</p>';
				$content = $thumbnail . $content;
			}
		}

		return $content;

	} // end change_thumbnail_metabox_content

	/**
	 * Change excerpt metabox content
	 *
	 * @since 1.0.2
	 */
	public function change_excerpt_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['excerpt_check'] ) && ! empty( $options['excerpt_check'] ) ) {
			if ( isset( $options['excerpt'] ) && ! empty( $options['excerpt'] ) ) { 
				$excerpt = '<p class="apti-text apti-excerpt">' . $options['excerpt'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $excerpt; ?>';

					    $('' + text_to_insert + '').insertBefore('#postexcerpt .inside label')
					});
				</script>
			<?php 
			}
		}

	} // end change_excerpt_metabox_content

	/**
	 * Change trackbacks metabox content
	 *
	 * @since 1.0.2
	 */
	public function change_trackbacks_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['trackbacks_check'] ) && ! empty( $options['trackbacks_check'] ) ) {
			if ( isset( $options['trackbacks'] ) && ! empty( $options['trackbacks'] ) ) { 
				$trackbacks = '<p class="apti-text apti-trackbacks">' . $options['trackbacks'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $trackbacks; ?>';

					    $('' + text_to_insert + '').insertBefore('#trackbacksdiv .inside p:nth-of-type(1)')
					});
				</script>
			<?php 
			}
		}

	} // end change_trackbacks_metabox_content

	/**
	 * Change customfields metabox content
	 *
	 * @since 1.0.1
	 */
	public function change_customfields_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['customfields_check'] ) && ! empty( $options['customfields_check'] ) ) {
			if ( isset( $options['customfields'] ) && ! empty( $options['customfields'] ) ) { 
				$customfields = '<p class="apti-text apti-customfields">' . $options['customfields'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $customfields; ?>';

					    $('' + text_to_insert + '').insertBefore('#postcustom .inside #postcustomstuff')
					});
				</script>
			<?php 
			}
		}

	} // end change_customfields_metabox_content

	/**
	 * Change comments metabox content
	 *
	 * @since 2.0
	 */
	public function change_comments_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['comments_check'] ) && ! empty( $options['comments_check'] ) ) {
			if ( isset( $options['comments'] ) && ! empty( $options['comments'] ) ) { 
				$comments = '<p class="apti-text apti-comments">' . $options['comments'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $comments; ?>';

					    $('' + text_to_insert + '').insertBefore('#commentsdiv .inside p:nth-of-type(1)')
					});
				</script>
			<?php 
			}
		}

	} // end change_comments_metabox_content	

	/**
	 * Change discussion metabox content
	 *
	 * @since 2.1
	 */
	public function change_discussion_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['discussion_check'] ) && ! empty( $options['discussion_check'] ) ) {
			if ( isset( $options['discussion'] ) && ! empty( $options['discussion'] ) ) { 
				$discussion = '<p class="apti-text apti-discussion">' . $options['discussion'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $discussion; ?>';

					    $('' + text_to_insert + '').insertBefore('#commentstatusdiv .inside p:nth-of-type(1)')
					});
				</script>
			<?php 
			}
		}

	} // end change_discussion_metabox_content	

	/**
	 * Change revisions metabox content
	 *
	 * @since 2.0
	 */
	public function change_revisions_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['revisions_check'] ) && ! empty( $options['revisions_check'] ) ) {
			if ( isset( $options['revisions'] ) && ! empty( $options['revisions'] ) ) { 
				$revisions = '<p class="apti-text apti-revisions">' . $options['revisions'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $revisions; ?>';

					    $('' + text_to_insert + '').insertBefore('#revisionsdiv .inside ul:nth-of-type(1)')
					});
				</script>
			<?php 
			}
		}

	} // end change_revisions_metabox_content

	/**
	 * Change pageattributes metabox content
	 *
	 * @since 1.0.1
	 */
	public function change_pageattributes_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['pageattributes_check'] ) && ! empty( $options['pageattributes_check'] ) ) {
			if ( isset( $options['pageattributes'] ) && ! empty( $options['pageattributes'] ) ) { 
				$pageattributes = '<p class="apti-text apti-pageattributes">' . $options['pageattributes'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $pageattributes; ?>';

					    $('' + text_to_insert + '').insertBefore('#pageparentdiv .inside p:nth-of-type(1)')
					});
				</script>
				<?php 
			}
		}

	} // end change_pageattributes_metabox_content

	/**
	 * Change categories metabox content
	 *
	 * @since 2.1
	 */
	public function change_categories_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['categories_check'] ) && ! empty( $options['categories_check'] ) ) {
			if ( isset( $options['categories'] ) && ! empty( $options['categories'] ) ) { 
				$categories = '<p class="apti-text apti-categories">' . $options['categories'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $categories; ?>';

					    $('' + text_to_insert + '').insertBefore('#taxonomy-category')
					});
				</script>
				<?php 
			}
		}

	} // end change_categories_metabox_content	

	/**
	 * Change tags metabox content
	 *
	 * @since 2.1
	 */
	public function change_tags_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['tags_check'] ) && ! empty( $options['tags_check'] ) ) {
			if ( isset( $options['tags'] ) && ! empty( $options['tags'] ) ) { 
				$tags = '<p class="apti-text apti-tags">' . $options['tags'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $tags; ?>';

					    $('' + text_to_insert + '').insertBefore('#tagsdiv-post_tag .tagsdiv')
					});
				</script>
				<?php 
			}
		}

	} // end change_categories_metabox_content	

	/**
	 * Change postformats metabox content
	 *
	 * @since 1.0.1
	 */
	public function change_postformats_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['postformats_check'] ) && ! empty( $options['postformats_check'] ) ) {
			if ( isset( $options['postformats'] ) && ! empty( $options['postformats'] ) ) { 
				$postformats = '<p class="apti-text apti-postformats">' . $options['postformats'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $postformats; ?>';

					    $('' + text_to_insert + '').insertBefore('#post-formats-select')
					});
				</script>
			<?php 
			}
		}

	} // end change_postformats_metabox_content

	/**
	 * Change slug metabox content
	 *
	 * @since 2.1
	 */
	public function change_slug_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['slug_check'] ) && ! empty( $options['slug_check'] ) ) {
			if ( isset( $options['slug'] ) && ! empty( $options['slug'] ) ) { 
				$slug = '<p class="apti-text apti-slug">' . $options['slug'] . '</p>'; ?>

				<script type="text/javascript">
					jQuery(function($) {
					    var text_to_insert = '<?php echo $slug; ?>';

					    $('' + text_to_insert + '').insertBefore('#slugdiv .inside label')
					});
				</script>
			<?php 
			}
		}

	} // end change_slug_metabox_content	

}
