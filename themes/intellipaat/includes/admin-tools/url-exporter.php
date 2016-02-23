<?php


/**
 * 	URL-exporter.php ------- By Makarand Mane
 *	
 *	http://code.stephenmorley.org/php/creating-downloadable-csv-files/
 *	http://stackoverflow.com/questions/4249432/export-to-csv-via-php
 *	http://php.net/manual/en/function.fputcsv.php
 **/


add_action('admin_menu', 'register_url_exporter_submenu_page');

function register_url_exporter_submenu_page() {
	add_submenu_page( 'tools.php', 'Url Exporter', 'Url Exporter', 'manage_options', 'url-exporter', 'url_exporter_submenu_page_callback' );
}

function url_exporter_submenu_page_callback() {
	
	
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';  screen_icon('themes'); 
		echo '<h2>URL Exporter</h2>';
		?>
		<form method="POST" action="">
            
            <table>
                <thead>
                    <tr><td colspan="2"><h4>Built in post types</h4></td></tr>
                </thead>
                <tbody>
                	
					<?php
						
						$args = array(	
									  	'public' 	=> true	,
										'_builtin'	=> true
									  );
        
                        $post_types = get_post_types( $args , 'objects' ); 
                        
                        foreach ( $post_types as $post_type ) {
                        
                          echo '<tr><td><input id="' . $post_type->name . '" type="checkbox" name="post-type[]" value="' . $post_type->name . '" /></td><td><label for="' . $post_type->name . '"> ' . $post_type->label . '</label></td></tr>';
                        }
                    
                    ?>  
                </tbody>        
            </table>  
            
            <table>
                <thead>
                    <tr><td colspan="2"><h4>Custom post types</h4></td></tr>
                </thead>
                <tbody>
                	
					<?php
						
						$args = array(	
									  	'public' 	=> true	,
										'_builtin'	=> false
									  );
        
                        $post_types = get_post_types( $args , 'objects' ); 
                        
                        foreach ( $post_types as $post_type ) {
                        
                          echo '<tr><td><input id="' . $post_type->name . '" type="checkbox" name="post-type[]" value="' . $post_type->name . '" /></td><td><label for="' . $post_type->name . '"> ' . $post_type->label . '</label></td></tr>';
                        }
                    
                    ?>  
                </tbody>        
            </table>  
            
            <table>
                <thead>
                    <tr><td colspan="2"><h4>Built in Taxonomies</h4></td></tr>
                </thead>
                <tbody>
                	
					<?php
						
						$args = array(	
									  	'public' 	=> true	,
										'_builtin'	=> true
									  );
        
                        $taxonomies = get_taxonomies( $args , 'objects' ); 
                        
                        foreach ( $taxonomies as $taxonomy ) {
                        
                           echo '<tr><td><input id="' . $taxonomy->name . '" type="checkbox" name="taxonomy[]" value="' . $taxonomy->name . '" /></td><td><label for="' .  $taxonomy->name . '"> ' . $taxonomy->label. '</label></td></tr>';
                        }
                    
                    ?>  
                </tbody>        
            </table>  
            
            
            <table>
                <thead>
                    <tr><td colspan="2"><h4>Custom defined Taxonomies</h4></td></tr>
                </thead>
                <tbody>
                	
					<?php
						
						$args = array(	
									  	'public' 	=> true	,
										'_builtin'	=> false
									  );
        
                        $taxonomies = get_taxonomies( $args , 'objects' ); 
                        
                        foreach ( $taxonomies as $taxonomy ) {
                        
                           echo '<tr><td><input id="' . $taxonomy->name . '" type="checkbox" name="taxonomy[]" value="' . $taxonomy->name . '" /></td><td><label for="' .  $taxonomy->name . '"> ' . $taxonomy->label. '</label></td></tr>';
                        }
                    
                    ?>  
                </tbody>        
            </table>  
            
            <p>
            	 <?php wp_nonce_field( 'secure_export_to_csv', '_ipnonce' ); ?>
            	<input type="hidden" name="export_to_csv" value="Y" />
                <input type="submit" value="Export to CSV" class="button-primary"/>
            </p>
        </form>
		<?php
	echo '</div>';
}
function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

add_action('init','intellipaat_export_to_csv', 100);
function intellipaat_export_to_csv(){
			
	if (isset($_POST["export_to_csv"])) {
		if(wp_verify_nonce( $_REQUEST['_ipnonce'] , 'secure_export_to_csv' )){
			
			download_send_headers($_SERVER['HTTP_HOST']."_urls_" . date("YmdHis") . ".csv");
		
			$out = fopen('php://output', 'w');
			
			if (isset($_POST["post-type"]) ) {
				
				$args = array(
								'post_type' 		=> $_POST['post-type'],
								'posts_per_page'	=> -1,
								'post_status'		=> 'publish',
								'orderby'			=> array('type','name')
							  );
			
				$posts = new WP_Query($args);
				$posts = $posts->posts;			
				
				foreach($posts as $post) {
					fputcsv($out, array(str_replace(",", " ", $post->post_title) , $post->post_type, get_permalink($post->ID)));
				}
			}
		
		
			if (isset($_POST["taxonomy"]) ) { 
				
				$taxonomies = $_POST["taxonomy"];			
	
				$terms = get_terms( $taxonomies, array( 'orderby' => 'term_group') );	
				
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
					foreach($terms as $term) {
						fputcsv($out, array(str_replace(",", " ", $term->name ) , $term->taxonomy,  get_term_link( $term ) ));
					}
				}
			}
			
			fclose($out);
		
			die();
		}

	}
		
}

/**
 *  url-exporter.php ------- end
 **/
 ?>