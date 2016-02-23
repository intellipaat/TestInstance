<?php
/*
Plugin Name: Categories but exclude
Plugin URI: http://www.poselab.com/
Description: Displays all categories except those selected in widget preferences.
Author: Javier Gómez Pose
Author URI: http://www.poselab.com/
Version: 1.0
License: GPL2
	
	Copyright 2010  Javier Gómez Pose  (email : javierpose@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Adds catbutexclude_Widget widget.
*/

function catbutexclude_load_textdomain() {
  load_plugin_textdomain( 'categories-but-exclude', false, dirname( plugin_basename( __FILE__ ) ) ); 
}
add_action('plugins_loaded', 'catbutexclude_load_textdomain');


class catbutexclude_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'catbutexclude_widget', // Base ID
			 __( 'Categories but exclude', 'categories-but-exclude' ), // Name
			array( 'description' => __( 'List all categories except some', 'categories-but-exclude' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$catbutexclude = apply_filters( 'widget_catbutexclude', $instance['catbutexclude'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		

			echo '<ul id="category_excluder_widget">';
				$cat_params = Array(
						'hide_empty'	=>	FALSE,
						'title_li'		=>	''
					);
				if( strlen( trim( $catbutexclude ) ) > 0 ){
					$cat_params['exclude'] = trim( $catbutexclude );
				}
				wp_list_categories( $cat_params );
			echo '</ul>';

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['catbutexclude'] = strip_tags( $new_instance['catbutexclude'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Categories', 'categories-but-exclude' );
		}
		$catbutexclude = strip_tags($instance['catbutexclude']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'categories-but-exclude' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'catbutexclude' ); ?>"><?php _e( 'Categories to exclude:', 'categories-but-exclude' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'catbutexclude' ); ?>" name="<?php echo $this->get_field_name( 'catbutexclude' ); ?>" type="text" value="<?php echo esc_attr( $catbutexclude ); ?>" />
		</p>
		<p><?php _e( 'Enter a comma-separated list of category ID numbers to display all of your categories except these ones', 'categories-but-exclude' ); ?></p>
<?php 
	}	

} // class catbutexclude_Widget

// register catbutexclude_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "catbutexclude_widget" );' ) );


?>