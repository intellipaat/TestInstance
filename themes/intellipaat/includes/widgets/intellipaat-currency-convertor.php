<?php

class IntellipatCurrencyConvertor extends WP_Widget {

	function IntellipatCurrencyConvertor() {
		// Instantiate the parent object
		parent::__construct( false, 'Woocommerce Currency Convertor for Intellipaat' );
	}

	function widget( $args, $instance ) {
		// Widget output
		extract($args);
		/*$INR=$USD='';
			$USD = 'selected="selected"';*/

		$title   = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		
		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;
		
		/*if ( is_user_logged_in() ) {
				 $country     = get_user_meta( get_current_user_id(), 'billing_country', true );
		 }
		 else{			 
				 $country	= WC()->customer->get_country( );
		}*/

		/* if($_SESSION['REMOTE_ADDR_CUREE'] == 'IN'){
			$INR = 'selected="selected"';
			$USD = '';
		 }*/
		 
		//echo '<p>Select your checkout Currency.</p>';		
		/*echo '<select style="display:none;" id="curreny_convertor" name="curreny_convertor" title="To pay in INR currency, You have fill your billing addresss from India. Other than Indian billing address can pay in  USD currency">
				<option value="INR" '.$INR.'>Indian Rupee</option>
				<option value="USD" '.$USD.'>US Dollar</option>
			</select>';*/
		
		add_action('wp_footer',array($this,'javascript'), 20);	
			
		echo $after_widget;
	}
	
	public function javascript( ) {
		?><script>
			function addThousandsSeparator(input) {
				return input.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
				<?php /*
				*		http://stackoverflow.com/questions/13621769/adding-comma-as-thousands-separator-javascript-output-being-deleted-instead
				
				//alternate solution
						var output = input
						if (parseFloat(input)) {
							input = new String(input); // so you can perform string operations
							var parts = input.split("."); // remove the decimal part
							parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
							output = parts.join(".");
						}
					
						return output;
				*/
				
				
				if($_SESSION['REMOTE_ADDR_CUREE'] == 'IN')
					$curreny ='INR';
				else					
					$curreny ='USD';
				 
		 ?>

			jQuery(document).ready(function($){
				//add_curreny_span();	
				//switch_currency(jQuery("#curreny_convertor").val());
				switch_currency('<?php echo $curreny ?>');
				
				jQuery( document ).ajaxComplete(function() {
						<?php if($curreny =='INR') { ?>
							set_india();
						<?php }?>
						switch_currency('<?php echo $curreny ?>');
				});
				
				//alert(jQuery("#curreny_convertor").val());
				//jQuery("li.payment_method_paypal input").attr("checked","checked");
				//jQuery("li.payment_method_ebs").hide();
								
				/*jQuery( document ).ajaxComplete(function() {
					if(jQuery("#billing_country").val()=='IN' && jQuery("#billing_country").prop('disabled') == false)
						set_india();
					else{
						//add_curreny_span();
						switch_currency('<?php echo $curreny ?>', 1);
						if(jQuery("#billing_country").val()!='IN' && jQuery("#billing_country").find('option[value="IN"]').prop('disabled') == false)
							disable_india();
					}
				});
				
				jQuery("#curreny_convertor").change(function(){
					switch_currency(jQuery(this).val(),0);
				});*/
				
				/*jQuery("#billing_country").change(function(){
					if(jQuery(this).val() == 'IN' )
						jQuery("#curreny_convertor").val('INR').trigger('change');
					else
						jQuery("#curreny_convertor").val('USD').trigger('change');
				});*/
				
				function switch_currency($currency, $afterAjax ){
					
					if( $currency == "INR"){
						/*if($afterAjax == 0){
							set_india();
							jQuery("#billing_country").val('IN').trigger('change');
						}*/
						//settings for payment gateway
						jQuery("li.payment_method_ebs").show();
						jQuery("li.payment_method_ebs input").attr("checked","checked");
						jQuery("li.payment_method_paypal").hide();
						jQuery("li.payment_method_ebs_global").hide();
						//settings for currency conversion						
						/*jQuery('span.amount').each(function(){
							jQuery(this).html('&#8377; '+ addThousandsSeparator(parseFloat(jQuery(this).attr("data-price"))*<?php //echo vibe_get_option('dollar_to_inr_conversion_rate'); ?>));
						});	*/
						
					}else{
						/*if($afterAjax == 0){
							if(jQuery("#billing_country").val() == 'IN')
								jQuery("#billing_country").val(null).trigger('change');
							disable_india();
						}*/
						//settings for payment gateway
						jQuery("li.payment_method_ebs_global").show();
						jQuery("li.payment_method_paypal").show();
						jQuery("li.payment_method_paypal input").attr("checked","checked");
						jQuery("li.payment_method_ebs").hide();
						//settings for currency conversion
						/*jQuery('span.amount').each(function(){
							jQuery(this).html(jQuery(this).attr("data-original"));
						});*/
					}
				}
								
				function set_india(){
					jQuery("#billing_country").find('option[value="IN"]').prop('disabled', false).show();
					jQuery("#billing_country").prop('disabled', true);
					$('<input>').attr({
						type: 'hidden',
						value: 'IN',
						id: 'billing_country_hidden',
						class: 'billing_country',
						name: 'billing_country'
					}).appendTo('form.checkout');					
				}
								
				function disable_india(){
						jQuery("#billing_country_hidden").remove();
						jQuery("#billing_country").prop('disabled', false);
						jQuery("#billing_country").find('option[value="IN"]').prop('disabled', true).hide();
				}
				
				/*function add_curreny_span(){
					jQuery('span.amount').each(function(){
						
						if(jQuery(this).hasClass('rupee'))
							jQuery(this).hide();
						else{								
							var original_code = jQuery(this).attr("data-original");		
							if (typeof original_code == 'undefined' || original_code == false) {
								jQuery(this).attr("data-original", jQuery(this).html());
								//jQuery(this).addClass("USD");
							}		
								// Original price
							var original_price = jQuery(this).attr("data-price");
				
							if (typeof original_price == 'undefined' || original_price == false) {
				
								// Get original price
								var original_price = jQuery(this).html();
				
								// Small hack to prevent errors with $ symbols
								jQuery( '<del></del>' + original_price ).find('del').remove();
				
								// Remove formatting
								original_price = original_price.replace( ',', '' );
								original_price = original_price.replace(/[^0-9\.]/g, '');
								original_price = parseFloat( original_price );
				
								// Store original price
								jQuery(this).attr("data-price", original_price);
								//jQuery(this).after("<span class='INR amount'>INR. "+original_price+"</span>"); //my second to add span having class inr and toggle hide and show
							}
						}
					
					});
				}*/
				
			});
		</script>
        <?php	
	}
	
	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance['title'] = empty( $new_instance['title'] ) ? '' : strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		global $wpdb;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wc_currency_converter') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} else {echo __('Currency converter', 'wc_currency_converter');} ?>" /></p>
		<?php
	}
}

function IntellipatCurrencyConvertor_widget() {
	register_widget( 'IntellipatCurrencyConvertor' );
}

add_action( 'widgets_init', 'IntellipatCurrencyConvertor_widget' );

?>