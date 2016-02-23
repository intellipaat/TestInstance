<?php

add_action('wp_footer','form_to_login_user');

function form_to_login_user(){	
	
	if(is_user_logged_in() || isset($_COOKIE['intellipaat_visitor']) || !is_singular(  ))
		return;
			
	if(is_home() || is_front_page())
		return;
		
    global $post;
	?>	
    
	<div id="login-form"  class="white-popup-block non-com mfp-hide">
		<?php global $user_ID, $user_identity; get_currentuserinfo();  ?>      
              
        <div id="login-div" class="row tab_content_login clearfix">
            
            <?php /*?><div class="col-md-6 col-md-offset-0 login-sub visible login">
                <h2>Signup or Login</h2>    
    
                <?php do_action( 'wordpress_social_login' ); ?> 
                
            </div><!--login-sub ends--><?php */?>
            
		   <?php
                $nonce = wp_create_nonce("intellipaat_visitor_secure_signup_nonce");
                $link = admin_url('admin-ajax.php?action=intellipaat_visitor_secure_signup');
            ?>
            
            <div class="col-md-10 col-md-offset-1 login-sub visible register">
                <h3>To view the Free <?php echo $post->post_title ;?>. Enter your details</h3>
                
                 <form method="post" action="<?php echo $link; ?>" name="signup_form" id="signup_form" class="wp-user-form">                                 
                
                    <div class="row name">
                       <div class="input-group  col-md-6">
                          <span class="input-group-addon  icon-user"></span>
                          <input type="text" class="form-control" name="first_name" required="required"  size="20" id="first_name" tabindex="1" placeholder="First Name (Required)"/> 
                        </div>
                       <div class="input-group  col-md-6">
                          <span class="input-group-addon  icon-user"></span>
                          <input type="text" class="form-control" name="last_name" required="required"  size="20" id="last_name" tabindex="2" placeholder="Last Name (Required)"/> 
                        </div>
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon icon-globe"></span>
                        <select class="form-control chosen-select" name="country" required="required" tabindex="3"  data-placeholder="<?php _e( 'Choose country &hellip;', 'woocommerce' ); ?>" title="Select your Country">	
                        	<option value=""></option>	
                        	<?php 
								$countries = WC()->countries->countries;
								foreach($countries as $country_code => $country){
										echo '<option value="'.$country_code.'">'.$country.'</option>';
									}
							?>
                        </select> 
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon icon-envelope"></span>
                        <input type="email" class="form-control" name="user_email" required="required"  size="20" id="user_login" tabindex="4" placeholder="Email Address (Required)"/> 
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon icon-telephone-24"></span>
                        <input type="text"  class="form-control"  name="phone"  placeholder="Phone (Optional)" id="phno" tabindex="5" />
                    </div>
                    <br />       
                     <p class="loading hide pull-left"><i class="icon-spinner"> </i> <strong>Please wait for a moment ...</strong></p>
                    <input type="hidden" name="nonce" value="<?php echo $nonce ; ?>" class="SecKey" /> 
                    <input type="hidden" name="page_id" value="<?php echo $post->ID ; ?>" />        
                    <input type="hidden" name="lead_source" value="<?php echo $post->post_type ; ?>" />        
                    <input type="submit" name="user-submit"  id="signup_submit" value="Submit" tabindex="6" class="pull-right user-submit" onClick="ga('send', 'event', { eventCategory: 'button', eventAction: 'click', eventLabel: 'submit'});" />                        
                   
                 </form>
            </div><!--login sub ends-->
        </div>
    </div>
    <?php wp_enqueue_script('jquery-cookie');
}
?>