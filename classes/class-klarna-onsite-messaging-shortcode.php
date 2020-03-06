<?php
/**
 * File for the class for the shortcode class.
 */

/**
 * Class for the shortcode for OSM.
 */
class Klarna_OnSite_Messaging_Shortcode {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_shortcode( 'onsite_messaging', array( $this, 'osm_shortcode' ) );
	}

	/**
	 * Prints the klarna placement tag.
	 *
	 * @param array $atts The attributes for the shortcode.
	 * @return void
	 */
	public function osm_shortcode( $atts ) {
		if ( ! is_admin() ) {
			do_action( 'osm_shortcode_added' );
			$atts = shortcode_atts(
				array(
					'data-key'             => 'homepage-promotion-wide',
					'data-theme'           => '',
					'data-purchase-amount' => '',
				), $atts
			);
			ob_start();
			kosm_klarna_placement( $atts );
			$html = ob_get_clean();
			return $html;
		}
	}
}
new Klarna_OnSite_Messaging_Shortcode();
