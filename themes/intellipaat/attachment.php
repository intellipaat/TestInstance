<?php

/**
 * This file creates a custom attachment redirect page for any Genesis child theme.
 * @author    Brad Dalton
 * @example   http://wpsites.net/wordpress-tips/5-ways-to-redirect-attachment-pages-to-the-parent-post-url/
 * @copyright 2014 WP Sites
 */

$url = site_url();

if($post->post_parent && $post->post_parent !=0 )
	$url = get_permalink($post->post_parent);

wp_redirect($url);
?>