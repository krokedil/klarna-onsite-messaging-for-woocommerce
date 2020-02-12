<?php // phpcs: ignore.
/**
 * Plugin Name: Klarna On-Site Messaging for WooCommerce
 * Plugin URI:
 * Description: Provides Klarna On-Site Messaging for WooCommerce
 * Author: krokedil, klarna
 * Author URI: https://krokedil.se/
 * Version: 1.0.5
 * Text Domain: klarna-onsite-messaging-for-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.6.4
 *
 * @package Klarna_OnSite_Messaging
 *
 * Copyright (c) 2017-2020 Krokedil
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
define( 'WC_KLARNA_ONSITE_MESSAGING_VERSION', '1.0.5' );
define( 'WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_KLARNA_ONSITE_MESSAGING_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Main plugin class.
 */
class Klarna_OnSite_Messaging_For_WooCommerce {
	/**
	 * Class cunstructor.
	 */
	public function __construct() {
		add_filter( 'wc_gateway_klarna_payments_settings', array( $this, 'extend_settings' ) );
		add_filter( 'kco_wc_gateway_settings', array( $this, 'extend_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'plugins_loaded', array( $this, 'check_version' ) );
		add_action( 'plugins_loaded', array( $this, 'include_files' ) );
	}

	/**
	 * Extends the settings for the Klarna plugin.
	 *
	 * @param array $settings The plugin settings.
	 * @return array $settings
	 */
	public function extend_settings( $settings ) {
		$settings['onsite_messaging']                      = array(
			'title' => 'Klarna On-Site Messaging',
			'type'  => 'title',
		);
		$settings['onsite_messaging_uci']                  = array(
			'title'       => __( 'UCI', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the UCI givern by klarna for Klarna On-Site Messaging', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_enabled_product']      = array(
			'title'   => __( 'Enable/Disable the Product placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable/Disable the Product placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'default' => 'yes',
		);
		$settings['onsite_messaging_placement_id_product'] = array(
			'title'       => __( 'Product page placement id', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement id of the On-Site Messaging placement for the product page.', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_product_location']     = array(
			'title'   => __( 'Product On-Site Messaging placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'desc'    => __( 'Select where to display the widget in your product pages', 'klarna-onsite-messaging-for-woocommerce' ),
			'id'      => '',
			'default' => '45',
			'type'    => 'select',
			'options' => array(
				'4'  => __( 'Above Title', 'klarna-onsite-messaging-for-woocommerce' ),
				'7'  => __( 'Between Title and Price', 'klarna-onsite-messaging-for-woocommerce' ),
				'15' => __( 'Between Price and Excerpt', 'klarna-onsite-messaging-for-woocommerce' ),
				'25' => __( 'Between Excerpt and Add to cart button', 'klarna-onsite-messaging-for-woocommerce' ),
				'35' => __( 'Between Add to cart button and Product meta', 'klarna-onsite-messaging-for-woocommerce' ),
				'45' => __( 'Between Product meta and Product sharing buttons', 'klarna-onsite-messaging-for-woocommerce' ),
				'55' => __( 'After Product sharing-buttons', 'klarna-onsite-messaging-for-woocommerce' ),
			),
			$settings['onsite_messaging_theme_product']       = array(
				'title'   => __( 'Product Placement Theme', 'klarna-onsite-messaging-for-woocommerce' ),
				'desc'    => __( 'Select wich theme to use for the product pages.', 'klarna-onsite-messaging-for-woocommerce' ),
				'id'      => '',
				'default' => '',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Light', 'klarna-onsite-messaging-for-woocommerce' ),
					'dark'    => __( 'Dark', 'klarna-onsite-messaging-for-woocommerce' ),
					'none'    => __( 'None', 'klarna-onsite-messaging-for-woocommerce' ),
				),
			),
		);
		$settings['onsite_messaging_enabled_cart']         = array(
			'title'   => __( 'Enable/Disable the Cart placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable/Disable the Cart placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'default' => 'yes',
		);
		$settings['onsite_messaging_placement_id_cart']    = array(
			'title'       => __( 'Cart page placement id', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement id of the On-Site Messaging placement for the cart page.', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_cart_location']        = array(
			'title'   => __( 'Cart On-Site Messaging placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'desc'    => __( 'Select where to display the widget on your cart page', 'klarna-onsite-messaging-for-woocommerce' ),
			'id'      => '',
			'default' => 'woocommerce_cart_collaterals',
			'type'    => 'select',
			'options' => array(
				'woocommerce_cart_collaterals'    => __( 'Above Cross sell', 'klarna-onsite-messaging-for-woocommerce' ),
				'woocommerce_before_cart_totals'  => __( 'Above cart totals', 'klarna-onsite-messaging-for-woocommerce' ),
				'woocommerce_proceed_to_checkout' => __( 'Between cart totals and proceed to checkout button', 'klarna-onsite-messaging-for-woocommerce' ),
				'woocommerce_after_cart_totals'   => __( 'After proceed to checkout button', 'klarna-onsite-messaging-for-woocommerce' ),
				'woocommerce_after_cart'          => __( 'Bottom of the page', 'klarna-onsite-messaging-for-woocommerce' ),
			),
			$settings['onsite_messaging_theme_cart']       = array(
				'title'   => __( 'Cart Placement Theme', 'klarna-onsite-messaging-for-woocommerce' ),
				'desc'    => __( 'Select wich theme to use for the cart page.', 'klarna-onsite-messaging-for-woocommerce' ),
				'id'      => '',
				'default' => '',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Light', 'klarna-onsite-messaging-for-woocommerce' ),
					'dark'    => __( 'Dark', 'klarna-onsite-messaging-for-woocommerce' ),
					'none'    => __( 'None', 'klarna-onsite-messaging-for-woocommerce' ),
				),
			),
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
		$settings = self::get_settings();
		$uci      = '';
		if ( isset( $settings['onsite_messaging_uci'] ) ) {
			$uci = $settings['onsite_messaging_uci'];
		}
		if ( 'US' === wc_get_base_location()['country'] ) {
			$env_string = 'us-library';
		} else {
			$env_string = 'eu-library';
		}
		if ( is_product() || is_cart() ) {
			wp_enqueue_script( 'onsite_messaging_script', 'https://' . $env_string . '.klarnaservices.com/merchant.js?uci=' . $uci . '&country=' . wc_get_base_location()['country'], array( 'jquery' ) );

			wp_register_script( 'klarna_onsite_messaging', plugins_url( '/assets/js/klarna-onsite-messaging.js', __FILE__ ), array( 'jquery' ), '1.0.0' );
			wp_localize_script(
				'klarna_onsite_messaging', 'klarna_onsite_messaging_params', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
			wp_enqueue_script( 'klarna_onsite_messaging' );
		}
	}

	/**
	 * Checks the plugin version.
	 *
	 * @return void
	 */
	public function check_version() {
		require WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/includes/plugin_update_check.php';
		$kernl_updater = new PluginUpdateChecker_2_0(
			'https://kernl.us/api/v1/updates/5c763fe5f22bcc016dd96618/',
			__FILE__,
			'klarna-onsite-messaging-for-woocommerce',
			1
		);
	}

	/**
	 * Includes the plugin files.
	 *
	 * @return void
	 */
	public function include_files() {
		// Includes.
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-product-page.php';
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-cart-page.php';
	}
} new Klarna_OnSite_Messaging_For_WooCommerce();
