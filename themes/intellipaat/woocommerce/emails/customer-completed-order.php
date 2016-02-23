<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>


<?php
	$images_url = get_stylesheet_directory_uri().'/images/emailer/';
	$items = $order->get_items();
	foreach($items as $item){
		$product_id = $item['product_id'];
		$product_name = $item['name'];
		break;
	}
	$vcourses=array();
	$vcourses=vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));
	$course_id = $vcourses[0];
	$ids = get_post_meta( $course_id, 'intellipaat_recommended_courses',true );
	
	if(!$ids)
		$ids = array(2482,2525,2357);
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), "medium" );
	$product_link = get_permalink($course_id);
?>


<?php do_action( 'woocommerce_email_header', $email_heading ); // var_dump($items); var_dump($vcourses); var_dump( $ids);?>

<table style="background-color:#dfdee4;" border="0" cellpadding="0" cellspacing="0" align="center" background="<?php echo $images_url ?>bg_top.jpg" width="660">
    <tbody>
      <tr>
        <td colspan="4" background="<?php echo $images_url ?>bg_top.jpg" bgcolor="#dfdee4" width="660">&nbsp;</td>
      </tr>
      <tr>
        <td style="background-color:#dfdee4;" background="<?php echo $images_url ?>bg_top.jpg" height="173" width="25">&nbsp;</td>
        <td align="left" height="171" valign="top" width="610"><table style="background-color:#dfdee4;" border="0" cellpadding="0" cellspacing="0" align="center" width="610">
            <tbody>
              <tr>
                <td rowspan="6" style="padding:0px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" background="<?php echo $images_url ?>bg_top.jpg" valign="top"><a href="<?php echo $product_link; ?>" target="_blank"><img width="200" height="112" border="0" alt="Start your course" src="<?php echo $thumb[0] ; ?>" style="display:block;"></a> <br>
                <strong>  Follow us </strong><br>
                  <center>
                  <?php
        			$social_icons = vibe_get_option('social_icons');
					if(is_array($social_icons) && is_array($social_icons['social'])){
						foreach($social_icons['social'] as $key=>$icon){ 
							$url=$social_icons['url'][$key];
							echo '<a href="'.$url.'" title="'.$icon.'" target="_blank" ><img src="'.$images_url.$icon.'.png" alt="" border="0"></a>';
						}
					}
					 ?>
                  </center></td>
                <td rowspan="6" style="background-color:#dfdee4;" background="<?php echo $images_url ?>bg_top.jpg" width="15">&nbsp;</td>
                <td style="padding:0px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:18px;font-weight:normal;color:#353535;text-decoration:none;" align="left" background="<?php echo $images_url ?>bg_top.jpg" valign="top"> Welcome to <a href="<?php echo $product_link; ?>" style="vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:18px;font-weight:bold;color:#353535;text-decoration:none;" target="_blank"> <?php echo $product_name ?></a></td>
              </tr>
              <tr>
                <td style="padding-top:4px;padding-bottom:8px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" background="<?php echo $images_url ?>bg_top.jpg" valign="top"> <?php printf( __( "Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:", 'woocommerce' ), get_option( 'blogname' ) ); ?></td>
              </tr>
              <tr>
                <td style="vertical-align:top;" align="left" bgcolor="#89888b" height="1" valign="top"></td>
              </tr>
              <tr>
                <td style="padding-top:8px;padding-right:0px;padding-bottom:12px;padding-left:0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" background="<?php echo $images_url ?>bg_top.jpg" valign="top"> Thank you for joining and happy learning! </td>
              </tr>
              <tr>
                <td style="padding:0px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" background="<?php echo $images_url ?>bg_top.jpg" valign="top"><div style="padding-top:6px; padding-right:15px;padding-bottom:6px; padding-left:15px;background-color:#f5892d;border-radius:2px;width:238px;color:#ffffff;text-decoration:none;" align="center"><a href="<?php echo site_url('/login/'); ?>" style="font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:14px;font-weight:normal;color:#ffffff;text-decoration:none;font-weight:normal;" target="_blank">Start your course</a></div></td>
              </tr>
            </tbody>
          </table></td>
        <td style="background-color:#dfdee4;" background="<?php echo $images_url ?>bg_top.jpg" width="25">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" background="<?php echo $images_url ?>bg_top.jpg" bgcolor="#dfdee4" width="660">&nbsp;</td>
      </tr>
    </tbody>
</table>

<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
    <tbody>
      <tr>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
        <td align="left" valign="top" width="350">
        	<table style="background-color:#ffffff;" border="0" cellpadding="3" cellspacing="0" align="left">
                <tbody>
                  <tr>
                    <td style="background-color:#ffffff;width:350px;padding:0px 0px 0px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:18px;font-weight:bold;color:#353535;" align="left" height="40" valign="middle"> Billed to:</td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:350px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Name: <?php echo ucfirst( $order->billing_first_name).' '.ucfirst( $order->billing_last_name); ?> </td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:350px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Email: <a href="mailto:<?php echo $order->billing_email; ?>" target="_blank"><?php echo $order->billing_email; ?></a></td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:350px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Purchase Date: <?php echo date(get_option( 'date_format' ), strtotime($order->order_date)); ?></td>
                  </tr>
                  <?php /*?><tr>
                    <td style="background-color:#ffffff;width:428;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Address: not entered </td>
                  </tr><?php */?>
                </tbody>
         	</table>
         </td>
        <td align="left" valign="top" width="264">
        	<table style="background-color:#ffffff;" border="0" cellpadding="3" cellspacing="0" align="center" width="264">
                <tbody>
                  <tr>
                    <td style="background-color:#ffffff;width:264px;padding:0px 0px 0px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:18px;font-weight:bold;color:#353535;" align="left" height="40" valign="middle"> From:</td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:264px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Intellipaat Software Services Pvt. Ltd.,</td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:264px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> A16 A, Van Vihar Colony, Tonk Road,</td>
                  </tr>
                  <tr>
                    <td style="background-color:#ffffff;width:264px;padding:2px 0px 2px 0px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="left" valign="middle"> Jaipur-302015 (Raj.) India.</td>
                  </tr>
                </tbody>
          	</table>
          </td>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
      </tr>
    </tbody>
</table>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
    <tbody>
      <tr>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
        <td align="left" valign="top" width="610"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="2" align="center">
            <tbody>
              <tr>
                <td colspan="4" style="background-color:#ffffff;width:610px;padding-top:12px;padding-bottom:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:18px;font-weight:bold;color:#353535;" align="left" height="50" valign="middle"> Order <?php echo $order->get_order_number(); ?> receipt details:</td>
              </tr>
              <tr style="background-color:#e3e3e3;">
                <td style="background-color:#e3e3e3;width:324;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="left" valign="middle" width="324"> Item</td>
                <td style="background-color:#e3e3e3;width:50;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="left" valign="middle" width="50"> Qty</td>
                <td style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="left" valign="middle" width="118"> Original Total </td>
                <td style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="left" valign="middle" width="118"> Purchase Total</td>
              </tr>
              
              <?php //echo $order->email_order_items_table( true, false, true ); ?>
              
             <?php 
			 		$items = $order->get_items();
					foreach($items as $item){ 
						//$price = $item['line_subtotal']/$item['qty'];
		?> <tr>
                <td style="background-color:#f1f1f1;width:324;padding-top:8px; padding-right:8px; padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;" align="left" valign="middle" width="324"><a href="#" style="vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" target="_blank"><?php echo $item['name']; ?></a></td>
                
                <td style="background-color:#f1f1f1;width:50;padding-top:8px;padding-right:8px;padding-left:8px;text-align:center;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="center" valign="top" width="50"> <?php echo $item['qty']; ?></td>
                
                <td style="background-color:#f1f1f1;width:118;padding-top:8px;padding-right:8px;padding-left:8px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="center" valign="top" width="118"> $ <?php echo $item['line_subtotal'] ; ?></td>
                
                <td style="background-color:#f1f1f1;width:118;padding-top:8px;padding-right:8px;padding-left:8px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;" align="center" valign="top" width="118"> $ <?php echo $item['line_total']; ?></td>
              </tr>
			  <?php }?>
              
            </tbody>
            <tfoot>
				<?php
                    if ( $totals = $order->get_order_item_totals() ) {
                        $i = 0;
                        foreach ( $totals as $total ) {
                            $i++;
                            ?><tr>
                            	<td></td>
                                <th scope="row" colspan="2" style="background-color:#e3e3e3;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
                                
                                <td style="background-color:#f1f1f1;width:118;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;color:#353535; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
                            </tr><?php
                        }
                    }
                ?>
              <tr>
                <td colspan="4" style="background-color:#ffffff;width:610px;padding-top:8px;text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:11px;font-weight:normal;color:#353535;" align="left" valign="middle"> Questions? Please contact us via <a href="http://support.intellipaat.com" target="_blank">support.intellipaat.com</a>.</td>
              </tr>
            </tfoot>
          </table></td>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
      </tr>
    </tbody>
</table>

<?php /*?><table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( true, false, true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table><?php */?>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php /*?><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
    <tbody>
      <tr>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
        <td align="left" valign="top" width="610"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="2" align="center">
            <tbody>
              <tr>
                <td colspan="2" style="background-color:#ffffff;width:610px;padding-top:12px;padding-bottom:8px; text-align:left;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:16px;font-weight:normal;color:#353535;" align="left" height="50" valign="middle"> Here are the simple steps to login to Intellipaat e-learning solution for creating your account: </td>
              </tr>
              <tr>
                <td style="background-color:#e3e3e3;width:110px;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:center;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="center" valign="middle" width="110"> Step 1 :</td>
                <td width="500" align="left" valign="middle" style="background-color:#f1f1f1; width:500px; padding-top:8px;padding-right:4px;padding-bottom:8px;padding-left:4px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#353535;">Click on this link to access e-learning solution - <a href="http://intellipaat.com/elearning//login/index.php" style="vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#f5892d;text-decoration:none;" target="_blank">http://intellipaat.com/elearning/login/index.php</a></td>
              </tr>
              <tr>
                <td style="background-color:#e3e3e3;width:110px;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:center;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="center" valign="middle" width="110"> Step 2 :</td>
                <td width="500" align="left" valign="middle" style="background-color:#f1f1f1; width:500px; padding-top:8px;padding-right:4px;padding-bottom:8px;padding-left:4px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#353535;"><img src="<?php echo $images_url ?>social-icons.png" width="69" height="32" alt="" /> Click on the Icons Google ( Preferred), and Facebook. We have our solution with integrated single sign on these platform and provide high level of security.  </td>
              </tr>
              <tr>
                <td style="background-color:#e3e3e3;width:110px;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:center;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="center" valign="middle" width="110"> Step 3 :</td>
                <td width="500" align="left" valign="middle" style="background-color:#f1f1f1; width:500px; padding-top:8px;padding-right:4px;padding-bottom:8px;padding-left:4px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#353535;">Please fill all the required details for account creation and once done please send us your e-mail id on <a href="mailto:support@intellipaat.com" style="vertical-align:middle;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#f5892d;text-decoration:none;">support@intellipaat.com</a></td>
              </tr>
              <tr>
                <td style="background-color:#e3e3e3;width:110px;padding-top:8px;padding-bottom:8px; padding-left:8px;text-align:center;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:bold;color:#353535;" align="center" valign="middle" width="110"> Step 4 :</td>
                <td width="500" align="left" valign="middle" style="background-color:#f1f1f1; width:500px; padding-top:8px;padding-right:4px;padding-bottom:8px;padding-left:4px;text-align:left;vertical-align:top;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:12px;font-weight:normal;color:#353535;">Upon receiving your email we will provide you access to your enrolled courses within 3 hrs.</td>
              </tr>
            </tbody>
          </table></td>
        <td style="background-color:#ffffff;" width="25">&nbsp;</td>
      </tr>
    </tbody>
</table><?php */?>

<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" background="<?php echo $images_url ?>bg_top.jpg" width="660">
    <tbody>
      <tr>
        <td align="left" bgcolor="#ffffff" valign="top" width="660">
        	<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
                <tbody>
                  <tr>
                    <td style="text-align:left;padding-top:5px;width:660;vertical-align:top;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="6"><img src="<?php echo $images_url ?>line-top.jpg" width="660" height="4" alt="" /></td>
                        </tr>
                      <tr>
                        <td width="64" style="width:64px;"><img style="display:block;" src="<?php echo $images_url ?>icon1.jpg" width="64" height="56" alt="" /></td>
                        <td width="156" style="font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:12px;font-weight:normal;color:#353535;text-decoration:none; width:156px;">Courses on demand</td>
                        <td width="64" style="width:64px;"><img style="display:block;" src="<?php echo $images_url ?>icon2.jpg" width="64" height="56" alt="" /></td>
                        <td width="156" style="font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:12px;font-weight:normal;color:#353535;text-decoration:none; width:156px;">24*7 On Demand Support</td>                        <td width="64" style="width:64px;"><img style="display:block;" src="<?php echo $images_url ?>icon3.jpg" width="64" height="56" alt="" /></td>
                        <td width="156" style="font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:12px;font-weight:normal;color:#353535;text-decoration:none; width:156px;">Lifetime Access</td>
                        </tr>
                      <tr>
                        <td colspan="6"><img src="<?php echo $images_url ?>line-bottom.jpg" width="660" height="4" alt="" /></td>
                        </tr>
                    </table></td>
                  </tr>
                  
                </tbody>
          </table>
          
          <table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
            <tbody>
              <tr>
                <td colspan="2" style="text-align:left;padding-top:25px;padding-right:25px;padding-bottom:15px; padding-left:25px;width:660;vertical-align:top;" width="660"><a href="#" style="font-family:Arial,Helvetica,sans-serif;line-height:16px;font-size:16px;font-weight:normal;color:#353535;text-decoration:none;" target="_blank">Students who viewed your course also viewed: </a></td>
              </tr>
            </tbody>
          </table>
          <table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
            <tbody>
              <tr>
              <?php /*foreach($ids as $id) { var_dump($id);}*/foreach($ids as $id) { 
			  			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), "medium" );
			  ?>
                <td style="background-color:#ffffff;" width="25">&nbsp;</td>
                <td align="left" valign="top" width="186"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="186">
                    <tbody>
                      <tr>
                        <td colspan="4" bgcolor="#dddddd" height="1" width="186"></td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                        <td colspan="2" style="background-color:#ffffff;width:186px;padding-top:15px;" align="center" valign="top" width="186"><a href="<?php echo get_permalink($id) ?>" target="_blank"><img style="display:block;" src="<?php echo $thumb[0]; ?>" alt="<?php echo get_the_title($id) ?>" border="0" align="middle" height="87" width="146"></a></td>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                        <td colspan="2" style="background-color:rgb(255,255,255);width:184px;padding:5px 19px;text-align:left;vertical-align:middle;line-height:13px;font-size:13px;font-family:Arial,Helvetica,sans-serif;color:rgb(53,53,53);" align="left" height="48" valign="middle" width="184"><a href="<?php echo get_permalink($id) ?>" style="padding-left:8px; font-family:Arial,Helvetica,sans-serif;line-height:13px;font-size:13px;text-decoration:none;color:#353535;font-weight:bold;" target="_blank"><?php echo get_the_title($id) ?></a></td>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                      </tr>
                      <tr>
                        <td colspan="4" bgcolor="#eeeeee" height="1" width="186"></td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                        
                        <td style="background-color:rgb(255,255,255);width:184px;padding:5px 8px;text-align:left;vertical-align:middle;line-height:13px;font-size:13px;font-family:Arial,Helvetica,sans-serif;color:rgb(53,53,53);" align="left" height="25" valign="middle" width="92">
                        
                        
                        <table align="left" width="60%" cellpadding="6">
                            <tbody><tr>
                            <td align="left" bgcolor="#ffffff" valign="top">
                            	<?php echo get_post_meta($id,'vibe_students',true).' Learners' ;?>
                            </td>
                            </tr>
                            </tbody></table>
                            <table align="right" width="40%" cellpadding="6">
                            <tbody><tr>
                            <td style="color:#67747c;font-size:13px;line-height:14px;font-weight:normal;text-align:right;vertical-align:top;text-decoration:none;font-family:sans-serif;padding:4px 0px 2px 0px" align="right" valign="top"><?php 
										$key_feature = get_field('course_1_key_feature', $id);
										if(!empty($key_feature)){
											$hours = str_replace(array('-','+'), array('',''), filter_var($key_feature, FILTER_SANITIZE_NUMBER_INT));
											if(is_numeric($hours))
												echo $hours.' Hrs';
										}
									?>
                            </td>
                            </tr>
                            </tbody></table>
                                                
                        
                        
                        </td>
                        <td align="left" bgcolor="#fff" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><img src="<?php echo $images_url ?>spacer.png" style="display:block;" alt=""></td>
                      </tr>
                      <tr>
                        <td colspan="4" bgcolor="#eeeeee" height="1" width="186"></td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><br></td>
                        <td colspan="2" style="background-color:#ffffff;padding:10px 0px 10px 0px;" align="center" valign="top" width="184"><div style="padding-left:10px;width:60px;color:#67747c;text-decoration:none; float:left;" align="left"><?php
                        
						$pid = get_post_meta($id,'vibe_product',true);
						if(!$pid ){
							$pid=get_post_meta($id,'intellipaat_online_training_course',true);
							
						}
						if(isset($pid) && $pid !='' && function_exists('get_product')){
							
							$product = get_product( $pid );
							if(is_object($product))
							echo $product->get_price_html();
						}
						?></div>
                        <div style="padding-top:6px; padding-right:4px;padding-bottom:6px;padding-left:4px;background-color:#f5892d;border-radius:2px;width:90px;color:#ffffff;text-decoration:none; float:right; margin-right:10px;" align="center"><a href="<?php echo get_permalink($id) ?>" style="font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:14px;font-weight:normal;color:#ffffff;text-decoration:none;font-weight:normal;" target="_blank">View More</a></div></td>
                        <td align="left" bgcolor="#dddddd" valign="top" width="1"><br></td>
                      </tr>
                      <tr>
                        <td colspan="4" bgcolor="#dddddd" height="2" width="186"></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <?php  } ?>
                <td style="background-color:#ffffff;" width="26">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7" height="25">&nbsp;</td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td colspan="3" style="vertical-align:top;" align="left" bgcolor="#cfcfcf" height="1" valign="top" width="660"></td>
      </tr>
    </tbody>
</table>
<?php //do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
    <tbody>
      <tr>
        <td colspan="3" style="vertical-align:top;" align="left" bgcolor="#cfcfcf" height="1" valign="top" width="660"></td>
      </tr>
      <tr>
        <td align="left" bgcolor="#ffffff" valign="top" width="660">
        <table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
            <tbody>
              <tr>
                <td colspan="4" bgcolor="#ffffff" height="25" width="660">&nbsp;</td>
              </tr>
              <tr>
                <td style="background-color:#ffffff;" width="25">&nbsp;</td>
                <td align="left" valign="top" width="610"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="610">
                    <tbody>
                      <tr>
                        <td style="padding:10px 0px 0px 0px;vertical-align:top;" align="left" valign="top" width="306"><a href="#" style="text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:18px;font-weight:bold;color:#353535;text-decoration:none;" target="_blank">All Intellipaat courses come with a Certificate of Completion</a></td>
                        <td rowspan="6" style="background-color:#ffffff;" width="15"><img src="<?php echo $images_url ?>spacer.png" border="0"></td>
                        <td rowspan="6" style="padding:0px 0px 0px 0px;" align="left" valign="top"><img style="display:block;" src="<?php echo $images_url ?>certificate.jpg" alt="All Intellipaat courses come with a Certificate of Completion" border="0" width="289"></td>
                      </tr>
                      <tr>
                        <td style="padding:12px 0px 12px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" valign="top"> Once all curriculum items are complete, your certificate can be unlocked. </td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" valign="top"><div style="padding-top:6px; padding-right:15px; padding-bottom:6px; padding-left:15px;background-color:#f5892d;border-radius:2px;width:275px;color:#ffffff;text-decoration:none;" align="center"> <a href="<?php echo site_url('/login/'); ?>" style="font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:14px;font-weight:normal;color:#ffffff;text-decoration:none;font-weight:normal;" target="_blank">Start unlocking your certificate</a></div></td>
                      </tr>
                    </tbody>
                  </table></td>
                <td style="background-color:#ffffff;" width="25">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" bgcolor="#ffffff" height="25" width="660">&nbsp;</td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td colspan="3" style="vertical-align:top;" align="left" bgcolor="#cfcfcf" height="1" valign="top" width="660"></td>
      </tr>
    </tbody>
</table>

<table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
    <tbody>
      <tr>
        <td colspan="3" style="vertical-align:top;" align="left" bgcolor="#cfcfcf" height="1" valign="top" width="660"></td>
      </tr>
      <tr>
        <td align="left" bgcolor="#ffffff" valign="top" width="660"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
            <tbody>
              <tr>
                <td colspan="4" bgcolor="#ffffff" height="25" width="660">&nbsp;</td>
              </tr>
              <tr>
                <td style="background-color:#ffffff;" width="25">&nbsp;</td>
                <td align="left" valign="top" width="610"><table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="610">
                    <tbody>
                      <tr>
                        <td style="padding:35px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:18px;font-weight:bold;color:#353535;text-decoration:none;" align="left" valign="top" width="329"> Introducing Intellipaat for Organizations </td>
                        <td rowspan="6" style="background-color:#ffffff;" width="41">&nbsp;</td>
                        <td rowspan="6" style="padding:0px 0px 0px 0px;" align="left" valign="top"><img style="display:block;" src="<?php echo $images_url ?>intellipaatfororganizations.jpg" alt="Intellipaat for Organizations" border="0"></td>
                      </tr>
                      <tr>
                        <td style="padding-top:12px;padding-bottom:12px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" valign="top"> Calling HR and training professionals! Bring Intellipaat to work. Join <b>40+</b> companies already training and learning on the Intellipaat platform.</td>
                      </tr>
                      <tr>
                        <td style="padding:0px 0px 0px 0px;vertical-align:top;text-align:left;font-family:Arial,Helvetica,sans-serif;line-height:14px;font-size:14px;font-weight:normal;color:#353535;text-decoration:none;" align="left" valign="top"><div style="padding-top:6px; padding-right:15px; padding-bottom:6px; padding-left:15px;background-color:#f5892d;border-radius:2px;width:238px;color:#ffffff;text-decoration:none;" align="center"> <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Intellipaat For Organizations' ) ) ); ?>" style="font-family:Arial,Helvetica,sans-serif;line-height:18px;font-size:14px;font-weight:normal;color:#ffffff;text-decoration:none;font-weight:normal;" target="_blank">Learn More</a></div></td>
                      </tr>
                    </tbody>
                  </table></td>
                <td style="background-color:#ffffff;" width="25">&nbsp;</td>
              </tr>
            </tbody>
          </table>
          <table style="background-color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="center" width="660">
            <tbody>
              <tr>
                <td style="vertical-align:top;" align="left" bgcolor="#ffffff" height="25" valign="top" width="25"><br></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
</table>
          
<?php do_action( 'woocommerce_email_footer' ); ?>