<?php 

add_shortcode('reviews','reviews_generator');

function reviews_generator($atts){
	global $wpdb;

	/*
	extract( shortcode_atts( array(

      		'id'   => '',
	  
      ), $atts ) );
	*/
	/*$output = "";
	 $args = array(
        'number'=>160,
        'offset'=>0,
        'status'=>'approve',
        'order'=>'DESC',
		'orderby'=>'comment_author_email',
    );
	 */
	//$comments = get_comments($args);
	//$query = "SELECT * FROM $wpdb->comments as c JOIN (SELECT DISTINCT comment_author, comment_author_email FROM $wpdb->comments) as dc WHERE c.comment_author_email = dc.comment_author_email ORDER BY c.comment_date_gmt DESC LIMIT 160"; var_dump($query);

	//$query = "SELECT DISTINCT * FROM $wpdb->comments WHERE comment_type = '' AND comment_approved = '1' GROUP BY comment_author_email ORDER BY comment_date_gmt DESC LIMIT 80";
	
	/* $query1 = "SELECT DISTINCT * FROM $wpdb->comments c LEFT JOIN $wpdb->commentmeta cm ON (c.comment_ID=cm.comment_id) LEFT OUTER JOIN $wpdb->posts p ON (c.comment_post_ID =p.ID) WHERE p.post_type = 'course' AND c.comment_type = '' AND c.comment_approved = '1' AND cm.meta_key='order_on_review' ORDER BY CONVERT(cm.meta_value, SIGNED INTEGER) LIMIT 80";
 
 $query2 = "SELECT DISTINCT * FROM $wpdb->comments LEFT JOIN $wpdb->commentmeta ON ($wpdb->comments.comment_ID=$wpdb->commentmeta.comment_id) LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =$wpdb->posts.ID)
 WHERE $wpdb->posts.post_type = 'course' AND comment_type = '' AND comment_approved = '1' AND $wpdb->commentmeta.meta_key!='order_on_review' GROUP BY comment_author_email LIMIT 80";
	
	
	
	$comments1 = $wpdb->get_results( $query1 );
	$comments2 = $wpdb->get_results( $query2 );
	$comments = array_merge($comments1, $comments2);
*/
	

 $query1 = "SELECT DISTINCT * FROM $wpdb->comments c 
LEFT JOIN $wpdb->commentmeta cm ON (c.comment_ID=cm.comment_id) LEFT OUTER JOIN $wpdb->posts p ON (c.comment_post_ID =p.ID)
 WHERE p.post_type = 'course' AND c.comment_type = '' AND c.comment_approved = '1'  AND CONVERT(cm.meta_value, SIGNED INTEGER)>0  AND cm.meta_key='order_on_review' ORDER BY CONVERT(cm.meta_value, SIGNED INTEGER) ASC LIMIT 80";

  $query1_temp = "SELECT GROUP_CONCAT(DISTINCT c.comment_ID) as ids_va FROM $wpdb->comments c 
LEFT JOIN $wpdb->commentmeta cm ON (c.comment_ID=cm.comment_id) LEFT OUTER JOIN $wpdb->posts p ON (c.comment_post_ID =p.ID)
 WHERE p.post_type = 'course' AND c.comment_type = '' AND c.comment_approved = '1'  AND CONVERT(cm.meta_value, SIGNED INTEGER)>0 AND cm.meta_key='order_on_review' ORDER BY CONVERT(cm.meta_value, SIGNED INTEGER) ASC LIMIT 80";
	
	$comments1 = $wpdb->get_results( $query1 );
	$comments1_temp = $wpdb->get_results( $query1_temp );


 $query2 = "SELECT DISTINCT * FROM $wpdb->comments 
LEFT JOIN $wpdb->commentmeta ON ($wpdb->comments.comment_ID=$wpdb->commentmeta.comment_id) LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =$wpdb->posts.ID)
 WHERE $wpdb->comments.comment_ID NOT IN (".$comments1_temp[0]->ids_va.") AND  $wpdb->posts.post_type = 'course' AND comment_type = '' AND comment_approved = '1' AND $wpdb->commentmeta.meta_key!='order_on_review' GROUP BY comment_author_email ORDER BY  comment_date_gmt DESC  LIMIT 80";
	

	$comments2 = $wpdb->get_results( $query2 );
	$comments = array_merge($comments1, $comments2);

	$output = '<div class="reviewlist commentlist"> ';
	$count = 0;
	
	foreach($comments as $comment ){
			
			if($count %2 == 0) $output .= '<div class="row"> ';
		
			$rating ='';
			$url = ('http://' == $comment->comment_author_url) ? '' : $comment->comment_author_url;
				$url = esc_url( $url, array('http', 'https') );
				if( trim($comment->comment_author_url) != '' ) {
						$url  = '<div class="pull-right">Follow Me on <a rel="nofollow noindex" class="linkedin_url" href="' . esc_attr( $url  ) . '">LinkedIn</a></div>';
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
			$output .= '<div class="col-md-6"> ';
				$output .= '<div class="col-md-6 comment"> ';
					 $output .=   '<div class="comment-body" id="div-comment-'.$comment->comment_ID.'">
											<div class="comment-author vcard">
											'.$thumb.'			<cite class="fn">'.apply_filters( 'comment_author',$comment->comment_author ) .'</cite> 	
											</div>
									
							
									<p>' .( ( $commenttitle) ? '<strong>' . esc_attr( $commenttitle ) . '</strong><br/><br/>' : '').$comment->comment_content.'</p>
									'.$rating.'
									'.$url .'
								</div>'; 
				$output .= '</div> '; 
			$output .= '</div> '; 
			
			if($count %2 == 1) $output .= '</div> ';
			$count++;
	}
	$output .= ' </div> ';
	$output .=   paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );
	return $output;
}

?>
