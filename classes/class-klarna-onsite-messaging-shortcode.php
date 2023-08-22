<?php
/**
 * File for the class for the shortcode class.
 *
 * @package Klarna_OnSite_Messaging/Classes
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
	 * @return string
	 */
	public function osm_shortcode( $atts ) {
		// If no price is set, maybe set the one for the product or cart page.
		if ( ! isset( $atts['data-purchase-amount'] ) ) {
			$atts = $this->set_price( $atts );
		}
		if ( ! is_admin() ) {
			do_action( 'osm_shortcode_added' );
			$atts = shortcode_atts(
				array(
					'data-key'             => 'homepage-promotion-wide',
					'data-theme'           => '',
					'data-purchase-amount' => '',
				),
				$atts
			);
			ob_start();
			kosm_klarna_placement( $atts );
			$html = ob_get_clean();

			return $html;
		}
	}

	/**
	 * Get the price if we can for a product or cart page.
	 *
	 * @param array $atts The attributes for the shortcode.
	 * @return array
	 */
	public function set_price( $atts ) {
		$price = '';
		wp_enqueue_script( 'klarna-onsite-messaging' );
		if ( is_product() ) {
			$product = kosm_get_global_product();

			// If we did not get a product, just return the attributes.
			if ( empty( $product ) ) {
				return $atts;
			}

			if ( $product->is_type( 'variable' ) ) {
				$price = $product->get_variation_price( 'min', true );
			} elseif ( $product->is_type( 'bundle' ) ) {
				$price = $product->get_bundle_price( 'min' );
			} else {
				$price = wc_get_price_to_display( $product );
			}

			// Force a numeric value.
			$price = floatval( $price ) * 100;
		} elseif ( is_cart() ) {
			$price = WC()->cart->get_total( 'klarna_onsite_messaging' ) * 100;
		}

		$atts['data-purchase-amount'] = $price;
		return $atts;
	}
}
new Klarna_OnSite_Messaging_Shortcode();
