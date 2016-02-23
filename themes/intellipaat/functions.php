<?php 

//Short version for next two lines but gives warning..... define('TLD', end(explode('.', $_SERVER['HTTP_HOST'])));
$host_name_array = explode('.', $_SERVER['HTTP_HOST']);
define('TLD', end($host_name_array));

add_theme_support( 'post-thumbnails' );


/*
*	All theme related funcitons
*/

if(is_admin()){
	include_once('includes/admin-tools/wp-export.php');
	include_once('includes/admin-tools/alt-editor.php');
	include_once('includes/admin-tools/url-exporter.php');
}


/*
*	All theme related funcitons
*/
include_once('includes/fields/field_multi_keyword_menu.php');
include_once('includes/theme-options.php');
include_once('includes/theme-functions.php');
include_once('includes/course-functions.php');

/*
*	All intellipaat theme shortcodes
*/
include_once('includes/shortcodes/intellipaat-course-tabs.php');
include_once('includes/shortcodes/intellipaat-collapsible.php');
include_once("includes/shortcodes/intellipaat-online-training-calendar.php");
include_once('includes/shortcodes/intellipaat-div-row.php');
include_once('includes/shortcodes/intellipaat-div-featured.php');
include_once('includes/shortcodes/intellipaat-youtub-thumb.php');
include_once('includes/shortcodes/intellipaat-slideshare.php');
include_once('includes/shortcodes/intellipaat-pdf.php');
include_once('includes/shortcodes/intellipaat-reviews.php');

/*
*	All intellipaat theme widgets
*/
//include_once('includes/widgets/intellipaat-post-widget.php');
include_once('includes/widgets/intellipaat-currency-convertor.php');

/*
*	All intellipaat cutom post type
*/
include_once('includes/custom-posts/intellipaat-news.php');
include_once('includes/custom-posts/intellipaat-tutorial.php');
include_once('includes/custom-posts/intellipaat-interview-question.php');
include_once('includes/custom-posts/intellipaat-jobs.php');
 
/*
*	All intellipaat custom meta box for course
*/
include_once('includes/custom-meta/meta-for-videothumb.php');

/*
*	All intellipaat theme actions and filter
*/

include_once('lib/intellipaat-page-actions.php');
include_once('lib/intellipaat-course_page-actions.php');
include_once('lib/intellipaat-admin-actions.php');
include_once('lib/intellipaat-comments-actions.php');
include_once('lib/intellipaat-theme-actions.php');
include_once('lib/intellipaat-security-actions.php');
include_once('lib/intellipaat-woocommerce-actions.php');
include_once('lib/intellipaat-seo-actions.php');
include_once('lib/intellipaat-tutorial-actions.php');
include_once('lib/intellipaat-ajax-actions.php');

//conditional theme action
if(file_exists( get_stylesheet_directory() . '/lib/intellipaat-'.TLD.'-actions.php'))
	include_once( 'lib/intellipaat-'.TLD.'-actions.php');
	
if(TLD != 'com' && TLD != 'us')
	include_once( 'lib/intellipaat-non-com-actions.php');

?>
