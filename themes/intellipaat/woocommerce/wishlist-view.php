<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.7
 */
?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>

<form id="yith-wcwl-form" action="<?php echo esc_url( YITH_WCWL()->get_wishlist_url( 'view' . ( $wishlist_meta['is_default'] != 1 ? '/' . $wishlist_meta['wishlist_token'] : '' ) ) ) ?>" method="post" class="woocommerce">

    <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>

    <!-- TITLE -->
    <?php
    do_action( 'yith_wcwl_before_wishlist_title' );

    if( ! empty( $page_title ) ) :
    ?>
        <div class="wishlist-title <?php echo ( $wishlist_meta['is_default'] != 1 && $is_user_owner ) ? 'wishlist-title-with-form' : ''?>">
            <?php echo apply_filters( 'yith_wcwl_wishlist_title', '<h2>' . $page_title . '</h2>' ); ?>
            <?php if( $wishlist_meta['is_default'] != 1 && $is_user_owner ): ?>
                <a class="btn button show-title-form">
                    <?php echo apply_filters( 'yith_wcwl_edit_title_icon', '<i class="fa fa-pencil"></i>' )?>
                    <?php _e( 'Edit title', 'yith-woocommerce-wishlist' ) ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if( $wishlist_meta['is_default'] != 1 && $is_user_owner ): ?>
            <div class="hidden-title-form">
                <input type="text" value="<?php echo $page_title ?>" name="wishlist_name"/>
                <button>
                    <?php echo apply_filters( 'yith_wcwl_save_wishlist_title_icon', '<i class="fa fa-check"></i>' )?>
                    <?php _e( 'Save', 'yith-woocommerce-wishlist' )?>
                </button>
                <a class="hide-title-form btn button">
                    <?php echo apply_filters( 'yith_wcwl_cancel_wishlist_title_icon', '<i class="fa fa-remove"></i>' )?>
                    <?php _e( 'Cancel', 'yith-woocommerce-wishlist' )?>
                </a>
            </div>
        <?php endif; ?>
    <?php
    endif;

     do_action( 'yith_wcwl_before_wishlist' ); ?>

    <!-- WISHLIST TABLE -->
    <?php /*?><div class="table-responsive">
        <table class="table table-hover shop_table cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo ( is_user_logged_in() ) ? esc_attr( $wishlist_meta['ID'] ) : '' ?>" data-token="<?php echo ( ! empty( $wishlist_meta['wishlist_token'] ) && is_user_logged_in() ) ? esc_attr( $wishlist_meta['wishlist_token'] ) : '' ?>">
    
            <?php $column_count = 2; ?>
    
            <thead>
            <tr>
                <?php if( $show_cb ) : ?>
    
                    <th class="product-checkbox">
                        <input type="checkbox" value="" name="" id="bulk_add_to_cart"/>
                    </th>
    
                <?php
                    $column_count ++;
                endif;
                ?>
    
                <?php if( $is_user_owner ): ?>
                    <th class="product-remove"></th>
                <?php
                    $column_count ++;
                endif;
                ?>
    
                <th class="product-thumbnail"></th>
    
                <th class="product-name">
                    <span class="nobr"><?php echo apply_filters( 'yith_wcwl_wishlist_view_name_heading', __( 'Course Name', 'yith-woocommerce-wishlist' ) ) ?></span>
                </th>
    
                <?php if( $show_price ) : ?>
    
                    <th class="product-price">
                        <span class="nobr">
                            <?php echo apply_filters( 'yith_wcwl_wishlist_view_price_heading', __( 'Course Price with options', 'yith-woocommerce-wishlist' ) ) ?>
                        </span>
                    </th>
    
                <?php
                    $column_count ++;
                endif;
                ?>
    
                <?php if( $show_last_column ) : ?>
    
                    <th class="product-add-to-cart"></th>
    
                <?php
                    $column_count ++;
                endif;
                ?>
            </tr>
            </thead>
    
            <tbody>
            <?php
            if( count( $wishlist_items ) > 0 ) :
                foreach( $wishlist_items as $item ) :
                    global $product;
                    if( function_exists( 'wc_get_product' ) ) {
                        $product = wc_get_product( $item['prod_id'] );
                    }
                    else{
                        $product = get_product( $item['prod_id'] );
                    }
    
                    if( $product !== false && $product->exists() ) :
                        $availability = $product->get_availability();
                        $stock_status = $availability['class'];
                        ?>
                        <tr id="yith-wcwl-row-<?php echo $item['prod_id'] ?>" data-row-id="<?php echo $item['prod_id'] ?>">
                            <?php if( $show_cb ) : ?>
                                <td class="product-checkbox">
                                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( $product->product_type != 'simple' ) ? 'disabled="disabled"' : '' ?>/>
                                </td>
                            <?php endif ?>
    
                            <?php if( $is_user_owner ): ?>
                            <td class="product-remove">
                                <div>
                                    <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist" title="<?php _e( 'Remove this product', 'yith-woocommerce-wishlist' ) ?>">&times;</a>
                                </div>
                            </td>
                            <?php endif; ?>
    
                            <td class="product-thumbnail">
                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
                                    <?php echo $product->get_image() ?>
                                </a>
                            </td>
    
                            <td class="product-name">
                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
                            </td>
    
                            <?php if( $show_price ) : ?>
                                <td class="product-price">
                                    <?php 
                                        intellipaat_selfpaced_course_button($item['prod_id']);
                                        intellipaat_online_course_button($item['prod_id']);								
                                    ?>
                                </td>
                            <?php endif ?>
    
    
                            <?php if( $show_last_column ): ?>
                            <td class="product-add-to-cart">
                                <!-- Date added -->
                                <?php
                                if( $show_dateadded && isset( $item['dateadded'] ) ):
                                    echo '<span class="dateadded">' . sprintf( __( 'Added on : %s', 'yith-woocommerce-wishlist' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
                                endif;
                                ?>
    
    
                                <!-- Change wishlist -->
                                <?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist ): ?>
                                <select class="change-wishlist selectBox">
                                    <option value=""><?php _e( 'Move', 'yith-woocommerce-wishlist' ) ?></option>
                                    <?php
                                    foreach( $users_wishlists as $wl ):
                                        if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
                                            continue;
                                        }
    
                                    ?>
                                        <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
                                            <?php
                                            $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
                                            if( $wl['wishlist_privacy'] == 1 ){
                                                $wl_privacy = __( 'Shared', 'yith-woocommerce-wishlist' );
                                            }
                                            elseif( $wl['wishlist_privacy'] == 2 ){
                                                $wl_privacy = __( 'Private', 'yith-woocommerce-wishlist' );
                                            }
                                            else{
                                                $wl_privacy = __( 'Public', 'yith-woocommerce-wishlist' );
                                            }
    
                                            echo sprintf( '%s - %s', $wl_title, $wl_privacy );
                                            ?>
                                        </option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                                <?php endif; ?>
    
                                <!-- Remove from wishlist -->
                                <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                    <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php _e( 'Remove this product', 'yith-woocommerce-wishlist' ) ?>"><?php _e( 'Remove', 'yith-woocommerce-wishlist' ) ?></a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        </tr>
                    <?php
                    endif;
                endforeach;
            else: ?>
                <tr>
                    <td colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php _e( 'No products were added to the wishlist', 'yith-woocommerce-wishlist' ) ?></td>
                </tr>
            <?php
            endif;
    
            if( ! empty( $page_links ) ) : ?>
                <tr class="pagination-row">
                    <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo $page_links ?></td>
                </tr>
            <?php endif ?>
            </tbody>
    
            <tfoot>
            <tr>
                <td colspan="<?php echo esc_attr( $column_count ) ?>">
                    <?php if( $show_cb ) : ?>
                        <div class="custom-add-to-cart-button-cotaniner">
                            <a href="<?php echo esc_url( add_query_arg( array( 'wishlist_products_to_add_to_cart' => '', 'wishlist_token' => $wishlist_meta['wishlist_token'] ) ) ) ?>" class="button alt" id="custom_add_to_cart"><?php echo apply_filters( 'yith_wcwl_custom_add_to_cart_text', __( 'Add the selected products to the cart', 'yith-woocommerce-wishlist' ) ) ?></a>
                        </div>
                    <?php endif; ?>
    
                    <?php if ( is_user_logged_in() && $is_user_owner && $show_ask_estimate_button && $count > 0 ): ?>
                        <div class="ask-an-estimate-button-container">
                            <a href="<?php echo ( $additional_info ) ? '#ask_an_estimate_popup' : $ask_estimate_url ?>" class="btn button ask-an-estimate-button" <?php echo ( $additional_info ) ? 'data-rel="prettyPhoto[ask_an_estimate]"' : '' ?> >
                            <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
                            <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', __( 'Ask for an estimate', 'yith-woocommerce-wishlist' ) ) ?>
                        </a>
                        </div>
                    <?php endif; ?>
    
                    <?php
                    do_action( 'yith_wcwl_before_wishlist_share' );
    
                    if ( is_user_logged_in() && $is_user_owner && $wishlist_meta['wishlist_privacy'] != 2 && $share_enabled ){
                        yith_wcwl_get_template( 'share.php', $share_atts );
                    }
    
                    do_action( 'yith_wcwl_after_wishlist_share' );
                    ?>
                </td>
            </tr>
            </tfoot>
    
        </table>
    </div><?php */?>
	<?php
    global $post;
	$count = 1;
    if( count( $wishlist_items ) > 0 ) { ?>
            <div class="row cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo ( is_user_logged_in() ) ? esc_attr( $wishlist_meta['ID'] ) : '' ?>" data-token="<?php echo ( ! empty( $wishlist_meta['wishlist_token'] ) && is_user_logged_in() ) ? esc_attr( $wishlist_meta['wishlist_token'] ) : '' ?>">
				<?php
                foreach( $wishlist_items as $item ) {
                    echo '<div id="yith-wcwl-row-'.$item['prod_id'].'" data-row-id="'.$item['prod_id'] .'" class="wishlist_items col-md-4 col-sm-6 wishlist_items-'.$item['prod_id'] .'" >';
						echo thumbnail_generator(get_post($item['prod_id']),'course','medium',1,1,1);
						echo '<table class="table table-striped"><tr data-row-id="'.$item['prod_id'] .'" align="center"><td>';
						
							if( $show_add_to_cart ){ 
									$self	=get_post_meta($item['prod_id'],'vibe_product',true);
									$online	=get_post_meta($item['prod_id'],'intellipaat_online_training_course',true);
									if(is_numeric($self) && is_numeric($online)){ ?>
										<a href="#" class="add_to_cart_both add_to_cart_button" data-course_id="<?php echo $item['prod_id'] ; ?>"><i class="icon-shopping-cart"></i> Add to Cart</a>
 										<div class="add_to_cart_both_wrap hide add_to_cart_both_wrap-<?php echo $item['prod_id'] ; ?>">																  
											<a class="add_to_cart_button add_course_to_cart " data-quantity="1" data-product_sku="" data-course_id="<?php echo $item['prod_id'] ; ?>" data-product_id="<?php echo $self ; ?>" rel="nofollow" href="/?add-to-cart=<?php echo $self ; ?>&amp;remove_from_wishlist_after_add_to_cart=<?php echo $item['prod_id'] ; ?>"><i class="icon-shopping-cart"></i> Take Self Paced Course</a>
										 
											<a class="add_to_cart_button add_course_to_cart " data-quantity="1" data-product_sku="" data-course_id="<?php echo $item['prod_id'] ; ?>" data-product_id="<?php echo $online ; ?>" rel="nofollow" href="/?add-to-cart=<?php echo $online ; ?>&amp;remove_from_wishlist_after_add_to_cart=<?php echo $item['prod_id'] ; ?>"><i class="icon-shopping-cart"></i> Take Online Training</a>
                                        </div>
										  <?php 									
										
									}else{
									
										if(is_numeric($self)){
											?>																  
											<a class="add_to_cart_button add_course_to_cart " data-quantity="1" data-product_sku="" data-course_id="<?php echo $item['prod_id'] ; ?>" data-product_id="<?php echo $self ; ?>" rel="nofollow" href="/?add-to-cart=<?php echo $self ; ?>&amp;remove_from_wishlist_after_add_to_cart=<?php echo $item['prod_id'] ; ?>"><i class="icon-shopping-cart"></i> Add to Cart</a>
										  <?php }
										if(is_numeric($online)){
											?>
											<a class="add_to_cart_button add_course_to_cart " data-quantity="1" data-product_sku="" data-course_id="<?php echo $item['prod_id'] ; ?>" data-product_id="<?php echo $online ; ?>" rel="nofollow" href="/?add-to-cart=<?php echo $online ; ?>&amp;remove_from_wishlist_after_add_to_cart=<?php echo $item['prod_id'] ; ?>"><i class="icon-shopping-cart"></i> Add to Cart</a>
										  <?php 
										  }
									}
                            }
							
                   			if( $is_user_owner && $repeat_remove_button ): ?> 
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist remove" title="<?php _e( 'Remove this product', 'yith-woocommerce-wishlist' ) ?>"><i class="icon-cross"> </i><?php _e( 'Remove', 'yith-woocommerce-wishlist' ) ?></a>
                            <?php endif;
								
							
						echo '</td></tr></table>';
                    echo '</div>';
					
					if($count%3 == 0)
						echo '<div class="clear clearfix hidden-sm hidden-xs"></div>';
					if($count%2 == 0)
						echo '<div class="clear clearfix hidden-lg hidden-md hidden-xs"></div>';
						
					$count++;
                    
                }?>
            </div>
            <?php
    }else{ ?>
            <div>
                <p class="wishlist-empty notice"><?php _e( 'No course were added to the wishlist', 'yith-woocommerce-wishlist' ) ?></p>
            </div>
        <?php
    }
    ?>

    <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

    <?php if( $wishlist_meta['is_default'] != 1 ): ?>
        <input type="hidden" value="<?php echo $wishlist_meta['wishlist_token'] ?>" name="wishlist_id" id="wishlist_id">
    <?php endif; ?>

    <?php do_action( 'yith_wcwl_after_wishlist' ); ?>

</form>

<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

<?php if( $additional_info ): ?>
	<div id="ask_an_estimate_popup">
		<form action="<?php echo $ask_estimate_url ?>" method="post" class="wishlist-ask-an-estimate-popup">
			<?php if( ! empty( $additional_info_label ) ):?>
				<label for="additional_notes"><?php echo esc_html( $additional_info_label ) ?></label>
			<?php endif; ?>
			<textarea id="additional_notes" name="additional_notes"></textarea>

			<button class="btn button ask-an-estimate-button ask-an-estimate-button-popup" >
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
				<?php _e( 'Ask for an estimate', 'yith-woocommerce-wishlist' ) ?>
			</button>
		</form>
	</div>
<?php endif; ?>