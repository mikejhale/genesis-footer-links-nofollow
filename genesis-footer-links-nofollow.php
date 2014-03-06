<?php
/*
Plugin Name: Genesis Footer Links Nofollow
Plugin URI: http://www.commencia.com/plugins/genesis-footer-links-nofollow
Description: Makes links in the footer nofollow
Version: 0.2
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
	register_setting( 'gfln-options_group', 'included_domains' );
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
         <tr valign="top">
        	<th scope="row"><?php _e( 'Included Domains', 'gfln' ) ?></th>
        	<td>
	        	<p><input type="text" name="included_domains" value="<?php echo get_option('included_domains'); ?>" /></p>
	        	<p><span class="description"><?php _e( 'Enter only domain names separated by commas: google.com, yoursite.com', 'gfln' ); ?></span></p>
        	</td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php 
}
	
function gfln_footer_output( $output ) {
	if ( !is_home() ) {
		return parse_footer_links( $output );
	}
	else {
		if ( get_option( 'homepage_follow' ) == 'on' ) {
			return $output;
		}
		else {
			return parse_footer_links( $output );
		}
	}
}

function parse_footer_links( $footer ) {
	// check for included domains
	$domains = array();
	$included = get_option( 'included_domains' );	
	if ( $included ) {
		$domains = array_filter( explode( ",", $included ) );
	}	
	
	// parse footer
	$dom = new DOMDocument();
	$dom->loadHTML( utf8_decode( $footer ) );
	
	foreach( $dom->getElementsByTagName( "a" ) as $a) {
		$href = $a->getAttribute( 'href' );
		$parseHref = parse_url( $href );
		$host = $parseHref['host'];

		if ( count( $domains ) > 0 ) {
			// only do included domains
			foreach( $domains as $d ) {
				if ( stristr( $host, trim( $d ) ) ) {
					$a->setAttribute( 'rel', 'nofollow' );
					break;
				}
			}		
		}
		else 
		{
			// do all
			$a->setAttribute( 'rel', 'nofollow' );
		}	
	}
	
	return $dom->saveHTML();
}
?>