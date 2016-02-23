<footer class="hidden-sm hidden-xs">
    <div class="container">
        <div class="row">
            <div class="footertop">
                <?php 
                           /* if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('topfootersidebar') ) : ?>
                <?php endif;*/ 
					if(function_exists('intellipaat_recent_posts'))
					//	intellipaat_recent_posts();
				
				?>
            </div>
        </div>
        <div class="row">
            <div class="footerbottom clearfix">
                <?php 
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('bottomfootersidebar') ) : ?>
                <?php endif; ?>
            </div>
       
            <?php if ( $training_in_city = vibe_get_option('training_in_city')){ ?>
            	 <div class="clearfix" style="margin-top:15px;">
                     <div class="col-md-12">
                        <h4 class="footertitle" style="text-transform:none;">Training in Cities</h4>
                        <p style="text-align:justify;"><small><?php echo $training_in_city; ?> </small></p>
                 	</div>						
                 </div>						
            <?php } ?>            
        </div>
    </div> 
</footer>
<div id="footerbottom">
    <div class="container">
        <div class="row">       
            <div <?php /*?>id="footermenu"<?php */?> class="col-md-8 hidden-sm hidden-xs">

<style>
.contry_outr{margin:10px;top: -20px;}
.dropdown-menu_cunty a {color: #000 !important;}
</style>
                <?php
                        $args = array(
                            'theme_location'  => 'footer-menu',
                            'container'       => '',
                            'menu_class'      => 'footermenu',
                            'fallback_cb'     => 'vibe_set_menu',
                        );
                        wp_nav_menu( $args );


							/*$ipAddr = $_SERVER['REMOTE_ADDR'];
							$fetch_ip = "SELECT countryCode, countryName FROM ip_geoipaddress WHERE INET_ATON('$ipAddr') BETWEEN beginIpNum AND endIpNum LIMIT 1";
							$query22 = $wpdb->get_results($fetch_ip);
							$countryCode_CURR=$query22[0]->countryCode;
							
							if($countryCode_CURR=="IN")
							{
								$_SESSION['REMOTE_ADDR_CUREE']=$countryCode_CURR;

							}else 
							{
								$_SESSION['REMOTE_ADDR_CUREE']="Other";
							}*/
						
if($_SESSION[REMOTE_ADDR_CUREE]=='IN'){ ?> <div class="btn-group contry_outr" >  
<input type="hidden" value="Other" id="REMOTE_ADDR_CUREE" name="REMOTE_ADDR_CUREE" />                  
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php echo get_stylesheet_directory_uri()?>/images/flagindia.gif" /> India<span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu_cunty">
                    <li><a href="javascript:void(0);" >
                        <img src="<?php echo get_stylesheet_directory_uri()?>/images/flagus.gif"> USA </a>
                    </li>
                </ul>
            </div> <?php }else { ?> <div class="btn-group contry_outr" >      
<input type="hidden" value="IN" id="REMOTE_ADDR_CUREE" name="REMOTE_ADDR_CUREE" />               
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php echo get_stylesheet_directory_uri()?>/images/flagus.gif"> USA <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu_cunty">
                    <li><a href="javascript:void(0);">
                        <img src="<?php echo get_stylesheet_directory_uri()?>/images/flagindia.gif"> India </a>
                    </li>
                </ul>
            </div> <?php  }


                ?>
					
			





            </div> 
            <div id="copyright" class="col-md-4 text-right">
            	&copy; Copyright 2011-<?php echo date('Y');?> intellipaat.com. All Rights Reserved.
            </div>
                         
            
            	
            
               <?php /*?><div id="footer-right" class="sidebar col-md-3  col-sm-6 hidden-xs">
                    <a class="btns teach " href="<?php echo esc_url( get_permalink( get_page_by_title( 'Become an Instructor' ) ) ); ?>">
                        <i class="icon-rocket"></i>
                        Teach on Intellipaat
                    </a>
                    
                    <a class="btns ufo" href="<?php echo esc_url( get_permalink( get_page_by_title( 'Intellipaat For Organizations' ) ) ); ?>">
                        <i class="icon-building-24"></i>
                        Intellipaat For Organizations
					</a>                    
               </div>
                    	<?php */?>	
            
        </div>

        <div class="row">
        	<div class="secure-pay">
            	<p class="secure-pay-info"><strong>100%</strong> Secure Payments. All major credit & debit cards accepted Or Pay by Paypal.</p>

            	<p class="pay_methods"><?php /*?>Payment method <?php */?><span> </span></p>
             </div>
        </div>
    </div>
    
    <div id="scrolltop">
        <a><i class="icon-arrow-1-up"></i><span><?php _e('top','vibe'); ?></span></a>
    </div>
    
</div>

<nav id="info-stripe" class="navbar navbar-default navbar-fixed-bottom">
  <div class="info-stripe-container">
  
        <p id="how-it-works" class="navbar-left hidden-xs">
        	<a href="#" class="navbar-link"><i class="icon-lightbulb-shine"></i><span class="text">How our support Works?</span></a>
        </p>
        <div id="support-process-wrap" class="hidden">
            <div class="container">
       			 <div class="row">
                    <div class="support-process aligncenter">
                    	<button type="button" class="close-popup" title="Close"><i class="icon-cross"></i></button>
                        <img class="resonsive-img" src="<?php echo get_stylesheet_directory_uri()?>/images/support_process.png">
                    </div>
        		</div>
			</div>
        </div>
        
        <?php /*?><p id="live_chat" class="navbar-right fr">
        	<a href="#" class="navbar-link"><i class="icon-chat mail"></i><span class="text">Live chat</span></a>
        </p><?php */?>
        
        <?php
        		$toll_free_number 	= str_replace('+','', vibe_get_option('toll_free_number'));
        		$mobile_no_1 		= vibe_get_option('mobile_no_1');
        		$mobile_no_2	 	= vibe_get_option('mobile_no_2');
        		$email_address 		= vibe_get_option('email_address');
		?>
        
        <div class="navbar-right"  style="margin-right: 21%;">
        	<div class="contact-no">
				<?php /* ?><span class="india-number" style="margin-right:5px;"> 
                     <span class="phone-2">Your Currency:  <img class="resonsive-img" style="<?php echo $flagindia_effect; ?>" onclick="select_intellipaat_flag_currency('IN');"  src="<?php echo get_stylesheet_directory_uri()?>/images/flagindia.gif">  <img onclick="select_intellipaat_flag_currency('Other');" style="<?php echo $flagus_effect; ?>"  class="resonsive-img" style="margin-right:5px;" src="<?php echo get_stylesheet_directory_uri()?>/images/flagus.gif"> </span>
                </span> <?php */ ?>
                <span class="india-number">
                	<i class="icon-call-old-telephone phone"> </i>  
                    <?php if(!empty($mobile_no_1) ){ ?><span><span class="phone-1"><?php echo $mobile_no_1 ?></span> <?php } ?>
                    <?php if(!empty($mobile_no_2) ){ ?>/ <span class="phone-2"><?php echo $mobile_no_2 ?></span> </span><?php } ?>
                </span> 
                <?php if(!empty($toll_free_number) ){ ?><span class="us-toll"><strong>US :</strong>   <span><?php echo $toll_free_number ?></span>(Toll Free)</span><?php } ?>
                
                <?php if(!empty($email_address) ){ ?>
                    <span class="hdmaito">
                        <a href="mailto:sales@intellipaat.com" style="text-transform:lowercase;">
                            <i class="icon-letter-mail-1 mail"></i><span><?php echo $email_address ?></span>
                        </a>
                    </span>
                <?php } ?>
            </div>
        </div>
    	
  </div>
</nav>
<input type="hidden" value="" id="doc_referrer" name="doc_referrer" />        
<input type="hidden" value="<?php if($_GET['utm_source']){ echo $_GET['utm_source']; } ?>" id="doc_utm_source" name="doc_utm_source" />        
<input type="hidden" value="<?php if($_GET['utm_medium']){ echo $_GET['utm_medium']; } ?>" id="doc_utm_campaign" name="doc_utm_campaign" />        
<input type="hidden" value="<?php if($_GET['utm_campaign']){ echo $_GET['utm_campaign']; } ?>" id="doc_utm_medium" name="doc_utm_medium" />        
</div><!-- END PUSHER -->
</div><!-- END MAIN -->


	<!-- SCRIPTS -->
 
<?php
wp_footer();
?>    
<?php
echo vibe_get_option('google_analytics');
?>
<script type="text/javascript">
   jQuery(document).ready(function(){
	  jQuery("#commentform #author").attr("required","required"); 
	  jQuery("#commentform #email").attr("required","required");
	  jQuery("#commentform #comment").attr("required","required");
	  jQuery("#doc_referrer").val(document.referrer);
		console.log("document referrer== "+document.referrer);
   });

    var _taq = {"id":"2c6998ea-6e8e-4901-bb0b-beffe8e6ab35","events":[],"identify":[],"property":[]};
    (function() {
        var ta = document.createElement('script'); ta.type = 'text/javascript'; ta.async = true; ta.id = "__ta";
        ta.src = '//cdn-jp.gsecondscreen.com/static/tac.min.js';
        var fs = document.getElementsByTagName('script')[0]; fs.parentNode.insertBefore(ta, fs);
    })();

function select_doc_referrer()
	{

    var flagvalue = document.referrer;
    var doc_utm_campaign =  jQuery("#doc_utm_campaign").val();
    var doc_utm_source =  jQuery("#doc_utm_source").val();
    var doc_utm_medium =  jQuery("#doc_utm_medium").val();

	if(doc_utm_campaign!="" && doc_utm_source!="" && doc_utm_medium!="")
	{
		jQuery.ajax({ 
				 data: {action: 'select_doc_referrer', flag_currency:flagvalue, doc_utm_campaign:doc_utm_campaign, doc_utm_source:doc_utm_source, doc_utm_medium:doc_utm_medium},
				 type: 'post',
				 url: ajaxurl,
				 success: function(data) {
					 console.log(data);
					//alert(data);
					   //should print out the name since you sent it along
				}
			});
		}
	
	}
select_doc_referrer();

function select_intellipaat_flag_currency(value)
	{

    var flagvalue = value;
		jQuery.ajax({ 
			 data: {action: 'select_intellipaat_flag_currency', flag_currency:flagvalue},
			 type: 'post',
			 url: ajaxurl,
			 success: function(data) {
				window.location = window.location.href;
				   //should print out the name since you sent it along

			}
		});
	}

/* BOOTSTRAP DROPDOWN MENU - Update selected item text and image */
jQuery(".dropdown-menu li a").click(function () {

    var selText = jQuery(this).text();
    var imgSource = jQuery(this).find('img').attr('src');
    var img = '<img src="' + imgSource + '"/>';        

    jQuery(this).parents('.btn-group').find('.dropdown-toggle').html(img + ' ' + selText + ' <span class="caret"></span>');
value=jQuery("#REMOTE_ADDR_CUREE").val();

select_intellipaat_flag_currency(value);
});

 </script>
</body>
</html>