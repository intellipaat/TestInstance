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
				<div class="col-md-8 col-sm-12 col-xs-12">
					<?php if( have_posts() ) :
						while( have_posts() ) : the_post(); 
						$orig_post = $post->ID;
						$videoTitle = get_post_custom_values('video_title', $post->ID); 
						$videoLink = get_post_custom_values('video_link', $post->ID);
						$imageArray = get_field('video_preview_image');
						$videoPreview = $imageArray['url'];

						//$videoDescription = get_post_custom_values('video_description', $post_id);
						$videoDescription = get_field('video_description', $post->ID);
						$displayTitle = isset($videoTitle[0]) ? $videoTitle[0] : 'Video Title';
						$displayLink = isset($videoLink[0]) ? $videoLink[0] : 'No Link';
						$displayDescription = ($videoDescription == '') ? $videoDescription : 'Description Not Added';
						?>
						<div class="row videoH1">
							<div class="col-md-8 col-sm-12 col-xs-12">
								<h1 class="entry-title"><?php echo $displayTitle; ?></h1>
							</div>
							<div class="col-md-4 col-sm-12 col-xs-12">
								<?php 
								$vibe_extras= new vibe_extras();
								echo $sharing = '<div class="course_sharing">'.$vibe_extras->social_sharing().'</div>'; 
								?>
							</div>
						</div>
						
						<div class="video-container">
						<?php if($displayLink == 'No Link'){
						echo 'Video link not available';
						}else{
							$youtubeId = jose_extractUTubeVidId($displayLink);
							if($youtubeId){
							?>
							<div id='videoPreviewImageContainer' style='width:100%;height:400px;text-align:center;border:1px solid #837E7E;border-radius:4px;'>
							<img src="<?php echo $videoPreview;?>" style="cursor:pointer" />
							</div>
							<div style="display:none" id='VideoContainer'>
							<iframe width="100%" height="400" src="https://www.youtube.com/embed/<?php echo $youtubeId;?>?controls=1&showinfo=0&rel=0" allowfullscreen></iframe>
							</div>
						<?php
							}else{
							echo 'Invalid URL';
						}
						}
						?>
					<div id='videoPlayButton'>
						<span class="course-video">
						<span></span>
						<a href="#" class="course_video_ban course_video_play">&nbsp;</a>
						</span>
					</div>
					</div>
					<div class="entry-content video_content_back">
					<h2><?php the_title(); ?></h2>
					<?php the_content(); ?>

					</div>
					<div class='video_promo_container'>
					<!-- <div class='video_promo_title'>Promote Course</div> -->
					</div>
					<div class='video_promo_content'>
						<p><?php echo $videoDescription;?></p>
					</div>
					<!-- entry-content -->
				<?php endwhile; else : endif; ?>
					<div class="clear"></div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12" style='margin-top:-4px;'>
				<div class="sidebar_right" style='margin-bottom:20px;'>
				<?php
					jose_related_course($orig_post,3);
					//jose_related_ebook($orig_post,2);
					jose_related_tutorial($orig_post,4);
					jose_related_certification($orig_post,2);
					jose_related_blog($orig_post,3);
				?>
				</div>
				</div>
			</div>
		
		</div><!-- #container -->
	</div>
</section>	
<script>
jQuery(document).ready(function(){
	jQuery('#videoPreviewImageContainer img').click(function(){
		jQuery(this).parent().hide();
		jQuery('#videoPlayButton').hide();
		var videoLink = jQuery('#VideoContainer iframe').attr('src');
		var autoPlay = videoLink+'&autoplay=1';
		jQuery('#VideoContainer iframe').attr('src',autoPlay);
		jQuery('#VideoContainer').show();
	});
	jQuery('#videoPlayButton').click(function(){
		jQuery('#videoPreviewImageContainer').hide();
		jQuery(this).hide();
		var videoLink = jQuery('#VideoContainer iframe').attr('src');
		var autoPlay = videoLink+'&autoplay=1';
		jQuery('#VideoContainer iframe').attr('src',autoPlay);
		jQuery('#VideoContainer').show();
	});
	
	
});
</script>
<?php get_footer( 'buddypress' ); ?>
