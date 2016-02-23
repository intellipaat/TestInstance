<?php

/**
* Adds meta sescription setting for intellipaat
*
*/

function intellipaat_meta_description(){
	$id =get_the_ID();
	if(is_tax('course-cat'))
		$id = 'course-cat_'.get_queried_object()->term_id;
	$intellipaat_meta_description = get_field('intellipaat_meta_description', $id); 
	if(!empty($intellipaat_meta_description)){
		echo '<div id="self-instructor" class="content hidden-xs">'.$intellipaat_meta_description.'</div>';	//<h4 class="heading"><b>Self-Paced Vs Instructor LED Online</b> </h4>
	}
}

add_action('bp_after_course_home_content','intellipaat_meta_description' );
add_action('bp_after_directory_course','intellipaat_meta_description' );


//Yoast WP-SEO Canonical Fix

function intellipaat_canonical_exclude( $canonical ) {
	global $post;

	if (is_page( 'all-courses' )) {
		//$canonical = false;
    }
	//echo 'test'.$meta = get_post_meta($post->ID,'wpseo_canonical',true);


if($post->ID == '1807'){
	//$canonical = site_url('/all-courses/big-data-hadoop-training');
}

    return $canonical;
}
 
add_filter( 'wpseo_canonical', 'intellipaat_canonical_exclude' );


//Yoast WP-SEO extra reaplacement variable

function intellipaat_wpseo_coursename_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		
		return str_replace(array('Training', 'training'), array('',''), $post->post_title);

    }
	return $var;
}
/**********************

First try and again rewritten in another way.

function intellipaat_wpseo_courseduration_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$hours = get_field('course_1_key_feature');
		if(!empty($hours)){
			$check_string = array('hrs', 'Hrs', 'HRS', 'HRs', 'Hr','HR' );
			foreach($check_string as $str){					
				$pos = strpos($hours, $str);
				if($pos)
					return substr($hours, $pos-3, 2).' Hrs Learning,';
			}
		}
		return '';
    }
	return $var;
}

function intellipaat_wpseo_projectduration_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$hours = get_field('course_2_key_feature');
		if(!empty($hours)){
			$check_string = array('hrs', 'Hrs', 'HRS', 'HRs', 'Hr','HR' );
			foreach($check_string as $str){					
				$pos = strrpos($hours, $str);
				if($pos){
					$hour = substr($hours, $pos-3, 2);
					if(is_numeric($hour))
						return $hour.' Hrs Projects,';
				}
			}
		}
		return '';
    }
	return $var;
}*/
/****
* 	http://stackoverflow.com/questions/6278296/extract-numbers-from-a-string
*/
function intellipaat_wpseo_courseduration_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$key_feature = get_field('course_1_key_feature');
		if(!empty($key_feature)){
			//preg_match_all('!\d+!', $key_feature, $hours);
			$hours = str_replace(array('-','+'), array('',''), filter_var($key_feature, FILTER_SANITIZE_NUMBER_INT));
			if(is_numeric($hours))
				return $hours.' Hrs Learning';
		}
		return '';
    }
	return $var;
}
function intellipaat_wpseo_projectduration_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$key_feature = get_field('course_2_key_feature');
		if(!empty($key_feature)){
			//preg_match_all('!\d+!', $key_feature, $hours);
			$hours = str_replace(array('-','+'), array('',''), filter_var($key_feature, FILTER_SANITIZE_NUMBER_INT));
			if(is_numeric($hours))
				return $hours.' Hrs Projects';
		}
		return '';
    }
	return $var;
}
function intellipaat_wpseo_price_filter($var){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$pid=get_post_meta($post->ID,'vibe_product',true);
		if(isset($pid) && $pid){} else $pid=get_post_meta($post->ID,'intellipaat_online_training_course',true); 
			
		$product = get_product( $pid );
		if(is_object($product)){
			$price = $product->get_price();  
			if(TLD == 'com' || TLD == 'us' ){
				$price_inr = vibe_get_option('dollar_to_inr_conversion_rate') * $price;
				return '&#8377; '.$price_inr.' // '.get_woocommerce_currency().' '.$price;
			}else
				return get_woocommerce_currency().' '.$price;
				
		}

    }
	return $var;
}
function intellipaat_wpseo_register_extra_replacements(){
	wpseo_register_var_replacement( '%%coursename%%', 'intellipaat_wpseo_coursename_filter', 'basic', 'Replaced with course title. Removes Training or training word from title. Only for courses.' );
	wpseo_register_var_replacement( '%%courseduration%%', 'intellipaat_wpseo_courseduration_filter', 'basic', 'Replaced with course duration shown in first key features. " hrs" string is necessory in first key feature. Only for courses.' );
	wpseo_register_var_replacement( '%%projectduration%%', 'intellipaat_wpseo_projectduration_filter', 'basic', 'Replaced with project duration shown in second key features. " hrs" string is necessory in second key feature. Only for courses.' );
	wpseo_register_var_replacement( '%%price%%', 'intellipaat_wpseo_price_filter', 'basic', 'Replaced with course fees. Only for courses.' );
}
add_action( 'wpseo_register_extra_replacements', 'intellipaat_wpseo_register_extra_replacements' );



/***
*	Overrides title method for yoast seo settings. Yoast have separate input box for title input. Client needs separate switch which can override default title template if it is on.
**/
function intellipaat_seo_title($title){
	
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$manual_title =  get_field('intellipaat_seo_manual_title');
		if(!$manual_title){
			global $wp_query;
			
			$wpseo_titles = get_option('wpseo_titles');
			$title_course = $wpseo_titles['title-course'];			
			$object = $wp_query->get_queried_object();
		
			return wpseo_replace_vars( $title_course, $object );			
		}
	}
	
	return $title;
}
add_filter( 'wpseo_title', 'intellipaat_seo_title', 11, 1 );

function intellipaat_all_course_seo_title($title){
	
	//For all courses page.
	$object = get_queried_object();	
	if(!empty($object->post_name)){
		if($object->post_name == 'all-courses'){
			$taxonomy = 'course-cat';
			$cat = get_query_var('course_category');

			if(empty($cat))
				$cat = vibe_get_option('default_courses_cat');
						
			$term = get_term_by('slug', $cat, $taxonomy );	
			//$tax_meta = WPSEO_Taxonomy_Meta::get_term_meta( (int) $term->term_id, $term->taxonomy );var_dump($tax_meta ); // This line also can be used
			//return $tax_meta['wpseo_title'];
			$term_title = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'title' );
			if ( is_string( $term_title ) && $term_title !== '' ) {
				return wpseo_replace_vars( $term_title, $term );
			}
			else{
				$wpseo_titles = get_option('wpseo_titles');	
				return wpseo_replace_vars( $wpseo_titles['title-tax-' . $taxonomy], $term );
			}		
		}
	}
	return $title;	
}

add_filter( 'wp_title', 'intellipaat_all_course_seo_title', 100, 1 );
add_filter( 'wpseo_opengraph_title', 'intellipaat_all_course_seo_title', 11, 1 );

/***
*	Overrides title method for yoast seo settings. Yoast have separate input box for title input. Client needs separate switch which can override default title template if it is on.
**/
function intellipaat_seo_metadesc($metadesc){
	global $post;
	if (is_singular() && $post->post_type == 'course' && !is_admin()) {
		$manual_metadesc =  get_field('intellipaat_seo_manual_metadesc');
		if(!$manual_metadesc){
			global $wp_query;
			
			$wpseo_titles = get_option('wpseo_titles');
			$metadesc_course = $wpseo_titles['metadesc-course'];			
			$object = $wp_query->get_queried_object();
		
			return wpseo_replace_vars( $metadesc_course, $object );			
		}
	}
	
	if (is_singular() && $post->post_type == 'tutorial' && !is_admin()) {		
		if(empty($metadesc)){	
			if($post->post_parent){ 
				 return get_post_meta( $post->post_parent, '_yoast_wpseo_metadesc', true);
			}			
		}
	}
	
		//For all courses page.
	$object = get_queried_object();	
	if(!empty($object->post_name)){
		if($object->post_name == 'all-courses'){
			$taxonomy = 'course-cat';
			$cat = get_query_var('course_category');

			if(empty($cat))
				$cat = vibe_get_option('default_courses_cat');
						
			$term = get_term_by('slug', $cat, $taxonomy );
			$term_metadesc = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'desc' );
			if ( is_string( $term_metadesc ) && $term_metadesc !== '' ) {
				return wpseo_replace_vars( $term_metadesc, $term );
			}
			else{
				$wpseo_titles = get_option('wpseo_titles');	
				return wpseo_replace_vars( $wpseo_titles['metadesc-tax-' . $taxonomy], $term );
			}		
		}
	}
	
	return $metadesc;
}
add_filter( 'wpseo_metadesc', 'intellipaat_seo_metadesc', 11, 1 );



/***
*	Overrides title method for yoast seo settings. Yoast have separate input box for title input. Client needs separate switch which can override default title template if it is on.
**/
function intellipaat_json_ld_search_url($search_url){
	return $search_url . '&post_type=course';
}
add_filter( 'wpseo_json_ld_search_url', 'intellipaat_json_ld_search_url', 11, 1 );



/*
*	WPSEO breadcrumb modificaton
*/
function intellipaat_breadcrumb_output($output){
	if(is_single() || is_page()){
		return str_replace(array('<span typeof="v:Breadcrumb"><strong class="breadcrumb_last" property="v:title"'),array('<span><strong class="breadcrumb_last"'),$output);
	}
	return $output;
}
add_filter(  'wpseo_breadcrumb_output', 'intellipaat_breadcrumb_output');


/*
*	WPSEO remove course archive url
*/
function intellipaat_sitemap_post_type_archive_link($archive_url='', $post_type =''){
	if($post_type == 'course'){
		return '';
	}
	return $archive_url;
}
add_filter(  'wpseo_sitemap_post_type_archive_link', 'intellipaat_sitemap_post_type_archive_link', 20, 2);


/*
*	WPSEO change priotity in xml sitemap for post type course
*/
function intellipaat_xml_sitemap_post_priority($return, $post_type, $post){
	if($post_type == 'course'){
		return 0.85;
	}
	return $return;
}
add_filter(  'wpseo_xml_sitemap_post_priority', 'intellipaat_xml_sitemap_post_priority', 11, 3);

/*
*	WPSEO change in open graph
*/
function intellipaat_wpseo_json_ld_output($data, $context){
	if($context == 'company'){
		
		$toll_free_number 	= vibe_get_option('toll_free_number');
		$mobile_no_1 		= vibe_get_option('mobile_no_1');
		$mobile_no_2	 	= vibe_get_option('mobile_no_2');
		$email_address 		= vibe_get_option('email_address');	
		
		$telephone = array($toll_free_number ,$mobile_no_1);
		
		if($mobile_no_2)
			$telephone[] = $mobile_no_2;
		
		/*$data["address"]	= array(
									array(
										"@type" 			=>	"PostalAddress",
										"addressLocality"	=>	"London, UK",
      									"postalCode"		=>	"EC3V 3LT",
										"streetAddress"		=>	"1 Royal Exchange Avenue, Bank"
									  ),
									array(
										"@type" 			=>	"PostalAddress",
										"addressLocality"	=>	"Paris, France",
      									"postalCode"		=>	"75116",
										"streetAddress"		=>	"55 Avenue Marceau"
									  ),
									array(
										"@type" 			=>	"PostalAddress",
										"addressLocality"	=>	"Phoenix, Mauritius",
      									"postalCode"		=>	"76546",
										"streetAddress"		=>	"KKT Centre, 84A, Royal Road"
									  ),
								);*/
		$data["email"]			= $email_address ;
		$data["telephone"]		= $telephone;
		$data["contactPoint"]	= array(
									array(
										"@type" 			=>	"ContactPoint",
										"telephone"			=>	$toll_free_number ,
										"contactType"		=>	"customer service",
     									"contactOption" 	=>	 "TollFree",
      									"availableLanguage"	=>	array("English"),
										"areaServed"		=>	array('US','GB')
									  ),
									array(
										"@type" 			=>	"ContactPoint",
										"telephone"			=>	$mobile_no_1,
										"contactType"		=>	"customer service",
      									"availableLanguage"	=>	array("English","Hindi"),
										"areaServed"		=>	array('US','IN')
									  )
								);
	}
	return $data;
}
add_filter( 'wpseo_json_ld_output',  'intellipaat_wpseo_json_ld_output', 10, 2);

?>
