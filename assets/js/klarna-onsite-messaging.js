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
			let price = Math.round( $("#kosm_cart_total").val() * 100 );
			$("klarna-placement").each( function() {
				$( this ).attr("data-total_amount", price );
				$( this ).attr("data-purchase-amount", price );
			});
			klarna_onsite_messaging.update_iframe();
		},

		update_total_variation: function( variation ) {
			let price = Math.round( variation.display_price * 100 );
			$("klarna-placement").each( function() {
				$( this ).attr("data-total_amount", price );
				$( this ).attr("data-purchase-amount", price );
			});
			klarna_onsite_messaging.update_iframe();
		},

		init: function() {
			$(document).ready( function() {
				if( false === klarna_onsite_messaging.check_variable ) {
					$('klarna-placement').hide();
				} else {
					$('klarna-placement').show();
				}
			});
			
			$(document.body).on("updated_cart_totals", function () {
				klarna_onsite_messaging.update_total_cart();
			});
			$(document).on( 'found_variation', function( e, variation ) {
				klarna_onsite_messaging.update_total_variation( variation );
			});
		}
	}
	klarna_onsite_messaging.init();
});