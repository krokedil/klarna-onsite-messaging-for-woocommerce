<?php
/**
 * Plugin function file.
 *
 * @package Klarna_OnSite_Messaging_For_WooCommerce/Includes
 */

/**
 * Gets the locale need for the klarna country.
 *
 * @param string $klarna_country Klarna country.
 * @return string
 */
function kosm_get_locale_for_klarna_country( $klarna_country ) {
	$has_english_locale = 'en_US' === get_locale() || 'en_GB' === get_locale();
	switch ( $klarna_country ) {
		case 'AT':
			if ( $has_english_locale ) {
				$klarna_locale = 'en-AT';
			} else {
				$klarna_locale = 'de-AT';
			}
			break;
		case 'AU':
			$klarna_locale = 'en-AU';
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
			if ( 'fr_ca' === strtolower( get_locale() ) ) {
				$klarna_locale = 'fr-CA';
			} else {
				$klarna_locale = 'en-CA';
			}
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
		case 'FR':
			if ( $has_english_locale ) {
				$klarna_locale = 'en-FR';
			} else {
				$klarna_locale = 'fr-FR';
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
				$klarna_locale = 'no-NO';
			}
			break;
		case 'NZ':
			$klarna_locale = 'en-NZ';
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
 * Gets the locale needed for the specified currency.
 *
 * @return string
 */
function kosm_get_locale_for_currency() {
	$wp_locale        = get_locale();
	$currency         = get_woocommerce_currency();
	$customer_country = ( method_exists( WC()->customer, 'get_billing_country' ) ) ? WC()->customer->get_billing_country() : null;
	switch ( $currency ) {
		case 'EUR': // Euro.
			$locale = kosm_process_eur_currency( $customer_country, $wp_locale );
			break;
		case 'AUD': // Australian Dollars.
			$locale = 'en-AU';
			break;
		case 'CAD': // Canadian Dollar.
			$locale = ( 'fr_CA' === $wp_locale ) ? 'fr-CA' : 'en-CA';
			break;
		case 'CHF': // Swiss Frank.
			$locale = ( 'de_CH' === $wp_locale || 'de_CH_informal' === $wp_locale ) ? 'de-CH' : 'en-CH';
			break;
		case 'DKK': // Danish Kronor.
			$locale = ( 'da_DK' === $wp_locale ) ? 'da-DK' : 'en-DK';
			break;
		case 'GBP': // Pounds.
			$locale = 'en-GB';
			break;
		case 'NOK': // Norwegian Kronor.
			$locale = ( 'nn_NO' === $wp_locale || 'nb_NO' === $wp_locale ) ? 'no-NO' : 'en-NO';
			break;
		case 'SEK': // Swedish Kronor.
			$locale = ( 'sv_SE' === $wp_locale ) ? 'sv-SE' : 'en-SE';
			break;
		case 'USD': // Dollars.
			$locale = 'en-US';
			break;
		case 'NZD': // New Zealand Dollars.
			$locale = 'en-NZ';
			break;
		default:
			$locale = 'en-US';
	}
	return $locale;
}

/**
 * Processes the Euro countries to get the locale.
 *
 * @param string $customer_country The Customer country.
 * @param string $wp_locale The WordPress locale.
 * @return string
 */
function kosm_process_eur_currency( $customer_country, $wp_locale ) {
	$default_euro_locale = apply_filters( 'kosm_default_euro_locale', 'en-DE' );

	// If we are forcing the euro locale, then return the locale directly.
	$force_euro_locale = apply_filters( 'kosm_force_euro_locale', false );
	if ( $force_euro_locale ) {
		return $default_euro_locale;
	}

	switch ( $customer_country ) {
		case 'AT': // Austria.
			$locale = ( 'de_AT' === $wp_locale ) ? 'de-AT' : 'en-AT';
			break;
		case 'BE': // Belgium.
			if ( 'fr_BE' === $wp_locale ) {
				$locale = 'fr-BE';
			} elseif ( 'nl_BE' === $wp_locale ) {
				$locale = 'nl-BE';
			} else {
				$locale = 'en-BE';
			}
			break;
		case 'DE': // Germany.
			$locale = ( 'de_DE' === $wp_locale || 'de_DE_formal' === $wp_locale ) ? 'de-DE' : 'en-DE';
			break;
		case 'ES': // Spain.
			$locale = ( 'es_ES' === $wp_locale ) ? 'es-ES' : 'en-ES';
			break;
		case 'FI': // Finland.
			if ( 'fi' === $wp_locale ) {
				$locale = 'fi-FI';
			} elseif ( 'sv_SE' === $wp_locale ) {
				$locale = 'sv-FI';
			} else {
				$locale = 'en-FI';
			}
			break;
		case 'FR': // France.
			$locale = ( 'fr_FR' === $wp_locale ) ? 'fr-FR' : 'en-FR';
			break;
		case 'IT': // Italy.
			$locale = ( 'it_IT' === $wp_locale ) ? 'it-IT' : 'en-IT';
			break;
		case 'NL': // Netherlands.
			$locale = ( 'nl_NL' === $wp_locale ) ? 'nl-NL' : 'en-NL';
			break;
		default:
			$locale = $default_euro_locale;
			break;
	}

	return $locale;
}

/**
 * Gets country for Klarna purchase.
 *
 * @return string
 */
function kosm_get_purchase_country() {
	// Try to use customer country if available.
	if ( ! empty( WC()->customer->get_billing_country() ) && strlen( WC()->customer->get_billing_country() ) === 2 ) {
		return WC()->customer->get_billing_country( 'edit' );
	}

	$base_location = wc_get_base_location();
	$country       = $base_location['country'];

	return $country;
}

/**
 * Returns the HTML of a klarna-placement tag.
 *
 * @param array $args The arguments.
 * @return void
 */
function kosm_klarna_placement( $args ) {
	$key             = $args['data-key'];
	$theme           = $args['data-theme'];
	$purchase_amount = $args['data-purchase-amount'];
	$locale          = kosm_get_locale_for_currency();
	?>
	<klarna-placement
		data-key="<?php echo esc_html( $key ); ?>"
		data-locale="<?php echo esc_html( $locale ); ?>"
		data-preloaded="true"
		<?php echo ( ! empty( $theme ) ) ? esc_html( "data-theme=$theme" ) : ''; ?>
		<?php echo ( ! empty( $purchase_amount ) ) ? esc_html( "data-purchase-amount=$purchase_amount" ) : ''; ?>
	></klarna-placement>
	<?php
}
