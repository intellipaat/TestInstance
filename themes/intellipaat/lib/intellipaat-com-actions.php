<?php

if(is_admin()){
	require_once(get_stylesheet_directory().'/includes/admin-tools/bulk-coupon-genarator.php');
	require_once(get_stylesheet_directory().'/includes/admin-tools/student-to-course.php');
}

require_once('com/intellipaat-theme-actions.php');
require_once('com/intellipaat-ajax-actions.php');
require_once('com/intellipaat-course_page-actions.php');
require_once('com/intellipaat-user-registration-actions.php');
require_once('com/intellipaat-wishlist-actions.php');
require_once('com/intellipaat-wplms-actions.php');
require_once('com/intellipaat-popup-login-signup-form.php');
require_once('com/intellipaat-woocommerce-actions.php');

?>