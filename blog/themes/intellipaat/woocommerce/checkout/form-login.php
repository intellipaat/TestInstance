<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'If existing customer?', 'woocommerce' ) );
$info_message .= ' <a href="#" id="showlogin">' . __( 'Click here to login', 'woocommerce' ) . '</a>';
wc_print_notice( $info_message, 'notice' );
?>

<div id="loginwrapper" class="loginwrapper row" style="display:none;">
	<div class="col-md-12">
    	<p><?php echo __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ) ?></p>
    </div>
	<div class="col-md-5 col-sm-5">
		<?php do_action( 'wordpress_social_login' ); ?> 
	</div>
    <p class="text-center visible-xs">OR</p>
	<div class="col-md-6 col-sm-6 col-sm-offset-1 col-md-offset-1">
    	<span id="loginerrors" class="clearfix"></span>
		<?php
            woocommerce_login_form(
                array(
                    'message'  => '',
                    'redirect' => wc_get_page_permalink( 'checkout' ),
                    'hidden'   => false
                )
            );
        ?>
	</div>
</div>
