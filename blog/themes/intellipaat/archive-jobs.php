<?php
get_header();
?>
<style>


</style>
<script>
jQuery(document).ready(function(){
jQuery(".accordian").click(function(){
//jQuery(".accordian_active").toggleClass("accordian_active");
var id = jQuery(this).attr("alt");
jQuery('#'+id).toggleClass("accordian_active");
})
});
</script>
<section id="title">
	<div class="container">
		<div class="row">
           
            <div class="col-md-3 col-sm-4">
                <?php vibe_breadcrumbs(); ?>  
            </div>
			<div class="qsearch-form"><div id="searchbox" class="fl">
                            <form role="search" method="get" id="search-form" action="<?php echo home_url( '/' ); ?>">
                                <div>
                                    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Search For Interview Questions...','vibe'); ?>" />
                                    <input type="hidden" value="interview-question" name="post_type" />                                      
                                    <button type="submit" role="submit"><i class="icon-search-2"></i></button>
                                </div>
                            </form>
                            
                        
                    	</div> </div>
        </div>
	</div>
</section>
<section id="content">
<div id="buddypress">
	<div class="container">
        <div class="row">
		<!-----------left---------->
		<div class="col-md-3 col-sm-3">
<!-----------------interview questionss section------------->
		<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name"><?php post_type_archive_title(); ?></span></a></h3>
		<div id="item-nav" class="hidden-xs">
<div id="object-nav" class="item-list-tabs no-ajax" role="navigation">

		<ul>
			<?php $args = array( 'post_type' => 'interview-question', 'posts_per_page' => 10, 'orderby' => 'id', 'order' => 'DESC'  );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();?>

<li><a href="<?php the_permalink();?>"> <?php the_title();?></a></li>
 <?php  
endwhile;
	 ?>  </ul> 
</div> 
</div></div>  
<!---------recent jobs section------------->
<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name">Recent Jobs</span></a></h3>
		<div id="item-nav" class="hidden-xs">
<div id="object-nav" class="item-list-tabs no-ajax" role="navigation">

		<ul>
		<?php


$args = array( 'posts_per_page' => 10,  'category' => 579, 'orderby' => 'date', 'order'=> 'DESC' );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
	<li>
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</li>
<?php endforeach; 
wp_reset_postdata();?>
		
			 </ul> 
</div> 
</div></div>  
<!---------recent jobs section end------------->
<!---------popular tutorials------------->
<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name">Tutorial</span></a></h3>
		<div id="item-nav" class="hidden-xs">
<div id="object-nav" class="item-list-tabs no-ajax" role="navigation">

		<ul>
			<?php $args = array( 'post_type' => 'tutorial','posts_per_page' => 10,
        'orderby' => 'id', 'order' => 'DESC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();?>

<li><a href="<?php the_permalink();?>"> <?php the_title();?></a></li>
 <?php  
endwhile;
	 ?>  </ul> 
</div> 
</div></div>   
<!--------------popular tutorials end------------>  
<!---------popular courses------------->
<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name">Popular Courses</span></a></h3>
		<div id="item-nav" class="hidden-xs">
<div id="object-nav" class="item-list-tabs no-ajax" role="navigation">

		<ul>
			<?php $args = array( 'post_type' => 'course','posts_per_page' => 10,
        'orderby' => 'id', 'order' => 'DESC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();?>

<li><a href="<?php the_permalink();?>"> <?php the_title();?></a></li>
 <?php  
endwhile;
	 ?>  </ul> 
</div> 
</div></div>    
<!---------popular courses end------------->

			</div>
		<!----middle--------->
		<div class="col-md-6 col-sm-6 hidden-xs">
			 <div class="container">  
   <h1> <?php post_type_archive_title(); ?></h1>

   <hr></hr>   
  <!--   <abbr>
 <p><span>I</span>t
   <?php $post_type = 'interview-question';
 $description = $wp_post_types[$post_type]->description; 
//echo htmlspecialchars_decode($description);?>
   <p></abbr>-->
	   
<!--<dl>
 <?php/* $args = array( 'post_type' => 'interview-question' );
$loop = new WP_Query( $args );

$i=1;
while ( $loop->have_posts() ) : $loop->the_post(); */

?>

 
		<dt><a href="javascript:void(0);" alt="Section<?php echo $i; ?>" class="accordian"><?php the_title(); ?></a></dt>
		<dd id="Section<?php echo $i; ?>">
			<p>
				<?php echo wp_trim_words( get_the_content(), 40, '...<a href="'. get_permalink() .'">Read More</a>' ); ?>
			</p>
		</dd>
		
		<?php 
/* $i++;		
endwhile; */
	 ?> 
	</dl>-->


   </div>
			</div>
    	
			<!---------right--------->
    		<div class="col-md-3 col-sm-3">
			
            	<div id="contact-form" class="widget pricing ">
                            <h3 class="contact-title">Drop Us A Query</h3>
                            <?php  echo do_shortcode('[contact-form-7 id="5870" title="Drop a Query Here Interview Question Page Form"]');?>
                        </div>          
                <div id="review-mobile" class="visible-xs"></div>
                          
                         <?php
						 	
						 	if( class_exists('BP_Course_Widget') ){
								 
								$intellipaat_recommended_courses = get_post_meta( get_the_ID(), 'intellipaat_recommended_courses',true );//get_field('intellipaat_recommended_courses');
								
								if(!empty($intellipaat_recommended_courses))
						 			$ids = implode(',',$intellipaat_recommended_courses);
								
								if(!$ids)
									$ids = '2482,2525,2357';
								
								$args = array(
									'before_widget' => '<div class="widget recommended_course hidden-xs">',
									'after_widget' 	=> '</div>',
									'before_title' 	=> '<h4 class="widget_title">',
									'after_title' 	=> '</h4>'
								);
								
								$instance = array( 'title'=> 'Recommended Courses','style' => 'single','orderby'=>'name','order'=>'DESC','category'=>'','ids'=>$ids , 'max_items' => 3 );
								
								the_widget( 'BP_Course_Widget', $instance, $args  ); 
								
							}
							
							
							$sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
							
							if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : 
							
							endif;
							
							?>   
                </div>
			<!----------end right-------->
        </div>
	</div>
	</div>
</section>

<?php
 get_footer(); 
?>