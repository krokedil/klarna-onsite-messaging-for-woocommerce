=== Klarna On-Site Messaging for WooCommerce ===
Contributors: krokedil
Tags: woocommerce, klarna, ecommerce, e-commerce, on-site messaging
Requires at least: 4.7
Tested up to: 5.7.0
Requires PHP: 5.6
WC requires at least: 3.8.0
WC tested up to: 5.1.0
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== DESCRIPTION ==

== Installation ==
1. Upload plugin folder to to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go WooCommerce Settings -> Payments -> Klarna Checkout/Klarna Payments and configure your On-Site messaging settings.

= Are there any specific requirements? =
* WooCommerce 3.0 or newer is required.
* PHP 5.6 or higher is required.

== Frequently Asked Questions ==
= Where can I find On-Site Messaging for WooCommerce documentation? =
For help setting up and configuring On-Site Messaging for WooCommerce please refer to our [documentation](https://docs.krokedil.com/article/259-klarna-on-site-messaging/).

	

== Changelog ==
= 2021.03.23    - version 1.5.0 =
* Feature       - Added support to automatically populate the data-purchase-amount when using a shortcode on a product or cart page as long as no purchase amount has been specified.
* Fix           - We now update the placement purchase amount properly when updating the cart on the cart page.
* Fix           - Fixed an issue of not including taxes in the variable product price.
* Fix           - Fixed a error caused by the global post variable not being present on a page.

= 2021.02.25    - version 1.4.4 =
* Enhancement   - Add support for France, Canada and New Zealand.

= 2020.11.24    - version 1.4.3 =
* Tweak         - Remove code that overwrote Klarnas cookie.

= 2020.10.02    - version 1.4.2 =
* Fix           - Fixed Norwegian, Finish and Dutch locales.

= 2020.09.22    - version 1.4.1 =
* Enhancement   - Added support for multicurrency plugins.
* Fix           - Removed JavaScript defaulted version number. This caused an issue when loading the JavaScript from Klarna when they made an updated their system.

= 2020.09.15    - version 1.4.0 =
* Feature       - Added a widget to add Klarna on-site messaging to a widget field.
* Enhancement   - Added support for changing the total value data tag on a page with multiple placements.

= 2020.08.11    - version 1.3.1 =
* Fix           - Small JS fix that could cause issues with single product page display and variable products.

= 2020.08.11    - version 1.3.0 =
* Feature       - Added [onsite_messaging] shortcode to be able to display OSM placements on other places than plugin default.
* Tweak         - Removed inline javascript.
* Tweak         - Coding standards fix.
* Fix           - Error notice fix.

= 2020.06.02    - version 1.2.2 =
* Fix           - Updated JavaScript library URL to not include the plugin version number.
* Fix           - Updated the US library endpoint to NA.

= 2020.03.04    - version 1.2.1 =
* Fix           - Changed locale name for Norwegian.

= 2020.03.03    - version 1.2.0 =
* Feature       - Added support for Australia.
* Fix           - Changed url to enqueue Klarna JS file for production.

= 2020.03.02    - version 1.1.0 =
* Feature       - Added support for new way to integrate On-Site Messaging.
* Tweak         - Use constant to enqueue correct script versions.
* Tweak         - Added admin notice to inform current users that they need to update the way OSM is integrated.

= 2019.10.22    - version 1.0.5 =
* Enhancement   - Only show iframe is customer matches the store base country.

= 2019.06.12    - version 1.0.4 =
* Enhancement   - Added support for custom themes.

= 2019.06.05 	- version 1.0.3 =
* Feature	    - Added support for themes.
* Enhancement	- Use the cheapest variation instead of 0 for the placement on a variable product.
* Fix			- Fixed an issue where placement filters did not run correctly.
* Fix			- Fixed not using the price shown for the placement in some cases depending on tax settings.

= 2019.02.27 	- version 1.0.2 =
* Fix	        - Fixed bug where Placement ID was not set correctly.

= 2019.02.27 	- version 1.0.1 =
* Enhancement	- Added Kernl Versioning.

= 2019.02.27 	- version 1.0.0 =
* Initial release
