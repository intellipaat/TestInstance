<?php


/*
*	cusotm fucntion by makarand to point urls to intellipaat.com website
*/
add_filter( 'vibe-opts-sections-wplms', 'intellipaat_sttings_in_wplms', 10, 1 );
function intellipaat_sttings_in_wplms($args){
	
	$args[1]['fields'][]= array(
								'id'	=> 'browse_courses',
								'type'	 => 'text',
								'title'	=> 'Browse courses',
								'sub_desc'	=> 'Course catgories in browse courses menu',
								'desc'	=> "Enter ID number of all Course categories saperated by comma(,). Write in order as wish to display in front end at header." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'default_popular_searches',
								'type'	 => 'editor',
								'title'	=> 'Default Popular Searches',
								'sub_desc'	=> 'Enter links of courses',
								'desc'	=> "Enter links of page/courses manually in editor to show in popular searches in header." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'header_banner_code',
								'type'	 => 'textarea',
								'title'	=> 'Header text banner',
								'sub_desc'	=> 'Banner code text/HTML',
								'desc'	=> "Please enter text or HTML code for text banner which will be displayed above header." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'header_banner_color',
								'type'	 => 'color',
								'title'	=> 'Header banner color',
								'std'	=>	'#FFFFFF',
								'sub_desc'	=> 'Text color in Hex format',
								'desc'	=> "Please click on input box. You will be hsown a color picker. Choose color which you want to apply for text of Header text banner." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'header_banner_bg_color',
								'type'	 => 'color',
								'title'	=> 'Header banner Background',
								'std'	=>	'#3B385F',
								'sub_desc'	=> 'Background color in Hex format',
								'desc'	=> "Please click on input box. You will be hsown a color picker. Choose color which you want to apply for background of Header text banner." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'header_banner_bg_image',
								'type'	 => 'upload',
								'title'	=> 'Header banner Background',
								'sub_desc'	=> 'Background image JPG/PNG/GIF',
								'desc'	=> "Upload an image for background of Header text banner." 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'tracking_code',
								'type'	 => 'textarea',
								'title'	=> 'Google analytics or any tracking code',
								'sub_desc'	=> 'Insert javascript/CSS code in html head section',
								'desc'	=> "Please enter full code with javascript tags. (May be Google analytics or any tracking code or CSS). This code will be kept before closing of &lt;/head&gt; tag" 
							);
	
	$args[1]['fields'][]= array(
								'id'	=> 'home_page_head_scripts',
								'type'	 => 'textarea',
								'title'	=> 'Custom Home page Scripts',
								'sub_desc'	=> 'Javascript/CSS code in html head section',
								'desc'	=> "Whatever You you will put only displayed on Home Page. (May be JS or CSS code). This code will be kept before closing of &lt;/head&gt; tag" 
							);
	$args[4]['fields'][]= array(								
								'id' => 'recommended_courses',
								'type' => 'posts_multi_select',
								'title' => __('Recommended Courses', 'vibe'),
								'sub_desc' => __('Default courses to show in Recommended Courses widget', 'vibe'),
								'args' => 'post_type=course',
								'class' => 'chosen',
								'std'=> array(2482,2525,2357)
							);
	$args[4]['fields'][]= array(
								'id'	=> 'global_course_offer',
								'type'	 => 'textarea',
								'title'	=> 'Course offer',
								'sub_desc'	=> 'Offer on every course',
								'desc'	=> "Please your offer text here whcih will be shown on all course pages below title." 
							);
	$args[4]['fields'][]= array(								
									'id' => 'individual_course_offer_mode',
									'type' => 'button_set',
									'title' => __('Toggle individual course offer', 'vibe'), 
									'sub_desc' => __('Toggle visibility of individual offer of courses.', 'vibe'),
									'desc' => __('If you enable this, then individual offer of course will not be shown. If you want to exclude some course then check next option.', 'vibe'),
									'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
									'std' => 0
							);	
	$args[4]['fields'][]= array(								
								'id' => 'excluded_courses_from_offer',
								'type' => 'posts_multi_select',
								'title' => __('Exclude Courses', 'vibe'),
								'sub_desc' => __('For Selected courses individual offer of course will be shown still last option is in disabled mode.', 'vibe'),
								'args' => 'post_type=course',
								'class' => 'chosen',
								'std'=> array(4028,34209)
							);
	
	$args[4]['fields'][]= array(
								'id'	=> 'course_report_recipient',
								'type'	 => 'text',
								'title'	=> 'Report recipient',
								'sub_desc'	=> '24 hours course access report',
								'desc'	=> "Please enter email address of person who should recieve this report. If report to be sent to multiple person, then enter <b>comma saperated email addresses</b>." 
							);
	
	if(TLD == 'com' || TLD == 'us' )
		$args[4]['fields'][]= array(
								'id'	=> 'support_note',
								'type'	 => 'editor',
								'title'	=> 'Support note.',
								'sub_desc'	=> 'Strat course page.',
								'desc'	=> "Support line will be displayed below single unit on learning pages." 
							);
	
	$args[8]['fields'][4]['title'] = 'Google analytics or any tracking code';
	$args[8]['fields'][4]['sub_desc']= 'Insert javascript code in html body section';
	$args[8]['fields'][4]['desc']= "Please enter full code with javascript tags. This code will be kept before closing of &lt;/body&gt; tag" ;
	
	
	$args[8]['fields'][]= array(
								'id'	=> 'toll_free_number',
								'type'	 => 'text',
								'title'	=> 'Troll free number',
								'sub_desc'	=> 'Enter number',
								'desc'	=> "Please enter your toll free number. Will be shown in footer strip" 
							);
	
	
	$args[8]['fields'][]= array(
								'id'	=> 'mobile_no_1',
								'type'	 => 'text',
								'title'	=> 'Mobile Number',
								'sub_desc'	=> 'First Mobile',
								'desc'	=> "Please enter your first mobile. Will be shown in footer strip" 
							);
	
	
	
	$args[8]['fields'][]= array(
								'id'	=> 'mobile_no_2',
								'type'	 => 'text',
								'title'	=> 'Mobile Number',
								'sub_desc'	=> 'Second Mobile',
								'desc'	=> "Please enter your Second mobile number. Keep blank to disable. Will be shown in footer strip" 
							);
	
	
	$args[8]['fields'][]= array(
								'id'	=> 'email_address',
								'type'	 => 'text',
								'title'	=> 'Email address',
								'sub_desc'	=> 'Sales mail address',
								'desc'	=> "Please enter sales mail ID here. Will be shown in footer strip" 
							);
	
	
	$args[8]['fields'][]= array(
								'id'	=> 'training_in_city',
								'type'	 => 'editor',
								'title'	=> 'Training in city',
								'sub_desc'	=> 'Name of all cities',
								'desc'	=> "Please enter name of all cities to show in footer. Keep blank to hide from footer." 
							);
	$args[] = array(
						'title'	=>	'Footer 2',
						'desc'	=>	'This is Custom tab created by Makarand to manage options for Footer 2<sup>nd</sup>. This footer is only visible on pages like tutorial, Interview questions, Jobs.',
						'icon'	=>	'editor-insertmore',
						'fields'=>	array(	
										array(
											'id' => 'courses_menu_items_1',
											'type' => 'multi_keyword_menu',
											'title' => __('Top Courses', 'vibe'),
											'sub_desc' => __('First 4 course with multikeyword links.', 'vibe'),
											'desc' => __('Add comma (,) separated multiple keywords. Then select url for course', 'vibe'),
											'args' => 'post_type=course&depth=1',
											'callback'	=> 'multi_keyword_menu_callback'
										),
										array(
											'id'	=> 'courses_menu_items_2',
											'type'	 => 'posts_multi_select',
											'title'	=> 'Top Courses',
											'sub_desc'	=> 'Bottom 4 courses with signle title.',
											'desc'	=> "Four random courses will be shown from selected courses. Select more than 4 courses." ,
											'class' => 'chosen',
											'args' => 'post_type=course&depth=1',
										),
										array(
											'id' => 'iq_menu_items_1',
											'type' => 'multi_keyword_menu',
											'title' => __('Top Interview questions', 'vibe'),
											'sub_desc' => __('First 4 Interview questions with multikeyword links.', 'vibe'),
											'desc' => __('Add comma (,) separated multiple keywords. Then select url for interview questions', 'vibe'),
											'args' => 'post_type=interview-question&depth=1',
											'callback'	=> 'multi_keyword_menu_callback'
										),						  
										array(
											'id'	=> 'iq_menu_items_2',
											'type'	 => 'posts_multi_select',
											'title'	=> 'Top interview questions',
											'sub_desc'	=> 'Bottom 4 interview questions with signle title.',
											'desc'	=> "Four random interview questions will be shown from selected interview questions. Select more than 4 interview questions." ,
											'class' => 'chosen',
											'args' => 'post_type=interview-question&depth=1',
										),
										array(
											'id' => 'jobs_menu_items_1',
											'type' => 'multi_keyword_menu',
											'title' => __('Recent Jobs', 'vibe'),
											'sub_desc' => __('First 4 jobs with multikeyword links.', 'vibe'),
											'desc' => __('Add comma (,) separated multiple keywords. Then select url for Recent Jobs', 'vibe'),
											'args' => 'post_type=jobs&depth=1',
											'callback'	=> 'multi_keyword_menu_callback'
										),					  
										array(
											'id'	=> 'jobs_menu_items_2',
											'type'	 => 'posts_multi_select',
											'title'	=> 'Recent Jobs',
											'sub_desc'	=> 'Bottom 4 jobs with signle title.',
											'desc'	=> "Four random Jobs will be shown from selected Jobs. Select more than 4 Jobs." ,
											'class' => 'chosen',
											'args' => 'post_type=jobs&depth=1',
										),
										array(
											'id' => 'tutorial_menu_items_1',
											'type' => 'multi_keyword_menu',
											'title' => __('Popular tutorial category', 'vibe'),
											'sub_desc' => __('First 4 tutorials with multikeyword links.', 'vibe'),
											'desc' => __('Add comma (,) separated multiple keywords. Then select url for tutorial category', 'vibe'),
											'args' => 'post_type=tutorial&depth=1',
											'callback'	=> 'multi_keyword_menu_callback'
										),				  
										array(
											'id'	=> 'tutorial_menu_items_2',
											'type'	 => 'posts_multi_select',
											'title'	=> 'Popular tutorial category',
											'sub_desc'	=> 'Bottom 4 tutorials with signle title.',
											'desc'	=> "Four random tutorial categories will be shown from selected tutorial categories. Select more than 4 tutorial category." ,
											'class' => 'chosen',
											'args' => 'post_type=tutorial&depth=1',
										),
									),							
					);
	
	
	$args[] = array(
						'title'	=>	'Intellipaat Customs',
						'desc'	=>	'This is Custom tab created by Makarand to add cusotm options for Intellipaat website.',
						'icon'	=>	'admin-network',
						'fields'=>	array(										  
										array(
											'id'	=> 'featured_courses_grp_1',
											'type'	 => 'text',
											'title'	=> 'All courses',
											'sub_desc'	=> 'Course catgories in first group',
											'desc'	=> "Enter ID number of all Course categories saperated by comma(,). Write in order as wish to display in front end on all course page." ,
										),								  
										array(
											'id'	=> 'featured_courses_grp_2',
											'type'	 => 'text',
											'title'	=> 'All courses',
											'sub_desc'	=> 'Course catgories in second group',
											'desc'	=> "Enter ID number of all Course categories saperated by comma(,). Write in order as wish to display in front end on all course page." ,
										),								  
										array(
											'id'	=> 'default_courses_cat',
											'type'	 => 'cats_select',
											'title'	=> 'All courses',
											'sub_desc'	=> 'Default category to be selected on all course page',
											'desc'	=> "Select a Course category which will be selected by default on all course page." ,
											'args' => 'taxonomy=course-cat&hide_empty=0',
					                        'class' => 'chosen',
										),										  
										array(
											'id'	=> 'excluded_pages',
											'type'	 => 'pages_multi_select',
											'title'	=> 'Excluded pages from HTML sitemap',
											'sub_desc'	=> 'ID number of Excluded pages',
											'desc'	=> "Enter ID number of pages saperated by comma(,). Enter only ID number of pages which you like to be excluded on <a href='".esc_url( get_permalink( get_page_by_title( 'sitemap' ) ) )."'>HTML sitemap page</a>." ,
											'class' => 'chosen',
										),											  
										array(
											'id'	=> 'excluded_hreflang_pages',
											'type'	 => 'pages_multi_select',
											'title'	=> 'Excluded hreflang tags from pages',
											'sub_desc'	=> 'ID number of Excluded pages',
											'desc'	=> "Select all pages on  which you dont want to put hreflang tags." ,
											'class' => 'chosen',
										),								  
										/*array(
											'id'	=> 'zoho_api_key',
											'type'	 => 'text',
											'title'	=> 'Zoho Api Key',
											'sub_desc'	=> 'Secret auth tokens for Zoho API calls',
											'std'	=> '',
											'desc'	=> "For Zoho API calls, Secret auth token is needed. Enter here a Secret auth token, Genarated on Zoho accounts settings page, click on this <a href='https://accounts.zoho.com/u/h#setting/authtoken'>link</a> to get your secret token." 
										),	*/						  
										array(
											'id'	=> 'base_currency_conversion_rate',
											'type'	 => 'text',
											'title'	=> 'Currency Conversion Rate',
											'sub_desc'	=> 'Currency Conversion Rate for Base currency',
											'std'	=> 1,
											'desc'	=> "Enter Currency Conversion Rate to convert Base currency to display in front end everywhere." 
										),						  
										array(
											'id'	=> 'dollar_to_inr_conversion_rate',
											'type'	 => 'text',
											'title'	=> 'Dollar to INR Conversion Rate',
											'sub_desc'	=> 'Currency Conversion Rate for Dollar to INR currency',
											'std'	=> 57,
											'desc'	=> "Enter Currency Conversion Rate to convert product amount into Rupees to display in front end everywhere." 
										),
										array(
											'id'	=> 'dollar_to_inr_conversion_rate_ebs_global',
											'type'	 => 'text',
											'title'	=> 'EBS Global Dollar to INR Conversion Rate',
											'sub_desc'	=> 'Currency Conversion Rate for Dollar to INR currency',
											'std'	=> 67,
											'desc'	=> "Enter Currency Conversion Rate for EBS Global Payment Gateway." 
										),
										array(
											'id'	=> 'checkout_conversion_pixel_code',
											'type'	 => 'textarea',
											'title'	=> 'Checkout page Conversion pixel',
											'sub_desc'	=> 'Before payment Conversion pixel code',
											'desc'	=> "Please enter full code with javascript tags. This code will be kept before closing of &lt;/head&gt; tag on Checkout page." 
										),
										array(
											'id'	=> 'order_received_conversion_code',
											'type'	 => 'textarea',
											'title'	=> 'Conversion Pixel codes',
											'sub_desc'	=> 'Conversion Pixel code on thank you page',
											'desc'	=> "Please enter full code with javascript tags. This code will be kept before closing of &lt;/head&gt; tag on thank you page." 
										),
										array(
											'id'	=> 'self_pace_course_conversion_code',
											'type'	 => 'textarea',
											'title'	=> 'Self paced course Conversion Pixel',
											'sub_desc'	=> 'Conversion Pixel code on purchase of self paced course',
											'desc'	=> "Please enter full code with javascript tags. This code will be kept before closing of &lt;/body&gt; tag on thank you page." 
										),
										array(
											'id'	=> 'online_training_course_conversion_code',
											'type'	 => 'textarea',
											'title'	=> 'Online Training Course Conversion Pixel',
											'sub_desc'	=> 'Conversion Pixel code on purchase of Online Training course',
											'desc'	=> "Please enter full code with javascript tags. This code will be kept before closing of &lt;/body &gt; tag  on thank you page." 
										),
										array(
											'id' => 'turn_on_review',
											'type' => 'button_set',
											'title' => __('Toggle review', 'vibe'), 
											'sub_desc' => __('Turn on/off review on all single course pages', 'vibe'),
											'desc' => __('If you enable this, then review submission form will be open for all world (means non lodgged in user). Keep this settings Turned off (recommended) ', 'vibe'),
											'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
											'std' => 0
										), 
										array(
											'id' => 'turn_on_ssl',
											'type' => 'button_set',
											'title' => __('Front End SSL Mode', 'vibe'), 
											'sub_desc' => __('Turn on SSL mode for Whole website', 'vibe'),
											'desc' => __('If you enable this, then "Whole Site" will force the whole site to use SSL (not recommended unless you have a really good reason to use it). Make sure that port 443 is enabled and SSL certificate installed for '.site_url(), 'vibe'),
											'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
											'std' => 0
										), 
									),						
					);
	$args[] = array(
						'title'	=>	'SugarCRM',
						'desc'	=>	'This is Custom tab created by Makarand to store information about sugarCRM.',
						'icon'	=>	'welcome-view-site',
						'fields'=>	array(										  
										array(
											'id'	=> 'sugarCRM_api_url',
											'type'	 => 'text',
											'title'	=> 'sugarCRM API URL',
											'sub_desc'	=> 'URL including http://',
											'std'	=> site_url().'/crm/service/v4_1/rest.php',
											'desc'	=> "Enter complete url for sugarCRM api. <a href='http://support.sugarcrm.com/02_Documentation/04_Sugar_Developer/Sugar_Developer_Guide_7.2/70_API/Web_Services/00_API_Versioning/'>Click here</a> to get url for your current sugarCRM." 
										),										  
										array(
											'id'	=> 'sugarCRM_api_user',
											'type'	 => 'text',
											'title'	=> 'sugarCRM admin username',
											'sub_desc'	=> '',
											'std'	=> 'admin',
											'desc'	=> "Enter sugarCRM admin username." 
										),									  
										array(
											'id'	=> 'sugarCRM_api_pass',
											'type'	 => 'text',
											'title'	=> 'sugarCRM Passsword',
											'sub_desc'	=> '',
											'std'	=> 'admin@123',
											'desc'	=> "Enter sugarCRM admin password." 
										),	
										array(
											'id' => 'sugarCRM_htaccess_protected',
											'type' => 'button_set',
											'title' => __('sugarCRM protection', 'vibe'), 
											'sub_desc' => __('Is sugarCRM .htaccess protected?', 'vibe'),
											'desc' => __('Enable this option, only if your sugarCRM is htaccess protected.', 'vibe'),
											'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
											'std' => 0
										), 									  
										array(
											'id'	=> 'sugarCRM_htaccess_user',
											'type'	 => 'text',
											'title'	=> 'sugarCRM htaccess username',
											'sub_desc'	=> '',
											'std'	=> 'admin',
											'desc'	=> "Enter sugarCRM folder htaccess username." 
										),									  
										array(
											'id'	=> 'sugarCRM_htaccess_pass',
											'type'	 => 'text',
											'title'	=> 'sugarCRM htaccess Passsword',
											'sub_desc'	=> '',
											'std'	=> 'admin@123',
											'desc'	=> "Enter sugarCRM folder htaccess password." 
										),
									),						
					);
	
	/*if(TLD == 'com' || TLD == 'us' )
		$args[] = array(
						'title'	=>	'Moodle API',
						'desc'	=>	'This is Custom tab created by Makarand to store information about Moodle API.',
						'icon'	=>	'admin-generic',
						'fields'=>	array(										  
										array(
											'id'	=> 'moodle_api_url',
											'type'	 => 'text',
											'title'	=> 'Moodle API URL',
											'sub_desc'	=> 'URL including http://',
											'std'	=> site_url().'/elearning/',
											'desc'	=> "Enter complete url for Moodle location only. e.g. http://bigdataonlinetraining.us/elearning/" 
										),
										array(
											'id' => 'moodle_api_key',
											'type' => 'text',
											'title' => __('Moodle API key', 'vibe'), 
											'sub_desc' => __('Enter API key', 'vibe'),
											'desc' => __('Enter api key genarated from moodle.', 'vibe'),
										), 	
									),						
					);*/
	
	
	return $args;
}