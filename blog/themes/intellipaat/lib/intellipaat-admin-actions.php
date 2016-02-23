<?php
 
add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );

function my_restrict_manage_posts() {
    global $typenow, $post, $post_id;

	if( $typenow != "page" && $typenow != "post" ){
		//get post type
		$post_type=get_query_var('post_type'); 
	
		//get taxonomy associated with current post type
		$taxonomies = get_object_taxonomies($post_type);
	
		//in next loop add filter for tax
		if ($taxonomies) {
			foreach ($taxonomies as $tax_slug) {
				$tax_obj = get_taxonomy($tax_slug);
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ($terms as $term) { 
					$label = (isset($_GET[$tax_slug])) ? $_GET[$tax_slug] : ''; // Fix
					echo '<option value='. $term->slug, $label == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}
