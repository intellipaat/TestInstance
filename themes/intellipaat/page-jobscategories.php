<?php
/**
 * Template Name: Jobs Categories
 */

get_header();
$args = array('taxonomy'=>'jobs-category');
$tutorialcats = get_categories( $args );
//print_r($tutorialcats);
?>
<div class="container bigdata-heading-main tutorial_main">
    <div class="row" > 
    <div class="col-md-12 bg-job">
    <br /><br /><br /><br /><br />
    <div class="links"><a href="/interview-questions/">INTERVIEW QUESTIONS</a> &nbsp; <a href="/tutorials/">TUTORIALS</a></div>
    </div>
    <?php if(count($tutorialcats)) { $i=0;?>
		<div class="masonry-container">
		<?php foreach($tutorialcats as $tutorialcat) { $i++; $first = explode("-",$tutorialcat->name); ?>
		<div class="col-md-3"><div class="inner"><h4><?php echo ucwords($tutorialcat->name); ?></h4>
        <?php 
		$tutorials = get_posts(array(
					  'post_type' => 'jobs',
					  'order' => 'ASC',
					  'numberposts' => -1,
					  'post_parent' => 0,
					  'tax_query' => array(
						array(
						  'taxonomy' => 'jobs-category',
						  'field' => 'id',
						  'terms' => $tutorialcat->term_id, // Where term_id of Term 1 is "1".
						  'include_children' => false
						)
					  )
					));
		if(count($tutorials)){ echo '<ul>';
		foreach($tutorials as $tutorial){ echo '<a href="'.get_permalink($tutorial->ID).'"><li>&bull; '.$tutorial->post_title.'</li></a>'; } ?>
        </ul>
        <?php } ?></div>
        </div>
	<?php if($i == 4) { $i=0; echo '</div><div class="col-md-12">'; } } ?>
    </div>
	 <?php } ?>
    </div>
    <br /><br />
</div>
<style type="text/css">
.col-md-3 { width: 24.9%; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.js"></script>
<script>
jQuery('.masonry-container').masonry({
  // options
  itemSelector: '.col-md-3'
});
</script>
<?php
get_footer();
?>