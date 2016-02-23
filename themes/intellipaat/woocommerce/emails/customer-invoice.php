<?php
/**
 * Customer invoice email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<table cellspacing="0" cellpadding="20">
	<tbody>	
    	<tr>
        	<td>
                
                <?php if ( $order->status === 'pending' ) : ?>
                
                    <p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'woocommerce' ), get_bloginfo( 'name', 'display' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', 'woocommerce' ) . '</a>' ); ?></p>
                
                <?php endif; ?>
                
                <?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>
                
                <h2><?php echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number(); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>
                
                <table cellspacing="2" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
                    <thead>
                        <tr>
                            <th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Course', 'woocommerce' ); ?></th>
                            <th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                            <th scope="col" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;"><?php _e( 'Price', 'woocommerce' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            switch ( $order->status ) {
                                case "completed" :
                                    echo $order->email_order_items_table( $order->is_download_permitted(), false, true );
                                break;
                                case "processing" :
                                    echo $order->email_order_items_table( $order->is_download_permitted(), true, true );
                                break;
                                default :
                                    echo $order->email_order_items_table( $order->is_download_permitted(), true, false );
                                break;
                            }
                        ?>
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
            
			</td>
	    </tr>
    </tbody>
</table>

<?php do_action( 'woocommerce_email_footer' ); ?>