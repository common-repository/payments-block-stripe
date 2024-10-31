<?php
/**
 * Plugin Name: Payment Block - Stripe
 * Description: Payment Block - Stripe — is a Gutenberg plugin. You can add unlimited Stripe payment button with the different options like different currencies, diferent Stripe keys(test and live both) and etc.
 * Author: ronakganatra
 * Author URI: https://profiles.wordpress.org/ronakganatra/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: rnk-stripe-payment-button
 * Domain Path: /languages
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function rnk_stripe_payment_button_cgb_block_assets() {
	$dir = (string) dirname(__FILE__);
	wp_enqueue_script('jquery');
	// include the stripe library
	require_once($dir . '/src/lib/Stripe.php');
	//require_once ($dir.'ClassRnkStripe.php');
	add_action('wp_ajax_nopriv_rnk_submit_payment', 'rnk_submit_payment');
	add_action('wp_ajax_rnk_submit_payment', 'rnk_submit_payment');
	// Register block styles for both frontend + backend.
	wp_register_style(
		'rnk_stripe_payment_button-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', __FILE__ ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null
	);

	// Register block editor script for backend.
	wp_register_script(
		'rnk_stripe_payment_button-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', __FILE__ ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null,
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'rnk_stripe_payment_button-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', __FILE__ ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'rnk-stripe-payment-button/block-rnk-stripe-payment-button', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'rnk_stripe_payment_button-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'rnk_stripe_payment_button-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'rnk_stripe_payment_button-cgb-block-editor-css',
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'rnk_stripe_payment_button_cgb_block_assets' );

// Add new categories.

function rnk_stripe_payment_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'rnk-payment',
				'title' => __( 'Payment', 'rnk-stripe-payment-button' ),
				'icon'  => 'money',
			),
		)
	);
}
add_filter( 'block_categories', 'rnk_stripe_payment_block_categories', 10, 2 );

add_shortcode('rnk_stripe_payment_block','rnk_stripe_payment_block_function');
function rnk_stripe_payment_block_function($atts){
	ob_start();
	$atts = shortcode_atts([
		"amount" => 1000,
		"publishable_key" => '',
		"secret_key" => '',
		"currency" => 'usd',
		"label" => "Pay"
	], $atts, 'rnk_stripe_payment_block');
	$amount = isset($atts['amount']) ? $atts['amount'] : '1000';
	$publishable_key = isset($atts['publishable_key']) ? $atts['publishable_key'] : '';
	$secret_key = isset($atts['secret_key']) ? $atts['secret_key'] : '';
	$currency = isset($atts['currency']) ? $atts['currency'] : 'usd';
	$label = isset($atts['label']) ? $atts['label'] : 'Pay';
	$amount = $amount*100;
	$url = plugins_url('rnk-stripe-payment-button');
	$stripe = array(
		'secret_key' => $secret_key,
		'publishable_key' => $publishable_key,
	);

	?>
	<style>
		.donate-loading {
			width: 24px;
			height: 24px;
			background: url('<?php echo $url; ?>/images/loading.gif') no-repeat;
		}
	</style>
	<script>
        (function($) {
            $(function() {
                $('.stripe-button').bind('token', function(e, token) {
                    var data = {
                        'action': 'rnk_submit_payment',
                        'token': token.id,
                        'amount': $(this).attr('data-amount'),
                        'secret_key': '<?php echo $secret_key; ?>',
                        'currency': '<?php echo $currency; ?>'
                    };
                    $('.stripe-button-inner').hide();
                    $('.donate-response').html('<div class="donate-loading"></div>');
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                        $('.donate-response').html(response);
                    });
                });
            });
        } (jQuery));
	</script>
	<script src="https://button.stripe.com/v1/button.js" class="stripe-button"
	        data-key="<?php echo $publishable_key; ?>"
	        data-amount="<?php echo $amount; ?>"
	        data-currency="<?php echo $currency;  ?>"
	        data-label="<?php echo $label; ?>"></script>
	<div class="donate-response"></div>
	<?php
	return ob_get_clean();
}

/*============================================================
	submit_payment
============================================================*/

function rnk_submit_payment() {

	$token = isset( $_POST['token']) ? sanitize_text_field($_POST['token']) : '';
	$amount = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : 0;
	$secret_key = isset($_POST['secret_key']) ? sanitize_text_field($_POST['secret_key']) : '';
	$currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : 'usd';
	Stripe::setApiKey($secret_key);
	try {
		$charge = Stripe_Charge::create(array(
			'card' => $token,
			'amount' => $amount,
			'currency' => $currency,
		));
	}
	catch (Stripe_Error $e) {
		die($e->getMessage());
	}

	die('Your payment has been sent. Thank you for your donation!');
}
