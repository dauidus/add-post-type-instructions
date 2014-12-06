<?php
/**
 * Set Helper Content.
 *
 * For A Better UX
 *
 * @package   Set_Helper_Content
 * @author    dauidus (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */
?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ) . ' Settings'; ?></h2>
	<?php // settings_errors(); ?>

	<?php
		$plugin = Set_Helper_Content::get_instance();
		$post_types = $plugin->supported_post_types();

		if ( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		} else {
			$active_tab = ( isset( $post_types[0] ) ) ? $post_types[0] : '';
		}
	?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $post_types as $pt ) { ?>
		<a href="?page=<?php echo $plugin->get_plugin_slug(); ?>&tab=<?php echo $pt; ?>" class="nav-tab <?php echo ( $pt == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php $post_type_object = get_post_type_object( $pt ); echo $post_type_object->labels->name; ?></a>
		<?php } ?>
	</h2>

	<form method="post" action="options.php">
		<?php
			$section = $plugin->get_plugin_slug() . '_' . $active_tab;

			settings_fields( $section );
			do_settings_sections( $section );

			submit_button();
		?>
	</form>

</div><!-- /.wrap -->
