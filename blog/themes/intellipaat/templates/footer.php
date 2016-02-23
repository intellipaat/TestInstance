<?php

global $wp_registered_sidebars;

$before_widget = $wp_registered_sidebars['bottomfootersidebar']['before_widget'];
$after_widget = $wp_registered_sidebars['bottomfootersidebar']['after_widget'];
$before_title = $wp_registered_sidebars['bottomfootersidebar']['before_title'];
$after_title = $wp_registered_sidebars['bottomfootersidebar']['after_title'];

$menus = array(
			   'courses' 	=> 'Top Courses',
			   'iq'			=> 'Top Interview questions',
			   'jobs'		=> 'Recent Jobs',
			   'tutorial'	=> 'Popular tutorial category',
		);
$count = 1;
foreach($menus as $menu => $title){
	
	echo $before_widget;
	echo $before_title.$title.$after_title;
	
	echo '<div class="menu-footer-featured-links-column-'.$count .'-container"><ul id="menu-footer-featured-links-column-'.$count .'" class="menu">';
	
		$menu_items = vibe_get_option($menu.'_menu_items_1');
	 	foreach($menu_items['post_id'] as $key => $menu_item){ 
            $keywords = explode(',',$menu_items['keywords'][$key]);
			shuffle($keywords );
			$menu_title = $keywords[0];
            echo '<li id="menu-item-'.$menu_item.'" class="menu-item menu-item-type-post_type menu-item-object-'.$menu.' menu-item-'.$menu_item.'"><a href="'.get_permalink($menu_item).'">'.$menu_title.'</a></li>';
         }
		$menu_items =vibe_get_option($menu.'_menu_items_2'); 
		shuffle($menu_items);
		for($i=0; $i<4; $i++){ 
			if(!isset($menu_items[$i]))
				break;
			$menu_item = $menu_items[$i];
			echo '<li id="menu-item-'.$menu_item.'" class="menu-item menu-item-type-post_type menu-item-object-'.$menu.' menu-item-'.$menu_item.'"><a href="'.get_permalink($menu_item).'">'.get_the_title($menu_item).'</a></li>';
         }
		
	echo '</ul></div>';
	echo $after_widget;
	$count++;
}
?>