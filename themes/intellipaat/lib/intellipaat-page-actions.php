<?php

function jose_page_category() {  
	register_taxonomy_for_object_type('post_tag', 'page'); 
	register_taxonomy_for_object_type('category', 'page'); 
 
}
add_action( 'admin_init', 'jose_page_category' );

function jose_extractUTubeVidId($url){
    $vid_id = "";
    $flag = false;
    if(isset($url) && !empty($url)){
        $parts = explode("?", $url);
        if(isset($parts) && !empty($parts) && is_array($parts) && count($parts)>1){
            $params = explode("&", $parts[1]);
            if(isset($params) && !empty($params) && is_array($params)){
                foreach($params as $param){
                    $kv = explode("=", $param);
                    if(isset($kv) && !empty($kv) && is_array($kv) && count($kv)>1){
                        if($kv[0]=='v'){
                            $vid_id = $kv[1];
                            $flag = true;
                            break;
                        }
                    }
                }
            }
        }
        if(!$flag){
            $needle = "youtu.be/";
            $pos = null;
            $pos = strpos($url, $needle);
            if ($pos !== false) {
                $start = $pos + strlen($needle);
                $vid_id = substr($url, $start, 11);
                $flag = true;
            }
        }
    }
    return $vid_id;
}

function jose_related_course($postId,$perPost){
	$categories = get_the_category($orig_post);

	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category){ 
		$catTermId = get_term_by('slug', $individual_category->slug, 'course-cat');
		$category_ids[] = $catTermId->term_id;
	}

	$args = array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => $perPost, // you may edit this number
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'course-cat',
                'field' => 'id',
                'terms' => $category_ids
            )
        ),
        'post__not_in' => array ($postId),
        );
	$my_query = new wp_query( $args );
	

	echo '<aside id="related_course" class="widget"><h3 class="widget-title">Related Courses</h3><ul>';
	if( $my_query->have_posts() ) {
	
	while( $my_query->have_posts() ) {
	$my_query->the_post(); ?>
	<li>
	<a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'>
	<?php the_post_thumbnail( 'related-course' ); ?>
	</a>
	<div class="related_content">
	<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
	</div>
	</li>
	<?php }
	
	} 
	echo '</ul></aside>';
	}

	wp_reset_query();
}

function jose_related_ebook($postId,$perPost){
	$categories = get_the_category($orig_post);
	$catName = $categories[0]->slug;

	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

	echo '<aside id="related_ebooks" class="widget"><h3 class="widget-title">Related Ebook</h3><ul>';
	echo "<li><a href='".site_url()."?post_type=course&course-cat=".$catName."&s=ebook' title='ebook' target='_blank'><img src='".get_stylesheet_directory_uri()."/images/ebook.jpg' alt='ebook' /></a></li>";
	echo '</ul></aside>';
	}

}

function jose_related_tutorial($postId,$perPost){
	$categories = get_the_category($orig_post);

	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category){ 
		$catTermId_tutorial = get_term_by('slug', $individual_category->slug, 'tuts-category');
		$catTermId_iq = get_term_by('slug', $individual_category->slug, 'iq-category');
		$category_ids_tutorial[] = $catTermId_tutorial->term_id;
		$category_ids_iq[] = $catTermId_iq->term_id;
	}

	$args = array(
        'post_type' => 'tutorial',
        'post_status' => 'publish',
        'posts_per_page' => $perPost, // you may edit this number
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'tuts-category',
                'field' => 'id',
                'terms' => $category_ids_tutorial
            )
        ),
        'post__not_in' => array ($postId),
        );

	$args_iq = array(
        'post_type' => 'interview-question',
        'post_status' => 'publish',
        'posts_per_page' => $perPost, // you may edit this number
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'iq-category',
                'field' => 'id',
                'terms' => $category_ids_iq
            )
        ),
        'post__not_in' => array ($postId),
        );
	$my_query = new wp_query( $args );
	$my_query_iq = new wp_query( $args_iq );
	echo '<aside id="related_tutorial" class="widget"><h3 class="widget-title">Related IQ & Tutorials</h3><ul>';
	if( $my_query_iq->have_posts() ) {
	
	while( $my_query_iq->have_posts() ) {
	$my_query_iq->the_post(); ?>
	<li>
	<div class="related_content">
	<i class="fa fa-pencil-square-o adjustSize"></i>
	<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
	</div>
	</li>
	<?php }
	
	} 

	if( $my_query->have_posts() ) {
	
	while( $my_query->have_posts() ) {
	$my_query->the_post(); ?>
	<li>
	<div class="related_content"><i class="fa fa-book adjustSize"></i>
	<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
	</div>
	</li>
	<?php }
	
	} 
	echo '</ul></aside>';
	}

	wp_reset_query();
}

function jose_related_blog($postId,$perPost){
	$categories = get_the_category($orig_post);
	$tags = wp_get_post_tags($postId);
	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category){ 
		$catTermId = get_term_by('slug', $individual_category->slug, 'course-cat');
		$category_ids[] = $catTermId->term_id;
	}

	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
	'tag__in' => $tag_ids,
	'post__not_in' => array($postId),
	'posts_per_page'=>$perPost, // Number of related posts to display.
	'caller_get_posts'=>1
	);
	$my_query = new wp_query( $args );
	echo '<aside id="related_blog" class="widget"><h3 class="widget-title">Related Blog</h3><ul>';
	if( $my_query->have_posts() ) {
	$count = 1;
	while( $my_query->have_posts() ) {
	$my_query->the_post(); ?>
	<li>
	<div class="related_content"><span class='badge'><?php echo $count;?></span>
	<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
	</div>
	</li>
	<?php 
	$count++;
	}
	
	echo '</ul></aside>';
	}


	}

	wp_reset_query();
}
?>