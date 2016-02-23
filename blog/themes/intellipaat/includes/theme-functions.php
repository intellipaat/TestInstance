<?php 


register_nav_menus( array(

		'country' => __('Country Menus')
) );


function intellipaat_browse_course_menu($cats){
	
	$browse_course_menu = get_transient( 'browse_course_menu' );
	if ( false === $browse_course_menu || current_user_can('edit_posts') ) {
		
		$taxonomy = 'course-cat';
		
		ob_start();
		echo '<div id="browse_courses" class="cats-dropdown cats-toggle">';
			echo '<div class="dropdown-toggle" data-toggle="dropdown">
						<a href="#" class="white-link fl">
							<span class="nav-bars alignleft "><span class="nav-one"></span><span class="nav-two"></span><span class="nav-three"></span></span>
							<span class="text fl"> Browse Courses</span>
						</a>
				</div>';
	
			if($cats)
				$cats = explode(',', $cats);
			else 
				$cats = get_terms($taxonomy , array('orderby' => 'id'));
	
			$no_of_cats = count($cats);
			$no_of_courses_shown_in_cat = $no_of_cats-1;
			echo '<div class="dropdown-menu" >';
				echo '<ul class="dropdown-menu-list">';
				/*if(is_user_logged_in()){
					echo '<li>
								<a class="main-cat" href="'.site_url('#Discover_Courses').'">
									<i class="icon-rocket cat-icon"></i>
									<span>Recommended for You</span>
								</a>
							</li>';
					echo '<li class="divider"></li>';
				}*/
				foreach($cats as $cat){
					$term = get_term( $cat, $taxonomy );
					echo '<li  data-submenu-id="submenu-'.$term->slug.'"><a class="main-cat menu-item" href="javascript:void(0)" title="'.$term->name.'" ><span>'.$term->name.'</span><i class="icon-arrow-1-right arr"></i></a>';
					$courses= get_field('intellipaat_custom_category_order', get_term_by( 'slug', $term->slug , $taxonomy )  );
					/*$courses = get_posts(array(
									'posts_per_page' => -1,
									'post_type' => 'course',
									$taxonomy => $term->slug,
									'orderby' => 'menu_order',
									'order' => 'ASC'
								));*/
					$no_of_courses = count($courses );
					$box_width = 300*(intval( $no_of_courses/$no_of_courses_shown_in_cat)+1)+30;
					$count = 0;
						
						echo '<div id="submenu-'.$term->slug.'" class="dropdown-menu sub" style="width:'.$box_width.'px">';
						echo '<h4 class="heading">All '.$term->name.' courses</h4>';
						foreach ( $courses as $course ) : 
							if($count%$no_of_courses_shown_in_cat == 0)
								echo '<ul class="browse-sub-menu">';
	
							echo '<li>
									<a href="'.get_permalink($course->ID).'">'.get_the_title($course->ID).'</a>
								</li>'; 
							$count++;
	
							if($count%$no_of_courses_shown_in_cat == 0 || $count == $no_of_courses)
								echo '</ul>';
						endforeach; 
	
						echo '</div>';
					wp_reset_postdata();
					echo '</li>';
				}
				echo '</ul>';
			echo '</div>';
		echo '</div>';
		
		$browse_course_menu = ob_get_contents();
		ob_end_clean();
		
		if(!is_user_logged_in())
			set_transient( 'browse_course_menu', $browse_course_menu,  YEAR_IN_SECONDS );
	}
	echo $browse_course_menu;
}

function intellipaat_jump_to(){
	$taxonomy = 'course-cat';
	$cats = vibe_get_option('browse_courses');
	
	if($cats)
			$cats = explode(',', $cats);
	else 
		$cats = get_terms($taxonomy , array('orderby' => 'id'));
		
	echo '<ul class="jump_to_menu clearfix"><p class="fl">Jump to :</p>';
	foreach($cats as $cat){
		$term = get_term( $cat, $taxonomy );
		echo '<li><a href="'.all_course_page_link().$term->slug.'/" title="'.$term->name.'" >'.$term->name.'</a>';
	}
	echo '</ul>';
}

function featured_reviews(){
	
	$output = " ";
	
	 $args = array(
        'number'=>2,
        'offset'=>0,
		'post_type' => 'course',
        'status'=>'approve',
        'order'=>'DESC',
        'search'=>'linkedin',
		'meta_query'=> array(
									'relation' => 'AND',
									array(
										'key'     => 'review_title',
										'value'   => '',
										'compare' => '!='
									),
									array(
										'key'     => 'review_rating',
										'value'   => '5',
										'type' => 'numeric',
										'compare' => '='
									)
							),
    );
	
			
			foreach( get_comments($args) as $comment ){			
				
					$rating ='';
					$url = ('http://' == $comment->comment_author_url) ? '' : $comment->comment_author_url;
						$url = esc_url( $url, array('http', 'https') );
						if( $url ) {
								$url  = '<div class="linkedin fr">Follow Me on <a class="linkedin_url" rel="nofollow noindex" href="' . esc_attr( $url  ) . '">LinkedIn</a></div>';
						}  
						else
							$url ='';
						  
					$commenttitle = get_comment_meta($comment->comment_ID, 'review_title', true );
					if( $commentrating = get_comment_meta( $comment->comment_ID, 'review_rating', true ) ) {
					  $rating .= '<div class="comment-rating star-rating fr">';
					  for($i=0;$i<5;$i++){
						if($commentrating > $i)
						  $rating .='<span class="fill"></span>';
						else
						  $rating .='<span></span>';
					  }
					  $rating .='</div>'; 
					}  
					
					$output .= '<div class="col-md-6 col-sm-6"> ';
									 $output .= '<div class="review-body" id="review-'.$comment->comment_ID.'">
													<div class="review-header clearfix">'.( ( $commenttitle) ? '<strong>' . esc_attr( $commenttitle ) . '</strong>' : '').$rating.'</div>
													<p class="comment-content">'.$comment->comment_content.'</p>
								
										<div class="comment-author vcard clearfix">
											<cite class="fn">-- '.apply_filters( 'comment_author',$comment->comment_author ) .'</cite>
											'.$url .'
										</div> 		
										<div class="author-img img-rounded">
										'.get_avatar( $comment->comment_author_email , 50 ).'			
										</div>								
									</div>'; 
					$output .= '</div> '; 
					
			}
					
	
		
	
	return $output;
}

function intellipaat_recent_posts(){
	global $post;
	$args = array( 'posts_per_page' => 3);

	$myposts = get_posts( $args );
	
	if($myposts){ ?>
        <div id="recent-posts" class="clearfix">
            <h3 class="footertitle col-md-12">Recent Posts</h3>
            <?php foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
                <div class="col-sm-4 footer_posts">
                    <?php if ( has_post_thumbnail() ){ ?>
                            <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail(array(120,71), array('class'=>'alignleft img-responsive'));  ?>
                            </a>
                    <?php } ?>
                    <h5 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <div class="excerpt">
                        <?php the_excerpt(); ?>
                         <a class="read-more link fr" href="<?php the_permalink(); ?>">Read More</a>
                    </div>
            	</div>
            <?php endforeach; ?>
        </div>
	<?php }
	wp_reset_postdata();
}

/*
*	Custom function to call sugarcrm api
*/

function intellipaat_LoginToSugarCRM()
{	
	$SugarUser = vibe_get_option('sugarCRM_api_user');
	$SugarPass = vibe_get_option('sugarCRM_api_pass');
	$login_parameters = array(	 "user_auth"=>array(  "user_name"	=> $SugarUser,
													  "password"	=> md5($SugarPass),
													  "version"		=> "1"
												   ),
								 "application_name"	=>	"RestTest",
								 "name_value_list"	=>	array()
							);
																						
	$SugarSessData = SugarCRM_API_call("login", $login_parameters);
	return $SugarSessData->id;
}
function SugarCRM_API_call($method, $parameters)
{
	$url = vibe_get_option('sugarCRM_api_url');
	$htaccess_protected = vibe_get_option('sugarCRM_htaccess_protected');
	
	if($url == '') return null;

	$curl_request = curl_init();
	curl_setopt($curl_request, CURLOPT_URL, $url);
	if($htaccess_protected  == 1)
	{		
		$htaccess_user = vibe_get_option('sugarCRM_htaccess_user');
		$htaccess_pass = vibe_get_option('sugarCRM_htaccess_pass');
		curl_setopt($curl_request, CURLOPT_USERPWD, $htaccess_user.":".$htaccess_pass);
		curl_setopt( $curl_request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
	}
	curl_setopt($curl_request, CURLOPT_POST, 1);
	curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($curl_request, CURLOPT_HEADER, 1);
	curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
	
	$jsonEncodedData = json_encode($parameters);

	$post = array(
		 "method" => $method,
		 "input_type" => "JSON",
		 "response_type" => "JSON",
		 "rest_data" => $jsonEncodedData
	);

	curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
	$result = curl_exec($curl_request);
	if ( $error = curl_error($curl_request) )
	{
		echo 'ERROR: ',$error;
		echo "<br />";
		echo curl_error($curl_request);
		echo "<br />";
		echo curl_errno($curl_request);
	}
	
	curl_close($curl_request);
	$result = explode("\r\n\r\n", $result, 2);
	$response = json_decode($result[1]);

	return $response;
}

function intellipaatCRM_LeadToMail( $session_id ='', $lead_id , $courses){
	
	$session_id =  $session_id ? $session_id  : intellipaat_LoginToSugarCRM();
	
	$courses = is_array($courses) ? $courses : explode(',',$courses);
	
	foreach($courses as $course){
		
		$get_mailprocess_details = array(
			'session' => $session_id,
			'module_name' => 'MP_MailProcess',
			'query' => "mp_mailprocess.course='".$course."' AND mp_mailprocess.order_by=1",
			'order_by' => "order_by asc ",
			'offset' => '0',
			'select_fields' => array('id','name', 'send_hours', 'order_by','course','emailtemplate_id_c'),
			'link_name_to_fields_array' => array(),
			'max_results' => '1', //The maximum number of results to return.        
			'deleted' => '0', //To exclude deleted records
			'Favorites' => false, //If only records marked as favorites should be returned.
		 ); 
		
		$mailprocess_result = SugarCRM_API_call('get_entry_list', $get_mailprocess_details); 
		
		if($mailprocess_result->result_count > 0){
			 $set_lead_mail = array(
			 'session' => $session_id,
			 'module_name' => 'MP_LeadToMail',
			 "name_value_list" => array(
				array("name" => "lead_id_c","value" => $lead_id),
				array("name" => "order_by","value" => 1),
				array("name" => "course","value" => $course),
				array("name" => "next_template_date","value" => date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." + ".$mailprocess_result->entry_list[0]->name_value_list->send_hours->value." hours"))),
				array("name" => "emailtemplate_id_c","value" => $mailprocess_result->entry_list[0]->name_value_list->emailtemplate_id_c->value),
				),
			);
			
			SugarCRM_API_call('set_entry', $set_lead_mail);
		}
		
	}
}

function checkMyInput($myInput){
	return htmlspecialchars(stripslashes(trim($myInput)));
}

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

/*
function logout_old_session_by_userid($user_login) {
	$user = get_user_by('login',$user_login);
	// get all sessions for user with ID $user_id
	$sessions = WP_Session_Tokens::get_instance( $user->ID );
 
	// we have got the sessions, destroy them all!
	$sessions->destroy_all();
    // your code
}
add_action('wp_login', 'logout_old_session_by_userid');
*/
/**
*	ZohoCRM_API_call
*/
/*function ZohoCRM_API_call($xml){
		//var_dump($xml); 
		$authtoken = vibe_get_option('zoho_api_key');
		$url ="https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
		$data="authtoken=".$authtoken."&scope=crmapi&xmlData=".$xml;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 5);
		
		//Execute cUrl session
		$response = curl_exec($ch);
		curl_close($ch);	
		//var_dump($response); die();
}


function intellipaat_moodle_url(){
	
	return vibe_get_option('moodle_api_url');
	
}

function intellipaat_moodle_api_url(){
	
	$api_key = vibe_get_option('moodle_api_key');
	
	return intellipaat_moodle_url().'webservice/soap/server.php?wsdl=1&wstoken='.$api_key ;
	
}

function intellipaat_moodle_course_url($course_id=''){
	
	return intellipaat_moodle_url().'course/view.php?id='.$course_id;
}*/
?>