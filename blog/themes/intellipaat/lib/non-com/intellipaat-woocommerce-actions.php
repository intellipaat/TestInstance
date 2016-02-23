<?php


/*
	not applicable on .com site
*/

add_action('wp_head','intellipaat_canonical_meta_on_product_page', 5);
add_filter( 'wpseo_canonical', 'intellipaat_remove_dup_canonical_link', 99 );

function intellipaat_canonical_meta_on_product_page(){
	if(is_singular('product') && TLD != 'com' ){
		global $post;
		$vcourses=array();
		$vcourses=vibe_sanitize(get_post_meta($post->ID,'vibe_courses',false));
		if(count($vcourses) ==1){
			foreach($vcourses as $course){
				echo '<link rel="canonical" href="'.get_permalink($course).'" />';
			}
		}
	}
}
// Remove Canonical Link Added By Yoast WordPress SEO Plugin
function intellipaat_remove_dup_canonical_link($canonical) {
	if(is_singular('product') && TLD != 'com' ){
		global $post;
		$vcourses=array();
		$vcourses=vibe_sanitize(get_post_meta($post->ID,'vibe_courses',false));
		if(count($vcourses) == 1){
			return false;
		}
	}
    return $canonical;
}


?>