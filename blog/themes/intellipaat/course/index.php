<?php //changes done
/**
 * The template for displaying course directory.
 *
 * Override this template by copying it to yourtheme/course/index.php
 *
 * @author 		MakarandMane
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */

get_header( 'buddypress' ); 

global $bp;

if(bp_is_course_component()){
	if(bp_is_single_item()){
		bp_core_load_template('course/single/home');
	}
}

$content = get_the_content();

if($content != ''){
?>
<section id="coursestitle" style="padding-top: 30px;">
    <div id="all_courses_header" class="container">
   
    	<div class="row">
        	<div class="col-md-9">
                <?php vibe_breadcrumbs(); ?>  
            	<?php the_content()?>
            </div>
            
            <div class="col-md-3 col-sm-8 col-sm-offset-2 col-md-offset-0">
                <div id="contact-form" class="widget pricing clear" style="margin-top:0;">
                    <h4 class="contact-title collapsed">Drop Us A Query</h4>
                    <?php  echo do_shortcode('[contact-form-7 id="2101" title="Drop a Query Here"]');?>
                </div>
                <?php
                    $sidebar = apply_filters('wplms_sidebar','buddypress',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>   
<?php } ?>

<section id="content">
	<div id="buddypress">
    <div class="container">

	<?php do_action( 'bp_before_directory_course_page' ); ?>

		<div class="padder">

		<?php do_action( 'bp_before_directory_course' ); ?>
		<div class="row">
			<div class="content-columns bg-white col-md-12">
            	<div class="row course_filterable">
            		<?php  	include('course-loop.php' );  ?>
                </div>
			</div>	
		</div>	
		<?php //do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->
</div>
</section>

<?php get_footer( 'buddypress' ); ?>
