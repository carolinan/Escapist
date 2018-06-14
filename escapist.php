<?php
/*
Plugin Name: Escapist
Description: Escapist creates a list of the escaping functions found in the theme and tries to determine if the function is used correctly.
Author: Poena
Version: 1.1
Text Domain: escapist
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


include( plugin_dir_path( __FILE__ ) . 'attributes.php');
include( plugin_dir_path( __FILE__ ) . 'html.php');
include( plugin_dir_path( __FILE__ ) . 'textarea.php');
include( plugin_dir_path( __FILE__ ) . 'other.php');
include( plugin_dir_path( __FILE__ ) . 'petpeeve.php');

/**
 * Register a custom menu page.
 */
function escapist_register_my_custom_menu_page(){
    add_theme_page( 
        __( 'Escapist', 'escapist' ),
        __( 'Escapist', 'escapist' ),
        'manage_options',
        'escapist',
        'escapist_custom_menu_page',
        6
    ); 
}
add_action( 'admin_menu', 'escapist_register_my_custom_menu_page' );
 
/**
 * Display a custom menu page
 */
function escapist_custom_menu_page() {
?>
	
	<div class="wrap">
    <div id="welcome-panel" class="welcome-panel">
   	<h1><?php esc_html_e( 'Escapist', 'escapist' ); ?></h1>
   	<hr>
    <p class="about-description">
    <?php 
    esc_html_e( 'Escapist creates a list of the escaping functions found in the theme and tries to determine if the function is used correctly.','escapist');
    echo '<br><br>';
	esc_html_e('Note: The plugin is not optimised in any way, and there are false positives where lines are incorrectly marked because the plugin only checks one line at the time.','escapist');
	echo '<br><br>';
	echo __('What the plugin does not do:<br>Besides the "Pet peeves", it does not check wether something is escaped or not.<br>
		It does not replace a manual code review. It can only help you identify files that you need to look closer at.','escapist');
	?>
    </p>
    <br>
	<?php
	$installed_themes = wp_get_themes();
	$themes = array();

	if ( ! empty( $installed_themes ) ) {
		foreach ( $installed_themes as $key => $theme ) {
			$themes[ $key ] = $theme->get( 'Name' );
		}
	}

	if ( empty( $themes ) ) {
		return;
	}

	$current_theme = get_stylesheet();
	if ( ! empty( $_POST['themename'] ) ) {
		$current_theme = $_POST['themename'];
	}
	?>

	<form action="<?php echo esc_url( admin_url( 'themes.php?page=escapist.php' ) ); ?>" method="post">
		<?php wp_nonce_field( 'escapist_nonce', 'escapist_nonce' ); ?>

		<div>
			<h2><?php esc_html_e( 'Select a theme to check:', 'escapist' ); ?></h2>
			<label for="themename">
				<select name="themename">
					<?php foreach ( $themes as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current_theme, $key ); ?>><?php esc_html_e( $value ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<input type="submit" value="<?php esc_attr_e( 'Check', 'escapist' ); ?>" id="check-status" class="button button-secondary">
		</div>
	</form>
	<br>
   	</div>

	<?php
	if ( empty( $_POST['themename'] ) ) {
		return;
	}
	// Verify nonce.
	if ( ! isset( $_POST['escapist_nonce'] ) || ! wp_verify_nonce( $_POST['escapist_nonce'], 'escapist_nonce' ) ) {
		esc_html_e( 'Error', 'escapist' );
		return;
	}

	$theme_slug = esc_html( $_POST['themename'] );
	$theme = wp_get_theme( $theme_slug );
	$php_files = $theme->get_files( 'php', 4, false );
	?>
	 <div>
	 	<table class="wp-list-table widefat plugins">
		 	<thead>
		 		<tr>
		 			<th colspan="2"><h2><?php esc_html_e('Checking for esc_attr, esc_attr__, esc_attr_e','escapist');?></h2>
		 			<i><?php esc_html_e('Escaping for HTML attributes.','escapist');?> 
		 			<a href="https://developer.wordpress.org/reference/functions/esc_attr/"><?php esc_html_e('Reference.','escapist');?></i></a>
		 			<br><br>
		 			</th>
		 		</tr>
				<tr>
					<th scope="col" id="name" class="manage-column column-name column-primary"><?php esc_html_e('File name','escapist');?></th>
					<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e('Description','escapist');?></th>
				</tr>
			</thead>
			<tbody">
			<?php

			foreach( array_keys( $php_files ) as $file ) {
				$filepath = get_theme_root( $theme ) . "/$theme_slug/$file";
				$lines = file( $filepath, FILE_IGNORE_NEW_LINES ); // Read the theme file into an array
				if ( preg_grep( '/esc_attr/', $lines ) ) {
					echo '<tr class="active">
					<th class="check-column" colspan="2"><strong style="margin-left:8px;">' . $file. '<strong></th>
					<td><br></td>
					</tr>';

					escapist_attributes( $lines );
				}
			}//End foreach.
		?>
	</tbody>
	</table>
	<br><br>

	 <table class="wp-list-table widefat plugins">
		 <thead>
		 	<tr>
		 		<th colspan="2"><h2><?php esc_html_e('Checking for esc_html, esc_html__, esc_html_e','escapist');?></h2>
		 		<i><?php esc_html_e('Escaping for HTML blocks.','escapist');?> 
		 		<a href="https://developer.wordpress.org/reference/functions/esc_html/"><?php esc_html_e('Reference.','escapist');?></i></a>
		 		<br><br>
		 		</th>
		 	</tr>
			<tr>
				<th scope="col" id="name" class="manage-column column-name column-primary"><?php esc_html_e('File name','escapist');?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e('Description','escapist');?></th>
			</tr>
		</thead>
		<tbody>
			<?php

			foreach( array_keys( $php_files ) as $file ) {
				$filepath = get_theme_root( $theme ) . "/$theme_slug/$file";
				$lines = file( $filepath, FILE_IGNORE_NEW_LINES ); // Read the theme file into an array
				if ( preg_grep( '/esc_html/', $lines ) ) {
					echo '<tr class="active">
					<th class="check-column" colspan="2"><strong style="margin-left:8px;">' . $file. '<strong></th>
					<td><br></td>
					</tr>';

					escapist_html( $lines );
				}
			}//End foreach.
		?>
	</tbody>
	</table>
	<br><br>
		 <table class="wp-list-table widefat plugins">
		 <thead>
		 	<tr>
		 		<th colspan="2"><h2><?php esc_html_e('Checking for esc_textarea','escapist');?></h2>
		 		<i><?php esc_html_e('Escaping for textarea values.','escapist');?> 
		 		<a href="https://developer.wordpress.org/reference/functions/esc_textarea/"><?php esc_html_e('Reference.','escapist');?></i></a>
		 		<br><br>
		 		</th>
		 	</tr>
			<tr>
				<th scope="col" id="name" class="manage-column column-name column-primary"><?php esc_html_e('File name','escapist');?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e('Description','escapist');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach( array_keys( $php_files ) as $file ) {
				$filepath = get_theme_root( $theme ) . "/$theme_slug/$file";
				$lines = file( $filepath, FILE_IGNORE_NEW_LINES ); // Read the theme file into an array
				if ( preg_grep( '/<textarea/', $lines ) or preg_grep( '/esc_textarea/', $lines ) ) {
					echo '<tr class="active">
					<th class="check-column" colspan="2"><strong style="margin-left:8px;">' . $file. '<strong></th>
					<td><br></td>
					</tr>';

					escapist_textarea( $lines );
				}
			}//End foreach.
		?>
	</tbody>
	</table>

	<br><br>
		 <table class="wp-list-table widefat plugins">
		 <thead>
		 	<tr>
		 		<th colspan="2"><h2><?php esc_html_e('Checking for other escaping functions','escapist');?></h2>
		 			<?php esc_html_e('Includes checks for esc_url and esc_js.','escapist');?> 

		 		<br><br>
		 		</th>
		 	</tr>
			<tr>
				<th scope="col" id="name" class="manage-column column-name column-primary"><?php esc_html_e('File name','escapist');?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e('Description','escapist');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach( array_keys( $php_files ) as $file ) {
				$filepath = get_theme_root( $theme ) . "/$theme_slug/$file";
				$lines = file( $filepath, FILE_IGNORE_NEW_LINES ); // Read the theme file into an array

				if ( preg_grep( '/esc_url/', $lines ) or preg_grep( '/esc_js/', $lines )) {
					echo '<tr class="active">
					<th class="check-column" colspan="2"><strong style="margin-left:8px;">' . $file. '<strong></th>
					<td><br></td>
					</tr>';

					escapist_other( $lines );
				}
			}//End foreach.
		?>
	</tbody>
	</table>

	<br><br>
		 <table class="wp-list-table widefat plugins">
		 <thead>
		 	<tr>
		 		<th colspan="2"><h2><?php esc_html_e('Pet peeves','escapist');?></h2>
			 		<br><br>
		 		</th>
		 	</tr>
			<tr>
				<th scope="col" id="name" class="manage-column column-name column-primary"><?php esc_html_e('File name','escapist');?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e('Description','escapist');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach( array_keys( $php_files ) as $file ) {
				$filepath = get_theme_root( $theme ) . "/$theme_slug/$file";
				$lines = file( $filepath, FILE_IGNORE_NEW_LINES ); // Read the theme file into an array

				if ( preg_grep( '/home_url/', $lines ) or preg_grep( '/placeholder/', $lines ) ) {
					echo '<tr class="active">
					<th class="check-column" colspan="2"><strong style="margin-left:8px;">' . $file. '<strong></th>
					<td><br></td>
					</tr>';

					escapist_petpeeve( $lines );
				}
			}//End foreach.
		?>
	</tbody>
	</table>
	</div>
</div>
<?php
}

