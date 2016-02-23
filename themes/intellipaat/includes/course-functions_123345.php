<?php 


function point_to_com_site($link){
	return str_replace(array('http://i','http://m.b','.in','.uk'),array('https://i','http://b','.com','.com'), $link );
}

function all_course_page_id(){
	$pages=get_option('bp-pages');
	return $pages['course'] ;
}

function all_course_page_link(){
	return get_permalink( all_course_page_id() );
}


function intellipaat_course_offer($id){
	
	$global_course_offer = vibe_get_option('global_course_offer');
	if($global_course_offer) 
		intellipaat_print_offer($global_course_offer);
		
	$course_offer = get_field('intellipaat_course_offer', $id); 
	if($course_offer) 
		intellipaat_print_offer($course_offer);
}

function intellipaat_print_offer($offer){
	echo "<div class='offer-wrapper'><span class='offer-title label label-success'>Offer :</span> <span class='offer-text'>".$offer."</span></div>";
}
function intellipaat_print_offer_newblock(){    
	echo "<div class='offer-newone' style='margin-bottom:10px'><img width='310px' src='".site_url()."/wp-content/themes/intellipaat/images/G3&more2.png'  /><span style='top: 5px; padding-left: 70px; ' class='hdmaito hidden-mobile'> <a href='mailto:sales@intellipaat.com' style='text-transform:lowercase;'> <i style='    padding-left: 40px;' class='icon-letter-mail-1 mail'></i><span>sales@intellipaat.com</span> </a> </span></div><div class='offer-newone'><a href='".site_url()."/our-business/' target='_blank' ><img width='310px'  src='".site_url()."/wp-content/themes/intellipaat/images/G3&more.png'  /></a></div>";
}

function intellipaat_selfpaced_course_button($id=NULL){
  global $post;
  $rel = (TLD == 'com' ? '' : 'rel="nofollow"') ;
  if(isset($id) && $id)
    $course_id=$id;
   else 
    $course_id=get_the_ID();

  // Free Course
   $free_course= get_post_meta($course_id,'vibe_course_free',true);

	if(!is_user_logged_in() && vibe_validate($free_course)){
		echo apply_filters('wplms_course_non_loggedin_user','<a href="#login-form" class="course_button button full popup-with-form myajax_crm">'.apply_filters('wplms_take_this_course_button_label',__('Take Self-Paced Course','vibe'),$course_id).'</a>'); 
		return;
	}

    $take_course_page_id=vibe_get_option('take_course_page');

    if(function_exists('icl_object_id'))
      $take_course_page_id = icl_object_id($take_course_page_id, 'page', true);

   $take_course_page=get_permalink($take_course_page_id);
   $user_id = get_current_user_id();

   do_action('wplms_the_course_button',$course_id,$user_id);

   $coursetaken=get_user_meta($user_id,$course_id,true);
   $auto_subscribe = 0; 
   if(vibe_validate($free_course) && is_user_logged_in() && (!isset($coursetaken) || !is_numeric($coursetaken))){
      $auto_subscribe = 1;
   }
   $auto_subscribe = apply_filters('wplms_auto_subscribe',$auto_subscribe,$course_id);
   
   if($auto_subscribe){

      $duration=get_post_meta($course_id,'vibe_duration',true);
      $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
      
      $new_duration = time()+$course_duration_parameter*$duration; //parameter 86400

      $new_duration = apply_filters('wplms_free_course_check',$new_duration);
      update_user_meta($user_id,$course_id,$new_duration);
      bp_course_update_user_course_status($user_id,$course_id,0);
      $group_id=get_post_meta($course_id,'vibe_group',true);
      if(isset($group_id) && $group_id !=''){
        groups_join_group($group_id, $user_id );
      }
      $coursetaken = $new_duration;      
   }

   if(isset($coursetaken) && $coursetaken && is_user_logged_in()){   // COURSE IS TAKEN & USER IS LOGGED IN
     
       
         if($coursetaken > time()){  // COURSE ACTIVE

            $course_user= bp_course_get_user_course_status($user_id,$course_id); // Validates that a user has taken this course
            
            $new_course_user = get_user_meta($user_id,'course_status'.$course_id,true); // Remove this line in 1.8.5

            if((isset($course_user) && is_numeric($course_user)) || (isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in())){ // COURSE PURCHASED SECONDARY VALIDATION
             echo '<form action="'.apply_filters('wplms_take_course_page',$take_course_page,$course_id).'" method="post">';

                if(isset($new_course_user) && is_numeric($new_course_user) && $new_course_user){ // For Older versions
                    switch($course_user){
                    case 1:
                      echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('START COURSE','vibe').'">'; 
                      wp_nonce_field('start_course'.$user_id,'start_course');
                    break;
                    case 2:  
                      echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('CONTINUE COURSE','vibe').'">';
                      wp_nonce_field('continue_course'.$user_id,'continue_course');
                    break;
                    case 3:
                      echo '<a href="#" class="full button">'.__('COURSE UNDER EVALUATION','vibe').'</a>';
                    break;
                    case 4:
                      $finished_course_access = vibe_get_option('finished_course_access');
                      if(isset($finished_course_access) && $finished_course_access){
                        echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('FINISHED COURSE','vibe').'">';
                        wp_nonce_field('continue_course'.$user_id,'continue_course');
                      }else{
                        echo '<a href="'.apply_filters('wplms_finished_course_link','#',$course_id).'" class="full button">'.__('COURSE FINISHED','vibe').'</a>';
                      }
                    break;
                    default:
                      $course_button_html = '<a class="course_button button">'.__('COURSE ENABLED','vibe').'<span>'.__('CONTACT ADMIN TO ENABLE','vibe').'</span></a>';
                      echo apply_filters('wplms_default_course_button',$course_button_html,$user_id,$course_id,$course_user);
                    break;
                  }  
                }else{
                  switch($course_user){
                    case 0:
                      echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('START COURSE','vibe').'">'; 
                      wp_nonce_field('start_course'.$user_id,'start_course');
                    break;
                    case 1:  
                      echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('CONTINUE COURSE','vibe').'">';
                      wp_nonce_field('continue_course'.$user_id,'continue_course');
                    break;
                    case 2:
                      echo '<a href="#" class="full button">'.__('COURSE UNDER EVALUATION','vibe').'</a>';
                    break;
                    default:
                      $finished_course_access = vibe_get_option('finished_course_access');
                      if(isset($finished_course_access) && $finished_course_access){
                        echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('FINISHED COURSE','vibe').'">';
                        wp_nonce_field('continue_course'.$user_id,'continue_course');
                      }else{
                        echo '<a href="'.apply_filters('wplms_finished_course_link','#',$course_id).'" class="full button">'.__('COURSE FINISHED','vibe').'</a>';
                      }
                    break;
                  }
                }
                
             
             echo  '<input type="hidden" name="course_id" value="'.$course_id.'" />';
             
             echo  '</form>'; 
            }else{ 
                  $pid=get_post_meta($course_id,'vibe_product',true); // SOME ISSUE IN PROCESS BUT STILL DISPLAYING THIS FOR NO REASON.
                  echo '<a href="'.get_permalink($pid).'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'.__('COURSE ENABLED','vibe').'<span>'.__('CONTACT ADMIN TO ENABLE','vibe').'</span></a>';   
            }
      }else{ 
              $pid=get_post_meta($course_id,'vibe_product',true);
              $pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
              if(is_numeric($pid)){
                $pid=get_permalink($pid);
                $check=vibe_get_option('direct_checkout');
                $check =intval($check);
                if(isset($check) &&  $check){
                  $pid .= '?redirect';
                }
              }
              echo '<a href="'.$pid.'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'.__('Course Expired','vibe').'&nbsp;<span>'.__('Click to renew','vibe').'</span></a>';   
      }
    
   }else{
      $pid=get_post_meta($course_id,'vibe_product',true);
      $pid=apply_filters('wplms_course_product_id',$pid,$course_id,0);
      /*if(is_numeric($pid) && get_post_type($pid) == 'product'){
        $pid=get_permalink($pid);
        $check=vibe_get_option('direct_checkout');
        $check =intval($check);
        if(isset($check) &&  $check){
          $pid .= '?redirect';
        }
      }*/
      
      $extra ='';
      //if(isset($pid) && $pid){
	  if(is_numeric($pid) && get_post_type($pid) == 'product'){		
			$product = get_product( $pid );
			if(is_object($product))
			$credits = $product->get_price_html();
		  	/*** Removed direct checkout check
			$product_link = point_to_com_site(get_permalink(get_the_ID()));
			$check=vibe_get_option('direct_checkout');
			$check =intval($check);
			if(isset($check) &&  $check){
			  $product_link  .= '?type=selfPaced&redirect';
			}
			else{
				$product_link = point_to_com_site(get_permalink($pid));
			}*/
			
			preg_match_all('/<span class="amount">(.*?)<\/span>/s', $credits, $matches);
			$usdprice=$matches[0];
	
			preg_match_all('/<span class="amount rupee">(.*?)<\/span>/s', $credits, $matches2);
			$inrprice=$matches2[0];
	
			global $wpdb;
			$ipAddr = $_SERVER['REMOTE_ADDR'];
			$fetch_ip = "SELECT countryCode, countryName FROM kvsv_geoip WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
			$query22 = $wpdb->get_results($fetch_ip);
			$valid_cc = array("IN");
	
			if (in_array($query22[0]->countryCode, $valid_cc)) 
			{
				$creditsprice= str_replace("|","",$inrprice[0]);
			}
			else
			{
				$creditsprice= $usdprice[0];
			}
		
		
			$product_link = point_to_com_site(get_permalink(get_the_ID())).'?type=selfPaced&redirect';
			echo '<div itemprop="offers" style="position: relative; float: left; width: 26%;padding-top: 18px;" itemscope="" itemtype="http://schema.org/Offer">
					<h5 class="credits price">'.$credits.'</h5>
					<meta itemprop="price" content="'.$product->get_price().'">
					<meta itemprop="priceCurrency" content="'.get_woocommerce_currency().'">
					<link itemprop="availability" href="http://schema.org/'.($product->is_in_stock() ? 'InStock' : 'OutOfStock').'">
				</div>';
       		echo '<div style=" width: 74%; float: left;text-align: right;"><a  style="text-transform:none;" href="'.$product_link .'" data-product_id="'.$pid.'" class="'.((isset($id) && $id )?'':' ').'add_to_cart_button button " onClick="ga(\'send\', \'event\', { eventCategory: \'button\', eventAction: \'click\', eventLabel: \'Take Self-Paced Course\'});" '.$rel.'>'.apply_filters('wplms_take_this_course_button_label',__('Buy Self Paced Course','vibe'),$course_id).apply_filters('wplms_course_button_extra',$extra,$course_id).'</a></div>'; 
			
			do_action('take_course_events');
			
      }else{
        //echo '<a href="'.apply_filters('wplms_private_course_button','#',$course_id).'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'. apply_filters('wplms_private_course_button_label',__('PRIVATE COURSE','vibe'),$course_id).'</a>'; 
      }
   }
}

/*function bkintellipaat_selfpaced_course_button($id=NULL){
  global $post;
  $rel = (TLD == 'com' ? '' : 'rel="nofollow"') ;
  if(isset($id) && $id)
    $course_id=$id;
   else 
    $course_id=get_the_ID();

  // Free Course
   $free_course= get_post_meta($course_id,'vibe_course_free',true);

  if(!is_user_logged_in() && vibe_validate($free_course)){
    echo apply_filters('wplms_course_non_loggedin_user','<a href="'.get_permalink($course_id).'?error=login" class="course_button button full" '.$rel.'>'.__('Self-Paced Course','vibe').'</a>'); 
    return;
  }

   $take_course_page=get_permalink(vibe_get_option('take_course_page'));
   $user_id = get_current_user_id();

   do_action('wplms_the_course_button',$course_id,$user_id);

   $coursetaken=get_user_meta($user_id,$course_id,true);
   
   if(isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in()){

      $duration=get_post_meta($course_id,'vibe_duration',true);
      $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
      $new_duration = time()+$course_duration_parameter*$duration; //parameter 86400

      $new_duration = apply_filters('wplms_free_course_check',$new_duration);
      if(update_user_meta($user_id,$course_id,$new_duration)){
        $group_id=get_post_meta($course_id,'vibe_group',true);
        if(isset($group_id) && $group_id !=''){
          groups_join_group($group_id, $user_id );
        }
      }
      $coursetaken = $new_duration;      
   }
	
   if(isset($coursetaken) && $coursetaken && is_user_logged_in()){
	   
	  echo '<a href="http://intellipaat.com/elearning/login/index.php" class="'.((isset($id) && $id )?'':'course_button full ').'button" '.$rel.'>'.__('Start Course','vibe').'</a>'; 
    	
	}else{
      $pid=get_post_meta($course_id,'vibe_product',true);
      $pid=apply_filters('wplms_course_product_id',$pid,$course_id);
      $extra ='';
      if(isset($pid) && $pid){
		  $product = get_product( $pid );
		  if(is_object($product))
			$credits = $product->get_price_html();
			
			//$product_link = point_to_com_site(get_permalink($pid));
			$product_link = point_to_com_site(get_permalink(get_the_ID()));
			$check=vibe_get_option('direct_checkout');
			$check =intval($check);
			if(isset($check) &&  $check){
			  $product_link  .= '?type=selfPaced&redirect';
			}
			else{
				$product_link = point_to_com_site(get_permalink($pid));
			}
			//$credits = apply_filters('wplms_course_credits',$credits,$id);
			
			echo '<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
					<h5 class="credits price">'.$credits.'</h5>
					<meta itemprop="price" content="'.$product->get_price().'">
					<meta itemprop="priceCurrency" content="'.get_woocommerce_currency().'">
					<link itemprop="availability" href="http://schema.org/'.($product->is_in_stock() ? 'InStock' : 'OutOfStock').'">
				</div>
				<a href="'.$product_link .'" data-product_id="'.$pid.'" class="'.((isset($id) && $id )?'':' ').'add_to_cart_button button" onClick="ga(\'send\', \'event\', { eventCategory: \'button\', eventAction: \'click\', eventLabel: \'Take Self-Paced Course\'});" '.$rel.'>'.__('Take Self-Paced Course','vibe').apply_filters('wplms_course_button_extra',$extra,$course_id).'</a>'; 
      }
   }
}*/

function intellipaat_online_course_button($id=NULL){
  global $post;
  $rel = (TLD == 'com' ? '' : 'rel="nofollow"') ;
  if(isset($id) && $id)
    $course_id=$id;
   else 
    $course_id=get_the_ID();
	
	$pid=get_post_meta($course_id,'intellipaat_online_training_course',true);
 
	if(!empty($pid) && $pid != 'null' && $pid != NULL ){
	
	   $user_id = get_current_user_id();		
	   if(isset($coursetaken) && $coursetaken && is_user_logged_in()){
		  
		  echo '<a href="'.get_permalink($course_id).'" class="'.((isset($id) && $id )?'':'course_button full ').'button"  '.$rel.'>'.__('Start Online training','vibe').'</a>'; 
			
		}else{
			
			$extra ='';
			if(isset($pid) && $pid !='' && function_exists('get_product')){
				
				$product = get_product( $pid );
				if(is_object($product))
				$credits = $product->get_price_html();
				$product_link = point_to_com_site(get_permalink(get_the_ID())).'?type=onlineTraining&redirect';
				/*$check=vibe_get_option('direct_checkout');
				$check =intval($check);
				if(isset($check) &&  $check){
				  $product_link  .= '?type=onlineTraining&redirect';
				}
				else{
					$product_link = point_to_com_site(get_permalink($pid));
				}*/
			
	preg_match_all('/<span class="amount">(.*?)<\/span>/s', $credits, $matches);
	$usdprice=$matches[0];
	
	preg_match_all('/<span class="amount rupee">(.*?)<\/span>/s', $credits, $matches2);
	$inrprice=$matches2[0];
	
	global $wpdb;
	$ipAddr = $_SERVER['REMOTE_ADDR'];
    $fetch_ip = "SELECT countryCode, countryName FROM kvsv_geoip WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
	$query22 = $wpdb->get_results($fetch_ip);
	$valid_cc = array("IN");
	
	 if (in_array($query22[0]->countryCode, $valid_cc)) {
      $creditsprice= str_replace("|","",$inrprice[0]);
        } else {
         $creditsprice= $usdprice[0];
        }
		
				echo '<div itemprop="offers" style="position: relative; float: left; width: 24%;padding-top: 18px;" itemscope="" itemtype="http://schema.org/Offer">
						<h5 class="credits price">'.$credits .'</h5>
						<meta itemprop="price" content="'.$product->get_price().'">
						<meta itemprop="priceCurrency" content="'.get_woocommerce_currency().'">
						<link itemprop="availability" href="http://schema.org/'.($product->is_in_stock() ? 'InStock' : 'OutOfStock').'">
					</div >
						<div style=" width: 74%;text-align: right; float: left;"> <a style="text-transform:none;" href="'.$product_link.'" data-product_id="'.$pid.'" class="'.((isset($id) && $id )?'':' ').'button add_to_cart_button" onClick="ga(\'send\', \'event\', { eventCategory: \'button\', eventAction: \'click\', eventLabel: \'Take Online training\'});" '.$rel.'>'.__('Buy Online training','vibe').apply_filters('wplms_course_button_extra',$extra,$course_id).'</a></div>'; 
			
				do_action('take_course_events');
			}
			
	   }
	}
}


function intellipaat_the_course_details($args=NULL){
  echo intellipaat_get_the_course_details($args);
}

function intellipaat_get_the_course_details($args=NULL){
  $defaults=array(
    'course_id' =>get_the_ID(),
    );
  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );

  $precourse=get_post_meta($course_id,'vibe_pre_course',true);
  $maximum = get_post_meta($course_id,'vibe_max_students',true);
  $badge=get_post_meta($course_id,'vibe_course_badge',true);
  $certificate=get_post_meta($course_id,'vibe_course_certificate',true);
  $level = vibe_get_option('level');
  if(isset($level) && $level)
    $levels=get_the_term_list( $course_id, 'level', '', ', ', '' );

  $course_details = array(
    //'price' => '<li><i class="icon-wallet-money"></i> <h5 class="credits">'.bp_course_get_course_credits('course_id='.$course_id).'</h5></li>',
   // 'precourse'=>((isset($precourse) && $precourse!='')?'<li><i class="icon-clipboard-1"></i> '.__('* REQUIRES','vibe').' <a href="'.get_permalink($precourse).'">'.get_the_title($precourse).'</a></li>':''),
   // 'time' => apply_filters('wplms_course_details_time','<li><i class="icon-clock"></i>'.get_the_course_time('course_id='.$course_id).'</li>'),
  //  'level' => ((isset($level) && $level && strlen($levels)>5)?'<li><i class="icon-bars"></i> '.$levels.'</li>':''),
  //  'seats' => ((isset($maximum) && is_numeric($maximum) && $maximum < 9999 )?'<li><i class="icon-users"></i> '.$maximum.' '.__('SEATS','vibe').'</li>':''),
   // 'badge' => ((isset($badge) && $badge && $badge !=' ')?'<li><i class="icon-award-stroke"></i> '.__('Course Badge','vibe').'</li>':''),
   // 'certificate'=> (vibe_validate($certificate)?'<li><i class="icon-certificate-file"></i>  '.__('Course Certificate','vibe').'</li>':''),
    );

  $course_details = apply_filters('wplms_course_details_widget',$course_details);
  global $post;
  $return ='<div class="course_details">
              <ul>'; 
  foreach($course_details as $course_detail){
    if(isset($course_detail) && strlen($course_detail) > 5)
      $return .=$course_detail;
  }
  $return .=  '</ul>
            </div>';
   return apply_filters($return);
//   return apply_filters('wplms_course_front_details',$return);
}

//replace vibe_breadcrumbs() funciton
if(!function_exists('vibe_breadcrumbs') && function_exists('yoast_breadcrumb') ){
	function vibe_breadcrumbs() {  
		$breadcrumbs = yoast_breadcrumb('<p id="breadcrumbs">','</p>',false);

		if(is_singular('course')){
			global $post;
			$wpseo_internallinks = get_option('wpseo_internallinks');
			$breadcrumbs_home = $wpseo_internallinks['breadcrumbs-home'];
			
			$term_list = wp_get_post_terms($post->ID, 'course-cat', array("fields" => "names"));
			if($term_list){
				if (in_array("Big Data", $term_list) || in_array("Cloud Computing", $term_list) || in_array("Application Development", $term_list)){
					$breadcrumbs = str_replace($breadcrumbs_home, 'Hadoop' , $breadcrumbs);
				}
				if (in_array("Business Intelligence", $term_list) || in_array("Data Mining", $term_list) || in_array("DataBase", $term_list)){
					$breadcrumbs = str_replace($breadcrumbs_home, 'Big data' , $breadcrumbs);
				}
			}
		}			
		echo $breadcrumbs;
	}
}

function intellipaat_recommended_courses($course_id=''){	
    
    if( class_exists('BP_Course_Widget') ){
		
		if(empty($course_id)) 
			$course_id = get_the_ID();
         
        $intellipaat_recommended_courses = get_post_meta( $course_id, 'intellipaat_recommended_courses',true );
        
        if(!empty($intellipaat_recommended_courses))
            $ids = implode(',',$intellipaat_recommended_courses);
        
        if(empty($ids))
            $ids = implode(',',vibe_get_option('recommended_courses'));
        
        $args = array(
            'before_widget' => '<div class="widget recommended_course hidden-xs">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<h4 class="widget_title">',
            'after_title' 	=> '</h4>'
        );
        if(strlen($ids) <= 5)
			$ids .= ',,,';
			
        $instance = array( 'title'=> 'Recommended Courses','style' => 'single','orderby'=>'name','order'=>'DESC','category'=>'','ids'=>$ids , 'max_items' => 3 );
        
        the_widget( 'BP_Course_Widget', $instance, $args  ); 
        
    }
    
}

function the_intellipaat_quiz($args=NULL){

  $defaults=array(
  'quiz_id' =>get_the_ID(),
  'ques_id'=> ''
  );

  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );

    $user_id = get_current_user_id();

    $questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
    if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
      $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));

    if(isset($questions['ques']) && is_array($questions['ques']))
      $key=array_search($ques_id,$questions['ques']);

    if($ques_id){
      $the_query = new WP_Query(array(
        'post_type'=>'question',
        'p'=>$ques_id
        ));
      while ( $the_query->have_posts() ) : $the_query->the_post(); 
        the_question();

        echo '<div class="quiz_bar">';
        if($key == 0){ // FIRST QUESTION
          if($key != (count($questions['ques'])-1)) // First But not the Last
            echo '<a href="#" class="ques_link right quiz_question nextq" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key+1)].'">'.__('Next Question','vibe').' &rsaquo;</a>';

        }elseif($key == (count($questions['ques'])-1)){ // LAST QUESTION

          echo '<a href="#" class="ques_link left quiz_question prevq" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key-1)].'">&lsaquo; '.__('Previous Question','vibe').'</a>';
          echo '<a id="finalise_quiz" href="#" class="ques_link right nextq" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key+1)].'">'.__('Submit Quiz','vibe').'</a>';

        }else{
          echo '<a href="#" class="ques_link left quiz_question prevq" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key-1)].'">&lsaquo; '.__('Previous Question','vibe').'</a>';
          echo '<a href="#" class="ques_link right quiz_question nextq" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key+1)].'">'.__('Next Question','vibe').' &rsaquo;</a>';
        }

        echo '</div>';
		
					 echo '<small>(Warning: Mandatory to submit an answer, otherwise counted zero) </small>';
      endwhile;
      wp_reset_postdata();
    }else{
        
        $quiz_taken=get_user_meta($user_id,$quiz_id,true);

        if(isset($quiz_taken) && $quiz_taken && ($quiz_taken < time())){
          
          $message=get_post_meta($quiz_id,'vibe_quiz_message',true);
          echo '<div class="main_content">';
          echo $message;
          echo '</div>';
        }else{
          echo '<div class="main_content">';
          the_content();
          echo '</div>';
        }
    } 
}

function single_course_page_reviews($course_id){
	global $wpdb;

	/*
	extract( shortcode_atts( array(

      		'id'   => '',
	  
      ), $atts ) );
	*/
	/*$output = "";
	 $args = array(
        'number'=>160,
        'offset'=>0,
        'status'=>'approve',
        'order'=>'DESC',
		'orderby'=>'comment_author_email',
    );
	 */
	//$comments = get_comments($args);
	//$query = "SELECT * FROM $wpdb->comments as c JOIN (SELECT DISTINCT comment_author, comment_author_email FROM $wpdb->comments) as dc WHERE c.comment_author_email = dc.comment_author_email ORDER BY c.comment_date_gmt DESC LIMIT 160"; var_dump($query);

	//$query = "SELECT DISTINCT * FROM $wpdb->comments WHERE comment_type = '' AND comment_approved = '1' GROUP BY comment_author_email ORDER BY comment_date_gmt DESC LIMIT 80";
	
	$query = "SELECT DISTINCT * FROM $wpdb->comments 
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =$wpdb->posts.ID)
 WHERE $wpdb->posts.post_type = 'course' AND comment_type = '' AND comment_approved = '1' AND $wpdb->posts.ID=$course_id GROUP BY comment_author_email ORDER BY comment_date_gmt DESC LIMIT 80";
	
	
	
	$comments = $wpdb->get_results( $query );
	
	$count = 0;
	
	foreach($comments as $comment ){
				$output = '<li class="comment">';	
			$rating ='';
			$url = ('http://' == $comment->comment_author_url) ? '' : $comment->comment_author_url;
				$url = esc_url( $url, array('http', 'https') );
				if( trim($comment->comment_author_url) != '' ) {
						$url  = '<div class="pull-right">Follow Me on <a rel="nofollow noindex" class="linkedin_url" href="' . esc_attr( $url  ) . '">LinkedIn</a></div>';
				}  
				else
					$url ='';
				  
			$commenttitle = get_comment_meta($comment->comment_ID, 'review_title', true );
			if( $commentrating = get_comment_meta( $comment->comment_ID, 'review_rating', true ) ) {
			  $rating .= '<div class="comment-rating star-rating">';
			  for($i=0;$i<5;$i++){
				if($commentrating > $i)
				  $rating .='<span class="fill"></span>';
				else
				  $rating .='<span></span>';
			  }
			  $rating .='</div>'; 
			}  
			$user = get_user_by( 'email', $comment->comment_author_email );
			$bpuser = new BP_Core_User( $user->ID );
			if(empty($bpuser->avatar_thumb)){
				$thumb = get_avatar( $comment->comment_author_email , 120 );
			} else {
				$thumb = $bpuser->avatar_thumb;
			}
			
					 $output .=   '<div class="comment-body" id="div-comment-'.$comment->comment_ID.'">
											<div class="comment-author vcard">
											'.$thumb.'			<cite class="fn">'.apply_filters( 'comment_author',$comment->comment_author ) .'</cite> 	
											</div>
									
							
									<p>' .( ( $commenttitle) ? '<strong>' . esc_attr( $commenttitle ) . '</strong><br/><br/>' : '').$comment->comment_content.'</p>
									'.$rating.'
									'.$url .'
								</div>'; 
			$count++;
			echo $output .= ' </li> ';
	}
	
}

function price_change_ip($course_price){
	global $wpdb;

						preg_match_all('/<span class="amount">(.*?)<\/span>/s',$course_price , $matches);
						$usdprice=$matches[0];
				
						preg_match_all('/<span class="amount rupee">(.*?)<\/span>/s', $course_price, $matches2);
						$inrprice=$matches2[0];
				
					
						$ipAddr = $_SERVER['REMOTE_ADDR'];
						$fetch_ip = "SELECT countryCode, countryName FROM kvsv_geoip WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
						$query22 = $wpdb->get_results($fetch_ip);
						$valid_cc = array("IN");
				
						if (in_array($query22[0]->countryCode, $valid_cc)) 
						{
							$price_html ="<strong>".$creditsprice= str_replace("|","",$inrprice[0])."</strong>";
						}
						else
						{
							$price_html ="<strong>".$creditsprice= $usdprice[0]."</strong>";
						}
						return $price_html;
						
}
  
?>