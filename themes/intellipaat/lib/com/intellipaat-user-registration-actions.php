<?php

/*
*	Adds login option to header 
*/
add_filter('wp_nav_menu_items','intellipaat_login', 9, 2);
function intellipaat_login($menu, $args) {
 
	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( 'top-menu' !== $args->theme_location ) )
		return $menu;
 
	$menu_item = $class ='';

	if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
		$menu_item .= '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/">My Courses</a></li>';
		$menu_item .= '<li class="loggedinuser"><a href="'.bp_get_loggedin_user_link().'" class="smallimg vbplogin">'.bp_get_loggedin_user_avatar( 'type=full' ).'<span class="name">'.bp_get_loggedin_user_fullname().'</span></a></li>';
	else :
		$menu_item .= '<li class="hidden-xs"><a href="#login" class="smallimg iplogin">'.__('Login / Sign Up','vibe').'</a></li>';
		if ( function_exists('bp_get_signup_allowed') && bp_get_signup_allowed() ) {
			$pages=get_option('bp-pages');
			$register_id = $pages['register'] ;
			if(is_page($register_id))
				$class = 'current-menu-item page-item-'.$register_id.' current_page_item';
			//$menu_item .= '<li class="menu-item page_item hidden-xs '.$class.'"><a href="'. site_url( BP_REGISTER_SLUG . '/' ) .'" class="vbpregister" title="'.__('Create an account','vibe').'">'.__('Sign Up','vibe').'</a> </li>';
		}
	endif;
	
	return $menu . $menu_item;
 
}

add_filter('wp_nav_menu_items','intellipaat_mobile_login', 9, 2);
function intellipaat_mobile_login($menu, $args) {
 
	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( 'mobile-menu' !== $args->theme_location ) )
		return $menu;
 
	$menu_item = $class ='';

	if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
		$menu_item .= '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/">My Courses</a></li>';
		$menu_item .= '<li class="loggedinuser"><a href="'.bp_get_loggedin_user_link().'" class="smallimg">'.bp_get_loggedin_user_avatar( 'type=full' ).'<span class="name">'.bp_get_loggedin_user_fullname().'</span></a></li>';
	else :
		$menu_item .= '<li class="menu-item page_item"><a href="'.wp_login_url().'" class="smallimg">'.__('Login','vibe').'</a></li>';
		if ( function_exists('bp_get_signup_allowed') && bp_get_signup_allowed() ) {
			$pages=get_option('bp-pages');
			$register_id = $pages['register'] ;
			if(is_page($register_id))
				$class = 'current-menu-item page-item-'.$register_id.' current_page_item';
			$menu_item .= '<li class="menu-item page_item '.$class.'"><a href="'. site_url( BP_REGISTER_SLUG . '/' ) .'" class="vbpregister" title="'.__('Create an account','vibe').'">'.__('Sign Up','vibe').'</a> </li>';
		}
	endif;
	
	return $menu_item.$menu ;
 
}

if ( !class_exists('vibe_bp_login') ) {
	class vibe_bp_login extends WP_Widget {
	
		function vibe_bp_login() {
			$widget_ops = array( 'classname' => 'vibe-bp-login', 'description' => __( 'Vibe BuddyPress Login', 'vibe' ) );
			$this->WP_Widget( 'vibe_bp_login', __( 'Vibe BuddyPress Login Widget','vibe' ), $widget_ops);
		}
		
		function widget( $args, $instance ) {
			extract( $args );
			
			echo $before_widget;
			
			if ( is_user_logged_in() ) :
				do_action( 'bp_before_sidebar_me' ); ?>
				<div id="sidebar-me">
					<div id="bpavatar">
						<?php bp_loggedin_user_avatar( 'type=full' ); ?>
					</div>
					<ul>
						<li id="username"><a href="<?php bp_loggedin_user_link(); ?>"><?php bp_loggedin_user_fullname(); ?></a></li>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/" title="<?php _e('View profile','vibe'); ?>"><?php _e('View profile','vibe'); ?></a></li>
						<li id="vbplogout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" id="destroy-sessions" rel="nofollow" class="logout" title="<?php _e( 'Log Out','vibe' ); ?>"><i class="icon-close-off-2"></i> <?php _e('LOGOUT','vibe'); ?></a></li>
						<li id="admin_panel_icon"><?php if (current_user_can("edit_posts"))
					       echo '<a href="'.vibe_site_url() .'wp-admin/" title="'.__('Access admin panel','vibe').'"><i class="icon-settings-1"></i></a>'; ?>
					  </li>
					</ul>	
					<ul>
            <?php
            $loggedin_menu = array(
              'courses'=>array(
                          'icon' => 'icon-book-open-1',
                          'label' => __('Courses','vibe'),
                          'link' => bp_loggedin_user_domain().BP_COURSE_SLUG
                          ),
              'stats'=>array(
                          'icon' => 'icon-analytics-chart-graph',
                          'label' => __('Stats','vibe'),
                          'link' => bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_STATS_SLUG
                          )
              );
            if ( bp_is_active( 'messages' ) ){
              $loggedin_menu['messages']=array(
                          'icon' => 'icon-letter-mail-1',
                          'label' => __('Inbox','vibe').(messages_get_unread_count()?' <span>' . messages_get_unread_count() . '</span>':''),
                          'link' => bp_loggedin_user_domain().BP_MESSAGES_SLUG
                          );
              $n=vbp_current_user_notification_count();
              $loggedin_menu['notifications']=array(
                          'icon' => 'icon-exclamation',
                          'label' => __('Notifications','vibe').(($n)?' <span>'.$n.'</span>':''),
                          'link' => bp_loggedin_user_domain().BP_NOTIFICATIONS_SLUG
                          );
            }
            if ( bp_is_active( 'groups' ) ){
              $loggedin_menu['groups']=array(
                          'icon' => 'icon-myspace-alt',
                          'label' => __('Groups','vibe'),
                          'link' => bp_loggedin_user_domain().BP_GROUPS_SLUG 
                          );
            }
            
            $loggedin_menu['settings']=array(
                          'icon' => 'icon-settings',
                          'label' => __('Settings','vibe'),
                          'link' => bp_loggedin_user_domain().BP_SETTINGS_SLUG
                          );
            $loggedin_menu = apply_filters('wplms_logged_in_top_menu',$loggedin_menu);
            foreach($loggedin_menu as $item){
              echo '<li><a href="'.$item['link'].'"><i class="'.$item['icon'].'"></i>'.$item['label'].'</a></li>';
            }
            ?>
					</ul>
				
				<?php
				do_action( 'bp_sidebar_me' ); ?>
				</div>
				<?php do_action( 'bp_after_sidebar_me' );
			
			/***** If the user is not logged in, show the log form and account creation link *****/
			
			else :
				if(!isset($user_login))$user_login='';
				do_action( 'bp_before_sidebar_login_form' ); ?>
                
                <?php do_action( 'wordpress_social_login' ); ?> 
                
                <p class="center">OR</p>
				
				
				<form name="login-form" id="vbp-login-form" class="standard-form" action="<?php echo apply_filters('wplms_login_widget_action',vibe_site_url( 'wp-login.php', 'login-post' )); ?>" method="post">
					<label><?php _e( 'Username', 'vibe' ); ?><br />
					<input type="text" name="log" id="side-user-login" class="input" tabindex="1" value="<?php echo esc_attr( stripslashes( $user_login ) ); ?>" /></label>
					
					<label><?php _e( 'Password', 'vibe' ); ?> <br />
					<input type="password" tabindex="2" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>
					
					<p class="clearfix">
                    	<label class="half fl rememberme"><input name="rememberme" tabindex="3" type="checkbox" id="sidebar-rememberme" value="forever" /><?php _e( 'Remember Me', 'vibe' ); ?></label>
                    	<label class="half fr forgotpass"><a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" class="text-right" tabindex="5" title="<?php _e('Forgot Password','vibe'); ?>"><?php _e('Forgot Password','vibe'); ?></a></label>
                    </p>
					
					<?php do_action( 'bp_sidebar_login_form' ); ?>
					<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In','vibe' ); ?>" tabindex="100" />
					<input type="hidden" name="testcookie" value="1" />
					<?php if ( bp_get_signup_allowed() ) :
						printf( __( '<a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'" tabindex="5" >'.__( 'Sign Up','vibe' ).'</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
					endif; ?>
          <?php do_action( 'login_form' ); //BruteProtect FIX ?>
				</form>
				
				
				<?php do_action( 'bp_after_sidebar_login_form' );
			endif;
			
			echo $after_widget;
		}
		
		/* Updates the widget */
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			return $instance;
		}
		
		/* Creates the widget options form */
		
		function form( $instance ) {
			
		}
	
	} 
} 


//add_action('user_register', 'create_zoho_account');


/**
* 	WSL action on thank you page to logout user to get social media icons
*/
function intellipaat_force_form_login()
{
	//var_dump($_SESSION);
	if(!$_SESSION['checkout_user_ID'] || !is_wc_endpoint_url('order-received') )
		return;
		
	global $current_user;
	$current_user = null;
	wp_set_current_user( 0 );
	wp_destroy_current_session();
	wp_clear_auth_cookie();	
	unset($current_user);
}

//add_action( 'wordpress_social_login', 'intellipaat_force_form_login' , 1  );

/**
*	checks new user and register session veriable
*
*	apply_filters( 'wsl_hook_process_login_delegate_wp_insert_user', $userdata, $provider, $hybridauth_user_profile )
*/
function intellipaat_map_user_id($userdata, $provider, $hybridauth_user_profile){
	if(!$_SESSION['checkout_user_ID'])
		return;
		
	$user_id = $_SESSION['checkout_user_ID'];
	unset($_SESSION['order_billing_email'] , $_SESSION['order_id'] ,$_SESSION['checkout_user_created'], $_SESSION['checkout_user_ID']);
	
	$_SESSION['intellipaat_map_user'] = TRUE;
	return $user_id;
}
add_filter( 'wsl_hook_process_login_delegate_wp_insert_user', 'intellipaat_map_user_id', 1, 3 );

//add_action('wsl_hook_process_login_before_wp_insert_user')


/**
*	checks new user and register session veriable
*/

add_action( "wsl_process_login_update_wsl_user_data_start", 'intellipaat_process_login_update_wsl_user_data_start' , 10, 5);

function intellipaat_process_login_update_wsl_user_data_start($is_new_user , $user_id, $provider, $adapter, $hybridauth_user_profile){
	if($is_new_user && !$_SESSION['intellipaat_map_user'])
		$_SESSION['intellipaat_is_new_user'] = TRUE;
}


/**
* 	Save users details.
*/

add_action( "wsl_process_login_authenticate_wp_user_start", 'intellipaat_process_login_authenticate_wp_user_start', 10 , 5); 

function intellipaat_process_login_authenticate_wp_user_start($user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile ){
	
	if($_REQUEST['intellipaat_profile_completion']){
		
		$firstName = $lastName ='';
		
		if(!empty($hybridauth_user_profile->firstName) || !empty($hybridauth_user_profile->lastName)){
			if(!empty($hybridauth_user_profile->firstName))
				$firstName = $hybridauth_user_profile->firstName;
				
			if(!empty($hybridauth_user_profile->lastName))
				 $lastName = $hybridauth_user_profile->lastName;
		}else{
			$pos = strpos($hybridauth_user_profile->displayName, ' ');
			$firstName = substr($hybridauth_user_profile->displayName,0,$pos) ; 
			$lastName = substr($hybridauth_user_profile->displayName,$pos, strlen($hybridauth_user_profile->displayName)) ; 
		}
				
		update_user_meta( $user_id, 'billing_first_name'   , $firstName );
		update_user_meta( $user_id, 'billing_last_name' , $lastName );
				
		update_user_meta( $user_id, 'billing_email'   , $hybridauth_user_profile->email);
		//update_user_meta( $user_id, 'billing_city'   , $_REQUEST['billing_city'] );
		update_user_meta( $user_id, 'billing_country' , $_REQUEST['billing_country'] );
		update_user_meta( $user_id, 'billing_phone' , $_REQUEST['billing_phone'] );
		
		unset($_SESSION['intellipaat_is_new_user']);
		
	}
	unset($_SESSION['order_billing_email'] , $_SESSION['order_id'] ,$_SESSION['checkout_user_created'], $_SESSION['checkout_user_ID'], $_SESSION['intellipaat_map_user']);
}



/**
* 	Asks for update details if new user.
*/

add_action( 'wsl_hook_process_login_before_wp_set_auth_cookie', 'intellipaat_hook_process_login_before_wp_set_auth_cookie', 10, 3);

function intellipaat_hook_process_login_before_wp_set_auth_cookie($user_id, $provider, $hybridauth_profile ){

	if(!$_SESSION['intellipaat_is_new_user'])
		return;
		
	/*$user = get_userdata($user_id);
	$billing_city 		= 	get_user_meta( $user_id, 'billing_city' 	, TRUE ); 
	$billing_city		=	$billing_city ? $billing_city : $hybridauth_profile->city;
	$billing_country	= 	get_user_meta( $user_id, 'billing_country'	, TRUE ); 
	$billing_country	=	$billing_country ? $billing_country : $hybridauth_profile->country;
	$billing_phone 		= 	get_user_meta( $user_id, 'billing_phone' , TRUE ); 
	$billing_phone		=	$billing_phone ? $billing_phone : $hybridauth_profile->phone;*/
		
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
	
	wp_enqueue_script( 'chosen' );

	//lets make the field required so that i can show you how to validate it later;
	//$firstname = empty( $_POST['firstname'] ) ? '' : $_POST['firstname'];
//	$lastname  = empty( $_POST['lastname'] ) ? '' : $_POST['lastname'];
//	$phone = empty( $_POST['phone'] ) ? '' : $_POST['phone'];
//	$billing_address_1 = empty( $_POST['billing_address_1'] ) ? '' : $_POST['billing_address_1'];
//	$billing_postcode = empty( $_POST['billing_postcode'] ) ? '' : $_POST['billing_postcode'];
	//$billing_city  = empty( $_POST['billing_city'] ) ? '' : $_POST['billing_city'];
	/*$billing_country = $hybridauth_profile->country;
	if ( strstr( $billing_country, ':' ) ) {
		$billing_country = explode( ':', $billing_country );
		$country         = current( $billing_country );
		$state           = end( $billing_country );
	} else {
		$country = $billing_country;
		$state   = '*';
	}*/
	
?>
	<!DOCTYPE html>
		<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Complete your profile - <?php echo get_bloginfo('name'); ?></title>
		<head>
        <?php wp_head();?>
			<style> 
				body,#wpbody,.form-table .pre,.ui-autocomplete li a{color:#333}body{font-family:sans-serif;font-size:12px;line-height:1.4em;}html,body{height:100%;margin:0;padding:0} div.updated,.login .message{background-color:#ffffe0;border-color:#e6db55}.message{margin:0 0 16px 8px;padding:12px;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px}.info{font-family:sans-serif;font-size:12px;line-height:1.4em}.login form{background:#fff;border:1px solid #e5e5e5;box-shadow:0 0 5px rgba(200, 200, 200, 0.7);font-weight:400;padding:25px;border-radius:3px}.login label{color:#777;font-size:14px;cursor:pointer;vertical-align:middle}input[type="text"]{background:0 repeat scroll 0 0 #fbfbfb;border:1px solid #e5e5e5;box-shadow:1px 1px 2px rgba(200,200,200,.2) inset;color:#555;font-size:14px;font-weight:200;line-height:1.5;margin-right:6px;margin-top:2px;outline:0 none;padding:5px 10px;width:100%}.button-primary{display:inline-block;text-decoration:none;font-size:12px;line-height:23px;height:24px;margin:0;padding:0 10px 1px;cursor:pointer;border-width:1px;border-style:solid;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background-color:#21759b;background-image:-webkit-gradient(linear,left top,left bottom,from(#2a95c5),to(#21759b));background-image:-webkit-linear-gradient(top,#2a95c5,#21759b);background-image:-moz-linear-gradient(top,#2a95c5,#21759b);background-image:-ms-linear-gradient(top,#2a95c5,#21759b);background-image:-o-linear-gradient(top,#2a95c5,#21759b);background-image:linear-gradient(to bottom,#2a95c5,#21759b);border-color:#21759b;border-bottom-color:#1e6a8d;-webkit-box-shadow:inset 0 1px 0 rgba(120,200,230,.5);box-shadow:inset 0 1px 0 rgba(120,200,230,.5);color:#fff;text-decoration:none;text-shadow:0 1px 0 rgba(0,0,0,.1);float:right;height:36px;}.error{margin:0 0 16px 8px;padding:12px;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px;background-color: #FFEBE8;border:1px solid #CC0000;}#icon_wrapper{ display:none;}
			</style>
			<script>
				function init() {
					if( document.getElementById('city') ) document.getElementById('city').focus()
					if( document.getElementById('country') ) document.getElementById('country').focus()
				}
			</script>
		</head>
		<body class="login" onLoad="init();"> 
        	<div id="headertop" class="global text-center">
            	<h1 id="logo"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></h1>
            </div>
            <section class="stripe">
                <div class="container">
                    <div class="row">
                        <div id="login" class="col-md-6 col-md-offset-3">
                            
                                    <h2 style="padding:0 10px;" class="text-center"> Hello <?php echo $hybridauth_profile->firstName.' '.$hybridauth_profile->lastName ?>,</h2>
                
                            <?php /*?><pre><?php var_dump($hybridauth_profile);?></pre><?php */?>
                            <?php
                                if( ! isset( $_REQUEST["intellipaat_profile_completion"] ) ){ 
                                    ?><p class="message text-center"><?php _wsl_e( "Almost there, we just need to finish few more details.", 'wordpress-social-login' ); ?></p><?php
                                }
                                elseif( $shall_pass_errors ){ 
                                    foreach( $shall_pass_errors as $k => $v ){
                                        ?><p class="error"><?php echo $k; ?></p><?php
                                    }
                                } 
                            ?>
                            <form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="loginform" name="loginform"> 
                            
                                    <?php /*?><p>
                                        <label for="billing_city"><?php _wsl_e( "City", 'wordpress-social-login' ); ?><span class="required">*</span></label>
                                        <input type="text" name="billing_city" id="billing_city" class="input" value="<?php echo $hybridauth_profile->city ?>" size="25" required />
                                    </p><?php */?>	
                
                                    <p>
                                        <label for="reg_billing_country"><?php _e( 'Country', 'woocommerce' ) ?><span class="required">*</span></label>
                                        <select id="reg_billing_country" name="billing_country" data-placeholder="<?php _e( 'Choose a country&hellip;', 'woocommerce' ); ?>" title="Select your Country" class="chosen_select" required >
                                            <option value="" disabled="disabled" selected="selected">Choose your country ...</option>
												<?php 
                                                    $countries = WC()->countries->countries;
                                                    foreach($countries as $country_code => $country_name){
                                                           echo '<option value="'.$country_code.'">'.$country_name.'</option>';
                                                    }
                                                ?>
                                        </select>
                                    </p>	
                
                                    <p>
                                        <label for="billing_phone"><?php _wsl_e( "Mobile number", 'wordpress-social-login' ); ?><span class="required">*</span></label>
                                        <input type="text" name="billing_phone" id="billing_phone" class="billing_phone input" value="<?php echo $hybridauth_profile->phone ?>" size="25" required />
                                    </p>
                                    <p class="text-center">
                                            <input type="submit" value="<?php _wsl_e( "Continue", 'wordpress-social-login' ); ?>" class="button button-primary button-large" id="wp-submit" name="wp-submit"> 
									</p>
									<p style="padding-top: 15px;">
                                            <span class="info">
                                                <img src="<?php echo $assets_base_url . strtolower( $provider ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" />
                                                <?php _wsl_e("You are now connected via", 'wordpress-social-login' ); ?> <b><?php echo ucfirst($provider) ?></b>.
                                            </span>
                                     </p>
                
                                <input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $_REQUEST['redirect_to'] ?>"> 
                                <input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>"> 
                                <input type="hidden" id="action" name="action" value="wordpress_social_profile_completion">
                                <input type="hidden" id="intellipaat_profile_completion" name="intellipaat_profile_completion" value="1">
                            </form>
                        </div> 
                        
                	</div>
           		</div>
           </section>
            <?php wp_footer();?>
            
			<script>
             jQuery(document).ready(function(){
                 
                 jQuery('select.chosen_select').chosen({
                    disable_search_threshold: 8, width: "100%"
                });	
				<?php /*?>/*jQuery('.billing_phone').keyup(function () { 
					this.value = this.value.replace(/[^0-9\.]/g,'');
				});*/
				/******* included globally
				jQuery('#loginform').on('focus', '.billing_phone', function(){
					jQuery(".billing_phone").bind('keyup change', function (e) {
						this.value = this.value.replace(/[^0-9\+]/g,'');					
					});														  
				});*/ //auto caps	<?php */?>
                 
            });
            </script>
		</body>
	</html> 
	<?php  
			die();
}

?>