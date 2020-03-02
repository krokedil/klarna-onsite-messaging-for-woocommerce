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

		update_total: function( variation ) {
			let price = Math.round( variation.display_price * 100 );
			document.getElementsByTagName("klarna-placement")[0].setAttribute("data-total_amount", price );
			document.getElementsByTagName("klarna-placement")[0].setAttribute("data-purchase-amount", price );
			klarna_onsite_messaging.update_iframe();
		}
	}

	$(document.body).on("updated_cart_totals", function () {
		klarna_onsite_messaging.update_iframe();
	});
	$(document).ready( function() {
		if( false === klarna_onsite_messaging.check_variable ) {
			$('klarna-placement').hide();
		} else {
			$('klarna-placement').show();
		}
	});

	$(document).on( 'found_variation', function( e, variation ) {
		klarna_onsite_messaging.update_total( variation );
	});
});