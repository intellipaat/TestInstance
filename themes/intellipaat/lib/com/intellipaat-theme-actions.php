<?php
/*
 *	Enqueue CSS and JS on website
 */
//echo 'tst'.get_current_template( true );
$userCountry = getLocationInfoByIp();
//echo $userCountry['country'];
$userCountryCode = isset($userCountry['country']) ? $userCountry['country'] : '';

function intellipaat_custom_script() {
	
	if( !is_admin()){
		
		wp_enqueue_script('custom_script', 
						  get_stylesheet_directory_uri().'/js/custom.com.js', 
						  array('jquery','jquery-ui-core','bp-course-js','intellipaat_script'), 
						  '1.0', 
						  true);
	}
 
}
add_action('wp_enqueue_scripts', 'intellipaat_custom_script', 30);

function intellipaat_wishlist_script() {
	
	if( !is_admin()){
		wp_register_script( 'jquery-yith-wcwl', get_stylesheet_directory_uri(). '/js/jquery.yith-wcwl.js', array( 'jquery', 'jquery-selectBox' ), '2.0', true );
	}
 
}
add_action('wp_enqueue_scripts', 'intellipaat_wishlist_script', 3);



function intellipaat_register_my_session(){
	if(is_admin())
		return;		

	if( !session_id() )
	{
		session_start();
	}
	/*if(isset($_SESSION['REMOTE_ADDR_CUREE'])&& $_SESSION['REMOTE_ADDR_CUREE']!="")
	{
		if($_SESSION['REMOTE_ADDR_CUREE']=="IN")
		{
			$countryCode_CURR="IN";

		}else if($_SESSION['REMOTE_ADDR_CUREE']=="Other")
		{
			$countryCode_CURR="USA";
		}
	}else{
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$fetch_ip = "SELECT countryCode, countryName FROM ip_geoipaddress WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
		$query22 = $wpdb->get_results($fetch_ip);
		$countryCode_CURR=$query22[0]->countryCode;
		$_SESSION['REMOTE_ADDR_CUREE']=$countryCode_CURR;

	}*/
	if(!isset($_SESSION['REMOTE_ADDR_CUREE']) || empty($_SESSION['REMOTE_ADDR_CUREE'])){ 
		global $wpdb;
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$fetch_ip = "SELECT countryCode, countryName FROM ip_geoipaddress WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
		$query22 = $wpdb->get_results($fetch_ip);
		$countryCode_CURR=$query22[0]->countryCode;
		$_SESSION['REMOTE_ADDR_CUREE']=$countryCode_CURR; 
	}	
	//CURRENT_USER_CURRENCY
	/*if(!isset($_SESSION['REMOTE_ADDR_CUREE']) || empty($_SESSION['REMOTE_ADDR_CUREE'])){
		
		if ( is_user_logged_in() ) {
			$country     = get_user_meta( get_current_user_id(), 'billing_country', true );
		}
		 else{			 
			$country	= WC()->customer->get_country( );
		}
		
		if($country == 'IN'){
			$_SESSION['REMOTE_ADDR_CUREE'] = 'INR';
		}else{
			$_SESSION['REMOTE_ADDR_CUREE'] = 'USD';
		}		
	}	*/
}

add_action('init', 'intellipaat_register_my_session');
function getLocationInfoByIp(){
	$client = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote = @$_SERVER['REMOTE_ADDR'];
	$result = array('country'=>'', 'city'=>'');
	if(filter_var($client, FILTER_VALIDATE_IP)){
		$ip = $client;
	}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
		$ip = $forward;
	}else{
		$ip = $remote;
	}
	$ip_data = @json_decode	(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
	if($ip_data && $ip_data->geoplugin_countryName != null){
		$result['country'] = $ip_data->geoplugin_countryCode;
		$result['city'] = $ip_data->geoplugin_city;
	}
	return $result;
}
?>
