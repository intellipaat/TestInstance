<?php 

add_shortcode( 'pdf', 'pdf_sample' );

function pdf_sample($atts){
	
	global $post;
	
	extract( shortcode_atts( array(

		  'id'			=>	'',
		  'url'			=>	'',
		  'title'		=>	'Sample Assignment of '.get_the_title($post->ID)

      ), $atts ) );
	
	
				
		$output ='<div class="sample-pdf">							
					';
						
						//if(is_user_logged_in() || isset($_COOKIE['intellipaat_visitor']) ){
							
							$output .= '<a href="'.$url.'" class="pdf-link">
											<i class="icon-bookmark-file-1"></i>	
											'.$title.' <span class="pull-right">Download</span>
										</a>';
																				
						/*}
						else{
			
							$output .= '<a class="popup-with-form" href="#login-form">
											<i class="icon-bookmark-file-1"></i>	
											'.$title.' <span class="pull-right">Download</span>
										</a>';
			
						}*/
	
	$output .= 		'
				</div>';
	
	return $output;
}

/*
*	http://stackoverflow.com/questions/11990902/how-to-force-pdf-to-download-beginner
*	http://css-tricks.com/snippets/htaccess/force-files-to-download-not-open-in-browser/
*/

?>