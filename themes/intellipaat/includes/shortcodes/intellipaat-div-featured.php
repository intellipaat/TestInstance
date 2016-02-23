<?php
function featured_shortcode($atts, $content)
{
	return "<div class='featured-video row'>".do_shortcode($content)."</div>";
}
add_shortcode('featured','featured_shortcode');
?>