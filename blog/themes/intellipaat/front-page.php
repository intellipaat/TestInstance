<?php
/**
 * Template Name: No Title
 */
get_header();
?>

<section id="slider-strip" class="clearfix">
    <div class="container">
    
        <div class="row">
        	<div class="col-md-7">
            	<div class="highlights">
            		<h2>Self-Paced &amp; Instructor Led Online Courses</h2>
                    <h4>With 24x7 Lifetime Access &amp; Support</h4>
                    <div class="heading-title">Leaders in <h1> Big Data Hadoop Training</h1></div>
                    <div id="jump_to" class="hidden-xs clearfix"><?php intellipaat_jump_to(); ?></div>
                    <a class="button browse_course_link" href="<?php echo all_course_page_link() ?>big-data/">Browse all courses</a>
                </div>
            </div>
        	<div class="col-md-5">
            	<div class="offer">
                	<?php 
						$home_offer_banner = vibe_get_option('home_offer_banner');
						if(isset($home_offer_banner) && $home_offer_banner){
							echo '<img src="'.$home_offer_banner.'" class="img-responsive" />';
						}	
					?>
                </div>
            </div>
        </div>
        
        <div id="features_list" class="row">
        
        	<div class="col-md-3 col-sm-6 col-xs-6">
                <div class="feature_icon"> <i class="icon-users"></i></div>
                <h4>+<?php echo intellipaat_userbase() ; ?> Users Base</h4>
                 <div class="clearfix"></div>
            </div>
            
        	<div class="col-md-3 col-sm-6 col-xs-6">
                <div class="feature_icon"> <i class="icon-database-5"></i></div>
                <h4>+40 Corporate Clients</h4>
                <div class="clearfix"></div>
            </div>
            
        	<div class="col-md-2 col-sm-6 col-xs-6">            
                <div class="feature_icon"> <i class="icon-book-open"></i></div>
                <h4>+150 courses.</h4>
                <div class="clearfix"></div>
            </div>
            
        	<div class="col-md-4 col-sm-6 col-xs-6">            
                <div class="feature_icon last"> <i class="icon-phone"></i></div>
                <h4> Learn Anytime, Anywhere, Any device!</h4>
                <div class="clearfix"></div>
            </div>
            
		</div>
    </div>
</section>

<section class="stripe course_tabs">
	<div class="container">
    	<div class="row">
				<h3 class="heading">Popular Courses</h3>
                <?php echo do_shortcode('[home_page_course]');?>
                <div class="aligncenter text-center" style="max-width:550px; width: 100%;">
                	<a href="<?php echo all_course_page_link() ?>big-data/" class="button aligncenter all_course_link"> Browse All courses </a>
                </div>
    	</div>
    </div>
</section>



<section id="clients" class="stripe">
	<div class="container">
    	
    	<div class="row">
        	<div class="col-md-5 col-sm-6">
            	<div class="clients">       
                    <h3 class="heading">40+ Corporate Clients <a class="button plain hidden-xs" href="<?php echo site_url('clients/')?>">Show All</a></h3>
                    <a href="<?php echo site_url('clients/')?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri()?>/images/corporate_clients.png"></a>
                </div>
            </div>
        	<div id="featured-video" class="col-md-5 col-md-offset-2 col-sm-6">            
                <iframe src="https://www.youtube.com/embed/q7r980_ugeM" width="100%" height="250" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
            </div>
    	</div>
        
    </div>
</section>

<section id="media" class="stripe">
	<div class="container">
    	
    	<div class="row">
        	<div class="col-md-8"> 
            	<div id="reviews">
            		<h3 class="heading">Reviews <a class="button plain hidden-xs" href="<?php echo site_url('reviews/'); ?>">Show All</a></h3>             
                    <div class="row">
                    <?php //echo featured_reviews(); ?>
                    <div class="col-md-6 col-sm-6"><div id="review-11132" class="review-body"><div class="review-header clearfix"><strong>Career-oriented Training</strong><div class="comment-rating star-rating fr"><span class="fill"></span><span class="fill"></span><span class="fill"></span><span class="fill"></span><span class="fill"></span></div></div><p class="comment-content">Thanks for making this platform available. I hope to bring more people from Nigeria to embrace intellipaat as the bridge to filling the gaps in their career needs.</p><div class="comment-author vcard clearfix"> <cite class="fn">-- Paschal Ositadima</cite><div class="linkedin fr">Follow Me on <a href="https://pk.linkedin.com/pub/paschal-ositadima/23/a29/331" rel="nofollow noindex" class="linkedin_url">LinkedIn</a></div></div><div class="author-img img-rounded"> <img width="50" height="50" class="avatar avatar-50 photo" src="<?php echo get_stylesheet_directory_uri(); ?>/images/paschal_thumb.jpg" alt=""></div></div></div>
                    <div class="col-md-6 col-sm-6"><div id="review-10828" class="review-body"><div class="review-header clearfix"><strong>Excellent Training Package!</strong><div class="comment-rating star-rating fr"><span class="fill"></span><span class="fill"></span><span class="fill"></span><span class="fill"></span><span class="fill"></span></div></div><p class="comment-content">It was a wonderful experience and learning from Intellipat trainers. The trainers were hands on and provided real time scenario's. For me learning cutting edge and latest technologies intellipat is the right place!!!</p><div class="comment-author vcard clearfix"> <cite class="fn">-- Vikrant Singh</cite><div class="linkedin fr">Follow Me on <a href="https://in.linkedin.com/pub/vikrant-singh/15/425/684" rel="nofollow noindex" class="linkedin_url">LinkedIn</a></div></div><div class="author-img img-rounded"> <img width="50" height="50" class="avatar avatar-50 photo" src="<?php echo get_stylesheet_directory_uri(); ?>/images/vikrant_thumb.jpg" alt=""></div></div></div>
                    </div>
                </div>               
            </div>
        	<div class="col-md-4 col-sm-6 col-sm-offset-3 col-md-offset-0"> 
            	<div class="media" >
                    <h3 class="heading">In Media <a class="button plain hidden-xs" href="<?php echo site_url('media/'); ?>">Show All</a></h3>        
                    <a href="<?php echo site_url('media/'); ?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/news_icons.png"></a>
                </div>
            </div>        	
    	</div>			
        	
    	<?php /*?><div class="row" id="follow_us">
        	<div class="col-md-4 col-sm-7">            
                <div id="newlsetter"> 
                	
					<script type="text/javascript">
                    //<![CDATA[
                    if (typeof newsletter_check !== "function") {
						window.newsletter_check = function (f) {
							var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
							if (!re.test(f.elements["ne"].value)) {
								alert("The email is not correct");
								return false;
							}
							return true;
						}
                    }
                    //]]>
                    </script>
                    
                    <div class="newsletter newsletter-subscription">
                        <form method="post" action="<?php echo site_url(); ?>/wp-content/plugins/newsletter/do/subscribe.php" onsubmit="return newsletter_check(this)">
                            
                            <input class="newsletter-email" type="email" placeholder="Enter e-mail for newsletter" name="ne" size="30" required>
                            <input class="newsletter-submit" type="submit" value="Subscribe"/>
                               
                        </form>
                    </div>
                </div>
            </div>
        	<div class="col-md-4 col-sm-5 hidden-xs">            
                <div id="social_icons">
                      <?php  echo vibe_socialicons(); ?>
                 </div>
            </div>
        	<div class="col-md-4 hidden-sm hidden-xs">            
               <div id="footer-right">                    
                    <a class="btns ufo" href="<?php echo esc_url( get_permalink( get_page_by_title( 'Intellipaat For Organizations' ) ) ); ?>">
                        <i class="icon-building-24"></i>
                        Intellipaat For Organizations
					</a>                    
               </div>
            </div>
    	</div><?php */?>
        
    </div>
</section>

<?php
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<section id="content">
    <div class="container">
            <?php
                the_content();           
            ?>
    </div>
</section>
<?php  
endwhile;
endif;
?>
<?php
get_footer();
?>