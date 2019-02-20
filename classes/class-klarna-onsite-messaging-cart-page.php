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
		$this->set_placement_id();
		add_action( apply_filters( 'klarna_onsite_messaging_cart_target', 'woocommerce_after_cart_totals' ), array( $this, 'add_iframe' ), 5 );
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
