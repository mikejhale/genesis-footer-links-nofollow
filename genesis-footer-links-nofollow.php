<?php
/**
 * Genesis Footer Links NoFollow
 *
 * @package           Genesis_Footer_Links_NoFollow
 * @author            Mike Hale
 * @license           GPL-2.0+
 * @link              http://www.mikehale.me/genesis-footer-links-nofollow-plugin/
 * @copyright         2014 Mike Hale
 *
 * @wordpress-plugin
 * 
 * Plugin Name:       Genesis Footer Links Nofollow
 * Plugin URI:        http://www.commencia.com/plugins/genesis-footer-links-nofollow
 * Description:       Makes links in the footer nofollow
 * Version:           0.2.0
 * Author:            Mike Hale
 * Author URI:        http://www.mikehale.me/
 * Text Domain:       genesis-footer-links-nofollow
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/GaryJones/plugin-name
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'admin_menu', 'gfln_create_menu' );
/**
 * Add Footer Links to Settings menu.
 *
 * @since 0.2.0
 */
function gfln_create_menu() {
	add_options_page(
		__( 'Genesis Footer Links Options', 'genesis-footer-links-nofollow' ),
		__( 'Footer Links', 'gfnl' ),
		'manage_options',
		'genesis-footer-links-nofollow-options',
		'gfln_options_page'
	);
}

add_action( 'admin_init', 'gfln_register_settings' );
/**
 * Register Genesis Footer Links NoFollow settings.
 *
 * This ensures that WordPress handles the saving of them via the Settings API.
 *
 * @since 0.2.0
 */
function gfln_register_settings() {
	register_setting( 'gfln-options_group', 'homepage_follow' );
	register_setting( 'gfln-options_group', 'included_domains' );
}

/**
 * Callback to output the settings page.
 *
 * @since  0.2.0
 */
function gfln_options_page() { ?>	
<div class="wrap">
<form method="post" action="options.php">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_fields( 'gfln-options_group' ); ?>
    <?php do_settings_sections( 'gfln-options_group' ); ?>
    <table class="form-table">
        <tr valign="top">
        	<th scope="row"><?php _e( 'Exclude Home Page Footer', 'genesis-footer-links-nofollow' ) ?></th>
        	<td>
	        	<p><input type="checkbox" name="homepage_follow"<?php checked( get_option( 'homepage_follow' ), 'on' ); ?> /></p>
	        	<p><span class="description"><?php printf( __( 'Checking this option will exclude footer links on the home page from being %s.', 'genesis-footer-links-nofollow' ), '<code>rel=nofollow</code>' ); ?></span></p>
        	</td>
        </tr>
         <tr valign="top">
        	<th scope="row"><?php _e( 'Included Domains', 'genesis-footer-links-nofollow' ) ?></th>
        	<td>
	        	<p><input type="text" name="included_domains" value="<?php echo get_option( 'included_domains' ); ?>" /></p>
	        	<p><span class="description"><?php printf( __( 'Optionally enter domain names to affect, separated by commas, e.g. %s.com. If none are listed, all links are amended.', 'genesis-footer-links-nofollow' ), '<code>google.com, yoursite</code>' ); ?></span></p>
        	</td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php 
}
	
add_filter( 'genesis_footer_output', 'gfln_footer_output', 90 );
/**
 * Integrate the plugin functionality with Genesis output.
 *
 * This is the only thing that touches Genesis.
 *
 * @since  0.2.0
 * 
 * @param  string $output Existing footer output.
 * 
 * @return string         Amended footer output.
 */
function gfln_footer_output( $output ) {
	if ( ! is_home() ) {
		return gfln_parse_footer_links( $output );
	}
	
	if ( 'on' === get_option( 'homepage_follow' ) ) {
		return $output;
	}
	
	return gfln_amend_links( $output );

}

/**
 * Parse markup to add `rel=nofollow` to links to selected domains.
 *
 * @since  0.2.0
 *
 * @param  string $markup Existing markup.
 * 
 * @return string         Amended markup.
 */
function gfln_amend_links( $markup ) {
	$domains = gfln_get_domains();

	$dom = new DOMDocument();
	$dom->loadHTML( utf8_decode( $markup ) );
	
	foreach( $dom->getElementsByTagName( 'a' ) as $a) {
		$href = $a->getAttribute( 'href' );
		$parsed_href = parse_url( $href );
		$host = $parsed_href['host'];

		if ( count( $domains ) > 0 ) { // only do included domains
			foreach( $domains as $d ) {
				if ( stristr( $host, trim( $d ) ) ) {
					$a->setAttribute( 'rel', 'nofollow' );
					break;
				}
			}		
		} else { // do all
			$a->setAttribute( 'rel', 'nofollow' );
		}	
	}
	
	return $dom->saveHTML();
}

/**
 * Get any listed domains.
 *
 * @since  0.3.0
 * 
 * @return array List of domains that should have nofollow applied. May be empty.
 */
function gfln_get_domains() {
	$domains = array();
	$included = get_option( 'included_domains' );	
	if ( $included ) {
		$domains = array_filter( explode( ',', $included ) );
		$domains = array_map( 'trim', $domains );
	}
	return $domains;
}
