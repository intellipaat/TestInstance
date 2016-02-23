<?php
/**
* Template Name: jobs
*/
get_header();
if(isset($_POST['search']))
{
	$search_string='';	
	$search_string.=$_POST['search_string'].'&'.$_POST['remote_address'];
		if(!empty($_POST['search_string'])){
			$text=$_POST['search_string'];
		}
		else{
			$text=$_POST['remote_address'];	
		}

		$active=1;
		?>

		
		<?php

}
	else{
		$search_string='Hadoop&in';
		$text='Hadoop';
	}


$feed = file_get_contents('http://rss.indeed.com/rss?q='.$search_string.'');

	$feed = str_replace('<media:', '<', $feed);
	$rss = simplexml_load_string($feed);
	
	$total_records=count($rss->channel->item);

	$count_test='Found '.$total_records.' '.$text.' Jobs';
	

?>

<section id="content">
	<div class="course-breadcrumb container">
	<div class="breadcrumpclass">
                <?php vibe_breadcrumbs(); ?>  
          </div>
		
		</div>
    </div>
	
	
	<div id="buddypress">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-3 col-sm-3">
				<!---------related jobs section------------->
<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name">Related Jobs</span></a></h3>
		<div id="item-nav" class="hidden-xs">
<div id="object-nav" class="item-list-tabs no-ajax" role="navigation">

		<ul>
		<?php


$args = array( 'post_type' => 'jobs','posts_per_page' => 10, 'orderby' => 'date', 'order'=> 'DESC' );

$myposts = get_posts( $args );
foreach ($myposts as $post ) : setup_postdata( $post ); ?>
	<li>
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</li>
<?php endforeach; 
wp_reset_postdata();?>
		
			 </ul> 
</div> 
</div></div>  
<!---------related jobs section end------------->

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

<div id="item-header-content">
		<h3><a href="javascript:void(0)"><span itemprop="name"><?php post_type_archive_title(); ?></span></a>Interview Questions</h3>
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


                
			</div>
            <div class="col-md-6 col-sm-6 hidden-xs">
			 
			<div id="item-body">           
            
             <?php 
			
			while ( have_posts() ) : the_post(); ?>
			<h1> <?php the_title(); ?></h1>
            <hr></hr>
			<?php the_content(); ?>
			
			 <?php endwhile; 
			
			 ?>
			 <input type='hidden' value='<?php echo $active;?>' id='active'>
			<input type='hidden' value='<?php echo $count_test;?>' id='count'>
			<div id="records_feed" style='display:none'>
			<?php 
		if($total_records){
		
echo '<h1>Found total '.$total_records.' jobs</h1>';
	
		foreach($rss->channel->item as $item)
		{
			

		
echo '<div class="job-list">
<h3 style="left: 0px;"><a href="'.$item->link.'" target="_blank" rel="nofollow">'.$item->source.'</a></h3>
<p><span class="company-name">'.$item->title.'</span></p>
<div>'.$item->description.'</div>
<ul class="job-footer">
<li class="job-days">'.time_elapsed_string(strtotime($item->pubDate)).'</li>
<li class="job-source">Job Source : Jobsindia.com</li>
</ul>
</div>';
		}
		


		}else{
			echo "NO Jobs found"; 
		}?>
		</div>
		</div>
            </div>
			
			<div class="col-md-3 col-sm-3">
            	
			<div id="contact-form" class="widget pricing ">
                            <h3 class="contact-title">Drop Us A Query</h3>
                            <?php  echo do_shortcode('[contact-form-7 id="20278" title="Drop a Query Here job"]');?>
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

                 
				
               
			</div>
		</div><!-- .padder -->
		
	</div><!-- #container -->
	</div>
	
</section>	
<script>
jQuery(document).ready(function(){

	jQuery(".nav li:eq(1)").click(function(){
		
		jQuery("#records_feed").show();
	});
	

	jQuery(".nav li:eq(0)").click(function(){
		
		jQuery("#records_feed").hide();
	});	
	
	jQuery(".nav li:eq(1) a").text(jQuery("#count").val());
	
	
});
jQuery(window).load(function(){
	
	if(jQuery("#active").val()=='1')
	{
		
	jQuery(".nav li:eq(0)").removeClass('active');	
	jQuery(".nav li:eq(1)").addClass('active');	
	jQuery("#records_feed").show();
	jQuery("#job_form").show();
	jQuery("#what").val('<?php echo $_POST['search_string'];?>');
	
	jQuery('#where option[value="<?php echo $_POST[remote_address];?>"]').prop('selected', true);
	jQuery(".tab-pane").removeClass('active');
	jQuery(".tab-pane:eq(1)").addClass('active');
	jQuery(".nav li:eq(1)").trigger('click');
	}
	
})


function submitForm( page_num ){
   $("#page_num").attr("value", page_num-1);
   $("#job_form").submit();
}
function resetPage(){
   $("#page_num").attr("value", 0);
}

</script>
<?php get_footer( ); ?>