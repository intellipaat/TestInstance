<?php 
/**
 * Query course owned by a student ------- By Makarand Mane
 **/


add_action('admin_menu', 'register_student_to_course_search_page');

function register_student_to_course_search_page() {
	add_submenu_page( 'lms', __('Search courses by student','intellipaat'), __('Student to Course','intellipaat'),  'edit_posts', 'student-course-search', 'student_to_course_search' );
}

function student_to_course_search() {
	
	global $post;
	
	$email = isset( $_GET['email'] ) ? $_GET['email'] : '';
	
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';  screen_icon('themes'); 
		echo '<h2>Search courses by student email.</h2>';
		
		?>
		<form method="GET" action="">
            <table class="" cellpadding="1">
                              
                <tr valign="top" >
                    <th scope="row" align="right">
                        <label for="email">
                            Email
                        </label> 
                    </th>
                    <td>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required />
                    </td>
                </tr>
               
            </table>
            <p>
            	<input type="hidden" name="page" value="student-course-search" />
            	<input type="hidden" name="search" value="Y" />
                <input type="submit" value="Search Now" class="button-primary"/>
            </p>
        </form>
		<?php
	echo '</div>';
	
	
	if (isset($_GET["search"]) && isset( $_GET['email'] )) {
		$user = get_user_by( 'email', $email ); ?>
		
            <table class="" cellpadding="1">
                              
                <tr valign="top" align="left" >
                    <th scope="row" >
                        User Name
                    </th>
                    <td>
                        : 
                    </td>
                    <td>
                        <?php echo $user->user_login; ?>
                    </td>
                </tr>
               
                              
                <tr valign="top" >
                    <th scope="row" align="left"  >
                        Display name
                    </th>
                    <td>
                        : 
                    </td>
                    <td>
                        <?php echo $user->display_name; ?>
                    </td>
                </tr>
               
                              
                <tr valign="top" >
                    <th scope="row"  align="left" >
                        User registered on
                    </th>
                    <td>
                        : 
                    </td>
                    <td>
                        <?php echo $user->user_registered; ?>
                    </td>
                </tr>
               
            </table>
         <?php 
		 
			$args = array(
				'user' => $user->ID,
				'order'           => 'DESC',
				'per_page'        => -1,
			);
			$count =1;
		 
		 if ( bp_course_has_items( $args ) ) : ?>
         
         	<table border="1" cellpadding="5" style="border-collapse:collapse;">
            	<thead><tr> <th colspan="4">Student subscribed to these following courses</th></tr></thead>
                <tbody>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Course Name</th>
                        <th>Admin link</th>
                        <th>Activity</th>
                    </tr>
             
                 <?php
                    while ( bp_course_has_items() ) : bp_course_the_item();
                        ?>                    
                            <tr>
                                <td><?php echo $count; $count++; ?></td>
                                <td><?php bp_course_title() ?></td>
                                <td><a target="_blank" href="<?php echo get_permalink() ?>?action=admin&student=<?php echo $user->user_login?>">Admin</a></td>
                                <td><a target="_blank" href="<?php echo get_permalink() ?>?action=admin&activity&student=<?php echo $user->ID?>">Activity</a></td>
                            </tr>
                        <?php                    
                    endwhile; 
            	?>
            </tbody>
          </table>
		<?php else: ?>		
            <div id="message" class="info">
                <p><?php echo $user->display_name." have not subscribed to any courses.";  ?></p>
            </div>
    	<?php
        endif;
		 
		 
	}

}
/**
 * Coupon Genarator ------- end
 **/
 ?>