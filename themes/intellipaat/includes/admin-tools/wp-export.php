<?php

class WP_Export_Extra_Fileds{
	
	protected $post_types = array(
								'news',
								'forum',
								'tutorial',
								'interview-question',
								'jobs',
							);
	public $post_type = '';
	
	function __construct() {		
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_my_script') );
		add_action( 'admin_head-export.php', array($this, 'export_js') );
		add_action( 'export_wp',  array($this,'add_query_filter') );		
	}
	
	function enqueue_my_script($hook) {
		if ( 'export.php' != $hook ) {
			return;
		}
        wp_enqueue_script( 'jquery-ui-datepicker' );
	}
	
	function export_js() {
		foreach($this->post_types as $post_type){
			$this->post_type = $post_type;
			$start_date = $this->export_date_options($post_type, 'start');
			$end_date	= $this->export_date_options($post_type, 'end');
			?>
			<script type="text/javascript">
			//<![CDATA[
				jQuery( document ).ready( function( $ ) {
					var form = $( '#export-filters' );
							ourradio = form.find( 'input:radio[value=<?php echo $post_type; ?>]' );
							ourradio.closest( 'p' ).after( '<ul class="u-export-filters" id="<?php echo $post_type; ?>-filters" style="margin-left: 18px; display: none;">\n<li>\n<label>Date range:</label>\n<?php $this->date_filter($start_date, $end_date); ?> </li>\n</ul>\n' );
						filters = form.find( '.export-filters' );
					form.find( 'input:radio' ).change(function() {
						switch ( $( this ).val() ) {
							case '<?php echo $post_type; ?>': $( '#<?php echo $post_type; ?>-filters' ).slideDown(); break;
							default: $( '#<?php echo $post_type; ?>-filters' ).slideUp(); break;
						}
					});
					jQuery('.<?php echo $post_type; ?>_start_date').datepicker({
															  'dateFormat'	:'yy-mm-dd',
															  'changeMonth'	: true,
															  'changeYear'	: true,
															  'minDate'		:'<?php echo $start_date ; ?>',
															  'maxDate'		:'<?php echo $end_date ; ?>',
															  });
					jQuery('.<?php echo $post_type; ?>_end_date').datepicker({
															  'dateFormat'	:'yy-mm-dd',
															  'changeMonth'	: true,
															  'changeYear'	: true,
															  'minDate'		:'<?php echo $start_date ; ?>',
															  'maxDate'		:'<?php echo $end_date ; ?>'
															});	
				});
			//]]>
			</script>
			<?php
		}
	}
	
	/*
	We are using admin_head-export.php action hook. This is called
	whenever the export screen is loaded. The method review_export_js 
	adds the necessary markup. We first find the radio button 
	displaying our custom post type and then append the required 
	filter markup. In this case we are adding a date filter to the 
	custom post type. The date_filter method just echos the markup 
	for the starting and ending dates.
	*/
	
	function date_filter($start_date='', $end_date='') {
		$this->start_date_filter($start_date);
		$this->end_date_filter($end_date);
	}
	 
	function start_date_filter($start_date) {
		echo '<input type="text"  name="'.$this->post_type.'_start_date" class="'.$this->post_type.'_start_date" placeholder="'.$start_date.'"  placeholder="Start Date" />';
	}
	 
	function end_date_filter($end_date) {
		echo '<input type="text"  name="'.$this->post_type.'_end_date" class="'.$this->post_type.'_end_date" placeholder="'.$end_date.'" placeholder="End Date" />';
	}
	 
	function export_date_options($post_type='', $date='start') {
		global $wpdb, $wp_locale;
		$post_type = $post_type ? $post_type : $this->post_type;
		$order = ($date=='start') ? 'ASC' : 'DESC';
		$result = $wpdb->get_row( "
			SELECT DATE( post_date ) AS day
			FROM $wpdb->posts
			WHERE post_type = '$post_type' AND post_status != 'auto-draft'
			ORDER BY post_date $order 
			LIMIT 1
		" );
		return $result->day;
	}
	 
	/*
	From now on, the export screen has the option of accepting the start 
	date and end date fields whenever the custom post type is selected. 
	Finally, we need to filter the records. We use the query filter to 
	add the additional where clauses. But first, we ensure that we add 
	the query filter only when export is taking place using the 
	export_wp action hook.
	*/
	 
	function add_query_filter() {
		if( $_REQUEST['content'] != 'post' && $_REQUEST['content'] != 'page' && $_REQUEST['content'] != 'all' && in_array( $_REQUEST['content'], $this->post_types)   ) {
			add_filter( 'query', array( $this, 'query_filter') );
			$this->post_type = $_REQUEST['content'];
		}
	}

	 
	/*
	The tutorial_query method updates the query to include the filters for
	the start and end dates.
	*/
	 
	function query_filter( $query ) { 
		
		if ( $_REQUEST[$this->post_type.'_start_date'] || $_REQUEST[$this->post_type.'_end_date'] ) {
			$start_date = $_REQUEST[$this->post_type.'_start_date'] ;
			$end_date 	= $_REQUEST[$this->post_type.'_end_date'] ;
	 
			global $wpdb;
		 
			if ( $start_date ) {
				$query .= $wpdb->prepare( " AND {$wpdb->posts}.post_date >= %s", date( 'Y-m-d', strtotime( $start_date ) ) );
			}
		 
			if ( $end_date ) {
				$query .= $wpdb->prepare( " AND {$wpdb->posts}.post_date < %s", date( 'Y-m-d', strtotime( '+1 day', strtotime( $end_date ) ) ) );
			}
		}
		remove_filter( 'query', array($this, 'query_filter') );
	
		return $query;
	}
	
}
$export_extras = new WP_Export_Extra_Fileds();

?>