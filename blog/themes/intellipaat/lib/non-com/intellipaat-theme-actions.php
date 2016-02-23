<?php

function register_my_session()
{
  if( !session_id() )
  {
    session_start();
  }
}

add_action('init', 'register_my_session');


/*
 *	Enqueue CSS and JS on website
 */
function intellipaat_custom_script() {
	
	if( !is_admin()){
		
		wp_enqueue_script('custom_script', 
						  get_stylesheet_directory_uri().'/js/custom.non-com.js', 
						  array('jquery','jquery-ui-core','bp-course-js','intellipaat_script'), 
						  '1.0', 
						  true);
	}
 
}
add_action('wp_enqueue_scripts', 'intellipaat_custom_script', 30);


?>