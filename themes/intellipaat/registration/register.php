<?php get_header( 'buddypress' );

$page_array=get_option('bp-pages');
if(isset($page_array['register'])){
	$id = $page_array['register'];
}
?>

<section id="content">
	<div class="container">
		<div class="col-md-10  col-md-offset-1">
			
            <h2><?php echo get_the_title($id); ?></h2>
            <p><?php the_sub_title($id); ?></p>

            <div class="content padder">
    
                <div class="page" id="register-page">
                
        
                    <div id="login-signup-div" class="row loginwrapper tab_content_login clearfix">
            
                        <div class="col-md-5 col-sm-5 login-sub visible login">
                
                            <?php do_action( 'wordpress_social_login' ); ?> 
                            
                        </div><!--login-sub ends-->
                        
                        <div class="col-md-5 col-md-offset-1 col-sm-6 col-sm-offset-1 login-sub visible register"> 
                        
                            <?php intellipaat_signup_form(); ?><!--signup div ends-->
                                
                        </div><!--login sub ends-->
                    </div>
        
                </div>
    
    
            </div><!-- .padder -->
		</div>
		<?php /*?><div class="col-md-3 col-sm-4">
			<div class="sidebar">
			<?php
		 		$sidebar = apply_filters('wplms_sidebar','buddypress',$id);
                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
           	<?php endif; ?>
			</div>
		</div><?php */?>
	</div>
</section><!-- #content -->
<?php get_footer( 'buddypress' ); ?>
