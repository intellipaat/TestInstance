<?php
/**
 * Template Name: Certificate Page Template
 *
 * This template is used to display youtube videos.
 * Please note that, the video links are fetched from custom fields.
 *
 * @Joseph Amalan
 */

	get_header( 'buddypress' );
?>
<section id="content">
	<div class="course-breadcrumb container">
	
		<?php vibe_breadcrumbs(); ?>
    
    </div>
	<div id="buddypress">
	    <div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-12 col-xs-12">
					<?php if( have_posts() ) :
						while( have_posts() ) : the_post(); 

						?>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h1 class="entry-title"><?php echo the_title(); ?></h1>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12" style='text-align:right;'>
								<?php 
								$vibe_extras= new vibe_extras();
								echo $sharing = '<div class="course_sharing">'.$vibe_extras->social_sharing().'</div>'; 
								?>
							</div>
						</div>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<!-- entry-content -->
				<?php endwhile; else : endif; ?>
					<div class="clear"></div>
				</div>
				<div class="col-md-3 col-sm-12 col-xs-12" style='margin-top:-4px;'>
				<div class="sidebar_right" style='margin-bottom:20px;'>
				  <?php  
					global $post; 
					if(!$post->post_parent){
						
						$parent_id = $post->ID;
					}
					else{
						
						$ancestors = get_ancestors( $post->ID, $post->post_type );
						$parent_id = end($ancestors);
					}
					jose_related_course($post->ID,3);
					get_sidebar('right'); 
				?>
				</div>
				</div>
			</div>
		
		</div><!-- #container -->
	</div>
</section>	
<?php get_footer( 'buddypress' ); ?>
