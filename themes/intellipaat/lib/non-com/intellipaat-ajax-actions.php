<?php

/*
*	WP ajax to to submit visitor form and regirter user.
*/
add_action("wp_ajax_nopriv_intellipaat_visitor_secure_signup", "intellipaat_visitor_secure_signup_callback");

function intellipaat_visitor_secure_signup_callback() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_visitor_secure_signup_nonce") && !is_singular( 'course' )) {
      exit("No naughty business please");
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
		
		setcookie('intellipaat_visitor', true, time()+60*60*24*30, '/');
		setcookie('intellipaat_visitor_email', $email, time()+60*60*24*30, '/');
		if($mobile)
			setcookie('intellipaat_visitor_phone', $mobile, time()+60*60*24*30, '/');	
		
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
		$verify_status = 1;//$email_object->verify_status;
		
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
									)								
							);
		
		if($lead_id)
			$parameters['name_value_list'][] = array("name" => "id","value" => $lead_id);	
		
		$lead = SugarCRM_API_call("set_entry", $parameters);	
		setcookie('intellipaat_lead_id', $lead->id, time()+60*60*24*365, '/' );
		intellipaatCRM_LeadToMail( $session_id , $lead->id , $course_title);
		
		/*** Create user account on website if email is valid and account not present with same mail address **/
		if ( email_exists($email) == false && $verify_status ) {
			$user_id = wc_create_new_customer($email);
			
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
			}	
			else{ //If user creation fails then send an email to admin 
				wp_mail( "mane.makarand@gmail.com" , "Visitor Sign up form user creation failure", $email."\r\n\r\n".print_r($_REQUEST, TRUE).print_r($user_id, TRUE) ); 
				$result['user'] = "No user account created and mail sent to admin";
			}
		}else{
			$user = get_user_by( 'email', $email );
			if(!user_can( $user, 'edit_posts')){
				$user_id = $user->ID;
				wp_set_current_user($user_id);
				wp_set_auth_cookie($user_id);
				$result['user_id'] = $user_id ;	
			}
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
		
//		if($verify_status)
//			intellipaat_add_visitor_to_abandon();
		
		$result['type'] = "success";
		$result['email_status'] = $verify_status;
		$result['desc'] = $desc;
      	$result = json_encode($result);
      	echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   } 

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