<?php

global $post;

add_filter('upload_mimes','intellipaat_custom_allowed_file_exts');

function intellipaat_custom_allowed_file_exts($mimes)
{
    // Add file extension 'extension' with mime type 'mime/type'
    $mimes['java'] = 'text/x-java-source';
    $mimes['dat'] = 'application/dat';
    return $mimes;
}


/*
 *	Intellipaat mail actions
 */

if(function_exists('wp_mail_smtp_mail_from_name')){
	remove_filter('wp_mail_from_name','wp_mail_smtp_mail_from_name' );
	add_filter('wp_mail_from_name','intellipaat_mail_from_name', 1000 );
	function intellipaat_mail_from_name ($orig) {	
		if( get_option('mail_from_name') != "" && is_string(get_option('mail_from_name')) )
			return get_option('mail_from_name');
		return $orig;
	}
}

//Use whenever needed and remove them to not conflict.
//add_filter( 'wp_mail_content_type', 'intellipaat_set_content_type_html' );
//add_filter( 'wp_mail_content_type', 'intellipaat_set_content_type_plain' );
function intellipaat_set_content_type_html( $content_type ) {
	return 'text/html';
}
function intellipaat_set_content_type_plain( $content_type ) {
	return 'text/plain';
}


/*
 *	Enqueue CSS and JS on website
 */
function intellipaat_javascripts() {
	
	if( !is_admin()){
		
		wp_enqueue_style('page', get_stylesheet_directory_uri().'/css/page.css', array('theme-css'), '1.0');
		
		if(is_page(all_course_page_id()))		//add script on all course page. required for masonary grid.
			wp_enqueue_script( 'isotope', VIBE_URL.'/js/jquery.isotope.min.js');
			
		 //Removing Woocommerce unnecessary javascripts
		if((is_single() && get_post_type() != 'product') || (is_page() && !is_page('cart') && !is_page('checkout')) || is_home() || is_front_page()){
			wp_deregister_script( 'woocommerce' );
			wp_deregister_script( 'wc-cart-fragments' );
		}
		
		if(is_singular('interview-question')){
			wp_enqueue_script( 'jquery-ui-tabs' );
		}
		
		wp_enqueue_script('intellipaat_script', 
						  get_stylesheet_directory_uri().'/js/common.js', 
						  array('jquery','jquery-ui-core','bp-course-js'), 
						  '1.5.1', 
						  true);
		global $wp;
		$settings_array = array(
			'cartUrl' 		=> WC()->cart->get_cart_url(),
			'cart_ajax_url' => admin_url('admin-ajax.php?action=intellipaat_cart'),
			'SecKeyLink'	=>	admin_url('admin-ajax.php?action=intellipaat_visitor_secure_key'),
			'currentPage'	=>	home_url(add_query_arg(array(),$wp->request))
		); 

		if(is_page('cart')){ $settings_array['cart_page'] = 1; } else { $settings_array['cart_page'] = 0; }
		if(is_page('checkout')){ $settings_array['saleInitiateUrl'] = admin_url('admin-ajax.php?action=intellipaat_visitor_purchase'); } 
		wp_localize_script( 'intellipaat_script', 'intellipaat', $settings_array );
		
		if(is_page(array('Online training calendar','online-training-calendar')) || is_singular('course'))
			wp_enqueue_script('moment',get_stylesheet_directory_uri().'/js/moment.min.js','','2.8.2', false );
	}
 
}
add_action('wp_enqueue_scripts', 'intellipaat_javascripts', 30);


/*
 *	cusotm javascripts to be added on home page head
 */

add_action('wp_head','intellipaat_print_head_scripts', 50);

function intellipaat_print_head_scripts()
{
	
	echo vibe_get_option('tracking_code');

	if(is_home() || is_front_page()){
		echo vibe_get_option('home_page_head_scripts');
	}
}


/*
 *	cusotm javascripts to be added on home page head
 */

add_action('wp_footer','intellipaat_print_footer_scripts', 50);

function intellipaat_print_footer_scripts()
{	
	global $post;
	
	if(!is_single() && !is_singular('course') && !is_admin() )
		return;
		
	$pid=get_post_meta($post->ID,'vibe_product',true);
	if(isset($pid) && $pid){} else $pid=get_post_meta($post->ID,'intellipaat_online_training_course',true);
		
	$product = get_product( $pid );
	if(is_object($product)){
		$price = $product->get_price();			
	}
	

?>
	
    <!-- Google Code for Remarketing Tag -->
    <!--------------------------------------------------
    Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
    --------------------------------------------------->
    <script type="text/javascript">
    var google_tag_params = {
    edu_pid: '<?php echo $post->ID ?>',
    edu_plocid: '<?php echo $post->post_title ?>',
    edu_pagetype: '<?php echo $post->post_type ?>',
    edu_totalvalue: '<?php echo $price ?>',
    };
    </script>
    <script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 981690275;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/981690275/?value=0&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
    
    
<?php
}


/*
*	Modifyied search for course search
*	http://wpsnipp.com/index.php/functions-php/limit-search-to-post-titles-only/
*	http://www.paulund.co.uk/limit-wordpress-search-to-post-titles
*/
function search_by_title_only( $search, &$wp_query )
{
    global $wpdb;

    if ( empty( $search ) || !isset($_GET['post_type']) || $wp_query->query['post_type'] != 'course')
        return $search; // skip processing - no search term in query

    $q = $wp_query->query_vars;    
    $n = ! empty( $q['exact'] ) ? '' : '%';

    $search =
    $searchand = '';

    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( like_escape( $term ) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }

    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
	
    return $search;
}
add_filter( 'posts_search', 'search_by_title_only', 500, 2 );

function search_by_content_only( $search, &$wp_query )
{
    global $wpdb;
	
    if ( empty( $search ) || !isset($_GET['post_type']) || $wp_query->query['post_type'] != 'course')
        return $search; // skip processing - no search term in query

    $q = $wp_query->query_vars;    
    $n = ! empty( $q['exact'] ) ? '' : '%';

    $search =
    $searchand = '';

    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( like_escape( $term ) );
        $search .= "{$searchand} ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')  "; //{$searchand}($wpdb->posts.post_title NOT LIKE '{$n}{$term}{$n}')
        $searchand = ' AND ';
    }

    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
	
    return $search;
}
//add_filter( 'posts_search', 'search_by_content_only', 500, 2 );

/**
 * 		Change order of posts on course search page
 */
function modify_main_query( $query ) {

	/**
	* http://wordpress.stackexchange.com/questions/72099/can-i-exclude-a-post-by-meta-key-using-pre-get-posts-function
	*/	
	
	/*if ( !is_admin() && $query->is_main_query() && $query->is_tax/*( !empty( $query->query['post_type'])  && $query->query['post_type']==  'course' ) ) {

		$query->set( 'orderby', 'menu_order');
		$query->query[ 'orderby']= 'menu_order';
		
		return;
	}*/
	
	
	if(!is_admin() && is_search() && is_main_query() && isset($_GET['post_type']) && isset($query->query["s"]) && $query->query["post_type"]=="course") {
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'DESC');
		$query->set('meta_key', 'vibe_students');
		$query->set( 'posts_per_page', -1 );
	}	//search results order set according to no of students enrolled in course
	
	return $query;

}
add_action( 'pre_get_posts', 'modify_main_query', 10 );

/**
 * 		Change order of posts on course category page
 */
function custom_courses_orderby($orderby) {
	global $wpdb;	
	if ( !is_admin() && is_main_query() && is_tax('course-cat') ) {
		$orderby = $wpdb->prefix . "posts.menu_order  ASC";
	}
	
	return $orderby;
}
add_filter('posts_orderby', 'custom_courses_orderby', 1);


/**
 * Remove the slug from published post permalinks.
 *
 * http://colorlabsproject.com/tutorials/remove-slugs-custom-post-type-url/
 */
function intelli_remove_cpt_slug( $post_link, $post, $leavename ) {
 
 
	if ( 'news' == $post->post_type && !is_admin() && (is_home() || is_front_page()) ) {
		$post_link = get_post_meta($post->ID, 'intellipaat-post-href', true);
		if($post_link)
			return $post_link.'" rel="nofollow noindex';
		else
			return site_url('media');
	}
	
	if ( 'post' == $post->post_type ) {
		$link = get_post_meta($post->ID, 'intellipaat_custom_blog_url', true);
		if(!empty($link))
			return $link;
		else
			return $post_link;
	}
	
	if ( 'course' == $post->post_type /*|| 'publish' != $post->post_status*/ ) {
		return str_replace( '/' . $post->post_type . '/', '/', $post_link );
	}
	 
	return $post_link;
}
add_filter( 'post_type_link', 'intelli_remove_cpt_slug', 10, 3 );
add_filter( 'post_link', 'intelli_remove_cpt_slug', 10, 3 );

function intellipaat_course_term_link( $termlink, $term, $taxonomy ){
	if($taxonomy == 'course-cat'){
		return  str_replace( '/' . $taxonomy . '/', '/all-courses/', $termlink );
	}
	return $termlink;
} 
add_filter( 'term_link', 'intellipaat_course_term_link', 50, 3 );
	
	
/**
 * Some hackery to have WordPress match postname to any of our public post types
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * Typically core only accounts for posts and pages where the slug is /post-name/
 */
function custom_parse_request_tricksy( $query ) {
 
    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;
 
    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
 
    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'course', 'page' ) );
    }
}
add_action( 'pre_get_posts', 'custom_parse_request_tricksy' );


/*
*	Force SSL on front end using theme options
*/
function force_ssl()
{	
    if (vibe_get_option('turn_on_ssl') && !is_ssl () )
    {
		wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		//header('HTTP/1.1 301 Moved Permanently');
      	//header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
     	exit();
    }
}
add_action('template_redirect', 'force_ssl');

/*
*	Custom function which will rewrite all url coming from database (non conditional action)
*/
function custom_ssl_url_scheme($url){
	return str_replace(array('http://intellipaat.com','http://intellipaat.in'),array('https://intellipaat.com','https://intellipaat.in'), $url );
}
add_filter('set_url_scheme', 'custom_ssl_url_scheme',10,1);


/*
*	Adds shopping cart icons to menu 
*/
add_filter('wp_nav_menu_items','intellipaat_country_menu', 10, 2);
function intellipaat_country_menu($menu, $args) {

	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( 'country' !== $args->theme_location )
		return $menu;
		
	$excluded_hreflang_pages = vibe_get_option('excluded_hreflang_pages');
	
	if((is_page() && !is_page($excluded_hreflang_pages)) || is_singular( array( 'course', 'post', 'tutorial' ,'news', 'jobs' ,'interview-question') ) || is_post_type_archive( array( 'course', 'post', 'tutorial' ,'news', 'jobs' ,'interview-question' ) )  || is_tax( 'tuts-category') || is_tax( 'course-cat') || is_tax('jobs-category')  || is_tax('iq-category'))
		return str_replace('/SELFLINK',parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),$menu);
	else
		return str_replace('SELFLINK','',$menu);
	 
}


/*
 *	http://someweblog.com/wordpress-custom-taxonomy-with-same-slug-as-custom-post-type/
 * Replace Taxonomy slug with Post Type slug in url
 * Version: 1.1
 */
function taxonomy_slug_rewrite($wp_rewrite) {
    $rules = array();
    // get all custom taxonomies
    $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');
    // get all custom post types
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
     
    foreach ($post_types as $post_type) {
        foreach ($taxonomies as $taxonomy) {
         
            // go through all post types which this taxonomy is assigned to
            foreach ($taxonomy->object_type as $object_type) {
                 
                // check if taxonomy is registered for this custom type
                if ($object_type == $post_type->rewrite['slug']) {
             
                    // get category objects
                    $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));
             
                    // make rules
                    foreach ($terms as $term) {
                        $rules[$object_type . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
                    }
                }
            }
        }
    }
    // merge with global rules
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'taxonomy_slug_rewrite');


?>