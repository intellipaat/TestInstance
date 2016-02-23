<?php 

add_shortcode( 'slideshare', 'slideshare' );

function slideshare($atts){
	
	global $post;
	
	extract( shortcode_atts( array(

		  'id'			=>	'',
		  'title'		=>	'Sample Class Presentation of '.get_the_title($post->ID)

      ), $atts ) );
	
	
				
		$output ='<div class="slideshare-presentation">							
					';
						
						//if(is_user_logged_in() || isset($_COOKIE['intellipaat_visitor']) ){
							
							$output .= '						
								<a href="javascript:void(0);" class="slideshare-link">
									<i class="icon-slideshare"></i>	
									'.$title.'</a>
									<div class="slideshare hidden">
										<iframe src="//www.slideshare.net/slideshow/embed_code/'.$id.'" width="100%" height="355" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;" allowfullscreen> </iframe>
									
									</div>';
																				
						/*}
						else{
			
							$output .='<a class="popup-with-form" href="#login-form">
										<i class="icon-slideshare"></i>	
										'.$title.'
									</a>';
			
						}*/
	
	$output .= 		'
				</div>';
	
	return $output;
}

?>