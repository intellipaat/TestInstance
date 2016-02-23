<?php

/*
*	Removed Groups menu from top logged in user popup
*	Add wishlst and refer friend menu
*/
function intellipaat_logged_in_top_menu($loggedin_menu){
	$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) ); 
	if ( !in_array( 'paid-memberships-pro/paid-memberships-pro.php', $active_plugins )) {
		unset($loggedin_menu['membership']);
	}
	if ( in_array( 'yith-woocommerce-wishlist/init.php', $active_plugins )) {
		$loggedin_menu['wishlist'] = array(
										   "icon"	=>	'icon-heart',
										   "label"	=>	'My wishlist',
										   "link"	=>	intellipaat_yith_wcwl_wishlist_page_url() ,
									);
	}
	if ( in_array( 'tc-refer-freinds/tc-refer-friends.php', $active_plugins )) {
		$loggedin_menu['referal'] = array(
										   "icon"	=>	'icon-users',
										   "label"	=>	'Refer A Friend',
										   "link"	=>	site_url() . '/refer-friend/',
									);
	}
	return $loggedin_menu;
}
add_filter('wplms_logged_in_top_menu','intellipaat_logged_in_top_menu');


/*function intellipaat_font_query_args($args){
		$args = str_replace('Open Sans', 'Open+Sans',$args);
		return $args;
}
add_filter('vibe_font_query_args','intellipaat_font_query_args');*/
?>