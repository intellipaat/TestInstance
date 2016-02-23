<?php
global $post; 
if(!$post->post_parent){
	
	$parent_id = $post->ID;
}
else{
	
	$ancestors = get_ancestors( $post->ID, $post->post_type );
	$parent_id = end($ancestors);
}

$tuts = get_posts("title_li=&post_parent=".$parent_id."&post_type=".$post->post_type."&echo=0&depth=1&orderby=menu_order&order=asc&posts_per_page=-1");

$pages = array();
foreach ($tuts as $page) {
   $pages[] += $page->ID;
}

$current = array_search(get_the_ID(), $pages);
if($current >=0 && is_int($current)){
	$nextID = $pages[$current+1]; 
	if($current)
		$prevID = $pages[$current-1];
	else
		$prevID = $parent_id;
}else{	
	$nextID = $pages[0];
	$prevID = NULL; 
}
?>


<?php get_header( 'buddypress' ); ?>

<!-- mfunc intellipaat_set_post_views($post->ID); --><!-- /mfunc -->
<section id="title" class="clear clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <?php vibe_breadcrumbs(); ?>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php get_template_part('templates/search','form'); ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
        	<div class="col-md-3 col-sm-3">
                <div class="sidebar">
                     <?php  
					 	global $post; 
						if(!$post->post_parent){
							
							$parent_id = $post->ID;
						}
						else{
							
							$ancestors = get_ancestors( $post->ID, $post->post_type );
							$parent_id = end($ancestors);
						}
					
					
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
							//the_post_thumbnail();
						} else{
							//echo get_the_post_thumbnail($parent_id);
						}
					 
					 if ( !wp_is_mobile() ) { get_sidebar('left'); } ?>
                </div>
            </div>
    		<div class="col-md-6 col-sm-6">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>
					<?php $tutorial_pdf_file = get_field( 'intellipaat_tutorial_pdf_file', $post->ID); ?>
                
                    <div <?php post_class('content'); ?>>
                           
                        <div class="pagetitle">
<?php 
$newtitle=get_post_custom_values("add_h1_page_title", get_the_ID());

if(isset($newtitle[0]) && $newtitle[0] !='')
{
?>
<div class="title"><?php echo $newtitle[0]; ?> </div>  <?php
}else{
?> <h1><?php the_title(); ?> </h1>  
<?php
} 

$sub_heading_with_h1=get_post_custom_values("sub_heading_with_h1_tag", get_the_ID());
if(isset($sub_heading_with_h1[0]) && $sub_heading_with_h1[0] !='')
{
?>
<div class="small_desc_heading"><?php echo $sub_heading_with_h1[0]; ?> </div>  <?php
} 

								if( $short_description )
									echo '<p>'. $short_description.'</p>';
							?>
                        </div>
                                      
                        <div class=" post-navigation page-navigation clear clearfix" style="margin:8px 0px 8px 0px"> 
							<?php if (!empty($prevID)) { ?> 
                                <div class="pull-left text-left">
                                        <a href="<?php echo get_permalink($prevID); ?>"  title="<?php echo get_the_title($prevID); ?>">&laquo;  Previous</a>
                                </div>
                            <?php } ?>
							<?php if (!empty($nextID)) { ?>
                                <div class="pull-right text-right">
                                    <a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">Next &raquo;</a>
                                </div>
                            <?php } ?>
                        </div>
                        
                          <?php 											
                                $video_id= get_post_meta($post->ID, 'intellipaat-videothumb',true);
                                
                                if(isset($video_id) && !empty($video_id)){
                                    echo '<div class="featured-video">'.do_shortcode('[videothumb class="col-md-13" id="'.$video_id.'" alt="'.get_the_title().' Video"]').'</div>';
                                }
                            ?> 
                        
                        <?php   the_content();    ?>    
                                 
                        <div class="row post-navigation page-navigation clear clearfix">  
                            <div class="pull-left text-left col-md-3">
                                <?php if (!empty($prevID)) { ?>
                                    <a href="<?php echo get_permalink($prevID); ?>"  title="<?php echo get_the_title($prevID); ?>">&laquo;  Previous</a>
                                <?php } ?>
                            </div>
                            <div class="text-center col-md-6"><?php if(!empty($tutorial_pdf_file)) echo '<a class="pdf-link" href="'.$tutorial_pdf_file.'">Download Tutorial PDF <i class="icon-download-3"> </i></a>';  ?></div>
                            <div class="pull-right text-right col-md-3">
                                <?php if (!empty($nextID)) { ?>
                                    <a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">Next &raquo;</a>
                                <?php } ?>
                            </div>
                        </div>
						<?php comments_template();    ?>
                    </div>   
                    
                <?php
                
                endwhile;
                endif;
                ?>
                
            </div>
            <div class="col-md-3 col-sm-3">
            	<div class="sidebar">
                    <?php get_sidebar('right'); ?>
                </div>
                <?php  if ( wp_is_mobile() ) { get_sidebar('left'); } ?>
            </div>  
        </div>
    </div>
</section>


<?php
get_footer( 'buddypress' );
?>
