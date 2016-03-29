<?php
global $yith_wcwl, $product;

		$label = apply_filters( 'yith_wcwl_button_label', get_option( 'yith_wcwl_add_to_wishlist_text' ) );
		$icon = get_option( 'yith_wcwl_add_to_wishlist_icon' ) != 'none' ? '<i class="' . get_option( 'yith_wcwl_add_to_wishlist_icon' ) . '"></i>' : '';

		$classes = get_option( 'yith_wcwl_use_button' ) == 'yes' ? 'class="add_to_wishlist single_add_to_wishlist button alt"' : 'class="add_to_wishlist"';

		$html  = '<div class="yith-wcwl-add-to-wishlist">';
		$html .= '<div class="yith-wcwl-add-button';  // the class attribute is closed in the next row

		$html .= $exists ? ' hide" style="display:none;"' : ' show"';

		$html .= '><a href="' . esc_url( $yith_wcwl->get_addtowishlist_url() ) . '" data-product-id="' . $product->id . '" data-product-type="' . $product_type . '" ' . $classes . ' ><i class="fa fa-hand-o-right"></i> '. $label . '</a>';
		$html .= '</div>';

		$html .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><span class="feedback"><i class="fa fa-thumbs-o-up"></i> ' . __( 'Product added!','yit' ) . '</span></div>';
		$html .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><span class="feedback"><i class="fa fa-thumbs-o-up"></i> ' . __( 'Product In Wishlist', 'yit' ) . '</span></div>';
		$html .= '<div style="clear:both"></div><div class="yith-wcwl-wishlistaddresponse"></div>';

		$html .= '</div>';
		$html .= '<div class="clear"></div>';
		echo $html;
?>