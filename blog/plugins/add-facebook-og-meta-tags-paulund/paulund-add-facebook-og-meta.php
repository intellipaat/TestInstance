<?php
/*
 * Plugin Name: Paulund Add Facebook OG Meta tags
 * Plugin URI: http://www.paulund.co.uk
 * Description: Add facebook og meta tag to the head tag of your blog
 * Version: 1.0
 * Author: Paul Underwood
 * Author URI: http://www.paulund.co.uk
 * License: GPL2 

    Copyright 2012  Paul Underwood

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License,
    version 2, as published by the Free Software Foundation. 

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details. 
*/
	
	add_action('wp_head', 'add_fb_open_graph_tags');
	
	/**
	 * Adds the facebook open graph meta tags to the head tag
	 */
	function add_fb_open_graph_tags(){
		if (is_single() && !is_admin()) {
			global $post;
			if(get_the_post_thumbnail($post->ID, 'thumbnail')) {
				$thumbnail_id = get_post_thumbnail_id($post->ID);
				$thumbnail_object = get_post($thumbnail_id);
				$image = $thumbnail_object->guid;
			} else {	
				$image = 'http://www.paulund.co.uk/wp-content/uploads/2011/12/logo.jpg'; // Change this to the URL of the logo you want beside your links shown on Facebook
			}
			//$description = get_bloginfo('description');
			$description = my_excerpt( $post->post_content, $post->post_excerpt );
			$description = strip_tags($description);
			$description = str_replace("\"", "'", $description);
	?>
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:image" content="<?php echo $image; ?>" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:description" content="<?php echo $description ?>" />
	<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
	<meta property="fb:page_id" content="281089281939813" />

<?php 	}
	}

	/**
	 * Add the my excerpt function to get the description of the post
	 */
	function my_excerpt($text, $excerpt){
		
	    if ($excerpt) return $excerpt;
	
	    $text = strip_shortcodes( $text );
	
	    $text = apply_filters('the_content', $text);
	    $text = str_replace(']]>', ']]&gt;', $text);
	    $text = strip_tags($text);
	    $excerpt_length = apply_filters('excerpt_length', 55);
	    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
	    $words = preg_split("/[\n
		 ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
	    if ( count($words) > $excerpt_length ) {
	            array_pop($words);
	            $text = implode(' ', $words);
	            $text = $text . $excerpt_more;
	    } else {
	            $text = implode(' ', $words);
	    }
	
	    return apply_filters('wp_trim_excerpt', $text, $excerpt);
	}
?>