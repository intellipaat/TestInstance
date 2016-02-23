<?php
/**
 * Template Name: Tutorial Category
 */

get_header();
$args = array('post_type'=>'post', 'posts_per_page'=>-1, 'category'=>31);
//$posts = get_posts($args);
//print_r($posts);
if(isset($_GET['tutcat'])){
	$course_category = $_GET['tutcat'];
} else {
	$course_category = 'big-data';
}

/*$posts = get_posts(array(
  'post_type' => 'tutorial',
  'order' => 'ASC',
  'numberposts' => -1,
  'post_parent' => null,
  'tax_query' => array(
    array(
      'taxonomy' => 'tuts-category',
      'field' => 'id',
      'terms' => 568, // Where term_id of Term 1 is "1".
      'include_children' => false
    )
  )
));*/
$posts = get_posts(array(
  'post_type' => 'tutorial',
  'order' => 'ASC',
  'numberposts' => -1,
  'post_parent' => 0,
					  'tax_query' => array(
						array(
						  'taxonomy' => 'tuts-category',
						  'field' => 'slug',
						  'terms' => $course_category, // Where term_id of Term 1 is "1".
						  'include_children' => false
						)
					  )
));
//print_r($posts);
?>
<div class="container bigdata-heading">
    <div class="row" > 
    <div class="col-md-12 clearfix bg"><h1 style="padding-left:20px;"><?php echo strtoupper(str_replace("-"," ",$course_category)); ?></h1></div>
    <?php if(count($posts)) { $i=0;?>
		<div class="col-md-12 clearfix">
		<?php foreach($posts as $post) { $i++; $first = explode("-",$post->post_name); ?>
		<div class="col-md-3"><h4><?php echo '<a href="'.$post->guid.'">'.$post->post_title.'</a>'; ?></h4><p align="center"><a href="/interview-question/<?php echo $first[0]; ?>-interview-questions/">Interview Questions</a> | <a href="/jobs/<?php echo $first[0]; ?>-jobs/">Jobs</a></p></div>
	<?php if($i == 4) { $i=0; echo '</div><div class="col-md-12 clearfix">'; } } ?>
    </div>
	 <?php } ?>
    </div>
    <br /><br />
</div>
<?php
get_footer();
?>