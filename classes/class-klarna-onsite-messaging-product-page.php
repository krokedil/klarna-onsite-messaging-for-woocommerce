<?php
/**
 * Class file for Klarna On-Site Messaging product page.
 *
 * @package Klarna_OnSite_Messaging/Classes
 */

/**
 * On-Site Messaging product page class.
 */
class Klarna_OnSite_Messaging_Product_Page {
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
		if ( $this->is_enabled() && is_product() ) {
			$this->set_placement_id();
			$this->set_data_key();
			$this->set_data_client_id();
			$this->set_theme();
			$target   = apply_filters( 'klarna_onsite_messaging_product_target', 'woocommerce_single_product_summary' );
			$priority = apply_filters( 'klarna_onsite_messaging_product_priority', ( isset( $this->settings['onsite_messaging_product_location'] ) ? $this->settings['onsite_messaging_product_location'] : '45' ) );
			add_action( $target, array( $this, 'add_iframe' ), $priority );
		}
	}

	/**
	 * Sets the placement id
	 *
	 * @return self
	 */
	private function set_data_key() {
		$this->data_key = $this->settings['placement_data_key_product'];
		return $this->data_key;
	}

	/**
	 * Sets the data key
	 *
	 * @return self
	 */
	private function set_placement_id() {
		$this->placement_id = isset( $this->settings['onsite_messaging_placement_id_product'] ) ? $this->settings['onsite_messaging_placement_id_product'] : '';
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
		if ( isset( $this->settings['onsite_messaging_theme_product'] ) && 'none' !== $this->settings['onsite_messaging_theme_product'] ) {
			$this->theme = $this->settings['onsite_messaging_theme_product'];
		} elseif ( isset( $this->settings['onsite_messaging_theme_product'] ) && 'none' === $this->settings['onsite_messaging_theme_product'] ) {
			$this->theme = '';
		} else {
			$this->theme = 'default';
		}
	}

	/**
	 * Checks if the placement is enabled
	 *
	 * @return boolean
	 */
	public function is_enabled() {
		if ( ! isset( $this->settings['onsite_messaging_enabled_product'] ) || 'yes' === $this->settings['onsite_messaging_enabled_product'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		global $product;
		if ( $product->is_type( 'variable' ) ) {
			$price = $product->get_variation_price( 'min' ) * 100;
		} else {
			$price = wc_get_price_to_display( $product ) * 100;
		}

		$locale = kosm_get_locale_for_currency();
		if ( ! empty( $this->data_client_id ) ) {
			$args = array(
				'data-key'             => $this->data_key,
				'data-purchase-amount' => $price,
				'data-theme'           => $this->theme,
			);
			kosm_klarna_placement( $args );
		} else {
			?>
			<klarna-placement 
				class="klarna-onsite-messaging-product" 
				<?php echo ( ! empty( $this->theme ) ) ? esc_html( "data-theme=$this->theme" ) : ''; ?> 
				data-id="<?php echo esc_html( $this->placement_id ); ?>"
				data-total_amount="<?php echo esc_html( $price ); ?>"
				></klarna-placement>
			<?php
		}
	}
} new Klarna_OnSite_Messaging_Product_Page();
