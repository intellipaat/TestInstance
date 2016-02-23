<?php 

add_shortcode('review_carousel','review_carousel_generator');

function review_carousel_generator($atts){
	
	extract( shortcode_atts( array(

      'id'   => '',
	  'class'=> '',
	  
      ), $atts ) );
	
	$output = " ";
	
	 $args = array(
        'number'=>10,
        'offset'=>0,
        'status'=>'approve',
		'post_type' => 'course',
        'order'=>'ASC',
    );
	
		$output .= "<h3 class='heading'>Testimonials</h3>";
		
		$output .= "<a class='heading_more' href='".site_url('/reviews')."'>+</a>";
		
		$output .= "<div id='review_carousel' class='flexslider review_carousel loading' >";
		
			$output .= "<ul class='slides'>";
			
			foreach( get_comments($args) as $comment ){
			
				
					$rating ='';
					$url = ('http://' == $comment->comment_author_url) ? '' : $comment->comment_author_url;
						$url = esc_url( $url, array('http', 'https') );
						if( trim($comment->comment_author_url) != '' ) {
								$url  = '<div class="pull-right">Follow Me on <a class="linkedin_url" rel="nofollow noindex" href="' . esc_attr( $url  ) . '">LinkedIn</a></div>';
						}  
						else
							$url ='';
						  
					$commenttitle = get_comment_meta($comment->comment_ID, 'review_title', true );
					if( $commentrating = get_comment_meta( $comment->comment_ID, 'review_rating', true ) ) {
					  $rating .= '<div class="comment-rating star-rating">';
					  for($i=0;$i<5;$i++){
						if($commentrating > $i)
						  $rating .='<span class="fill"></span>';
						else
						  $rating .='<span></span>';
					  }
					  $rating .='</div>'; 
					}  
					$user = get_user_by( 'email', $comment->comment_author_email );
			$bpuser = new BP_Core_User( $user->ID );
			if(empty($bpuser->avatar_thumb)){
				$thumb = get_avatar( $comment->comment_author_email , 120 );
			} else {
				$thumb = $bpuser->avatar_thumb;
			}
					$output .= '<li class="" style="margin-left:30px;"> ';
						$output .= '<div class=" comment"> ';
							 $output .=   '<div class="comment-body" id="div-comment-'.$comment->comment_ID.'">
												
												<div class="comment-author vcard">
												'.$thumb.'			<cite class="fn">'.apply_filters( 'comment_author',$comment->comment_author ) .'</cite> 	
												</div>
											
									
											<p>'.( ( $commenttitle) ? '<strong>' . esc_attr( $commenttitle ) . '</strong><br/><br/>' : '').$comment->comment_content.'</p>
											'.$rating.'
											'.$url .'
										</div>'; 
						$output .= '</div> '; 
					$output .= '</li> '; 
					
			}
			
			$output .= "</ul>";			
		
		$output  .= "</div>";	
	
		
	
	return $output;
}

?>
