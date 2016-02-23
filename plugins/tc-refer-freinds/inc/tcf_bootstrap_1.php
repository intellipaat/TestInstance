<?PHP

/*-----------------------------------------------------------------------------------*/
/*	Install Database
/*-----------------------------------------------------------------------------------*/

function tcraf_db_install(){
	
	// define needed globals
	global $wpdb;
	$tcraf_db = $wpdb->prefix . "tc_refer_friends";

	// create table
	if( $wpdb->get_var("SHOW TABLES LIKE '$tcraf_db'") != $tcraf_db ){
		
		$sql = "CREATE TABLE ".$tcraf_db." (
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			ip VARCHAR(250) NOT NULL,
			aff_id VARCHAR(250) NOT NULL, 
			order_id VARCHAR(250) NOT NULL, 
			coupon_id VARCHAR(250) NOT NULL, 
			status VARCHAR(250) NOT NULL,
			added DATETIME NOT NULL
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
				
	} // end table creation
	
}

/*-----------------------------------------------------------------------------------*/
/*	Bootstrapn' Time!
/*-----------------------------------------------------------------------------------*/

function tcraf_init(){
	
	// Load Lang
	load_plugin_textdomain( 'tcraf_locale', false, TCRAF_RELPATH . '/languages/' );
		
	// Make sure we are not in the admin section
	if( !is_admin() && get_option('tc-raf-enabled') == 'true' ){
		
		// Include Share JS If Enabled
		if( get_option('tc-raf-social-enabled') == 'true' ){

			// Register JS
			wp_register_script('tcraf', TCRAF_LOCATION.'/js/tcraf.js', false, TCRAF_VERSION, true);
			wp_enqueue_script('tcraf');
		
		} // end social
				
		// Flush, register, enque CSS
		wp_deregister_style('tcraf_css');
		wp_register_style('tcraf_css', TCRAF_LOCATION.'/css/tcraf.css');
		wp_enqueue_style('tcraf_css');		
				
	} // end non admin
	
	// Admin Resources
	if(is_admin()){
		// Include Media Uploader
		wp_enqueue_script('media-upload');
	}
	
	// Make sure our random key is generated
	if( get_option('edd-tcmd-discount-string') == '' ){
		update_option( 'edd-tcmd-discount-string', md5( rand(1000, 999999999).time() ) );
	}
					
} // End init

/*-----------------------------------------------------------------------------------*/
/*	Current Page Function
/*-----------------------------------------------------------------------------------*/

function tcraf_current_page($type = 1){

	if($type == 1){
		
		$pageURL = 'http';
		
		if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
			$pageURL .= "s";
		}
				
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80"){
			
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		
		} else {
			
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		}
		
		return $pageURL;
		
	} else if($type == 2){
		
		// Get post id and user's IP
		$postID = get_the_ID();
		$postURL = get_permalink($postID);
		return $postURL;
		
	} // end chain

}

/*-----------------------------------------------------------------------------------*/
/*	Shortcode Handle For RAF Page
/*-----------------------------------------------------------------------------------*/

function tcraf_aff_page_shortcode($atts, $content){
	
	// globals
	global $tcraf_options;
	
	// Check If Logged In
	if( is_user_logged_in() ){

		// Check if plugin on
		if( $tcraf_options['enabled'] == 'true' ){
			
			// Get Current User ID
			$current_user = wp_get_current_user();
			
			// Stats Class
			$tcraf_stats = new TCRAFSTATS;
			$stats = $tcraf_stats->aff_stats($current_user->ID);
			$coupons = $tcraf_stats->aff_coupons($current_user->ID);
			
			// Setup Social Sharing
			$tcraf_social_share = '';
			if( $tcraf_options['social-enabled'] == 'true' ){
				
				$social_url = $tcraf_options['social-url'].'?raf='.$current_user->ID;
				
				$tcraf_social_share = '
				
				<div class="tcraf-social-container" data-share-url="'.urlencode( $social_url ).'" data-tweet-text="'.urlencode( $tcraf_options['social-tweet-text'] ).'" data-fb-title="'.urlencode( $tcraf_options['social-fb-title'] ).'" data-fb-text="'.urlencode( $tcraf_options['social-fb-text'] ).'" data-fb-image="'.urlencode( $tcraf_options['social-fb-image'] ).'">
				
					<a href="#share" class="tcraf-social-button facebook" title="'.__('Click to Share', 'tcraf_locale').'">Share</a>
					
					<a href="#tweet" class="tcraf-social-button twitter" title="'.__('Click to Tweet', 'tcraf_locale').'">Tweet</a>
					
					<a href="#share" class="tcraf-social-button google last" title="'.__('Click to Share', 'tcraf_locale').'">Google Share</a>
					
					<br class="tcraf-clear" />
					
					<p class="tcraf-social-desc">'.__('Share your URL on your favorite social services and start referring your friends right away!', 'tcraf_lcoale').'</p>
								
				</div>
				
				';
				
			} // end social building
												
			$return = '
			
			<p>'.__('This page gives you a run down of how to refer friends to the shop and gives you a breakdown of how many people you have referred.', 'tcraf_locale').'</p>
			
			<h3>'.__('Refer A Friend URL', 'tcraf_locale').'</h3>
			
			<p>'.__('This is your Refer A Friend URL, you can use this URL to refer people generating great discounts for you and the people you refer.', 'tcraf_locale').'</p>
			
			<p><code>'.site_url("?raf=".$current_user->ID).'</code></p>
			
			<p>'.__('You can also apply your Refer A Friend ID to the end of shop URLs to refer people right to product pages or other pages on the site.', 'tcraf_locale').'</p>
			
			<p><code>[site-url-here] ?raf='.$current_user->ID.'</code></p>
			
			'.$tcraf_social_share.'
						
			<h3>'.__('Your Referral Stats', 'tcraf_locale').'</h3>
			
			<div class="tcraf-stats summary table-responsive">
				<table class="wp-list-table widefat table table-condensed table-striped fixed" cellspacing="0">
					<thead>
						<tr>
							<th scope="col" class="visits">'.__('Refferal Visits', 'tcraf_locale').'</th>
							<th scope="col" class="hits">'.__('Refferal Hits', 'tcraf_locale').'</th>
							<th scope="col" class="referrals">'.__('Referrals Made', 'tcraf_locale').'</th>
							<th scope="col" class="min">'.__('Mininum Not Met', 'tcraf_locale').'</th>
							<th scope="col" class="ratio">'.__('Checkout Ratio', 'tcraf_locale').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="visits">'.$stats->visits_total.'</td>
							<td class="hits">'.$stats->hits_total.'</td>
							<td class="referrals">'.$stats->refs_total.'</td>
							<td class="min">'.$stats->min_total.'</td>
							<td class="ratio">'.$stats->ratio.'</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<h3>'.__('Your Referral Coupons', 'tcraf_locale').'</h3>
	
			<p>'.__('Here is a list of your <strong>un-used referral coupons</strong>. These are coupons you have received for referring a friend and have not used yet. You can apply multiple coupons together on cart at your next purchase.', 'tcraf_locale').'</p>
			
			<div class="tcraf-stats summary table-responsive">
				<table class="wp-list-table table table-hover table-condensed table-striped widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th scope="col" class="details">'.__('Coupon Details', 'tcraf_locale').'</th>
							<th scope="col" class="code">'.__('Coupon Code', 'tcraf_locale').'</th>
						</tr>
					</thead>
					<tbody>
					'.$coupons.'
					</tbody>
				</table>
			</div>
			';
			
			// Is Cart Min set?
			if( $tcraf_options['minimum-purchase'] != '0' ){
				
				$return.= '
				
				<h3>'.__('Minimum Purchase Required', 'tcraf_locale').'</h3>
				
				<p>'.__('In order to get your referral coupon you must meet a minimum cart total per sale of:', 'tcraf_locale').' $'.$tcraf_options['minimum-purchase'].'</p>
				
				';
				
			} // end cart min
			
		} else { // RAF is disabled
			
			$return = '<p>Refer A Freind for WooCommerce is currently disabled.</p>';
			
		} // end enabled check
		
	} else { // not logged in
	
		$return = '<p>'.__('You need to be logged in to use this feature.', 'tcraf_locale').' <a href="'.wp_login_url( get_permalink() ).'">'.__('Login Here', 'tcraf_locale').'</a> or <a href="'.site_url('/wp-login.php?action=register&redirect_to='.get_permalink()).'">'.__('Register', 'tcraf_locale').'</a>';
		
	} // end login check
	
	// Return Shortcode Content
	return $return;

}

/*-----------------------------------------------------------------------------------*/
/*	Check to see if RAF ID is set and log hits
/*-----------------------------------------------------------------------------------*/

function tcraf_ref_check(){
	
	// Check if Ref Is Set
	if( isset( $_GET['raf'] ) ){
	
		// Setup DB	
		global $wpdb;
		global $woocommerce;
		$tc_table = $wpdb->prefix."tc_refer_friends";

		// Get Ref ID
		$raf_id = $_GET['raf'];
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		// Safety Check
		if( !is_numeric( $raf_id ) ){
			die('Stop what your doing, its pretty suspect!');
		}
		
		if(is_user_logged_in()){
			$user_ID = get_current_user_id();
			if($raf_id == $user_ID)
				return '';
		}
		
		// If RAF ID Is Valid User	
		if( get_userdata(  $raf_id ) == true){
				
			// Format Time
			$time = date( 'Y-m-d H:i:s' );
						
			// insert		
			$wpdb->insert( $tc_table, array(
				'aff_id'	=> $raf_id,
				'ip'		=> $ip_address,
				'status'	=> 'waiting',
				'added'		=> $time
			));
			
			// Get This Row
			$lastid = $wpdb->insert_id;
			
			// Bake Cookies
			setcookie("tcraf_buyer", "true", time()+86400, '/');
			setcookie("tcraf_record", $lastid, time()+86400, '/');
			
			// Apply Coupin
			$woocommerce->cart->add_discount( get_option('tc-raf-buyer-discount') );	
			
		} // end if aff ID set
		
	} // end if valid user id
	
}

/*-----------------------------------------------------------------------------------*/
/*	Check whether user is refering himself
/*-----------------------------------------------------------------------------------*/

function tcraf_user_check($user_login, $user){
	global $wpdb;
	global $woocommerce;
	$tc_table = $wpdb->prefix."tc_refer_friends";
	
	if( isset( $_COOKIE['tcraf_record'] ) ){ $tcraf_record = $_COOKIE['tcraf_record']; } else return;
	if( isset( $_COOKIE['tcraf_buyer'] ) ){ $tcraf_buyer = $_COOKIE['tcraf_buyer']; } else return;
	
	if( $tcraf_record != '' && $tcraf_buyer == 'true' ){
		$user_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT aff_id FROM $tc_table WHERE id = %d",
				// Values
				$tcraf_record
			) // end prep
			
		);
		if($user->ID == $user_id){
			if( $woocommerce->cart->has_discount( get_option('tc-raf-buyer-discount') )){ 
				$woocommerce->cart->remove_coupon( get_option('tc-raf-buyer-discount'));
			}
			
			setcookie("tcraf_buyer", "", time()-86400, '/');
			setcookie("tcraf_record", "", time()-86400, '/');
		}
	}	
}

/*-----------------------------------------------------------------------------------*/
/*	Cross check for discount
/*-----------------------------------------------------------------------------------*/

function tcraf_woocommerce_add_to_cart( $instance, $number, $number ){
    global $woocommerce;
	
	if( isset( $_COOKIE['tcraf_record'] ) ){ $tcraf_record = $_COOKIE['tcraf_record']; } else return;
	if( isset( $_COOKIE['tcraf_buyer'] ) ){ $tcraf_buyer = $_COOKIE['tcraf_buyer']; } else return;
	if( $tcraf_record != '' && $tcraf_buyer == 'true' ){
		$applied_coupons = $woocommerce->cart->get_applied_coupons();
		if(empty($applied_coupons))
			$woocommerce->cart->add_discount( get_option('tc-raf-buyer-discount') );
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Display Message on Cart Page
/*-----------------------------------------------------------------------------------*/

function tcraf_cart_display(){
	
	global $woocommerce;
	
	// Check if discount is applied
	if( $woocommerce->cart->has_discount( get_option('tc-raf-buyer-discount') )){ ?>
    
    	<div class="tcraf-cart-display"><?PHP echo get_option('tc-raf-cart-message'); ?></div>
        
    <?PHP } // end if
	
}

/*-----------------------------------------------------------------------------------*/
/*	Add Our RAF Tracking To WooCommmerce Order Meta
/*-----------------------------------------------------------------------------------*/

function tcraf_order_meta( $order_id ){

	$tcraf_record = '';
	$tcraf_buyer = '';
	if( isset( $_COOKIE['tcraf_record'] ) ){ $tcraf_record = $_COOKIE['tcraf_record']; }
	if( isset( $_COOKIE['tcraf_buyer'] ) ){ $tcraf_buyer = $_COOKIE['tcraf_buyer']; }
	
	// If Record Set
	if( $tcraf_record != '' && $tcraf_buyer == 'true' ){
		// Add Tracking For RAF
		update_post_meta( $order_id, 'tcraf_record_id', esc_attr($tcraf_record) );
		// Add Current User ID To Total
		update_post_meta( $order_id, 'tcraf_record_id', esc_attr($tcraf_record) );
	}

}

/*-----------------------------------------------------------------------------------*/
/*	Update After Order Complete
/*-----------------------------------------------------------------------------------*/

function tcraf_order_update($order_id){
	
	// Setup DB	
	global $wpdb;
	global $woocommerce;
	global $tcraf_options;
	$tc_table = $wpdb->prefix."tc_refer_friends";
	$current_user = wp_get_current_user();
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$cartmin = 'true';
	
	// Check for TCRAF Meta
	$tcraf_record = get_post_meta($order_id, 'tcraf_record_id', true);
	
	// Check for cart minimum
	if( $tcraf_options['minimum-purchase'] != '0' ){
		
		// Lock by default if enabled
		$cartmin = 'false';
		$order_total = get_post_meta($order_id, '_order_total', true);
		if( $order_total > $tcraf_options['minimum-purchase'] ){
			$cartmin = 'true';
		}
		
	} // end cart min check
	
	// If Cookies Are Set, Continue
	if( $tcraf_record != '' ){
		
		// Create Coupon for Aff
		if( $tcraf_options['referral'] == 'true' ){
			
			// If Cart Min Met
			if( $cartmin == 'true' ){
			
				$coupon_code = 'RAF'.$tcraf_record.'-'.md5( time() ); // Code
				$amount = $tcraf_options['referral-amount']; // Amount
				$discount_type = $tcraf_options['referral-type']; // Type: fixed_cart, percent, fixed_product, percent_product
				
				if($discount_type == 'percent'){
					$order = new WC_Order( $order_id );
					$order_total = $order->get_subtotal();
					$discount_type = 'fixed_cart';
					$amount = ($order_total*$amount)/100;
					$amount = round($amount , 2);
				}
				
				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'		=> 'shop_coupon'
				);
				
				// Get Coupn ID					
				$new_coupon_id = wp_insert_post( $coupon );
									
				// Add meta
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				update_post_meta( $new_coupon_id, 'product_ids', '' );
				update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
				update_post_meta( $new_coupon_id, 'usage_limit', '1' );
				update_post_meta( $new_coupon_id, 'expiry_date', '' );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
				
				// Update DB
				$wpdb->query( $wpdb->prepare("UPDATE $tc_table SET status = 'complete', order_id = %d, coupon_id = %s WHERE id = %d",
					$order_id,
					$new_coupon_id,
					$tcraf_record
				));
				
				// Get Our Affiliate ID
				$user_id = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT aff_id FROM $tc_table WHERE id = %d",
						// Values
						$tcraf_record
					) // end prep
					
				);
							
				// Get User Data And Send Email
				$user_info = get_userdata($user_id);
				$msg = "<p>".$tcraf_options['referral-msg']."</p>";
				$msg .= "\n\n<p>".__('Your Coupon Code for next purchase', 'tcraf_locale').": <strong>".$coupon_code."</strong></p>";
				wp_mail($user_info->user_email, $tcraf_options['referral-title'], $msg);			

			} else { // else cart min false
			
				// Update DB
				$wpdb->query( $wpdb->prepare("UPDATE $tc_table SET status = 'cartmin', order_id = %d WHERE id = %d",
					$order_id,
					$tcraf_record
				));
			
			} // end cart min
			
			// Remove Cookies
			setcookie("tcraf_buyer", "", time()-86400, '/');
			setcookie("tcraf_record", "", time()-86400, '/');

		} // end if referral enabled
															
	} // end if cookies set

}

/*-----------------------------------------------------------------------------------*/
/*	Check To See If "RAF" Page Is Present
/*-----------------------------------------------------------------------------------*/

function tcraf_check_pages(){
	
	// Get All Pages
	$pages = get_pages(array(
		'post_type' => 'page',
		'post_status' => 'publish'
	));
	
    // Assume it's not there
    $found = 'false';
 
    // Check Pages for Shortcode
    foreach($pages as $page){
				
        if( stripos($page->post_content, '[tcraf-refer-page') !== false ){
            $found = 'true'; // Found it!
            break; // No need to continue
        }
		
    } // end for each
    
	// return 
	return $found;

}

/*-----------------------------------------------------------------------------------*/
/*	Start Running Hooks
/*-----------------------------------------------------------------------------------*/

// Installer
register_activation_hook( TCRAF_RELPATH.'/tc-refer-friends.php', 'tcraf_db_install' );
// Start the plugin
add_action( 'init', 'tcraf_init' );
// Add hook to include settings CSS
add_action( 'admin_init', 'tcraf_settings_admin_css' );
// create custom plugin settings menu
add_action( 'admin_menu', 'tcraf_create_menu' );
// Selective Hook If Enabled
if( get_option('tc-raf-enabled') == 'true' ){
	// Add Listener for template redirect
	add_action('wp_login', 'tcraf_user_check', 100, 2);
	add_action( 'woocommerce_add_to_cart', 'tcraf_woocommerce_add_to_cart', 100, 3 );
	// Add Listener for template redirect
	add_action( 'template_redirect', 'tcraf_ref_check' );
	// Add Aff Page Shortcode
	add_shortcode('tcraf-refer-page', 'tcraf_aff_page_shortcode');
	// Add Display To Cart / Checkout
	add_action( 'woocommerce_cart_collaterals', 'tcraf_cart_display' );
	add_action( 'woocommerce_before_checkout_form', 'tcraf_cart_display' );
	// Add Tracking To WooCommerce Order Meta
	add_action('woocommerce_checkout_update_order_meta', 'tcraf_order_meta');
	// Run When Order Complete
	add_action('woocommerce_order_status_completed', 'tcraf_order_update');

}