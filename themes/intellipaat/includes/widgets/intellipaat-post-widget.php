<?php 


class Intellipaat_widget extends WP_Widget {
		
		function __construct() {
				parent::__construct(		
					'intelli_recent_post',	
					__('Footer Posts', 'intellipaat'), 
					array( 'description' => __( 'widget to show three blog posts', 'intellipaat' ), ) 
				);
		}
		
		
		public function widget( $args, $instance ) {
				$title = apply_filters( 'widget_title', $instance['title'] );
				
					if(empty($title)){
						$title= "<span class='hidden-title'></span> ";
					}
								
				// before and after widget arguments are defined by themes
				//echo $args['before_widget'];
				echo '<div class="col-md-9 col-sm-6"><div class="footerwidget">';
				
				if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];
				
				if ( is_singular('course') && $ids = get_field('intellipaat_custom_blog_interlinking') ) {
					$html = file_get_contents('https://intellipaat.com/blog/wp-admin/admin-ajax.php?action=intellipaat_custom_blog_actions&post_ids='.$ids.'&nonce='.md5('blog-intellipaat'));
					echo $html;
				}
				else{
					
				
				$list = $instance[ 'list' ];
				 if(empty($list)){						
						
					$query = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => 3 ,'offset' => 2, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );												
				 }else{
					 
					 $post_ids=explode(',',$list);			 
					
						
					$query = new WP_Query( apply_filters( 'widget_posts_args', array( 'post__in' =>$post_ids , 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
				  }
					if ($query->have_posts()) :
					
					?>
					<div class="footerwidget row">
					 <ul class="blog-thumbnail col-md-4">
                            
                            <?php $count= 1;?>
                            
                            <?php while ( $query->have_posts() ) : $query->the_post(); 
							
									$link = get_field('intellipaat_custom_blog_url');
							?>                   	
                                
                                <li>
                                        
                                       <a href="#post<?php echo $count;?>" post-id="<?php the_ID()?>" class="linkright <?php if($count==1 && $count<2) echo "active-link";?>" title="<?php the_title_attribute(); ?>" >
                                       <?php the_post_thumbnail( array(70,70) ); ?>
                                       <?php the_title();?>
                                      <?php if($count==1 && $count<2){?> <i class="aactive-rrow icon-arrow-1-left"></i><?php }?></a>
                                     
                                       
                                
                                </li>
                               
                             <?php $count++; ?>
                                
                            <?php endwhile; ?>
                            
                      </ul>
                      <?php $count= 1;?>
                            
                      <div class="col-md-8">
                      	 <?php while ( $query->have_posts() ) : $query->the_post(); 
							
									$link = get_field('intellipaat_custom_blog_url');
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
												'.(has_post_thumbnail(get_the_ID())?'
												<div class="featured">
													<a href="'.$link.'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
												</div>':'').'
												<div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
													<h3><a href="'.$link.'">'.get_the_title().'</a></h3>
													<p>'.get_the_excerpt().'</p>
													<a href="'.$link.'" class="link">'.__('Read More','vibe').'</a>
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
				echo $args['after_widget'];
				?>		
                		<style>

								.blog-thumbnail li a {
									color: #AAA5A5 !important;
								}
								.blog-thumbnail li a img {
									border: 3px solid #b9bbbc;
									float: left;
									margin-bottom: 5px;
									margin-right: 15px;
									max-width: 48px;
								}
								.blog-thumbnail > li:hover a img {
									border: 3px solid white;
								}
								.blog-thumbnail > li:hover a {
									color:#FFF;
								}
								
								
								.bottom-img .widget h4 {
									color: white !important;
								}
								.bottom-img .widget a {
									display: block;
									margin-bottom: 10px;
								}
								.bottom-img .widget a img {
									border: 1px solid white;
									border-radius: 4px;
									width: 68%;
								}
								.blog-thumbnail {
									position: relative;
								}
								.post-viewer {
									background-color: #261c1c;
									border: 1px solid #ababab;
									border-radius: 7px;
								}
								
								.in-footer {
									margin-bottom: 0;
									padding: 15px;
								}
								.in-footer .featured {
									width: 130px;
								}
								.in-footer .excerpt.thumb {
									margin-left: 235px;
								}
								.in-footer.blogpost .excerpt.thumb > h3 {
									border-bottom: 1px dashed #525252;
									padding-bottom: 5px;
								}
								.aactive-rrow {
									color: #ababab;
									display: block;
									float: right;
									font-size: 25px;
									font-weight: bold;
									height: 20px;
									width: 50px;
									position: absolute;
									right: -70px;
									bottom: 5px;
								}
								@media (max-width: 992px) {
									.in-footer .excerpt.thumb {
										margin-left: 0px !important;
									}
								}
								@media (max-width: 768px) {
									.in-footer .meta .date {
										position:relative;
										background:none;
									}
									.in-footer .featured {
										width: 80%;
									}
									.in-footer .excerpt.thumb {
										margin-left: 0;
										width:100%;
									}
									.post-viewer{
										margin-top:30px;	
									}
								}

						</style>
                     	 <script>
							jQuery(".linkright").click(function() { 
								  
									//jQuery(".post-viewer").hide();			
									
									var currentTab = jQuery(this).attr('href');
									jQuery('.post-viewer').hide();
									jQuery('.aactive-rrow').remove();
									jQuery('.linkright').removeClass('active-link');
									jQuery(currentTab).show();
									jQuery(this).addClass('active-link');
									jQuery('.active-link').append('<i class="aactive-rrow icon-arrow-1-left"></i>');
									return false;
								 
									//$("#maindiv").show(); 
								  
							});
						  </script>   
                    <?php
		}
				
		
		public function form( $instance ) {
				if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
				}
				else {
				$title = "text";
				}
				$list = $instance[ 'list' ];
				
				?>
				<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
                
                <p>
				<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( "Enter any Three Post ID's(comma seperated list):" ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" type="text" value="<?php echo esc_attr( $list ); ?>" />
				</p>
                
				<?php 
		}
			
		
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['list'] = strip_tags($new_instance['list']);
			return $instance;
	}
} // Class intelli_load_widget end here


function intelli_load_widget() {
	register_widget( 'Intellipaat_widget' );
}
add_action( 'widgets_init', 'intelli_load_widget' );

?>