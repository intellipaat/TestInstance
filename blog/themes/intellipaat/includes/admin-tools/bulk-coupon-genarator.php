<?php 
/**
 * Coupon Genarator ------- By Makarand Mane
 **/


add_action('admin_menu', 'register_coupon_genarator_submenu_page');

function register_coupon_genarator_submenu_page() {
	add_submenu_page( 'tools.php', 'Coupon Genarator', 'Coupon Genarator', 'manage_options', 'coupon-genarator', 'coupon_genarator_submenu_page_callback' );
}

function coupon_genarator_submenu_page_callback() {
	
	global $post;
	
	$prefix = 'int';
	$coupons_table = '';
	
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';  screen_icon('themes'); 
		echo '<h2>Coupon Genarator</h2>';
		
		if (isset($_POST["genarate_coupons"])) {					
			$amount = $_POST['coupon_amount']; // Amount
			$discount_type = $_POST['discount_type']; // Type: fixed_cart, percent, fixed_product, percent_product
			$product_ids = $_POST['product_ids'] ? implode(', ', $_POST['product_ids']) : '';
			$product_categories = $_POST['product_categories'] ? implode(', ', $_POST['product_categories']) : '';
			$prefix = $_POST['coupon_code_prefix'] ? $_POST['coupon_code_prefix'] :$prefix ;
			
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			
			$total_coupons_tobe_genarated = $_POST['num_of_coupons'];
			
			for( $i=0; $i<$total_coupons_tobe_genarated; $i++){
					$coupon_code = $prefix.substr(str_shuffle($characters), 0, 10 ) ;
								
								
								
					$coupon = array(
						'post_title' => $coupon_code,
						'post_content' => '',
						'post_status' => 'publish',
						'post_author' => 1,
						'post_type'		=> 'shop_coupon'
					);
										
					$new_coupon_id = wp_insert_post( $coupon );
										
					// Add meta
					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
					update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
					update_post_meta( $new_coupon_id, 'product_ids', $product_ids  );
					update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
					update_post_meta( $new_coupon_id, 'product_categories', $product_categories  );
					update_post_meta( $new_coupon_id, 'usage_limit', '1' );
					update_post_meta( $new_coupon_id, 'expiry_date', '' );
					update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
					update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					
					$coupons_table .= '<tr> <td>'.($i+1).'</td> <td>' .$coupon_code.'</td><tr>';
			}
			
			?>
			<div class="updated fade"><p> <?php echo $total_coupons_tobe_genarated; ?> Coupons are genarated </p> </div>
            <?php
		}
		
		?>
		<form method="POST" action="">
            <table class="" cellpadding="1">
                <tr valign="top" >
                    <th scope="row" align="right">
                        <label for="num_of_coupons">
                            Number of coupons to be genarated
                        </label> 
                    </th>
                    <td>
                        <input type="number" id="num_of_coupons" name="num_of_coupons" size="25" required />
                    </td>
                </tr>
               
                <tr valign="top" >
                    <th scope="row" align="right">
                        <label for="coupon_code_prefix">
                            Coupon Code prefix
                        </label> 
                    </th>
                    <td>
                        <input type="text" id="coupon_code_prefix" name="coupon_code_prefix" value="<?php echo $prefix; ?>" required />
                    </td>
                </tr>
               
              <tr> 
                <th scope="row">
                    <label for="discount_type">Discount type</label>
                </th>
                <td>
                    <select id="discount_type" name="discount_type" class="select short" required>
                        <option value="fixed_cart">Cart Discount</option>
                        <option value="percent">Cart % Discount</option>
                        <option value="fixed_product">Product Discount</option>
                        <option value="percent_product">Product % Discount</option>
                    </select> 
                </td>
            </tr>
            
            
               
              <tr> 
                <th scope="row">
                    <label for="product_ids">Include Products</label>
                </th>
                
                <?php $args = array(
						'posts_per_page'   => -1,
						'offset'           => 0,
						'category'         => '',
						'category_name'    => '',
						'orderby'          => 'title',
						'order'            => 'ASC',
						'include'          => '',
						'exclude'          => '',
						'meta_key'         => '',
						'meta_value'       => '',
						'post_type'        => 'product',
						'post_mime_type'   => '',
						'post_parent'      => '',
						'post_status'      => 'publish',
						'suppress_filters' => true 
					);
					$posts_array = get_posts( $args ); ?>
                
                <td>
                    <select id="product_ids" name="product_ids[]" multiple="multiple" data-placeholder="Search for a product…">
                    	<?php foreach ( $posts_array as $post ) : setup_postdata( $post ); ?>
                        	<option value="<?php the_ID(); ?>">#<?php the_ID(); ?> - <?php the_title(); ?></option>
                        <?php endforeach; 
							wp_reset_postdata(); 	?>
                    </select>
                </td>
            </tr>
            
            
            
            <tr> 
                <th scope="row">
                    <label for="product_ids">Include Category</label>
                </th>
                
                <?php $args = array(
									'type'                     => 'post',
									'child_of'                 => 0,
									'parent'                   => '',
									'orderby'                  => 'name',
									'order'                    => 'ASC',
									'hide_empty'               => 0,
									'hierarchical'             => 0,
									'exclude'                  => '',
									'include'                  => '',
									'number'                   => '',
									'taxonomy'                 => 'product_cat',
									'pad_counts'               => false 
								
								); 
					$categories = get_categories( $args ); ?>
                
                <td>
                    <select id="product_categories" name="product_categories[]" multiple="multiple" data-placeholder="Search for a Categories…">
                    	<?php foreach ( $categories as $category ) : ?>
                        	<option value="<?php echo $category->term_id ?>">#<?php echo $category->term_id ; ?> - <?php echo $category->cat_name ; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
               
            <tr>
                <th scope="row">
                	<label for="coupon_amount">Coupon amount</label>
                </th>
                <td>
               		<input type="number" class="short wc_input_price" required name="coupon_amount" id="coupon_amount" value="" placeholder="0" step="any"> 
                </td>
            </tr>
                
            </table>
            <p>
            	<input type="hidden" name="genarate_coupons" value="Y" />
                <input type="submit" value="Genarate Now" class="button-primary"/>
            </p>
        </form>
		<?php
	echo '</div>';
	
	if($coupons_table != ''){ ?>
		 <table class="wp-list-table widefat" >
         	<thead>
                <tr valign="top">
                    <th>
                    		Sr. No.
                    </th>
                    <th>
                    		Coupon codes
                    </th>
                </tr>
             </thead>
             <tbody>
					<?php	echo $coupons_table;	?>
             </tbody>
        </table>
	<?php
	}

}
/**
 * Coupon Genarator ------- end
 **/
 ?>