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
?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ) . ''; ?></h2>
	<?php // settings_errors(); ?>

	<?php
		$plugin = add_post_type_instructions::get_instance();
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
		<a href="?page=<?php echo $plugin->get_plugin_slug(); ?>&tab=general" class="nav-tab nav-tab-general <?php echo ( 'general' == $active_tab ) ? 'nav-tab-active' : ''; ?>">General Settings</a>
	</h2>

	<div id="apti-sidebar">
		<h3>Suite of Products:</h3>

		<?php

		if ( ! class_exists( '' ) ) {
			$plugin_banners[] = array(
				'url' => '',
				'img' => '',
				'alt' => 'Requirements Checklist PRO',
			);
		}

		if ( ! class_exists( '' ) ) {
			$plugin_banners[] = array(
				'url' => '',
				'img' => '',
				'alt' => 'Default Content PRO',
			);
		}


		$service_banners[] = array(
				'url' => 'https://www.paypal.me/dauidus/5',
				'img' => 'donate.jpg',
				'alt' => 'Donate Banner',
		);

		shuffle( $plugin_banners );
		?>

		<div id="sidebar-container">
			<div id="sidebar">
				
				<?php
				$i = 0;
				foreach ( $plugin_banners as $banner ) {
					echo '<a target="_blank" href="' . esc_url( $banner['url'] ) . '"><div style="width: 100%; height: 90px; background: #1E8CBE; border-radius: 8px;"></div>';
					//<img src="' . plugins_url( 'set-default-content/admin/images/' . $banner['img'] ) . '" alt="' . esc_attr( $banner['alt'] ) . '"/>
					echo '</a><br/>';
					$i ++;
				}

				foreach ( $service_banners as $service_banner ) {
					echo '<a target="_blank" href="' . esc_url( $service_banner['url'] ) . '"><img class="apti-banner" src="' . plugins_url( 'instructional-content/admin/images/' . $service_banner['img'] ) . '" alt="' . esc_attr( $service_banner['alt'] ) . '"/></a><br/><br/>';
				}
				?>	

			</div>
		</div>
	</div>

	<form method="post" action="options.php">
		<?php
			$section = $plugin->get_plugin_slug() . '_' . $active_tab;

			settings_fields( $section );
			do_settings_sections( $section );

			submit_button();
		?>
	</form>

</div><!-- /.wrap -->
