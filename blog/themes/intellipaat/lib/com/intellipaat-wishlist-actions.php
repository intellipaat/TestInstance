<?php


if ( !defined( 'BP_WISHLIST_SLUG' ) )
			define( 'BP_WISHLIST_SLUG', 'wishlist');
			
			

/*
*	Add tab to profile for wishlist
*/

function profile_tab_wishlist() {
	global $bp;
	$user_access= 1;
	$course_link = trailingslashit( bp_loggedin_user_domain() . BP_COURSE_SLUG );
		
	if(function_exists('bp_is_my_profile'))
		$user_access = apply_filters('wplms_user_profile_courses',bp_is_my_profile());
		
	bp_core_new_subnav_item( array(
				'name'            =>  __('My Wishlist', 'vibe' ),
				'slug'            => BP_WISHLIST_SLUG ,
				'parent_url'      => $course_link,
				'parent_slug'     => BP_COURSE_SLUG,
				'screen_function' => 'wishlist_screen',
				'user_has_access' => $user_access,
				'position'        => 10
			) );
}
add_action( 'bp_setup_nav', 'profile_tab_wishlist', 100 );
 
 
function wishlist_screen() {
  //  add_action( 'bp_template_title', 'wishlist_title' );
  //  add_action( 'bp_template_content', 'wishlist_content' );
	bp_core_load_template(apply_filters( 'bp_course_template_my_wishlist', 'members/single/home' ));
}
/*function wishlist_title() {
    echo 'My Wishlist';
}

function wishlist_content() { 
    echo do_shortcode('[yith_wcwl_wishlist per_page=500]');
}*/

function intellipaat_yith_wcwl_wishlist_page_url(){
	return trailingslashit(bp_loggedin_user_domain()  . BP_COURSE_SLUG .'/'. BP_WISHLIST_SLUG);
}
add_filter('yith_wcwl_wishlist_page_url','intellipaat_yith_wcwl_wishlist_page_url');

function intellipaat_course_wishlist_button($id){
	echo do_shortcode("[yith_wcwl_add_to_wishlist icon='fa-heart' product_id='".$id."']"); 
}
function intellipaat_wishlist_button_add_events(){
	if(is_user_logged_in())
		add_action('course_wishlist_button','intellipaat_course_wishlist_button');
}
add_action('take_course_events', 'intellipaat_wishlist_button_add_events');

add_action( 'wp_ajax_remove_course_from_wishlist', 'intelliaat_remove_course_from_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_remove_course_from_wishlist', 'intelliaat_remove_course_from_wishlist_ajax' );
function intelliaat_remove_course_from_wishlist_ajax(){
	$response =array();
	if(!isset($_POST)){
		
			$response = array(
					  	'result'	=> false,					  
					  );		  
	}else{
		
		$YITH_WCWL = YITH_WCWL();
	
		$result = $YITH_WCWL->remove($_POST['remove_from_wishlist']);
		
		if($result)
			$response = array(
							'result'	=> true,
							'message'	=> 'Removed from wishlist'						  
						  );
		else
			$response = array(
					  	'result'	=> false,					  
					  );	
	}
	echo json_encode($response);
	die();
}


add_action( 'wp_ajax_add_course_to_cart', 'intelliaat_add_course_to_cart_ajax' );
add_action( 'wp_ajax_nopriv_add_course_to_cart', 'intelliaat_add_course_to_cart_ajax' );
function intelliaat_add_course_to_cart_ajax(){
	$response =array();
	if(!isset($_POST)){
		
			$response = array(
					  	'result'	=> false,					  
					  );		  
	}else{
		$pid = $_POST['product_id'] ;
		$course_id = $_POST['course_id'];
		global $woocommerce;
		$woocommerce->cart->add_to_cart( $pid);
		
		$course_in_cart = array();
		$course_in_cart[$course_id] = $pid; 
		setcookie('course_in_cart',json_encode($course_in_cart), time()+60*60*24*365, get_permalink($course_id)); 
		
		if(function_exists('w3tc_pgcache_flush_post'))
			w3tc_pgcache_flush_post($course_id); 
		
		$response = array(
						'result'	=> true,
						'message'	=> 'Moved to cart.'						  
					  );	
	}
	echo json_encode($response);
	die();
}
?>