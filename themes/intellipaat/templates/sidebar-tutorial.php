 <?php 
 
	global $post; 
	if(!$post->post_parent){
		
		$parent_id = $post->ID;
    }
    else{
		
		$ancestors = get_ancestors( $post->ID, $post->post_type );
		$parent_id = end($ancestors);
    }

?>


<?php 
	if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
		the_post_thumbnail();
	} else{
		echo get_the_post_thumbnail($parent_id);
	}
?>

<?php
	// All tutorial under this tutorial topic
	
	$parentName = get_the_title($parent_id);
	$parentLink = get_permalink($parent_id);
	// will display the subpages of this top level page
	$children = wp_list_pages("title_li=&child_of=".$parent_id."&post_type=".$post->post_type."&echo=0&depth=1&sort_column=menu_order");
	
	?>
	
 
	<div class="widget tutorial-widget">
	    <a href="<?php echo $parentLink; ?>"><h3 class="widget_title"><?php echo $parentName;?>s</h3></a>
        <div class="pagelist">
            <ul>
                <?php echo $children; ?>
            </ul>
        </div>
	</div>
 
 
   
    <?php // Interview Questions
		
		$iqs = get_field('intellipaat_interview_questions', $parent_id);
		
		if( $iqs ){ ?>
		
			<div class="widget tutorial-widget">
				<h5 class="widget_title">Interview Questions</h5>                    
				<div class="pagelist">
					 <ul>
										 
							<?php foreach ( $iqs as $iq){ ?>							
										  <li>
											  <a href="<?php echo get_permalink($iq); ?>"><?php echo get_the_title($iq); ?></a>
										  </li>
                            <?php } ?>
										  
						</ul> 
				</div>  
                <a class="text-center aligncenter archive-link" href="<?php echo get_post_type_archive_link( 'interview-question' ); ?>">Browse All Interview Questions</a>
			</div>
            
          <?php } 	
		
	?>
    
  
<?php //POPULAR TUTORIALS
	
	$popular_tutorial = get_field('intellipaat_popular_tutorial', $parent_id);
	
	if($popular_tutorial && !empty($popular_tutorial)){
		$popular_tutorial = explode(',',$popular_tutorial);
	}else{
		$taxonomies = get_object_taxonomies($post->post_type); 
		 foreach ($taxonomies as $taxonomy) {
			$terms = wp_get_post_terms( $post->ID, $taxonomy , array("fields" => "ids")); 
			if ( !empty( $terms ) ) {
					$args = array(
						'posts_per_page'   => 5,
						'orderby'          => 'menu_order',
						'order'            => 'ASC',
						'exclude'          => $post->ID,
						'post_type'        => $post->post_type,	
						'post_parent'	   => 0,
						'tax_query' => array(
								array(
									'taxonomy' => $taxonomy,
									'field'    => 'term_id',
									'terms'    => $terms,
									'operator' => 'IN',
								),
							),
					);
			
				$popular_tutorial = get_posts($args);
			}
		}
	}
	
	if($popular_tutorial){
		?>
							
			<div class="widget tutorial-widget">
				<h4 class="widget_title">Popular Tutorials</h4>
				<div class="pagelist">
					<ul>
						<?php 
							foreach($popular_tutorial as $tuts_post){
								echo '<li><a href="'.get_permalink($tuts_post).'">'.get_the_title($tuts_post).'</a></li>'; 
							}
						?>
					</ul>
				</div>
				<a class="text-center aligncenter archive-link" href="<?php echo get_post_type_archive_link( $post->post_type ); ?>">Browse All Tutorials</a>
			</div>
		
		<?php
	}


	
?>
  
    <?php // RECENTLY VIEWED TUTORIALS
		/*$popularpost = new WP_Query( 
								array( 
									  'post_type' => $post->post_type,
									  'meta_key' => 'ip_post_views_count', 
									  'orderby' => 'meta_value_num',  
									  'posts_per_page' => 5, 
									  'order' => 'DESC'  ) 
								);
		
		//var_dump($popularpost);
		if( $popularpost->have_posts() ){ ?>
		
			<div class="widget tutorial-widget">
				<h5 class="widget_title">Recently Viewed Tutorials</h5>                    
				<div class="pagelist">
					 <ul>
										 
							<?php while ( $popularpost->have_posts() ) : $popularpost->the_post(); ?>							
										  <li>
											  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										  </li>
                            <?php endwhile; ?>
										  
						</ul> 
				</div>  
			</div>
            
          <?php }*/ 	
		
	?>
