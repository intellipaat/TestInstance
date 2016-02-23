<?php
/**
 * Plugin Name: Custom Blog Actions
 * Plugin URI: http://www.makarandmane.com
 * Description: A  Custom Blog Actions Plugin written for intellipaat to show blogs in footer by calling ID of postg form blog site.
 * Version: 1.0
 * Author: Makarand Mane 
 * Author URI: http://www.makarandmane.com
 * License: GPL2
 */
 
 /*  Copyright 2014  Makarand Mane  (email : mane.makarand@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
*	To change default wordpress urls to point to https
*/
//add_filter('set_url_scheme', 'custom_ssl_url_scheme',10,1);
function custom_ssl_url_scheme($url){
	return str_replace(array('http://'),array('https://'), $url );
}

function custom_blog_excerpt_length( $length ) {
	return 17;
}

/*
*	To accept request from outside of blog
*/
add_action("wp_ajax_intellipaat_custom_blog_actions", "intellipaat_custom_blog_actions_callback");
add_action("wp_ajax_nopriv_intellipaat_custom_blog_actions", "intellipaat_custom_blog_actions_callback");

function intellipaat_custom_blog_actions_callback() {
	
	add_filter( 'excerpt_length', 'custom_blog_excerpt_length', 999 );
	
   if (  $_REQUEST['nonce'] == md5('blog-intellipaat') ) {
				
				if(empty($_REQUEST['post_ids'])){						
						
					$query = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => 3 ,'offset' => 2, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );												
				}else{
				 
				 	$post_ids=explode(',',$_REQUEST['post_ids']);
					$query = new WP_Query( apply_filters( 'widget_posts_args', array( 'post__in' =>$post_ids , 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
				}
				
				if ($query->have_posts()) :
				
				?>
				<div class="footerwidget row">
				 <ul class="blog-thumbnail col-md-4">
						
						<?php $count= 1;?>
						
						<?php while ( $query->have_posts() ) : $query->the_post();
						
						
						$image = get_children( array(
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'numberposts' => 1,
							'order' => 'asc',
							'orderby' => 'ID',
							'post_mime_type' => 'image',
						) );
						$image_url = ( $image ) ? wp_get_attachment_image( current($image)->ID, array(70,70) ) : get_the_post_thumbnail(get_the_ID(), array(70,70)) ;
					?>                   	
							
							<li>
									
								   <a href="#post<?php echo $count;?>" post-id="<?php the_ID()?>" class="linkright <?php if($count==1 && $count<2) echo "active-link";?>" title="<?php the_title_attribute(); ?>" >
								   <?php  echo $image_url  ?>
								   <?php the_title();?>
								  <?php if($count==1 && $count<2){?> <i class="aactive-rrow icon-arrow-1-left"></i><?php }?></a>
								 
								   
							
							</li>
						   
						 <?php $count++; ?>
							
						<?php endwhile; ?>
						
				  </ul>
				  <?php $count= 1;?>
						
				  <div class="col-md-8">
					 <?php while ( $query->have_posts() ) : $query->the_post();  
					 
							 $image = get_children( array(
									'post_parent' => get_the_ID(),
									'post_type' => 'attachment',
									'numberposts' => 1,
									'order' => 'asc',
									'orderby' => 'ID',
									'post_mime_type' => 'image',
								) );
								$image_url = ( $image ) ? wp_get_attachment_image( current($image)->ID , 'full' ) : get_the_post_thumbnail(get_the_ID(), 'full') ;
						?>                   	
							
						   <div class="post-viewer" id="post<?php echo $count;?>" style=" <?php if($count==1 && $count<2){echo 'display:block'; } else {echo 'display:none'; }?> ">              	
						  
								<?php
									echo ' <div class="blogpost in-footer">
											<div class="meta">
											   <div class="date">
												<p class="day"><span>'.get_the_time('j').'</span></p>
												<p class="month">'.get_the_time('M').'</p>
											   </div>
											</div>
											'.($image_url ?'
											<div class="featured">
												<a href="'.get_permalink().'">'.$image_url.'</a>
											</div>':'').'
											<div class="excerpt '.($image_url ?'thumb':'').'">
												<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
												<p>'.get_the_excerpt().'</p>
												<a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
											</div>
										</div>';									
									?>
						  
						   </div>
						   
						 <?php $count++; ?>
							
						<?php endwhile; ?>
					</div>
				   </div>                  
				  
				  
				<?php 
			   
				wp_reset_postdata();
		
				endif;	
	   
	}
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();
   
}
?>