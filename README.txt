=== Payment Block - Stripe ===
Plugin Name: Payment Block - Stripe
Plugin URI: https://profiles.wordpress.org/ronakganatra/
Author: ronakganatra
Author URI: https://profiles.wordpress.org/ronakganatra/
Contributors: ronakganatra, avinapatel, jontyravi
Stable tag: 1.0.0
Tags: donation-button, donation-block, payment-button, payment-block, button, donate, pay, payment, donation, stripe, stripe donation, stripe payment, stripe donation button, shortcode, sidebar, widget, gutenberg, gutenberg payment block, gutenberg stripe payment, stripe payment block, stripe payment button, payment gateway
Requires at least: 5.0
Tested up to: 5.1.1
Requires PHP: 5.2.4
Copyright:
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create Stripe Payment Buttons as per your need in very simple way.

== Description ==
Stripe Payment block allows you to create dynamic PayPal Donation Buttons quickly and in a very easy way on your website.

= Plugin Functionality: =
* Sandbox/Live Mode
* Dynamic Amount of Payment
* All Currencies available
* Dynamic Button Name
* Shortcode can be used in code too.

= Shortcode =
<code>[rnk_stripe_payment_block amount="10" publishable_key="pk_test_key" secret_key="sk_test_key" currency="usd" label="Pay"]</code>
<code><?php echo do_shortcode('[rnk_stripe_payment_block amount="10" publishable_key="pk_test_key" secret_key="sk_test_key" currency="usd" label="Pay"]'); ?></code>
= Currency Codes =
* 		    'AUD' => 'Australian Dollars (A $)'
*        	'BRL' => 'Brazilian Real'
*        	'CAD' => 'Canadian Dollars (C $)'
*        	'CZK' => 'Czech Koruna'
* 			'DKK' => 'Danish Krone'
*       	'EUR' => 'Euros (€)',
*       	'HKD' => 'Hong Kong Dollar ($)'
*      		'HUF' => 'Hungarian Forint'
*       	'ILS' => 'Israeli New Shekel'
*			'JPY' => 'Yen (¥)'
*       	'MYR' => 'Malaysian Ringgit'
*      		'NZD' => 'New Zealand Dollar ($)'
*     		'PHP' => 'Philippine Peso'
*     		'GBP' => 'Pounds Sterling (£)'
*     		'RUB' => 'Russian Ruble'
*     		'SGD' => 'Singapore Dollar ($)'
*     		'THB' => 'Thai Baht'
*    		'USD' => 'US Dollars'

== Installation ==

* Download the plugin
* Upload the folder "rnk-stripe-payment-button" to wp-content/plugins (or upload a zip through the WordPress admin)


== Frequently Asked Questions ==

= How to create Stripe Payment button ? =

* Select Payment button block from blocks list.
* Also you can place shortcode [rnk_stripe_payment_block amount="10" publishable_key="pk_test_key" secret_key="sk_test_key" currency="usd" label="Pay"]


== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png


== Changelog ==

= 1.0.0
* Very first release which allows many dynamic options like Amount, Button Text, Currency etc.
