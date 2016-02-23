<?php global $post; ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
<title><?php echo wp_title('|',true,'right'); ?></title>

<?php $excluded_hreflang_pages = vibe_get_option('excluded_hreflang_pages');

if((is_page() && !is_page($excluded_hreflang_pages)) || is_singular( array( 'course', 'post', 'tutorial' ,'news', 'jobs' ,'interview-question') ) || is_post_type_archive( array( 'course', 'post', 'tutorial' ,'news', 'jobs' ,'interview-question' ) )  || is_tax( 'tuts-category') || is_tax( 'course-cat') || is_tax('jobs-category')  || is_tax('iq-category')) { ?>
	<?php if(TLD == 'com') { ?>
        <link rel="alternate" hreflang="en-us" href="https://intellipaat.com<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>" />
        <link rel="alternate" hreflang="en-in" href="https://intellipaat.in<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
        <link rel="alternate" hreflang="en-gb" href="http://intellipaat.uk<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
    <?php } ?>
    
    <?php if(TLD == 'in') { ?>
        <link rel="alternate" hreflang="en-in" href="https://intellipaat.in<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
        <link rel="alternate" hreflang="en-us" href="https://intellipaat.com<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>" />
        <link rel="alternate" hreflang="en-gb" href="http://intellipaat.uk<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
    <?php } ?>
    
    <?php if(TLD == 'uk') { ?>
        <link rel="alternate" hreflang="en-gb" href="http://intellipaat.uk<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
        <link rel="alternate" hreflang="en-in" href="https://intellipaat.in<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>"/>
        <link rel="alternate" hreflang="en-us" href="https://intellipaat.com<?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); ?>" />
    <?php } ?>
<?php } ?>


<?php
$layout = vibe_get_option('layout');
if(!isset($layout) || !$layout)
    $layout = '';

wp_head();
?>
<link rel='stylesheet' id='page-css' href='https://intellipaat.com/wp-content/themes/intellipaat/style_refer.css' type='text/css' media='all'/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

</head>
<body <?php body_class($layout); ?>>

<?php if($header_banner = vibe_get_option('header_banner_code')) { ?>
	<div id="header_banner" class="header_banner_wrapper">
		<div class="container">
			<div class="row">
            	<div class="col-sm-12 header_banner text-center"><span><?php echo $header_banner ?></span></div>
			</div>
		</div>
	</div>
    <style>
		.header_banner_wrapper{ 
			background-color: <?php echo vibe_get_option('header_banner_bg_color') ? vibe_get_option('header_banner_bg_color') : '#ffffff'?>;
			<?php echo vibe_get_option('header_banner_bg_image') ? 'background-image:url("'.vibe_get_option('header_banner_bg_image').'");' : ''?>
			background-repeat:repeat-x;
			background-position:center top;
			padding:5px 0;
			font-size:13px;
			font-weight:600;
		}
		.header_banner_wrapper *{color:<?php echo vibe_get_option('header_banner_color') ? vibe_get_option('header_banner_color') : '#3A3A3A'?>;}
	</style>
<?php } ?>

<div id="global" class="global">
    <div class="pagesidebar">
        <div class="sidebarcontent">    
            <h2 id="sidelogo">
            <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
            </h2>
            <?php
                $args = apply_filters('wplms-mobile-menu',array(
                    'theme_location'  => 'mobile-menu',
                    'container'       => '',
                    'menu_class'      => 'sidemenu',
                    'fallback_cb'     => 'vibe_set_menu',
                ));

                wp_nav_menu( $args );
            ?>
        </div>
        <a class="sidebarclose"><span></span></a>
    </div>  
    <div class="pusher">
        <?php
            $fix=vibe_get_option('header_fix');
        ?>
        <div id="headertop" class="<?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-7">
                    	<?php

                            if(is_home()){
                                echo '<h1 id="logo">';
                            }else{
                                echo '<h2 id="logo">';
                            }
                        ?>
                        
                            <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                        <?php
                            if(is_home()){
                                echo '</h1>';
                            }else{
                                echo '</h2>';
                            }
                        ?>
                    </div>  
                    <div class="top-navigation col-md-9 col-sm-9 col-xs-5">
                    
                        <div class="country_navigation countrydropdownmenu fr hidden-sm hidden-xs">                     		              
                            <ul id="countrydropdownmenu" class="menu">
                                <li>
                                    <a href="javascript:void(0)" class="current_flag">
                                        <span class="<?php echo $_SERVER['HTTP_HOST']; ?> fl"> </span>
                                        <span class="arrow fl"></span>
                                    </a>
                                    <?php wp_nav_menu(array(  'theme_location' => 'country','container' => '', 'menu_class'      => 'sub-menu', 'link_before'     => '<span class="flag alignright"></span>'));?>
                                </li>
                            </ul>                
                        </div>	
                        
                        <div class="trigger fr hidden-md hidden-lg">                        	              
                            <a id="trigger">
                                <span class="lines"></span>
                            </a>
                        </div>
					
					<?php
                            $args = apply_filters('wplms-top-menu',array(
                                'theme_location'  => 'top-menu',
                                'container'       => '',
                                'menu_class'      => 'topmenu',
                                'fallback_cb'     => 'vibe_set_menu',
                            ));

                       		wp_nav_menu( $args );
                    ?>
                    <?php /*
                    if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                        ?>
                        <ul class="topmenu">
                            <li><a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php bp_loggedin_user_avatar( 'type=full' ); ?><?php bp_loggedin_user_fullname(); ?></a></li>
                        </ul>
                    <?php
                    else :
                        ?>
                        <ul class="topmenu">
                            <li><a href="#login" class="smallimg vbplogin"><?php _e('Login','vibe'); ?></a></li>
                            <li><?php if ( function_exists('bp_get_signup_allowed') && bp_get_signup_allowed() ) :
                                printf( __( '<a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'">'.__('Sign Up','vibe').'</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
                            endif; ?>
                            </li>
                        </ul>                     
                        
                        
                    <?php
                    endif;*/ ?> 
                     
                        <div id="vibe_bp_login">
                   			<?php  if ( function_exists('bp_get_signup_allowed')){
								the_widget('vibe_bp_login',array(),array());   
							}?>
                   		</div>
                        
                    </div>
                   
                    
                </div>  
            </div>
        </div>
        <header id="header-main" class="fix">
            <div class="container">
            
                <div class="row">
                
                    <div class="col-md-11">
                    	<div class="fl hidden-xs">
							<?php intellipaat_browse_course_menu(vibe_get_option('browse_courses')); ?>
                        </div>
                        <div id="searchbox" class="fl">
                            <form role="search" method="get" id="search-form" action="<?php echo home_url( '/' ); ?>">
                                <div>
                                    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Search For Training...','vibe'); ?>" />
                                    <input type="hidden" value="course" name="post_type" />                                      
                                    <button type="submit" role="submit"><i class="icon-search-2"></i></button>
                                </div>
                            </form>
                            
                        
                    	</div> 
                        
                        
						<?php 	
								if(all_course_page_id() == get_the_ID()){
									$term = get_term_by( 'slug', get_query_var('course_category') , 'course-cat' );
									$popular_searches = get_field('intellipaat_top_searches', $term, 0);									
								}
								else if(is_single() || is_page() || is_singular('course') ){
									$popular_searches = get_field('intellipaat_top_searches');
								}else{
									$queried_object = get_queried_object();	
									if(isset($queried_object->taxonomy)){
										$popular_searches = get_field('intellipaat_top_searches', $queried_object, 0);
									}
								}
								
								if(!isset($popular_searches) || empty($popular_searches ))
									$popular_searches = vibe_get_option('default_popular_searches');
									
								$popular_searches = str_replace(array('<p>','</p>'),array('',''),$popular_searches);
								
							?>
                                
                            <div id="top_searches" class="fl  hidden-sm">
                                 <span>Popular Search: <?php echo $popular_searches ?></span>
                            </div>
                             
                    </div>  
                                      
                    <div class="col-md-1 hidden-sm hidden-xs">                                                                 
                        <?php /*
                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'walker'          => new vibe_walker,
                                // 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); */
                        ?> 
                    </div>
						
                </div>
            </div>
        </header>