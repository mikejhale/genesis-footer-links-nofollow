<?php
/*
Plugin Name: Genesis Footer Links Nofollow
Plugin URI: http://www.commencia.com/plugins/genesis-footer-links-nofollow
Description: Makes links in the footer nofollow
Version: 0.1
Author: Mike Hale
Author URI: http://www.mikehale.me/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Actions
add_action( 'admin_menu', 'gfln_create_menu' );
add_action( 'admin_init', 'gfln_register_settings' );

//  Filters
add_filter( 'genesis_footer_output', 'gfln_footer_output', 90 );

function gfln_create_menu() {
	add_options_page( 'Footer Links Options', 'Footer Links', 'manage_options', 'genesis-footer-links-nofollow-options', 'gfln_options_page' );
}

function gfln_register_settings() {
	register_setting( 'gfln-options_group', 'homepage_follow' );
}

function gfln_options_page() { ?>	
<div class="wrap">
<h2>Genesis Footer Links Nofollow</h2>
<form method="post" action="options.php">
	<?php settings_fields( 'gfln-options_group' ); ?>
    <?php do_settings_sections( 'gfln-options_group' ); ?>
    <table class="form-table">
        <tr valign="top">
        	<th scope="row"><?php _e( 'Exlude Homepage Footer', 'gfln' ) ?></th>
        	<td>
	        	<p><input type="checkbox" name="homepage_follow" <?php checked( get_option( 'homepage_follow' ), 'on' ); ?> /></p>
	        	<p><span class="description"><?php _e( 'Checking this option will exclude footer links on the home page from being rel=nofollow.', 'gfln' ); ?></span></p>
        	</td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php 
}
	
function gfln_footer_output( $output ) {	
	if ( !is_home() )
	{
		return preg_replace( '/(<a.*?)>/i', '$1 rel="nofollow">', $output );
	}
	else
	{
		if ( get_option( 'homepage_follow' ) == 'on' ) {
			return $output;
		}
		else 
		{
			return preg_replace( '/(<a.*?)>/i', '$1 rel="nofollow">', $output );	
		}
	}
}
?>