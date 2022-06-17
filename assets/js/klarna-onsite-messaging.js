jQuery( function($) {
	var klarna_onsite_messaging = {
		update_iframe: function() {
			window.kudt = window.kudt || [];
			window.kudt.push({eventName: 'refresh-placements'});
			$('klarna-placement').show();
		},

		check_variable: function() {
			if ($('.product-type-variable')[0] && $('.variation_id').val() === '0'){
				// Product is a variable product and variable is not set
				return false;
			}
			return true;
		},

		update_total_cart: function() {
			let price = Math.round($("#kosm_cart_total").val() * 100);
			klarna_onsite_messaging.update_total_price(price)
		},

		update_total_variation: function( variation ) {
			let price = Math.round(variation.display_price * 100);
			klarna_onsite_messaging.update_total_price(price);
		},

		update_total_price: function (price) {
			/* If the price is undefined, OSM won't appear. Let this be known. */
			if (!price) {
				console.warn('OSM price error: ', price)
			}

			$("klarna-placement").each( function() {
				$( this ).attr("data-total_amount", price );
				$( this ).attr("data-purchase-amount", price );
			});

			klarna_onsite_messaging.update_iframe();
		},

		debug_info: function()
		{
			if(/[?&]osmDebug=1/.test(location.search))
			{
				const d = klarna_onsite_messaging_params.debug_info;
				
				if(typeof d !== 'undefined')
				{
					console.log('%cDebug info: ', 'color: #ff0000');

					if(typeof d.product !== 'undefined' && d.product)
					{
						console.log('Page has Product OSM.');
					}

					if(typeof d.cart !== 'undefined' && d.cart)
					{
						console.log('Page has Cart OSM.');
					}

					if(typeof d.shortcode !== 'undefined' && d.shortcode)
					{
						console.log('Page has Shortcode OSM.');
					}

					if(typeof d.locale !== 'undefined')
					{
						console.log('Locale: ' + d.locale);
					}

					if(typeof d.currency !== 'undefined')
					{
						console.log('Currency: ' + d.currency);
					}

					if(typeof d.data_client !== 'undefined')
					{
						console.log((!d.data_client) ? 'Data client is missing.' : 'Data client exists.');
					}

					if(typeof window.KlarnaOnsiteService !== 'undefined' && typeof window.KlarnaOnsiteService.loaded !== 'undefined')
					{
						console.log('Klarna loaded status: ' + window.KlarnaOnsiteService.loaded);
					}
				}
			}
		},

		init: function() {
			$(document).ready( function() {
				if( false === klarna_onsite_messaging.check_variable ) {
					$('klarna-placement').hide();
				} else {
					$('klarna-placement').show();
				}

				klarna_onsite_messaging.debug_info();
			});
			
			$(document.body).on("updated_cart_totals", function () {
				klarna_onsite_messaging.update_total_cart();
			});
			$(document).on( 'found_variation', function( e, variation ) {
				klarna_onsite_messaging.update_total_variation( variation );
			});

			// "WooCommerce Measurement Price Calculator".
			$('.product_price').on('wc-measurement-price-calculator-product-price-change', function (e, measurement, price) {
				if (price) {
					klarna_onsite_messaging.update_total_price(price * 100)
				}
			});
		},
	}
	klarna_onsite_messaging.init();
});