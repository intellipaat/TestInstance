<?php 

add_shortcode('home_page_course','home_page_course');

function home_page_course($atts){
	
	extract( shortcode_atts( array(

		  'tag_id'  => 	"",
		  'term'		=>	'',
		  'showposts' => 8

      ), $atts ) );
	
	$count=1;
	//$cached_course_category = get_transient( 'all_courses_home_page' );
	
	//if ( false === $cached_course_category || current_user_can('edit_posts') ) {
		
	if (1) {
		$cached_course_category = '';
		$class = 'fast-moving-courses' ;
		
	
		$args = array(
			'showposts' => $showposts ,
			'post_type' => 'course',
			'order' => 'ASC',
		);
		
		
		$args['orderby']		= 'meta_value_num';
		$args['meta_key']		= 'intellipaat_order_fast_moving';
		$args['meta_query']		= array(
											array(
												'key'     => 'intellipaat_fast_moving',
												'value'   => 1,
												'compare' => '=',
											),
										);
		
							
		$query = new WP_Query( $args );
		
		if ($query->have_posts()) :
			
		
					 
				while ( $query->have_posts() ) : $query->the_post(); 
					/*$terms = get_the_terms( get_the_ID(), 'course-cat' );
					foreach($terms as $term){
						$class .= $term->slug.' ';
					}*/
					/*$intellipaat_fast_moving_new = get_field('intellipaat_fast_moving_new');	
					if($intellipaat_fast_moving_new)
						$class .= implode(' ', $intellipaat_fast_moving_new);
					$pid=get_post_meta(get_the_ID(),'vibe_product',true);
					if($pid)
						$class .= 'self-paced';*/
				
					
					$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
					
						
					
					if(function_exists('thumbnail_generator')){
						$cached_course_category .=  thumbnail_generator($query->post,'course','medium',1,1,1);
					}       	
					
					$cached_course_category .=  "</div>";
					
					if($count%4==0)
									$cached_course_category .= '<div class="clear clearfix"></div>';
								$count++;
					
		
				endwhile;	
		else :
		
				$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
				$cached_course_category .= '<h2>We did\'t found any course matching your request.</h2>';
				$cached_course_category .=  "</div>";
				
		endif;	
		
		
		
		wp_reset_postdata();	
		
		if(!is_user_logged_in())
			set_transient( 'all_courses_home_page', $cached_course_category,  WEEK_IN_SECONDS );
	}
	
	$output = '<div class="row flex-viewport" >';
	$output .= $cached_course_category;
	$output .= '</div>';	
	
	return $output;
}
/*
add_shortcode('home_course_tab','courses_tab');

function courses_tab($atts){
	
	extract( shortcode_atts( array(

		  'tag_id'  => 	"",
		  'term'		=>	'',
		  'showposts' => 8

      ), $atts ) );
	
		if($tag_id){
	
			$count=1;
			$posts= get_field('intellipaat_custom_category_order', get_term_by( 'id', $tag_id, 'course-cat' )  );
									
			
			if($posts)
			{	
				global $post;
				$output = '<div class="row flex-viewport" >';
						 
					  $output .= '<ul class="grid-img-ul">' ;
					  
							foreach($posts as $post){
												
								$output .= '<li class="course_icon_hover grid-course-img col-md-3 col-sm-6" >';
								
								if(function_exists('thumbnail_generator')){
									$output .=  thumbnail_generator($post,'course','medium',1,1,1);
								}       	
								
								$output .= '</li>';	
								if($count%4==0)
										$output .= '<li class="clear clearfix"></li>';
									$count++;
							}
						$output = $output. " </ul> ";
						   
					$output .= "</div>";
			}
			else{
					$args = array(
						'showposts' => 24,
						'post_type' => 'course',
						'order' => 'ASC',
						'orderby' => 'menu_order',
						'tax_query' => array(
							array(
								'taxonomy' => 'course-cat',
								'field' => 'id',
								'terms' => array( $tag_id ),
							)
						)
					);
					$query = new WP_Query( $args );
					if ($query->have_posts()) :
						
						$output = '<div class="row flex-viewport" >';
								 
							  $output .= '<ul class="grid-img-ul">' ;
							   while ( $query->have_posts() ) : $query->the_post(); 
									
									$output .= '<li class="course_icon_hover grid-course-img col-md-3 col-sm-6" >';
									
										
									
									if(function_exists('thumbnail_generator')){
										$output .= thumbnail_generator($query->post,'course','medium',1,1,1);
									}else{
										
										$output .= '<div class="block courseitem">';
									
										$output .= '<div class="block_media">';
											$output .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_id() , array(310,185) ) . '</a>';
										$output .= '</div>';
										
										$output .= '<div class="block_content">';
											$output .= '<h4 class="block_title"><a  class="course_icon_text" href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';						
											
										$output .= '</div>';
										
										$output .= '</div>';
										
									}           	
								
								$output .= '</li>';
								
								if($count%4==0)
									$output .= '<li class="clear clearfix"></li>';
								$count++;
			
							endwhile;			  
							   
							$output = $output. " </ul> ";
							   
							  
						   
					$output .= "</div>";
					
				   endif;
			}
	
			wp_reset_postdata();
			
		}else{
			
			$cached_course_category = get_transient( 'all_courses_home_page' );
			
			if ( false === $cached_course_category || current_user_can('edit_posts') ) {
				
				$cached_course_category = '';
				$class = 'fast-moving-courses' ;
				
			
				$args = array(
					'showposts' => $showposts ,
					'post_type' => 'course',
					'order' => 'ASC',
				);
				
				
				$args['orderby']		= 'meta_value_num';
				$args['meta_key']		= 'intellipaat_order_fast_moving';
				$args['meta_query']		= array(
													array(
														'key'     => 'intellipaat_fast_moving',
														'value'   => 1,
														'compare' => '=',
													),
												);
				
									
				$query = new WP_Query( $args );
				
				if ($query->have_posts()) :
					
				
							 
						while ( $query->have_posts() ) : $query->the_post(); 
							/*$terms = get_the_terms( get_the_ID(), 'course-cat' );
							foreach($terms as $term){
								$class .= $term->slug.' ';
							}*/
							/*$intellipaat_fast_moving_new = get_field('intellipaat_fast_moving_new');	
							if($intellipaat_fast_moving_new)
								$class .= implode(' ', $intellipaat_fast_moving_new);
							$pid=get_post_meta(get_the_ID(),'vibe_product',true);
							if($pid)
								$class .= 'self-paced';
						
							
							$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
							
								
							
							if(function_exists('thumbnail_generator')){
								$cached_course_category .=  thumbnail_generator($query->post,'course','medium',1,1,1);
							}       	
							
							$cached_course_category .=  "</div>";
							
				
						endwhile;	
				else :
				
						$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
						$cached_course_category .= '<h2>We did\'t found any course matching your request.</h2>';
						$cached_course_category .=  "</div>";
						
				endif;	
				
				
				
				wp_reset_postdata();	
				
				set_transient( 'all_courses_home_page', $cached_course_category,  WEEK_IN_SECONDS );
			}
			
			$output = '<div class="row flex-viewport" >';
			$output .= $cached_course_category
			$output .= '</div>';
			
		}                   
	
	return 	$output;
	
}


add_shortcode('home_product_tab','products_tab');

function products_tab($atts){
	
	extract( shortcode_atts( array(

      'tag_id'   => "",
	  

      ), $atts ) );
	

		$args = array(
			'showposts' => 20,
			'post_type' => 'product',
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => array( $tag_id ),
				)
			)
		);
		$query = new WP_Query( $args );
		
		
		if ($query->have_posts()) :
			
			$output = '<div class="row flex-viewport" >';
					 
				  $output .= '<ul class="grid-img-ul">' ;
				   while ( $query->have_posts() ) : $query->the_post(); 
				   		
						$product = get_product( get_the_ID() );
						if(is_object($product))
						$course_credits = '<strong>'.$product->get_price_html().'</strong>';
									
						$output .= '<li class="course_icon_hover grid-course-img col-md-3 col-sm-6" >';
						
							
						
						
							
							$output .= '<div class="block courseitem">
                                            <div class="block_media">
                                                <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'full').'</a>
                                            </div>
                                            <div class="block_content">
                                                <h4 class="block_title"><a title="Splunk Training" href="'.get_permalink().'">'.get_the_title().'</a></h4>
                                                <div class="star-rating"></div>
												<strong>'.$product->get_price_html().'</strong>                                           
                                            </div>
                                        </div>';
					        	
					
					$output .= '</li>';

				endwhile;			  
				   
				$output = $output. " </ul> ";
				   
				  
			   
		$output .= "</div>";
		
	   endif;	           
	
		wp_reset_postdata();
                   
	
	return 	$output;
	
}
*/
?>