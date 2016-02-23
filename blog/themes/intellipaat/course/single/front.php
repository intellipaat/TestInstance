<?php  //lots of chnages made for look & confirm 07/08/2015

/**
 * The template for displaying Course font
 *
 * Override this template by copying it to yourtheme/course/single/front.php
 *
 * @author 		Makarand Mane
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */
?>

<div class="about-course">
	<?php
	global $post;
	$id= get_the_ID();
	
	do_action('wplms_course_before_front_main');
	
	if(have_posts()):
	while(have_posts()):the_post();
	?>
	
	<div class="course_title">
		<?php 
		$newtitle=get_post_custom_values("add_h1_page_title", get_the_ID());
		if(count($newtitle)>0)
		{
		?>
		 <div class="title"><?php echo $newtitle[0]; ?> </div>  <?php
		}else{
		?> <h1><?php the_title(); ?> </h1>  <?php
		} 
		
		//vibe_breadcrumbs(); ?>
		
        <?php intellipaat_course_offer(get_the_ID()); ?>
        <?php 
			$short_desc = get_field('intellipaat_short_description', get_the_ID()); 
			if($short_desc) echo "<h6 class='short_description'>".$short_desc."</h6>";
		?>
		<?php /*?><h6><?php the_excerpt(); ?></h6><?php */?>
	</div>
	<?php /*?><div class="students_undertaking">
		<?php
		$students_undertaking=array();
		$students_undertaking = bp_course_get_students_undertaking();
		$students=get_post_meta(get_the_ID(),'vibe_students',true);
	
		echo '<strong>'.$students.__(' STUDENTS ENROLLED','vibe').'</strong>';
	
		echo '<ul>';
		$i=0;
		foreach($students_undertaking as $student){
			$i++;
			echo '<li>'.get_avatar($student).'</li>';
			if($i>5)
				break;
		}
		echo '</ul>';
		?>
	</div><?php */?>
	<?php
	do_action('wplms_before_course_description');
	?>
	<div class="course_description" itemprop="description">
		<div class="small_desc">
		<?php 
		
		$sub_heading_with_h1=get_post_custom_values("sub_heading_with_h1_tag", get_the_ID());
		if(count($sub_heading_with_h1)>0)
		{
		?>
		 <div class="small_desc_heading"><?php echo $sub_heading_with_h1[0]; ?> </div>  <?php
		} 
		?>
		<?php 
			$more_flag = 1;
			$content=get_the_content(); 
			$middle=strpos( $post->post_content, '<!--more-->' );
			if($middle){
				echo apply_filters('the_content',substr($content, 0, $middle));
			}else{
				$limit=apply_filters('wplms_course_excerpt_limit',1200);
				$middle = strrpos(substr($content, 0, $limit), " ");
	
				if(strlen($content) < $limit){
					$more_flag = 0;
				}
				$check_vc=strpos( $post->post_content, '[vc_row]' );
				if ( isset($check_vc) ) {
					$more_flag=0;
					echo apply_filters('the_content',$content);
				}else{
					echo apply_filters('the_content',substr($content, 0, $middle));
				}
			}
		?>
		<?php 
			if($more_flag)
				echo '<a href="#" id="more_desc" class="link" data-middle="'.$middle.'">'.__('READ MORE','vibe').'</a>';
		?>
		</div>
		<?php if($more_flag){ ?>
		<div class="full_desc">
		<?php 
			echo apply_filters('the_content',substr($content, $middle,-1));
		?>
		<?php 
			echo '<a href="#" id="less_desc" class="link">'.__('LESS','vibe').'</a>';
		?>
		</div>
		<?php
			}
		?>
	</div>
	<?php
	do_action('wplms_after_course_description');
	?>
	
	<?php /*?><div class="course_reviews">
	<?php
		 comments_template('/course-review.php',true);
	?>
	</div><?php */?>
	
	<?php
	endwhile;
	endif;
	?>
</div>