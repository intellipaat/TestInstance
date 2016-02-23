<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */
?>
<h3><?php _e('General Settings', 'adrotate'); ?></h3>
<span class="description"><?php _e('General settings for AdRotate.', 'adrotate'); ?></span>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e('Load jQuery', 'adrotate'); ?></th>
		<td><label for="adrotate_jquery"><input type="checkbox" name="adrotate_jquery" <?php if($adrotate_config['jquery'] == 'Y') { ?>checked="checked" <?php } ?> /> <?php _e('jQuery is required for dynamic groups, ad block detection and some other features. Enable this if your theme does not load jQuery.', 'adrotate'); ?></label></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Load scripts in footer?', 'adrotate'); ?></th>
		<td><label for="adrotate_jsfooter"><input type="checkbox" name="adrotate_jsfooter" <?php if($adrotate_config['jsfooter'] == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Enable if you want to load all AdRotate Javascripts in the footer of your site.', 'adrotate'); ?></label></td>
	</tr>
</table>

<h3><?php _e('Banner Folder', 'adrotate'); ?></h3>
<span class="description"><?php _e('Set a location where your banner images will be stored.', 'adrotate'); ?>  <?php _e('Available in AdRotate Pro!', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Location', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_banner_folder"><?php echo site_url(); ?>/<input name="adrotate_banner_folder_disabled" type="text" class="search-input" size="30" value="<?php echo $adrotate_config['banner_folder']; ?>" autocomplete="off" disabled /> <?php _e('(Default: wp-content/banners/).', 'adrotate'); ?><br />
			<span class="description"><?php _e('To try and trick ad blockers you could set the folder to something crazy like:', 'adrotate'); ?> "/wp-content/<?php echo adrotate_rand(12); ?>/".<br />
			<?php _e("This folder will not be automatically created if it doesn't exist. AdRotate will show errors when the folder is missing.", 'adrotate'); ?></span>
		</td>
	</tr>
</table>

<h3><?php _e('Bot filter', 'adrotate'); ?></h3>
<span class="description"><?php _e('The bot filter is used for the AdRotate stats tracker.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('User-Agent Filter', 'adrotate'); ?></th>
		<td>
			<textarea name="adrotate_crawlers" cols="90" rows="15"><?php echo $crawlers; ?></textarea><br />
			<span class="description"><?php _e('A comma separated list of keywords. Filter out bots/crawlers/user-agents.', 'adrotate'); ?><br />
			<?php _e('Keep in mind that this might give false positives. The word \'fire\' also matches \'firefox\', but not vice-versa. So be careful!', 'adrotate'); ?><br />
			<?php _e('Only words with alphanumeric characters and [ - _ ] are allowed. All other characters are stripped out.', 'adrotate'); ?><br />
			<?php _e('Additionally to the list specified here, empty User-Agents are blocked as well.', 'adrotate'); ?> (<?php _e('Learn more about', 'adrotate'); ?> <a href="http://en.wikipedia.org/wiki/User_agent" title="User Agents" target="_blank"><?php _e('user-agents', 'adrotate'); ?></a>.)</span>
		</td>
	</tr>
</table>