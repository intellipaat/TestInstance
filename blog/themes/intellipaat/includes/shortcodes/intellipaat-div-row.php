<?php
function row_shortcode($atts, $content)
{
	return "<div class='row'>".do_shortcode($content)."</div>";
}
add_shortcode('row','row_shortcode');
?>