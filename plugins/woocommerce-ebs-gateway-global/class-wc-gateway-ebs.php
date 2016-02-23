<?php
/*
Plugin Name: WooCommerce EBS Global Gateway
Plugin URI: http://support.ebs.in/
Description: EBS Global Payment Gateway.
Version: 3.0
Author: EBS
Author URI: http://www.ebs.in
*/

add_action('plugins_loaded', 'woocommerce_ebs_global_init', 0);

    function woocommerce_ebs_global_init() {
	load_plugin_textdomain('wc-gateway-ebs-global', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
	
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;
	class WC_Gateway_Ebs_Global extends WC_Payment_Gateway {
		public function __construct() { 
			global $woocommerce;
	        $this->id			= 'ebs_global';
	        $this->icon 		= apply_filters('woocommerce_ebs_icon', plugins_url().'/woocommerce-ebs-gateway/images/logo.png');
	        $this->has_fields 	= false;
	        $this->ebsurl 		= 'https://secure.ebs.in/pg/ma/payment/request/';
	        $this->method_title = __( 'EBS Global', 'woocommerce' );
	        // Load the form fields.
			$this->init_form_fields();
			// Load the settings.
			$this->init_settings();	
	        $this->title = $this->settings['title'];   
            $this -> description = $this->settings['description'];  
			$this->conversion_rate 	= vibe_get_option('dollar_to_inr_conversion_rate');  
			$this->tax				= 14;
		    $this->testmode			= $this->get_option( 'testmode' );
		    // Actions
			add_action( 'woocommerce_api_wc_gateway_ebs_global', array( $this, 'check_ebs_response' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );		
			add_action( 'woocommerce_receipt_ebs_global', array(&$this, 'receipt_page'));
	    }    
	    
    function init_form_fields() {
	    
	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woocommerce' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable EBS Payment Gateway', 'woocommerce' ), 
								'default' => 'yes'
							), 
				'title' => array(
								'title' => __( 'Title', 'woocommerce' ), 
								'type' => 'text', 
								'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ), 
								'default' => __( 'EBS', 'woocommerce' )
							),
				'description' => array(
								'title' => __( 'Description', 'woocommerce' ), 
								'type' => 'textarea', 
								'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ), 
								'default' => __("EBS Payment gateway", 'woocommerce')
							),
				'account_id' => array(
								'title' => __( 'EBS Account ID', 'woocommerce' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your EBS Account ID.', 'woocommerce' ), 
								'default' => ''
							),
				'secret_key' => array(
								'title' => __( 'EBS Secret Key', 'woocommerce' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your EBS Secret Key.', 'woocommerce' ), 
								'default' => ''
							),			
				'mode' => array(
								'title' => __( 'Mode', 'woocommerce' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable Test Mode', 'woocommerce' ), 
								'default' => 'no',
								'description' => sprintf( __( 'This controls for selecting the payment mode as TEST or LIVE.', 'woocommerce' ), 'https://support.ebs.in/' ),
							),
                'page_id' => array(
								'title' => __( 'Page ID', 'woocommerce' ), 
								'type' => 'text', 
								'default' => '',
								'description' => sprintf( __( 'This controls for page id that is created by the merchant in EBS merchant account.', 'woocommerce' ), 'https://support.ebs.in/' ),
							),
                'hash_type' => array(
				                'title'       => __( 'Hash Type', 'woocommerce' ),
				                'type'        => 'select',
				                'description' => __( 'Choose whether you wish to hash type immediately.', 'woocommerce' ),
				                'default'     => 'md5',
				                'desc_tip'    => true,
				                'options'     => array(
					                'md5'          => __( 'MD5', 'woocommerce' ),
                                    'SHA512'       => __( 'SHA512', 'woocommerce' ),
                                    'SHA1'         => __( 'SHA1', 'woocommerce' ),
				)
			),

				);
	    
	} // End init_form_fields()    
	    
	function get_ebs_args( $order ) {
			global $woocommerce;		
			$order_id = $order->id;		
			$description = $order->customer_note;
			if(empty($description)){
				$description = "Order is ".$order_id;
			}
			$account_id = $this->settings['account_id'];
			$secret_key = $this->settings['secret_key'];
			$ebs_mode = $this->settings['mode'];
			$tax	= ($order->order_total*$this->tax)/100;
			$amount	= $order->order_total + $tax;
			$amount = round($this->conversion_rate  * $amount, 2);
			$mode = ($ebs_mode == "yes") ? "TEST" : "LIVE";
			$return_url = trailingslashit(home_url()).'?wc-api=WC_Gateway_Ebs_Global&order_id='.$order_id;

			$Code = array("AF" =>  "AFG", "AL" => "ALB", "DZ" => "DZA", "AS" => "ASM", "AD" => "AND", "AO" => "AGO", "AI" => "AIA", "AQ" => "ATA", "AG" => "ATG", "AR" => "ARG", "AM" => "ARM","AW" => "ABW", "AU" => "AUS", "AT" => "AUT", "AZ" => "AZE", "BS" => "BHS", "BH" => "BHR","BD" => "BGD", "BB" => "BRB", "BY" => "BLR", "BE" => "BEL", "BZ" => "BLZ", "BJ" => "BEN", "BM" => "BMU", "BT" => "BTN", "BO" => "BOL", "BA" => "BIH", "BW" => "BWA", "BV" => "BVT", "BR" => "BRA", "IO" => "IOT", "VG" => "VGB", "BN" => "BRN", "BG" => "BGR", "BF" => "BFA", "BI" => "BDI","KH" => "KHM", "CM" => "CMR", "CA" => "CAN", "CV" => "CPV", "KY" => "CYM", "CF" => "CAF", "TD" => "TCD", "CL" => "CHL", "CN" => "CHN", "CX" => "CXR", "CC" => "CCK", "CO" => "COL", "KM" => "COM", "CG" => "COG", "CK" => "COK", "CR" => "CRI", "CI" => "CIV", "HR" => "HRV", "CU" => "CUB", "CY" => "CYP", "CZ" => "CZE", "DK" => "DNK", "DM" => "DMA","DO" => "DOM", "TL" => "TLS", "EC" => "ECU", "EG" => "EGY", "SV" => "SLV", "GQ" => "GNQ","ER" => "ERI", "EE" => "EST", "ET" => "ETH", "FK" => "FLK","FO" => "FRO","FJ" => "FJI","FI" => "FIN","FR => FRA","FX" => "FXX","GF" => "GUF","PF" => "PYF","TF" => "ATF","GA" => "GAB","GE" => "GEO","GM" => "GMB","PS" => "PSE","DE" => "DEU","GH" => "GHA","GI" => "GIB","GR" => "GRC","GL" => "GRL","GD" => "GRD","GP" => "GLP","GU" => "GUM","GT" => "GTM","GN" => "GIN","GW" => "GNB","GY" => "GUY","HT" => "HTI","HM" => "HMD","HN" => "HND","HK" => "HKG","HU" => "HUN","IS" => "ISL","IN" => "IND","ID" => "IDN","IQ" => "IRQ","IE" => "IRL","IR" => "IRN","IL" => "ISR","IT" => "ITA","JM" => "JAM","JP" => "JPN","JO" => "JOR","KZ" => "KAZ","KE" => "KEN","KI" => "KIR","KP" => "PRK","KR" => "KOR","KW" => "KWT","KG" => "KGZ","LA" => "LAO","LV" => "LVA","LB" => "LBN","LS" => "LSO","LR" => "LBR","LY" => "LBY","LI" => "LIE","LT"=>"LTU","LU" => "LUX","MO" => "MAC","MK" => "MKD","MG" => "MDG","MW" => "MWI","MY" => "MYS","MV" => "MDV","ML" => "MLI","MT" => "MLT","MH" => "MHL","MQ" => "MTQ","MR" => "MRT","MU" => "MUS","YT" => "MYT","MX" => "MEX","FM" => "FSM","MD" => "MDA","MC" => "MCO","MN" => "MNG","MS" => "MSR","MA" => "MAR","MZ" => "MOZ","MM" => "MMR","NA" => "NAM","NR" => "NRU","NP" => "NPL","NL" => "NLD","NC" => "NCL","NZ" => "NZL","NI" => "NIC","NE" => "NER","NG" => "NGA","NU" => "NIU","NF" => "NFK","MP" => "MNP","NO" => "NOR","OM" => "OMN","PK" => "PAK","PW" => "PLW","PA" => "PAN","PG" => "PNG","PY" => "PRY","PE" => "PER","PH" => "PHL","PN" => "PCN","PL" => "POL","PT" => "PRT","PR" => "PRI","QA" => "QAT","RE" => "REU","RO" => "ROU","RU" => "RUS","RW" => "RWA","LC" => "LCA","WS" => "WSM","SM" => "SMR","ST" => "STP","SA" => "SAU","SN" => "SEN","SC" => "SYC","SL" => "SLE","SG" => "SGP","SK" => "SVK","SI" => "SVN","SB" => "SLB","SO" => "SOM","ZA" => "ZAF","ES" => "ESP","LK" => "LKA","SH" => "SHN","KN" => "KNA","PM" => "SPM","VC" => "VCT","SD" => "SDN","SR"=> "SUR","SJ" => "SJM","SZ" => "SWZ","SE" => "SWE","CH" => "CHE","SY" => "SYR","TW" => "TWN","TJ" => "TJK","TZ" => "TZA","TH" => "THA","TG" => "TGO","TK" => "TKL","TO" => "TON","TT" => "TTO","TN" => "TUN","TR" => "TUR","TM" => "TKM","TC" => "TCA","TV" => "TUV","UG" => "UGA","UA" => "UKR","AE" => "ARE","GB" => "GBR","US" => "USA","VI" => "VIR","UY" => "URY","UZ" => "UZB","VU" => "VUT","VA" => "VAT","VE" => "VEN","VN" => "VNM","WF" => "WLF","EH" => "ESH","YE" => "YEM","CS" => "SCG","ZR" => "ZAR","ZM" => "ZMB","ZW" => "ZWE","AP" => "   ","RS" => "SRB","AX" => "ALA" , "EU" => "" ,"ME" => "MNE","GG" => "GGY","JE" => "JEY","IM" => "IMN","CW" => "CUW","SX" => "SXM"); 
	$billing_country = $Code[$order->billing_country];
	$shipping_country = $Code[$order->shipping_country];
	
	    
			$ebs_args =  array(
                    'channel' => 0,
					'account_id' => $account_id,
                    'page_id' => $this->settings['page_id'],
					'mode' => $mode,
                    'currency' => 'INR',
					'reference_no' => $order_id,
					'amount' => $amount,
					'description' => $description,
					'name' => $order->billing_first_name." ".$order->billing_last_name,
					'address' => $order->billing_address_1." ".$order->billing_address_2,
					'city' => $order->billing_city ? $order->billing_city  : 'IND',
					'state' => $order->billing_state,
					'postal_code' => $order->billing_postcode  ? $order->billing_postcode : '878585',
					'country' => $billing_country,
					'email' => $order->billing_email,
					'phone' => $order->billing_phone,
					'ship_name' => $order->shipping_first_name.' '.$order->shipping_last_name,
					'ship_address' => $order->shipping_address_1.' '.$order->shipping_address_2,
					'ship_city' => $order->shipping_city,
					'ship_state' => $order->shipping_state,
					'ship_country' => $shipping_country,
					'ship_postal_code' => $order->shipping_postcode,
					'return_url' => $return_url,
					'domain_id' =>  '',
					'group_id' =>  '',
					'display_currency' =>  '',
					'display_currency_rate' =>  '',
					'payment_mode' =>  '',
					'card_brand' =>  '',
					'payment_option' =>  '',
					'bank_code' =>  '',
					'emi' =>  '',
					'language' =>  '',
					'account_number' =>  '',
			);	
        
            $hashData = $this->settings['secret_key'];
            $hashType = $this->settings['hash_type']; 
		ksort($ebs_args);		
		foreach ($ebs_args as $key => $value){
			if (strlen($value) > 0) {
				$hashData .= '|'.$value;
			}
		}

		if (strlen($hashData) > 0) {
			if($hashType == "SHA512")
				$hashValue = strtoupper(hash('SHA512',$hashData));	
			if($hashType == "SHA1")
				$hashValue = strtoupper(sha1($hashData));
            if($hashType == "md5")
			    $hashValue = strtoupper(md5($hashData));	
		}
                
          $var =  array(
                    'channel' => 0,
					'account_id' => $account_id,
                    'page_id' => $this->settings['page_id'],
					'mode' => $mode,
                    'currency' => 'INR',
					'reference_no' => $order_id,
					'amount' => $amount,
					'description' => $description,
					'name' => $order->billing_first_name." ".$order->billing_last_name,
					'address' => $order->billing_address_1." ".$order->billing_address_2,
					'city' => $order->billing_city ? $order->billing_city  : 'IND',
					'state' => $order->billing_state,
					'postal_code' => $order->billing_postcode  ? $order->billing_postcode : '878585',
					'country' => $billing_country,
					'email' => $order->billing_email,
					'phone' => $order->billing_phone,
					'ship_name' => $order->shipping_first_name.' '.$order->shipping_last_name,
					'ship_address' => $order->shipping_address_1.' '.$order->shipping_address_2,
					'ship_city' => $order->shipping_city,
					'ship_state' => $order->shipping_state,
					'ship_country' => $shipping_country,
					'ship_postal_code' => $order->shipping_postcode,
					'return_url' => $return_url,
                    'secure_hash' => $hashValue,
					'group_id' =>  '',
					'display_currency' =>  '',
					'display_currency_rate' =>  '',
					'payment_mode' =>  '',
					'card_brand' =>  '',
					'payment_option' =>  '',
					'bank_code' =>  '',
					'emi' =>  '',
					'language' =>  '',
					'account_number' =>  '',
			);	

            
           // echo "<pre>"; print_r($var); die;
			$ebs_args = apply_filters( 'woocommerce_ebs_args', $var );
			return $ebs_args;
		}

	        /**** Updated by rajkumar 03Apr14 ****/
		function process_payment( $order_id ) {
			global $woocommerce;		
			$order = new WC_Order( $order_id );
			$ebs_adr = $this->ebsurl;		
			$ebs_args = $this->get_ebs_args( $order );		
			$ebs_args_array = array();
			$output = "<form id=\"ebs_form\" name=\"ebs_form\" method=\"post\" action=\"$ebs_adr\">".implode('', $ebs_args_array) . ' ';
			foreach ($ebs_args as $key => $value) {
				if (strlen($value) > 0) {
					$output .= "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
				}
			}
			$output .= "<p>Please wait.. Redirecting to payment page.</p></form>";		
			echo $output."<script language=\"javascript\" type=\"text/javascript\">document.getElementById('ebs_form').submit();</script>";
			exit();	

		}
		/**** End ****/
	    
        /**** Updated by rajkumar 30 Sep 14 ****/
		function check_ebs_response() {
                global $woocommerce;	
                $response = $_REQUEST;		
                $order_id = $response['MerchantRefNo'];
                $order = new WC_Order( $order_id );
                $secret_key = $this->get_option( 'secret_key' ); 
		        $secureHash = $response['SecureHash'];
		        $params = $secret_key;
		        $hashType = $this->get_option( 'hash_type' ); 				
				$tax	= ($order->order_total*$this->tax)/100;
				$amount	= $order->order_total + $tax;
				$amount = round($this->conversion_rate  * $amount, 2);
	
		        ksort($response);
		        foreach ($response as $key => $value){
			        if (strlen($value) > 0) {
				        $params .= '|'.$value;
			        }
		        }		
		        if (strlen($params) > 0) {
			        if($hashType == "SHA512")
				        $hashValue = strtoupper(hash('SHA512',$params));	
			        if($hashType == "SHA1")
				        $hashValue = strtoupper(sha1($params));	
                    if($hashType == "md5")
			            $hashValue = strtoupper(md5($params));
		        }				
		        $hashValid = ($hashValue == $secureHash) ? true : false; 
				
				$responseMsg = $response['ResponseMessage'];
				if($response['ResponseCode']==0){				
					if($response['Amount'] == $amount){
						$notes 	= $responseMsg.'. Transaction ID: '.$response['TransactionID'];		
						$order -> payment_complete();
						$order -> add_order_note($notes);	
						if($response['IsFlagged'] == 'YES'){
							$order -> add_order_note("The payment has been kept on hold until the manual verification is completed and authorized by EBS");
						}
						$woocommerce -> cart -> empty_cart();	
                        $responseMsg = $response['MerchantRefNo'];
                        /*$woocommerce->add_message("Order ID Is: ".$responseMsg, 'woocommerce');										 
		                $woocommerce->add_message( __('Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon.', 'woocommerce') );*/ 						 
		                wc_add_notice('Order ID Is: '.$responseMsg.'. Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon.','success');
						$redirect = add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, $order->get_checkout_order_received_url()));
		                wp_redirect($redirect);    
		                exit;       
					}
				}
				else{
					$status = 'Failed';
					$order -> update_status('failed');
		            $order->add_order_note($response['ResponseMessage'].'. Transaction ID: '.$response['TransactionID']);
		            #$woocommerce->add_error( __('Transaction Failed. Try again!!!', 'woocommerce') );
		            wc_add_notice('Transaction Failed. Please try again!!!','error');
		            $redirect = add_query_arg('pay_for_order', 'true', add_query_arg('order', $order->order_key, add_query_arg('order_id', $order_id, get_permalink(woocommerce_get_page_id('pay')))));	  
		            wp_redirect($redirect); 	          
		            exit;
				}
			
		}
	}
	
    /**** End ****/

	function add_ebs_global_gateway($methods) {
		$methods[] = 'WC_Gateway_Ebs_Global';
		return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'add_ebs_global_gateway' );
}
