<?php
/*
*	Change ebs arguments at before we order it.

*/

add_filter('woocommerce_ebs_icon','intellipaat_ebs_icon', 10,1);
function intellipaat_ebs_icon( $icon ){
	return get_stylesheet_directory_uri().'/images/visa-mastercard.jpg';	
}

/*
*	Prints checkout conversion pixel code at checkout page before checkout
*/
add_action('wp_head','checkout_conversion_pixel_code_javascript');
function checkout_conversion_pixel_code_javascript(){
	if(is_checkout() && '/checkout/' == $_SERVER['REQUEST_URI'] ){
		echo  vibe_get_option('checkout_conversion_pixel_code');
	}
}



//Adds phone field to woocommerce billing fields

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' ,11 );
function custom_override_checkout_fields( $fields ) {	
 
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_address_1']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_postcode']);
	//unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	//unset($fields['billing']['billing_phone']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_city']);
	  
     $order = array(
        "billing_country",
        "billing_first_name", 
        "billing_last_name", 
        "billing_phone", 
        "billing_email"

    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["billing"][$field];
    }

    $fields["billing"] = $ordered_fields;
    return $fields;
}


/*
*	Script to be added on checkout page
*/
/*function intellipaat_checkout_before_customer_details($checkout){
	if( !is_user_logged_in()) return;
	$customer_id = get_current_user_id();
	$name = 'billing';
	?>
    <div class="container">
    	<div class="row address">
        	<header class="title">
                <h3 class="heading">Your billing details</h3>
                <a id="edit_customer_details" href="#" class="edit"><?php _e( 'Edit', 'woocommerce' ); ?></a>
            </header>
            <div class="col-md-6 col-sm-6">
                <address>
                	<?php
						$address = apply_filters( 'woocommerce_my_account_my_address_formatted_address', array(
							'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
							'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
							//'company'     => get_user_meta( $customer_id, $name . '_company', true ),
//							'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
//							'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
//							'city'        => get_user_meta( $customer_id, $name . '_city', true ),
//							'state'       => get_user_meta( $customer_id, $name . '_state', true ),
							//'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
							'country'     => get_user_meta( $customer_id, $name . '_country', true )
						), $customer_id, $name );
		
						$formatted_address = WC()->countries->get_formatted_address( $address );
		
						if ( ! $formatted_address )
							_e( 'You have not set up this type of address yet. Click on edit to change billing details.', 'woocommerce' );
						else
							echo $formatted_address;
					?>
                </address>
            </div>   
            <div class="col-md-6 col-sm-6">
            	
            </div>
            <script>
            	jQuery(document).ready(function(){
					jQuery('#customer_details').hide();					
					jQuery('#edit_customer_details').click(function(e){
						jQuery('#customer_details').slideToggle();	
						e.preventDefault();
					});
				});
            </script>     
        </div>
     </div>  
	<?php
}*/
//add_action( 'woocommerce_checkout_before_customer_details', 'intellipaat_checkout_before_customer_details',9, 1 );


//add_action( 'dynamic_sidebar_before', 'intellipaat_dynamic_sidebar_before', 10, 1 );
/*function intellipaat_dynamic_sidebar_before($index) {
	if($index != 'checkout' || is_wc_endpoint_url("order-received") || is_admin())
		return;
		
	  if ( get_option( 'woocommerce_enable_coupons' ) == 'no' || get_option( 'woocommerce_enable_coupon_form_on_checkout' ) == 'no' ){}else{ ?>
       <div class="coupon">
       <?php
       
        $info_message = apply_filters('woocommerce_checkout_coupon_message', __('Have a coupon?', 'woocommerce'));
        ?>

        <p class="woocommerce_info"><strong><?php echo $info_message; ?> </strong><a href="#" class="showcoupon"><?php _e('Click here to enter your code', 'woocommerce'); ?></a></p>
        <form class="checkout_coupon" method="post">

             <div class="coupon-form">
                <input name="coupon_code" id="coupon_code" placeholder="Enter your coupon code" class="input-text" required />
                <button type="submit" name="apply_coupon">Apply</button>
            </div>

            <div class="clear"></div>
        </form>
        </div>
        <?php
        }
}*/

/*function create_account($order_id) {
	$this->woo_side_id = $order_id;
	$crm_data = array();
	//** @var $order WC_Order 
	$order = wc_get_order($order_id);
	
	
	//** @var $user WC_User 
	$user = $order->get_user();
	if ( !$user) {
		// order is by guest
	}
	
	$billing_add = $order->get_formatted_billing_address();
	
	$billing_street = $order->billing_address_1 . ' ' .$order ->  billing_address_1;
	$billing_city = $order->billing_city;
	$billing_state  = $order->billing_state;
	
	$billing_postcode  =  $order->billing_postcode;
	$billing_country    = $order->billing_country ;
	$billing_email = $order->billing_email;
	$billing_phone = $order->billing_phone;
	
	$billing_lastname = $order->billing_last_name;
	$billing_firstname = $order->billing_first_name;
	
	$billing_company = $order->billing_company;
	
	$crm_data['email'] = $billing_email;
	
	if ($billing_street)
	$crm_data['street']  = $billing_street;
	
	if ($billing_city) {
		$crm_data['city'] = $billing_city;
	} 
	
	if ($billing_state) {
		$crm_data['state'] = $billing_state;
	} 
	
	if ($billing_postcode) {
		$crm_data['zipcode'] = $billing_postcode;
	} 
	
	if ($billing_country) {
		$crm_data['country'] = $billing_country;
	} 
	
	//** Shipping address 

	$order->shipping_address_1 . ' ' .$order ->  shipping_address_1;
	$shipping_city = $order->shipping_city;
	$shipping_state  = $order->shipping_state;
	
	$shipping_postcode  =  $order->shipping_postcode;
	$shipping_country    = $order->shipping_country ;
	$shipping_email = $order->shipping_email;
	$shipping_phone = $order->shipping_phone;
	
	
	$extra_info =  get_post( $order_id );
	
	
	///////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////Mapping//////////////////////////
	///////////////////////////////////////////////////////////////////////////
	
	//** Account phone 
	$options = $this->getMappingOption ( 'account-phone' );
	if (! is_null ( $options ) && $options ['enable'] == 1 && $options ['value']) {
		if ($options ['value'] == 'billing_phone' && $billing_phone) {
			$crm_data ['phone'] = $billing_phone;
		} elseif ($options ['value'] == 'shipping_phone' && $shipping_phone) {
			$crm_data ['phone'] = $shipping_phone;
		}
	}
	
	//* account-description 
	$options = $this->getMappingOption ( 'account-description' );
	if (! is_null ( $options ) && $options ['enable'] == 1 && $options ['value']) { 
		$crm_data['description'] = $options ['value'];
	}
	
	$crm_data['account_name'] = $billing_firstname . ' ' . $billing_firstname;
	
	
	if (get_option ( 'zoho_crm_order_to_account' )=='yes') {
		$this->insert_account ( $crm_data );
	}
	///////////////////////////////////////////////////////////////////////////
	/////////////////////////////////INSERT CONTACT////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	$crm_data['lastname'] = $billing_lastname;
	
	$options = $this->getMappingOption('contact-description');
	
	if (! is_null ( $options ) && $options ['enable'] == 1 && $options ['value']) {
		$crm_data['description'] = $options ['value'];
	}
	
	if (get_option('zoho_crm_order_to_contact')=='yes' ) 
	$this->insert_contact($crm_data);
}*/
//add_action('woocommerce_checkout_order_processed', 'create_zoho_account');

/*
*	Conversion code to be added on thank you page according to category.
*	Cost calculation according to category and replace in conversion pixel code.
*/
$orderID = '';
function conversion_code_printer() {  

	global $orderID;

	$product_cats = array();
	$OnlineTraintingCost = $SelfPacedCost =0;
	
	$code_script['combo-offers'] =$code_script['online-instructor-based']  = $code_script['self-paced']  = '';
	
	$code_script['self-paced']  = vibe_get_option('self_pace_course_conversion_code');
	
	$code_script['online-instructor-based'] = vibe_get_option('online_training_course_conversion_code');
	
	$order = new WC_Order($orderID);
	$items = $order->get_items();
	foreach($items as $item){		
		$terms = get_the_terms( $item['product_id'], 'product_cat' );
        foreach ($terms as $term) {
            $product_cats[] = $term->slug;
			/*** calculating category wise costing***/
			if($term->slug == 'online-instructor-based'){
					$OnlineTraintingCost += $item['line_total'];
			}else if($term->slug == 'self-paced'){
					$SelfPacedCost += $item['line_total'];
			}
        }
	}
	$product_cats = array_unique($product_cats); // only unique code needed to be insterted	 so we need unique categories.
	foreach($product_cats as $product_cat){
		$conversion_code .= $code_script[$product_cat]; //$product_cat contains name of category string and again $ will call var named with catogory.
	}
	
	$conversion_code = str_replace(array('OnlineTraintingCost','SelfPacedCost'), array($OnlineTraintingCost,$SelfPacedCost), $conversion_code); //Replace variables in Javascript with values
	
	echo $conversion_code;  
	
	
	/*** Affiliate sale lead tracking **/
	
	$coupons = implode(',', $order->get_used_coupons());	
	
	
	if ( in_array( 'postaffiliatepro/postaffiliatepro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
	?>
    
        <!-- <Affiliate sale lead tracking  tracking code> -->
        <script id="pap_x2s6df8d" src="http://affiliates.intellipaat.com/scripts/trackjs.js" type="text/javascript">
        </script>
        <script type="text/javascript">
        PostAffTracker.setAccountId('default1');
        
        var sale = PostAffTracker.createSale();
        sale.setTotalCost('<?php echo $order->order_total; ?>');
        sale.setOrderID('<?php echo $order->id; ?>');
        sale.setData1('<?php echo $order->billing_email; ?>');
        sale.setData2('<?php echo $coupons; ?>');
        PostAffTracker.register();
        </script>
        <!-- <Affiliate sale lead tracking  tracking code> -->
    
	<?php 
	}
	

/*?>    <!-- <webgains tracking code> -->
	<script language="javascript" type="text/javascript">
    
    var wgOrderReference = "<?php echo $order->id; ?>";
    var wgOrderValue = "<?php echo $order->order_total; ?>";
    var wgEventID = 16783;
    var wgComment = "<?php echo implode(',', $product_cats)?>";
    var wgLang = "en_US";
    var wgsLang = "javascript-client";
    var wgVersion = "1.2";
    var wgProgramID = 10121;
    var wgSubDomain = "track";
    var wgCheckSum = "";
    var wgItems = "<?php //echo count($items); ?>";
    var wgVoucherCode = "<?php echo $coupons; ?>";
    var wgCustomerID = "";
    var wgCurrency = "USD";
    
    if(location.protocol.toLowerCase() == "https:") wgProtocol="https";
    else wgProtocol = "http";
    
    wgUri = wgProtocol + "://" + wgSubDomain + ".webgains.com/transaction.html" + "?wgver=" + wgVersion + "&wgprotocol=" + wgProtocol + "&wgsubdomain=" + wgSubDomain + "&wgslang=" + wgsLang + "&wglang=" + wgLang + "&wgprogramid=" + wgProgramID + "&wgeventid=" + wgEventID + "&wgvalue=" + wgOrderValue + "&wgchecksum=" + wgCheckSum + "&wgorderreference="  + wgOrderReference + "&wgcomment=" + escape(wgComment) + "&wglocation=" + escape(document.referrer) + "&wgitems=" + escape(wgItems) + "&wgcustomerid=" + escape(wgCustomerID) + "&wgvouchercode=" + escape(wgVoucherCode) + "&wgCurrency=" + escape(wgCurrency);
    document.write('<sc'+'ript language="JavaScript"  type="text/javascript" src="'+wgUri+'"></sc'+'ript>');
    
    </script>
    <noscript>
    <img src="http://track.webgains.com/transaction.html?wgver=1.2&wgprogramid=10121&wgrs=1&wgvalue=0&wgeventid=16783&wgorderreference=myorderreference&wgitems=&wgvouchercode=&wgcustomerid=&wgCurrency=USD" alt="" />
    </noscript>
     
    <!-- </webgains tracking code> --><?php */?>
    
    <!-- Offer Conversion: http://www.intellipaat.com/ -->
    <iframe src="https://tracking.vcommission.com/SL2MO?adv_sub=<?php echo $order->id; ?>" scrolling="no" frameborder="0" width="1" height="1"></iframe>
    <!-- // End Offer Conversion -->
    
   <?php /*?> <!-- <s2d6 affiliate network> -->    
    <script src="https://www.s2d6.com/js/globalpixel.js?x=sp&a=739&h=68424&o=<?php echo $order->id; ?>&g=&s=<?php echo $order->order_total; ?>&q=1"></script>
    <!-- </s2d6 affiliate network> --><?php */?>
    
	<?php
	
}


add_action( 'woocommerce_thankyou', 'woocommerce_conversion_code_tracking', 10 );
function woocommerce_conversion_code_tracking($order_id){
	global $orderID;
	$orderID=$order_id;
	add_action(  'wp_footer',  'conversion_code_printer'  , 20 );
		
}


/*
*	Send copy of order completed mail to admin.
*/
add_filter( 'woocommerce_email_headers', 'intellipaat_email_headers', 10, 2);

function intellipaat_email_headers( $headers, $object ) {
    if ($object == 'customer_completed_order') {
		if(TLD == 'us')
        	$headers .= 'BCC: mane.makarand@gmail.com' . "\r\n";
		else
        	$headers .= 'BCC: support@intellipaat.com' . "\r\n";
    }
    return $headers;
}

/*
*	Alter woocommerce hooks
*/
add_action('after_setup_theme','intellipaat_alter_hooks', 20);
function intellipaat_alter_hooks(){
	remove_action('woocommerce_thankyou','wplms_redirect_to_course',10);
	add_action('woocommerce_course_details','wplms_redirect_to_course',10);
	//remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_payment', 20 );
}

/*
*	Prints scripts in head tag on thank you page.
*/
add_action( 'wp_head', 'thank_you_page_head_script', 20 );
function thank_you_page_head_script() {
	
	if(is_wc_endpoint_url("order-received")) 
		echo vibe_get_option('order_received_conversion_code');
	
   /** both code works
   
   global $woocommerce;
    if ($woocommerce && is_page( woocommerce_get_page_id( 'thanks' ) ) && sizeof($woocommerce->cart->get_cart())==0) :
        die( "in second");
    endif;*/
} 


/*
*	http://woodocs.wpengine.com/document/change-email-subject-lines/
*	Add one more variable in subject of woocommerce
*/

add_filter('woocommerce_email_subject_new_order', 'intellipaat_email_subject_vars', 10, 2);
add_filter('woocommerce_email_subject_customer_completed_order', 'intellipaat_email_subject_vars', 10, 2);
add_filter('woocommerce_email_subject_customer_processing_order', 'intellipaat_email_subject_vars', 10, 2);
add_filter('woocommerce_email_subject_customer_invoice', 'intellipaat_email_subject_vars', 10, 2);
add_filter('woocommerce_email_subject_customer_invoice_paid', 'intellipaat_email_subject_vars', 10, 2);

function intellipaat_email_subject_vars( $subject, $order ) {
	global $woocommerce;
	
	return str_replace('{customername}', $order->billing_first_name.' '.$order->billing_last_name, $subject);
}


add_filter('woocommerce_email_subject_new_order', 'intellipaat_email_subject_course_name_vars', 10, 2);
function intellipaat_email_subject_course_name_vars( $subject, $order) {
	global $woocommerce;
	$product_name= array();
	
	foreach($order->get_items() as $item) {
		$product_name[] = $item['name'];	
	}
	$product_name = implode(', ',$product_name);

	return str_replace('{course_name}', $product_name, $subject);
}

add_filter('woocommerce_email_subject_customer_new_account', 'intellipaat_email_subject_new_account_vars', 10, 2);
function intellipaat_email_subject_new_account_vars( $subject, $user_object ) {
	$user_id = $user_object->ID;
	
	$billing_first_name = get_user_meta( $user_id, 'billing_first_name',true ); 
	if(empty($billing_first_name))
		$billing_first_name = $_REQUEST['first_name'];
		
	$billing_last_name = get_user_meta( $user_id, 'billing_last_name', true ); 
	if(empty($billing_last_name))
		$billing_last_name = $_REQUEST['last_name'];
	
	return str_replace('{customername}', $billing_first_name.' '.$billing_last_name, $subject);
}



/**
 * Dual currency for .com site only ------- By Makarand Mane
 **/
function intellipaat_dual_currency($return, $price, $args ){
	
	extract( apply_filters( 'wc_price_args', wp_parse_args( $args, array(
		'ex_tax_label'       => false,
		'currency'           => 'INR',
		'decimal_separator'  => wc_get_price_decimal_separator(),
		'thousand_separator' => wc_get_price_thousand_separator(),
		'decimals'           => wc_get_price_decimals(),
		'price_format'       => get_woocommerce_price_format()
	) ) ) );
	
	if(!is_numeric($price)){
		$price = str_replace(',', '', $price);
		$price = (float)$price;
	}
	
	$price = vibe_get_option('dollar_to_inr_conversion_rate') * $price; 
	$doller_in_inr = apply_filters( 'formatted_woocommerce_price',  number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

//	return '<span>'.$return.'<span class="amount rupee"> | &#8377;'. $doller_in_inr .' </span></span>';
					$credits_bill_mycart= '<span>'.$return.'<span class="amount rupee"> | &#8377;'. $doller_in_inr .' </span></span>';

						preg_match_all('/<span class="amount">(.*?)<\/span>/s', $credits_bill_mycart, $matches);
						$usdprice=$matches[0];
						preg_match_all('/<span class="amount rupee">(.*?)<\/span>/s', $credits_bill_mycart, $matches2);
						$inrprice=$matches2[0];
				
						global $wpdb;
						$ipAddr = $_SERVER['REMOTE_ADDR'];
						$fetch_ip = "SELECT countryCode, countryName FROM kvsv_geoip WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
						$query22 = $wpdb->get_results($fetch_ip);
						$valid_cc = array("IN");
						
						$creditsprice_cart="0.0";
						if (in_array($query22[0]->countryCode, $valid_cc)) 
						{
							return  $creditsprice_cart= str_replace("|","",$inrprice[0]);
						}
						else
						{
							return  $creditsprice_cart= $usdprice[0];
						}
					
	//return '<span>'.$return.'<span class="amount rupee"> | &#8377;'. $doller_in_inr .' </span></span>';
	
}
add_filter( 'wc_price', 'intellipaat_dual_currency', 10, 3);


/*
*	Send email to admin when user is created from woocommerce checkout page

add_action('woocommerce_created_customer', 'admin_email_on_registration', 10 , 1);
function admin_email_on_registration( $customer_id) {
    wp_new_user_notification( $customer_id );
	//$_SESSION['checkout_user_created']=TRUE;
	//$_SESSION['checkout_user_ID']=$customer_id;
}
*/

 /*function auto_login_new_user( $sanitized_user_login, $user_email, $errors ) {
	  if( email_exists( $user_email )) {
			wp_set_current_user($user_id);
			wp_set_auth_cookie($user_id);
			wp_redirect( home_url() );
			die();
	   }       
}*/
//add_action( 'user_register', 'auto_login_new_user' , 9, 3);



// This action was added to prior requirement and I am disabling after LMS migration.
//add_action( 'woocommerce_after_checkout_validation', 'wc_auto_login' , 9, 1);
/*function wc_auto_login($wc_post){
	$email = $wc_post['billing_email'];	
	 if( email_exists( $email  ) && !is_user_logged_in() ) {
		 	$user = get_user_by( 'email', $email  );
			wp_set_current_user($user->ID);
			//wp_set_auth_cookie($user->ID);
			$_SESSION['checkout_user_created']=FALSE;
			$_SESSION['checkout_user_ID']=$user->ID;
	   }
}*/


/***
*	If user is new then save data in session which will be used when he log in using soical media signon
*
*	do_action( 'woocommerce_thankyou', $order->id )
*/
/*function intellipaat_register_user_details_to_session($order_id){
		if(!$_SESSION['checkout_user_ID'])
			return;

		$order = new WC_Order( $order_id );
		
		$_SESSION['order_id'] = $order_id;
		$_SESSION['order_billing_email'] = $order->billing_email;
		unset($order);
}*/
//add_action( 'woocommerce_thankyou', 'intellipaat_register_user_details_to_session', 10 ,1);

?>