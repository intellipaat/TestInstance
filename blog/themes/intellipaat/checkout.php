<?php

/**
 * Template Name: Checkout
 * FILE: checkout.php 
 * Created on Apr 2, 2013 at 3:07:11 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 */


get_header();


if ( have_posts() ) : while ( have_posts() ) : the_post();

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(vibe_validate($title)){
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(vibe_validate($breadcrumbs))
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="<?php echo $v_add_content;?> content">
                    <?php
                        the_content();
                     ?>
                </div>
            </div>
            <div id="sidebar" class="col-md-3 hidden-sm hidden-xs">
                <?php
				
					if ( get_option( 'woocommerce_enable_coupons' ) == 'no' || get_option( 'woocommerce_enable_coupon_form_on_checkout' ) == 'no' || is_wc_endpoint_url("order-received") ){}else{ ?>
                       <div class="coupon">
                       <?php
                       
                        $info_message = apply_filters('woocommerce_checkout_coupon_message', __('Have a coupon?', 'woocommerce'));
                        ?>
            
                        <p class="woocommerce_info"><strong><?php echo $info_message; ?> </strong><a href="#" class="showcoupon"><?php _e('Click here to enter your code', 'woocommerce'); ?></a></p>
                        <form class="checkout_coupon" method="post">
                
                             <div class="coupon-form">
                                <input name="coupon_code" id="coupon_code" placeholder="Enter your coupon code" class="input-text" required />
                                <button type="submit" name="apply_coupon">Apply</button>
                            </div>
                
                            <div class="clear"></div>
                        </form>
                        </div>
                    <?php
                    }
                    
                    $sidebar = apply_filters('wplms_sidebar','checkout',$page_id);
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
endwhile;
endif;
?>
</div>
<?php
get_footer();
?>
