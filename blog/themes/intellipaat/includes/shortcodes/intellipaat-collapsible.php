<?php
add_shortcode('collapsible','collapsible');

function collapsible($atts, $content=''){
	
		extract( shortcode_atts( array(
      		'id'   => '',	  
		), $atts ) );
		
		return '.... <a href="#" onClick="jQuery(this).next().removeClass(\'hidden\'); jQuery(this).remove();return false;">View More</a><span class="collapsible-text hidden">'.$content.'</span>';
}

/**
$randID = 'collapsible-'.mt_rand (10,100);

return '.... <a href="#'$randID'" onClick="'$randID'()">View More</a><span id="'.$randID.'" class="collapsible-text hidden">'.$content.'</span> <script>jQuery(\'#'.$randID.'\').removeClass(\'hidden\');return false;</script>';
	*/	
?>