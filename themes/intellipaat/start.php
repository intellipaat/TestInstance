<?php
/**
 * Template Name: Start Course Page
 */

// COURSE STATUS : 
// 0 : NOT STARTED 
// 1: STARTED 
// 2 : SUBMITTED
// > 2 : EVALUATED

// VERSION 1.8.4 NEW COURSE STATUSES
// 1 : START COURSE
// 2 : CONTINUE COURSE
// 3 : FINISH COURSE : COURSE UNDER EVALUATION
// 4 : COURSE EVALUATED

do_action('wplms_before_start_course');

get_header('buddypress');

do_action('wplms_start_course',get_the_ID(),get_current_user_ID());

$user_id = get_current_user_id();  

if(isset($_POST['course_id'])){
    $course_id=$_POST['course_id'];
    $coursetaken=get_user_meta($user_id,$course_id,true);
}else if(isset($_COOKIE['course'])){
      $course_id=$_COOKIE['course'];
      $coursetaken=1;
}

if(!isset($course_id) || !is_numeric($course_id))
    wp_die(__('INCORRECT COURSE VALUE. CONTACT ADMIN','vibe'));

$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
$unit_id = wplms_get_course_unfinished_unit($course_id);

$unit_comments = vibe_get_option('unit_comments');
$class= '';
if(isset($unit_comments) && is_numeric($unit_comments)){
    $class .= 'enable_comments';
}

$class= apply_filters('wplms_unit_wrap',$class,$unit_id,$user_id);

if ( have_posts() ) : while ( have_posts() ) : the_post();

?>
<?php if(is_page_template('start.php')) :?>

<?php endif;?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/wp-content/themes/intellipaat/custom_css.css" media="screen" />

<style>
</style>

<section id="content" class=" mynew_block">
	<div class="container " style="">
		<div class="row " style=""> 
		<div class="col-md-12" style=""> 
			<div class="col-md-9" style=""> 
			

				<div class="unit_wrap <?php echo $class; ?>">
                <div id="unit_content" class="unit_content">
                
                <div id="unit" class="<?php echo get_post_type($unit_id); ?>_title" data-unit="<?php if(isset($unit_id)) echo $unit_id; ?>">
                	<?php
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

                	?>
                	<h1><?php 
                    if(isset($course_id)){
                    	echo get_the_title($unit_id);
                    }else{
                        the_title();
                    }
                     ?></h1>
                    <?php
					if(isset($course_id)){
                    	the_sub_title($unit_id);
                    }else{
                    	the_sub_title();	
                    }	
                    ?>	
                    </div>
					
                    <?php

                    if(isset($coursetaken) && $coursetaken && $unit_id !=''){
                    	if(isset($course_curriculum) && is_array($course_curriculum)){
							the_intellipaat_unit($unit_id);
                            if(isset($unit_comments) && is_numeric($unit_comments)){
                                echo "<script>jQuery(document).ready(function($){ $('.unit_content').trigger('load_comments'); });</script>";
                            }
                    	}else{
                    		echo '<h3>';
                    		_e('Course Curriculum Not Set.','vibe');
                    		echo '</h3>';
                    	}
                    }else{
                        the_content();
                        if(isset($course_id) && is_numeric($course_id)){
                            $course_instructions = get_post_meta($course_id,'vibe_course_instructions',true);
                            echo apply_filters('the_content',$course_instructions);
                        }
                    }
                    
                endwhile;
                endif;
                ?>
                <?php
                $units=array();
                if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
                  foreach($course_curriculum as $key=>$curriculum){
                    if(is_numeric($curriculum)){
                        $units[]=$curriculum;
                    }
                  }
                }else{
                    echo '<div class="error"><p>'.__('Course Curriculum Not Set','vibe').'</p></div>';
                }   

                  if($unit_id ==''){
                    echo  '<div class="unit_prevnext_1"><div class="col-md-3"></div><div class="col-md-6">
                          '.((isset($done_flag) && $done_flag)?'': '<a href="#" data-unit="'.$units[0].'" class="unit unit_button">'.__('Start Course','vibe').'</a>').
                        '</div></div>';
                  }else{

                    $k = array_search($unit_id,$units);
                  
                  if(empty($k)) $k = 0;

            	  $next=$k+1;
                  $prev=$k-1;
                  $max=count($units)-1;

                  $done_flag=get_user_meta($user_id,$unit_id,true);
                  

                  echo  '<div class="unit_prevnext"><div class="col-md-3">';
                  if($prev >=0){
                    if(get_post_type($units[$prev]) == 'quiz'){
                        echo '<a href="#" data-unit="'.$units[$prev].'" class="unit unit_button">'.__('Previous Quiz','vibe').'</a>';
                    }else    
                        echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button">'.__('Previous Session','vibe').'</a>';
                  }
                  echo '</div>';

                  echo  '<div class="col-md-6">';
                    if(!isset($done_flag) || !$done_flag){
                            if(get_post_type($units[($k)]) == 'quiz'){

                                $quiz_status = get_user_meta($user_id,$units[($k)],true);
                                $quiz_class = apply_filters('wplms_in_course_quiz','');
                                if(is_numeric($quiz_status)){
                                    if($quiz_status < time()){ 
                                        echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                                    }else{
                                        echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                                    }
                                }else{
                                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.'">'.__('Start Quiz','vibe').'</a>';
                                }
                            }else{
                                echo apply_filters('wplms_unit_mark_complete','<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Session Complete','vibe').'</a>',$unit_id,$course_id);
                            }
                    }else{
                        if(get_post_type($units[($k)]) == 'quiz'){
                            $quiz_status = get_user_meta($user_id,$units[($k)],true);
                            $quiz_class = apply_filters('wplms_in_course_quiz','');
                            $quiz_passing_flag = apply_filters('wplms_next_unit_access',true,$units[($k)]);
                            if(is_numeric($quiz_status)){
                                if($quiz_status < time()){ 
                                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                                }else{
                                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                                }
                            }else{
                                echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                            }
                          }
                          // If unit does not show anything
                    }
                    echo '</div>';

                  echo  '<div class="col-md-3">';

                  $nextflag=1;
                  if($next <= $max){
                    $nextunit_access = vibe_get_option('nextunit_access');
                    if(isset($nextunit_access) && $nextunit_access){
                        for($i=0;$i<$next;$i++){
                            $status = get_post_meta($units[$i],$user_id,true);
                            if(!empty($status) && (!isset($done_flag) || !$done_flag)){
                                $nextflag=0;
                                break;
                            }
                        }
                    }
                    $class = 'unit unit_button';

                    $unit_lock = vibe_get_option('nextunit_access');
                    if(isset($unit_lock) && $unit_lock && (!isset($done_flag) || !$done_flag)){
                        $class .=' hide';
                    }
                    if($nextflag){
                        if(get_post_type($units[$next]) == 'quiz'){
                            if($quiz_passing_flag)
                                echo '<a href="#" id="next_quiz" data-unit="'.$units[$next].'" class="'.$class.'">'.__('Next Quiz','vibe').'</a>';
                        }else{
                            if(get_post_type($units[$next]) == 'unit'){ //Display Next unit link because current unit is a quiz on Page reload
                               if($quiz_passing_flag)
                                echo '<a href="#" id="next_unit" data-unit="'.$units[$next].'" class="'.$class.'">'.__('Next Session','vibe').'</a>';
                            }
                        } 
                    }else{
                        echo '<a href="#" id="next_unit" class="unit unit_button hide">'.__('Next Session','vibe').'</a>';
                    }
                  }
                  echo '</div></div>';

                } // End the Bug fix on course begining
	            ?>
                </div>
                <?php
                	wp_nonce_field('security','hash');
                	echo '<input type="hidden" id="course_id" name="course" value="'.$course_id.'" />';
                ?>
                <div id="ajaxloader" class="disabled"></div>
                </div>
            </div>
            <div class="col-md-3 sidebar_lms">
			
				<div class="tabs effect-1">
  <!-- tab-title -->
  <input type="radio" id="tab-1" name="tab" checked="checked">
  <span href="#tab-item-1"> <img class="imgleft" src="<?php echo site_url(); ?>/wp-content/themes/intellipaat/images/lms_nicon3.png" title="<?php _e('My Course', 'vibe'); ?>"  /> </span>

  <input type="radio" id="tab-2" name="tab">
  <span href="#tab-item-2"><img class="imgleft" src="<?php echo site_url(); ?>/wp-content/themes/intellipaat/images/lms_nicon4_1.png" title="<?php _e('My Course', 'vibe'); ?>"  /></span>

<input type="radio" id="tab-3" name="tab">
  <span href="#tab-item-3"> <img class="imgleft" src="<?php echo site_url(); ?>/wp-content/themes/intellipaat/images/lms_nicon5.png" title="<?php _e('My Course', 'vibe'); ?>"  /></span>
  <!-- tab-content -->
  <div class="tab-content">
    <section id="tab-item-1">
        <div class="course_list" > <?php  echo the_course_timeline($course_id,$unit_id); ?>
		<?php if(isset($course_curriculum) && is_array($course_curriculum)){
            		?>
            	<div class="more_course">
            		<form action="<?php echo get_permalink($course_id); ?>" method="post">
            		<?php
            		$finishbit=get_post_meta($course_id,$user_id,true);
            		if(isset($finishbit) && $finishbit!=''){
            			if($finishbit>0 && $finishbit < 3){
                            //echo '<input type="submit" name="review_course" class="review_course unit_button full button" value="'. __('REVIEW COURSE ','vibe').'" />';
            			    echo '<input type="submit" name="submit_course" class="review_course unit_button full button" style="    background-color: #222;border-color: 0px;" value="'. __('FINISH COURSE ','vibe').'" />';
            			}
            		}
            		?>	
            		<?php wp_nonce_field($course_id,'review'); ?>
            		</form>
            	</div>
            	<?php
            		}?> </div>
		
    </section>

    <section id="tab-item-2">
     <div class="course_list" >
	 <div id="notes" class="note_block1">
		<div id="note-form">
			<form id="add-note-form" class="single-line-form" method="post">
				<textarea id="note" class="ud-form js-note note expand34-80 lmsnote" placeholder="Start typing to take your note" name="note" data-page-name="enable-default-text"></textarea>
				<input type="hidden" id="lmsunit_id" name="lmsunit_id" value="<?php echo $unit_id; ?>"/>
				<input type="hidden" id="lmscourse_id" name="lmscourse_id" value="<?php echo $course_id; ?>"/>
				
				<div class="bottom">
					<input type="button" class="lms_addanote hide" value="Add note">
				</div>
			</form>
		</div>
		<div class="note-locked none">
			<span class="muted">
				<i class="icon-lock"></i>
				<span class="error-message" translate="">
					<span class="">Notes are not available at this moment.</span>
				</span>
			</span>
		</div>
		
		<div id="notes-mask">
		</div>
		
		<div id="download-notes " class="none" >
		<a id="download-button" class="btn btn-default" translate="" href="/notes/download?lecture_id=2705770" target="_blank">
			<span class="">Download Your Notes</span>
			</a>
		</div>

	 </div>
				
				</div>
	 
    </section>
	<script>
	jQuery(document).ready(function() {
    jQuery(window).resize(function() {
        var bodyheight = jQuery(document).height();
		jQuery("#unit_content").height(bodyheight);
		
    }).resize();
});
	/*$(".lmsnote").keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
		var note = $("#note").val();
		var lmscourse_id = $("#lmscourse_id").val();
		var lmsunit_id = $("#lmsunit_id").val();
		
		$.ajax({ 
			 data: {action: 'contact_form_me', note:note, lmscourse_id:lmscourse_id, lmsunit_id:lmsunit_id},
			 type: 'post',
			 url: ajaxurl,
			 success: function(data) {
				 var note = $("#note").val("");
				   //should print out the name since you sent it along
				get_lms_comments();
			}
		});

		}
	});*/

		jQuery('.lms_addanote').click(function(){
			var note = jQuery("#note").val();
			var lmscourse_id = jQuery("#lmscourse_id").val();
			var lmsunit_id = jQuery("#lmsunit_id").val();
			
			jQuery.ajax({ 
				 data: {action: 'contact_form_me', note:note, lmscourse_id:lmscourse_id, lmsunit_id:lmsunit_id},
				 type: 'post',
				 url: ajaxurl,
				 success: function(data) {
					 var note = jQuery("#note").val("");
					   //should print out the name since you sent it along
					get_lms_comments();
				}
			});
		});

	function get_lms_comments()
	{
    var lmscourse_id = jQuery("#lmscourse_id").val();
    var lmsunit_id = jQuery("#lmsunit_id").val();
		jQuery.ajax({ 
			 data: {action: 'get_lms_comments', lmscourse_id:lmscourse_id, lmsunit_id:lmsunit_id},
			 type: 'post',
			 url: ajaxurl,
			 success: function(data) {
				
				 jQuery("#notes-mask").html(data);
				   //should print out the name since you sent it along

			}
		});
	}

function get_lms_ratting(value)
	{
		var lmscourse_id = jQuery("#lmscourse_id").val();
		var lmsunit_id = jQuery("#lmsunit_id").val();
		if (confirm('Confirm your rating?')) {
			jQuery.ajax({ 
			 data: {action: 'save_lms_ratting', lmscourse_id:lmscourse_id, lmsunit_id:lmsunit_id,rattingvalue:value},
			 type: 'post',
			 url: ajaxurl,
			 success: function(data) {
				
				 jQuery("#lms_star_rating").html(data);
				   //should print out the name since you sent it along

			}
		});
		}
	}

	
	</script>
   <!--  <section id="tab-item-3">
      <h1>Mark Note</h1>
    </section>-->
  </div>
</div>
 <!-- tab-content -->
			
			
            </div>
        </div> </div>
    </div>
</section>
<?php
get_footer();
?>