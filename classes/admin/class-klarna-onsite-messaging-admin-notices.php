<?php
/**
 * Admin notice class file.
 *
 * @package Klarna_OnSite_Messaging_For_WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Returns error messages depending on
 *
 * @class    Klarna_OnSite_Messaging_Admin_Notices
 * @version  1.0
 * @package  Klarna_OnSite_Messaging_For_WooCommerce/Classes
 * @category Class
 * @author   Krokedil
 */
class Klarna_OnSite_Messaging_Admin_Notices {

	/**
	 * The reference the *Singleton* instance of this class.
	 *
	 * @var $instance
	 */
	protected static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return self::$instance The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Klarna_OnSite_Messaging_For_WooCommerce constructor.
	 */
	public function __construct() {
		$this->settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();

		add_action( 'admin_init', array( $this, 'check_settings' ) );
	}

	/**
	 * Checks the settings.
	 */
	public function check_settings() {
		if ( ! empty( $_POST ) ) { // phpcs:ignore
			add_action( 'woocommerce_settings_saved', array( $this, 'check_uci' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'check_uci' ) );
		}
	}

	/**
	 * Check if UCI field exist.
	 */
	public function check_uci() {
		if ( isset( $this->settings['onsite_messaging_uci'] ) && empty( $this->settings['data_client_id'] ) ) {
			$gateway_id = '';
			if ( class_exists( 'WC_Klarna_Payments' ) ) {
				$gateway_id = 'klarna_payments';
			} elseif ( class_exists( 'Klarna_Checkout_For_WooCommerce' ) ) {
				$gateway_id = 'kco';
			} elseif ( class_exists( 'KCO' ) ) {
				$gateway_id = 'kco';
			}
			?>
			<div class="kco-message notice woocommerce-message notice-error">
			<?php
			echo wp_kses_post(
				wpautop(
					'<p>' .
					sprintf(
						// translators: Link to Klarna settings.
						__(
							'Klarna On-site Messaging has a new way to be implemented on your site. Please visit the <a href="%s">plugin settings page</a> for more information.',
							'klarna-checkout-for-woocommerce'
						),
						admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $gateway_id )
					) . '</p>'
				)
			);
			?>
			</div>
			<?php
		}
	}

}

Klarna_OnSite_Messaging_Admin_Notices::get_instance();
