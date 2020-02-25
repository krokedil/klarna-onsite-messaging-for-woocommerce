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
			$this->set_data_key();
			$this->set_data_client_id();
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
		$store_base_location = apply_filters( 'klarna_onsite_messaging_store_location', wc_get_base_location()['country'] );
		$customer_location   = apply_filters( 'klarna_onsite_messaging_customer_location', WC()->customer->get_country() );
		if ( $store_base_location == $customer_location ) {
			$this->settings = Klarna_OnSite_Messaging_For_WooCommerce::get_settings();
			if ( ! isset( $this->settings['onsite_messaging_enabled_cart'] ) || 'yes' === $this->settings['onsite_messaging_enabled_cart'] ) {
				return true;
			}
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
		$this->placement_id = $this->settings['onsite_messaging_placement_id_cart'];
		return $this->placement_id;
	}

	/**
	 * Sets the data client id
	 *
	 * @return self
	 */
	private function set_data_client_id() {
		$this->data_client_id      = '';
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
			$this->theme = 'data-theme="' . $this->settings['onsite_messaging_theme_cart'] . '"';
		} elseif ( isset( $this->settings['onsite_messaging_theme_cart'] ) && 'none' === $this->settings['onsite_messaging_theme_cart'] ) {
			$this->theme = '';
		} else {
			$this->theme = 'data-theme="default"';
		}
	}

	/**
	 * Gets the locale need for the klarna country.
	 *
	 * @param string $klarna_country Klarna country.
	 * @return string
	 */
	public function get_locale_for_klarna_country( $klarna_country ) {
		$has_english_locale = 'en_US' === get_locale() || 'en_GB' === get_locale();
		switch ( $klarna_country ) {
			case 'AT':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-AT';
				} else {
					$klarna_locale = 'de-AT';
				}
				break;
			case 'BE':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-BE';
				} elseif ( 'fr_be' === strtolower( get_locale() ) ) {
					$klarna_locale = 'fr-BE';
				} else {
					$klarna_locale = 'nl-BE';
				}
				break;
			case 'CA':
				$klarna_locale = 'en-CA';
				break;
			case 'CH':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-CH';
				} else {
					$klarna_locale = 'de-CH';
				}
				break;
			case 'DE':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-DE';
				} else {
					$klarna_locale = 'de-DE';
				}
				break;
			case 'DK':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-DK';
				} else {
					$klarna_locale = 'da-DK';
				}
				break;
			case 'ES':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-ES';
				} else {
					$klarna_locale = 'es-ES';
				}
				break;
			case 'FI':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-FI';
				} elseif ( 'sv_se' === strtolower( get_locale() ) ) {
					$klarna_locale = 'sv-FI';
				} else {
					$klarna_locale = 'fi-FI';
				}
				break;
			case 'IT':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-IT';
				} else {
					$klarna_locale = 'it-IT';
				}
				break;
			case 'NL':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-NL';
				} else {
					$klarna_locale = 'nl-NL';
				}
				break;
			case 'NO':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-NO';
				} else {
					$klarna_locale = 'nb-NO';
				}
				break;
			case 'PL':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-PL';
				} else {
					$klarna_locale = 'pl-PL';
				}
				break;
			case 'SE':
				if ( $has_english_locale ) {
					$klarna_locale = 'en-SE';
				} else {
					$klarna_locale = 'sv-SE';
				}
				break;
			case 'GB':
				$klarna_locale = 'en-GB';
				break;
			case 'US':
				$klarna_locale = 'en-US';
				break;
			default:
				$klarna_locale = 'en-US';
		}
		return $klarna_locale;
	}

	/**
	 * Gets country for Klarna purchase.
	 *
	 * @return string
	 */
	public function get_purchase_country() {
		// Try to use customer country if available.
		if ( ! empty( WC()->customer->get_billing_country() ) && strlen( WC()->customer->get_billing_country() ) === 2 ) {
			return WC()->customer->get_billing_country( 'edit' );
		}

		$base_location = wc_get_base_location();
		$country       = $base_location['country'];

		return $country;
	}

	/**
	 * Adds the iframe to the page.
	 *
	 * @return void
	 */
	public function add_iframe() {
		$total = WC()->cart->get_total( 'klarna_onsite_messaging' ) * 100;
		$locale = $this->get_locale_for_klarna_country( $this->get_purchase_country() );
		if ( ! empty( $this->data_client_id ) ) {
			?>
				<klarna-placement class="klarna-onsite-messaging-cart" 
					<?php echo $this->theme; ?> 
					data-key="<?php echo $this->data_key; // phpcs: ignore. ?>" 
					data-purchase-amount="<?php echo $total; // phpcs: ignore. ?>"
					data-locale="<?php echo $locale; ?>"
					data-preloaded="true"
				></klarna-placement>

				<script>
					window.kudt = window.kudt || [];
					window.kudt.push({
					eventName: 'refresh-placements'
					});
				</script>

				<script id="rendered-js">
					function uuidv4() {
					return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
						var r = Math.random() * 16 | 0,v = c == 'x' ? r : r & 0x3 | 0x8;
						return v.toString(16);
					});
					}
					document.cookie = `ku1-sid=test-session; ku1-vid=${uuidv4()}`;
					//# sourceURL=pen.js
				</script>
			<?php
		} else {
			?>
			<klarna-placement class="klarna-onsite-messaging-cart" <?php echo $this->theme; ?> data-id="<?php echo $this->placement_id; // phpcs: ignore. ?>" data-total_amount="<?php echo $total; // phpcs: ignore. ?>"></klarna-placement>
			<?php
		}
	}
}
new Klarna_OnSite_Messaging_Cart_Page();
