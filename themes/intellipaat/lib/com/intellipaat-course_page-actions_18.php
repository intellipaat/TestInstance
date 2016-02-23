<?php

/**
*	To manupulate thumbnail_generator output for class view
*/


function intellipaat_thumb_featured_image($return){
	global $post, $product; 
	$exists = YITH_WCWL()->is_product_in_wishlist( get_the_ID() );
	
	if ( bp_is_current_action( BP_WISHLIST_SLUG ) || !is_user_logged_in()) 
		return str_replace(array('<a href="">','</a></a>'),array('','</a>'),$return);
	else
		return $return.do_shortcode("[yith_wcwl_add_to_wishlist icon='fa-heart' exists='".$exists."' label='' already_in_wishslist_text='' product_id='".get_the_ID()."']");
}
add_filter( 'vibe_thumb_featured_image', 'intellipaat_thumb_featured_image',10,1 );


/**
*	Remove parent theme actions
*/

add_action('after_setup_theme','intellipaat_alter_wplms_hooks', 20);
function intellipaat_alter_wplms_hooks(){
	remove_action('wp_ajax_quiz_question', 'quiz_question');
	remove_filter('wplms_course_product_id','wplms_expired_course_product_id',10,3); // This hook called in vice course module plugin. For ingnoring error I removed this filter
	
	remove_action('wplms_unit_header','wplms_custom_unit_header',10,2); //intructor name
	remove_action('wplms_course_unit_meta','vibe_custom_print_button'); //print unit button
}

/*
*	Fixing "Too fast or answer not marked" issue in Quiz
*	http://support.vibethemes.com/support/solutions/articles/1000165098-fixing-too-fast-or-answer-not-marked-issue-in-quiz
*/
remove_filter('check_comment_flood', 'check_comment_flood_db');
add_filter('comment_flood_filter', '__return_false');

/*
*	Hide Correct answer and explanation till the user has passed the Quiz
*	http://support.vibethemes.com/support/solutions/articles/1000182141-hide-correct-answer-and-explanation-till-the-user-has-passed-the-quiz
*
*	You can totally hide the correct answer in the quiz results by adding this css in Customizer -> Custom CSS section :
*	http://support.vibethemes.com/support/discussions/topics/1000055389?page=1
*/
add_filter('wplms_show_quiz_correct_answer','wplms_check_passed_quiz',10,2);
function wplms_check_passed_quiz($return,$quiz_id){
	$user_id = get_current_user_id();
	$marks = get_post_meta($quiz_id,$user_id,true);
	if(isset($marks) && is_numeric($marks)){
		$passing_score = get_post_meta($quiz_id,'vibe_quiz_passing_score',true);
		if(is_numeric($passing_score) && $marks < $passing_score)
			return false;
	}
	return $return;
}


//BEGIN QUIZ
add_action('wp_ajax_quiz_question', 'intellipaat_quiz_question'); // Only for LoggedIn Users
if(!function_exists('intellipaat_quiz_question')){
  function intellipaat_quiz_question(){
      
      $quiz_id= $_POST['quiz_id'];
      $ques_id= $_POST['ques_id'];

      

      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){ // Same NONCE just for validation

        $user_id = get_current_user_id();
        $quiztaken=get_user_meta($user_id,$quiz_id,true);
        

         if(isset($quiztaken) && $quiztaken){
            if($quiztaken > time()){
                the_intellipaat_quiz('quiz_id='.$quiz_id.'&ques_id='.$ques_id);  
            }else{
              echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
        echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
            }
            
         }else{
            echo '<div class="message info"><h3>'.__('Start Quiz to begin quiz.','vibe').'</h3>'; 
            echo '<p>'.__('Click "Start Quiz" button to start the Quiz.','vibe').'</p></div>';
         }

     }else{
                echo '<div class="message error"><h3>'.__('Quiz not active.','vibe').'</h3>'; 
                echo '<p>'.__('Contact your instructor or site admin.','vibe').'</p></div>';
     }
     die();
  }  
}

remove_filter('bp_course_admin_before_course_students_list','bp_course_admin_search_course_students',10,2);
add_filter('bp_course_admin_before_course_students_list','intellipaat_course_admin_search_course_students',10,2);
function intellipaat_course_admin_search_course_students($students,$course_id){

	$course_statuses = apply_filters('wplms_course_status_filters',array(
		0 => __('Expired Course','vibe'),
		1 => __('Start Course','vibe'),
		2 => __('Continue Course','vibe'),
		3 => __('Under Evaluation','vibe'),
		4 => __('Course Finished','vibe')
		));
	echo '<form method="get" action="">
			<input type="hidden" value="admin" name="action">
			'.(isset($_GET['item_page'])?'<input type="hidden" name="item_page" value="'.$_GET['item_page'].'">':'').'
			<select name="status"><option value="">'.__('Filter by Status','vibe').'</option>';
			foreach($course_statuses as $key =>$value){
				echo '<option value="'.$key.'" '.selected($_GET['status'],$key).'>'.$value.'</option>';
			}
	echo  '</select>';
	do_action('wplms_course_admin_form',$students,$course_id);
	echo '<input type="text" name="student" value="'.$_GET['student'].'" placeholder="'.__('Enter student username/email','vibe').'" class="input" />
			<input type="submit" value="'.__('Let\'s find','vibe').'" />
		 </form>';
    if(isset($_GET['student']) && $_GET['student']){
    	$args = array(
			'search'         => $_GET['student'],
			'search_columns' => array( 'login', 'email','nicename'),
			'fields' => array('ID'),
			'meta_query' => array(
				array(
					'key' => $course_id,
					'compare' => 'EXISTS'
					)
				),
		);
    	$user_query = new WP_User_Query( $args );
    	$users = $user_query->get_results();

		if(count($users)){
			$students=array();
			foreach($users as $user){
				if(is_object($user) && isset($user->ID))
					$students[]=$user->ID;
			}
		}else{
			$info_message = 'Unable to find member using "'. $_GET['student'].'". You may try again using email or username.';
			wc_print_notice( $info_message, 'error' );
		}
    }
	return $students;
}


function the_unit_desc($id=NULL){
    if(!isset($id))
      return;
	echo get_unit_desc($id);
}

function get_unit_desc($id=NULL){
    if(!isset($id))
      return;
	
	$before	='<div class="unit_desc">'; //<h4 class="heading">Basic unit description</h4>
	$after ='</div>';
	
	$desc = get_field('intellipaat_brief_unit_description', $id);
	if(!empty($desc ))
		return $before.$desc.$after;
	
	return $desc;
}
/*
*	Prints description before unit.
*/
function intellipaat_unit_content_filter($content){
	global $post;
	if( is_singular('unit') && !is_admin()){
			$desc = get_unit_desc($post->ID);
			$content = $desc.$content;
	}
	return $content;
}
add_filter('the_content', 'intellipaat_unit_content_filter');

/*=== UNIT TRAVERSE =====*/
remove_action('wp_ajax_unit_traverse', 'unit_traverse');
remove_action( 'wp_ajax_nopriv_unit_traverse', 'unit_traverse' );
add_action('wp_ajax_unit_traverse', 'intellipaat_unit_traverse');
add_action( 'wp_ajax_nopriv_unit_traverse', 'intellipaat_unit_traverse' );

function intellipaat_unit_traverse(){
  $unit_id= $_POST['id'];
  $course_id = $_POST['course_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  //verify unit in course
  $units = bp_course_get_curriculum_units($course_id);
  if(!in_array($unit_id,$units)){
    _e('Session not in Course','vibe');
    die();
  }

  // Check if user has taken the course
  $user_id = get_current_user_id();
  $coursetaken=get_user_meta($user_id,$course_id,true);

  //if(!isset($_COOKIE['course'])) {
    if($coursetaken>time()){
      setcookie('course',$course_id,$expire,'/');
      $_COOKIE['course'] = $course_id;
    }else{
      $pid=get_post_meta($course_id,'vibe_product',true);
      $pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
      if(is_numeric($pid))
        $pid=get_permalink($pid);

      echo '<div class="message"><p>'.__('Course Expired.','vibe').'<a href="'.$pid.'" class="link alignright">'.__('Click to renew','vibe').'</a></p></div>';
      die();
    }
  //}
  
  if(isset($coursetaken) && $coursetaken){
      
      // Drip Feed Check    
      $drip_enable=get_post_meta($course_id,'vibe_course_drip',true);

      
      if(vibe_validate($drip_enable)){

          $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
          $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);

          $unitkey = array_search($unit_id,$units);
          if($unitkey == 0){
            $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);

            if(!isset($pre_unit_time) || $pre_unit_time ==''){
              update_post_meta($units[$unitkey],$user_id,current_time('timestamp'));
              if(is_numeric($units[1]))
                //Parmas : Next Unit, Next timestamp, course_id, userid
                do_action('wplms_start_unit',$units[$unitkey],$course_id,$user_id,$units[1],(current_time('timestamp')+$drip_duration*$drip_duration_parameter));
            }
          }else{

             $pre_unit_time=get_post_meta($units[($unitkey-1)],$user_id,true);

             if(isset($pre_unit_time) && $pre_unit_time){
                
                $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);

                $value = $pre_unit_time + $drip_duration*$drip_duration_parameter;
                $value = apply_filters('wplms_drip_value',$value,$units[($unitkey-1)],$course_id,$units[$unitkey]);

                //print_r(date('l jS \of F Y h:i:s A',$value).' > '.date('l jS \of F Y h:i:s A',current_time('timestamp')));

               if($value > current_time('timestamp')){
                      echo '<div class="message"><p>'.__('Session will be available in ','vibe').tofriendlytime($value-current_time('timestamp')).'</p></div>';
                      die();
                  }else{
                      $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
                      if(!isset($pre_unit_time) || $pre_unit_time ==''){
                        update_post_meta($units[$unitkey],$user_id,current_time('timestamp'));
                        //Parmas : Next Unit, Next timestamp, course_id, userid
                        do_action('wplms_start_unit',$units[$unitkey],$course_id,$user_id,$units[$unitkey+1],(current_time('timestamp')+$drip_duration*$drip_duration_parameter));
                      }
                  } 
              }else{
                  echo '<div class="message"><p>'.__('Session can not be accessed.','vibe').'</p></div>';
                  die();
              }    
            }
          }  

      // END Drip Feed Check  
      
      echo '<div id="unit" class="'.get_post_type($unit_id).'_title" data-unit="'.$unit_id.'">';
        do_action('wplms_unit_header',$unit_id,$course_id);

        $minutes=0;
        $mins = get_post_meta($unit_id,'vibe_duration',true);
        $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
        if($mins){
          if($mins > $unit_duration_parameter){
            $hours = floor($mins/$unit_duration_parameter);
            $minutes = $mins - $hours*$unit_duration_parameter;
          }else{
            $minutes = $mins;
          }
        
          do_action('wplms_course_unit_meta',$unit_id);
          if($mins < 9999){ 
            if($unit_duration_parameter == 1)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' seconds','vibe').'</span>';
            else if($unit_duration_parameter == 60)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' minutes','vibe').'</span>';
            else if($unit_duration_parameter == 3600)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' hours','vibe').'</span>';
          } 

        }
      echo '<br /><h1>'.get_the_title($unit_id).'</h1>';
          the_sub_title($unit_id);
      echo '<div class="clear"></div>';
      echo '</div>';
        the_intellipaat_unit($unit_id); 
      
              $unit_class='unit_button';
              $hide_unit=0;
              $nextunit_access = vibe_get_option('nextunit_access');
              

              $k=array_search($unit_id,$units);
              $done_flag=get_user_meta($user_id,$unit_id,true);

              $next=$k+1;
              $prev=$k-1;
              $max=count($units)-1;

              echo  '<div class="unit_prevnext"><div class="col-md-3">';
              if($prev >=0){

                if(get_post_type($units[$prev]) == 'quiz'){
                  echo '<a href="#" data-unit="'.$units[$prev].'" class="unit '.$unit_class.'">'.__('Previous Quiz','vibe').'</a>';
                }else    
                  echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button">'.__('Previous Session','vibe').'</a>';
              }
              echo '</div>';

              echo  '<div class="col-md-6">';
              if(get_post_type($units[($k)]) == 'quiz'){
                $quiz_status = get_user_meta($user_id,$units[($k)],true);
                if(is_numeric($quiz_status)){
                  if($quiz_status < time()){
                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                  }else{
                      $quiz_class = apply_filters('wplms_in_course_quiz','');
                      echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                  }
                }else{
                    $quiz_class = apply_filters('wplms_in_course_quiz','');
                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.'">'.__('Start Quiz','vibe').'</a>';
                }
              }else  
                  echo ((isset($done_flag) && $done_flag)?'': apply_filters('wplms_unit_mark_complete','<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Session Complete','vibe').'</a>',$unit_id,$course_id));

              echo '</div>';

              echo  '<div class="col-md-3">';

              if($next <= $max){

                if(isset($nextunit_access) && $nextunit_access){
                    $hide_unit=1;

                    if(isset($done_flag) && $done_flag){
                      $unit_class .=' ';
                      $hide_unit=0;
                    }else{
                      $unit_class .=' hide';
                      $hide_unit=1;
                    }
                }

                if(get_post_type($units[$next]) == 'quiz'){
                      echo '<a href="#" id="next_quiz" data-unit="'.$units[$next].'" class="unit '.$unit_class.'">'.__('Proceed to Quiz','vibe').'</a>';
                }else  
                  echo '<a href="#" id="next_unit" '.(($hide_unit)?'':'data-unit="'.$units[$next].'"').' class="unit '.$unit_class.'">'.__('Next Session','vibe').'</a>';
              }
              echo '</div></div>';
          
        }
        die();
}  
/*
*	Redeclaration of function the_unit()
*/
if(!function_exists('the_intellipaat_unit')){
  function the_intellipaat_unit($id=NULL){
    if(!isset($id))
      return;
    
    do_action('wplms_before_every_unit',$id);
    
    $post_type = get_post_type($id);
    $the_query = new WP_Query( 'post_type='.$post_type.'&p='.$id );
    $user_id = get_current_user_id();

    while ( $the_query->have_posts() ):$the_query->the_post();
    
    $unit_class = 'unit_class';
    $unit_class=apply_filters('wplms_unit_classes',$unit_class,$id);
    echo '<div class="main_unit_content '.$unit_class.'">';

    if($post_type == 'quiz'){ 
      $expiry = get_user_meta($user_id,$id,true);
      if(is_numeric($expiry) && $expiry < time()){
        $message = get_post_meta($id,'vibe_quiz_message',true);
        echo apply_filters('the_content',$message);
      }else{
        the_content();  
      }
    }else{
		the_unit_desc($id);
		the_content();  
    }
    
    wp_link_pages(array(
      'before'=>'<div class="unit-page-links page-links"><div class="page-link">',
      'link_before' => '<span>',
      'link_after'=>'</span>',
      'after'=> '</div></div>'));

    echo '</div>';
    endwhile;
    wp_reset_postdata();
    if(get_post_type($id) == 'unit')
    do_action('wplms_after_every_unit',$id);

    echo intellippat_course_get_unit_attachments($id);

    echo '<div class="unitforum">'.vibe_get_option('support_note').'</div>';
  }
}

if(!function_exists('intellippat_course_get_unit_attachments')){

  function intellippat_course_get_unit_attachments($id=NULL){
      if(!is_numeric($id)){
        global $post;
        $id=$post->ID;
      }

      $return='';
      $attachments =& get_children( 'post_type=attachment&output=ARRAY_N&order=ASC&post_parent='.$id);//&orderby=menu_order
       if($attachments && count($attachments)){
            $att= '';

            $count=0;
          foreach( $attachments as $attachmentsID => $attachmentsPost ){
          
          $type=get_post_mime_type($attachmentsID);

          if($type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif'){
              
              if($type == 'application/zip')
                $type='icon-compressed-zip-file';
              else if($type == 'video/mpeg' || $type== 'video/mp4' || $type== 'video/quicktime')
                $type='icon-movie-play-file-1';
              else if($type == 'text/csv' || $type== 'text/plain' || $type== 'text/xml')
                $type='icon-document-file-1';
              else if($type == 'audio/mp3' || $type== 'audio/ogg' || $type== 'audio/wmv')
                $type='icon-music-file-1';
              else if($type == 'application/pdf')
                $type='icon-text-document';
              else
                $type='icon-file';

              $count++;

              $att .='<li><i class="'.$type.'"></i>'.wp_get_attachment_link($attachmentsID).'</li>';
            }
          }

        if($count){
          $return ='<div class="unitattachments"><h4>'.__('Attachments','vibe').'<span><i class="icon-download-3"></i>'.$count.'</span></h4><ul id="attachments">';
          $return .= $att;
          $return .= '</ul></div>';
        }
      }
      return $return;
    }
}