<?php
/**
 * Upstream product page class.
 */
class Klarna_Upstream_Product_Page {
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
		add_action( apply_filters( 'klarna_upstream_product_target', 'woocommerce_product_meta_end' ), array( $this, 'add_iframe' ), 5 );
	}

	/**
	 * Sets the placement id
	 *
	 * @return self
	 */
	private function set_placement_id() {
		$settings           = Klarna_Upstream_For_WooCommerce::get_settings();
		$this->placement_id = $settings['upstream_placement_id_product'];
		return $this->placement_id;
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		global $product;
		if ( $product->is_type( 'variable' ) ) {
			$price = 0;
		} else {
			$price = $product->get_price( 'klarna_upstream' );
		}
		?>
		<klarna-placement data-id="<?php echo $this->placement_id; ?>" data-total_amount="<?php echo $price; ?>"></klarna-placement>
		<?php
	}
} new Klarna_Upstream_Product_Page();
