<?php

add_action( 'wp_loaded', 'multi_keyword_menu', 10 );

function multi_keyword_menu(){
	class VIBE_Options_multi_keyword_menu extends VIBE_Options{	
		function __construct($field = array(), $value ='', $parent){			
			parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
			$this->field = $field;
			$this->value = $value;	
		}
		function enqueue(){}
		
	}//class

}

function multi_keyword_menu_callback($field, $value){
			$myposts = get_posts($field['args'].'&posts_per_page=-1');
			$class = (isset($field['class']))?$field['class']:'regular-text';
			echo '<ul id="'.$field['id'].'-ul">';

			if(isset($value) && is_array($value)){
				foreach($value['post_id'] as $k=>$v){ 
					if($value != ''){
						echo '<li>
													<select id="'.$field['id'].'-'.$k.'[post_id]" name="wplms['.$field['id'].'][post_id]['.$k.']">';
													
													foreach($myposts as $mypost){
														echo '<option value="'.$mypost->ID.'" '.(($v ==$mypost->ID)?'selected="selected"':'').'>'.$mypost->post_title.'</option>';															
													}
											echo 	'</select>
													<input type="text" id="'.$field['id'].'-'.$k.'-keywords" name="wplms['.$field['id'].'][keywords]['.$k.']" value="'.esc_attr($value['keywords'][$k]).'" class="'.$class.'" /> 
													<a href="javascript:void(0);" class="vibe-opts-multi-social-remove">'.__('Remove', 'vibe').'</a>
												 </li>';
						
					}//if
					
				}//foreach
			}else{
					
			}//if
			
			echo '<li style="display:none;">
						<select id="'.$field['id'].'[post_id]" rel-name="wplms['.$field['id'].'][post_id][]">';
						
						foreach($myposts as $mypost){
								echo '<option value="'.$mypost->ID.'">'.$mypost->post_title.'</option>';
						}
														
											echo 	'</select>
													<input type="text" id="'.$field['id'].'[keywords]" name="" placeholder="'.__('Enter comma separated keywords.','vibe').'" value="" class="'.$class.'" rel-name="wplms['.$field['id'].'][keywords][]" /> 
														<a href="javascript:void(0);" class="vibe-opts-multi-social-remove">'.__('Remove', 'vibe').'</a>
													</li>';
			
			echo '</ul>';
			
			echo '<a href="javascript:void(0);" class="vibe-btn green vibe-opts-multi-social-add " rel-id="'.$field['id'].'-ul">'.__('Add menu', 'vibe').'</a><br/>';
			
			echo (isset($field['desc']) && !empty($field['desc']))?' <span class="description">'.$field['desc'].'</span>':'';
			
		}//function?>