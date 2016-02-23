<?php
/**
 * Template Name: Video Template
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
						$orig_post = $post_id;
						$videoTitle = get_post_custom_values('video_title', $post_id); 
						$videoLink = get_post_custom_values('video_link', $post_id);
						$videoDescription = get_post_custom_values('video_description', $post_id);
						$displayTitle = isset($videoTitle[0]) ? $videoTitle[0] : 'Video Title';
						$displayLink = isset($videoLink[0]) ? $videoLink[0] : 'No Link';
						$displayDescription = isset($videoDescription[0]) ? $videoDescription[0] : 'Description Not Added';
						?>
						<h1 class="entry-title"><?php echo $displayTitle; ?></h1>
						<div class="video-container">
						<?php if($displayLink == 'No Link'){
						echo 'Video link not available';
						}else{
							$youtubeId = jose_extractUTubeVidId($displayLink);
							if($youtubeId){
							echo '<iframe width="100%" height="480" src="https://www.youtube.com/embed/'.$youtubeId.'?controls=1&showinfo=0&rel=0" allowfullscreen></iframe>';
							}else{
							echo 'Invalid URL';
						}
						}
						?>
					</div>
					<div class="entry-content video_content_back">
					<h2><?php the_title(); ?></h2>
					<?php the_content(); ?>

					</div>
					<div class='video_promo_container'>
					<div class='video_promo_title'>Promote Course</div>
					</div>
					<div class='video_promo_content'><p><?php echo $displayDescription;?></p></div>
					<!-- entry-content -->
					<?php endwhile; else : endif; ?>
					<div class="clear"></div>
				</div>
				<div class="col-md-3 col-sm-12 col-xs-12">
				<div class="sidebar_right">
					<?php 
					jose_related_course($orig_post,1);
					jose_related_ebook($orig_post,2);
					jose_related_tutorial($orig_post,2);
					jose_related_blog($orig_post,3);
					?>
				</div>
				</div>
			</div>
		
		</div><!-- #container -->
	</div>
</section>	
<?php get_footer( 'buddypress' ); ?>
