<?php 

function intellipaat_interview_question_posttype(){
	
	register_post_type('interview-question',
		array (
			'labels' => array(
				'name'					=> __('Interview Questions', 'intellipaat'),
				'menu_name'				=> __('Interview Questions', 'intellipaat'),
				'singular_name'			=> __('Interview Question', 'intellipaat'),
				'all_items'				=> __('All Interview Questions', 'intellipaat'),
				'parent_item'           => __('Parent Interview Question', 'intellipaat'),
				'parent_item_colon'     => __('Parent Interview Question:', 'intellipaat'),
				'add_new'				=> __('Add Interview Question', 'intellipaat'),
				'add_new_item'			=> __('Add new Interview Question', 'intellipaat'),
				'edit_item'				=> __('Edit Interview Question', 'intellipaat'),
				'new_item'				=> __('New Interview Question', 'intellipaat'),
				'view_item'				=> __('View Interview Question', 'intellipaat'),
				'search_items'			=> __('Search Interview Questions', 'intellipaat'),
				'not_found'				=> __('No Interview Question found', 'intellipaat'),
				'not_found_in_trash'	=> __('No Interview Questions Found in Trash', 'intellipaat')
			),
			'public'				=> true,
			'show_ui'				=> true,
			'has_archive'			=> true,
			'show_in_nav_menus' 	=> true,
			'capability_type'		=> 'page',
			'hierarchical'			=> false,
			'exclude_from_search'	=> true,
			'publicly_queryable'	=> true,
			'query_var'				=> true,
			'map_meta_cap'        	=> true,
			'rewrite'				=> array(
											'slug'					=> 'interview-question',
											'hierarchical'	 		=> true,
											'with_front'			=> false
										),
			'menu_position'			=> 20,
			'menu_icon'				=> 'dashicons-smiley',
			'supports'				=> array(
				'title',
				'thumbnail',
				'editor',
				'page-attributes',
				'revision',
				'custom-fields',
				'comments'
			),
			/*'taxonomies'			=> array('post_tag'),*/
		)
	);
	

	$args = array(
		'hierarchical'          => true,
		'labels'                =>  array(
											'name'                       => _x( 'Interview Question Category', 'taxonomy general name' ),
											'singular_name'              => _x( 'Interview Question Category', 'taxonomy singular name' ),
											'search_items'               => __( 'Search Interview Question Categories' ),
											'popular_items'              => __( 'Popular Interview Question Categories' ),
											'all_items'                  => __( 'All Interview Question Categories' ),
											'parent_item'                => null,
											'parent_item_colon'          => null,
											'edit_item'                  => __( 'Edit Category' ),
											'update_item'                => __( 'Update Category' ),
											'add_new_item'               => __( 'Add New Category' ),
											'new_item_name'              => __( 'New Category Name' ),
											'separate_items_with_commas' => __( 'Separate Categories with commas' ),
											'add_or_remove_items'        => __( 'Add or remove Categories' ),
											'choose_from_most_used'      => __( 'Choose from the most used Categories' ),
											'not_found'                  => __( 'No Categories found.' ),
											'menu_name'                  => __( 'Categories' ),
										),
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array(
											'slug'					=> 'interview-question',
											'hierarchical'	 		=> true,
											'with_front'			=> false
										),
	);

	register_taxonomy( 'iq-category', 'interview-question', $args );
	

}

add_action('init', 'intellipaat_interview_question_posttype');



/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'intellipaat_interview_question_meta_boxes_setup' );
add_action( 'load-post-new.php', 'intellipaat_interview_question_meta_boxes_setup' );
function intellipaat_interview_question_meta_boxes_setup(){
	if ( !is_admin() )
		return;
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes_interview-question', 'intellipaat_add_interview_question_meta_boxes' );	
}
function intellipaat_add_interview_question_meta_boxes(){
	
	$args = array(				  );
	add_meta_box(
		'intellipaat-basic-question-meta',      // Unique ID
		esc_html__( 'Basic interview questions', 'intellipaat' ),    // Title
		'intellipaat_interview_basic_question_meta_box',   // Callback function
		'interview-question',         // Admin page (or post type)
		'advanced',         // Context
		'high',        // Priority
		$args
	);
		
	add_meta_box(
		'intellipaat-advanced-question-meta',      // Unique ID
		esc_html__( 'Advanced interview questions', 'intellipaat' ),    // Title
		'intellipaat_interview_advanced_question_meta_box',   // Callback function
		'interview-question',         // Admin page (or post type)
		'advanced',         // Context
		'high',         // Priority
		$args
	);
	
}

function intellipaat_interview_basic_question_meta_box($post){
	
	$editor_id = 1000;
    $questions = unserialize(get_post_meta( $post->ID, 'intellipaat_interview_basic_question', true) ); 
	$settings = array(
					  	'textarea_name'=>'answer',
						'editor_class' => 'iq-answer',
						'textarea_rows'=> 8
					  ) ;

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'intellipaat_interview_basic_question_meta_box_nonce', 'basic_question_meta_box_nonce' ); ?>
    
    <div class="repeater">
    
        <ul data-repeater-list="basic-questions" class="sortable-item-list">
        
        	<?php if($questions){ ?>
            	<?php foreach($questions as $question){ ?>
                    <li class="repeater-item ui-state-default" data-repeater-item>
                        <table width="100%" class="iqa">
                            <tr>
                                <td class="disable-Selection drag-icon" width="40px" rowspan="2" align="center"><span class="dashicons dashicons-image-flip-vertical"></span></td>
                                <td class="disable-Selection drag-icon" width="80px"><label> <strong>Question : </strong></label></td>
                                <td> <input type="text" name="question" value="<?php echo $question['question'] ?>" class="iq-question" required/></td>
                                <td class="disable-Selection"><button data-repeater-delete type="button" class="button"><span class="dashicons dashicons-trash"></span> Delete</button></td>
                            </tr> 
                            <tr>
                                <td class="disable-Selection drag-icon"><label> <strong>Answer : </strong></label></td>
                                <td class="editor-wrapper" colspan="2"><?php wp_editor(stripslashes(base64_decode($question['answer'])), "editor_id-".$editor_id, $settings ) ?></td>
                            </tr>                        
                        </table>
                    </li>
        			<?php $editor_id++;
				} ?>
        	<?php }else{ ?>
                <li class="repeater-item ui-state-default" data-repeater-item>
                	<table width="100%" class="iqa">
                    	<tr>
                        	<td class="disable-Selection drag-icon" width="40px" rowspan="2" align="center"><span class="dashicons dashicons-image-flip-vertical"></span></td>
                        	<td class="disable-Selection drag-icon" width="80px"><label> <strong>Question : </strong></label></td>
                        	<td> <input type="text" name="question" value="" placeholder="" class="iq-question"/></td>
                        	<td class="disable-Selection"><button data-repeater-delete type="button" class="button"><span class="dashicons dashicons-trash"></span> Delete</button></td>
                        </tr> 
                    	<tr>
                        	<td class="disable-Selection drag-icon"><label> <strong>Answer : </strong></label></td>
                        	<td class="editor-wrapper" colspan="2"><?php wp_editor( "", "editor_id-1000", $settings ) ?></td>
                        </tr>                        
                    </table>
                </li>
        	<?php } ?>         
        </ul>
        
        <input data-repeater-create type="button" class="button-primary" value="Add new Basic Interview Question"/>
        
    </div>
    <?php
	
}

function intellipaat_interview_advanced_question_meta_box($post){
	
	$editor_id = 5000;
    $questions = unserialize(get_post_meta( $post->ID, 'intellipaat_interview_advanced_question', true ) ); 
	$settings = array(
					  	'textarea_name'=>'answer',
						'editor_class' => 'iq-answer',
						'textarea_rows'=> 8
					) ;
	
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'intellipaat_interview_advanced_question_meta_box_nonce', 'advanced_question_meta_box_nonce' ); ?>

    <div class="repeater">
    
        <ul data-repeater-list="advanced-questions" class="sortable-item-list">
        
        	<?php if($questions){ ?>
            	<?php foreach($questions as $question){ ?>
                    <li class="repeater-item ui-state-default" data-repeater-item>
                        <table width="100%" class="iqa">
                            <tr>
                                <td class="disable-Selection drag-icon" width="40px" rowspan="2" align="center"><span class="dashicons dashicons-image-flip-vertical"></span></td>
                                <td class="disable-Selection drag-icon" width="80px"><label> <strong>Question : </strong></label></td>
                                <td> <input type="text" name="question" value="<?php echo $question['question'] ?>" class="iq-question" required/></td>
                                <td class="disable-Selection drag-icon"><button data-repeater-delete type="button" class="button"><span class="dashicons dashicons-trash"></span> Delete</button></td>
                            </tr> 
                            <tr>
                                <td class="disable-Selection"><label> <strong>Answer : </strong></label></td>
                                <td class="editor-wrapper" colspan="2"><?php wp_editor( stripslashes( base64_decode($question['answer'])), "editor_id-".$editor_id, $settings ) ?></td>
                            </tr>                        
                        </table>
                    </li>
        		<?php $editor_id++; } ?>
        	<?php }else{ ?>
                <li class="repeater-item ui-state-default" data-repeater-item>
                	<table width="100%" class="iqa">
                    	<tr>
                        	<td class="disable-Selection drag-icon" width="40px" rowspan="2" align="center"><span class="dashicons dashicons-image-flip-vertical"></span></td>
                        	<td class="disable-Selection drag-icon" width="80px"><label> <strong>Question : </strong></label></td>
                        	<td> <input type="text" name="question" value="" placeholder="" class="iq-question"/></td>
                        	<td class="disable-Selection"><button data-repeater-delete type="button" class="button"><span class="dashicons dashicons-trash"></span> Delete</button></td>
                        </tr> 
                    	<tr>
                        	<td class="disable-Selection drag-icon"><label> <strong>Answer : </strong></label></td>
                        	<td class="editor-wrapper" colspan="2"><?php wp_editor( "", "editor_id-5000", $settings ) ?></td>
                        </tr>                        
                    </table>
                </li>
        	<?php } ?>         
        </ul>
        
        <input data-repeater-create type="button" class="button-primary" value="Add new Advanced Interview Question"/>
        
    </div>
    <?php
}

add_action( 'save_post_interview-question', 'intellipaat_interview_question_meta_box_save', 10, 2 ); 
function intellipaat_interview_question_meta_box_save( $post_id , $post)
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
	
    // if our nonce isn't there, or we can't verify it, bail
    if( isset( $_POST['basic_question_meta_box_nonce'] ) && wp_verify_nonce( $_POST['basic_question_meta_box_nonce'], 'intellipaat_interview_basic_question_meta_box_nonce' ) ) {
		$qas = array();
		foreach($_POST['basic-questions'] as $qa){
			$qas[]= array(
						'question' => $qa['question'],
						'answer' => base64_encode($qa['answer']),
					  );
		}
		update_post_meta( $post_id, 'intellipaat_interview_basic_question', serialize($qas) );
	}
	
    if( isset( $_POST['advanced_question_meta_box_nonce'] ) && wp_verify_nonce( $_POST['advanced_question_meta_box_nonce'], 'intellipaat_interview_advanced_question_meta_box_nonce' ) ) {
		$qas = array();
		foreach($_POST['advanced-questions'] as $qa){
			$qas[]= array(
						'question' => $qa['question'],
						'answer' => base64_encode($qa['answer']),
					  );
		}
		update_post_meta( $post_id, 'intellipaat_interview_advanced_question', serialize($qas) );
	}
}

function intellipaat_iq_admin_scripts($hook) {
    if ( ('post-new.php' == $hook && $_REQUEST['post_type']=='interview-question') || ('post.php' == $hook && get_post_type( $_GET['post'] ) == 'interview-question')) {
		wp_enqueue_script( 'jquery-repeater', get_stylesheet_directory_uri() . '/js/jquery.repeater.min.js', array('jquery','jquery-ui-sortable') );
		add_action( 'admin_print_scripts', 'admin_inline_js', 100 );
    }
}

/**
*	http://stackoverflow.com/questions/3919928/tinymce-instances-in-jquery-sortable  sortable issue
*	http://jeremyhixon.com/repeating-wordpress-editor/
*	http://blog.mirthlab.com/2008/11/13/dynamically-adding-and-removing-tinymce-instances-to-a-page/
*	http://wordpress.stackexchange.com/questions/51776/how-to-load-wp-editor-through-ajax-jquery
*	https://github.com/DubFriend/jquery.repeater
*	http://bechster.com/add-tinymce-visual-editor-comment-form-wordpress/
*	http://wordpress.stackexchange.com/questions/44993/use-quicktags-toolbar-on-any-textarea
*	https://core.trac.wordpress.org/ticket/26183#no3  quicktags toolbar empty
*/


add_action( 'admin_enqueue_scripts', 'intellipaat_iq_admin_scripts' );
function admin_inline_js(){?>
	<script type='text/javascript'>
		 var intellipaat_editor_id = 6000;
		 jQuery(document).ready(function () {
										  
			jQuery( ".sortable-item-list" ).sortable({
			  	revert: true,
				cursor: 'move',
			  	start: function(e, ui){
					tinyMCE.execCommand( 'mceRemoveEditor', false,  jQuery('textarea',ui.item)[0].id );
				},
				stop: function(e,ui) {
					tinyMCE.execCommand( 'mceAddEditor', false,  jQuery('textarea',ui.item)[0].id );
				}
			});
			jQuery( ".disable-Selection" ).disableSelection();
			jQuery('.repeater').repeater({
				// (Optional)
				// "defaultValues" sets the values of added items.  The keys of
				// defaultValues refer to the value of the input's name attribute.
				// If a default value is not specified for an input, then it will
				// have its value cleared.
				defaultValues: {
					'question': '',
					'answer':''
				},
				// (Optional)
				// "show" is called just after an item is added.  The item is hidden
				// at this point.  If a show callback is not given the item will
				// have jQuery(this).show() called on it.
				show: function () {
					newRow = jQuery(this);
					newID = 'editor_id-'+intellipaat_editor_id;
					newRow.find('textarea').attr('id',newID);
					newName = document.getElementById(newID).name;
					newRow.find('td.editor-wrapper').html('<div>Loadind Editor ... </div>');
					newRow.slideDown();
					jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', 
						{ 'action':'intellipaat_iq_editor', 'iq_editor_nonce' : '<?php echo wp_create_nonce("intellipaat_iq_editor_nonce") ?>', 'editor_id': newID , 'editor_name' : newName },
						function(data,status){ 
							if (status == "success") {  
								newRow.find('td.editor-wrapper').html(data);  
								
								var settings = jQuery.extend( {}, tinyMCEPreInit.mceInit['content'], { selector : "#" + newID } );
								tinyMCEPreInit.mceInit[newID] = settings;
								//var settings = jQuery.extend( {}, tinyMCEPreInit.qtInit['content'], { id :  newID } );
								//tinyMCEPreInit.qtInit[newID] = settings;
								
								quicktags({id : newID, buttons:"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"});
								tinyMCE.init(tinyMCEPreInit.mceInit[newID]);
								//tinyMCE.execCommand( 'mceRemoveEditor', false,  newID );
								//tinymce.EditorManager.execCommand('mceAddEditor', false, newID);
							}
					});					
					intellipaat_editor_id++;
				},
				// (Optional)
				// "hide" is called when a user clicks on a data-repeater-delete
				// element.  The item is still visible.  "hide" is passed a function
				// as its first argument which will properly remove the item.
				// "hide" allows for a confirmation step, to send a delete request
				// to the server, etc.  If a hide callback is not given the item
				// will be deleted.
				hide: function (deleteElement) {
					if(confirm('Are you sure you want to delete this question?')) {
						jQuery(this).slideUp(deleteElement);
					}
				},
				// (Optional)
				// You can use this if you need to manually re-index the list
				// for example if you are using a drag and drop library to reorder
				// list items.
				ready: function (setIndexes) {
					jQuery( ".sortable-item-list" ).on('sortstop', setIndexes);
				},
				// (Optional)
				// Removes the delete button from the first list item,
				// defaults to false.
				isFirstItemUndeletable: false
			})
		});
        </script>	
		<style>
			.drag-icon{ cursor:move}
            .iq-question, .iq-answer { width:100%}
            .iqa{ margin:5px auto }
        </style>
    <?php
}

add_action("wp_ajax_intellipaat_iq_editor", "intellipaat_iq_editor_callback");

function intellipaat_iq_editor_callback() {
	if ( !wp_verify_nonce( $_REQUEST['iq_editor_nonce'], "intellipaat_iq_editor_nonce")) {
      exit("No naughty business please");
	}
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$settings = array(
				'textarea_name'=> $_POST['editor_name'],
				'editor_class' => 'iq-answer',
				'textarea_rows'=> 8
		) ;
	
		die( wp_editor( "", $_POST['editor_id'], $settings ) );
	}
	die();
}
?>