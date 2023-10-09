<?php // phpcs:ignore
/**
 * Plugin Name: Klarna On-Site Messaging for WooCommerce
 * Plugin URI:
 * Description: Provides Klarna On-Site Messaging for WooCommerce
 * Author: krokedil, klarna
 * Author URI: https://krokedil.se/
 * Version: 1.13.0
 * Text Domain: klarna-onsite-messaging-for-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 3.8
 * WC tested up to: 8.2.0
 *
 * @package Klarna_OnSite_Messaging
 *
 * Copyright (c) 2017-2023 Krokedil
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
define( 'WC_KLARNA_ONSITE_MESSAGING_VERSION', '1.13.0' );
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
		add_action( 'wc_ajax_kosm_get_cart_total', array( $this, 'get_cart_total' ) );

		add_filter( 'wc_gateway_klarna_payments_settings', array( $this, 'extend_settings' ) );
		add_filter( 'kco_wc_gateway_settings', array( $this, 'extend_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'script_loader_tag', array( $this, 'load_klarna_async' ), 10, 3 );

		add_action( 'plugins_loaded', array( $this, 'check_version' ) );
		add_action( 'plugins_loaded', array( $this, 'include_files' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'register_klarna_osm_widget' ) );
	}

	/**
	 * Retrieve the cart total amount.
	 *
	 * @return void
	 */
	public function get_cart_total() {
		if ( ! isset( WC()->cart ) ) {
			wp_send_json_error( 'no_cart' );
		}

		wp_send_json_success( WC()->cart->total );
	}

	/**
	 * Init the plugin after plugins_loaded so environment variables are set.
	 */
	public function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$this->set_data_client_id();
		add_action( 'before_woocommerce_init', array( $this, 'declare_wc_compatibility' ) );
	}

		/**
		 * Declare compatibility with WooCommerce features.
		 *
		 * @return void
		 */
	public function declare_wc_compatibility() {
		// Declare HPOS compatibility.
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Extends the settings for the Klarna plugin.
	 *
	 * @param array $settings The plugin settings.
	 * @return array $settings
	 */
	public function extend_settings( $settings ) {
		$settings['onsite_messaging']                  = array(
			'title' => 'Klarna On-Site Messaging',
			'type'  => 'title',
		);
		$settings['data_client_id']                    = array(
			'title'       => __( 'Data client ID', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the data-client-id given by Klarna for Klarna On-Site Messaging', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_enabled_product']  = array(
			'title'   => __( 'Enable/Disable the Product placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable/Disable the Product placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'default' => 'yes',
		);
		$settings['placement_data_key_product']        = array(
			'title'       => __( 'Product page placement data key', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement data key for the product page.', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_product_location'] = array(
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
		$settings['onsite_messaging_enabled_cart']  = array(
			'title'   => __( 'Enable/Disable the Cart placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable/Disable the Cart placement', 'klarna-onsite-messaging-for-woocommerce' ),
			'default' => 'yes',
		);
		$settings['placement_data_key_cart']        = array(
			'title'       => __( 'Cart page placement data key', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the placement data key for the cart page.', 'klarna-onsite-messaging-for-woocommerce' ),
			'default'     => '',
			'desc_tip'    => true,
		);
		$settings['onsite_messaging_cart_location'] = array(
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
		);
		$settings['onsite_messaging_theme_cart']    = array(
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
		);

		$settings['custom_product_page_widget_enabled'] = array(
			'title'   => __( 'Enable custom placement hook', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'    => 'checkbox',
			'default' => 'no',
		);

		$settings['custom_product_page_placement_hook'] = array(
			'title'    => __( 'Custom placement hook', 'klarna-onsite-messaging-for-woocommerce' ),
			'desc_tip' => __( 'Enter a custom hook where you want the OSM widget to be placed.', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'     => 'text',
			'default'  => 'woocommerce_single_product_summary',
		);

		$settings['custom_product_page_placement_priority'] = array(
			'title'    => __( 'Custom placement hook priority', 'klarna-onsite-messaging-for-woocommerce' ),
			'desc_tip' => __( 'Enter a priority for the custom hook where you want the OSM widget to be placed.', 'klarna-onsite-messaging-for-woocommerce' ),
			'type'     => 'number',
			'default'  => 35,
		);

		return $settings;
	}

	/**
	 * Load Klarna JS file asynchronously.
	 *
	 * @param string $tag The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 *
	 * @return string
	 */
	public function load_klarna_async( $tag, $handle ) {
		if ( 'klarna-onsite-messaging' !== $handle ) {
			return $tag;
		}
		$tag = str_replace( ' src', ' async src', $tag );
		$tag = str_replace( '></script>', ' data-client-id="' . apply_filters( 'kosm_data_client_id', $this->data_client_id ) . '"></script>', $tag );

		return $tag;
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
		} elseif ( class_exists( 'KCO' ) ) {
			return get_option( 'woocommerce_kco_settings' );
		}
	}

	/**
	 * Sets the placement id
	 *
	 * @return self
	 */
	private function set_data_client_id() {
		$settings             = self::get_settings();
		$this->data_client_id = '';
		if ( isset( $settings['data_client_id'] ) ) {
			$this->data_client_id = apply_filters( 'kosm_data_client_id', $settings['data_client_id'] );
		}
		return $this->data_client_id;
	}

	/**
	 * Enqueues scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$settings = self::get_settings();
		if ( empty( $settings ) ) {
			// If there are no settings, KP and KCO are most likely disabled.
			return;
		}

		$uci = '';

		if ( isset( $settings['onsite_messaging_uci'] ) ) {
			$uci = $settings['onsite_messaging_uci'];
		}

		$region        = 'eu-library';
		$base_location = wc_get_base_location();
		if ( is_array( $base_location ) && isset( $base_location['country'] ) ) {
			if ( in_array( $base_location['country'], array( 'US', 'CA' ) ) ) {
				$region = 'na-library';
			} elseif ( in_array( $base_location['country'], array( 'AU', 'NZ' ) ) ) {
				$region = 'oc-library';
			}
		}

		$region = apply_filters( 'kosm_region_library', $region );

		if ( 'yes' === $settings['testmode'] ) {
			$environment = 'playground.';
		} else {
			$environment = '';
		}
		global $post;

		if ( ! empty( $this->data_client_id ) ) {
			wp_register_script( 'klarna-onsite-messaging', 'https://' . $region . '.' . $environment . 'klarnaservices.com/lib.js', array( 'jquery' ), null, true );
		} elseif ( ! empty( $uci ) ) {
			wp_register_script( 'onsite_messaging_script', 'https://' . $region . '.' . $environment . 'klarnaservices.com/merchant.js?uci=' . $uci . '&country=' . wc_get_base_location()['country'], array( 'jquery' ), null );
		}

		wp_register_script( 'klarna_onsite_messaging', plugins_url( '/assets/js/klarna-onsite-messaging.js', __FILE__ ), array( 'jquery' ), WC_KLARNA_ONSITE_MESSAGING_VERSION );

		$localize = array(
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'get_cart_total_url' => WC_AJAX::get_endpoint( 'kosm_get_cart_total' ),
		);

		if ( isset( $_GET['osmDebug'] ) && '1' === $_GET['osmDebug'] ) {
			$localize['debug_info'] = array(
				'product'       => is_product(),
				'cart'          => is_cart(),
				'shortcode'     => ( ! empty( $post ) && has_shortcode( $post->post_content, 'onsite_messaging' ) ),
				'data_client'   => ! ( empty( $this->data_client_id ) ),
				'locale'        => kosm_get_locale_for_currency(),
				'currency'      => get_woocommerce_currency(),
				'library'       => ( wp_scripts() )->registered['klarna-onsite-messaging']->src ?? $region,
				'base_location' => $base_location['country'],
			);
		}

		wp_localize_script(
			'klarna_onsite_messaging',
			'klarna_onsite_messaging_params',
			$localize
		);

		if ( is_product() || is_cart() || ( ! empty( $post ) && has_shortcode( $post->post_content, 'onsite_messaging' ) ) ) {
			if ( ! empty( $this->data_client_id ) ) {
				wp_enqueue_script( 'klarna-onsite-messaging' );
			} elseif ( ! empty( $uci ) ) {
				wp_enqueue_script( 'onsite_messaging_script' );
			}
			wp_enqueue_script( 'klarna_onsite_messaging' );
		}
	}

	/**
	 * Checks the plugin version.
	 *
	 * @return void
	 */
	public function check_version() {
		require WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/kernl-update-checker/kernl-update-checker.php';

		$MyUpdateChecker = Puc_v4_FactoryKernl::buildUpdateChecker(
			'https://kernl.us/api/v1/updates/5c763fe5f22bcc016dd96618/',
			__FILE__,
			'klarna-onsite-messaging-for-woocommerce'
		);
	}

	/**
	 * Includes the plugin files.
	 *
	 * @return void
	 */
	public function include_files() {
		// Includes.
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/includes/klarna-onsite-messaging-functions.php';

		// Classes.
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-product-page.php';
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-cart-page.php';
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-shortcode.php';
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/class-klarna-onsite-messaging-widget.php';
		include_once WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/classes/admin/class-klarna-onsite-messaging-admin-notices.php';
	}

	/**
	 * Register Klarna On-Site Messaging widget.
	 */
	public function register_klarna_osm_widget() {
		register_widget( 'Klarna_OnSite_Messaging_Widget' );
	}

} new Klarna_OnSite_Messaging_For_WooCommerce();
