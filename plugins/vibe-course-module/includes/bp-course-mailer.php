<?php

class bp_course_activation{
   var $settings;
   var $subject;
   var $user_email;
   function __construct(){
      $settings = get_option('lms_settings');
      if(isset($settings) && isset($settings['activate'])){
        $this->settings = $settings['activate'];
      }
      add_filter('bp_core_signup_send_validation_email_to',array($this,'user_mail'));
      add_filter('bp_core_signup_send_validation_email_subject',array($this,'bp_course_activation_mail_subject'));    
      add_filter('bp_core_signup_send_validation_email_message',array($this,'bp_course_activation_mail_message'),10,3);
      add_filter('messages_notification_new_message_message',array($this,'bp_course_bp_mail_filter'),10,7);
      add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
   }
    function bp_course_bp_mail_filter($email_content, $sender_name, $subject, $content, $message_link, $settings_link, $ud){
        $email_content = bp_course_process_mail($sender_name,$subject,$email_content); 
      return $email_content;
    }
    
    function set_html_content_type() {
        return 'text/html';
    }

   function user_mail($email){
    $this->user_email = $email;
    return $email;
   }

   function bp_course_activation_mail_subject($subject){
    $this->subject = $subject;

    if(isset($this->settings) && is_array($this->settings) && isset($this->settings['subject'])){
      $this->subject = $this->settings['subject'];
    }
    return $subject;
  }
  
  function bp_course_activation_mail_message($message,$user_id,$link){

    if(isset($this->settings) && is_array($this->settings) && isset($this->settings['message'])){
      $message = $this->settings['message'];
      if(strpos($message,'{{activationlink}}') === false){
        $message .= $message.' '.sprintf(__('Click %s to Activate account.','vibe'),'<a href="'.$link.'">'.__('this link','vibe').'</a>'); 
      }else{
        $message = str_replace('{{activationlink}}',$link,$message);
      }
      $message = bp_course_process_mail($this->user_email,$this->subject,$message);
    }    

    return $message;
  }
}

new bp_course_activation;

// BP Course Mail function

function bp_course_wp_mail($to,$subject,$message,$args=''){
  if(!count($to))
    return;
  
    $headers = "MIME-Version: 1.0" . "\r\n";
     $settings = get_option('lms_settings');
    if(isset($settings['email_settings']) && is_array($settings['email_settings'])){
        if(isset($settings['email_settings']['from_name'])){
          $name = $settings['email_settings']['from_name'];
        }else{
          $name =get_bloginfo('name');
        }
        if(isset($settings['email_settings']['from_email'])){
          $email = $settings['email_settings']['from_email'];
        }else{
          $email = get_option('admin_email');
        }
        if(isset($settings['email_settings']['charset'])){
          $charset = $settings['email_settings']['charset'];
        }else{
           $charset = 'utf8'; 
        }
    }
    $headers .= "From: $name<$email>". "\r\n";
    $headers .= "Content-type: text/html; charset=$charset" . "\r\n";
    
  $message = bp_course_process_mail($to,$subject,$message,$args);  
  $message = apply_filters('wplms_email_templates',$message,$to,$subject,$message,$args);
  wp_mail($to,$subject,$message,$headers);
}

// BP Course Mail function to be extended in future

function bp_course_process_mail($to,$subject,$message,$args=''){
    $template = html_entity_decode(get_option('wplms_email_template'));
    if(!isset($template) || !$template || strlen($template) < 5)
      return $message;
     

    $site_title = get_option('blogname');
    $site_description = get_option('blogdescription');
    $logo_url = vibe_get_option('logo');
    $logo = '<a href="'.get_option('home_url').'"><img src="'.$logo_url.'" alt="'.$site_title.'" style="max-width:50%;"/></a>';

    $sub_title = $subject; 
    if(isset($args['user_id'])){
      if(is_numeric($args['user_id'])){
        $name = bp_core_get_userlink($args['user_id']);
      }else if(is_array($args['user_id'])){
        $userid = $args['user_id'][0];
        if(is_numeric($userid)){
          $name = bp_core_get_userlink($userid);
        }
      }
    }else
      $name = $to;

    $datetime = date_i18n( get_option( 'date_format' ), time());
    if(isset($args['item_id'])){
      $instructor_id = get_post_field('post_author', $args['item_id']);
      $sender = bp_core_get_user_displayname($instructor_id);
      $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
      $sender_links = '<a href="'.bp_core_get_user_domain( $instructor_id ).'">'.__('Profile','vibe-customtypes').'</a>&nbsp;|&nbsp;<a href="'.get_author_posts_url($instructor_id).$instructing_courses.'/">'.__('Courses','vibe-customtypes').'</a>';
      $item = get_the_title($args['item_id']);
      $item_links  = '<a href="'.get_permalink( $args['item_id'] ).'">'.__('Link','vibe-customtypes').'</a>&nbsp;|&nbsp;<a href="'.bp_core_get_user_domain($instructor_id).'/">'.__('Instructor','vibe-customtypes').'</a>';
      $unsubscribe_link = bp_core_get_user_domain($args['user_id']).'/settings/notifications';
    }else{
      $sender ='';
      $sender_links ='';
      $item ='';
      $item_links ='';
      $unsubscribe_link = '#';
      $template = str_replace('cellpadding="28"','cellpadding="0"',$template);
    }
   
    $copyright = vibe_get_option('copyright');
    $link_id = vibe_get_option('email_page');
    if(is_numeric($link_id)){
      $array = array(
        'to' => $to,
        'subject'=>$subject,
        'message'=>$message,
        'args'=>$args
        );
      $link = get_permalink($link_id).'?vars='.urlencode(json_encode($array));
    }else{
      $link = '#';
    }


    $template = str_replace('{{logo}}',$logo,$template);
    $template = str_replace('{{subject}}',$subject,$template);
    $template = str_replace('{{sub-title}}',$sub_title,$template);
    $template = str_replace('{{name}}',$name,$template);
    $template = str_replace('{{datetime}}',$datetime,$template);
    $template = str_replace('{{message}}',$message,$template);
    $template = str_replace('{{sender}}',$sender,$template);
    $template = str_replace('{{sender_links}}',$sender_links,$template);
    $template = str_replace('{{item}}',$item,$template);
    $template = str_replace('{{item_links}}',$item_links,$template);
    $template = str_replace('{{site_title}}',$site_title,$template);
    $template = str_replace('{{site_description}}',$site_description,$template);
    $template = str_replace('{{copyright}}',$copyright,$template);
    $template = str_replace('{{unsubscribe_link}}',$unsubscribe_link,$template);
    $template = str_replace('{{link}}',$link,$template);
    $template = bp_course_minify_output($template);
    return $template;
}

function bp_course_minify_output($buffer){
  $search = array(
  '/\>[^\S ]+/s',
  '/[^\S ]+\</s',
  '/(\s)+/s'
  );
  $replace = array(
  '>',
  '<',
  '\\1'
  );
  if (preg_match("/\<html/i",$buffer) == 1 && preg_match("/\<\/html\>/i",$buffer) == 1) {
    $buffer = preg_replace($search, $replace, $buffer);
  }
  return $buffer;
}

function send_html( $message,    $user_id, $activate_url ) {
  $message = bp_course_process_mail($to,$subject,$message,$args); 
  return $message;
}


