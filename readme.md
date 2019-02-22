# Klarna On-Site Messaging for WooCommerce plugin

**Requires either [Klarna Checkout for WooCommerce](https://woocommerce.com/products/klarna-checkout/) or [Klarna Payments for WooCommerce](https://woocommerce.com/products/klarna-payments/) to be installed**

#### Installation

To install this plugin you first need to have either Klarna Checkout for WooCommerce or Klarna Payments for WooCommerce installed. You can find the links to this above. You install this plugin just like any other WordPress plugin:

1. Download and unzip the latest release zip file.
2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
3. Upload the entire plugin directory to your /wp-content/plugins/ directory.
4. Activate the plugin through the ‘Plugins’ menu in WordPress Administration.

You also need two things from Klarna to integrate On-Site Messaging. The placement ID and UCI. Both of these you can get from your merchant portal. You can find more information about that [here](https://developers.klarna.com/en/gb/kco-v3/on-site-messaging/getting-started).

Then go to the settings for either the Klarna Checkout or Klarna Payments gateway ( WooCommerce –> Settings –> Payments ). At the bottom of these pages you should now see a section called *Klarna On-Site Messaging*. Here you can enter the Placement IDs and your UCI.

Now when you go to either your product or cart page you should be seeing the widget that you settup in your merchant portal.

#### Changing widget location.
In your Klarna settings there is a select option to select where on the page you want to display the widgets.

You can also change the location of the individual widget by using the following filters:

* *klarna_onsite_messaging_product_target* for the Product page placement action.
* *klarna_onsite_messaging_product_priority* for the Product page priority.
* *klarna_onsite_messaging_cart_target* for the Cart page placement action.
* *klarna_onsite_messaging_cart_priority* for the Cart page priority.

##### Code example
```
<?php
// Change the placement action for the widget.
add_filter( 'klarna_onsite_messaging_cart_target', 'change_klarna_onsite_messaging_cart_location' );
function change_klarna_onsite_messaging_cart_location( $location ) {
    // Here you need to replace the $location variable with the action that you want to use instead of the default.
    // You can also add custom checks to apply different locations depending on your needs.
    $location = 'woocommerce_after_cart';
    return $location;
}

// Change the priority for the action for the widget.
add_filter( 'klarna_onsite_messaging_cart_priority', 'change_klarna_onsite_messaging_cart_priority );
function change_klarna_onsite_messaging_cart_priority( $priority ) {
	// Here you need to replace the $priority variable with the action that you want to use instead of the default.
    // You can also add custom checks to apply different priority depending on your needs.
    $priority = '10';
    return $priority;
}
```
These can be used with any action that you want. You can find a list of all WooCommerce actions and where they operate [here](https://docs.woocommerce.com/wc-apidocs/hook-docs.html)

**We do not support individual themes. If you want to change the appearance of the widget outside of what we can do with the filter then this requires custom CSS code. Use the classes *klarna-onsite-messaging-product* and *klarna-onsite-messaging-cart* respectively.**
