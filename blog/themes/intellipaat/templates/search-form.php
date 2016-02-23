<?php global $post; $obj = get_post_type_object( $post->post_type );?>
<div class="posttype-search">
    <form role="search" method="get" id="search-form" action="<?php echo home_url( '/' ); ?>">
        <div class="form-field">
            <input type="text" value="<?php the_search_query(); ?>" name="s" class="input-text" placeholder="<?php _e('Search For '.$obj->labels->singular_name.'...','intellipaat'); ?>" />
            <input type="hidden" value="<?php echo $post->post_type; ?>" name="post_type" />                                      
            <button type="submit" role="submit"><i class="icon-search-2"></i></button>
        </div>
	</form>
</div>