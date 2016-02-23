<?php

include_once('intellipaat-com-actions.php');

function stop_robots(){
	echo '<meta name="robots" content="noindex, nofollow">';	
}
add_action('wp_head', 'stop_robots',1);
add_action('wp_head', 'stop_robots',30);


?>