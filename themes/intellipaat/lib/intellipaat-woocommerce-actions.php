<?php

add_action('woocommerce_add_to_cart', 'custome_add_to_cart', 1, 2);
function custome_add_to_cart($cart_item_key, $product_id) {
			  global $wpdb, $current_user;
			  $user_id = $current_user->ID;
			  if($user_id > 0){
			  $data = array('product_id'=>$product_id, 'user_id'=>$user_id, 'date'=>date('Y-m-d H:i:s'));
			  $wpdb->insert( 'ip_self_paced_courses', $data);
			  }
		}

/*
*	For ruppee symbol
*/
add_filter( 'woocommerce_currency_symbol', 'add_inr_currency_symbol' );
function add_inr_currency_symbol( $symbol ) {
	$currency = get_option( 'woocommerce_currency' );
	switch( $currency ) {
		case 'INR': $symbol = '&#8377;'; break;
	}
	return $symbol;
}

/*
*	Courses names in alll product page in column.
*/

add_action( 'manage_product_posts_custom_column' , 'Intellipaat_product_course_column', 10, 2 );
function Intellipaat_product_course_column( $column, $post_id ) {
    switch ( $column ) {
		case 'courses' :
		$vcourses=vibe_sanitize(get_post_meta($post_id,'vibe_courses',false));
		if(count($vcourses)){
			echo '<ul>';
			foreach($vcourses as $course){
				echo '<li><a href="'.get_permalink($course).'"><i class="icon-book-open"></i> '.get_the_title($course).'</a></li>';
			}
			echo '</ul>';
		}
		
		break;
	}
}
add_filter('manage_product_posts_columns', 'intellipaat_custom_column' ,9);
function intellipaat_custom_column($defaults) {
    $defaults['courses']  = 'Associated Courses';
    return $defaults;
}


/*
*	Price convertor for all domains using currency conversion rate
*/
add_filter('woocommerce_get_price','intellipaat_change_price_regular_member', 100, 2);
add_filter('woocommerce_get_regular_price','intellipaat_change_price_regular_member', 100, 2);
add_filter('woocommerce_get_sale_price','intellipaat_change_price_regular_member', 100, 2);
//add_filter('woocommerce_order_amount_item_subtotal','intellipaat_change_price_regular_member', 100, 2);
//add_filter('woocommerce_order_amount_line_subtotal','intellipaat_change_price_regular_member', 100, 2);
//add_filter('woocommerce_order_amount_item_total','intellipaat_change_price_regular_member', 100, 2);
function intellipaat_change_price_regular_member( $price, $product='' ){

	if(isset($product) && $product)
		return $price * vibe_get_option('base_currency_conversion_rate');
	
	return $price;	
}



/*
*	Change default user of woocommerce to Student
*/

function ialm_new_customer_data($new_customer_data){
	$new_customer_data['role'] = get_option( 'default_role' );
	return $new_customer_data;
}
add_filter( 'woocommerce_new_customer_data', 'ialm_new_customer_data');




/***
*	Create new contact when a user complete a sale. 
*
*	do_action( 'woocommerce_thankyou', $order->id )
*/
function intellipaat_crm_contact($order_id){	
	
		$order = new WC_Order( $order_id );
					 
		$fname  = $order->billing_first_name;
		$lname  = $order->billing_last_name;
		$email 	= $order->billing_email;
		$mobile = $order->billing_phone;
		$country_code = $order->billing_country;
		$country = WC()->countries->countries[$country_code];
		$lead_source = 'Sale_Initiated';
		
		$user_id = $order->get_user_id( );
		$contact_id = get_user_meta( $user_id, 'intellipaatCRM_contact_id' , true );
		$course_title = order_to_course_list($order);
		$payment_method = $order->payment_method;
		$order_total = $order->get_total();
		
	   /*** Sugar CRM api Call *****/
		$session_id = intellipaat_LoginToSugarCRM();
		
		if(!isset($contact_id) || empty( $contact_id ) ){
			
			/***** Create CRM contact *******/
			$parameters = array(
									"session" => $session_id,
									"module_name" => "Contacts",
									'name_value_list'=>array(  
											array('name'=>'first_name','value'=>$fname ),  
											array('name'=>'last_name','value'=>$lname ),  
											array('name'=>'phone_mobile', 'value'=>$mobile), 
											array('name'=>'lead_source','value'=> $lead_source),   
											array('name'=>'primary_address_country','value'=>$country),
											array('name'=>'email1','value'=>$email),
											array('name'=>'amount_c', 'value'=> $order_total ), 
											array('name'=>'payment_mode_c', 'value'=> $payment_method  ), 
											array('name'=>'course_title_c', 'value'=>  $course_title), 
										)								
								);
			
			$contact = SugarCRM_API_call("set_entry", $parameters); 
			$contact_id = $contact->id;
			setcookie('intellipaat_contact_id', $contact_id , time()+(60*60*24*7), '/' );	
			update_user_meta( $user_id, 'intellipaatCRM_contact_id', $contact_id ); 
		}else{
			$parameters = array(
						"session" => $session_id,
						"module_name" => "Contacts",
						'name_value_list'=>array(  
								array('name'=>'id','value'=> $contact_id ),
								array('name'=>'amount_c', 'value'=> $order_total ), 
								array('name'=>'payment_mode_c', 'value'=> $payment_method  ), 
								array('name'=>'course_title_c', 'value'=>  $course_title), 
							)								
					);
			$contact = SugarCRM_API_call("set_entry", $parameters); 
		}
		
		/****** Change lead status to converted *****/
		$lead_id = '';
		if(isset($_COOKIE['intellipaat_lead_id'])){ 
			$lead_id = $_COOKIE['intellipaat_lead_id'];
		}else{
			$get_existing_lead_id = array(
				"session" => $session_id,
				"module_name" => "Leads",
				'query' => 'leads.phone_mobile="'.$mobile.'" and leads.id in (SELECT DISTINCT er.bean_id AS id FROM email_addr_bean_rel er, email_addresses ea WHERE ea.id = er.email_address_id
					 AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = "Leads"
					 AND email_address ="'.$email.'") ',
				'order_by' => "order_by asc",
				'offset' => '0',
				'select_fields' => array('id'),
				'link_name_to_fields_array' => array(),
				'max_results' => '1', //The maximum number of results to return.        
				'deleted' => '0', //To exclude deleted records
				'Favorites' => false, //If only records marked as favorites should be returned.
			 ); 
			$existing_lead_id_result = SugarCRM_API_call('get_entry_list', $get_existing_lead_id);  /****** If lead not present in cookie then get it from CRM *****/
			
			if($existing_lead_id_result->result_count > 0){
				if(isset($existing_lead_id_result->entry_list)){				
					foreach($existing_lead_id_result->entry_list as $lead){				
						$lead_id = $lead->name_value_list->id->value;
					}
				}
			}
		}
		
		if($lead_id != ''){
			$parameters = array(
								"session" => $session_id,
								"module_name" => "Leads",
								'name_value_list'=>array(  
										array("name" => "id","value" => $lead_id ),
										array('name'=>'contact_id','value'=>$contact_id ), 
										array('name'=>'account_id','value'=>'ac920a15-abf6-150-968b-554233136a94' ), 
										array('name'=>'status', 'value'=>'Sold'),
									)
							);
		
			$lead = SugarCRM_API_call("set_entry", $parameters); 
		}
		
		/******** Stop sending mail to lead *****/
		$get_lead_mail_list_parameters = array(
		  'session' => $session_id,
		  'module_name' => 'MP_LeadToMail',
		  'query' => "mp_leadtomail.lead_id_c='".$lead_id."'",
		  'order_by' => "order_by asc ",
		  'offset' => '0',
		  'select_fields' => array('id'),
		  'link_name_to_fields_array' => array(),
		  'max_results' => '', //The maximum number of results to return.        
		  'deleted' => '0', //To exclude deleted records
		  'Favorites' => false, //If only records marked as favorites should be returned.
		);
		
		$lead_mail_result = SugarCRM_API_call('get_entry_list', $get_lead_mail_list_parameters);
		
		if($lead_mail_result->result_count > 0){
			$leadToEmail = array();
		
			if(isset($lead_mail_result->entry_list)){
			
				foreach($lead_mail_result->entry_list as $lead_mail){
				
					$leadToEmail[] = array(
											array("name" => "id","value" => $lead_mail->name_value_list->id->value),
											array("name" => "order_by","value" => 999),
										);
					
				}
					
			}
						
			//set data of  lead to mail
			$set_lead_mail = array(
				'session' => $session_id,
				'module_name' => 'MP_LeadToMail',
				"name_value_list" => $leadToEmail
			);	
			
			$lead_mail = SugarCRM_API_call('set_entries', $set_lead_mail);
		}
		
		unset($order);
}
add_action( 'woocommerce_thankyou', 'intellipaat_crm_contact', 10 ,1);

/*
*	Adds shopping cart icons to menu 
*/
add_filter('wp_nav_menu_items','intellipaat_wcmenucart', 10, 2);
function intellipaat_wcmenucart($menu, $args) {
 
	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( /*'mobile-menu' !== $args->theme_location && */'top-menu' !== $args->theme_location ) )
		return $menu;
 
	global $woocommerce;
	$viewing_cart = __('View your shopping cart', 'intellipaat');
	$start_shopping = __('Start shopping', 'intellipaat');
	$cart_url = $woocommerce->cart->get_cart_url();
	//$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
	$cart_contents_count = $woocommerce->cart->cart_contents_count;
	//$cart_contents = sprintf(_n('%d', '%d', $cart_contents_count, 'intellipaat'), $cart_contents_count);
	//$cart_total = $woocommerce->cart->get_cart_total();
	// Uncomment the line below to hide nav menu cart item when there are no items in the cart
	// if ( $cart_contents_count > 0 ) {
		if ($cart_contents_count == 0) {
			$menu_item = '<li class="menu-item woo-cart-menu empty"><a class="wcmenucart-contents" href="'. site_url('all-courses/') .'" title="'. $start_shopping .'">';
		} else {
			$menu_item = '<li class="menu-item woo-cart-menu filled"><a class="wcmenucart-contents ajax-cart-link" href="'. $cart_url .'" title="'. $viewing_cart .'">';
		}

		$menu_item .= '<i class="icon-shopping-cart"></i> ';

		$menu_item .= '<span class="cart_count">'.$cart_contents_count.'</span>';
		$menu_item .= '</a></li>';
	// Uncomment the line below to hide nav menu cart item when there are no items in the cart
	// }
	
	return $menu . $menu_item;;
 
}


/*
*	Add shop more button at moni cart 
*/
add_action("wp_ajax_intellipaat_cart", "intellipaat_cart_callback");
add_action("wp_ajax_nopriv_intellipaat_cart", "intellipaat_cart_callback");

function intellipaat_cart_callback() {	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		echo '<div class="padder content">'.do_shortcode('[woocommerce_cart]').'</div>'; 	  
    	die();
	} 
	else{
		wp_redirect( WC()->cart->get_cart_url());
		exit();
	}
}


/*
*	Add shop more button at moni cart 
*/
function intellipaat_widget_shopping_cart_before_button(){
?>
    <p class="buttons">
		<a href="<?php echo all_course_page_link(); ?>" class="button wc-forward"><?php _e( 'Shop More', 'woocommerce' ); ?></a>
    </p>
<?php
}
add_action('woocommerce_after_mini_cart', 'intellipaat_widget_shopping_cart_before_button', 30);




add_action( 'template_redirect', 'intellipaat_course_woocommerce_direct_checkout' );
function intellipaat_course_woocommerce_direct_checkout()
{   
  if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $check=vibe_get_option('direct_checkout');
        $check =intval($check);
    if(isset($check) /*&&  $check == 2*/){
      if( is_single() && get_post_type() == 'course' && isset($_GET['redirect']) && isset($_GET['type']) ){
          global $woocommerce;		  
		  $course_id = get_the_ID(); 
		  if($_GET['redirect'] != '')		  	
				$url = $_GET['redirect'];
		  else	
				$url = get_permalink($course_id);
		 
		  if($_GET['type'] == 'selfPaced'){
				$pid=get_post_meta($course_id,'vibe_product',true);
				/* below code inserted for self placed reports
				global $wpdb, $current_user;
			  	$user_id = $current_user->ID;
				$data = array('product_id'=>$course_id, 'user_id'=>$user_id, 'date'=>date('Y-m-d H:i:s'));
			    $wpdb->insert( 'ip_self_paced_courses', $data);
				 end **/
			}
		  else if($_GET['type'] == 'onlineTraining'){
				$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
			}
			
          $courses = vibe_sanitize(get_post_meta($pid,'vibe_courses',false));
          if(isset($courses) && is_array($courses) && count($courses)){
            $woocommerce->cart->add_to_cart( $pid );
			
			//set cookie to hide take course button at course page
			$course_in_cart = isset($_COOKIE['course_in_cart']) ? json_decode(stripslashes( $_COOKIE['course_in_cart']), true) : array() ;
			$course_in_cart[$course_id] = $pid; 
			setcookie('course_in_cart',json_encode($course_in_cart), time()+60*60*24*365); 
			
			//$_SESSION['new_product_added'] = 1;
			//w3tc_pgcache_flush();
			if(function_exists('w3tc_pgcache_flush_post'))
				w3tc_pgcache_flush_post($course_id); //http://wordpress.stackexchange.com/questions/7112/w3-total-cache-cache-refresh-programmatically
            //$checkout_url = $woocommerce->cart->get_checkout_url();
            wp_redirect( $url);
            exit();
          }
      }
    }
    /*if(isset($check) &&  $check == 3){ 
      if( is_single() && get_post_type() == 'product' && isset($_GET['redirect'])){ 
          global $woocommerce; 
          $courses = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_courses',false));
          
          if(isset($courses) && is_array($courses) && count($courses)){
            $woocommerce->cart->add_to_cart( get_the_ID() );
            $cart_url = $woocommerce->cart->get_cart_url(); 
            wp_redirect( $cart_url);
            exit();
          }
      }
    }*/
  }
}



?>