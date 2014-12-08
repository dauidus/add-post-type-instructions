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
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'set-helper-content.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Fire functions
		add_action( 'admin_print_styles', array( $this, 'is_edit_page' ) );
		add_action( 'edit_form_after_title', array( $this, 'add_content_above' ) );
		add_filter( 'the_editor_content', array( $this, 'change_editor_content' ) );
		add_action( 'admin_head', array( $this, 'change_author_metabox_content' ) );
		add_filter( 'admin_post_thumbnail_html', array( $this, 'change_thumbnail_metabox_content' ) );
		add_action( 'admin_head', array( $this, 'change_excerpt_metabox_content' ) );
		add_action( 'admin_head', array( $this, 'change_trackbacks_metabox_content' ) );
		add_action( 'admin_head', array( $this, 'change_customfields_metabox_content' ) );
		// add_action( 'admin_head', array( $this, 'change_comments_metabox_content' ) );
		// revisions
		add_action( 'admin_head', array( $this, 'change_pageattributes_metabox_content' ) );
		add_action( 'admin_head', array( $this, 'change_postformats_metabox_content' ) );

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
	 * @return string Post type
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
	 * @param  string $content HTML string
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
	 * Add instruction text above the content editor
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0
	 */
	public function add_content_above() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['instruction'] ) && ! empty( $options['instruction'] ) ) {
			$template = $options['instruction'];
			echo '<br /><div id="apti-below-title"><h3>' . $template . '</h3></div>';
		}

	} // end add_content_above

	/**
	 * Set the default value fot the content editor
	 *
	 * @param  string $content HTML string
	 * @return null
	 *
	 * @since 1.0
	 */
	public function change_editor_content( $content ) {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['editor'] ) && ! empty( $options['editor'] ) ) {

			//add our content
			if ( empty( $content ) ) {
        		$template = $options['editor'];
        		return $template;
    		} else
        		return $content;

		}

	} // end change_editor_content

	/**
	 * Change author metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.1
	 */
	public function change_author_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_author_metabox_content

	/**
	 * Change thumbnail metabox content
	 *
	 * @param  string $content HTML string
	 * @return string Modified content
	 *
	 * @since 1.0
	 */
	public function change_thumbnail_metabox_content( $content ) {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['thumbnail'] ) && ! empty( $options['thumbnail'] ) ) {
			$thumbnail = '<p class="apti-text apti-thumbnail">' . $options['thumbnail'] . '</p>';
			$content = $thumbnail . $content;
		}

		return $content;

	} // end change_thumbnail_metabox_content

	/**
	 * Change excerpt metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.2
	 */
	public function change_excerpt_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_excerpt_metabox_content

	/**
	 * Change trackbacks metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.2
	 */
	public function change_trackbacks_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_trackbacks_metabox_content

	/**
	 * Change customfields metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.1
	 */
	public function change_customfields_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_customfields_metabox_content

	/**
	 * Change comments metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.2
	 */
/*	public function change_comments_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		if ( isset( $options['comments'] ) && ! empty( $options['comments'] ) ) { 
			$comments = '<p class="apti-text apti-comments">' . $options['comments'] . '</p>'; ?>

			<script type="text/javascript">
				jQuery(function($) {
				    var text_to_insert = '<?php echo $comments; ?>';

				    $('' + text_to_insert + '').insertBefore('#commentstatusdiv .inside #postcustomstuff')
				});
			</script>
		<?php 
		}

	} // end change_comments_metabox_content	*/

// revisions

	/**
	 * Change pageattributes metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.1
	 */
	public function change_pageattributes_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_pageattributes_metabox_content

	/**
	 * Change postformats metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0.1
	 */
	public function change_postformats_metabox_content() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

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

	} // end change_postformats_metabox_content

}
