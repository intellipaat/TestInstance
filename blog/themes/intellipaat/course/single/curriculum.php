<?php   ///Made changes over parent theme and check for updates 07/08/2015
/**
 * The template for displaying Course Curriculum
 *
 * Override this template by copying it to yourtheme/course/single/curriculum.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.2
 */


global $post;
$id= get_the_ID();

$class='';
$settings = get_option('lms_settings');
if(isset($settings['general']['curriculum_accordion']))
	$class="accordion";
?>

<?php /*?><div class="course_title">
	<h2><?php  _e('Course Curriculum','vibe'); ?></h2>
</div>
<?php */?>
<div class="course_curriculum <?php echo $class; ?>">
	<?php
    do_action('wplms_course_curriculum_section',$id);
   /* $course_curriculum = vibe_sanitize(get_post_meta($id,'vibe_course_curriculum',false));
    
    if(isset($course_curriculum)){
    
    
        foreach($course_curriculum as $lesson){
            if(is_numeric($lesson)){
                $icon = get_post_meta($lesson,'vibe_type',true);
    
                if(get_post_type($lesson) == 'quiz')
                    $icon='task';
    
                        $href=get_the_title($lesson);
                        $free='';
                        $free = get_post_meta($lesson,'vibe_free',true);
    
                        $curriculum_course_link = apply_filters('wplms_curriculum_course_link',0);
                        if(vibe_validate($free) || ($post->post_author == get_current_user_id()) || current_user_can('manage_options') || $curriculum_course_link){
                            $href=apply_filters('wplms_course_curriculum_free_access','<a href="'.get_permalink($lesson).'?id='.get_the_ID().'">'.get_the_title($lesson).(vibe_validate($free)?'<span>'.__('FREE','vibe').'</span>':'').'</a>',$lesson,$free);
                        }
    
                echo '<div class="course_lesson">
                        <i class="icon-'.$icon.'"></i><h6>'.apply_filters('wplms_curriculum_course_lesson',$href,$lesson).'</h6>';
                        $minutes=0;
                        $hours=0;
                        $min = get_post_meta($lesson,'vibe_duration',true);
                        $minutes = $min;
                        if($minutes){
                            if($minutes > 60){
                                $hours = intval($minutes/60);
                                $minutes = $minutes - $hours*60;
                            }
                        echo apply_filters('wplms_curriculum_time_filter','<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hrs','vibe'):'').' '.$minutes.' '.__('mins','vibe').'</span><b>'.((isset($hours) && $hours)?$hours:"00").':'.$minutes.'</b>',$min);
                        }	
    
                        echo '</div>';
						$desc = get_field('intellipaat_brief_unit_description', $lesson);
						if(!empty($desc))
                        	echo '<div class="unit_desc">'.$desc.'</div>';
            }else{
                echo '<h5 class="course_section">'.$lesson.'</h5>';
            }
        }
    }
    else{*/
        $course_curriculum = get_field('intellipaat_curriculum', $id); 
        if(!empty($course_curriculum)){
            echo $course_curriculum;
        }
    //}
	?>
</div>