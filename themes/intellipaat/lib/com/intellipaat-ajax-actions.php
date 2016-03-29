<?php


/*
*	WP ajax to send sale initiate email to admin
*/
add_action("wp_ajax_intellipaat_visitor_purchase", "intellipaat_visitor_purchase_callback");
add_action("wp_ajax_nopriv_intellipaat_visitor_purchase", "intellipaat_visitor_purchase_callback");

function intellipaat_visitor_purchase_callback() {

  /* if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_visitor_purchase_nonce")) {
      exit("No naughty business please");
   } 
   
	$nonce = wp_create_nonce("intellipaat_visitor_purchase_nonce"); 
	*/  
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	   
		$fname  = isset($_REQUEST['billing_first_name']) ? $_REQUEST['billing_first_name'] : '' ;
		$lname  = isset($_REQUEST['billing_last_name']) ? $_REQUEST['billing_last_name'] : '' ;
		$email = $_REQUEST['billing_email'];
		$mobile = isset($_REQUEST['billing_phone']) ? $_REQUEST['billing_phone'] : '';
		$country_code = $_REQUEST['billing_country'];
		$country = WC()->countries->countries[$country_code];
		$courses = cart_to_course_list();
		$description  = 'User Purchasing '.$courses;
		$referer  = $_SERVER['HTTP_REFERER'];// get_permalink($_REQUEST['page_id']);
		$lead_source = 'Sale_Initiated'; 
		$lead_id = '';
			   
	   /*** Sugar CRM api Call *****/
		$session_id = intellipaat_LoginToSugarCRM();
				
		$get_existing_lead_id = array(
			"session" => $session_id,
			"module_name" => "Leads",
			'query' => 'leads.phone_mobile="'.$mobile.'" and leads.id in (SELECT DISTINCT er.bean_id AS id FROM email_addr_bean_rel er, email_addresses ea WHERE ea.id = er.email_address_id
                 AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = "Leads"
                 AND email_address ="'.$email.'") ',
			'order_by' => " leads.date_modified desc",
			'offset' => '0',
			'select_fields' => array('id', 'description'),
			'link_name_to_fields_array' => array(),
			'max_results' => '1', //The maximum number of results to return.        
			'deleted' => '0', //To exclude deleted records
			'Favorites' => false, //If only records marked as favorites should be returned.
		 ); 
		$existing_lead_id_result = SugarCRM_API_call('get_entry_list', $get_existing_lead_id); 

		if($existing_lead_id_result->result_count > 0){
			if(isset($existing_lead_id_result->entry_list)){				
				foreach($existing_lead_id_result->entry_list as $single_lead){				
					$lead_id =  $single_lead->name_value_list->id->value;
					if(!empty($single_lead->name_value_list->description->value))
						$description = $description."\r\n ---------------------------------- \r\n".$single_lead->name_value_list->description->value;
				}
			}
		}
		
		$parameters = array(
								"session" => $session_id,
								"module_name" => "Leads",
								'name_value_list'=>array(  
										array('name'=>'first_name','value'=>$fname ),  
										array('name'=>'last_name','value'=>$lname ),  
										array('name'=>'status', 'value'=>'New'),  
										array('name'=>'phone_mobile', 'value'=>$mobile),  
										array('name'=>'lead_source','value'=> $lead_source), 
										array('name'=>'primary_address_country','value'=>$country),
										array('name'=>'email1','value'=>$email),  
										array('name'=>'description','value'=>$description ),  
										array('name'=>'reference_url_c','value'=>$referer  ),  
										array('name'=>'course_title_c','value'=> $courses ),
										array('name'=>'lead_source_description','value'=>$_SESSION['doc_referrer']),  
										array('name'=>'alt_address_street','value'=>$_SESSION['doc_utm_medium']),
										array('name'=>'status_description','value'=>$_SESSION['doc_utm_campaign']),  
										array('name'=>'primary_address_street','value'=>$_SESSION['doc_utm_source']), 
									)								
							);
		//$lead_id = isset($_COOKIE['intellipaat_lead_id']) ? $_COOKIE['intellipaat_lead_id'] : '' ;
		/*if(isset($_COOKIE['intellipaat_lead_id'])){ 
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
			$existing_lead_id_result = SugarCRM_API_call('get_entry_list', $get_existing_lead_id);  // If lead not present in cookie then get it from CRM
			var_dump($existing_lead_id_result);
			if($existing_lead_id_result->result_count > 0){
				if(isset($existing_lead_id_result->entry_list)){				
					foreach($existing_lead_id_result->entry_list as $lead){				
						$lead_id = $lead->name_value_list->id->value;
					}
				}
			}
		}*/
		if($lead_id != '')
			$parameters['name_value_list'][] =	array("name" => "id","value" => $lead_id );
		
		$lead = SugarCRM_API_call("set_entry", $parameters); 

		if($lead_id == ''){
			intellipaatCRM_LeadToMail( $session_id , $lead->id , $courses);
		}
		setcookie('intellipaat_lead_id', $lead->id, time()+60*60*24, '/' );
		
		$result['lead_id'] = $lead->id;

		$now = date('Y-m-d H:i:s');

		$message = '<!DOCTYPE HTML>
						<html>
						<head></head>
						<body>
							<ul>
								<li><strong>Key</strong> : <strong>Value</strong> </li>';
							
								foreach($_REQUEST as $k => $v){
									$message .=	'<li><span style="width=200px"><strong>'.$k.'</strong></span> : '.$v.'</li>';
								}
								/*<strong>New Visitor</strong> @ '.site_url().' <br /><br />
								<strong>Email</strong> : '.$_REQUEST['billing_email'].' <br />
								<strong>Phone</strong> : '.$_REQUEST['phone'].'  <br />
								<strong>Visited url</strong> : '.get_permalink($_REQUEST['page_id']).'<br />
								<strong>IP Address </strong>: '.$_SERVER['REMOTE_ADDR'].' <br />
								<strong>Date/time</strong> '.$now.'*/

			$message .=				'</ul>
										<hr />';
			
			ob_start(); ?>
			
							<table border="1" cellpadding="5" bordercolor="#333333" cellpadding="5" >
								<thead>
									<tr>
										<th class="product-name"><?php _e( 'Product', 'vibe' ); ?></th>
										<th class="product-total"><?php _e( 'Total', 'vibe' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										do_action( 'woocommerce_review_order_before_cart_contents' );
						
										foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
											$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						
											if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
												?>
												<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
													<td class="product-name">
														<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
														<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
														<?php echo WC()->cart->get_item_data( $cart_item ); ?>
													</td>
													<td class="product-total">
														test<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?> <small>(Inclusive of all taxes)</small>
													</td>
												</tr>
												<?php
											}
										}
						
									?>
								</tbody>
								<tfoot>
						
									<tr class="cart-subtotal">
										<th><?php _e( 'Cart Subtotal', 'vibe' ); ?></th>
										<td><?php wc_cart_totals_subtotal_html(); ?></td>
									</tr>
						
									<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
										<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
											<th><?php _e( 'Coupon:', 'vibe' ); ?> <?php echo esc_html( $code ); ?></th>
											<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
										</tr>
									<?php endforeach; ?>
						
									<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						
										<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
						
										<?php wc_cart_totals_shipping_html(); ?>
						
										<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
						
									<?php endif; ?>
						
									<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
										<tr class="fee">
											<th><?php echo esc_html( $fee->name ); ?></th>
											<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
										</tr>
									<?php endforeach; ?>
						
									<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
										<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
											<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
												<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
													<th><?php echo esc_html( $tax->label ); ?></th>
													<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr class="tax-total">
												<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
												<td><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></td>
											</tr>
										<?php endif; ?>
									<?php endif; ?>
						
									<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
										<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
											<th><?php _e( 'Coupon:', 'vibe' ); ?> <?php echo esc_html( $code ); ?></th>
											<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
										</tr>
									<?php endforeach; ?>
						
									<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
						
									<tr class="order-total">
										<th><?php _e( 'Order Total', 'vibe' ); ?></th>
										<td><?php wc_cart_totals_order_total_html(); ?></td>
									</tr>
						
									<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
						
								</tfoot>
							</table>

                     <?php
				$cart =	ob_get_contents();
				ob_end_clean();
				
				$product_name = array();
				$items = WC()->cart->get_cart();
				foreach($items as $item => $values) { 
                	$_product = $values['data']->post;
					$product_name[] = $_product->post_title;
				} 
				$product_name = implode(', ', $product_name);
				
		$message .=				$cart.'</body>
						</html>' ;
		
		$headers .= "Reply-To: ".$_REQUEST['billing_email']." \r\n";
		
		$headers .= "MIME-Version: 1.0 \r\n";
		
		$headers .= "Content-type: text/html; charset=utf-8 \r\n";
		
		$headers .= "Content-Transfer-Encoding: quoted-printable \r\n";
					
		$mail = wp_mail( get_option( 'admin_email' ) , "New sale initiated by ".$_REQUEST['billing_first_name']." ".$_REQUEST['billing_last_name']." for ".$product_name." @".site_url(), $message, $headers); 
		
		global $wpdb;
		
		/*$now = date('Y-m-d H:i:s');
		$wpdb->insert( 
			$wpdb->prefix.'cf7dbplugin_submits', 
			array( 
				'submit_time' 	=> 	$now ,
				'form_name'		=>	'Checkout New Sales',
				'field_name'	=>	'',
				'email'			=> 	$_REQUEST['user_email'] ,
				'phone' 		=> 	$_REQUEST['phone'], 
				'ip' 			=> 	$_SERVER['REMOTE_ADDR'] ,
				'visited_url' 	=> 	get_permalink($_REQUEST['page_id']), 
			)
		);
		*/
		$result['type'] = "success";
		$result['type'] = "success";
		//$result['inserted_row'] = $wpdb->insert_id ;
      	$result = json_encode($result);
      	echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();
}




/*
*	WP ajax to to submit visitor form and regirter user.
*/
add_action("wp_ajax_nopriv_intellipaat_checkout_login", "intellipaat_checkout_login_callback");

function intellipaat_checkout_login_callback() {

   /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_visitor_secure_signup_nonce") && !is_singular()) {
      exit("No naughty business please");
   }  */ 
   if ( !isset($_REQUEST['ajaxform']) || empty($_REQUEST['ajaxform']) || $_REQUEST['ajaxform'] != 'set') {
      exit("Oops! Something bad happened, please refresh this page and retry again.");
   }   
   if ( !isset($_REQUEST['username']) || empty($_REQUEST['username']) ||  !isset($_REQUEST['password']) || empty($_REQUEST['password'])) {
      exit("Oops! Something bad happened, please refresh this page and retry again.");
   }   
   
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	   	$result = array();
		$creds = array();
		$creds['user_login'] = checkMyInput($_REQUEST['username']);
		$creds['user_password'] =checkMyInput($_REQUEST['password']);
		$creds['remember'] = checkMyInput($_REQUEST['rememberme']);
		$user = wp_signon( $creds, true );
		if ( is_wp_error($user) ){
			$result['result'] = false;
			$result['error'] = '<div class="alert alert-danger alert-dismissible" role="alert">
									  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									  '.$user->get_error_message().'
								</div>';
		}
		else{
			$result['result'] = true;			
			$result['message'] = '<div class="alert alert-success" role="alert">
									  Login sucessful, please wait for a moment ...
								</div>';
			$result['location'] = $_REQUEST["redirect_to"];			
		}
		$result = json_encode($result);
		echo $result;
	}
	else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	
	die();
}

/*
*	WP ajax to to submit visitor form and regirter user.
*/
add_action("wp_ajax_nopriv_intellipaat_visitor_secure_login", "intellipaat_visitor_secure_login_callback");

function intellipaat_visitor_secure_login_callback() {

   /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_visitor_secure_signup_nonce") && !is_singular()) {
      exit("No naughty business please");
   }  */ 
   if ( !isset($_REQUEST['ajaxform']) || empty($_REQUEST['ajaxform']) || $_REQUEST['ajaxform'] != 'set') {
      exit("No naughty business please");
   }   
   if ( !isset($_REQUEST['log']) || empty($_REQUEST['log']) ||  !isset($_REQUEST['pwd']) || empty($_REQUEST['pwd'])) {
      exit("No naughty business please");
   }   
   
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	   $result = array();
	   $user = wp_signon();
		if ( is_wp_error($user) ){
			$result['result'] = false;
			$result['error'] = '<div class="alert alert-danger alert-dismissible" role="alert">
									  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									  '.$user->get_error_message().'
								</div>';
		}
		else{
			$result['result'] = true;			
			$result['message'] = '<div class="alert alert-success" role="alert">
									  Login sucessful, please wait for a moment ...
								</div>';
			$result['location'] = $_REQUEST["redirect_to"];			
		}
		$result = json_encode($result);
      	echo $result;
	}
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();
}




/*
*	WP ajax to to submit visitor form and regirter user.
*/
add_action("wp_ajax_nopriv_intellipaat_visitor_secure_signup", "intellipaat_visitor_secure_signup_callback");

function intellipaat_visitor_secure_signup_callback() {
	
   if ( !isset($_REQUEST['ajaxform']) || empty($_REQUEST['ajaxform']) || $_REQUEST['ajaxform'] != 'set') {
      exit("No naughty business please");
   }  
   if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_visitor_secure_signup_nonce") && !is_singular( 'course' )) {
      exit("No naughty business please");
   }   
   if ( !isset($_REQUEST['user_email']) || empty($_REQUEST['user_email']) || !isset($_REQUEST['account_password']) || empty($_REQUEST['account_password']) || !isset($_REQUEST['country']) || empty($_REQUEST['country']) ) {
		exit("No naughty business please");
   }  
   if(!is_email($_REQUEST['user_email'])){
   		$result['result'] = false;
		$result['error'] = '<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Please enter valid email address.
							  </div>';
							  
		$result = json_encode($result);
      	echo $result; 
   		die();
   }
   
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	   		
		$fname  = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '' ;
		$lname  = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '' ;
		$email = $_REQUEST['user_email'];
		$mobile = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
		$country_code = $_REQUEST['country'];
		$country = WC()->countries->countries[$country_code];
		$courses = cart_to_course_list();
		$course_title  = $courses ? $courses :  html_entity_decode(get_the_title($_REQUEST['page_id']),ENT_QUOTES, 'UTF-8');
		$description  = 'User Filling signup form';
		$referer  = get_permalink($_REQUEST['page_id']);
		$lead_source_post_type = $_REQUEST['lead_source'];
		$lead_source = 'Visitor Sign Up';
		
		$password = isset($_REQUEST['account_password']) ? $_REQUEST['account_password'] : wp_generate_password() ;
		$email_status_in_mail ='';
		
		global $wpdb;
		$now = date('Y-m-d H:i:s');
	   	$wpdb->insert( 
			$wpdb->prefix.'intellipaat_visitors', 
			array( 
				'name' 			=> $fname.' '.$lname,
				'email' 		=> $email,
				'phone' 		=> $mobile, 
				'country' 		=> $country,
				'lead_source' 	=> $lead_source_post_type,
				'course'		=> $course_title, 
				'ip' 			=> $_SERVER['REMOTE_ADDR'] ,
				'visited_url' 	=> $referer, 
				'date_time' 	=> $now ,
			)
		);	 //adding record to visitor table	
		$visitor_id = $wpdb->insert_id;
		$result['inserted_row'] = $visitor_id ;
		
		/*setcookie('intellipaat_visitor', true, time()+60*60*24*30, '/');
		setcookie('intellipaat_visitor_email', $email, time()+60*60*24*30, '/');
		if($mobile)
			setcookie('intellipaat_visitor_phone', $mobile, time()+60*60*24*30, '/');	*/
		
		/***** Updating student count on course page ******/
		if($lead_source_post_type === 'course'){
			$udate_result = $wpdb->query(
								 $wpdb->prepare( "UPDATE $wpdb->postmeta  SET meta_value =  meta_value + 1 WHERE  meta_key LIKE 'vibe_students' AND post_id=%d", $_REQUEST['page_id'])
							);
			if($udate_result)
				$result['student_count'] = 'updated student count. '.$udate_result;		
		}
		
		/***** Validating email address ******/
		//$email_object = verify_email($email);
		//$verify_status = 1;$email_object->verify_status;
		
		/*$email_status_in_mail = '<br><br>
			<strong>Verify Status</strong> - ' . ($verify_status ? "OK" :"BAD"). '<br>
			<strong>Verify status_desc</strong> - ' . $email_object->verify_status_desc.'<br><br>
			<strong>verify-email.org Authentication status</strong> - ' . ($email_object->authentication_status ? "success" : "invalid user") .'<br>
			<strong>Limit status</strong> - ' . ($email_object->limit_status ? " verification is not allowed": "not limited" ). '<br>
			<strong>Limit desc</strong> - ' . $email_object->limit_desc  ;
		
		/***** Adding to log table ******/
		/*if($verify_status)
			$desc='Valid Email';
		else if(!$email_object->verify_status_desc)
			$desc='Fake email or may be from Invalid Domain';
		else{
			$desc = strip_tags($email_object->verify_status_desc);
			$desc=substr($desc , strpos($desc, '550'), 99);
		}*/
		
		/*$wpdb->insert( 
			$wpdb->prefix.'intellipaat_visitors_log', 
			array( 
				'id' => $visitor_id,
				'email' => $email,
				'flag' => $verify_status, 
				'reason' => $desc,
			)
		);	 //adding record to visitor table	
		$visitor_id = $wpdb->insert_id;*/
		
		/* Zoho code disabled temporary
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>'; // same error with or without this line
		$xml .= '<Leads>';
		$xml .= '<row no="1">';
		if(isset($fname)) $xml .= '<FL val="First Name">'.$fname.'</FL>';
		if(isset($lname)) $xml .= '<FL val="Last Name">'.$lname.'</FL>';
		$xml .= '<FL val="Email">'.$email.'</FL>';
		if(isset($mobile))  $xml .= '<FL val="Mobile">'.$mobile.'</FL>';
		$xml .= '<FL val="Country">'.$country.'</FL>';
		$xml .= '<FL val="Courses">'.html_entity_decode($course_title,ENT_QUOTES, 'UTF-8').'</FL>';
		$xml .= '<FL val="Lead Source">'.$lead_source.'</FL>';
		$xml .= '<FL val="Referrer">'.$referer.'</FL>';
		$xml .= '</row>';
		$xml .= '</Leads>';
		ZohoCRM_API_call($xml); //Zoho CRM API call for lead genaration.*/
		
		/*** Sugar CRM api Call *****/
		$session_id = intellipaat_LoginToSugarCRM();
				
		$get_existing_lead_id = array(
			"session" => $session_id,
			"module_name" => "Leads",
			'query' => 'leads.phone_mobile="'.$mobile.'" and leads.id in (SELECT DISTINCT er.bean_id AS id FROM email_addr_bean_rel er, email_addresses ea WHERE ea.id = er.email_address_id
                 AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = "Leads"
                 AND email_address ="'.$email.'") ',
			'order_by' => " leads.date_modified desc",
			'offset' => '0',
			'select_fields' => array('id', 'description'),
			'link_name_to_fields_array' => array(),
			'max_results' => '1', //The maximum number of results to return.        
			'deleted' => '0', //To exclude deleted records
			'Favorites' => false, //If only records marked as favorites should be returned.
		 ); 
		$existing_lead_id_result = SugarCRM_API_call('get_entry_list', $get_existing_lead_id); 

		if($existing_lead_id_result->result_count > 0){
			if(isset($existing_lead_id_result->entry_list)){				
				foreach($existing_lead_id_result->entry_list as $single_lead){				
					$lead_id =  $single_lead->name_value_list->id->value;
					if(!empty($single_lead->name_value_list->description->value))
						$description = $description."\r\n ---------------------------------- \r\n".$single_lead->name_value_list->description->value;
				}
			}
		} 
		$parameters = array(
								"session" => $session_id,
								"module_name" => "Leads",
								'name_value_list'=>array(  
										array('name'=>'first_name','value'=>$fname ),  
										array('name'=>'last_name','value'=>$lname ),  
										array('name'=>'status', 'value'=>'New'),  
										array('name'=>'phone_mobile', 'value'=>$mobile),  
										array('name'=>'lead_source','value'=>str_replace(' ','_',$lead_source)), 
										array('name'=>'primary_address_country','value'=>$country),
										array('name'=>'email1','value'=>$email),  
										array('name'=>'description','value'=>$description ),  
										array('name'=>'reference_url_c','value'=>$referer  ),  
										array('name'=>'course_title_c','value'=>$course_title ),
										array('name'=>'lead_source_description','value'=>$_SESSION['doc_referrer']),  
										array('name'=>'alt_address_street','value'=>$_SESSION['doc_utm_medium']),
										array('name'=>'status_description','value'=>$_SESSION['doc_utm_campaign']),  
										array('name'=>'primary_address_street','value'=>$_SESSION['doc_utm_source']), 
									)								
							);
		
		if($lead_id)
			$parameters['name_value_list'][] = array("name" => "id","value" => $lead_id);	
		
		$lead = SugarCRM_API_call("set_entry", $parameters);	
		setcookie('intellipaat_lead_id', $lead->id, time()+60*60*24*365, '/' );
		intellipaatCRM_LeadToMail( $session_id , $lead->id , $course_title);
		
		/*** Create user account on website if email is valid and account not present with same mail address **/
		if ( email_exists($email) == false) {
			$user_id = wc_create_new_customer($email,'',$password );
			
			if ( !is_wp_error( $user_id ) ){
				$userdata = array(
					'ID'			=>  $user_id,
					'first_name'	=>  $fname,
					'last_name'		=>  $lname,
					'display_name' 	=>	$fname.' '.$lname,
				);
				
				wp_update_user( apply_filters( 'woocommerce_checkout_customer_userdata', $userdata, $this ) );
				
				update_user_meta($user_id, 'billing_first_name', $fname);
				update_user_meta($user_id, 'shipping_first_name', $fname);
				update_user_meta($user_id, 'billing_last_name', $lname);
				update_user_meta($user_id, 'shipping_last_name', $lname);
				update_user_meta($user_id, 'billing_email', $email);
				update_user_meta($user_id, 'billing_phone', $mobile);
				update_user_meta($user_id, 'billing_country', $country_code);
				update_user_meta($user_id, 'shipping_country', $country_code);
				wp_set_current_user($user_id);
				wp_set_auth_cookie($user_id);
				$result['user_id'] = $user_id ;	
				
				$result['result'] = true;
				$result['message'] = '<div class="alert alert-success" role="alert">
										User account created. Welcome to Intellipaat.
								  </div>';
			}	
			else{ //If user creation fails then send an email to admin 
				wp_mail( "mane.makarand@gmail.com" , "User user creation failure. This user can be spam.", $email."\r\n\r\n".print_r($_REQUEST, TRUE).print_r($user_id, TRUE) ); 
				$result['result'] = false;
				$result['error'] = '<div class="alert alert-danger alert-dismissible" role="alert">
									  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									  		#420A Can not create an account. Please contact system administrator.
									  </div>';
			}
		}else{			
			$result['result'] = false;
			$result['error'] = '<div class="alert alert-danger alert-dismissible" role="alert">
									  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									  Account already exists with this email. <strong><a href="'.wp_lostpassword_url().'" class="alert-link">Click here to retrive password</a></strong>.
								</div>';
		} 
		
		/***** Send mail to amin *****/
		$message = '<!DOCTYPE HTML>
						<html>
						<head></head>
						<body>
							<p>
								<strong>New Visitor</strong> @ '.site_url().' <br /><br />
								<strong>Full Name</strong> : '.$fname.' '.$lname.' <br />
								<strong>Email</strong> : '.$email.' <br />
								<strong>Phone</strong> : '.$mobile.'  <br />
								<strong>Country</strong> : '.$country.'  <br />
								<strong>Course Title</strong> : '.$course_title.'  <br />
								<strong>Visited url</strong> : '.$referer.'<br />
								<strong>IP Address </strong>: '.$_SERVER['REMOTE_ADDR'].' <br />
								<strong>Date/time</strong> '.$now.$email_status_in_mail. '
							</p>
						</body>
						</html>' ;
		
		$headers .= "Reply-To: ".$email." \r\n";
		
		$headers .= "MIME-Version: 1.0 \r\n";
		
		$headers .= "Content-type: text/html; charset=utf-8 \r\n";
		
		$headers .= "Content-Transfer-Encoding: quoted-printable \r\n";
		
		$headers .= 'Cc: sales@intellipaat.com' . "\r\n";
					
		wp_mail( get_option( 'admin_email' ) , "New visitor @ ".site_url(), $message, $headers); 
		
		/*if($verify_status)
			intellipaat_add_visitor_to_abandon();*/
		
      	$result = json_encode($result);
      	echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   } 

   die();
}



/************************************************************************/
			
/************************************************************************/
$YITH_WCWL2 =new YITH_WCWL();
			add_action( 'init', array( $YITH_WCWL2, 'create_lead_CRM' ) );
            add_action( 'wp_ajax_add_to_wishlist', array( $YITH_WCWL2, 'create_lead_CRM' ) );
            add_action( 'wp_ajax_nopriv_add_to_wishlist', array( $YITH_WCWL2, 'create_lead_CRM' ) ); 

add_action('woocommerce_add_to_cart', 'create_lead_CRM');
add_action('take_course_crm_events', 'create_lead_CRM');

function create_lead_CRM() {
session_start();
global  $woocommerce;
global $wpdb;
global $current_user;
		$course_title="";
		$lead_source="";
		$description ="";
		get_currentuserinfo();
		$course_id=get_the_ID();
		if($_REQUEST['add_to_wishlist']!="")
		{
			$course_id=$_REQUEST['add_to_wishlist'];
		}
		$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
		
		if($_REQUEST['type']=="selfPaced"){$lead_source = "Self Paced Course";}
		else if($_REQUEST['type']=="onlineTraining"){ $lead_source = "online Training";} 
		else{ $lead_source = "Add to Wish list"; } 
		$post_7 = get_post($course_id); 
		$title = $post_7->post_title;
		$course_title  =$title;
		$description   =$title;
		$all_meta_for_user = get_user_meta( $current_user->ID );
		
		if ( is_user_logged_in() ) {
				 $country_code     = get_user_meta( get_current_user_id(), 'billing_country', true );
		 }
		 else{			 
				 $country_code	= WC()->customer->get_country( );
		}
		if($country_code)
			$country = WC()->countries->countries[$country_code];
		else{
			$ipAddr = $_SERVER['REMOTE_ADDR'];
			$fetch_ip = "SELECT countryCode, countryName FROM kvsv_geoip WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
			$query22 = $wpdb->get_results($fetch_ip);
			$country =$query22[0]->countryName;
		}

		$now = date('Y-m-d H:i:s');
	   	/*** Sugar CRM api Call *****/
		$session_id = intellipaat_LoginToSugarCRM();
		
		$fname  = isset($current_user->user_firstname) ? $current_user->user_firstname : 'Non_member First Name' ;
		$lname  = isset($current_user->user_lastname) ? $current_user->user_lastname : 'Non_member Last Name' ;
		$mobile  = isset($all_meta_for_user['billing_phone'][0]) ? $all_meta_for_user['billing_phone'][0] : 'phone_mobile' ;
		$email = $current_user->user_email? $current_user->user_email : 'Non_member email' ;
		$parameters = array(
								"session" => $session_id,
								"module_name" => "Leads",
								'name_value_list'=>array(  
										array('name'=>'lead_source_description','value'=>$_SESSION['doc_referrer']),  
										array('name'=>'alt_address_street','value'=>$_SESSION['doc_utm_medium']),
										array('name'=>'status_description','value'=>$_SESSION['doc_utm_campaign']),  
										array('name'=>'primary_address_street','value'=>$_SESSION['doc_utm_source']),  
										array('name'=>'status', 'value'=>'New'),  
										array('name'=>'lead_source','value'=>str_replace(' ','_',$lead_source)), 
										array('name'=>'primary_address_country','value'=>$country),
										array('name'=>'email1','value'=>$email),  
										array('name'=>'phone_mobile', 'value'=>""),  
										array('name'=>'lead_source_description','value'=>$_SESSION['doc_referrer']),  
										array('name'=>'alt_address_street','value'=>$_SESSION['doc_utm_medium']),
									)								
							);
	
// 
/*
 $_SESSION['doc_referrer']=$_POST['flag_currency'];
    $_SESSION['doc_utm_Campaign']=$_POST['doc_utm_Campaign'];
    $_SESSION['doc_utm_source']=$_POST['doc_utm_source'];
    $_SESSION['doc_utm_Medium']=$_POST['doc_utm_Medium'];*/
	$lead = SugarCRM_API_call("set_entry", $parameters);	
	//$result_crm = "==========================".json_encode($_SESSION);
	//$result_crm .= "==========================".json_encode($parameters);
	//$result_crm .= "==========================".json_encode($lead);
	//'name'=>'reference_url_c','value'=>$referer 
	
	//$friends = 'kovitsv@gmail.com'; 
   //wp_mail( $friends, "Lead Create New bgbg", 'I just put something on my blog:test me'.$result_crm);

}


add_action('wp_ajax_contact_form_me', 'contact_form_me');
add_action('wp_ajax_nopriv_contact_form_me', 'contact_form_me');

function contact_form_me()
{
    $unit_id= $_POST['lmsunit_id'];
    $course_id = $_POST['lmscourse_id'];
    $content=$_POST['note'];
	$section=$_POST['section'];
    if(!is_user_logged_in() || !is_numeric($course_id) || !is_numeric($unit_id) || strlen($content)==0 ){
      _e('Not Allowed','vibe');
      die();
    }

     $user_id = get_current_user_id();
    $comment_data = array(
      'comment_post_ID'=>$unit_id,
      'comment_content' => $content,
      'comment_type' => 'note',
      'user_id' => $user_id,
      'comment_approved' => 1,
      );
    if(is_numeric($reply) && $reply){
      $comment_data['comment_parent'] = $reply;
      $comment_data['comment_type'] = 'creply';
    }

    $comment_id =wp_insert_comment($comment_data);
    if(is_numeric($comment_id)){
      if(is_numeric($reply) && $reply){
        add_comment_meta($comment_id,'reply_'.$unit_id.'_'.$section,$comment_data['comment_parent']);
      }else{
        add_comment_meta($comment_id,'unit'.$unit_id.'_'.$user_id,$section);
      }
      echo $comment_id;
    }else
      _e('Unable to post','vibe');
    die();   
}


add_action('wp_ajax_get_lms_comments', 'get_lms_comments');
add_action('wp_ajax_nopriv_get_lms_comments', 'get_lms_comments');

function get_lms_comments()
{
	
    $unit_id=$_POST['lmsunit_id'];
    $section=$_POST['section'];
    /*if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($unit_id)){
       _e('Security check Failed. Contact Administrator.','vibe');
       die();
    }*/
//    $num = $_POST['num'];
    $num = 9;
    if(!$num || !is_numeric($num) || $num  < 10)
      $num = 10;
    else
      $num= $num*2;

    $num = apply_filters('wplms_unit_comments_per_section',$num);
    global $wpdb;
    $user_id = get_current_user_id();

	$query = $wpdb->prepare("
      SELECT rel.meta_value,comments.comment_ID,comments.comment_date, comments.user_id,comments.comment_content,comments.comment_type
      FROM {$wpdb->comments} as comments
      LEFT JOIN {$wpdb->commentmeta} AS rel ON comments.comment_ID = rel.comment_id
      WHERE   comments.comment_post_ID = %d and  comments.user_id = %d and  comments.comment_type = 'note'
      ORDER BY comments.comment_date DESC
      LIMIT 0,%d
      ",$unit_id,$user_id,$num);

    $query = apply_filters('wplms_unit_comments_query',$query,$unit_id);

    $results = $wpdb->get_results($query,ARRAY_A);
    if(isset($results) && is_array($results) ){
      $json_array = array();
      $i=0;$j=1;
	 echo " <ul id='notes-list'>";
			//	<div class='inplaceeditor-delete'>×</div>
      foreach($results as $result){
          $avatar=get_avatar($result['user_id']);
          preg_match( '#src=["|\'](.+)["|\']#Uuis', $avatar, $matches );
          $seconds_span = time()-strtotime($result['comment_date']);

		echo "<li class='display-flex' ><div class='marker'><time>".$j++."</time></div>
				<div class='ud-inplacenoteeditor fx' >
					".$result['comment_content']."
				</div>
			</li>";  
					
          $json_array[$i][$result['meta_value']]=array(
            'ID' => $result['comment_ID'],
            'content' => $result['comment_content'],
            'time'=> tofriendlytime($seconds_span),
            'type'=>$result['comment_type'],
            'author'=>Array(
              'user_id' => $result['user_id'],  
              'img'=> $matches[1],
              'name'=>bp_core_get_user_displayname($result['user_id']),
              'link'=>bp_core_get_user_domain($result['user_id'])
              ),
          );

          if(($result['user_id'] == $user_id) || (current_user_can('edit_posts'))){
            $json_array[$i][$result['meta_value']]['controls']=array(
              'edit_unit_comment'=>1,
              'instructor_reply_unit_comment'=>1,
            );
            if($result['comment_type'] == 'public'){
              $json_array[$i][$result['meta_value']]['controls']['private_unit_comment']=1;
            }else{
              $json_array[$i][$result['meta_value']]['controls']['public_unit_comment']=1;
            }
            $json_array[$i][$result['meta_value']]['controls']['reply_unit_comment']=1;
            $json_array[$i][$result['meta_value']]['controls']['popup_unit_comment']=1;
            $json_array[$i][$result['meta_value']]['controls']['remove_unit_comment']=1;
          }else{
            $json_array[$i][$result['meta_value']]['controls']=array(
              'reply_unit_comment'=>1,
              'instructor_reply_unit_comment'=>1, 
            );
          }
          global $wpdb;
          $replies = $wpdb->get_results($wpdb->prepare("SELECT comment_id FROM {$wpdb->commentmeta} WHERE meta_key = %s and meta_value = %d",'reply_'.$unit_id.'_'.$_POST['section'],$result['comment_ID']),ARRAY_A);
          if(isset($replies) && is_array($replies)){
            if(is_array($replies[0]) && is_numeric($replies[0]['comment_id']) && $replies[0]['comment_id']){
              $json_array[$i][$result['meta_value']]['controls']['reply_unit_comment'] = $replies[0]['comment_id'];
            }
          }
          $i++;
        }echo " </ul>";
		 $j=0;
      $json_array=array_reverse($json_array);
      json_encode($json_array);
	  $obj = json_decode($json_array);
	 
    }
    die();
  
}


add_action('wp_ajax_save_lms_ratting', 'save_lms_ratting');
add_action('wp_ajax_nopriv_save_lms_ratting', 'save_lms_ratting');

function save_lms_ratting()
{
	
    $unit_id=$_POST['lmsunit_id'];
    $rattingvalue=$_POST['rattingvalue'];
	
	$unit_id= $_POST['lmsunit_id'];
    $course_id = $_POST['lmscourse_id'];
    $content=$rattingvalue;
    
    $user_id = get_current_user_id();
    $comment_data = array(
      'comment_post_ID'=>$unit_id,
      'comment_content' => $content,
      'comment_type' => 'lms_rating',
      'comment_agent' => 'LMS Screen',
      'user_id' => $user_id,
      'comment_approved' => 1,
      );
    
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'comments';
	
	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE comment_post_ID='".$unit_id."' and comment_type='lms_rating' and user_id='".$user_id."'", OBJECT );
	
	 if(isset($results) && is_array($results) && count($results)>0 ){ 
		$wpdb->query("UPDATE $table_name SET comment_content='".$content."' WHERE comment_ID='".$results[0]->comment_ID."' and comment_type='lms_rating' and user_id='".$results[0]->user_id."'");
		$comment_id=$results[0]->comment_ID;
		
	 }else{
		 $comment_id =wp_insert_comment($comment_data);
	 }
	
    $reviews=$content;
    if(is_numeric($comment_id)){
      $ratmeta = '';
			for($i=1;$i<=5;$i++){
				if($reviews >= 1){
					$ratmeta .='<span class="fill" onclick="get_lms_ratting('.$i.');"></span>';
				}else{
					$ratmeta .='<span onclick="get_lms_ratting('.$i.');"></span>';
				}
				$reviews--;
			}
		echo	$ratmeta .='';
    }else
      _e('Unable to post','vibe');
    die();   
}

add_action('wp_ajax_select_intellipaat_flag_currency', 'select_intellipaat_flag_currency');
add_action('wp_ajax_nopriv_select_intellipaat_flag_currency', 'select_intellipaat_flag_currency');

function select_intellipaat_flag_currency()
{
	echo  $_SESSION['REMOTE_ADDR_CUREE']=$_POST['flag_currency'];
	
	if($_SESSION['REMOTE_ADDR_CUREE'] == 'IN')
		WC()->customer->set_country('IN');
	else if(WC()->customer->get_country() == 'IN')
		WC()->customer->set_country('US');
		
    die();   
}


add_action('wp_ajax_select_doc_referrer', 'select_doc_referrer');
add_action('wp_ajax_nopriv_select_doc_referrer', 'select_doc_referrer');

function select_doc_referrer()
{

    $_SESSION['doc_referrer']=$_POST['flag_currency'];
    $_SESSION['doc_utm_campaign']=$_POST['doc_utm_campaign'];
    $_SESSION['doc_utm_source']=$_POST['doc_utm_source'];
    $_SESSION['doc_utm_medium']=$_POST['doc_utm_medium'];
	print_r($_SESSION);
	
    die();   
}
/*
Query to create table

CREATE TABLE IF NOT EXISTS `ip_intellipaat_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `country` varchar(30) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `ip` varchar(20) NOT NULL,
  `visited_url` varchar(150) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `ip_intellipaat_visitors_log` (
  `id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `flag` tinyint(1) NOT NULL,
  `reason` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/
?>
