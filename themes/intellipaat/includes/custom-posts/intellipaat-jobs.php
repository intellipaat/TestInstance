<?php 

function intellipaat_job_posttype(){
	
	register_post_type('jobs',
		array (
			'labels' => array(
				'name'					=> __('Jobs', 'intellipaat'),
				'menu_name'				=> __('Jobs', 'intellipaat'),
				'singular_name'			=> __('Job', 'intellipaat'),
				'all_items'				=> __('All Jobs', 'intellipaat'),
				'parent_item'           => __('Parent Job', 'intellipaat'),
				'parent_item_colon'     => __('Parent Job:', 'intellipaat'),
				'add_new'				=> __('Add Job', 'intellipaat'),
				'add_new_item'			=> __('Add new Job', 'intellipaat'),
				'edit_item'				=> __('Edit Job', 'intellipaat'),
				'new_item'				=> __('New Job', 'intellipaat'),
				'view_item'				=> __('View Job', 'intellipaat'),
				'search_items'			=> __('Search Jobs', 'intellipaat'),
				'not_found'				=> __('No Job found', 'intellipaat'),
				'not_found_in_trash'	=> __('No Jobs Found in Trash', 'intellipaat')
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
				'slug'					=> 'jobs',
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
											'name'                       => _x( 'Job Category', 'taxonomy general name' ),
											'singular_name'              => _x( 'Jobs Category', 'taxonomy singular name' ),
											'search_items'               => __( 'Search Job Categories' ),
											'popular_items'              => __( 'Popular Job Categories' ),
											'all_items'                  => __( 'All Job Categories' ),
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
										'slug'					=> 'jobs',
										'hierarchical'	 		=> true,
										'with_front'			=> false
									),
	);

	register_taxonomy( 'jobs-category', 'jobs', $args );

}

add_action('init', 'intellipaat_job_posttype');




?>