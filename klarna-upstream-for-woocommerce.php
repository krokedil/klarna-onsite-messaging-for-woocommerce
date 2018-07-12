<?php
/*
 * Plugin Name: Klarna Upstream for WooCommerce
 * Plugin URI:
 * Description: Provides Klarna upstream for WooCommerce
 * Author: krokedil, klarna, automattic
 * Author URI: https://krokedil.se/
 * Version: 1.0.0
 * Text Domain: klarna-upstream-for-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.4.2
 *
 * Copyright (c) 2017-2018 Krokedil
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Definitions.
define( 'WC_KLARNA_UPSTREAM_VERSION', '1.0.0' );
define( 'WC_KLARNA_UPSTREAM_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_KLARNA_UPSTREAM_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Main plugin class.
 */
class Klarna_Upstream_For_WooCommerce {

	/**
	 * Class cunstructor.
	 */
	public function __construct() {
		add_filter( 'wc_gateway_klarna_payments_settings', array( $this, 'extend_settings' ) );
		add_filter( 'kco_wc_gateway_settings', array( $this, 'extend_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Includes.
		include_once WC_KLARNA_UPSTREAM_PLUGIN_PATH . '/classes/class-klarna-upstream-product-page.php';
		include_once WC_KLARNA_UPSTREAM_PLUGIN_PATH . '/classes/class-klarna-upstream-cart-page.php';
	}

	/**
	 * Extends the settings for the Klarna plugin.
	 *
	 * @param array $settings The plugin settings.
	 * @return array $settings
	 */
	public function extend_settings( $settings ) {
		$settings['upstream'] =  array(
			'title' => 'Klarna upstream',
			'type'  => 'title',
		);
		$settings['upstream_placement_id_product'] = array(
			'title'       => __( 'Product page placement id', 'klarna-upstream-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement id of the upstream iframe for the product page.', 'klarna-upstream-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['upstream_placement_id_cart'] = array(
			'title'       => __( 'Cart page placement id', 'klarna-upstream-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement id of the upstream iframe for the cart page.', 'klarna-upstream-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['upstream_uci'] = array(
			'title'       => __( 'UCI', 'klarna-upstream-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the UCI givern by klarna for Klarna Upstream', 'klarna-upstream-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);

		return $settings;
	}

	/**
	 * Gets the settings for the different klarna plugins.
	 *
	 * @return array
	 */
	public static function get_settings() {
		if ( class_exists( 'WC_Klarna_Payments' ) ) {
			return get_option( 'woocommerce_klarna_payments_settings' );
		} elseif ( class_exists( 'Klarna_Checkout_For_WooCommerce' ) ) {
			return get_option( 'woocommerce_kco_settings' );
		}
	}

	/**
	 * Enqueues scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$settings   = self::get_settings();
		$uci        = '';
		if ( isset( $settings['upstream_uci'] ) ) {
			$uci = $settings['upstream_uci'];
		}
		if ( 'US' === wc_get_base_location()['country'] ) {
			$env_string = 'us-library';
		} else {
			$env_string = 'eu-library';
		}
		if ( is_product() || is_cart() ) {
			wp_enqueue_script( 'upstream_script', 'https://' . $env_string . '.klarnaservices.com/merchant.js?uci=' . $uci . '&country=' . wc_get_base_location()['country'], array( 'jquery' ) );

			wp_register_script( 'klarna_upstream', plugins_url( '/assets/js/klarna-upstream.js', __FILE__ ), array( 'jquery' ), '1.0.0' );
			wp_localize_script( 'klarna_upstream', 'klarna_upstream_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
			wp_enqueue_script( 'klarna_upstream' );
		}
	}
} new Klarna_Upstream_For_WooCommerce();
