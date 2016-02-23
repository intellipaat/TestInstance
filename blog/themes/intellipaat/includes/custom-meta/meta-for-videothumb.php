<?php 

add_action( 'load-post.php', 'videothumb_meta_boxes_setup' );
add_action( 'load-post-new.php', 'videothumb_meta_boxes_setup' );

function videothumb_meta_boxes_setup() {
  
  add_action( 'add_meta_boxes', 'add_videothumb_meta_boxes' );
  
  add_action( 'save_post', 'save_videothumb_meta', 10, 2 );
}

function add_videothumb_meta_boxes() {
	
	$screens = array( 'course', 'tutorial', 'interview-question', 'jobs' );

	foreach ( $screens as $screen ) {
		
		  add_meta_box(
			'intellipaat-videothumb',      // Unique ID
			esc_html__( 'Youtube video id', 'intellipaat' ),    
			'videothumb_meta_box', //callback to show metabox in admin  
			$screen,         
			'side',         
			'default'         
		  );
	}
}

function videothumb_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'intellipaat_videothumb_nonce' ); ?>

  <p>
    <label for="intellipaat_videothumb">
    <input class="widefat" type="text" name="intellipaat-videothumb" id="intellipaat_videothumb" value="<?php echo esc_attr( get_post_meta( $object->ID, 'intellipaat-videothumb', true ) ); ?>" size="30" />
	
	<?php _e( "Enter youtube paly video id. Example: http://www.youtube.com/watch?v=<strong>SxNJTWZVOQk.v</strong>
", 'intellipaat' ); ?></label>
    <br />
    
  </p>
<?php 
}


function save_videothumb_meta( $post_id, $post ) {

 
  if ( !isset( $_POST['intellipaat_videothumb_nonce'] ) || !wp_verify_nonce( $_POST['intellipaat_videothumb_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['intellipaat-videothumb'] ) ?  $_POST['intellipaat-videothumb']  : '' );

  /* Get the meta key. */
  $meta_key = 'intellipaat-videothumb';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

?>