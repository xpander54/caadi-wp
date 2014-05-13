<!-- Start the Loop. -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<!--Start Post-->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <h1 class="post_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
    <?php the_title(); ?>
    </a></h1>
<!--   <ul class="post_meta">
    <li class="posted_by"><span>By</span>&nbsp;
      <?php the_author_posts_link(); ?>
    </li>
    <li class="post_date">&nbsp;
      <?php// the_time('M-j-Y') ?>
    </li>
    <li class="post_category">&nbsp;
      <?php the_category(', '); ?>
    </li>
    <li class="post_comment">&nbsp;
      <?php// comments_popup_link('0 Comments.', '1 Comment.', '% Comments.'); ?>
    </li>
  </ul> -->
  <hr/>
  <div class="post_content">
    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                         <?php inkthemes_get_thumbnail(250, 170); ?>
                    <?php } else { ?>
                        <?php inkthemes_get_image(250, 170); ?> 
                        <?php
                    }
                    ?>	
    <?php the_excerpt(); ?>
    <p>
      <?php the_tags(); ?>
    </p>
    <!-- <a class="read_more" href="<?php the_permalink(); ?>"></a> </div> -->
    <a href="<?php the_permalink(); ?>">ver más</a> </div>
</div>
<!--End Post-->
<?php endwhile; else: ?>
<!--End Loop-->
<div class="post">
 <p>
 <!-- <?php //_e('Sorry, no posts matched your criteria.', 'cloriato'); ?><p> -->
    Sin resultados. 
  </p>
</div>
<?php endif; ?>
