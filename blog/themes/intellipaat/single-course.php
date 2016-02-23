<?php 
	get_header( 'buddypress' );
?>
<section id="content">
	<div class="course-breadcrumb container">
	
		<?php vibe_breadcrumbs(); ?>
    
    </div>
	<div id="buddypress">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-3 col-sm-3">
					<?php if ( bp_course_has_items() ) : while ( bp_course_has_items() ) : bp_course_the_item(); ?>

					<?php do_action( 'bp_before_course_home_content' ); ?>

					<div id="item-header" role="complementary">

						<?php 
locate_template( array( 'course/single/course-header.php' ), true ); 
?>

					</div><!-- #item-header -->
			
				<div id="item-nav" class="hidden-xs">
					<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                    	<?php 
							$content = get_the_content();
							$curriculum = get_field( "intellipaat_curriculum" );
							$course_and_infographics = get_field( "intellipaat_about_course_and_infographics" );
							$title_course_and_infographics = get_field( "intellipaat_title_for_what_is_this_course_tab" );
							if(!$title_course_and_infographics)
								$title_course_and_infographics = "What is ".get_the_title();
							$is_faq = get_field( "course_faq" );
							$data_index = 0;
						?>
						<ul>
                        	<?php if(empty($_GET['action'])){ ?>
								<?php if(isset($content) && !empty($content)){ $about_index=$data_index;?><li class="about-us selected" ><a href="#about-us" data-index="<?php echo $data_index; $data_index++; ?>">About Course</a></li><?php }?>
                                <?php if(isset($curriculum) && !empty($curriculum)){?><li class="curriculum"><a href="#curriculum" data-index="<?php echo $data_index; $data_index++; ?>" >Course Content</a></li><?php }?>
                                <?php if (isset($content) && !empty($content)) { ?><li class="sample-video" ><a href="#about-us" data-index="<?php echo $about_index; ?>">Watch sample video</a></li><?php } ?> 
                                <?php if(isset($is_faq) && !empty($is_faq)){?><li class="faq"><a href="#faq" data-index="<?php echo $data_index; $data_index++; ?>" >FAQ</a></li><?php }?>
                                <?php if(isset($course_and_infographics) && !empty($course_and_infographics)){?><li class="course_and_infographics"><a href="#course_and_infographics" data-index="<?php echo $data_index; $data_index++; ?>" ><?php echo $title_course_and_infographics ?></a></li><?php }?>
                                <li class="self-instructor"><a href="#self-instructor" class="scrollMe" >Self-Paced Vs Instructor LED Online</a></li>
                            	<li class="reviews"><a href="#reviews" class="scrollMe" >Reviews</a></li>
                           
							<?php } else { ?>
                            	<li id="home" class="<?php echo (!isset($_GET['action'])?'selected':''); ?>"><a href="<?php bp_course_permalink(); ?>"><?php  _e( 'Course Home', 'vibe' ); ?></a></li>
							<?php } ?>
                            
							<?php //bp_get_options_nav(); ?>
                            
							<?php

							/*if(function_exists('bp_course_nav_menu'))
								bp_course_nav_menu();
							else{*/
							?>	
							
							<?php
							//}
							/*$vgroup=get_post_meta(get_the_ID(),'vibe_group',true);
							if(isset($vgroup) && $vgroup){
								$group=groups_get_group(array('group_id'=>$vgroup));
							?>
							<li id="group"><a href="<?php echo bp_get_group_permalink($group); ?>"><?php  _e( 'Group', 'vibe' ); ?></a></li>
							<?php
							}	*/
							$forum=get_post_meta(get_the_ID(),'vibe_forum',true);
							if(isset($forum) && $forum){
							?>
								<li id="forum"><a href="<?php echo get_permalink($forum); ?>"><?php  _e( 'Forum', 'vibe' ); ?></a></li>
							<?php 
							}				
							?>
                            
                            <li class="certification"><a href="#certification" class="scrollMe" >Certification</a></li>   
                            
                            <?php 
								$blog_link = "";
								$blog_link = get_field('intellipaat_blog_category_url');
								
								if(!$blog_link){
									$terms = wp_get_post_terms( get_the_ID(), 'course-cat',  array('fields' => 'all') ); 
									foreach($terms as $term){
										$blog_link = get_field('intellipaat_blog_category_url', $term, 0);
										if($blog_link)
											break;
									}
								}
								
								if(!$blog_link)
									$blog_link = "https://intellipaat.com/blog/";
							?>
                                                     
                            <li class="blog"><a href="<?php echo $blog_link; ?>">Blog</a></li>
                            
                            <?php if(is_super_admin() || is_instructor()) { ?>
								<li id="members" class="<?php echo (($_GET['action']=='members')?'selected':''); ?>"><a href="<?php bp_course_permalink(); ?>?action=members"><?php  _e( 'Members', 'vibe' ); ?></a></li>
								<li id="admin" class="<?php echo ((isset($_GET['action']) && $_GET['action']=='admin')?'selected':''); ?>"><a href="<?php bp_course_permalink(); ?>?action=admin"><?php  _e( 'Admin', 'vibe' ); ?></a></li>
                            <?php } ?>
                            
							<?php do_action( 'bp_course_options_nav' ); ?>
						</ul>
					</div>
				</div><!-- #item-nav -->
                
                <?php 
				
				$related_courses_ids = get_field('intellipaat_related_courses');
                
                if($related_courses_ids){ ?>
                
                    <div id="related-courses" class="widget pricing">
                    
                        <p class="h3 title">Related Courses </p>
                        
                        <ul class="related-courses">
                        
							<?php 
                                
                                foreach( $related_courses_ids as $related_courses_id ){
                                    $alt_title = get_field( "intellipaat_alternate_course_title", $related_courses_id );
									$post_title =  $alt_title ?  $alt_title : $related_courses_id->post_title;
                                    echo '<li class="icon-check-5"><a href="'.get_permalink($related_courses_id).'">'.$post_title.'</a></li>';
                                
                                }
                            
                            ?>
                        </ul>
                    
                    </div>
                    
                 <?php } ?>
                
                <?php 
				
				$related_resources_ids = get_field('intellipaat_related_resources');
                
                if($related_resources_ids){ ?>
                
                    <div id="related-courses" class="widget pricing">
                    
                        <p class="h3 title">Related Resources </p>
                        
                        <ul class="related-courses">
                        
							<?php 
                                
                                foreach( $related_resources_ids as $related_resources_id ){
                                    $alt_title = get_field( "intellipaat_secondary_title", $related_resources_id );
									$post_title =  $alt_title ?  $alt_title : $related_resources_id->post_title;
                                    echo '<li class="icon-check-5"><a href="'.get_permalink($related_resources_id).'">'.$post_title.'</a></li>';
                                
                                }
                            
                            ?>
                        </ul>
                    
                    </div>
                    
                 <?php } ?>
                
			</div>
            
            <div class="visible-xs col-xs-12">
            
            	<div class="accordion" id="course-mobile-details">
                
				<?php if(isset($content) && !empty($content)){ ?>
                    <div class="accordion-group panel">
                        <div class="accordion-heading mobile-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#course-mobile-details" href="#mobile_about_us">About Course</a>
                        </div>
                        <div id="mobile_about_us" class="accordion-body collapse">
                            <div class="accordion-inner"> </div>
                        </div>
                    </div><!-- about course -->
                <?php } ?>
                
                <?php if(isset($curriculum) && !empty($curriculum)){ ?>
                    <div class="accordion-group panel">
                        <div class="accordion-heading mobile-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#course-mobile-details" href="#mobile_course_content">Course Content</a>
                        </div>
                        <div id="mobile_course_content" class="accordion-body collapse">
                            <div class="accordion-inner"></div>
                        </div>
                    </div><!-- course content -->
                 <?php } ?>
                
                
                <div class="accordion-group panel">
                        <div class="accordion-heading mobile-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#course-mobile-details" href="#mobile_keyfeature">Key Features </a>
                        </div>
                        <div id="mobile_keyfeature" class="accordion-body collapse">
                            <div class="accordion-inner"> </div>
                        </div>
                    </div><!-- key features-->                            

                
                <div class="accordion-group panel">
                        <div class="accordion-heading mobile-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#course-mobile-details" href="#mobile_selfpacevsinstrcutor">Self-Paced Vs Instructor LED Online</a>
                        </div>
                        <div id="mobile_selfpacevsinstrcutor" class="accordion-body collapse">
                            <div class="accordion-inner"></div>
                        </div>
                    </div>     <!-- self vs instructor --> 
                                                                    
             	</div>
                
            </div>
            
			<div class="col-md-6 col-sm-6 hidden-xs">	
			<?php do_action( 'template_notices' ); ?>
            
            
            
			<div id="item-body">           
                
              <?php /*?>  <h1><?php the_title(); ?></h1><?php */?>
                
            	<?php 
					
					/*
					ob_start();
					
					echo '[tabs style="" theme=]';
					
					if(isset($content) && !empty($content)){
						echo '[tab title="About Course" icon=""]';
								locate_template( array( 'course/single/front.php' ), true );
						echo '[/tab]';
					}
					
					if(isset($curriculum) && !empty($curriculum)){
						echo '[tab title="Course Content" icon=""]';
								locate_template( array( 'course/single/curriculum.php'  ), true );
								
						echo '[/tab]';
					}
					
					if(isset($is_faq) && !empty($is_faq)){
						
						echo '[tab title="FAQ" icon=""]';
							echo "<div class='course_title'>";
								echo "<h2>Frequently Asked Questions</h2>";
								echo "</div>";
								echo "<div style='display:none;'>".do_shortcode('[agroup] [accordion title="&nbsp;&nbsp;"]&nbsp;&nbsp; [/accordion][/agroup]')."</div>";
								echo $is_faq;	
						echo '[/tab]';												
					}	
											
					if(isset($course_and_infographics) && !empty($course_and_infographics)){
						echo '[tab title="'.$title_course_and_infographics .'" icon=""]';
								echo $course_and_infographics;								
						echo '[/tab]';
					}
					
					echo '[/tabs]';
					
					$course_tabs = ob_get_contents();
					ob_end_clean();
					
					$course_tabs = do_shortcode($course_tabs);
					//$course_tabs = str_replace('</ul>', '<li><a href="#reviews" onclick="jQuery(\'body,html\').animate({ scrollTop: jQuery(\'#reviews\').offset().top -90 }, 1200); return false;" >Reviews</a></li></ul>', $course_tabs);
					echo $course_tabs;
					
					global $withcomments;
					$withcomments = true;
					comments_template('/course-review.php',true);*/
				?>
          		

				<?php 
				
				do_action( 'bp_before_course_body' );
				?>
				  
				<?php
				/**
				 * Does this next bit look familiar? If not, go check out WordPress's
				 * /wp-includes/template-loader.php file.
				 *
				 * @todo A real template hierarchy? Gasp!
				 */

				if(isset($_GET['action']) && $_GET['action']):

					switch($_GET['action']){
						case 'curriculum':
							locate_template( array( 'course/single/curriculum.php'  ), true );
						break;
						case 'members':
							if(is_super_admin() || is_instructor())
								locate_template( array( 'course/single/members.php'  ), true );
						break;
						case 'events':
							locate_template( array( 'course/single/events.php'  ), true );
						break;
						case 'admin':
							if(current_user_can( 'manage_options' ) || (get_current_user_id() == $post->post_author)){
								locate_template( array( 'course/single/admin.php'  ), true );	
							}else{
								locate_template( array( 'course/single/front.php' ) );
							}
							
						break;
						default:
							locate_template( array( 'course/single/front.php' ) );
					}
					
				
				else :
					
					if ( isset($_POST['review_course']) && isset($_POST['review']) && wp_verify_nonce($_POST['review'],get_the_ID()) ){
						 global $withcomments;
					      $withcomments = true;
					      comments_template('/course-review.php',true);
					}else if(isset($_POST['submit_course']) && isset($_POST['review']) && wp_verify_nonce($_POST['review'],get_the_ID())){ // Only for Validation purpose

						bp_course_check_course_complete();

						bp_course_record_activity(array(
					          'action' => 'Student Submitted the course '.get_the_title(),
					          'content' => 'Student '.bp_core_get_userlink(get_current_user_id()).' submitted the course '.get_the_title().' for evaluation',
					          'type' => 'submit_course',
					          'item_id' => get_the_ID(),
					          'primary_link'=>get_permalink(get_the_ID()),
					          'secondary_item_id'=>$user_id
					        ));	


					// Looking at home location
					}else if ( bp_is_course_home() ){
						
								
							ob_start();
							
							echo '[tabs style="" theme=]';
							
							if(isset($content) && !empty($content)){
								echo '[tab title="About Course" icon=""]';
										locate_template( array( 'course/single/front.php' ), true );
								echo '[/tab]';
							}
							
							if(isset($curriculum) && !empty($curriculum)){
								echo '[tab title="Course Content" icon=""]';
										locate_template( array( 'course/single/curriculum.php'  ), true );
										
								echo '[/tab]';
							}
							
							if(isset($is_faq) && !empty($is_faq)){
								
								echo '[tab title="FAQ" icon=""]';
									echo "<div class='course_title'>";
										echo "<p class='h2'>Frequently Asked Questions</p>";
										echo "</div>";
										echo "<div style='display:none;'>".do_shortcode('[agroup] [accordion title="&nbsp;&nbsp;"]&nbsp;&nbsp; [/accordion][/agroup]')."</div>";
										echo $is_faq;	
								echo '[/tab]';												
							}	
													
							if(isset($course_and_infographics) && !empty($course_and_infographics)){
								echo '[tab title="'.$title_course_and_infographics .'" icon=""]';
										echo $course_and_infographics;								
								echo '[/tab]';
							}
							
							echo '[/tabs]';
							
							$course_tabs = ob_get_contents();
							ob_end_clean();
							
							$course_tabs = do_shortcode($course_tabs);
							//$course_tabs = str_replace('</ul>', '<li><a href="#reviews" onclick="jQuery(\'body,html\').animate({ scrollTop: jQuery(\'#reviews\').offset().top -90 }, 1200); return false;" >Reviews</a></li></ul>', $course_tabs);
							echo $course_tabs;
							
							global $withcomments;
							$withcomments = true;
							comments_template('/course-review.php',true);

						// Use custom front if one exists
						$custom_front = locate_template( array( 'course/single/front.php' ) );
						if     ( ! empty( $custom_front   ) ) : load_template( $custom_front, true );
						
						elseif ( bp_is_active( 'structure'  ) ) : locate_template( array( 'course/single/structure.php'  ), true );

						// Otherwise show members
						elseif ( bp_is_active( 'members'  ) ) : locate_template( array( 'course/single/members.php'  ), true );

						endif;

					// Not looking at home
					}else {

						// Course Admin/Instructor
						if     ( bp_is_course_admin_page() ) : locate_template( array( 'course/single/admin.php'        ), true );

							// Course Members
						elseif ( bp_is_course_members()    ) : locate_template( array( 'course/single/members.php'      ), true );

						// Anything else (plugins mostly)
						else                                : locate_template( array( 'course/single/plugins.php'      ), true );

						endif;
					}
				endif;
					
				do_action( 'bp_after_course_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_course_home_content' ); ?>

			<?php endwhile; endif; ?>
			</div>
			<div class="col-md-3 col-sm-3" style="padding: 5px;">
                            
                <?php 
					//Hide take self paced course widget if course is already in cart.
					$hiddenClass = $pid = '';
					$_cart = WC()->cart->get_cart();
					
					if(isset($_COOKIE['course_in_cart'])) {
						$course_in_cart = json_decode(stripslashes( $_COOKIE['course_in_cart']), true);
						if(isset($course_in_cart[get_the_ID()])){
							$pid = $course_in_cart[get_the_ID()];
							foreach($_cart as $cart_item_key => $values ){
								if( $pid == $values['product_id'] ) 
									$hiddenClass = 'hide';
							}
						}
					}
				?>
            		
				<div class="widget pricing <?php echo $hiddenClass; ?>" style="box-shadow:none;padding:0px 0px 0px 3px;">
					<div style="float:left;width:95%;">
					<?php intellipaat_selfpaced_course_button(); ?>	
					<?php intellipaat_online_course_button(); ?>	</div>				
					<div style="float:left;width:5%;"><?php do_action('course_wishlist_button', get_the_ID()); ?></div>
				</div>  
            	
				
				<?php /* */
                    if ( sizeof( $_cart ) > 0 ) {
						//do_action('take_course_crm_events');
						
						
                        $total = WC()->cart->cart_contents_count.' Course'.( WC()->cart->cart_contents_count > 1 ? 's' : '');
						$credits_bill=WC()->cart->get_cart_total();
						
						
                        echo '<div class="widget woocart pricing course-cart" style="margin:0px">';
                        echo "You have ".$total." of ".$credits_bill." in your cart. (<a class='link wcmenucart-contents ajax-cart-link' href='".WC()->cart->get_cart_url()."'>Edit Cart</a> // <a class='link wcmenucart-contents ajax-cart-link' href='".WC()->cart->get_cart_url()."'>Apply Discount</a>)";
						echo '<p class="buttons">
								<a class="button wc-forward" href="'.site_url('all-courses/').'">Shop More</a>
								<a class="button checkout wc-forward" href="'.WC()->cart->get_checkout_url().'">Checkout</a>
							</p>';
                        echo '</div> ';
						/*
						?>
                        <div class="widget pricing course-discount">
                        	<form method="post" action="<?php echo WC()->cart->get_cart_url() ?>">
                                <div class="coupon-form">
                                    <input name="coupon_code" id="coupon_code" placeholder="Enter your coupon code" name="coupon_code" class="form-control" />
                                    <button type="submit" name="apply_coupon">Apply</button>
                                </div>
							</form>
                        </div> 
                        <?php */
					}
                ?>					
				                       

                   
                   
                        <?php				
							
							$key_feature = "";
							
							$key_feature_value = "";
							
							for($i=1; $i<=6 ; $i++){
								
								$key_value = get_field("course_".$i."_key_feature");
								
								$key_text = get_field("course_".$i."_key_feature_text");
								
								//check if both key and value are set
								if((isset($key_value) && !empty($key_value)) && (isset($key_text) && !empty($key_text))){
							
									$key_feature_value .=  '<div class="accordion-group panel">
																<div class="accordion-heading">
																	<a href="#keyfeature'.$i.'" data-parent="#key-features" data-toggle="collapse" class="accordion-toggle collapsed">'.$key_value.'</a>
																</div>
																<div class="accordion-body collapse" id="keyfeature'.$i.'">
																	<div class="accordion-inner">'.$key_text.'</div>
																</div>
															</div>
														';
								
								}
																				
							}
							?>
							<div class="" style="background-color:#fff;    padding: 12px;">	
								<a href="https://intellipaat.com/our-business/" target="_blank" ><img src="<?php echo site_url(); ?>/wp-content/themes/intellipaat/images/corporates.png" /> </a>
							</div>
							<?php //Check if anything is set..from all key features
							if(isset($key_feature_value) && !empty($key_feature_value)){
								
							?>                            
                                <div class="widget key-features pricing hidden-xs" style="    padding: 0px 20px 10px;">
                                    
                                    <p id="keyfeature-heading" class="h3" style="margin-top:0;">Key Features :</p>
                                    
                                    <div id="key-features" class="accordion ">
                                    
                                    <?php 	echo $key_feature_value; ?>
                                    
                                     </div>
                                    
                                    <?php  intellipaat_the_course_details();  ?>
                                
                                </div>                             
                            <?php
								
							 }
							
						 ?>  
                         <div id="contact-form" class="widget pricing ">
                            <p class="h3 contact-title">Drop Us A Query</p>
                            <?php  echo do_shortcode('[contact-form-7 id="2101" title="Drop a Query Here"]');?>
                        </div>
                        
                        <div id="review-mobile" class="visible-xs"></div>
                          
                         <?php
						 	
						 	intellipaat_recommended_courses();
							
							
							$sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
							
							if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : 
							
							endif;
							
							?>           
                
                </div>
				
               
			 	
                
			</div>
		</div><!-- .padder -->
		
	</div><!-- #container -->
	</div>
</section>	
<?php get_footer( 'buddypress' ); ?>
