<?php

/**
*	Log user access given time.


function intellipaat_log_access_info($course_id,$user_id){
	
	global $wpdb;
	$now = date('Y-m-d H:i:s');
	$wpdb->insert( 
		$wpdb->prefix.'intellipaat_course_subscribed_log', 
		array( 
			'course_id'	=> $course_id,
			'user_id' 	=> $user_id, 
			'datetime'	=> $now ,
		)
	);
}*/
//add_action('wplms_course_subscribed', 'intellipaat_log_access_info', 2, 2);
//add_action('wplms_course_product_puchased', 'intellipaat_log_access_info', 1, 2);

/**
*	--
	-- Table structure for table `ip_intellipaat_course_subscribed_log`
	--
	
	CREATE TABLE IF NOT EXISTS `ip_intellipaat_course_subscribed_log` (
	  `id` int(11) NOT NULL,
	  `course_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `datetime` datetime NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;
	
	--
	-- Indexes for dumped tables
	--
	
	--
	-- Indexes for table `ip_intellipaat_course_subscribed_log`
	--
	ALTER TABLE `ip_intellipaat_course_subscribed_log`
	  ADD PRIMARY KEY (`id`);
*/
// Genarte and send report to admin
function intellipaat_daily_course_access_report_callback(  ) {
	
	global $wpdb;
	$now = date('Y-m-d H:i:s');
	$time = date('Y-m-d H:i:s', strtotime($now) - 60 * 60 * 24);
	$attachments = array();
	$filepath = WP_CONTENT_DIR  . '/uploads/'.'subscribe_course_report_last_24_hour.csv';
	$course_report_recipient = vibe_get_option('course_report_recipient');
	if(empty($course_report_recipient))
		$course_report_recipient = get_option('admin_email');
	
	$activity_logs = $wpdb->get_results( $wpdb->prepare( 
		"
		SELECT      activity.id, activity.date_recorded as date, 
					activity.item_id as course_id, course.post_title as title, activity.primary_link as course_link, 
					activity.secondary_item_id as student_id, student.display_name as name, student.user_login as username, student.user_email as email,
					activity.user_id as loggedin_user_id, loggeduser.user_login as loggedin_user_name
		FROM        {$wpdb->prefix}bp_activity activity
		INNER JOIN  $wpdb->posts course
					ON activity.item_id = course.ID
		INNER JOIN  $wpdb->users student
					ON activity.secondary_item_id = student.ID
		INNER JOIN  $wpdb->users loggeduser
					ON activity.user_id = loggeduser.ID
		WHERE       activity.type = %s AND activity.date_recorded >= %s
		ORDER BY   	activity.id ASC
		",
		'subscribe_course', $time
	) );
	
	$fd = fopen($filepath, 'w');
    if($fd === FALSE) {
        die('Failed to open temporary file');
    }

	if ( ! empty( $activity_logs ) && ! is_wp_error( $activity_logs ) ){
		fputcsv($fd, array('ID' , 'Date/Time', 'Course ID', 'Course Title', 'Course URL', 'Student ID', 'Student Name', 'Student username', 'Student email', 'Logged user/admin ID', 'Logged user/admin username' ));
		foreach($activity_logs as $log) {
			fputcsv($fd, array($log->id, $log->date, $log->course_id, $log->title, $log->course_link, $log->student_id, str_replace(",", " ", $log->name) , $log->username, $log->email, $log->loggedin_user_id, $log->loggedin_user_name ));
		}
	}
	rewind($fd);
    fclose($fd);	
	
	$attachments[] = $filepath;
	
			
	$message = '<!DOCTYPE HTML>
					<html>
					<head><title>Student course subscrition report created</title></head>
						<style type="text/css">
						</style>
					<body>
						<h3>Student course subscription report created DateTime-'.$now.'</h3>
						<p>Total found records : '.$wpdb->num_rows.'</p>
						<p>Please check attachment for report.</p>
						<p></p>
						<p>Don\'t reply to email.</p>
					</body>
					</html>' ;
	
	$headers = "From: info@intellipaat.us \r\n";
	$headers .= "Reply-To: info@intellipaat.us \r\n";	
	$headers .= "MIME-Version: 1.0 \r\n";		
	$headers .= "Content-type: text/html; charset=utf-8 \r\n";		
	$headers .= "Content-Transfer-Encoding: quoted-printable \r\n";
				
	$mail = wp_mail( $course_report_recipient , "Student course subscription report. DateTime-".$now , $message, $headers, $attachments);
}
add_action('intellipaat_daily_course_access_report', 'intellipaat_daily_course_access_report_callback', 10  );

// Schedule daily course acces given report
function intellipaat_course_access_report_cron_job() {
	//echo date('Y-m-d H:i:s'); 
//	echo date('Y-m-d H:i:s', time()+(60*60*5)+1800 );
//	die;
	
	$next_day = strtotime(date('Y-m-d 03:30:00', strtotime('+1 day')));
	
	//wp_clear_scheduled_hook('intellipaat_daily_course_access_report');
	if ( ! wp_next_scheduled( 'intellipaat_daily_course_access_report' ) ) {
		wp_schedule_event( $next_day, 'daily', 'intellipaat_daily_course_access_report' );
	}
}
add_action( 'wp', 'intellipaat_course_access_report_cron_job' );

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
              $html_durationblock.= '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' Seconds','vibe').'</span>';
            else if($unit_duration_parameter == 60)
              $html_durationblock.= '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' Minutes','vibe').'</span>';
            else if($unit_duration_parameter == 3600)
              $html_durationblock.= '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' Hours','vibe').'</span>';
          }  

        }
      //echo '<br /><h1>'.get_the_title($unit_id).'</h1>';
	   $html_block ='<div class="sidebar_lms_right_tray" ><div class="tray_tab1"><a href="'.get_permalink($course_id).'" title="Back to Course list"><span class="courseimage"></span></a><span class="spanhead">'.get_the_title($unit_id).'</span>'.$html_durationblock.'</div><div class="tray_tab3">Expand/Collpase</div><div id="slider-arrow" class="icon-collpase tray_tab4"></div>';
		echo  $html_block.= "</div>";
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
                  echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button"><img src="'.site_url().'/wp-content/themes/intellipaat/images/pervioussession.png" /></a>';
              }
              echo '</div>';

              echo  '<div class="col-md-6">';
              if(get_post_type($units[($k)]) == 'quiz'){
                $quiz_status = get_user_meta($user_id,$units[($k)],true);
                if(is_numeric($quiz_status)){
                  if($quiz_status < time()){
                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup btn_qz">'.__('Check Results','vibe').'</a>';
                  }else{
                      $quiz_class = apply_filters('wplms_in_course_quiz','');
                      echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button btn_qz '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                  }
                }else{
                    $quiz_class = apply_filters('wplms_in_course_quiz','');
                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button btn_qz '.$quiz_class.'">'.__('Start Quiz','vibe').'</a>';
                }
              }else  {
				  
				   $desc_video = get_field('intellipaat_brief_unit_description', $unit_id);

if(strlen($desc_video)>1) 
{

				  echo '<span style="float:left;text-transform:none;">
<button type="button" class="video_dsc" data-toggle="modal" data-target="#myModal">Topics Covered</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Topics Covered</h4>
      </div>
	    <div class="modal-body">
        '.$desc_video.'
      </div>
	 
    </div>

  </div>
</div></span>';
}

				   $reviews=0;
			 global $wpdb;
			$table_name = $wpdb->prefix . 'comments';
			$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE comment_post_ID='".$unit_id."' and comment_type='lms_rating' and user_id='".$user_id."'", OBJECT );
			 if(isset($results) && is_array($results) ){
				$reviews=$results[0]->comment_content;
			 }
	 
			 $ratmeta = '<div class="star-rating" id="lms_star_rating">  ';
			for($i=1;$i<=5;$i++){
				if($reviews >= 1){
					$ratmeta .='<span class="fill" onClick="get_lms_ratting('.$i.');"></span>';
				}else{
					$ratmeta .='<span onClick="get_lms_ratting('.$i.');"></span>';
				}
				$reviews--;
			}
			$ratmeta .='</div>';
			
			
			//	echo  '<div class="rating_label "><div class="star-label"> Rating </div>'.$ratmeta.'</div>';
			
				$attachments =& get_children( 'post_type=attachment&output=ARRAY_N&order=ASC&post_parent='.$unit_id);//&orderby=menu_order
			   if($attachments && count($attachments)){
					echo "<span style='float:left;'></span>";
			   }else{
					echo "<span style='float:left;'>".$ratmeta."</span>";
			   }
				
					
					
				  echo "<span style='float:left;'>";
					
                  echo ((isset($done_flag) && $done_flag)?'<div class="icon-mark-complete_done"></div>': apply_filters('wplms_unit_mark_complete','<a href="#" title="Mark as complete" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button"><div class="icon-mark-complete"></div></a>',$unit_id,$course_id));
				  
				  
				  echo "</span>";
			  }
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
                  echo '<a href="#" id="next_unit" '.(($hide_unit)?'':'data-unit="'.$units[$next].'"').' class="unit '.$unit_class.'"><img src="'.site_url().'/wp-content/themes/intellipaat/images/nextsession.png" /></a>';
              }
              echo '</div></div>';
			  //.png
          
        }
		
		 echo "<script> 

			jQuery('#lmsunit_id').val(".$unit_id.");
			jQuery('.lms_addanote').removeClass('hide');
			jQuery('#notes-mask').html('');
			get_lms_comments();
			  
			jQuery('#slider-arrow').click(function(){
				if(jQuery(this).hasClass('icon-collpase')){ 
	    jQuery( '.sidebar_lms' ).animate({left: '+=400'
		  }, 500, function() {
            // Animation complete.
          });
		  jQuery('.col-md-9').animate({
    width: '100%',
    opacity: 1
  }, 700 );
		 // jQuery('.col-md-9').css({'width':'100%'});
		  jQuery( '#slider-arrow' ).removeClass('icon-collpase').addClass('icon-expand');
        }else {   	
	    jQuery( '.sidebar_lms' ).animate({
          left: '-=400'
		  }, 700, function() {
            // Animation complete.
          });
		  jQuery('.col-md-9').animate({
    width: '75%',
    opacity: 1
  }, 500 );
		  //jQuery('.col-md-9').css({'width':'75%'});
		  jQuery( '#slider-arrow' ).removeClass('icon-expand').addClass('icon-collpase');
		   }
  });
</script>";
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
		//the_unit_desc($id);
		//the_content();  
		 $content = get_the_content();
		if (preg_match("/vimeo/i", $content)) {
			$end = trim(end(explode('/', $content)));
			$end=str_replace("&nbsp;","",$end);
			$end=str_replace("\n","",$end);
			echo '<div class="wrapper"><p class="h_iframe"><iframe src="https://player.vimeo.com/video/'.$end.'" height="510" width="1330"  frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></p></div>';
			 }else{
				 the_content();  
			 } 
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

  //  echo '<div class="unitforum">'.vibe_get_option('support_note').'</div>';
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
          $return ='<div class="unitattachments" style="height:510px"><h4>'.__('Attachments','vibe').'<span><i class="icon-download-3"></i>'.$count.'</span></h4><ul id="attachments">';
          $return .= $att;
          $return .= '</ul></div>';
        }
      }
      return $return;
    }
}