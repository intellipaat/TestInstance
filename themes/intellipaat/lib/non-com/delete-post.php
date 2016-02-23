<?php


function ipx_delete_post(){

	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'tutorial',
	);
	$posts_array = get_posts( $args ); 
	
	foreach ( $posts_array as $post ) {
	    wp_delete_post( $post->ID );
	}


}
add_action( 'admin_post_tuts_del', 'ipx_delete_post' );

?>