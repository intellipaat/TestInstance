<?php


/*
*	Sugar CRM API calll before we send email to admin using cform 7
*/
add_action( 'wpcf7_before_send_mail', 'intellipaat_cform7_WebToLead' );
function intellipaat_cform7_WebToLead( $cf7 )
{
	$submission = WPCF7_Submission::get_instance();
	if($submission && ($cf7->id == 2101 || $cf7->id == 2129)) {
		$posted_data = $submission->get_posted_data();	
		
		/*date_default_timezone_set('Asia/Kolkata');
		$now = date('d-m-Y H:i'); ".$now." GMT +5.30*/
		
		$email = $posted_data["email"];
		$fname  = $posted_data["firstname"];
		$lname  = $posted_data["lastname"];
		$mobile = $posted_data["mobile"];
		$country = $posted_data["country"];
		$description  = $posted_data["description"];
		$referer  = $posted_data["referer"];
		$courses = cart_to_course_list();
		$course_title  = $courses ? $courses : $posted_data["course_title"];
		$lead_source = $cf7->title;
		
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
		setcookie('intellipaat_lead_id', $lead->id, time()+60*60*24*7, '/' );
		intellipaatCRM_LeadToMail( $session_id , $lead->id , $course_title); 
	}
}



/*
*	WP ajax to get secutiry key for visitor form
*/
add_action("wp_ajax_nopriv_intellipaat_visitor_secure_key", "intellipaat_visitor_secure_key_callback");

function intellipaat_visitor_secure_key_callback() {
	die( wp_create_nonce("intellipaat_visitor_secure_signup_nonce") );
}

/*
*	WP ajax to get secutiry key for visitor form
*/
add_action("wp_ajax_nopriv_intellipaat_browse_course_menu", "intellipaat_ajax_browse_course_menu");
add_action("wp_ajax_priv_intellipaat_browse_course_menu", "intellipaat_ajax_browse_course_menu");

function intellipaat_ajax_browse_course_menu() {
	intellipaat_browse_course_menu();
	die();
}

 
/*
*	WP ajax to send sale initiate email to admin
*/
add_action("wp_ajax_intellipaat_userbase", "intellipaat_userbase_callback");
add_action("wp_ajax_nopriv_intellipaat_userbase", "intellipaat_userbase_callback");

function intellipaat_userbase_callback() {
	die(intellipaat_userbase());
}

function intellipaat_userbase(){
	if(TLD == 'com'){
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 
			"
				SELECT sum(meta_value) 
				FROM $wpdb->postmeta 
				WHERE meta_key = %s
			", 
			'vibe_students'
		) );
	}
	else{
		$user_total = file_get_contents('https://intellipaat.com/wp-admin/admin-ajax.php?action=intellipaat_userbase');
		if($user_total)
			return $user_total;
		else 
			return '250,000';
	}
}


function cart_to_course_list(){
	
	global  $woocommerce;
	$items = $woocommerce->cart->get_cart();
			
	if ( sizeof( $items ) <= 0 )
		return '';
		
	$courses =array();
	foreach($items as $item => $values) {
		$_product = $values['data']->post;
		$vcourses = vibe_sanitize(get_post_meta($_product->ID,'vibe_courses',false));
		foreach($vcourses as $course){
			$courses[] = get_the_title($course);
		}
		
	}
	return implode(', ',$courses);
}


function order_to_course_list($order){
		
	$courses =array();
	foreach($order->get_items() as $item ) { 
		$vcourses = vibe_sanitize(get_post_meta($item['product_id'],'vibe_courses',false));
		foreach($vcourses as $course){
			$courses[] = get_the_title($course);
		}
		
	}
	return implode(', ',$courses);
}

/*function verify_email($email){

	$username	= '';
	$password	= '';
	$api_url	= 'http://api.verify-email.org/api.php?';
				
	$url		= $api_url . 'usr=' . $username . '&pwd=' . $password . '&check=' . $email;
	
	$object		= json_decode(remote_get_contents($url)); // the response is received in JSON format; here we use the function remote_get_contents($url) to detect in witch way to get the remote content
			
	return $object;
			
	/*echo 'The email address ' . $email . ' is ' . ($object->verify_status?'GOOD':'BAD or cannot be verified') . '  '; 
	echo 'authentication_status - ' . $object->authentication_status . ' (your authentication status: 1 - success; 0 - invalid user)'; 
	echo 'limit_status - ' . $object->limit_status . ' (1 - verification is not allowed, see limit_desc; 0 - not limited)'; 
	echo 'limit_desc - ' . $object->limit_desc . ' '; 
	echo 'verify_status - ' . $object->verify_status . ' (entered email is: 1 - OK; 0 - BAD)'; 
	echo 'verify_status_desc - ' . $object->verify_status_desc . ' ';*/

//}

// Get remote file contents, preferring faster cURL if available
/*function remote_get_contents($url)
{
        if (function_exists('curl_get_contents') AND function_exists('curl_init'))
        {
                return curl_get_contents($url);
        }
        else
        {
                // A litte slower, but (usually) gets the job done
                return file_get_contents($url);
        }
}

function curl_get_contents($url)
{
        // Initiate the curl session
        $ch = curl_init();
        
        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // Removes the headers from the output
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        // Return the output instead of displaying it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // Execute the curl session
        $output = curl_exec($ch);
        
        // Close the curl session
        curl_close($ch);
        
        // Return the output as a variable
        return $output;
}*/

/*function intellipaat_add_visitor_to_abandon() {
	
	if(TLD != 'com' && TLD != 'us' )
		return;
		
	if(is_user_logged_in()) {
		
		global $woocommerce;
		
		if (function_exists('WC')) {
			$visitor_cart = WC()->cart->get_cart();
		} else {
			$visitor_cart = $woocommerce->cart->get_cart();
		}
		
		if ( sizeof( $visitor_cart ) <= 0 ){
			//if there is nothing in cart then I have added same course in cart.
			$course_id = $_REQUEST['page_id']; 			
			$pid=get_post_meta($course_id,'vibe_product',true);
			if(!$pid)
				$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
			
			if($pid)
				$woocommerce->cart->add_to_cart( $pid );
			else
				return;	
				
			$visitor_cart = $woocommerce->cart->get_cart();
			
			if ( sizeof( $visitor_cart ) <= 0 )
				return ;
			
			do_action( 'woocommerce_cart_updated' );
			
			$cart = WC()->instance()->cart;
			$cart_id = $cart->generate_cart_id($pid);
			$cart_item_id = $cart->find_product_in_cart($cart_id);
			
			if($cart_item_id){
			   $cart->set_quantity($cart_item_id,0);
			}
		}else{
			do_action( 'woocommerce_cart_updated' );
		}

		
	}else {
		intellipaat_rac_guest_entry_checkout_ajax(); // modified function for Guests
	}

}

function intellipaat_rac_guest_entry_checkout_ajax(){
	
	global $woocommerce;
	if (!is_user_logged_in()) {
		if (!isset($_COOKIE['rac_cart_id'])) { //means they didn't come mail
			if (function_exists('icl_register_string')) {
				$currentuser_lang = isset($_SESSION['wpml_globalcart_language']) ? $_SESSION['wpml_globalcart_language'] : ICL_LANGUAGE_CODE;
			} else {
				$currentuser_lang = 'en';
			}
			$visitor_mail = $_POST['user_email'];
			$visitor_first_name = $_POST['first_name'];
			$visitor_last_name = $_POST['last_name'];
			$visitor_phone = $_POST['phone'];
			$ip_address = $_SERVER["REMOTE_ADDR"];
		
			if (function_exists('WC')) {
				$visitor_cart = WC()->cart->get_cart();
			} else {
				$visitor_cart = $woocommerce->cart->get_cart();
			}
			
			if ( sizeof( $visitor_cart ) <= 0 ){
				//if there is nothing in cart then I have added same course in cart.
				$course_id = $_REQUEST['page_id']; 			
				$pid=get_post_meta($course_id,'vibe_product',true);
				if(!$pid)
					$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
				
				if($pid)
					$woocommerce->cart->add_to_cart( $pid );
				else
					return;	
					
				$visitor_cart = $woocommerce->cart->get_cart();
				
				$cart = WC()->instance()->cart;
				$cart_id = $cart->generate_cart_id($pid);
				$cart_item_id = $cart->find_product_in_cart($cart_id);
				
				if($cart_item_id){
				   $cart->set_quantity($cart_item_id,0);
				}
			}
			if ( sizeof( $visitor_cart ) <= 0 )
				return ;
				
			$visitor_details = $visitor_cart;
			
			$visitor_details['visitor_mail'] = $visitor_mail;
			$visitor_details['first_name'] = $visitor_first_name;
			$visitor_details['last_name'] = $visitor_last_name;
			$visitor_details['visitor_phone'] = $visitor_phone;
			
			global $wpdb;
			$table_name = $wpdb->prefix . 'rac_abandoncart';
			$cart_content = maybe_serialize($visitor_details);
			$user_id = "000";
			$current_time = current_time('timestamp');
			if (get_option('rac_remove_carts') == 'yes') {


				if (get_option('rac_remove_new') == 'yes') {

					$wpdb->delete($table_name, array('email_id' => $visitor_mail, 'cart_status' => 'NEW'));
				}

				if (get_option('rac_remove_abandon') == 'yes') {

					$wpdb->delete($table_name, array('email_id' => $visitor_mail, 'cart_status' => 'ABANDON'));
				}
			}

			//check for duplication
			@$check_ip = $wpdb->get_results("SELECT * FROM $table_name WHERE ip_address ='$ip_address' AND cart_status='NEW'");
			if (!is_null($check_ip[0]->id) && !empty($check_ip[0]->id)) {//update
				$wpdb->update($table_name, array('cart_details' => $cart_content, 'user_id' => $user_id, 'email_id' => $visitor_mail), array('id' => $check_ip[0]->id));
			} else {//Insert New entry
				$wpdb->insert($table_name, array('cart_details' => $cart_content, 'user_id' => $user_id, 'email_id' => $visitor_mail, 'cart_abandon_time' => $current_time, 'cart_status' => 'NEW', 'ip_address' => $ip_address, 'wpml_lang' => $currentuser_lang));
				setcookie("rac_checkout_entry", $wpdb->insert_id, time() + 3600, "/");
			}
			// echo $wpdb->insert_id;
		}
	}

}*/



/*
*	Zoho CRM API calll before we send email to admin using cform 7
*/
//add_action( 'wpcf7_before_send_mail', 'intellipaat_cform7_WebToLead' );
/*function intellipaat_cform7_WebToLead( $cf7 )
{
	$submission = WPCF7_Submission::get_instance();
	if($submission && ($cf7->id == 2101 || $cf7->id == 2129)) {
		$posted_data = $submission->get_posted_data();	
		
		$email = $cf7->posted_data["your-email"];
		$first_name  = $cf7->posted_data["your-firstname"];
		$last_name  = $cf7->posted_data["your-lastname"];
		$phone = $cf7->posted_data["your-phone"];
		$company = $cf7->posted_data["your-company"];
		$message  = $cf7->posted_data["your-message"];
		$lead_source = $cf7->title;
		$email = $posted_data["email"];
		$fname  = $posted_data["firstname"];
		$lname  = $posted_data["lastname"];
		$mobile = $posted_data["mobile"];
		$country = $posted_data["country"];
		$description  = $posted_data["description"];
		$referer  = $posted_data["referer"];
		$course_title  = $posted_data["course_title"];
		$lead_source = $cf7->title;

		$xml  = '<?xml version="1.0" encoding="UTF-8"?>'; // same error with or without this line
		$xml .= '<Leads>';
		$xml .= '<row no="1">';
		$xml .= '<FL val="First Name">'.$fname.'</FL>';
		$xml .= '<FL val="Last Name">'.$lname.'</FL>';
		$xml .= '<FL val="Email">'.$email.'</FL>';
		$xml .= '<FL val="Mobile">'.$mobile.'</FL>';
		$xml .= '<FL val="Country">'.$country.'</FL>';
		$xml .= '<FL val="Description">'.$description.'</FL>';
		$xml .= '<FL val="Lead Source">'.$lead_source.'</FL>';
		$xml .= '<FL val="Referrer">'.$referer.'</FL>';
		$xml .= '<FL val="Courses">'.$course_title.'</FL>';
		$xml .= '</row>';
		$xml .= '</Leads>'; 
		
		ZohoCRM_API_call($xml);
	}
}
*/
?>