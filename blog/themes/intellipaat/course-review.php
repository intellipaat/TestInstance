<?php
	$review_status = vibe_get_option('turn_on_review');

    $fields =  array(
        'author' => '<p><label class="comment-form-author clearfix">'.__( 'Name','vibe' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input class="form_field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" /></p>',
        'email'  => '<p><label class="comment-form-email clearfix">'.__( 'Email','vibe' ) .  ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .          '<input id="email" class="form_field" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"/></p>',
        'url'   => '<p><label class="comment-form-url clearfix">'. __( 'LinkedIn Profile address','vibe' )  . '</label>' . '<input id="url" name="url" type="text" class="form_field" value="' . esc_attr( $commenter['comment_author_url'] ) . '"/></p>',
         );
        
   $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="8" ">'.$content.'</textarea></p>';

  if(is_user_logged_in()):

    global $post;

    $user_id = get_current_user_id();
    $coursetaken=get_user_meta($user_id,$post->ID,true);

    if(isset($coursetaken) && $coursetaken){
    
      
    $answers=get_comments(array(
      'post_id' => $post->ID,
      'status' => 'approve',
      'user_id' => $user_id
      ));
    if(isset($answers) && is_array($answers) && count($answers)){
        $answer = end($answers);
        $content = $answer->comment_content;
    }else{
        $content='';
    }
	//moved two variable to top

   if ( isset($_POST['review']) && wp_verify_nonce($_POST['review'],get_the_ID()) ):

    comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Post Review','vibe'),'title_reply'=> '<span>'.__('Write a Review','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
    echo '<div id="comment-status" data-quesid="'.$post->ID.'"></div>';
    endif;
  }
  ?>
<?php
	elseif($review_status): // elseif part added
		comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Post Review','vibe'),'title_reply'=> '<span>'.__('Write a Review','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
   		echo '<div id="comment-status" data-quesid="'.$post->ID.'"></div>';
	endif;
?>
<div id="reviews">
    <p class="review_title h3"><?php _e('Course Reviews','vibe'); ?></p>
      <?php
      if (get_comments_number()==0) {
        echo '<div id="message" class="notice"><p>';_e('No Reviews found for this course.','vibe');echo '</p></div>';
      }else{
      ?>
      <ol class="reviewlist commentlist"> 
      <?php 
	  		single_course_page_reviews($post->ID);
            //wp_list_comments('type=comment&avatar_size=120&reverse_top_level=false'); 
            paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') )
        ?>  
      </ol> 
    <?php
      }
	  
	  echo '<div class="aligncenter hidden-xs" style="width: 93px;">'.do_shortcode('[button url="'.site_url('/reviews/').'" class="aligncenter" size="0px" margin="0" radius="0px" width="0px" height="0px" target="_self"]View all[/button]').'</div>';
    ?>
</div>