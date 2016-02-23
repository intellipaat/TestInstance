<?php


	function intellipaat_post_views_count($postID) {
		$count_key = 'ip_post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
	//To keep the count accurate, lets get rid of prefetching
	//remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	
	function intellipaat_track_post_views($post_id='') {
		if ( !is_singular( 'tutorial' )) 
			return ''; 

		if ( empty ( $post_id) ) {
			global $post;
			$post_id = $post->ID;    
		}
		intellipaat_post_views_count($post_id);
	}
	add_action( 'wp_head', 'intellipaat_track_post_views');

?>