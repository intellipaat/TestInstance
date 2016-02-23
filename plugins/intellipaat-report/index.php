<?php
/**
 * Plugin Name: Intellipaat Custom
 * Plugin URI: https://intellipaat.com/
 * Description: This plugin will prepare reports and all other custom things for intellipath
 * Version: 1.0
 * Author: Sanjeev Kumar
 * Author URI: http://roomrentjaipur.com
 */

function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

/*
add_action('woocommerce_add_to_cart', 'custome_add_to_cart', 1, 2);
function custome_add_to_cart($cart_item_key, $product_id) {
			  global $wpdb, $current_user;
			  $user_id = $current_user->ID;
			  if($user_id > 0){
			  $data = array('product_id'=>$product_id, 'user_id'=>$user_id, 'date'=>date('Y-m-d H:i:s'));
			  $wpdb->insert( 'ip_self_paced_courses', $data);
			  }
		}
*/
		
add_action('wplms_unit_header', 'get_course_access_report',1,2);
					function get_course_access_report($unit_id, $course_id) {
							 global $wpdb, $current_user;
			  				$user_id = $current_user->ID;
							if($user_id > 0) {
							//$product_id = 2;
							$my_course_id = $my_unit_id = 0;
							if($course_id != ''){
								$my_course_id = $course_id;
							}
							if($unit_id != ''){
								$my_unit_id = $unit_id;
							}
							$product = $my_course_id."-".$my_unit_id."-".get_client_ip_server()."-".time();
							  $selected_product = array($product);
							 if(!empty(get_user_meta($user_id, 'course_access_report'))){
								 $jData = json_decode(get_user_meta($user_id, 'course_access_report', true));
								 $totalData  = array_merge($jData, $selected_product);
								 $encodedData = json_encode($totalData);
								 update_user_meta($user_id, 'course_access_report', $encodedData);
							 } else {
								 add_user_meta( $user_id, 'course_access_report', json_encode($selected_product) );
							 } 
						  }
						}
						
if(!class_exists('intellipaatCustom'))
{
	class intellipaatCustom 
	{
		public function __construct()
		{	
			/********* create a tables on plugin activation ***************/
			register_activation_hook( __FILE__, array($this, 'self_paced_courses_table') );
			
			/********* delete a page on plugin deactivation ***************/
			//register_deactivation_hook( __FILE__, array($this, 'deletePages') );
			// woocommerce_add_cart_item_data
						
			add_action( 'init', array($this, 'ccsve_export'));
			// Hook which will create menu on admin side for navigation
			add_action('admin_menu', array($this,'on_admin_menu'));	
			/************ add custom js *********************************/
			add_action('init', array($this, 'plugin_backend_JS'));
			
			/************ add custom css *********************************/
			add_action('init', array($this, 'plugin_backend_CSS'));
			
			add_action( 'wp_ajax_get_your_fee', array($this, 'get_your_estimated_fee') );
			add_action( 'wp_ajax_nopriv_get_your_fee', array($this, 'get_your_estimated_fee') );
			
		}
		
		public function  self_paced_courses_table()
				{
					global $wpdb;
					$table_name = $wpdb->prefix . 'self_paced_courses';
					$sql = "CREATE TABLE $table_name (
					  id int(11) NOT NULL AUTO_INCREMENT,
					  product_id int(11) DEFAULT NULL,
					  user_id int(11) DEFAULT NULL,
					  date varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					);";
				
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $sql );
				}
		
		/**
		* function to add menu on admin
 		*/	 
		public function on_admin_menu()
		{
			add_menu_page('Reports', 'User Report', 'manage_options', 'access_logs_report', array($this,'access_logs_report'));
			add_submenu_page('access_logs_report', 'Access logs Report', 'Access logs Report', 'manage_options', 'access_logs_report', array($this, 'access_logs_report'));
			add_submenu_page('access_logs_report', 'Learners Course Details', 'Learners Course Details', 'manage_options', 'learners_course_details', array($this, 'learners_course_details'));
			add_submenu_page('access_logs_report', 'Wishlist Report', 'Wishlist Report', 'manage_options', 'wishlist_report', array($this, 'wishlist_report'));
			add_submenu_page('access_logs_report', 'Active Self-paced Users Report', 'Active Self-paced Users Report', 'manage_options', 'self_paced_users_report', array($this, 'self_paced_users_report'));
			
			add_submenu_page('access_logs_report', 'Course Enrollment Report', 'Course Enrollment Report', 'manage_options', 'course_enrollment_report', array($this, 'course_enrollment_report'));

add_submenu_page('access_logs_report', 'Course Report', 'Course Report', 'manage_options', 'course_report', array($this, 'course_report'));
			
			
			add_submenu_page('access_logs_report', 'Theme Options', 'Theme Options', 'manage_options', 'theme_options', array($this, 'theme_options'));
			
		}

public function course_report(){
global $wpdb;


$studentQuery = "
SELECT ID
FROM {$wpdb->users} u INNER JOIN {$wpdb->usermeta} m ON u.ID = m.user_id
WHERE 1 = 1
and m.meta_key = '{$wpdb->prefix}capabilities'
and m.meta_value LIKE '%student%'
";
$studentList = $wpdb->get_results($studentQuery);
echo count($studentList);

?>
<div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br></div>
<table class="widefat fixed" cellspacing="0" id="" width="100%">
<thead>
<tr>
<th scope="col" width="10%">#</th>
<th scope="col" width="15%">First Name</th>
<th scope="col" width="15%">Last Name</th>
<th scope="col" width="20%">Email Id</th>
<th scope="col" width="40%">Course(s) Registered </th>
<th scope="col" width="20%">Phone Number</th>
<th scope="col" width="20%">User Registered </th>
<th scope="col" width="10%">Country</th>
</tr>
</thead>
<tbody>
<?php
if(count($studentList)>0){
$count=1;
foreach($studentList as $student){
$student = $student->ID;
$user = get_user_by('id',$student);
$subCourseQuery = "
select 
p.post_title
from 
{$wpdb->prefix}posts p,
{$wpdb->prefix}usermeta m
where 1 = 1
and p.post_type = 'course'
and m.meta_key = p.ID
and m.user_id = '".$student."'
";
$courseList = $wpdb->get_results($subCourseQuery, ARRAY_A);
$course = implode(',', array_map(function($el){ return $el['post_title']; }, $courseList));
if($course == ''){
	$course = 'Not registered yet';
}

?>
<tr>
<td><?php echo $count;?></td>
<td><?php echo get_user_meta($student, 'first_name', true); ?></td>
<td><?php echo get_user_meta($student, 'last_name', true); ?></td>
<td><?php echo $user->user_email; ?></td>
<td><?php echo $course;?></td>
<td><?php echo get_user_meta($student, 'billing_phone', true); ?></td>
<td><?php echo $user->user_registered; ?></td>
<td><?php echo get_user_meta($student, 'billing_country', true); ?></td>
</tr>
	<?php
$count++;
 } 
		}
?>
</tbody>
</table>
<?php
}
		
		public function course_enrollment_report(){
			global $wpdb;
			?>
			<div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br>
</div>
<h2>Course Enrollment Report <?php if(isset($_POST['course_id'])) { ?>(<a href="?page=course_enrollment_report&action=course_enrollment_report&course_id=<?php echo $_POST['course_id']; ?>">Export</a>)<?php } ?></h2>
<?php
$keyword = '';
 if(isset($_POST['course_id'])) { $course_id = $_POST['course_id']; } ?>
<form method="post" id="export-form" action="?page=course_enrollment_report">
	
           <p> 
           <?php 
	$products = get_posts(array('post_type'=>'course', 'posts_per_page'=>-1, 'order'=> 'ASC', 'orderby' => 'title'));
	if(count($products) > 0) {	?>
           <select name="course_id">
           <?php foreach($products as $product){
			   if($product->ID == $course_id) { ?>
           <option value="<?php echo $product->ID; ?>" selected="selected"><?php echo $product->post_title; ?></option>
           <?php } else { ?>
           <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
           <?php }  } ?>
           </select>
          <?php 
		  } ?>
         <input type="submit" name="download_csv" id="download_csv" class="button button-primary" value="Search"></p>
        </form>
<table class="widefat fixed" cellspacing="0" id="" width="100%">
	<thead>
		<tr>
            <th scope="col" width="15%">First Name</th>
			<th scope="col" width="15%">Last Name</th>
			<th scope="col" width="20%">Email Id</th>
            <th scope="col" width="20%">Phone Number</th>
            <th scope="col" width="40%">User Registered </th>
            <th scope="col" width="40%">Country</th>
		</tr>
	</thead>
    <?php if(isset($_POST['course_id'])){ ?>
	  		<?php
			$students_undertaking = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key  = %s ",$_POST['course_id']));	 
			
		
			if(count($students_undertaking)>0){
		foreach($students_undertaking as $student){
			$student = $student->user_id;
			$user = get_user_by('id',$student);
?>
	<tr>
        <td><?php echo get_user_meta($student, 'first_name', true); ?></td>
		<td><?php echo get_user_meta($student, 'last_name', true); ?></td>
		<td><?php echo $user->user_email; ?></td>
        <td><?php echo get_user_meta($student, 'billing_phone', true); ?></td>
        <td><?php echo $user->user_registered; ?></td>
        <td><?php echo get_user_meta($student, 'billing_country', true); ?></td>
	</tr>
	<?php }
		}
	}
	else {
		?>
	<tr>
		<td colspan="3" align="center">No Data Available</td>

	</tr>
	<?php
	} 
	?>
</table>
</div>
		<?php	}
		
		public function access_logs_report(){
			global $wpdb;
			?>	
            <div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br>
</div>
<h2>Access logs Report <?php if(isset($_POST['keyword'])) { ?>(<a href="?page=access_logs_report&action=access_logs_report&keyword=<?php echo $_POST['keyword']; ?>">Export</a>)<?php } ?></h2>
<?php
$keyword = '';
 if(isset($_POST['keyword'])) { $keyword = 'value="'.$_POST['keyword'].'"'; } ?>
<form method="post" id="export-form" action="?page=access_logs_report">
           <p> <strong>Search:</strong> <input type="text" name="keyword" placeholder="Enter Keyword" <?php echo $keyword; ?> /> <input type="submit" name="download_csv" id="download_csv" class="button button-primary" value="Search"></p>
        </form>
  
<table class="widefat fixed" cellspacing="0" id="" width="100%">
	<thead>
		<tr>
            <th scope="col" width="8%">First Name</th>
			<th scope="col" width="8%">Last Name</th>
            <th scope="col" width="10%">Email </th>
            <th scope="col" width="10%">Phone</th>
			<th scope="col" width="14%">IP Address</th>
            <th scope="col" width="10%">Start Time </th>
            <th scope="col" width="20%">Courses Accessed</th>
            <th scope="col" width="20%">Unit Accessed</th>
            
		</tr>
	</thead>
    <?php if(isset($_POST['keyword']) && !empty($_POST['keyword'])){ ?>
	<?php
	$accessLogs = array();
	$sql = "SELECT * FROM `ip_usermeta` um JOIN `ip_users` u ON u.ID=um.user_id WHERE um.`meta_key` = 'course_access_report' AND u.`user_email` LIKE '%".$_POST['keyword']."%'";
	$accessLogs = $wpdb->get_results($sql);
	if(!empty($accessLogs)>0){
		$data = json_decode($accessLogs[0]->meta_value);
		foreach($data as $val)
		{
			$myvar = explode("-",$val);
			?>
	<tr>
        <td><?php echo get_user_meta($accessLogs[0]->user_id, 'first_name', true); ?></td>
		<td><?php echo get_user_meta($accessLogs[0]->user_id, 'last_name', true); ?></td>
		<td><?php echo $accessLogs[0]->user_email; ?></td>
        <td><?php echo get_user_meta($accessLogs[0]->user_id, 'billing_phone', true); ?></td>
        <td><?php echo $myvar[2]; ?></td>
        <td><?php echo date("d-M-Y h:i:s", $myvar[3]); ?></td>
        <td><?php echo get_the_title($myvar[0]); ?></td>
        <td><?php if($myvar[1]==0){ echo 'Lecture 1'; } else { echo get_the_title($myvar[1]); } ?></td>
	</tr>
	<?php
		}
		
	}
	else {
		?>
	<tr>
		<td colspan="3" align="center">No Data Available</td>

	</tr>
	<?php
	} }
	?>
</table>
</div>
		<?php
        }
		
		public function learners_course_details(){
			global $wpdb;
			?>
			<div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br>
</div>
<h2>Learners course details Report <?php if(isset($_POST['keyword'])) { ?>(<a href="?page=learners_course_details&action=learners_course_details&keyword=<?php echo $_POST['keyword']; ?>">Export</a>)<?php } ?></h2>
<?php
$keyword = '';
 if(isset($_POST['keyword'])) { $keyword = 'value="'.$_POST['keyword'].'"'; } ?>
<form method="post" id="export-form" action="?page=learners_course_details">
           <p> <strong>Search:</strong> <input type="text" name="keyword" placeholder="Enter Keyword" <?php echo $keyword; ?> /> <input type="submit" name="download_csv" id="download_csv" class="button button-primary" value="Search"></p>
        </form>
  
<table class="widefat fixed" cellspacing="0" id="" width="100%">
	<thead>
		<tr>
            <th scope="col" width="15%">First Name</th>
			<th scope="col" width="15%">Last Name</th>
			<th scope="col" width="20%">Email Id</th>
            <th scope="col" width="20%">Phone Number</th>
            <th scope="col" width="40%">Courses Purchased </th>
		</tr>
	</thead>
    <?php if(isset($_POST['keyword'])){ ?>
	<?php
	$learnersData = array();
	$sql = "SELECT * FROM `ip_usermeta` um JOIN `ip_users` u ON u.ID=um.user_id WHERE u.`user_email` LIKE '%".$_POST['keyword']."%'";
	//$sql = "SELECT p.post_title, p.post_author FROM `ip_usermeta` um RIGHT JOIN ip_posts p ON p.post_author = um.user_id WHERE um.`meta_value` LIKE '%".$_POST['keyword']."%'";
	$learnersData = $wpdb->get_results($sql);
	$user_id = $learnersData[0]->user_id;
	$user = get_user_by('id',$user_id);
	$args = array(
				'user' => $user_id,
				'order'           => 'DESC',
				'per_page'        => -1,
			);
		 
	if ( bp_course_has_items( $args ) && $user_id > 0) :
		while ( bp_course_has_items() ) : bp_course_the_item();
			?>
	<tr>
        <td><?php echo get_user_meta($user_id, 'first_name', true); ?></td>
		<td><?php echo get_user_meta($user_id, 'last_name', true); ?></td>
		<td><?php echo $user->user_email; ?></td>
        <td><?php echo get_user_meta($user_id, 'billing_phone', true); ?></td>
        <td><?php bp_course_title() ?></td>
	</tr>
	<?php endwhile;
	else:
		?>
	<tr>
		<td colspan="3" align="center">No Data Available</td>

	</tr>
	<?php
	endif;
	 }
	?>
</table>
</div>
		<?php	}
		
		public function wishlist_report(){
			global $wpdb;
			
			?>
			<div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br>
</div>
<h2>Wishlist Report (<a href="?page=wishlist_report&action=wishlist_report">Export</a>)</h2>
<table class="widefat fixed" cellspacing="0" id="" width="100%">
	<thead>
		<tr>
        	<th scope="col" width="10%">Serial No.</th>
            <th scope="col" width="10%">First Name</th>
			<th scope="col" width="15%">Last Name</th>
			<th scope="col" width="20%">Email Id</th>
            <th scope="col" width="30%">Courses Accessed</th>
            <th scope="col" width="15%">Phone Number </th>
            <th scope="col" width="15%">Country </th>
            <th scope="col" width="15%">Date Added </th>
		</tr>
	</thead>
	<?php
	$wishlistData = array();
	$wishlistData = $wpdb->get_results("select (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='first_name') as first_name, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='last_name') as last_name, u.user_email, p.post_title, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='billing_phone') as phone, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='billing_country') as country, wl.dateadded
FROM ip_users as u 
LEFT JOIN ip_yith_wcwl as wl 
ON u.ID = wl.user_id
RIGHT JOIN ip_yith_wcwl_lists as wll 
ON wll.user_id = wl.user_id
LEFT JOIN ip_posts as p
ON p.ID = wl.prod_id");
	if(!empty($wishlistData)>0){
		$i=0;
		foreach($wishlistData as $data)
		{
			if(trim($data->user_email) != ''){
			$i=$i+1;
			?>
	<tr>
        <td><?php echo $i; ?></td>
		<td><?php echo $data->first_name; ?></td>
		<td><?php echo $data->last_name; ?></td>
        <td><?php echo $data->user_email; ?></td>
        <td><?php echo $data->post_title; ?></td>
        <td><?php echo $data->phone; ?></td>
        <td><?php echo $data->country; ?></td>
        <td><?php echo $data->dateadded; ?></td>
	</tr>
	<?php }
		}
		
	}
	else {
		?>
	<tr>
		<td colspan="3" align="center">No Data Available</td>

	</tr>
	<?php
	}
	?>
</table>
</div>
		<?php	}
		
		public function self_paced_users_report(){
			global $wpdb;
			?>
			<div class="wrap">
<div class="icon32 icon32-posts-page" id="icon-edit-pages"><br>
</div>
<h2>Active Self Paced Users Report <?php if(isset($_POST['start_date'])) { ?>(<a href="?page=self_paced_users_report&action=self_paced_users_report&start_date=<?php echo $_POST['start_date']; ?>&end_date=<?php echo $_POST['end_date']; ?>">Export</a>)<?php } ?></h2>
<?php
$start_date = $end_date = '';
 if(isset($_POST['start_date'])) { $start_date = 'value="'.$_POST['start_date'].'"'; $end_date = 'value="'.$_POST['end_date'].'"'; } ?>
<form method="post" id="export-form" action="?page=self_paced_users_report">
           <p> <strong>Search:</strong> Start Date: <input type="text" required name="start_date" placeholder="20/05/2015" <?php echo $start_date; ?> />End Date: <input type="text" name="end_date" required placeholder="20/06/2015" <?php echo $end_date; ?> /> <input type="submit" name="download_csv" id="download_csv" class="button button-primary" value="Search"></p>
        </form>
  
<table class="widefat fixed" cellspacing="0" id="" width="100%">
	<thead>
		<tr>
        	<th scope="col" width="10%">Order ID</th>
            <th scope="col" width="10%">First Name</th>
			<th scope="col" width="15%">Last Name</th>
			<th scope="col" width="20%">Email Id</th>
            <th scope="col" width="10%">Phone Number</th>
            <th scope="col" width="10%">Country</th>
            <th scope="col" width="20%">Course Name</th>
            <th scope="col" width="10%">Date Time</th>
            <th scope="col" width="10%">Purchased Flag </th>
		</tr>
	</thead>
    <?php if(isset($_POST['start_date']) && $_POST['start_date'] != ''){ ?>
	<?php
	$start = explode("/",$_POST['start_date']);
	$start_date = $start[2].'-'.$start[1].'-'.$start[0];
	$end = explode("/",$_POST['end_date']);
	$end_date = $end[2].'-'.$end[1].'-'.$end[0];
	$self_paced = array();
	$sql = "SELECT * FROM `ip_self_paced_courses` where `date` between '$start_date' AND '$end_date' order by date desc";
	$self_paced = $wpdb->get_results($sql);
	echo '<pre>';
	//print_r($self_paced);
	echo '</pre>';
	if(!empty($self_paced)){			
		foreach($self_paced as $self_pace)
		{
			
			if($self_pace->user_id > 0) {
			$args = array(
			  'post_type' => 'shop_order',
			  'post_status' => 'publish',
			  'meta_key' => '_customer_user',
			  'meta_value' => $self_pace->user_id,
			  'posts_per_page' => '-1'
			);
			$my_query = new WP_Query( $args );
			$customer_orders = $my_query->posts;
			$orderid = '#';
			$purchased_products = array();
			if(count($customer_orders)>0){
			foreach ($customer_orders as $customer_order) {
			 $order = new WC_Order($customer_order->ID);
			 //print_r($order);
			 /*$items = $order->get_items();
			 
			 foreach($items as $item) {
				$purchased_products[]= $item['product_id'];
			 }*/
			 $order->populate($customer_order);
 			 $orderdata = (array) $order;
			 $orderid = $orderdata['id'];
			 $order_date = $orderdata['post']->post_date;
			 //print_r($orderdata['post']->post_date);
			} }
			$user = get_user_by('id',$self_pace->user_id);
			$billing_email = get_user_meta($self_pace->user_id, 'billing_email', true);
			if($billing_email != ''){
			?>
	<tr>
    	<td><?php echo $orderid; ?></td>
        <td><?php echo get_user_meta($self_pace->user_id, 'billing_first_name', true); ?></td>
		<td><?php echo get_user_meta($self_pace->user_id, 'billing_last_name', true); ?></td>
		<td><?php //echo $user->user_email; ?>
        <?php echo get_user_meta($self_pace->user_id, 'billing_email', true); ?></td>
        <td><?php echo get_user_meta($self_pace->user_id, 'billing_phone', true); ?></td>
        <td><?php echo get_user_meta($self_pace->user_id, 'billing_country', true); ?></td>
        <td><?php echo get_the_title($self_pace->product_id); ?></td>
        <td><?php echo date("d-M-Y h:i:s", strtotime($self_pace->date)); ?></td>
        <td><?php //if(in_array($self_pace->product_id, $purchased_products)) 
		if($orderid != '#'){ echo '<span style="color:green">Yes</span>'; } else { echo '<span style="color:red">No</span>'; } ?></td>
	</tr>
	<?php	}
		  }
		}
		
	}
	else {
		?>
	<tr>
		<td colspan="3" align="center">No Data Available</td>

	</tr>
	<?php
	} }
	?>
</table>
</div>
		<?php	}
		//add_action( 'admin_post_print.csv', array($this, 'generate_wishlist_report_csv'));
		public function ccsve_export(){
			$ccsve_export_check = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
			if ($ccsve_export_check == 'wishlist_report') {   
				echo $this->generate_wishlist_report_csv();
				exit;
			}
			if ($ccsve_export_check == 'self_paced_users_report') { 
				$start = explode("/",$_REQUEST['start_date']);
				$start_date = $start[2].'-'.$start[1].'-'.$start[0];
				$end = explode("/",$_REQUEST['end_date']);
				$end_date = $end[2].'-'.$end[1].'-'.$end[0];
				echo $this->generate_self_paced_users_report_csv($start_date, $end_date);
				exit;
			}
			if ($ccsve_export_check == 'learners_course_details') {  
				$keyword =   $_REQUEST['keyword']; 
				echo $this->generate_learners_course_details_csv($keyword);
				exit;
			}
			if ($ccsve_export_check == 'access_logs_report') {   
				$keyword =   $_REQUEST['keyword'];
				echo $this->generate_access_logs_report_csv($keyword);
				exit;
			}
			if ($ccsve_export_check == 'course_enrollment_report') {   
				$course_id =   $_REQUEST['course_id'];
				echo $this->generate_course_enrollment_report_csv($course_id);
				exit;
			}
			
		}
	public function generate_access_logs_report_csv($keyword) {
		global $wpdb;
		$accessLogs = $edata = array();
	$sql = "SELECT * FROM `ip_usermeta` um JOIN `ip_users` u ON u.ID=um.user_id WHERE um.`meta_key` = 'course_access_report' AND u.`user_email` LIKE '%".$keyword."%'";
	$accessLogs = $wpdb->get_results($sql);
	if(!empty($accessLogs)>0){
		$data = json_decode($accessLogs[0]->meta_value);
		foreach($data as $val)
		{
			$myvar = explode("-",$val);
			$lecture = 'Lecture 1';
			if($myvar[1]!=0){ $lecture = get_the_title($myvar[1]); }
			$edata[] = array('First Name'=>get_user_meta($accessLogs[0]->user_id, 'first_name', true), 'Last Name'=>get_user_meta($accessLogs[0]->user_id, 'last_name', true), 'Email Id'=>$accessLogs[0]->user_email, 'Phone Number'=>get_user_meta($accessLogs[0]->user_id, 'billing_phone', true), 'IP Address'=>$myvar[2], 'Start Date-Time'=>date("d-M-Y h:i:s", $myvar[3]), 'Courses Accessed'=>get_the_title($myvar[0]), 'Unit Accessed'=>$lecture);
		}
	}
	
	//print_r($edata); die;
		$filename = "access_logs_report_" . date('Ymd') . ".xls";
		 header("Content-Disposition: attachment; filename=$filename");
  		 header("Content-Type: application/vnd.ms-excel");
		  $flag = false;
		  //$complete_data='';
		  foreach($edata as $row) {
			if(!$flag) {
			  // display field/column names as first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $flag = true;
			}
			array_walk($row, $this->cleanData());
			echo implode("\t", array_values($row)) . "\n";
		  }
		  // Close the file stream
		// Make sure nothing else is sent, our file is done
		exit;
	}
	
	public function generate_course_enrollment_report_csv($course_id) {
		global $wpdb;
		$students_undertaking = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key  = %s ",$course_id));	 
			if(count($students_undertaking)>0){
		foreach($students_undertaking as $student){
			$student = $student->user_id;
			$user = get_user_by('id',$student);
			$edata[] = array('First Name'=>get_user_meta($student, 'first_name', true), 'Last Name'=>get_user_meta($student, 'last_name', true), 'Email Id'=>$user->user_email, 'Phone Number'=>get_user_meta($student, 'billing_phone', true), 'User Registered'=>$user->user_registered, 'Country'=>get_user_meta($student, 'billing_country', true));
			 }
		}
		
			//print_r($edata); die;
		$filename = "earners_course_details_report_" . date('Ymd') . ".xls";
		 header("Content-Disposition: attachment; filename=$filename");
  		 header("Content-Type: application/vnd.ms-excel");
		  $flag = false;
		  //$complete_data='';
		  foreach($edata as $row) {
			if(!$flag) {
			  // display field/column names as first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $flag = true;
			}
			array_walk($row, $this->cleanData());
			echo implode("\t", array_values($row)) . "\n";
		  }
		  // Close the file stream
		// Make sure nothing else is sent, our file is done
		exit;
	}
	
	public function generate_learners_course_details_csv($keyword) {
		global $wpdb;
		$learnersData = array();
	$sql = "SELECT * FROM `ip_usermeta` um JOIN `ip_users` u ON u.ID=um.user_id WHERE u.`user_email` LIKE '%".$keyword."%'";
	$learnersData = $wpdb->get_results($sql);
	$user_id = $learnersData[0]->user_id;
	$user = get_user_by('id',$user_id);
	$args = array(
				'user' => $user_id,
				'order'           => 'DESC',
				'per_page'        => -1,
			);
		 
	if ( bp_course_has_items( $args ) && $user_id > 0) :
		while ( bp_course_has_items() ) : bp_course_the_item();
			$edata[] = array('First Name'=>get_user_meta($user_id, 'first_name', true), 'Last Name'=>get_user_meta($user_id, 'last_name', true), 'Email Id'=>$user->user_email, 'Phone Number'=>get_user_meta($user_id, 'billing_phone', true), 'Courses Purchased'=>bp_course_get_name());
			 endwhile;
		endif;
	//print_r($edata); die;
		$filename = "earners_course_details_report_" . date('Ymd') . ".xls";
		 header("Content-Disposition: attachment; filename=$filename");
  		 header("Content-Type: application/vnd.ms-excel");
		  $flag = false;
		  //$complete_data='';
		  foreach($edata as $row) {
			if(!$flag) {
			  // display field/column names as first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $flag = true;
			}
			array_walk($row, $this->cleanData());
			echo implode("\t", array_values($row)) . "\n";
		  }
		  // Close the file stream
		// Make sure nothing else is sent, our file is done
		exit;
	}
	
	public function generate_self_paced_users_report_csv($start_date, $end_date) {
		global $wpdb;
	$self_paced = array();
	$sql = "SELECT * FROM `ip_self_paced_courses` where `date` between '$start_date' AND '$end_date' order by date desc";
	$self_paced = $wpdb->get_results($sql);
	
	$data = $edata = array();
	if(!empty($self_paced)){			
		foreach($self_paced as $self_pace)
		{
			if($self_pace->user_id > 0) {
			$args = array(
			  'post_type' => 'shop_order',
			  'post_status' => 'publish',
			  'meta_key' => '_customer_user',
			  'meta_value' => $self_pace->user_id,
			  'posts_per_page' => '-1'
			);
			$my_query = new WP_Query($args);
			
			$customer_orders = $my_query->posts;
			$orderid = '#';
			$purchased_products = array();
			if(count($customer_orders)> 0){
			foreach ($customer_orders as $customer_order) {
			 $order = new WC_Order($customer_order->ID);
			 /*$items = $order->get_items();
			 foreach($items as $item) {
				$purchased_products[]= $item['product_id'];
			 }*/
			 $order->populate($customer_order);
 			 $orderdata = (array) $order;
			 $orderid = $orderdata['id'];
			 $order_date = $orderdata['post']->post_date;
			 }
			}
			$pflag = "No";
			if($orderid != '#') $pflag = "Yes";
			$user = get_user_by('id',$self_pace->user_id);
			$billing_email = get_user_meta($self_pace->user_id, 'billing_email', true);
			if($billing_email != '' && (strtotime($order_date) <= strtotime($end_date)) && (strtotime($order_date) >= strtotime($start_date))){
			$edata[] = array('Order ID'=>$orderid,'First Name'=>get_user_meta($self_pace->user_id, 'billing_first_name', true), 'Last Name'=>get_user_meta($self_pace->user_id, 'billing_last_name', true), 'Email Id'=>get_user_meta($self_pace->user_id, 'billing_email', true), 'Phone Number'=>get_user_meta($self_pace->user_id, 'billing_phone', true), 'Country'=>get_user_meta($self_pace->user_id, 'billing_country', true), 'Courses Accessed'=>get_the_title($self_pace->product_id), 'Date-Time'=>date("d-M-Y h:i:s", strtotime($self_pace->date)), 'Purchased Flag'=>$pflag);
			  }
			}
		}
	}
	
		$filename = "self_paced_users_report_" . date('Ymd') . ".xls";
		 header("Content-Disposition: attachment; filename=$filename");
  		 header("Content-Type: application/vnd.ms-excel");
		  $flag = false;
		  //$complete_data='';
		  foreach($edata as $row) {
			if(!$flag) {
			  // display field/column names as first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $flag = true;
			}
			array_walk($row, $this->cleanData());
			echo implode("\t", array_values($row)) . "\n";
		  }
		  // Close the file stream
		// Make sure nothing else is sent, our file is done
		exit;
	}
	public function generate_wishlist_report_csv() {
		global $wpdb;
			$wishlistData = $wpdb->get_results("select (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='first_name') as first_name, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='last_name') as last_name, u.user_email, p.post_title, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='billing_phone') as phone, (select um.meta_value from ip_usermeta as um where u.ID = um.user_id and um.meta_key='billing_country') as country, wl.dateadded
FROM ip_users as u 
LEFT JOIN ip_yith_wcwl as wl 
ON u.ID = wl.user_id
RIGHT JOIN ip_yith_wcwl_lists as wll 
ON wll.user_id = wl.user_id
LEFT JOIN ip_posts as p
ON p.ID = wl.prod_id");
$data = array();
	if(!empty($wishlistData)>0){
		foreach($wishlistData as $wdata)
			{
				if(trim($wdata->user_email) != ''){
			$data[] = array('First Name'=>$wdata->first_name, 'Last Name'=>$wdata->last_name, 'Email Id'=>$wdata->user_email, 'Courses Accessed'=>$wdata->post_title, 'Phone Number'=>$wdata->phone, 'Country'=>$wdata->country, 'Date Added'=>$wdata->dateadded);
				}
			}
		}
		$filename = "wishlist_report_" . date('Ymd') . ".xls";
		 header("Content-Disposition: attachment; filename=$filename");
  		 header("Content-Type: application/vnd.ms-excel");
		  $flag = false;
		  //$complete_data='';
		  foreach($data as $row) {
			if(!$flag) {
			  // display field/column names as first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $flag = true;
			}
			array_walk($row, $this->cleanData());
			echo implode("\t", array_values($row)) . "\n";
		  }
		  // Close the file stream
		// Make sure nothing else is sent, our file is done
		exit;
	}
	public function cleanData(&$str)
	  {
		$str = preg_replace("/t/", "\t", $str);
		$str = preg_replace("/r?n/", "\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	  }	 
	  public function theme_options()
	  {
		if(isset($_POST) && !empty($_POST))
    {
				update_option('active_common_sidebar_banner', $_POST['active_common_sidebar_banner']);
				update_option('sidebar_banner_url', $_POST['sidebar_banner_url']);	
				update_option('sidebar_banner_link', $_POST['sidebar_banner_link']);
				update_option('disclaimer', $_POST['disclaimer']);
			
		}
		?>
        <form action="?page=theme_options" method="post">
        <table>
        <tr><th colspan="2">Do you want to enable common sidebar banner? <input type="radio" name="active_common_sidebar_banner" value="yes" <?php if(get_option('active_common_sidebar_banner') == 'yes') echo 'checked="checked"'; ?>/>Yes <input type="radio" name="active_common_sidebar_banner" value="no" <?php if(get_option('active_common_sidebar_banner') == 'no') echo 'checked="checked"'; ?> />No </th></tr>
        <tr><th>Sidebar Banner URL</th><td><input name="sidebar_banner_url" style="width:600px;" value="<?php echo get_option('sidebar_banner_url'); ?>"/></td></tr>
        <tr><th>Sidebar Banner Link</th><td><input name="sidebar_banner_link" style="width:600px;" value="<?php echo get_option('sidebar_banner_link'); ?>"/></td></tr>
        <tr><th>Enter Disclaimer</th><td><textarea rows="10" cols="80" name="disclaimer"><?php echo stripslashes(get_option('disclaimer')); ?></textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Update" /></td></tr>
        </table>
        </form>
	  <?php }	  
  }
}

if(class_exists('intellipaatCustom') ){
	$SUA = new intellipaatCustom();
}

//-- LICENSE VALIDATION SCRIPT END--//
