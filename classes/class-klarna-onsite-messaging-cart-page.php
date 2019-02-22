<?php
/**
 * Class file for Klarna On-Site Messaging cart page.
 *
 * @package Klarna_OnSite_Messaging/Classes
 */

/**
 * On-Site Messaging cart page class.
 */
class Klarna_OnSite_Messaging_Cart_Page {
	/**
	 * Placement id
	 *
	 * @var string
	 */
	public $placement_id;

	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( $this->is_enabled() ) {
			$this->set_placement_id();
			$settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
			$target   = apply_filters( 'klarna_onsite_messaging_cart_target', ( isset( $settings['onsite_messaging_cart_location'] ) ? $settings['onsite_messaging_cart_location'] : 'woocommerce_cart_collaterals' ) );
			$priority = apply_filters( 'klarna_onsite_messaging_cart_priority', '5' );
			add_action( $target, array( $this, 'add_iframe' ), $priority );
		}
	}

	/**
	 * Checks if the placement is enabled
	 *
	 * @return boolean
	 */
	public function is_enabled() {
		$settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
		if ( ! isset( $settings['onsite_messaging_enabled_cart'] ) || 'yes' === $settings['onsite_messaging_enabled_cart'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Sets the placement id
	 *
	 * @return self
	 */
	private function set_placement_id() {
		$settings           = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
		$this->placement_id = $settings['onsite_messaging_placement_id_cart'];
		return $this->placement_id;
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		$total = WC()->cart->get_total( 'klarna_onsite_messaging' );
		?>
		<klarna-placement class="klarna-onsite-messaging-cart" data-id="<?php echo $this->placement_id; // phpcs: ignore. ?>" data-total_amount="<?php echo $total; // phpcs: ignore. ?>"></klarna-placement>
		<?php
	}
} new Klarna_OnSite_Messaging_Cart_Page();
