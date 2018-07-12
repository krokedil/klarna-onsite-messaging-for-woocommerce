<?php
/**
 * Upstream cart page class.
 */
class Klarna_Upstream_Cart_Page {
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
		add_action( apply_filters( 'klarna_upstream_cart_target', 'woocommerce_after_cart_totals' ), array( $this, 'add_iframe' ), 5 );
	}

	/**
	 * Sets the placement id
	 *
	 * @return self
	 */
	private function set_placement_id() {
		$settings           = Klarna_Upstream_For_WooCommerce::get_settings();
		$this->placement_id = $settings['upstream_placement_id_cart'];
		return $this->placement_id;
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		$total = WC()->cart->get_total( 'klarna_upstream' );
		?>
		<klarna-placement data-id="<?php echo $this->placement_id; ?>" data-total_amount="<?php echo $total; ?>"></klarna-placement>
		<?php
	}
} new Klarna_Upstream_Cart_Page();
