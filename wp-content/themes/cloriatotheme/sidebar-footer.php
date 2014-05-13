<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 */
?>
<div class="footer_widget">
  <div class="grid_8 alpha">
    <div class="widget_inner">
      <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
      <?php dynamic_sidebar('first-footer-widget-area'); ?>
      <?php else : ?>
      <h3>Setting Up Footer</h3>
      <p>Footer is widgetized. To setup the footer, drag the required Widgets in Appearance -> Widgets Tab in the First, Second or Third Footer Widget Areas.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="grid_8">
    <div class="widget_inner lebo">
      <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
      <?php dynamic_sidebar('second-footer-widget-area'); ?>
      <?php else: ?>
        <h3>Organization Details</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dictum, neq ue ut imperdiet pellentesque.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="grid_8 omega">
    <div class="widget_inner lebo last">
      <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
      <?php dynamic_sidebar('third-footer-widget-area'); ?>
      <?php else: ?>
      <h3>Contact Information</h3>
      <fieldset>
      Contact: +91-9926465653 <br/>
      Email: admin@inkthemes.com<br/>
      <a href="#">www.inkthemes.com</a>
      </fieldset>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="clear"></div>
