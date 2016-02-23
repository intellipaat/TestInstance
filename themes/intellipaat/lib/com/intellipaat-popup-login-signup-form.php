<?php

add_action('wp_footer','form_to_login_user');

function form_to_login_user(){	
	
	if(is_user_logged_in() || isset($_COOKIE['intellipaat_visitor']) || !is_singular(  ))
		return;
			
	if(is_home() || is_front_page() || is_page())
		return;
	
	$page_array=get_option('bp-pages');
	if(isset($page_array['register'])){
		$id = $page_array['register'];
		if(is_page($id))
			return;
	}
		
    global $post, $user_ID;
	?>	
    
	<div id="login-form"  class="white-popup-block com mfp-hide">   
    
    	<div class="mfp-title">
        	
            <h3>Sign Up or Login to view the Free <?php echo $post->post_title ; if($post->post_type=='course') echo ' course'; ?>.</h3>
            
        </div>
              
        <div id="login-signup-div" class="row loginwrapper tab_content_login clearfix">
            
            <div class="col-md-5 col-sm-5 login-sub visible login">
    
                <?php do_action( 'wordpress_social_login' ); ?> 
                
            </div><!--login-sub ends-->
            
		   <?php
            ?>
            
            <div class="col-md-6 col-md-offset-1 col-sm-6 col-sm-offset-1 login-sub visible register"> 
            
            	<?php intellipaat_login_form(); ?><!--login div ends-->             
            
            	<?php intellipaat_signup_form(); ?><!--signup div ends-->
                    
            </div><!--login sub ends-->
        </div>
    
    	<div class="mfp-footer">
        	
            <p class="login-text">Don't have an account? <a id="toggle-signup-div" href="#signup-div">Sign up</a>.</p>
        	
            <p class="signup-text">Already have an account? <a id="toggle-login-div"  href="#login-div">Login</a>.</p>
            
        </div>
        
    </div>
    <?php //wp_enqueue_script('jquery-cookie');
}

function intellipaat_login_form(){
	
	 global $post, $user_ID;  
	
	$login_link 	= wp_login_url().'?redirect_to='.urlencode(get_permalink($post->ID));//apply_filters('wplms_login_widget_action',vibe_site_url( 'wp-login.php', 'login-post' ));
	$login_nonce 	= wp_create_nonce("intellipaat_visitor_secure_login_nonce");
	?>
        <div id="login-div" class="login-div clearfix">
            
            <span id="loginerrors" class="clearfix"></span>
            
            <form method="post" action="<?php echo $login_link; ?>" name="loginform" id="login_form" class="wp-user-form">  
                
                <p>Login with your email</p>                               
            
                <div class="input-group">
                    <span class="input-group-addon icon-envelope"></span>
                    <input type="text" class="form-control" name="log" required="required"  size="20" id="user_login" tabindex="1" placeholder="Email Address OR Username"/> 
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon icon-lock"></span>
                    <input type="password"  class="form-control"  name="pwd" required="required"  placeholder="Password" id="ipass" tabindex="2" />
                </div>
                <br />   
                <p class="fl">
                    <label class="rememberme"><input name="rememberme" tabindex="3" type="checkbox" id="rememberme" value="forever" /><?php _e( ' Remember Me', 'vibe' ); ?></label>
                </p>    
                <?php do_action( 'bp_sidebar_login_form' ); ?>
                <input type="hidden" name="testcookie" value="1" />          
                <input type="submit" name="wp-submit"  id="submit" value="Log-in"  data-loading-text="Logging in..." data-complete-text="Logged in"  tabindex="4" class="pull-right user-submit" onClick="ga('send', 'event', { eventCategory: 'button', eventAction: 'click', eventLabel: 'submit'});" />   
                
                <?php do_action( 'login_form' ); //BruteProtect FIX ?>                                         
               
             </form>
             
             <p class="clearfix text-center">or <a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" tabindex="8" title="<?php _e('Forgot Password','vibe'); ?>"><?php _e('Forgot Password','vibe'); ?></a></p>
        
        </div>
    
    <?php
}

function  intellipaat_signup_form(){
	
	 global $post, $user_ID;  
	
	$signup_nonce	= wp_create_nonce("intellipaat_visitor_secure_signup_nonce");
	$signup_link 	= admin_url('admin-ajax.php?action=intellipaat_visitor_secure_signup');
	?>
    <div id="signup-div" class="signup-div clearfix">
                	
        <span id="signuperrors" class="clearfix"></span>
    
         <form method="post" action="<?php echo $signup_link; ?>" name="signup_form" id="signup_form" class="wp-user-form">                                 
        
            <div class="row name">
               <div class="input-group  col-md-6">
                  <span class="input-group-addon  icon-user"></span>
                  <input type="text" class="form-control billing_first_name" name="first_name" required="required"  size="20" id="first_name" tabindex="1" placeholder="First Name *"/> 
                </div>
               <div class="input-group  col-md-6">
                  <span class="input-group-addon  icon-user"></span>
                  <input type="text" class="form-control billing_last_name" name="last_name" required="required"  size="20" id="last_name" tabindex="2" placeholder="Last Name *"/> 
                </div>
            </div>
            <br />
            <div class="input-group">
                <span class="input-group-addon icon-globe"></span>
                <select class="form-control chosen-select billing_country" name="country" required="required" tabindex="3"  data-placeholder="<?php _e( 'Choose country &hellip; *', 'woocommerce' ); ?>" title="Select your Country">	
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
                <input type="email" class="form-control billing_email" name="user_email" required="required"  size="20" id="user_login" tabindex="4" placeholder="Email Address *"/> 
            </div>
            <br />
            <div class="input-group">
                <label class="" for="account_password"><span></span></label>
                <span class="input-group-addon icon-lock"></span>
                <input type="password"  class="form-control"  name="account_password" required="required"  placeholder="Password" id="account_password" tabindex="5" />
            </div>
            <br  />
            <div class="input-group">
                <span class="input-group-addon icon-telephone-24"></span>
                <input type="text"  class="form-control billing_phone"  name="phone"  placeholder="Phone" id="phno" tabindex="6" />
            </div>
            <br /> 
            <input type="hidden" name="nonce" value="<?php echo $signup_nonce ; ?>" class="SecKey" /> 
            <input type="hidden" name="page_id" value="<?php echo $post->ID ; ?>" />        
            <input type="hidden" name="lead_source" value="<?php echo $post->post_type ; ?>" />  
            <input type="hidden" name="redirect_to" value="<?php echo get_permalink($post->ID) ; ?>" />       
            <input type="submit" name="user-submit"  id="signup_submit" value="Create an account" tabindex="7" data-loading-text="Please wait, Creating an account..." data-complete-text="Account created"  class="pull-right user-submit" onClick="ga('send', 'event', { eventCategory: 'button', eventAction: 'click', eventLabel: 'submit'});" />                        
           
         </form>
         
    </div>
    
<?php
}
?>