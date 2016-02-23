<?php 

add_shortcode( 'online_training_calendar', 'intellipaat_online_training_calendar' );

function intellipaat_online_training_calendar($atts){
	global $post;
	$current_page = get_permalink($post->ID);
	$found_posts=0;
	
	$current_date = date('Y-m-d');
	
	ob_start();
	$terms = get_terms('event-type');
	echo '[tabs style="" theme=]';
	foreach($terms as $term){
		$args = array( 
					  'post_type' => WPLMS_EVENTS_CPT,
					  'event-type' => $term->slug
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
		
		$args['orderby']='meta_value';
		$args['order']='ASC';
		$args['meta_key'] = 'vibe_start_date';
		
		$check=vibe_get_option('direct_checkout');
		$check =intval($check);
	
		$eventdaysquery = new WP_Query( $args );
		
		if($eventdaysquery->have_posts()){
			
			$found_posts += $eventdaysquery->found_posts;
			
			echo '[tab title="'.$term->name.'" icon=""]';
			echo '<div class="upcoming_batches">';			
			
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
					$course =get_post_meta(get_the_ID(),'vibe_event_course',true);
					
					$product_link='';
					
					if(isset($product) && $product){
						if(isset($check) &&  $check){
							$product_link  .= '<a class="button add_to_cart_button" href="'.point_to_com_site(get_permalink($course)).'?type=onlineTraining&redirect='.urlencode ($current_page).'">Add to cart</a>';
						}
						else{
							$product_link= str_replace('href="/','href="http://intellipaat.com/', do_shortcode('[add_to_cart id='.$product.' style=""]'));
						}
					}
					
					
						//$product_link= str_replace('Add to cart', 'Reserve My Seat', do_shortcode('[add_to_cart id='.$product.' style=""]'));
					/*if(isset($product) && $product)
						$product_link = '<a href="'.get_permalink($product).'" class="button">Reserve My Seat</a>';
					
					*/
					echo ' <div class="blogpost events">
							<div class="meta">
							   <div class="date">
								<p class="day"><span>'.date('j',strtotime($start_date)).'</span></p>
								<p class="month">'.date('M',strtotime($start_date)).'</p>
							   </div>
							</div>
							'.(has_post_thumbnail(get_the_ID())?'
							<div class="featured">
								<a href="'.point_to_com_site(get_permalink($course)).'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
							</div>':'').'
							<div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
								<h3>	<a href="'.point_to_com_site(get_permalink($course)).'">'.get_the_title().' </a>&nbsp;&nbsp;&nbsp;
								'. ( current_user_can('edit_post', $post->ID)  ? '<small><a href="'.get_edit_post_link().'"><i class="icon-pencil"></i> Edit This</a></small>' : '').'</h3>
								<div class="online_batch_meta">
									 <p> <label><i class="icon-calendar"></i> Start Date :</label> <span class="date" rel="dateTime-'.get_the_ID().'" data-date="'.date('d M Y',strtotime($start_date)).'">'.date('dS, M',strtotime($start_date)).'</span>
											<label> | <i class="icon-clock"></i> From :</label> <span class="start_time" rel="dateTime-'.get_the_ID().'" data-start_time="'.$start_time.'">'.$start_time.'</span> - <label>To :</label> <span class="end_time" rel="dateTime-'.get_the_ID().'" data-end_time="'.$end_time.'">'.$end_time.'</span>
											<label> | <i class="icon-clock"></i> Course Duration : </label> '.$duration.'
											<label> | <i class="icon-calendar"></i> Days : </label> '.implode(', ',$days).'
									</p>
								</div>
								'.$product_link.'
								</div>
							</div>';
			}
				echo '</div>';
			echo '[/tab]';
		}
		wp_reset_postdata();
	}
	echo '[/tabs]';
	$course_tabs = ob_get_contents();
	ob_end_clean();
		
		echo '<div id="buddypress">
				<div  id="course-directory-form" class="dir-form">	
					<div class="item-list-tabs" role="navigation">
						<ul>
							<li class="selected" id="course-all"><a>Upcoming Batches <span>'.$found_posts.'</span></a></li>
							
						</ul>
					</div>';
				echo '<div class="item-list-tabs" id="subnav" role="navigation">
					<span class="pull-right">
							<span class="text notice">All timings are in Indian Standard Time zone ( GMT + 5:30 )</span> &nbsp; &nbsp; &nbsp;
							<select id="time_zone">
								<option value="+5.50" data-notice="All timings are in Indian Standard Time zone ( GMT + 5:30 )" data-value="IST">IST</option>
								<option value="+0.00" data-notice="All timings are in Greenwich Mean Time zone ( GMT + 0:00 )" data-value="GMT">GMT</option>
								<option value="-4.00" data-notice="All timings are in Eastern Daylight Time zone ( GMT - 4:00 )" data-value="EDT">EDT</option>
								<option value="-5.00" data-notice="All timings are in Central Daylight Time zone ( GMT - 5:00 )" data-value="CDT">CDT</option>
								<option value="-7.00" data-notice="All timings are in Pacific Daylight Time zone ( GMT -7:00 )" data-value="PDT">PDT</option>
							</select>
					</span>
				</div>
			</div></div>';
	
			echo do_shortcode($course_tabs);
			
			wp_enqueue_script('jquery-cookie');
	?>
	<?php 
        $tld = end(explode('.', $_SERVER['HTTP_HOST']));
        $default_timezone = array(
                                        'us' => 'CDT',
                                        'com' => 'EDT',
                                        'in' => 'IST',
                                        'uk' => 'GMT',
                                  );
    ?>
    <script>
		var default_time = '<?php echo isset($_COOKIE['default_time']) ? $_COOKIE['default_time'] : $default_timezone[$tld] ?>'; //load batch with defalut by cookie time zone preference
           
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
	//return $output;
}

/*

| <label><i class="icon-wallet-money"></i></label><span class="amount">$ 50</span>
												| '.$product_link.' | '.do_shortcode('[add_to_cart_url id='.$product.']').' 
$output .=  '<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>STARTS</th>
						<th>DURATION</th>
						<th class="remove_table_class">DAYS</th>
						<th>Time</th>
						<th>
							<div class="btn-group dropdownmenuselect  dropdown-menu-currency">
								<a href="javascript:void(0)" data-toggle="dropdown" class="dropdownmenuselectanchor dropdownmenuselectanchor2"><span class="">PRICE</span> (<strong class="curreny_appear">USD</strong><span>▼</span>)
								</a>
								<ul id="currency" class="dropdown-menu">
									<li id="INR"><a href="javascript:void(0)">INR</a></li>
									<li id="USD"><a href="javascript:void(0)">USD</a></li>
								</ul>
							</div>
						</th>
					</tr>
				</thead>
				<tbody class="context">';*/
			/*$output .=  '<tr>
								<td> <div class="featured"><h3>'.get_the_title().'</h3>'.get_the_post_thumbnail(get_the_ID(),'small').'</div></td>
								<td>'.date('dS, M',strtotime($start_date)).'</td>	
								<td>'.$duration.'</td>	
								<td>'.implode(', ',$days).'</td>	
								<td>'.$start_time.' - '.$end_time.'</td>	
								<td></td>	
								<td><a></a></td>
						
						</tr>';*/
?>