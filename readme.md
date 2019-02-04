# Klarna On-Site Marketing for WooCommerce plugin

**Requires either [Klarna Checkout for WooCommerce](https://woocommerce.com/products/klarna-checkout/) or [Klarna Payments for WooCommerce](https://woocommerce.com/products/klarna-payments/) to be installed**

#### Installation

To install this plugin you first need to have either Klarna Checkout for WooCommerce or Klarna Payments for WooCommerce installed. You can find the links to this above. You install this plugin just like any other WordPress plugin:

1. Download and unzip the latest release zip file.
2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
3. Upload the entire plugin directory to your /wp-content/plugins/ directory.
4. Activate the plugin through the ‘Plugins’ menu in WordPress Administration.

You also need two things from Klarna to integrate On-Site Marketing. The placement ID and UCI. Both of these you can get from your merchant portal. You can find more information about that [here](https://developers.klarna.com/en/gb/kco-v3/on-site-messaging/getting-started).

Then go to the settings for either the Klarna Checkout or Klarna Payments gateway ( WooCommerce –> Settings –> Payments ). At the bottom of these pages you should now see a section called *Klarna upstream*. Here you can enter the Placement IDs and your UCI.

Now when you go to either your product or cart page you should be seeing the widget that you settup in your merchant portal.

#### Changing widget location.

You can change the location of the individual widget by using the following filters:

* *klarna_upstream_product_target* for the Product page.
* *klarna_upstream_cart_target* for the Cart page.

##### Code example
	<?php>
	add_filter( 'klarna_upstream_cart_target', 'change_klarna_upstream_cart_location' );
	function change_klarna_upstream_cart_location( $location ) {
		// Here you need to replace the $location variable with the action that you want to use instead of the default.
		// You can also add custom checks to apply different locations depending on your needs.
		$location = 'woocommerce_after_cart';
		return $location;
	}

These can be used with any action that you want. You can find a list of all WooCommerce actions and where they operate [here](https://docs.woocommerce.com/wc-apidocs/hook-docs.html)

**We do not support individual themes. If you want to change the appearance of the widget outside of what we can do with the filter then this requires custom CSS code. Use the classes *klarna-upstream-product* and *klarna-upstream-cart* respectively.**