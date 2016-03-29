<?php


/**
* Adds upcoming batch table in course content using wplms_before_course_description action
*
*/



if(class_exists('WPLMS_Events_Interface')){
	function intellipaat_event_calendar($course=NULL) {
		global $post;
		$course_id = get_the_ID();
		$current_date = date('Y-m-d');
		
		$args = array( 
					  'post_type' => WPLMS_EVENTS_CPT,
				);		
		
		$args['meta_query']=array(
			'relation' => '"AND"',
		);
		$args['meta_query'][]=array(
			'key' => 'vibe_start_date',
			'compare' => '>',
			'value' => $current_date,
			'type' => 'DATE'
		);		
		/*$args['meta_query'][]=array(
			'key' => 'vibe_end_date',
			'compare' => '>',
			'value' => $current_date,
			'type' => 'DATE'
		);*/
		$args['meta_query'][]=array(
			'key' => 'vibe_event_course',
			'compare' => '=',
			'value' => get_the_ID(),
			'type' => 'DECIMAL'
		);
		$args['orderby']='meta_value';
		$args['order']='ASC';
		$args['meta_key'] = 'vibe_start_date';
	
		$eventdaysquery = new WP_Query( $args );
		
		if($eventdaysquery->have_posts()){
			echo '<h4><b>Upcoming Batches:</b> </h4>';
			echo '<table class="table upcoming_batches">
					<thead>
						<tr>
							<th>STARTS</th>
							<th>DURATION</th>
							<th class="remove_table_class">DAYS</th>
							<th>Time &nbsp;&nbsp;&nbsp; 
								<select id="time_zone">
									<option value="+5.50" data-notice="All timings are in Indian Standard Time zone ( GMT + 5:30 )" data-value="IST">IST</option>
									<option value="+0.00" data-notice="All timings are in Greenwich Mean Time zone ( GMT + 0:00 )" data-value="GMT">GMT</option>
									<option value="-4.00" data-notice="All timings are in Eastern Daylight Time zone ( GMT - 4:00 )" data-value="EDT">EDT</option>
									<option value="-5.00" data-notice="All timings are in Central Daylight Time zone ( GMT - 5:00 )" data-value="CDT">CDT</option>
									<option value="-7.00" data-notice="All timings are in Pacific Daylight Time zone ( GMT -7:00 )" data-value="PDT">PDT</option>
								</select>
							</th>
							<th> </th>
						</tr>
					</thead>
					<tbody class="context">';
		
			$check=vibe_get_option('direct_checkout');
			$check =intval($check);
			
			while ( $eventdaysquery->have_posts() ) {
				$eventdaysquery->the_post();
				$icon = get_post_meta($post->ID,'vibe_icon',true);
				$color = get_post_meta($post->ID,'vibe_color',true);
				$start_date = get_post_meta($post->ID,'vibe_start_date',true);
				$end_date = get_post_meta($post->ID,'vibe_end_date',true);
				$start_time =  get_post_meta(get_the_ID(),'vibe_start_time',true);
				$end_time =  get_post_meta(get_the_ID(),'vibe_end_time',true);
				$duration = get_post_meta(get_the_ID(),'intellipaat_course_duration',true);
				$days = get_post_meta(get_the_ID(),'intellipaat_course_days',true);
				$product=get_post_meta(get_the_ID(),'vibe_product',true);
				
				$product_link='';
				
				if(isset($check) &&  $check){
					$product_link  .= point_to_com_site(get_permalink($course_id)).'?type=onlineTraining&redirect';
				}
				else{
					$product_link = point_to_com_site(get_permalink($product));
				}
				
				if(isset($product) && $product)
					$product_link = '<a href="'.$product_link.'" class="add_to_cart_button">Register &rsaquo;&rsaquo;</a>';
				
				
				echo '<tr>
						<td><span class="date" rel="dateTime-'.get_the_ID().'" data-date="'.date('d M Y',strtotime($start_date)).'">'.date('dS, M',strtotime($start_date)).'</span></td>	
						<td>'.$duration.'</td>	
						<td>'.implode(', ',$days).'</td>	
						<td><span class="start_time" rel="dateTime-'.get_the_ID().'" data-start_time="'.$start_time.'">'.$start_time.'</span> - <label>To :</label> <span class="end_time" rel="dateTime-'.get_the_ID().'" data-end_time="'.$end_time.'">'.$end_time.'</span></td>
						<td>'.$product_link.'</td>				
				</tr>';
			}
			
			echo '</tbody></table>';
		}
		wp_reset_postdata();
		wp_enqueue_script('jquery-cookie');
		?>
        	 <script>
				var default_time = '<?php echo $_COOKIE['default_time'] ?>'; //load batch with defalut by cookie time zone preference
				   
				if(default_time == ''){
					default_time = 'IST';
				}
		
				function calcTime(myDateTime, offset, returnType) {
					d = new Date(Date.parse(myDateTime+' GMT+0530'));
					utc = d.getTime() + (d.getTimezoneOffset() * 60000);
					nd = new Date(utc + (3600000*offset));
					if(returnType == 'date' )
						return moment(nd).format('Do, MMM');	
					else if(returnType == 'time' )
						return moment(nd).format('hh:mm A');	
					else
						return nd.toLocaleString();		
				}
				
				jQuery(document).ready(function(){ 
					jQuery('#time_zone').change(function(){
						var TimezoneOffset = jQuery(this).val();
						jQuery('span.date').each(function(){
							var date = jQuery(this).data('date');
							var rel = jQuery(this).attr('rel');
							var start_dateTime = date +' '+jQuery('span.start_time[rel='+rel+']').data('start_time');
							var end_dateTime = date +' '+jQuery('span.end_time[rel='+rel+']').data('end_time');
							jQuery(this).text(calcTime(start_dateTime, TimezoneOffset, 'date'));
							jQuery('span.start_time[rel='+rel+']').text(calcTime(start_dateTime, TimezoneOffset, 'time'));
							jQuery('span.end_time[rel='+rel+']').text(calcTime(end_dateTime, TimezoneOffset, 'time'));
						});
						jQuery('span.notice').text(jQuery(this).find(':selected').data('notice'));
						jQuery.cookie('default_time', jQuery(this).find(':selected').data('value'), { expires: 30 , path: '/' });
					});				
					var defaultOffset = jQuery('option[data-value='+default_time+']').val();
					jQuery('#time_zone').val(defaultOffset).trigger('change');				
				});
			</script>
        <?php
	}
	
	add_action('wplms_before_course_description','intellipaat_event_calendar' );
}

/**
* Adds course details below course content using bp_after_course_home_content action
*
*/

function intellipaat_after_course_home_content(){
	$id =get_the_ID();
	
	$intellipaat_course_certification = get_field('intellipaat_course_certification', $id); 
	if(!empty($intellipaat_course_certification)){
		echo '<div id="certification" class="content hidden-xs"><h4 class="heading"><b>Certification</b> </h4>'. $intellipaat_course_certification.'</div>';
	}
	
	$intellipaat_selfpaced_vs_instructor_based = get_field('intellipaat_selfpaced_vs_instructor_based', $id); 
	if(!empty($intellipaat_selfpaced_vs_instructor_based)){
		echo '<div id="self-instructor" class="content hidden-xs"><h4 class="heading"><b>Self-Paced Vs Instructor LED Online</b> </h4>'.$intellipaat_selfpaced_vs_instructor_based.'</div>';	
	}
}

add_action('bp_after_course_home_content','intellipaat_after_course_home_content' );


/*function intellipaat_course_nav_menu_filter($menu){
	unset($menu['members']);
	return $menu;
}
//add_filter('wplms_course_nav_menu', 'intellipaat_course_nav_menu_filter');
*/

/**
*	To manupulate thumbnail_generator output for class view
*/

function intellipaat_course_duration(){
	global $post;
	/*$duration = get_post_meta($post->ID,'vibe_duration',true);
	if($duration)
		return '<span class="intelli_duration pull-right"><i class="icon-clock"> </i> '.$duration.' Hrs</span>';
	else
		return '';*/
		
	$key_feature = get_field('course_1_key_feature');
	if(!empty($key_feature)){
		//preg_match_all('!\d+!', $key_feature, $hours);
		$hours = str_replace(array('-','+'), array('',''), filter_var($key_feature, FILTER_SANITIZE_NUMBER_INT));
		if(is_numeric($hours))
			return '<span class="intelli_duration pull-right"><i class="icon-clock"> </i> '.$hours.' Hrs</span>';
	}
	return '';
}
add_filter( 'vibe_thumb_instructor_meta', 'intellipaat_course_duration' );

function intellipaat_thumb_student_count($val){
	return '<span class="intelli_students"><i class="icon-users"> </i>'.$val.'</span>';
}
add_filter( 'vibe_thumb_student_count', 'intellipaat_thumb_student_count', 10 , 1 );

function intellipaat_thumb_course_meta($val){
	$votes = '';
	$count=get_post_meta(get_the_ID(),'rating_count',true);
	if($count > 0)
		//$votes = '<meta itemprop="votes" content="'.$count.'">';
		
	$val = str_replace(array('Students', 'STUDENTS', '( 0 REVIEWS )'), 'Learners', $val);
	$val = str_replace(array('<div class="star-rating">', '( <strong itemprop="count">', '__ )', ' itemprop="size"'), array('<div class="star-rating hide">', '<a class="review link" href="#reviews"> <strong class="hide" itemprop="count">', ' </a>'.$votes, ' ' ), $val);
	//$val = preg_replace(array('/(//\d/ '.'REVIEWS//)/'), ' ', $val);
	return $val;
}
add_filter( 'wplms_course_meta', 'intellipaat_thumb_course_meta', 10 , 1 );
add_filter( 'vibe_thumb_student_count', 'intellipaat_thumb_course_meta', 10 , 1 );

function intellipaat_course_thumb_extras($output=''){
	global $post;
	$course_id = get_the_ID();
	$key_feature = "";		
	
	for($i=1; $i<=3 ; $i++){		
		$key_value = get_field("course_".$i."_key_feature");	

		//check if both key and value are set
		if(isset($key_value) && !empty($key_value) ){	
			$output .=  '<span>'.$key_value.'</span>';	
		}
														
	}
	if(isset($output) && !empty($output))
		$output =  '<div class="extra_details">'.$output .'</div>';	
	
	return $output;
}
add_filter( 'wplms_course_thumb_extras', 'intellipaat_course_thumb_extras' );

function intellipaat_add_to_cart_button_on_categorypage($out=''){
	global $post;
	$course_id = get_the_ID();
	$pid = get_post_meta($course_id,'vibe_product',true);
	if(!$pid)
		$pid = get_post_meta($course_id,'intellipaat_online_training_course',true);
	
	$out .='</div><div class="woocommerce center">';
	$product = new WC_Product( $pid );

	if ( $product->is_on_sale() ) 
		 $out .= apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );
		 
	if(!is_tax('course-cat'))
		return $out;
		
	if($url = do_shortcode('[add_to_cart_url id="'.$pid.'"]'))
		$out .= '<a href="'.$url.'" rel="nofollow" data-product_id="'.$pid.'" class="button course_cat_page">Add to cart</a>';
		
	return $out;
}
add_filter( 'wplms_course_thumb_extras', 'intellipaat_add_to_cart_button_on_categorypage' );


function intellipaat_thumb_reviews($val){
	return '';
}
add_filter( 'vibe_thumb_reviews', 'intellipaat_thumb_reviews', 10 , 1 );


function intellipaat_course_credits($course_credits, $course_id){
	$pid = get_post_meta($course_id,'vibe_product',true);
	if(!$pid ){
		$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
		if(isset($pid) && $pid !='' && function_exists('get_product')){
			
			$product = get_product( $pid );
			if(is_object($product))
			$course_credits = '<strong>'.$product->get_price_html().'</strong>';
		}
		
	}else{
		$course_credits = str_replace('<strong itemprop="offers" itemscope itemtype="http://schema.org/Offer">', '<strong>',$course_credits);	
	}
	return $course_credits;
}
add_filter( 'wplms_course_credits', 'intellipaat_course_credits', 10 , 2 );


/**
*	To manupulate all courses page
*/
  
add_filter('rewrite_rules_array', 'intellipaat_insertrules');  
// Adding fake pages' rewrite rules
function intellipaat_insertrules($rules)
{
	$newrules = array();
	$newrules['all-courses/?(.*)/?$'] = 'index.php?pagename=all-courses&course_category=$matches[1]';
  
	return $newrules + $rules;
}  
// Tell WordPress to accept our custom query variable
add_filter('query_vars', 'intellipaat_insertqv');
function intellipaat_insertqv($vars)
{
	array_push($vars, 'course_category');
	return $vars;
}

// Remove WordPress's default canonical handling function
 

add_filter('wp_head', 'intellipaat_rel_canonical');
function intellipaat_rel_canonical()
{
	global $wp_the_query;
	
	if (!is_page( 'all-courses' ))
		return;
	$course_category = get_query_var('course_category');
	
	if (!$id = $wp_the_query->get_queried_object_id())
            return;
  
	$link = trailingslashit(get_permalink($id));

	// Make sure fake pages' permalinks are canonical
	if (!empty($course_category))
		$link .= user_trailingslashit($course_category);

	//echo '<link rel="canonical" href="'.$link.'" />';
}



/***
*	Load addiional courses from other category using ajax method
**/

add_action("wp_ajax_intellipaat_all_course_page_filterd", "intellipaat_all_course_page_filterd_callback");
add_action("wp_ajax_nopriv_intellipaat_all_course_page_filterd", "intellipaat_all_course_page_filterd_callback");

function intellipaat_all_course_page_filterd_callback() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "intellipaat_all_course_page_nonce")) {
      exit("No naughty business please");
   } 
	$course_category = $_REQUEST['course_category'];
   	filtered_course_loop($course_category);
	die();
}

/***
*	Flush transient cache on some events
**/
add_action("save_post","intellipaat_filtered_course_cache");
add_action("wp_trash_post","intellipaat_filtered_course_cache");
add_action("untrash_post","intellipaat_filtered_course_cache");
function intellipaat_filtered_course_cache(){
    global $post;
	$post_type = get_post_type( $post );
	
	if($post_type != 'course' )
		return;
	intellipaat_flush_course_cache();
}
add_action('w3tc_flush_objectcache', 'intellipaat_flush_course_cache');
add_action('w3tc_flush_file', 'intellipaat_flush_course_cache');
add_action('w3tc_flush_memcached', 'intellipaat_flush_course_cache');
add_action('w3tc_flush_dbcache', 'intellipaat_flush_course_cache');
add_action('w3tc_flush', 'intellipaat_flush_course_cache');
add_action('w3tc_flush_all', 'intellipaat_flush_course_cache');
function intellipaat_flush_course_cache(){
	$terms = get_terms( 'course-cat' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			delete_transient('all_courses_'.$term->slug);
		}
	}		
	delete_transient('all_courses_new-courses');
	delete_transient('all_courses_fast-moving-courses');
	delete_transient('all_courses_home_page');
	delete_transient('browse_course_menu');
}

function filtered_course_loop($course_category){
	$taxonomy = 'course-cat';
	if($course_category == ''){
		$default_cat = vibe_get_option('default_courses_cat');
		$default_cat_obj = get_term( $default_cat, $taxonomy );
		$course_category = $default_cat_obj->slug;
	}
	
	
	$cached_course_category = get_transient( 'all_courses_'.$course_category ); 
	$cached_course_category = false;
	if ( false === $cached_course_category || current_user_can('edit_posts') || ((TLD =='us'|| TLD =='com') && is_user_logged_in())) {
		
		$cached_course_category = '';
		$class = $course_category ? $course_category : $default_cat_slug ;
		$posts= get_field('intellipaat_custom_category_order', get_term_by( 'slug', $course_category, $taxonomy )  ); //fetch custom order for course as category. Advanced fileds.
		
		if($posts)
		{		
			global $post;	
			foreach($posts as $post){
							
				$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
				
				if(function_exists('thumbnail_generator')){
					$cached_course_category .=  thumbnail_generator($post,'course','medium',1,1,1);
				}       	
				
				$cached_course_category .=  "</div>";	
			}			
		}
		else
		{
			$args = array(
				'showposts' =>  -1,
				'post_type' => 'course',
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'course-cat' => $course_category
			);
			
			/*if($course_category && $course_category != 'new-courses' && $course_category != 'fast-moving-courses')
				$args['course-cat']= $course_category;
			else if($course_category && $course_category == 'new-courses'){
				$args['order']			= 'ASC';
				$args['orderby']		= 'meta_value_num';
				$args['meta_key']		= 'intellipaat_order_new_course';
				$args['meta_query']		= array(
													array(
														'key'     => 'intellipaat_new_course',
														'value'   => 1,
														'compare' => '=',
													),
												);
			}
			else {      
				$args['order']			= 'ASC';
				$args['orderby']		= 'meta_value_num';
				$args['meta_key']		= 'intellipaat_order_fast_moving';
				$args['meta_query']		= array(
													array(
														'key'     => 'intellipaat_fast_moving',
														'value'   => 1,
														'compare' => '=',
													),
												);
			}*/
								
			$query = new WP_Query( $args );
			
			if ($query->have_posts()) :
				
			
						 
					while ( $query->have_posts() ) : $query->the_post(); 
						/*$terms = get_the_terms( get_the_ID(), 'course-cat' );
						foreach($terms as $term){
							$class .= $term->slug.' ';
						}*/
						/*$intellipaat_fast_moving_new = get_field('intellipaat_fast_moving_new');	
						if($intellipaat_fast_moving_new)
							$class .= implode(' ', $intellipaat_fast_moving_new);
						$pid=get_post_meta(get_the_ID(),'vibe_product',true);
						if($pid)
							$class .= 'self-paced';*/
					
						
						$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
						
							
						
						if(function_exists('thumbnail_generator')){
							$cached_course_category .=  thumbnail_generator($query->post,'course','medium',1,1,1);
						}       	
						
						$cached_course_category .=  "</div>";
						
			
					endwhile;	
			else :
			
					$cached_course_category .=  '<div class="'.$class.' course_items col-md-3 col-sm-6" >';
					$cached_course_category .= '<h2>We did\'t found any course matching your request.</h2>';
					$cached_course_category .=  "</div>";
					
			endif;	
			
		}
		
		wp_reset_postdata();	
		
		if(!is_user_logged_in())
			set_transient( 'all_courses_'.$course_category, $cached_course_category,  WEEK_IN_SECONDS );
	}
	
				//	echo  "<!--queries : ".  get_num_queries()." Seconds: ".timer_stop( 0 )."-->";
	
	echo $cached_course_category;
}


/*
*	Remove instructor list from course search page
*/
add_filter('wplms_course_search_selects','intellipaat_custom_search_args');
function intellipaat_custom_search_args($args){
 $args = 'instructors=0&cats=1&level=1';
return $args;
}


?>
