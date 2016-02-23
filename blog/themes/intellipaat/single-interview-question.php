<?php 
	get_header( 'buddypress' );

?>
<section id="title" class="clear clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <?php vibe_breadcrumbs(); ?>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php get_template_part('templates/search','form'); ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
            <div id="sidebar-left" class="sidebar-left col-md-3 col-sm-3">
                <div class="sidebar">
                	<?php 
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
							the_post_thumbnail();
						} 
					?>	
                    <?php  if ( !wp_is_mobile() ) { get_sidebar('left'); } ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div id="item-body" class="content">           
                    
                    <?php $qa_id_count = $qa_count = 1;  ?>
                    <?php while ( have_posts() ) : the_post(); ?>
<?php 
$newtitle=get_post_custom_values("add_h1_page_title", get_the_ID());

if(isset($newtitle[0]) && $newtitle[0] !='')
{
?>
<div class="title"><?php echo $newtitle[0]; ?> </div>  <?php
}else{
?> <h1><?php the_title(); ?> </h1>  
<?php
} 

$sub_heading_with_h1=get_post_custom_values("sub_heading_with_h1_tag", get_the_ID());
if(isset($sub_heading_with_h1[0]) && $sub_heading_with_h1[0] !='')
{
?>
<div class="small_desc_heading"><?php echo $sub_heading_with_h1[0]; ?> </div>  <?php
} 
?>
                        <hr></hr>
                        <?php 											
                                $video_id= get_post_meta($post->ID, 'intellipaat-videothumb',true);
                                
                                if(isset($video_id) && !empty($video_id)){
                                    echo '<div class="featured-video">'.do_shortcode('[videothumb class="col-md-13" id="'.$video_id.'" alt="'.get_the_title().' Video"]').'</div>';
                                }
                            ?> 
                        <?php if(!empty($post->post_content)) the_content(); ?>
                       
                       	<?php $basic_questions = unserialize(get_post_meta( $post->ID, 'intellipaat_interview_basic_question', true ) );  ?>                        
                       	<?php $advanced_questions = unserialize(get_post_meta( $post->ID, 'intellipaat_interview_advanced_question', true ) );  ?> 

                        <?php if($basic_questions || $advanced_questions ){ ?>
                             <div id="iq-tabs">
                                  <ul>
                                       <?php if($basic_questions){ ?> <li><a href="#iq-tabs-1">Basic Questions</a></li><?php } ?>
                                       <?php if($advanced_questions){ ?> <li><a href="#iq-tabs-2">Advanced Questions</a></li><?php } ?>
                                  </ul>       
                            
                                    <?php if($basic_questions){
                                        
                                        echo "<div id=\"iq-tabs-1\"><dl>";
                                            foreach($basic_questions as $basic_question){
                                                echo "<dt class=\"accordian active_arrow\" alt=\"Section_".$qa_id_count."\"><a>".$qa_count.". ".$basic_question['question']."</a></dt>";
                                                echo "<dd id=\"Section_".$qa_id_count."\" class='accordian_active'>".apply_filters( 'the_content', stripslashes(base64_decode($basic_question['answer'])))."</dd>";
                                                $qa_id_count++; $qa_count++;
                                            }
                                        echo "</dl></div>";
                                        
                                    }   ?> 
                                
                                    <?php  $qa_count=1;
                                        
                                    if($advanced_questions){
                                    
                                        echo "<div id=\"iq-tabs-2\"><dl>";
                                            foreach($advanced_questions as $advanced_question){
                                                echo "<dt class=\"accordian active_arrow\" alt=\"Section_".$qa_id_count."\"><a>".$qa_count.". ".$advanced_question['question']."</a></dt>";
                                                echo "<dd id=\"Section_".$qa_id_count."\" class='accordian_active'>".apply_filters( 'the_content',stripslashes( base64_decode($advanced_question['answer'])))."</dd>";
                                                $qa_id_count++; $qa_count++;
                                            }
                                        echo "</dl></div>";
                                        
                                    }   ?>
                                </div>
                           <?php } ?>
                            
                     <?php endwhile; ?>
                    <div class="post-navigation page-navigation clear clearfix">  
                        <div class="pull-left text-left"><?php previous_post_link('%link', '&laquo;  Previous'); ?></div>
                        <div class="pull-right text-right"><?php next_post_link('%link', 'Next &raquo;'); ?></div>
                    </div>
                </div><!-- #item-body -->
                  <?php comments_template();  ?>
            </div>
            <div id="sidebar-right" class=" sidebar-right col-md-3 col-sm-3">
                <div class="sidebar">
                    <?php get_sidebar('right'); ?>
                </div>
                <?php  if ( wp_is_mobile() ) { get_sidebar('left'); } ?>
            </div>  
        </div>
    </div>
</section>

<script>
jQuery(window).load(function () {

	jQuery(".accordian").each(function(){
		jQuery(this).addClass('active_arrow').removeClass('active_arrow_remove');
		var id = jQuery(this).attr("alt");
		jQuery('#'+id).addClass("accordian_active");
        
    });

});
jQuery(document).ready(function(){
	jQuery( "#iq-tabs" ).tabs();
});
	

<?php /*?>jQuery(document).ready(function(){

	
	$('#thumbs')each(function(){
    if(enumThumb == 0){
          $(this).addClass('active');
    }   
	
	.jQuery(".accordian").click(function(){
	
		jQuery(this).addClass('active_arrow').removeClass('active_arrow_remove');
		var id = jQuery(this).attr("alt");
		jQuery('#'+id).toggleClass("accordian_active");
		if(!jQuery('#'+id).hasClass("accordian_active"))
		{
			jQuery(this).removeClass('active_arrow').addClass('active_arrow_remove');
		}
	})
});<?php */?>
</script>
<style>
div#iq-tabs .ui-tabs-nav {
    background: #f5f5f5;
    clear: both;
    display: block;
    padding: 5px 5px 0;
    margin: 0 10px;
    overflow: hidden;
}

div#iq-tabs .ui-tabs-nav li {
    width: 50%;
    /* padding: 10px 4%; */
    float: left;
    list-style: none;
    text-align: center;
}

div#iq-tabs .ui-tabs-nav li.ui-tabs-active {
    background: #0B99BC;
}

div#iq-tabs .ui-tabs-nav li.ui-tabs-active a, div#iq-tabs .ui-tabs-nav li.ui-tabs-active a:hover {
    color: #fff !important;
}

div#iq-tabs .ui-tabs-nav .ui-tabs-anchor {
    width: 100%;
    padding: 10px;
    display: block;
}
</style>
<?php get_footer( 'buddypress' ); ?>
