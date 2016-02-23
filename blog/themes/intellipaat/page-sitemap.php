<?php
get_header();

$taxonomy = 'course-cat';

if ( have_posts() ) : while ( have_posts() ) : the_post();

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(vibe_validate($title)){
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(vibe_validate($breadcrumbs))
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <div class="container">
        <div class="row">
        
            <div class="col-md-9 col-sm-8">
                <div class="content sitemap">
                    <h2 style="margin-top:0; font-weight:800;">All courses sorted by categories <i class="icon-curved-arrow"> </i></h2>
                    <?php

						$cats = get_terms($taxonomy , array('orderby' => 'title'));		
						
							
						foreach($cats as $cat){
							echo '<div class="cat-list" >';
							$term = get_term( $cat, $taxonomy );
							echo '<h3 class="heading cat-'.$term->slug.'"><i class="icon-forward-2"> </i>  <span>All courses in <strong>'.$term->name.'</strong></span></h3>';
							$courses = get_posts(array(
											'posts_per_page' => -1,
											'post_type' => 'course',
											$taxonomy => $term->slug,
											'orderby' => 'menu_order',
											'order' => 'ASC'
										));									
								
//							echo '<div class="col-md-12">';
							echo '<ul class="row">';
								foreach ( $courses as $course ) : 
			
									echo '<li class="col-md-4 col-sm-6 course"><i class="icon-link"> </i> &nbsp;
											<a href="'.get_permalink($course->ID).'">'.get_the_title($course->ID).'</a>
										</li>'; 
									$count++;
			
								endforeach; 
							echo '</ul>';
//							echo '</div>';
							echo '</div>';
			
							wp_reset_postdata();
						}
					?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 sidebar">                
                <div class="pricing categories widget">
                    <h3 class="heading" style="margin-top:0;">Course categories</h3>
                    <ul>
                    <?php 
						
						foreach($cats as $cat){
							$term = get_term( $cat, $taxonomy );
							echo '<li  data-submenu-id="submenu-'.$term->slug.'"><i class="icon-forward-2 arr"> </i>  &nbsp; <a class="main-cat menu-item" href="'.get_term_link( $term, $taxonomy ).'" title="'.$term->name.'" ><span> '.$term->name.'</span></a></li>';
						}
						
						?>
                    </ul>
                </div>
                
                <div class="pricing pages widget">
                    <h3 class="heading" style="margin-top:0;">Pages</h3>
                    <ul>
                    <?php 
						$args = array(
							'sort_order' => 'ASC',
							'sort_column' => 'post_title',
							'hierarchical' => 1,
							'exclude' => vibe_get_option('excluded_pages'),
							'include' => '',
							'meta_key' => '',
							'meta_value' => '',
							'child_of' => 0,
							'parent' => -1,
							'exclude_tree' => '',
							'number' => '',
							'offset' => 0,
							'post_type' => 'page',
							'post_status' => 'publish'
						); 
						$pages = get_pages($args); 
						
						foreach($pages as $page){
							echo '<li><i class="icon-burst"> </i> &nbsp;<a id="page-'.$page->ID.'" href="'. get_page_link( $page->ID ).'">'.$page->post_title.'</a></li>';
						}
						
						?>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</section>
<?php
endwhile;
endif;
?>
</div>

<?php
get_footer();
?>