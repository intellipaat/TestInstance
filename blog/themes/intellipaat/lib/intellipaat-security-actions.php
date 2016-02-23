<?php

// hide the meta tag generator from head and rss
remove_action('wp_head', 'wp_generator');
add_filter('the_generator','disable_version');
function disable_version() {
   return '';
}

?>