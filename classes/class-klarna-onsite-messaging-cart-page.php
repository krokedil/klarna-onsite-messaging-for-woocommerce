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
	 * Settings
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
		add_action( 'wp_head', array( $this, 'init_class' ) );
	}

	/**
	 * Initiates the class
	 *
	 * @return void
	 */
	public function init_class() {
		if ( $this->is_enabled() ) {
			$this->set_placement_id();
			$this->set_theme();
			$target   = apply_filters( 'klarna_onsite_messaging_cart_target', ( isset( $this->settings['onsite_messaging_cart_location'] ) ? $this->settings['onsite_messaging_cart_location'] : 'woocommerce_cart_collaterals' ) );
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
		$this->settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
		if ( ! isset( $this->settings['onsite_messaging_enabled_cart'] ) || 'yes' === $this->settings['onsite_messaging_enabled_cart'] ) {
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
		$this->settings     = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
		$this->placement_id = $this->settings['onsite_messaging_placement_id_cart'];
		return $this->placement_id;
	}

	/**
	 * Sets the iFrame theme from settings.
	 *
	 * @return void
	 */
	private function set_theme() {
		if ( isset( $this->settings['onsite_messaging_theme_cart'] ) ) {
			$this->theme = $this->settings['onsite_messaging_theme_cart'];
		} else {
			$this->theme = '';
		}
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		$total = WC()->cart->get_total( 'klarna_onsite_messaging' );
		?>
		<klarna-placement class="klarna-onsite-messaging-cart" data-theme="<?php echo $this->theme; ?>" data-id="<?php echo $this->placement_id; // phpcs: ignore. ?>" data-total_amount="<?php echo $total; // phpcs: ignore. ?>"></klarna-placement>
		<?php
	}
} new Klarna_OnSite_Messaging_Cart_Page();
