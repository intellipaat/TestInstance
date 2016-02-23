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
		   <?php  	get_sidebar('left'); ?> 
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
			<?php get_sidebar('right');?>
            				<!----------end right-------->
        </div>
	</div>
	</div>
</section>

<?php
 get_footer(); 
?>