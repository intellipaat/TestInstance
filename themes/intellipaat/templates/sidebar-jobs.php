 <?php
 global  $post;
 
 // Interview Questions
	
	$iqs = get_field('intellipaat_interview_questions', $post->ID);
	
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
<!--------- certification page section------------->
 <?php //POPULAR TUTORIALS
	
	$certification_page_title = get_field('intellipaat_certification_page_link_title', $post->ID);
	$certification_page = get_field('intellipaat_certification_page_link', $post->ID);
	
	if( $certification_page ){ ?>
	
		<div class=" text-center widget tutorial-widget">
			 <h3><a class="bold" href="<?php echo $certification_page  ?>"><i class="icon-certificate-file"> </i><?php echo $certification_page_title ?></a></h3>
		</div>
		
	  <?php } 	
	
?>
<!--------- certification page section end------------->

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