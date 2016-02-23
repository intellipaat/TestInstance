<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<p>
	Hello <?php echo ucfirst($order->billing_first_name).' '.ucfirst($order->billing_last_name); ?>,<br/><br/>

    Thanks for registering to Intellipaat courses.<br/>
	Here are the simple steps to login to Intellipaat e-learning solution for creating your account
</p>
<h4>Step 1: </h4>
<p>
	Click on this link to access e-learning solution - <a href="http://intellipaat.com/elearning//login/index.php">http://intellipaat.com/elearning//login/index.php</a>
<p>

<h4>Step 2: </h4>
<p>
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/social_email.png" alt="social links" /> <br/>
	Click on the Icons Google ( Preferred), Facebook and Linkedin. We have our solution with integrated single sign on these platform and provide high level of security.
<p>

<h4>Step 3: </h4>
<p>
	Please fill all the required details for account creation and once done please send us your username and email id on <a href="mailto:support@intellipaat.com">support@intellipaat.com</a>
</p>

<h4>Step 4: </h4>
<p>
	Upon receiving your email we will provide you access to your enrolled courses within 3 hrs.<br/>
	Please drop us an email on <a href="mailto:support@intellipaat.com">support@intellipaat.com</a> for any doubt or issues faced during course.
</p>

<p>
	Regards,<br/>
	Support team.
</p>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>