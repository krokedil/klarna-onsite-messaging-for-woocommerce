jQuery( function($) {
	var klarna_upstream = {
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

		update_total: function( variable_id ) {
			if( $('.variation_id').val() !== '' ) {
				var variables        = $('form.variations_form').data('product_variations');
				var price;
				var i;
				for (i = 0; i < variables.length; i++) {
					if( variables[i].variation_id == variable_id ) {
						price = variables[i].display_price;
					}
				}
				document.getElementsByTagName("klarna-placement")[0].setAttribute("data-total_amount", price);
				klarna_upstream.update_iframe();
			} else {
				$('klarna-placement').hide();			
			}
		}
	}

	$(document.body).on("updated_cart_totals", function () {
		klarna_upstream.update_iframe();
	});
	$(document).ready( function() {
		if( false === klarna_upstream.check_variable ) {
			$('klarna-placement').hide();
		} else {
			$('klarna-placement').show();
		}
	});
	$(document).on('change', "input[name='variation_id']", function(){
		klarna_upstream.update_total( $('input[name="variation_id"]').val() );
	});
});