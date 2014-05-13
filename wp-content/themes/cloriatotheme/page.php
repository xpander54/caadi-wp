<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="grid_24 content_wrapper">
  <div class="grid_16 alpha">
    <!--Start Content-->
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="content">
	<h1><?php the_title(); ?></h1>
      <?php if (function_exists('inkthemes_breadcrumbs')) inkthemes_breadcrumbs(); ?>
      <?php the_content(); ?>
	  <div class="clear"></div>
      <!--Start Comment box-->
      <h3>Comments &amp; Responses</h3>
      <?php comments_template(); ?>
      <!--End comment Section-->
    </div>
    <!--End Content-->
    <?php endwhile;?>
  </div>
  <!--Start Sidebar-->
  <?php get_sidebar(); ?>
  <!--End Sidebar-->
</div>
<!--End Content Wrapper-->
<div class="clear"></div>
</div>
<!--End Container-->
<?php get_footer(); ?>
