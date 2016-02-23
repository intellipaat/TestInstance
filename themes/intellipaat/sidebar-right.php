<?php
	global $post;
	
	$taxonomies = get_object_taxonomies($post->post_type);
	$taxonomy = end($taxonomies);
	$terms = wp_get_post_terms( $post->ID, $taxonomy , array("fields" => "ids"));
	$id = $taxonomy.'_'.end($terms);
	if(get_option('active_common_sidebar_banner') == 'yes'){
		$intellipaat_ad_image_1 = get_option('sidebar_banner_url'); 
		$intellipaat_ad_url_1 = get_option('sidebar_banner_link'); 
	} else {
		$intellipaat_ad_image_1 = get_field('intellipaat_ad_image_1', $id); 
	    $intellipaat_ad_url_1 = get_field('intellipaat_ad_url_1', $id); 
	}
	
	$intellipaat_ad_image_2 = get_field('intellipaat_ad_image_2', $id); 
	$intellipaat_ad_url_2 = get_field('intellipaat_ad_url_2', $id);
	if(!empty($intellipaat_ad_image_1) ){
		echo '<div class="text-center aligncenter ad-wrapper">';
			if($intellipaat_ad_url_1)
				echo '<a href="'.$intellipaat_ad_url_1.'">';
			echo '<img  class="tuts-ads aligncenter img-responsive" src="'.$intellipaat_ad_image_1.'" />';							
			if($intellipaat_ad_url_1)
				echo '</a>';
		echo '</div>';
	}
	if(!empty($intellipaat_ad_image_2) ){
		echo '<div class="text-center aligncenter ad-wrapper">';                            
			if($intellipaat_ad_url_2)
				echo '<a href="'.$intellipaat_ad_url_2.'">';
			echo '<img  class="tuts-ads aligncenter img-responsive" src="'.$intellipaat_ad_image_2.'" />';							
			if($intellipaat_ad_url_2)
				echo '</a>';									
		echo '</div>';
	}
    
	if($post->post_type == 'tutorial'){
		if(!$post->post_parent){		
			$id = $post->ID;
		}
		else{			
			$ancestors = get_ancestors( $post->ID, $post->post_type );
			$id = end($ancestors);
		}
		intellipaat_recommended_courses($id);
	}else	
		intellipaat_recommended_courses($post->ID);
	
    $sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
    
    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : 
    
    endif;
    
?>   