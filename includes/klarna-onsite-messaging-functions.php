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
function kosm_get_purchase_country() {
    // Try to use customer country if available.
    if ( ! empty( WC()->customer->get_billing_country() ) && strlen( WC()->customer->get_billing_country() ) === 2 ) {
        return WC()->customer->get_billing_country( 'edit' );
    }

    $base_location = wc_get_base_location();
    $country       = $base_location['country'];

    return $country;
}