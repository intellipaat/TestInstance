<?php  ///Contains changes over parent theme. Confirm 07-08-2015
/**
 * The template for displaying Course Header
 *
 * Override this template by copying it to yourtheme/course/single/header.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */

global $post;
$id= get_the_ID();

$video_id= get_post_meta($id, 'intellipaat-videothumb',true);

do_action( 'bp_before_course_header' );

?>
<div  itemscope itemtype="http://data-vocabulary.org/Product">
    <div id="item-header-avatar" class="thumbnail-video">
        <?php //bp_course_avatar(); commented it to show video thumbnail instead of featured imafge ?>
        
        <?php 		
            if(isset($video_id) && !empty($video_id)){
				$thumb =wp_get_attachment_image_src( get_post_thumbnail_id( $id ), full );
				if($thumb)
					echo '<meta itemprop="image" content="'.$thumb[0].'">';
                echo do_shortcode('[videothumb class="col-md-13" id="'.$video_id.'"]');
            }
            else{
                //bp_course_avatar();  
                echo str_replace('<img ','<img itemprop="image" ',get_the_post_thumbnail($id,'full'));
            } 
        ?> 
    </div><!-- #item-header-avatar -->


    <div id="item-header-content">
        <span class="highlight" itemprop="category"><?php bp_course_type(); ?></span>
        <h3>
        	<a href="<?php bp_course_permalink(); ?>" title="<?php bp_course_name(); ?>" itemprop="url"><span itemprop="name"><?php bp_course_name(); ?></span></a>
        </h3>
        
        <?php do_action( 'bp_before_course_header_meta' ); ?>
    
        <div id="item-meta">
            <?php bp_course_meta(); ?>
                <?php do_action( 'bp_course_header_actions' ); ?>
    
            <?php do_action( 'bp_course_header_meta' ); ?>
    
        </div>
    </div><!-- #item-header-content -->

<?php /*?><div id="item-admins">

<h3><?php _e( 'Instructors', 'vibe' ); ?></h3>
	<?php
	bp_course_instructor();

	do_action( 'bp_after_course_menu_instructors' );
	?>
</div><?php */?><!-- #item-actions -->
</div>
<?php
do_action( 'bp_after_course_header' );
?>