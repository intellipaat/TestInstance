<?php global $post; ?>

<?php //POPULAR TUTORIALS

	$taxonomies = get_object_taxonomies($post->post_type); 
	foreach ($taxonomies as $taxonomy) {
		$terms = wp_get_post_terms( $post->ID, $taxonomy , array("fields" => "ids")); 
		if ( !empty( $terms ) ) {
				$args = array(
					'posts_per_page'  	=> 8,
					'orderby'         	=> 'menu_order',
					'order'            	=> 'ASC',
					'post__not_in'		=> array($post->ID),
					'post_type'        	=> $post->post_type,	
					'post_parent'	   	=> 0,
					'tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field'    => 'term_id',
								'terms'    => $terms,
								'operator' => 'IN',
							),
						),
				);
		}else{		
			$args = array( 'post_type' => $post->post_type, 'posts_per_page' => 8, 'orderby' => 'menu_order', 'order' => 'ASC' ,'post__not_in'   => array($post->ID), );
		}
	}
	$loop = new WP_Query( $args );

	if( $loop->have_posts()  ){ ?>
	
		<div class="widget tutorial-widget">
			 <h4 class="widget_title">Interview Questions</h4>                   
			<div class="pagelist">
				 <ul>
									 
						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>							
									  <li>
										  <a href="<?php echo get_permalink($tuts_post); ?>"><?php echo get_the_title($tuts_post); ?></a>
									  </li>
						<?php  endwhile;
                        wp_reset_postdata(); ?>
									  
					</ul> 
			</div>  
			<a class="text-center aligncenter archive-link" href="<?php echo get_post_type_archive_link( 'interview-question' ); ?>">Browse All Interview Questions</a>
		</div>
		
	  <?php } 	
	
?>

<!--------- jobs section------------->
 <?php //POPULAR TUTORIALS
	
	$job_title = get_field('Intellipaat_job_page_title', $post->ID);
	$jobs = get_field('intellipaat_job_pages', $post->ID);
	
	if( $jobs && $job_title){ ?>
	
		<div class=" text-center widget tutorial-widget">
			 <h3><a class="bold" href="<?php echo $jobs ?>"><i class="icon-pin-2"> </i><?php echo $job_title ?></a></h3>
		</div>
		
	  <?php } 	
	
?>
<!--------- jobs section end------------->
<!---------popular tutorials------------->

 <?php //POPULAR TUTORIALS
	
	$tuts_posts = get_field('intellipaat_tutorials', $post->ID);
	
	if( $tuts_posts ){ ?>
	
		<div class="widget tutorial-widget">
			 <h4 class="widget_title">Popular Tutorials</h4>                   
			<div class="pagelist">
				 <ul>
									 
						<?php foreach ( $tuts_posts as $tuts_post){ ?>							
									  <li>
										  <a href="<?php echo get_permalink($tuts_post); ?>"><?php echo get_the_title($tuts_post); ?></a>
									  </li>
						<?php } ?>
									  
					</ul> 
			</div>  
			<a class="text-center aligncenter archive-link" href="<?php echo get_post_type_archive_link( 'tutorial' ); ?>">Browse All Tutorials</a>
		</div>
		
	  <?php } 	
	
?>
<!--------------popular tutorials end------------>  