<?php
/*
Template Name: Gallery Page
*/
?>
<?php get_header(); ?>
<div class="grid_24 content_wrapper">
<div class="fullwidth">
<?php //if (function_exists('inkthemes_breadcrumbs')) inkthemes_breadcrumbs(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<h1>
  <?php the_title(); ?>
</h1>
<?php endwhile;?>
<ul class="thumbnail">
<?php
        if ($wp_query->have_posts()) : while (have_posts()) : the_post();
                the_content();
                $attachment_args = array(
                    'post_type' => 'attachment',
                    'numberposts' => -1,
                    'post_status' => null,
                    'post_parent' => $post->ID,
                    'orderby' => 'menu_order ID'
                );
                $attachments = get_posts($attachment_args);
                if ($attachments) {
                    foreach ($attachments as $gallery_image) {
					
                       $attachment_img =  wp_get_attachment_url( $gallery_image->ID);
					   $img_source=inkthemes_image_resize($attachment_img, 215, 157);
				echo '<li><a alt="'.$gallery_image->post_title.'" href="'.$attachment_img.'" class="zoombox zgallery1">';				
                                echo  '<img src="'.$img_source[url].'" alt=""/>';
				echo '</a></li>';
				}
				}
                ?>
<?php endwhile; endif; ?>
</ul>
</div>
</div>
<!--End Content Wrapper-->
<div class="clear"></div>
</div>
<!--End Container-->
<?php get_footer(); ?>
