<?php get_header( 'buddypress' ); ?>

<?php 

if(isset($_POST['search']))
{
	$search_string='';	
	$search_string.=$_POST['search_string'].'&'.$_POST['remote_address'];
	if(!empty($_POST['search_string'])){
		$text=$_POST['search_string'];
	}
	else{
		$text=$_POST['remote_address'];	
	}

	$active=1;
}
else{
	$text = get_field('intellipaat_job_search_string');
	$text = $text ? $text  : 'Hadoop';
	$search_string = urlencode($text).'&in';
}


$feed = file_get_contents('http://rss.indeed.com/rss?q='.$search_string.'');

$feed = str_replace('<media:', '<', $feed);
$rss = simplexml_load_string($feed);

$total_records=count($rss->channel->item);

$count_text='Found '.$total_records.' '.$text.' Jobs';

$form =  '<div class="job-box">
                <form id="job_form" action="" method="POST"><input id="page_num" name="page_num" type="hidden" value="1" />
                    <div class="row">
                        <div class="form-group">
                        
                            <div class="col-md-4">
                            <label>What</label>
                            <input id="what" class="form-control what" maxlength="512" name="search_string" type="text" value="'.$text.'" />
                            <label>Job title or keyword</label></div>
                            
                            <div class="col-md-4"><label>Where</label>
                            <select id="where" class="form-control where" name="remote_address">
                            <option selected="selected" value="IN">India</option>
                            <option value="AQ">Antarctica</option>
                            <option value="AR">Argentina</option>
                            <option value="AU">Australia</option>
                            <option value="AT">Austria</option>
                            <option value="BH">Bahrain</option>
                            <option value="BE">Belgium</option>
                            <option value="BR">Brazil</option>
                            <option value="CA">Canada</option>
                            <option value="CL">Chile</option>
                            <option value="CN">China</option>
                            <option value="CO">Colombia</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="DK">Denmark</option>
                            <option value="FI">Finland</option>
                            <option value="FR">France</option>
                            <option value="DE">Germany</option>
                            <option value="GR">Greece</option>
                            <option value="HK">Hong Kong</option>
                            <option value="HU">Hungary</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IE">Ireland</option>
                            <option value="IL">Israel</option>
                            <option value="IT">Italy</option>
                            <option value="JP">Japan</option>
                            <option value="KR">Korea</option>
                            <option value="KW">Kuwait</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MY">Malaysia</option>
                            <option value="MX">Mexico</option>
                            <option value="NL">Netherlands</option>
                            <option value="NZ">New Zealand</option>
                            <option value="NO">Norway</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PE">Peru</option>
                            <option value="PH">Philippines</option>
                            <option value="PL">Poland</option>
                            <option value="PT">Portugal</option>
                            <option value="QA">Qatar</option>
                            <option value="RO">Romania</option>
                            <option value="RU">Russia</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SG">Singapore</option>
                            <option value="ZA">South Africa</option>
                            <option value="ES">Spain</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="TW">Taiwan</option>
                            <option value="TH">Thailand</option>
                            <option value="TR">Turkey</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="VE">Venezuela</option>
                            <option value="VN">Vietnam</option>
                            </select>
                            <label>Country name</label></div>
                            
                            <div class="col-md-4"><label> </label>
                            <input class="submit button" name="search" type="submit" value="Search Jobs" /></div>
                        </div>
                    </div>
                </form>
            </div>'; ?>



<!-- mfunc intellipaat_set_post_views($post->ID); --><!-- /mfunc -->
<section id="title" class="clear clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php vibe_breadcrumbs(); ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
        	<div class="col-md-3 col-sm-3">
                <div class="sidebar">
                	<?php 
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
							the_post_thumbnail();
						} 
					?>
                    <?php  	get_sidebar('left'); ?>
                </div>
            </div>
    		<div class="col-md-6 col-sm-6">
            
            	<?php ob_start(); ?> 
                    <div class="post-navigation page-navigation clear clearfix">           	
                        <div class="pull-left text-left"><?php previous_post_link('%link', '&laquo;  Previous'); ?></div>
                        <div class="pull-right text-right"><?php next_post_link('%link', 'Next &raquo;'); ?></div>
                    </div>
                <?php $next_prev_links = ob_get_contents(); ?>
                <?php ob_end_clean(); ?>
                        
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>
                
                    <div <?php post_class('content'); ?>>
                    
                    	<?php ob_start(); ?>
                    
                    	[tabs] [tab title="Career in <?php echo $text; ?>"]
                           
                        <div class="pagetitle">

<?php 
$newtitle=get_post_custom_values("add_h1_page_title", get_the_ID());

if(isset($newtitle[0]) && $newtitle[0] !='')
{
?>
<div class="title"><?php echo $newtitle[0]; ?> </div>  <?php
}else{
?> <h1><?php the_title(); ?> </h1>  
<?php
} 

$sub_heading_with_h1=get_post_custom_values("sub_heading_with_h1_tag", get_the_ID());
if(isset($sub_heading_with_h1[0]) && $sub_heading_with_h1[0] !='')
{
?>
<div class="small_desc_heading"><?php echo $sub_heading_with_h1[0]; ?> </div>  <?php
} 
                         	
								$short_description = get_field( 'intellipaat_short_description'); 
								if( $short_description )
									echo '<p>'. $short_description.'</p>';
							?>
                        </div>
                        <?php echo $next_prev_links; ?>
                        
                          <?php 											
                                $video_id= get_post_meta($post->ID, 'intellipaat-videothumb',true);
                                
                                if(isset($video_id) && !empty($video_id)){
                                    echo '<div class="featured-video">'.do_shortcode('[videothumb class="col-md-13" id="'.$video_id.'" alt="'.get_the_title().' Video"]').'</div>';
                                }
                            ?> 
                        
                        <?php   the_content();    ?>
                        
                        [/tab] [tab title="<?php echo $count_text; ?>"]
                        	<?php echo $form ;?>
							<?php if($total_records){            
                                        echo '<h1>Found total '.$total_records.' jobs</h1>';                                    
                                        foreach($rss->channel->item as $item)
                                        {
                                            echo '<div class="job-list">
                                            <h3 style="left: 0px;"><a href="'.$item->link.'" target="_blank" rel="nofollow">'.$item->source.'</a></h3>
                                            <p><span class="company-name">'.$item->title.'</span></p>
                                            <div>'.$item->description.'</div>
                                            <ul class="job-footer">
                                            <li class="job-days">'.time_elapsed_string(strtotime($item->pubDate)).'</li>
                                            <li class="job-source">Job Source : Jobsindia.com</li>
                                            </ul>
                                            </div>';
                                        }
                                    }else{
                                        echo "NO Jobs found"; 
                                    }
                                ?>                        
                        [/tab]
                        [/tabs]
                        
                        <?php $content = ob_get_contents(); ?>
                        <?php ob_end_clean(); ?>
                        
                        <?php echo do_shortcode( $content ); ?>
                        
                     	<?php echo $next_prev_links; ?>
						<?php comments_template();    ?>  
                    </div>
                    
                <?php
                
                endwhile;
                endif;
                ?>
                
                
            </div>
            <div class="col-md-3 col-sm-3">
            	<div class="sidebar">
                    <?php get_sidebar('right'); ?>
                </div>
            </div>  
        </div>
    </div>
</section>

<?php
get_footer( 'buddypress' );
?>
