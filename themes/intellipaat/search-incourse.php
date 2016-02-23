<?php 
	
    global $wp_query; 
	$query = $meta_query_args = $wp_query->query;
	$exclude_ids = array();
	
	remove_filter( 'posts_search', 'search_by_title_only', 500 );
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$exclude_ids[] = get_the_ID();	
	endwhile;	endif;	wp_reset_postdata();
	
	
	/* 2nd Query ---- Meta Query*/
	$meta_query_args['post__not_in'] = $exclude_ids;
	
	unset($meta_query_args['s']);
	$meta_query_args['meta_key'] =  'intellipaat_curriculum';
	$meta_query_args['meta_value'] = $query['s'];
	$meta_query_args['meta_compare'] ='LIKE';
	$meta_query_args['posts_per_page'] = -1;
	
	$meta_query = new WP_Query( $meta_query_args );
	if ( $meta_query->have_posts() ) : while ( $meta_query->have_posts() ) : $meta_query->the_post();
		$exclude_ids[] = get_the_ID();	
	endwhile;	endif;	wp_reset_postdata();
	
	/** 3rd Query--- my query */
	$query['post__not_in'] = $exclude_ids;
	add_filter( 'posts_search', 'search_by_content_only', 500, 2 );
	$content_query = new WP_Query( $query );
	remove_filter( 'posts_search', 'search_by_content_only', 500 );
	
	
	
	$wp_query->posts = array_merge( $wp_query->posts, $meta_query->posts, $content_query->posts );
	$wp_query->post_count = $wp_query->post_count + $meta_query->post_count + $content_query->post_count;
	$wp_query->found_posts = $wp_query->found_posts + $meta_query->found_posts + $content_query->found_posts;
	
    $total_results = $wp_query->found_posts;
	
    get_header();
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php _e('Search Results for "', 'vibe'); the_search_query(); ?>"</h1>
                    <h5><?php echo $total_results.__(' results found','vibe');  ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php vibe_breadcrumbs(); ?>  
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="scontent">
            <?php
                $select_boxes = apply_filters('wplms_course_search_selects','instructors=1&cats=1&level=1');
               echo the_widget('BP_Course_Search_Widget',$select_boxes,array()); 
            ?>
        </div>
        <?php
            do_action('wplms_course_sidebar_hook');
        ?>
        <div class="search_results">
            <?php
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                //if($post->post_type == 'course'){
                    if(function_exists('thumbnail_generator')){
                        echo '<div class="col-md-3 clear4">'.thumbnail_generator($post,'course','medium',0,0,0).'</div>';
                    }else{
                       echo ' <div class="blogpost">
                            <div class="meta">
                               <div class="date">
                                <p class="day"><span>'.get_the_time('j').'</span></p>
                                <p class="month">'.get_the_time('M').'</p>
                               </div>
                            </div>
                            '.(has_post_thumbnail(get_the_ID())?'
                            <div class="featured">
                                <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
                            </div>':'').'
                            <div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
                                <h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                                <div class="cats">
                                    '.$cats.'
                                    <p>| 
                                    <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>
                                    </p>
                                </div>
                                <p>'.get_the_excerpt().'</p>
                                <a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
                            </div>
                        </div>';
                    }
                //}   
                endwhile;
                else:
                    echo '<h3>'.__('Sorry, No results found.','vibe').'</h3>';
                endif;
                pagination();
                wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php
get_footer();
?>