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

add_filter( 'comment_form_defaults', 'remove_comment_form_allowed_tags' );
function remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;
}

function additional_comment_columns( $columns )
{
	return array_merge( $columns, array(
		'order_on_review' => __( 'Order on Review' ),
		'order_on_course' => __( 'Order on Course' )
	) );
}
add_filter( 'manage_edit-comments_columns', 'additional_comment_columns', 10, 1 );

add_filter( 'manage_edit-comments_sortable_columns', 'my_sortable_comments_column' );
function my_sortable_comments_column( $columns ) {
    $columns['order_on_review'] = 'order_on_review';
	$columns['order_on_course'] = 'order_on_course';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}

function my_quick_edit_custom_box($column_name, $screen, $name)
{ 
   //if($name != 'comments' && ($column_name != 'order_on_review' || $column_name != 'order_on_course')) return false;
?>
    <fieldset>
        <div id="my-custom-content" class="inline-edit-col">
            <label>
                <span class="title"><?php if($column_name == 'order_on_review') _e('Order on Review', 'my_plugin'); ?></span>
                <span class="input-text-wrap"><input type="text" name="<?php echo $column_name; ?>" class="ptitle" value=""></span>
            </label>
            <label>
                <span class="title"><?php if($column_name == 'order_on_course') _e('Order on Course', 'my_plugin'); ?></span>
                <span class="input-text-wrap"><input type="text" name="<?php echo $column_name; ?>" class="ptitle" value=""></span>
            </label>
        </div>
    </fieldset>
<?php 
}
//add_action('quick_edit_custom_box', 'my_quick_edit_custom_box', 10, 3);

function myplugin_comment_column( $column, $comment_ID )
{
	switch ( $column ) {
		case 'order_on_review': 
			echo get_comment_meta( $comment_ID, $column , true );
		break;
		case 'order_on_course': 
			echo get_comment_meta( $comment_ID, $column , true );
		break;
	}
}
add_action( 'manage_comments_custom_column', 'myplugin_comment_column', 10, 2 );

add_action('add_meta_boxes_comment','pmg_comment_tut_add_meta_box');
function pmg_comment_tut_add_meta_box()
{
    add_meta_box('comments_order',__('Comments Order'),'pmg_comment_tut_meta_box_cb','comment','normal','high');
}
function pmg_comment_tut_meta_box_cb($comment)
{
    $order_on_review=get_comment_meta($comment->comment_ID,'order_on_review',true);
	$order_on_course=get_comment_meta($comment->comment_ID,'order_on_course',true);
    wp_nonce_field('pmg_comment_update','pmg_comment_update',false);
    ?>
    <p>
        <label for="order_on_review"><?php _e('Order on Review'); ?></label>
        <input type="text"name="order_on_review" value="<?php echo $order_on_review; ?>"class="widefat"/>
    </p>
    <p>
        <label for="order_on_course"><?php _e('Order on Course'); ?></label>
        <input type="text"name="order_on_course" value="<?php echo $order_on_course; ?>"class="widefat"/>
    </p>
    <?php
}

add_action('edit_comment','pmg_comment_tut_edit_comment');

function pmg_comment_tut_edit_comment($comment_id)
{
    if(!isset($_POST['pmg_comment_update'])||!wp_verify_nonce($_POST['pmg_comment_update'],'pmg_comment_update'))return;
    if(isset($_POST['order_on_review']) && intval($_POST['order_on_review']) > 0)
        update_comment_meta($comment_id,'order_on_review',intval($_POST['order_on_review']));
	if(isset($_POST['order_on_course']) && intval($_POST['order_on_course']) > 0)
        update_comment_meta($comment_id,'order_on_course',intval($_POST['order_on_course']));
}
// ADD THE COMMENTS META FIELDS TO THE COMMENTS ADMIN PAGE

//Function: Sort Post Views in WP dashboard based on the Number of Views (ASC or DESC).
function sort_views_column( $vars ) 
{
    if ( isset( $vars['orderby'] ) && 'order_on_review' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'order_on_review', //Custom field key
            'orderby' => 'meta_value_num') //Custom field value (number)
        );
    }
	if ( isset( $vars['orderby'] ) && 'order_on_course' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'order_on_course', //Custom field key
            'orderby' => 'meta_value_num') //Custom field value (number)
        );
    }
    return $vars;
}
add_filter( 'request', 'sort_views_column' );

add_action( 'restrict_manage_comments', 'ba_admin_posts_filter_restrict_manage_posts' );

function ba_admin_posts_filter_restrict_manage_posts()
{
    global $wpdb;
   $courses = get_posts(array('post_type'=>'course', 'posts_per_page'=>-1, 'order'=> 'ASC', 'orderby' => 'title'));
?>
<select name="course">
<option value=""><?php _e('All Courses', 'baapf'); ?></option>
<?php
    $current = isset($_GET['course'])? $_GET['course']:'';
    foreach ($courses as $course) {
        if($course->ID == $current) { ?>
           <option value="<?php echo $course->ID; ?>" selected="selected"><?php echo $course->post_title; ?></option>
           <?php } else { ?>
           <option value="<?php echo $course->ID; ?>"><?php echo $course->post_title; ?></option>
           <?php } 
		    } ?>
</select> 
<?php
}

//restrict the posts by an additional author filter
function add_filter_to_comments_query($query){
    global $pagenow;
    if ( is_admin() && $pagenow=='edit-comments.php' && isset($_GET['course']) && $_GET['course'] != '') {
            //set the query variable for 'author' to the desired value
                $query->query_vars['post_id'] = $_GET['course'];
    }
}

add_action('pre_get_comments','add_filter_to_comments_query');
?>