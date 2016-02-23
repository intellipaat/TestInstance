<?php 

add_shortcode( 'videothumb', 'video_thumbnail' );

function video_thumbnail($atts){
	
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
	
	extract( shortcode_atts( array(

		  'id'   => 'Rq6kcjUubbU',
		  'class'=> 'col-md-4',
		  'name' => '',
		  'alt' => '',
		  'title' => 'video'

      ), $atts ) );
	
	
	$output = "<div class='thumb_course_links ".$class."'>";	
	
		$output .= "<div class='crop_thumb'>";			
			
				$output .= "<a class='hs-rsp-popup popup-youtube hiddendiv' href='http://www.youtube.com/watch?v=".$id."'>";
				
				$output .= "<img src='//img.youtube.com/vi/".$id."/0.jpg' height='150px' width='200zpx' title='Click to watch ".$title."' class='thumb-img' alt='".$alt."'>";
									
				$output .= "<span class='play-video icon-play-alt'></span></a>";
		
		$output .= "</div>";
		
		if(isset($name) && $name!=''){			
			$output .= "</span><p>$name</p>";			
		}
		
	$output .= "</div>";
		
	return $output;
}

add_shortcode( 'videothumb11', 'video_thumbnail11' );

function video_thumbnail11($atts){
	
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
	
	extract( shortcode_atts( array(

		  'id'   => 'Rq6kcjUubbU',
		  'class'=> 'col-md-12',
		  'name' => '',
		  'alt' => '',
		  'title' => 'video'

      ), $atts ) );
	
	
	$output = "<div class='thumb_course_links ".$class."'>";	
	
		$output .= "<div class='crop_thumb'>";			
			
				$output .= "<a class='hs-rsp-popup popup-youtube hiddendiv' href='http://www.youtube.com/watch?v=".$id."'>";
				
				$output .= "<img src='//img.youtube.com/vi/".$id."/0.jpg' height='270px' width='520px' title='Click to watch ".$title."' class='thumb-img' alt='".$alt."'>";
									
				$output .= "<span class='play-video icon-play-alt'></span></a>";
		
		$output .= "</div>";
		
		if(isset($name) && $name!=''){			
			$output .= "</span><p>$name</p>";			
		}
		
	$output .= "</div>";
		
	return $output;
}

?>