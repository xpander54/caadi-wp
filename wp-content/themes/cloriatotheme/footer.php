<!--Start Footer Bg-->
  <div class="footer-content">
  <div class="footer_wrap">
    <!--Start Container-->
    <div class="container_24">
      <!--Start Footer wrapper-->
      <div class="grid_24 footer_wrapper">
        <!--Start Footer-->
        <div class="footer">
          <!--Start Footer widget-->
          <?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>
          <!--End Footer Widget-->
        </div>
        <!--End Footer-->
      </div>
	  <div class="clear"></div>
      <!--End Footer wrapper-->
    </div>
	<div class="clear"></div>
    <!--End Container-->
    <div class="footer-glow"></div>
    </div>
  </div>
</div>
<div class="footer-space"></div>
<?php wp_footer(); ?>
</body></html>