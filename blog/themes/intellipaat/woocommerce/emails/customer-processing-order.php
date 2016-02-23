<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<table cellspacing="0" cellpadding="20">
	<tbody>	
    	<tr>
        	<td>
            <p><?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'woocommerce' ); ?></p>
            
            <?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>
            
            <h2><?php echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number(); ?></h2>
            
            <table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
                <thead>
                    <tr>
                        	<th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Course', 'woocommerce' ); ?></th>
                            <th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                            <th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Price', 'woocommerce' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $order->email_order_items_table( $order->is_download_permitted(), true, ( $order->status=='processing' ) ? true : false ); ?>
                </tbody>
                <tfoot>
                    <?php
                        if ( $totals = $order->get_order_item_totals() ) {
                            $i = 0;
                            foreach ( $totals as $total ) {
                                $i++;
                                ?><tr>
                                    <th scope="row" colspan="2" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:right;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
                                                        
                                    <td style="background-color:#f1f1f1;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;color:#353535; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
                                </tr><?php
                            }
                        }
                    ?>
                </tfoot>
            </table>
            
            <?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>
            
            <?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>
            
            <h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
            
            <?php if ($order->billing_email) : ?>
                <p><strong><?php _e( 'Email:', 'woocommerce' ); ?></strong> <?php echo $order->billing_email; ?></p>
            <?php endif; ?>
            <?php if ($order->billing_phone) : ?>
                <p><strong><?php _e( 'Tel:', 'woocommerce' ); ?></strong> <?php echo $order->billing_phone; ?></p>
            <?php endif; ?>
            
            <?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>
            
            </td>
	    </tr>
    </tbody>
</table>
<?php do_action( 'woocommerce_email_footer' ); ?>