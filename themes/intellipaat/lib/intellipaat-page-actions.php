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
	$categories = get_the_category($postId);

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

	$intellipaat_recommended_courses = get_post_meta( $postId, 'intellipaat_recommended_courses',true );
	$countRelated = count($intellipaat_recommended_courses);
	if(isset($intellipaat_recommended_courses[0]) && $intellipaat_recommended_courses[0] != 'null'){
		$recommendStatus = true;
	}else{
		$recommendStatus = false;
	}
	echo '<aside id="related_course" class="widget"><h3 class="widget-title">Related Courses</h3><ul>';
	if($recommendStatus == false) {
		if( $my_query->have_posts() ) {
		
			while( $my_query->have_posts() ) {
				$my_query->the_post();
				$st 		= get_post_meta($my_query->post->ID,'vibe_students',true);
				$students 	= apply_filters('vibe_thumb_student_count','<strong>'.$st.' '.__('Students','vibe-customtypes').'</strong>');
				$jose_rating = get_post_meta($my_query->post->ID,'average_rating',true);
				$jose_rating_count = get_post_meta($my_query->post->ID,'rating_count',true);
				$jose_display_rating = '<div class="star-rating">';
				$featured_style = '';
				for($i=1;$i<=5;$i++){

					if(isset($jose_rating)){
					if($jose_rating >= 1){
					$jose_display_rating .='<span class="fill"></span>';
					}elseif(($jose_rating < 1 ) && ($jose_rating > 0.4 ) ){
					$meta .= '<span class="half"></span>';
					}else{
					$jose_display_rating .='<span></span>';
					}
					$jose_rating--;
					}else{
					$jose_display_rating .='<span></span>';
					}
				}
				$jose_display_rating =  apply_filters('vibe_thumb_rating',$jose_display_rating,$featured_style,$jose_rating);
				$jose_display_rating .= apply_filters('vibe_thumb_reviews','(&nbsp;'.(isset($jose_rating_count)?$jose_rating_count:'0').'&nbsp;'.__('REVIEWS','vibe-customtypes').'&nbsp;)',$featured_style).'</div>';
?>
				<li>

					<div class="col-md-4 col-sm-12 col-xs-12 padjust">
						<a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'>
						<?php echo get_the_post_thumbnail($my_query->post->ID); ?>
						</a>
					</div>
					<div class="col-md-8 col-sm-12 col-xs-12 padjust1">
						<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
					<div style='padding-top:0px;display:inline-block;width:46%;margin-top:17px;'><?php echo $students;?></div>
					<div style='padding-top:5px;display:inline-block;width:40%;margin-left:10px;'><?php echo $jose_display_rating;?></div>
					</div>
				</li>
<?php 
			}
			
		} 
	}else{
		$args_related = array(
			'post_type' => 'course',
			'post_status' => 'publish',
			'orderby' => 'rand',
			'post__in' => $intellipaat_recommended_courses,
			'post__not_in' => array ($postId),
			);
		$my_query_related = new wp_query( $args_related );
		while( $my_query_related->have_posts() ) {
			$my_query_related->the_post();
			$st 		= get_post_meta($my_query_related->post->ID,'vibe_students',true);
			$students 	= apply_filters('vibe_thumb_student_count','<strong>'.$st.' '.__('Students','vibe-customtypes').'</strong>');
			$jose_rating = get_post_meta($my_query_related->post->ID,'average_rating',true);
			$jose_rating_count = get_post_meta($my_query_related->post->ID,'rating_count',true);
			$jose_display_rating = '<div class="star-rating">';
			$featured_style = '';
			for($i=1;$i<=5;$i++){

				if(isset($jose_rating)){
				if($jose_rating >= 1){
				$jose_display_rating .='<span class="fill"></span>';
				}elseif(($jose_rating < 1 ) && ($jose_rating > 0.4 ) ){
				$meta .= '<span class="half"></span>';
				}else{
				$jose_display_rating .='<span></span>';
				}
				$jose_rating--;
				}else{
				$jose_display_rating .='<span></span>';
				}
			}
			$jose_display_rating =  apply_filters('vibe_thumb_rating',$jose_display_rating,$featured_style,$jose_rating);
			$jose_display_rating .= apply_filters('vibe_thumb_reviews','(&nbsp;'.(isset($jose_rating_count)?$jose_rating_count:'0').'&nbsp;'.__('REVIEWS','vibe-customtypes').'&nbsp;)',$featured_style).'</div>';
?>
			<li>

				<div class="col-md-4 col-sm-12 col-xs-12 padjust">
					<a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'>
					<?php echo get_the_post_thumbnail($my_query_related->post->ID); ?>
					</a>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12 padjust1">
					<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
				<div style='padding-top:0px;display:inline-block;width:46%;margin-top:17px;'><?php echo $students;?></div>
				<div style='padding-top:5px;display:inline-block;width:40%;margin-left:10px;'><?php echo $jose_display_rating;?></div>
				</div>
			</li>
<?php 
		}
	
	}
	echo '</ul></aside>';
	}
	wp_reset_query();
}



function jose_related_ebook($postId,$perPost){
	$categories = get_the_category($postId);
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
	$categories = get_the_category($postId);
	

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
		
		$intellipaat_recommended_questions = get_post_meta( $postId, 'interview_questions',true );
		$countRelated = count($intellipaat_recommended_questions);
		if(isset($intellipaat_recommended_questions[0]) && $intellipaat_recommended_questions[0] != 'null'){
			$recommendStatus = true;
		}else{
			$recommendStatus = false;
		}
		
		if($recommendStatus == false) {
		
			if( $my_query_iq->have_posts() ) {
				echo '<aside id="related_tutorial" class="widget"><h3 class="widget-title">Related Interview Questions</h3><ul>';
				while( $my_query_iq->have_posts() ) {
					$my_query_iq->the_post(); ?>
					<li>
					<div class="related_content">
					<i class="fa fa-arrow-right adjustSize"></i>
					<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
					</div>
					</li>
		<?php 
				}
				echo '</ul></aside>';			
			} 

		}else{
			$args_related = array(
			'post_type' => 'interview-question',
			'post_status' => 'publish',
			'orderby' => 'rand',
			'post__in' => $intellipaat_recommended_questions,
			'post__not_in' => array ($postId),
			);
			$my_query_related = new wp_query( $args_related );

			echo '<aside id="related_tutorial" class="widget"><h3 class="widget-title">Related Interview Questions</h3><ul>';
			while( $my_query_related->have_posts() ) {
				$my_query_related->the_post(); ?>
				<li>
					<div class="related_content">
					<i class="fa fa-arrow-right adjustSize"></i>
					<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
					</div>
				</li>
			<?php
			}
			echo '</ul></aside>';	
		}
	}
	wp_reset_query();
}

function jose_related_blog($postId,$perPost){
	
	$blog_db = new wpdb('intellipaat_blog', 'jKLv5JqVAtFFcKxE', 'intellipaat_alpha_blog', 'localhost');
	
	$link = mysql_connect('localhost', 'intellipaat_blog', 'jKLv5JqVAtFFcKxE');
	$selected = mysql_select_db("intellipaat_alpha_blog",$link);
	$blog_prefix_post = 'fpp7vnh3_posts p';
	$blog_prefix_term_r = 'fpp7vnh3_term_relationships r';
	$blog_prefix_term_t = 'fpp7vnh3_term_taxonomy t';
	$blog_prefix_post_meta = 'fpp7vnh3_postmeta m';
	$query = '
		SELECT p1.*, wm2.meta_value FROM fpp7vnh3_posts p1 LEFT JOIN fpp7vnh3_postmeta wm1 ON ( wm1.post_id = p1.id AND wm1.meta_value IS NOT NULL AND wm1.meta_key = "_thumbnail_id") LEFT JOIN fpp7vnh3_postmeta wm2 ON ( wm1.meta_value = wm2.post_id AND wm2.meta_key = "_wp_attached_file" AND wm2.meta_value IS NOT NULL) WHERE p1.post_status="publish" AND p1.post_type="post" ORDER BY p1.post_date DESC limit 5
	';
	$result = mysql_query($query,$link);
	echo '<aside id="related_blog" class="widget"><h3 class="widget-title">Related Articles</h3><ul>';	
	

	while ($row = mysql_fetch_array($result)) {
		$blogId = $row['ID'];
		$blogTitle = $row['post_title'];
		$blogUrl = $row['guid'];
		$attached_Url = $row['meta_value'];
		$result_attached = mysql_query($attach_query,$link);
		$rows1=mysql_num_rows($result_attached);
		$siteUrl = site_url( '/blog/wp-content/uploads/', 'http' );
		$displayImage = $siteUrl.$attached_Url;
		$imageFile = ABSPATH.'blog/wp-content/uploads/'.$attached_Url;
		if(!file_exists($imageFile)){
			$displayImage = get_bloginfo('template_directory').'/images/placeholder.png';
		}
	?>
		<li>
			<div class="col-md-4 col-sm-12 col-xs-12 padjust">
				<a href="<?php echo $blogUrl;?>" rel="bookmark" title="<?php echo $blogTitle; ?>" target='_blank'>
				<img src='<?php echo $displayImage;?>' />
				</a>
			</div>
			<div class="col-md-8 col-sm-12 col-xs-12 padjust1">
				<a href="<?php echo $blogUrl;?>" rel="bookmark" title="<?php echo $blogTitle; ?>" target='_blank'><?php echo  $blogTitle;; ?></a>
			</div>
		</li>
<?php
	}
	echo '</ul></aside>';
	wp_reset_query();
}

function jose_related_certification($postId,$perPost){
	$categories = get_the_category($postId);
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
			'meta_query'		=> array(
				array(
					'key'	 	=> 'intellipaat_course_certification',
					'value'	  	=> array(''),
					'compare' 	=> 'NOT IN',
				),
			),
			'post__not_in' => array ($postId),
			);
		$my_query = new wp_query( $args );

		if( $my_query->have_posts() ) {
			echo '<aside id="related_certification" class="widget"><h3 class="widget-title">Related Certification</h3><ul>';
			while( $my_query->have_posts() ) {
				$my_query->the_post();
				$st 		= get_post_meta($my_query->post->ID,'vibe_students',true);
				$students 	= apply_filters('vibe_thumb_student_count','<strong>'.$st.' '.__('Students','vibe-customtypes').'</strong>');
				$jose_rating = get_post_meta($my_query->post->ID,'average_rating',true);
				$jose_rating_count = get_post_meta($my_query->post->ID,'rating_count',true);
				$jose_display_rating = '<div class="star-rating">';
				$featured_style = '';
				for($i=1;$i<=5;$i++){

					if(isset($jose_rating)){
						if($jose_rating >= 1){
						$jose_display_rating .='<span class="fill"></span>';
						}elseif(($jose_rating < 1 ) && ($jose_rating > 0.4 ) ){
						$meta .= '<span class="half"></span>';
						}else{
						$jose_display_rating .='<span></span>';
						}
						$jose_rating--;
					}else{
						$jose_display_rating .='<span></span>';
					}
				}
				$jose_display_rating =  apply_filters('vibe_thumb_rating',$jose_display_rating,$featured_style,$jose_rating);
				$jose_display_rating .= apply_filters('vibe_thumb_reviews','(&nbsp;'.(isset($jose_rating_count)?$jose_rating_count:'0').'&nbsp;'.__('REVIEWS','vibe-customtypes').'&nbsp;)',$featured_style).'</div>';
		?>
				<li>

					<div class="col-md-4 col-sm-12 col-xs-12 padjust">
						<a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'>
						<?php echo get_the_post_thumbnail($my_query->post->ID); ?>
						</a>
					</div>
					<div class="col-md-8 col-sm-12 col-xs-12 padjust1">
						<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title(); ?>" target='_blank'><?php the_title(); ?></a>
					<div style='padding-top:0px;display:inline-block;width:43%;margin-top:17px;'><?php echo $students;?></div>
					<div style='padding-top:5px;display:inline-block;width:40%;margin-left:10px;'><?php echo $jose_display_rating;?></div>
					</div>


				</li>
			<?php }
			echo '</ul></aside>';
		} 

	
	}
}
?>