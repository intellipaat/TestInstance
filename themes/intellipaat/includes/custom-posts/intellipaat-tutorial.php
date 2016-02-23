<?php 

function intellipaat_tutorial_posttype(){
	
	register_post_type('tutorial',
		array (
			'labels' => array(
				'name'					=> __('Tutorials', 'intellipaat'),
				'menu_name'				=> __('Tutorials', 'intellipaat'),
				'singular_name'			=> __('Tutorial', 'intellipaat'),
				'all_items'				=> __('All Tutorials', 'intellipaat'),
				'parent_item'           => __('Parent Tutorial', 'intellipaat'),
				'parent_item_colon'     => __('Parent Tutorial:', 'intellipaat'),
				'add_new'				=> __('Add Tutorial', 'intellipaat'),
				'add_new_item'			=> __('Add new Tutorial', 'intellipaat'),
				'edit_item'				=> __('Edit Tutorial', 'intellipaat'),
				'new_item'				=> __('New Tutorial', 'intellipaat'),
				'view_item'				=> __('View tutorial', 'intellipaat'),
				'search_items'			=> __('Search tutorials', 'intellipaat'),
				'not_found'				=> __('No tutorial found', 'intellipaat'),
				'not_found_in_trash'	=> __('No tutorials Found in Trash', 'intellipaat')
			),
			'public'				=> true,
			'show_ui'				=> true,
			'has_archive'			=> true,
			'show_in_nav_menus' 	=> true,
			'capability_type'		=> 'page',
			'hierarchical'			=> true,
			'exclude_from_search'	=> true,
			'publicly_queryable'	=> true,
			'query_var'				=> true,
			'map_meta_cap'        	=> true,
			'rewrite'				=> array(
												'slug'					=> 'tutorial',
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
			)
		)
	);
	

	$args = array(
		'hierarchical'          => true,
		'labels'                =>  array(
											'name'                       => _x( 'Tutorial Category', 'taxonomy general name' ),
											'singular_name'              => _x( 'Tutorial Category', 'taxonomy singular name' ),
											'search_items'               => __( 'Search Tutorial Categories' ),
											'popular_items'              => __( 'Popular Tutorial Categories' ),
											'all_items'                  => __( 'All Tutorial Categories' ),
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
										'slug'					=> 'tutorial',
										'hierarchical'	 		=> true,
										'with_front'			=> false
									),
	);

	register_taxonomy( 'tuts-category', 'tutorial', $args );

}

add_action('init', 'intellipaat_tutorial_posttype');




?>