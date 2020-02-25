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
	 * Checks if KCO gateway is enabled.
	 *
	 * @var $enabled
	 */
	protected $enabled;

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
		$this->enabled = $this->settings['enabled'];

		add_action( 'admin_init', array( $this, 'check_settings' ) );
    }

	/**
	 * Checks the settings.
	 */
	public function check_settings() {
		if ( ! empty( $_POST ) ) {
			add_action( 'woocommerce_settings_saved', array( $this, 'check_uci' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'check_uci' ) );
		}
	}

	/**
	 * Check if UCI field exist.
	 */
	public function check_uci() {
		if ( isset( $this->settings['onsite_messaging_uci'] ) && ! empty( $this->settings['onsite_messaging_uci'] ) ) {
			?>
			<div class="kco-message notice woocommerce-message notice-error">
			<?php echo wp_kses_post( wpautop( '<p>' . __( 'Klarna On-site Messaging has a new way to be implemented on your site. Please visit the plugin settings page for more information.', 'klarna-checkout-for-woocommerce' ) . '</p>' ) ); ?>
			</div>
			<?php
		}
	}

}

Klarna_OnSite_Messaging_Admin_Notices::get_instance();
