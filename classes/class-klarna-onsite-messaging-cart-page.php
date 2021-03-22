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
		if ( $this->is_enabled() && is_cart() ) {
			$this->set_placement_id();
			$this->set_data_key();
			$this->set_data_client_id();
			$this->set_theme();
			$target   = apply_filters( 'klarna_onsite_messaging_cart_target', ( isset( $this->settings['onsite_messaging_cart_location'] ) ? $this->settings['onsite_messaging_cart_location'] : 'woocommerce_cart_collaterals' ) );
			$priority = apply_filters( 'klarna_onsite_messaging_cart_priority', '5' );
			add_action( $target, array( $this, 'add_iframe' ), $priority );
			add_action( 'woocommerce_cart_totals_after_order_total', array( $this, 'add_cart_total_input' ) );
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
	private function set_data_key() {
		$this->data_key = $this->settings['placement_data_key_cart'];
		return $this->data_key;
	}

	/**
	 * Sets the data key
	 *
	 * @return self
	 */
	private function set_placement_id() {
		$this->placement_id = isset( $this->settings['onsite_messaging_placement_id_cart'] ) ? $this->settings['onsite_messaging_placement_id_cart'] : '';
		return $this->placement_id;
	}

	/**
	 * Sets the data client id
	 *
	 * @return self
	 */
	private function set_data_client_id() {
		$this->data_client_id = '';
		if ( isset( $this->settings['data_client_id'] ) ) {
			$this->data_client_id = $this->settings['data_client_id'];
		}
		return $this->data_client_id;
	}

	/**
	 * Sets the iFrame theme from settings.
	 *
	 * @return void
	 */
	private function set_theme() {
		if ( isset( $this->settings['onsite_messaging_theme_cart'] ) && 'none' !== $this->settings['onsite_messaging_theme_cart'] ) {
			$this->theme = $this->settings['onsite_messaging_theme_cart'];
		} elseif ( isset( $this->settings['onsite_messaging_theme_cart'] ) && 'none' === $this->settings['onsite_messaging_theme_cart'] ) {
			$this->theme = '';
		} else {
			$this->theme = 'default';
		}
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		$total = WC()->cart->get_total( 'klarna_onsite_messaging' ) * 100;
		if ( ! empty( $this->data_client_id ) ) {
			$args = array(
				'data-key'             => $this->data_key,
				'data-purchase-amount' => $total,
				'data-theme'           => $this->theme,
			);
			kosm_klarna_placement( $args );
		} else {
			?>
			<klarna-placement 
				class="klarna-onsite-messaging-cart" 
				<?php echo ( ! empty( $this->theme ) ) ? esc_html( "data-theme=$this->theme" ) : ''; ?> 
				data-id="<?php echo esc_html( $this->placement_id ); ?>"
				data-total_amount="<?php echo esc_html( $total ); ?>"
				></klarna-placement>
			<?php
		}
	}

	/**
	 * Add a hidden input field with the cart totals.
	 *
	 * @return void
	 */
	public function add_cart_total_input() {
		?>
		<input type="hidden" id="kosm_cart_total" name="kosm_cart_total" value="<?php echo esc_html( WC()->cart->get_total( 'klarna_onsite_messaging' ) ); ?>">
		<?php
	}
}
new Klarna_OnSite_Messaging_Cart_Page();
