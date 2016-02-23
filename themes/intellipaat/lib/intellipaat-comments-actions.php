<?php

// Add the comment meta (saved earlier) to the comment text
// You can also output the comment meta values directly to the comments template  

add_filter( 'comment_text', 'intellipaat_modify_comment' ,11);
function intellipaat_modify_comment( $text ){
   global $comment;

	if(get_post_type($comment->comment_post_ID) == 'course'){
		$url = ('http://' == $comment->comment_author_url) ? '' : $comment->comment_author_url;
		$url = esc_url( $url, array('http', 'https') );
	  	if( $url ) {
				$url  = '<div class="pull-right">Follow Me on <a class="linkedin_url" rel="nofollow noindex" href="' . esc_attr( $url  ) . '">LinkedIn</a></div>';
				$text =  $text.$url ;
		  }   
	}
	return $text;
}


// Apply filter
add_filter( 'get_avatar' , 'my_custom_avatar' , 11 , 5 );

function my_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	if(!is_admin())
	{
		
   /* $user = false;
	
    if ( is_numeric( $id_or_email ) ) {
			
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
			
        } elseif ( is_object( $id_or_email ) ) {
			
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id' , $id );
            }
			
    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }*/
		
   // if ( !$user && is_object( $user ) ) {
			
		$avatar = 'https://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s='.$size;
		$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			
    //}
	}
    return $avatar;
}


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
	{
        update_comment_meta($comment_id,'order_on_review',intval($_POST['order_on_review']));
	}else
	{
        update_comment_meta($comment_id,'order_on_review',intval(0));
	}
	if(isset($_POST['order_on_course']) && intval($_POST['order_on_course']) > 0)
	{
        update_comment_meta($comment_id,'order_on_course',intval($_POST['order_on_course']));
	}else
	{
        update_comment_meta($comment_id,'order_on_course',intval(0));
	}
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

/*add_action( 'pre_get_comments', 'my_comments_orderby' );
function my_comments_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'order_on_review' == $orderby ) {
        $query->set('meta_key','order_on_review');
        $query->set('orderby','meta_value_num');
    }
	if( 'order_on_course' == $orderby ) {
		$query->set('meta_key','order_on_course');
        $query->set('orderby','meta_value_num');
    }
}*/

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

add_action('save_post','add_filter_to_comments_query');

/************************************************************************************************/

function bbp_buddypress_add_pending( $action = '' ) {
		$user_id  = bp_loggedin_user_id();
		global $wpdb;
        $new = $wpdb->get_results("select * from ip_posts where post_author='".$user_id."' ORDER BY `ip_posts`.`ID` DESC limit 1");
        $table1 = "ip_posts";
        $data_array = array('post_status' => 'pending');
        $where = array('ID' => $new[0]->ID);
        $wpdb->update( $table1, $data_array, $where );
}
add_action( 'bbp_new_reply', 'bbp_buddypress_add_pending', 1 );


function bbp_buddypress_new_forum_pending( $action = '' ) {
		$user_id  = bp_loggedin_user_id();
		global $wpdb;
        $new = $wpdb->get_results("select * from ip_posts where post_author='".$user_id."' ORDER BY `ip_posts`.`ID` DESC limit 1");
        $table1 = "ip_posts";
        $data_array = array('post_status' => 'pending');
        $where = array('ID' => $new[0]->ID);
        $wpdb->update( $table1, $data_array, $where );
}
add_action( 'bbp_new_forum', 'bbp_buddypress_new_forum_pending', 1 );


function bbp_buddypress_new_topic_pending( $action = '' ) {
		$user_id  = bp_loggedin_user_id();
		global $wpdb;
        $new = $wpdb->get_results("select * from ip_posts where post_author='".$user_id."' ORDER BY `ip_posts`.`ID` DESC limit 1");
        $table1 = "ip_posts";
        $data_array = array('post_status' => 'pending');
        $where = array('ID' => $new[0]->ID);
        $wpdb->update( $table1, $data_array, $where );
}
add_action( 'bbp_new_topic', 'bbp_buddypress_new_topic_pending', 1 );

/****************************************
* extra comment fields
*/
/*
add_action( 'comment_form_logged_in_after', 'intellipaat_additional_fields' );
add_action( 'comment_form_after_fields', 'intellipaat_additional_fields' );

function intellipaat_additional_fields () {
  echo '<p class="comment-form-position">'.
  '<label for="position">' . __( 'Your current Position' ) . '<span class="required">*</span></label>'.
  '<input id="position" class="form_field" name="position" type="text" size="30"  tabindex="4" /></p>';

}

// Save the comment meta data along with comment

add_action( 'comment_post', 'intellipaat_save_comment_meta_data' );
function intellipaat_save_comment_meta_data( $comment_id ) {
 
 if ( ( isset( $_POST['position'] ) ) && ( $_POST['position'] != '') )
  $position = wp_filter_nohtml_kses($_POST['position']);
  add_comment_meta( $comment_id, 'position', $position );
  
}

add_filter( 'preprocess_comment', 'intellipaat_verify_comment_meta_data' );
function intellipaat_verify_comment_meta_data( $commentdata ) {
  if ( ! isset( $_POST['position'] ) ||  empty( $_POST['position'] ))
  wp_die( __( 'Error: Please fil required information (Your Position). Go back and retry again.' ) );
  return $commentdata;
}
*/


/*
add_filter( 'get_comment_author', 'intellipaat_modify_comment_author' );

function intellipaat_modify_comment_author( $text ){
   global $comment;

  if(get_post_type($comment->comment_post_ID) == 'course'){
  		if(!is_admin()){
		   if( $position = get_comment_meta( get_comment_ID(), 'position', true ) ) {
			$position = '<br /><span  class="position">' . esc_attr( $position ) . '</span>';
			$text = $text.$position  ;
		  } 
		}
		else{
			 if( $position = get_comment_meta( get_comment_ID(), 'position', true ) ) {
			$position = ' (' . esc_attr( $position ).')';
			$text = $text.$position  ;
		  } 
		}
  }
  
  return $text;
}

// Add an edit option to comment editing screen  

add_action( 'add_meta_boxes_comment', 'intellipaat_extend_comment_add_meta_box' );
function intellipaat_extend_comment_add_meta_box() {
    add_meta_box( 'position', __( 'A persons Extra Information' ), 'intellipaat_extend_comment_meta_box', 'comment', 'normal', 'high' );
}

function intellipaat_extend_comment_meta_box ( $comment ) {
    $position = get_comment_meta( $comment->comment_ID, 'position', true );
    wp_nonce_field( 'intellipaat_extend_comment_update', 'intellipaat_extend_comment_update', false );
    ?>
    <p>
        <label for="position"><?php _e( 'Position' ); ?></label>
        <input type="text" name="position" id="position" value="<?php echo esc_attr( $position ); ?>" class="widefat" />
    </p>
    <?php
}
// Update comment meta data from comment editing screen 

add_action( 'edit_comment', 'intellipaat_extend_comment_edit_metafields' );

function intellipaat_extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['intellipaat_extend_comment_update'] ) || ! wp_verify_nonce( $_POST['intellipaat_extend_comment_update'], 'intellipaat_extend_comment_update' ) ) return;

  if ( ( isset( $_POST['position'] ) ) && ( $_POST['position'] != '') ) :
  $phone = wp_filter_nohtml_kses($_POST['position']);
  update_comment_meta( $comment_id, 'position', $phone );
  else :
  delete_comment_meta( $comment_id, 'position');
  endif;

}*/


?>