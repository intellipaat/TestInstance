<?php
/*
Plugin Name: intellipaat XML Sitemap for Images
Plugin URI: http://www.makarandmane.com/portfolio/
Description: This plugin will generate a XML Image Sitemap for your WordPress blog. Open the <a href="tools.php?page=image-sitemap-generate-page">settings page</a> to create your image sitemap.
Author: Makarand Mane
Version: 2.5
Author URI: http://www.makarandmane.com/
*/

add_action('admin_menu', 'image_sitemap_generate_page');

function image_sitemap_generate_page() {
    if(function_exists('add_submenu_page')) add_submenu_page('tools.php', __('Image Sitemap'),
	    __('Image Sitemap'), 'manage_options', 'image-sitemap-generate-page', 'image_sitemap_generate');
}

/* @author  VJTD3 <http://www.VJTD3.com> */
function IsImageSitemapWritable($filename) {
    if(!is_writable($filename)) {
        if(!@chmod($filename, 0666)) {
            $pathtofilename = dirname($filename);
            if(!is_writable($pathtofilename)) {
                if(!@chmod($pathtoffilename, 0666)) {
                    return false;
                }
            }
        }
    }
    return true;
}

function EscapeXMLEntities($xml) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $xml);
}

function image_sitemap_generate () {

  if ($_POST ['submit']) {
    $st = image_sitemap_loop ();
     if (!$st) {
echo '<br /><div class="error"><h2>Oops!</h2><p>The XML sitemap was generated successfully but the  plugin was unable to save the xml to your WordPress root folder at <strong>' . $_SERVER["DOCUMENT_ROOT"] . '</strong>.</p><p>Please ensure that the folder has appropriate <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">write permissions</a>.</p><p> You can either use the chmod command in Unix or use your FTP Manager to change the permission of the folder to 0666 and then try generating the sitemap again.</p><p>If the issue remains unresolved, please post the error message in this <a target="_blank" href="http://wordpress.org/tags/google-image-sitemap?forum_id=10#postform">WordPress forum</a>.</p></div>';	
exit();
}
?>

<div class="wrap">
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<div style="width:800px; padding:10px 20px; background-color:#eee; font-size:.95em; font-family:Georgia;margin:20px">
  <h2>XML Sitemap for Images</h2>
  <?php $sitemapurl = get_bloginfo('url') . "/sitemap-image.xml"; ?>
  <p>The <a target="_blank" href="<?php echo $sitemapurl; ?>">XML Sitemap</a> was generated successfully and you can <a target="_blank" href="http://www.google.com/webmasters/sitemaps/ping?sitemap=<?php echo $sitemapurl; ?>">ping Google</a> to inform them about your updated sitemap.</p>
  <p>This WordPress Plugin is written by <a href="http://www.labnol.org/about/">Amit Agarwal</a> of <a href="http://www.labnol.org/">Digital Inspiration</a>. For feedback or suggestions on improving this plugin, please send me an email at amit@labnol.org</p>
  <p><a href="https://twitter.com/labnol" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @labnol</a>
    <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fdigital.inspiration&amp;send=false&amp;layout=button_count&amp;width=300&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font=arial&amp;height=24&amp;appId=197498283654348" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:20px;" allowTransparency="true"></iframe>
  </p>
</div>
<?php } else { ?>
<div class="wrap">
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<div style="width:800px; padding:10px 20px; background-color:#eee; font-size:.95em; font-family:Georgia;margin:20px">
  <h2>XML Sitemap for Images</h2>
  <p>Sitemaps are a way to tell Google and other search engines about web pages, images and video content on your site that they may otherwise not discover. </p>
  <form id="options_form" method="post" action="">
    <div class="submit">
      <input type="submit" name="submit" id="sb_submit" value="Generate Image Sitemap" />
    </div>
  </form>
  <p>Click the button above to generate a Image Sitemap for your website. Once you have created your Sitemap, you should submit it to Google using Webmaster Tools. </p>
  <p>You may also want to create separate <a href="http://wordpress.org/extend/plugins/xml-sitemaps-for-videos/">Video Sitemap</a> and <a href="http://wordpress.org/extend/plugins/google-mobile-sitemap/">Mobile Sitemap</a> for improving your site's visibility in Google.</p>
  <p>This WordPress Plugin is written by <a href="http://www.labnol.org/about/">Amit Agarwal</a> of <a href="http://www.labnol.org/">Digital Inspiration</a>. </p>
</div>
<?php }
}

function image_sitemap_loop () {
	global $wpdb;
	$opt = get_option('wpseo_xml');
	$excluded_posts = explode(',', $opt['excluded-posts']);

	$posts = $wpdb->get_results ("SELECT id, post_parent, post_content, guid, post_type, post_status FROM $wpdb->posts wposts
                                     WHERE ((wposts.post_type = 'post') and (wposts.post_status='publish'))
                                     OR    ((wposts.post_type = 'page') and (wposts.post_status='publish'))
                                     OR    ((wposts.post_type = 'course') and (wposts.post_status='publish'))
                                     OR    ((wposts.post_type = 'tutorial') and (wposts.post_status='publish'))
                                     OR    ((wposts.post_type = 'interview-question') and (wposts.post_status='publish'))
                                     OR    ((wposts.post_type = 'attachment') and (wposts.post_status='inherit')
                                           and ((wposts.post_mime_type = 'image/jpg') or (wposts.post_mime_type = 'image/gif') 
                                           or (wposts.post_mime_type = 'image/jpeg') or (wposts.post_mime_type = 'image/png')))
                                     ");

	if (empty ($posts)) {
	   return false;
	} else {
          $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
          $xml .= '<!-- Created by (https://intellipaat.com/) -->'."\n";
          $xml .= '<!-- Generated-on="'.date("F j, Y, g:i a").'" -->'."\n";
          $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

          foreach($posts as $post) {

               if($post->post_type == "attachment") {
                   if($post->post_parent != 0 && !in_array($post->post_parent, $excluded_posts ) ) {
						if( $opt['post_types-' . get_post_type( $post->post_parent ) . '-not_in_sitemap'] === false  && get_post_status($post->post_parent) == 'publish')
                       		$images[$post->post_parent][] = $post->guid;
                   }
               }
               else if(preg_match_all("/[\'\"](http:\/\/.[^\'\"]+\.(?:jpe?g|png|gif))[\'\"]/ui", $post->post_content, $matches, PREG_SET_ORDER)) {
                   foreach($matches as $match) {
                       $images[$post->id][] = $match[1];
                   }
               }
         }

         foreach($images as $k => $v) {
            $permalink = get_permalink($k);
			if ( ! empty ( $permalink ) ) {
				$img = "<image:image><image:loc>".implode("</image:loc></image:image><image:image><image:loc>", $v)."</image:loc></image:image>";
				$xml .= "<url><loc>" . EscapeXMLEntities($permalink) . "</loc>" . $img . "</url>";
			}
         }
         $xml .= "\n</urlset>";
        }

        $image_sitemap_url = $_SERVER["DOCUMENT_ROOT"].'/sitemap-image.xml';
        if(IsImageSitemapWritable($_SERVER["DOCUMENT_ROOT"]) || IsImageSitemapWritable($image_sitemap_url)) {
            if(file_put_contents($image_sitemap_url, $xml)) {
                   return true;
            }
        }
   return false;
 }
?>