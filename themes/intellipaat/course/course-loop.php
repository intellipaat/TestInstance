<?php //lots of changes done
/**
 * The template for displaying course directory loop.
 *
 * Override this template by copying it to yourtheme/course/course-loop.php
 *
 * @author 		Makarand Mane
 * @package 	vibe-course-module/templates
 * @version     1.8.4
 */
?>
<?php		
	$taxonomy = 'course-cat';
	$course_category = get_query_var('course_category');
	$current_page = all_course_page_link();
	$default_cat = vibe_get_option('default_courses_cat');
	$default_cat_obj = get_term( $default_cat, $taxonomy );
	$default_cat_slug = $default_cat_obj->slug;
	
	if($course_category){		
		$term_obj = get_term_by('slug', $course_category, $taxonomy );
		$id = 'course-cat_'.$term_obj->term_id;
	}
	else
		$id = 'course-cat_'.$default_cat_obj->term_id;
	$intellipaat_meta_description = get_field('intellipaat_meta_description', $id); 
	if(!empty($intellipaat_meta_description)){
		echo '<div class="col-md-12 hidden-xs">'.$intellipaat_meta_description.'</div>';	
	}
?>

<div class="col-md-3 col-sm-4">
	<div id="navbar-inner-filter" class="navbar-inner-filter filter-nav">
		<ul class="nav nav-pills nav-stacked intell_filterable">
               <?php /*?> <li class="active">
                    <a data-filter="*" class="all filter" href="javascript:void(0)">
                        <span class="icon-list-1"></span> All Courses</a>
                </li><?php */
				
				$cats = vibe_get_option('featured_courses_grp_1');
                $cats = explode(',', $cats);
				
				
                foreach($cats as $cat){
					$term = get_term( $cat, $taxonomy );
						$color = get_field('intellipaat_cat_icons_color', $term);
						//$term_link = str_replace( WPLMS_COURSE_CATEGORY_SLUG, 'all-courses', get_term_link( $term ) );
					?>
                    <li id="<?php echo $term->slug ?>">
                        <a data-filter=".<?php echo $term->slug ?>" data-slug="<?php echo $term->slug ?>" class="filter" href="<?php echo esc_url( $current_page.$term->slug ) ?>/">
                        <span class="<?php echo get_field('intellipaat_cat_icons', $term)?> icon-crown" <?php if($color)  echo 'style="background-color:'.$color.';"'; ?> ></span> <?php echo $term->name ?></a>
                    </li>
               <?php
                }
				
				?>
                    <?php /*?> 
                <li>
                    <a data-filter=".fast-moving-courses" class="filter" href="<?php echo $current_page; ?>fast-moving-courses/">
                    <span class="icon-crown"></span> Fast Moving</a></li>
                <li id="new-courses">
                    <a data-filter=".new-courses" data-slug="new-courses" class="filter" href="<?php echo $current_page; ?>new-courses/">
                        <span class="icon-arrows-expand"></span> New 
                     </a>
                </li>
			   <?php $term = get_term( 92, $taxonomy ); 
					$color = get_field('intellipaat_cat_icons_color', $term);
				?>
                <li id="<?php echo $term->slug ?>">
                    <a data-filter=".<?php echo $term->slug ?>" data-slug="<?php echo $term->slug ?>" class="filter" href="<?php echo esc_url( $current_page.$term->slug ) ?>/">
                    <span class="<?php echo get_field('intellipaat_cat_icons', $term)?> icon-crown" <?php if($color)  echo 'style="background-color:'.$color.';"'; ?> ></span> <?php echo $term->name ?></a>
                </li>  <li>
                        <a data-filter=".self-paced" class="filter" href="javascript:void(0)">
                            <span class="icon-analytics-chart-graph"></span> Self-paced</a>
                  </li><?php */?>
          </ul>
          
          <ul class="nav nav-pills nav-stacked intell_filterable" style="clear:both; padding-top:15px; margin-bottom:15px;">
			  <?php
			  
                $cats = vibe_get_option('featured_courses_grp_2');
                $cats = explode(',', $cats);
    
                foreach($cats as $cat){
					$term = get_term( $cat, $taxonomy );
						$color = get_field('intellipaat_cat_icons_color', $term);
						//$term_link = str_replace( WPLMS_COURSE_CATEGORY_SLUG, 'all-courses', get_term_link( $term ) );
					?>
                    <li id="<?php echo $term->slug ?>">
                        <a data-filter=".<?php echo $term->slug ?>" data-slug="<?php echo $term->slug ?>" class="filter" href="<?php echo esc_url( $current_page.$term->slug ) ?>/">
                        <span class="<?php echo get_field('intellipaat_cat_icons', $term)?> icon-crown" <?php if($color)  echo 'style="background-color:'.$color.';"'; ?> ></span> <?php echo $term->name ?></a>
                    </li>
               <?php
                }
              ?>
          </ul>
          
	</div>
</div>

<div id="course_container_wrap" class="col-md-9 col-sm-8">
    <div id="course_container" class="filterable_course_container row" >
    	<?php filtered_course_loop($course_category); ?>
    </div>
    <div id="loading-course" class="load_grid" style="display:none;"><span style="background-position:30px center"> Loading... </span></div>
</div>
<?php  
	$nonce = wp_create_nonce("intellipaat_all_course_page_nonce");
    $link = admin_url('admin-ajax.php');
?>
<script>

var courseContainerMoved = 0;
function moveContainer(){
	jQuery("#course_container").detach().appendTo('.intell_filterable li.active');
	jQuery("#loading-course").detach().appendTo('.intell_filterable li.active');
	if(courseContainerMoved == 0) scrollToContainer(160);
	courseContainerMoved=1;
}

function restoreContainer(){
	jQuery("#course_container").detach().appendTo('#course_container_wrap');
	jQuery("#loading-course").detach().appendTo('#course_container_wrap');
	courseContainerMoved =0;
	scrollToContainer(210);
}
function scrollToContainer(margin){
	if(jQuery('#headertop').hasClass('fixed'))
		margin=margin-50;

	jQuery('body,html').animate({
	  scrollTop:jQuery('#course_container').offset().top -margin
	}, 1200);	
}

jQuery(window).load(function ($) {
	var $ = jQuery;
	$('.course_filterable').each(function(){
	var $container = $(this).find('.filterable_course_container'),
	$filtersdiv = $(this).find('.intell_filterable'),
	$checkboxes = $(this).find('.intell_filterable a');
	
	
	$container.imagesLoaded( function(){  
		$container.isotope({
		  itemSelector: '.course_items',
		  layoutMode: 'fitRows'
		}); 
	});
	
	$checkboxes.click(function(event){
		event.preventDefault();
		var me = $(this);
		
		if(!me.hasClass('courseLoaded')){
			$('#loading-course').show();
			me.addClass('loading');
			me.parent().addClass('expanding');
			//$.get( me.attr('href') ,function(data,status){
			$.get( 	'<?php echo $link ?>' , 
					{ action:"intellipaat_all_course_page_filterd", nonce:"<?php echo $nonce ?>", course_category:me.attr('data-slug') } 
				)
				.done(function(data){
					//$newItems = $(data).find('#course_container').html();
	
					$("#course_container").append( data ).isotope('reloadItems');
					$('#loading-course').fadeOut();
					me.removeClass('loading').addClass('courseLoaded').trigger('click');
					me.parent().removeClass('expanding').addClass('expanded');
					$container.imagesLoaded( function(){ 
						$container.isotope( 'reLayout' ); 
					});
				});
			//$("#course_container").load(me.attr('href')+" #course_container");
		}
		window.history.pushState( me.text() , me.text(), me.attr('href') );		
		$filtersdiv.find('.active').removeClass('active');
		var filters = me.attr('data-filter');
		$('.expanded').not(me.parent()).removeClass('expanded').addClass('collapsed');
		if(me.parent().hasClass('expanded') || me.parent().hasClass('collapsed'))
			me.parent().toggleClass('expanded').toggleClass('collapsed');
		me.parent().addClass('active');
		$container.isotope({filter: filters});
		if($(window).width() < 768){
			moveContainer();	
		}
		scrollToContainer(190);	
	});
	<?php if($course_category && $course_category != $default_cat_slug ){ ?>  
	$('.intell_filterable li#<?php echo $course_category ?>').addClass('active collapsed');
	$('.intell_filterable li#<?php echo $course_category ?> a').addClass('courseLoaded').trigger('click');
	<?php } else { ?>
	$('.intell_filterable a:first').parent().addClass('active collapsed');
	$('.intell_filterable a:first').addClass('courseLoaded').trigger('click');
	<?php } ?>
	
	});
	
	 $(window).resize(function(){
									
		if($(window).width() <768){
			if(courseContainerMoved == 0)
				moveContainer();
		}
		else if(courseContainerMoved == 1)
			 restoreContainer();
		
	});
	 
});
 


</script>