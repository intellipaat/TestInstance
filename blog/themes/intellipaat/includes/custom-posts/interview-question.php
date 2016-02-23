<?php 
	register_post_type( 'Interview Questions',
    array(
            'labels' => array(
                    'name' => __( 'Interview Questions' ),
                    'singular_name' => __( 'Interview Questions' )
            ),
    'public' => true,
    'has_archive' => true,
	'rewrite' => array(
				'slug'=>'media',
				'hierarchical'	 		=> true,
				'with_front'			=> false
			),
	'supports'	=> array(
				'title',
				'thumbnail',
				'editor',
				'page-attributes',
				'revision',
				'custom-fields',
				'author',
				'excerpt',
				'revisions',
				'comments'
			)
    //'show_in_menu' => 'edit.php?post_type=course'
    )
);
	
//============= adding post meta ================

add_action( 'load-post.php', 'post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'post_meta_boxes_setup' );

function post_meta_boxes_setup() {
  
  add_action( 'add_meta_boxes', 'add_post_meta_boxes' );
  
  add_action( 'save_post', 'save_post_class_meta', 10, 2 );
}

function add_post_meta_boxes() {
  add_meta_box(
    'intellipaat-post-href',      // Unique ID
    esc_html__( 'Post href', 'intellipaat' ),    
    'post_class_meta_box',   
    'Interview Questions',         
    'side',         
    'default'         
  );
}

function post_class_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'intellipaat_post_class_nonce' ); ?>

  <p>
    <label for="intellipaat-post-href"><?php _e( "Add a link, which will be applied to required content of post.", 'intellipaat' ); ?></label>
    <br />
    <input class="widefat" type="text" name="intellipaat-post-href" id="intellipaat-post-href" value="<?php echo esc_attr( get_post_meta( $object->ID, 'intellipaat-post-href', true ) ); ?>" size="30" />
    
  </p>
<?php 
}


function save_post_class_meta( $post_id, $post ) {

 
  if ( !isset( $_POST['intellipaat_post_class_nonce'] ) || !wp_verify_nonce( $_POST['intellipaat_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['intellipaat-post-href'] ) ?  $_POST['intellipaat-post-href']  : '' );

  /* Get the meta key. */
  $meta_key = 'intellipaat-post-href';

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