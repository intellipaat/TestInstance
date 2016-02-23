<?php


/**
 * 	Hyphen remover ------- By Makarand Mane
 *
 *	https://wordpress.org/support/topic/searching-images-on-alt-text?replies=2
 *	https://wordpress.org/support/topic/automatically-alt-tag-images?replies=33
 *	
 **/


add_action('admin_menu', 'alt_tag_editor_submenu_page');

function alt_tag_editor_submenu_page() {
	add_media_page( 'Alt tag Editor', 'Alt tag Editor', 'manage_options', 'alt-tag-editor', 'alt_tag_editor_submenu_page_callback' );
}

function alt_tag_editor_submenu_page_callback() {
	
	global $wpdb;

	$meta_key = '_wp_attachment_image_alt';
	$charactor	= "%-%";
	
	
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';  screen_icon('themes'); 
		echo '<h2>Alt tag Editor</h2>';
		
		if (isset($_POST["replace"]) && $_POST["replace"] == 'Y') {	
		
			$result = $wpdb->get_results( $wpdb->prepare( "	UPDATE $wpdb->postmeta
														 		SET meta_value = REPLACE(meta_value, '-', ' ')
																WHERE  `meta_key` LIKE %s
															",$meta_key) );
			
			?>
			<div class="updated fade"><p>Hyphen/s(-) removed from alt tags of all images .</p> </div>
            <?php
		}
		
		$total_alt_tags = $wpdb->get_var( $wpdb->prepare( "SELECT  COUNT(meta_value) FROM $wpdb->postmeta Where meta_key = %s and meta_value Like %s", $meta_key, $charactor) );
		
		?>
        <div class="notice"><p>Total <strong><?php echo $total_alt_tags; ?> images </strong> have hyphen(-) in there alt tags.</p> </div>
        
		<form method="POST" action="">
            <p>
            	<input type="hidden" name="replace" value="Y" />
                <input type="submit" value="Replace Now" class="button-primary"/>
            </p>
        </form>
		<?php
	echo '</div>';

}


/**
 * Hyphen remover ------- end
 *
 */
?>