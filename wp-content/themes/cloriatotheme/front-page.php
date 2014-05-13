<?php
/*
/**
 * The main front page file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * 
 */
?>
<?php get_header(); ?>
<!--Start Slider Wrapper-->
<div class="grid_24 slider_wrapper">
  <!--Start Slider-->
  <div id="slides">
    <div class="slide slides_container"  >
      <!--Start Slider-->
     <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring1 = get_option('inkthemes_slideimage1');
            $value_img = array('.jpg', '.png','.jpeg','.gif','.bmp','.tiff','.tif');
            $check_img_ofset = 0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring1))
             {
            $check_img_ofset = 1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset == 0 && get_option('inkthemes_slideimage1') !='') { ?>
            <div><?php echo get_option('inkthemes_slideimage1'); ?></div>
       <?php } else { ?>     
      <div>       
        <?php if ( get_option('inkthemes_slideimage1') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink1'); ?>"><img  src="<?php echo get_option('inkthemes_slideimage1'); ?>" alt=""/></a>
        <?php }  else {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink1'); ?>"><img  src="<?php echo get_template_directory_uri(); ?>/images/img-1.jpg" alt=""/></a>
        <?php } ?>
        <div class="caption conference">
          <?php if ( get_option('inkthemes_slideheading1') !='' ) {  ?>
            <h2><a href="<?php if ( get_option('inkthemes_slidelink1') !='' ) { echo get_option('inkthemes_slidelink1'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading1')); ?></a></h2>
          <?php }  else {  ?>
          <h2><a href="#">4th Annual Seattle Conference</a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_slidedescription1') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription1')); ?></p>
          <?php }  else {  ?>
          <p>Every year, we address several aspects of the Bioeconomy, including biofuel, biotechnology, and green chemistry. The impact of biofuels on greenhouse gases is subject to debate.</p>
          <?php } ?>
        </div>
      </div>
            <?php } ?>
      <!--End Slider-->
       <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring2 = get_option('inkthemes_slideimage2');
            $check_img_ofset=0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring2))
             {
            $check_img_ofset=1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset==0 && get_option('inkthemes_slideimage2') !='') { ?>
            <div><a href="#"><?php echo get_option('inkthemes_slideimage2'); ?></a></div>
       <?php } else { ?>  
      <!--Start Slider-->
      <?php if ( get_option('inkthemes_slideimage2') !='' ) {  ?>
      <div>
        <?php if ( get_option('inkthemes_slideimage2') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink2'); ?>"><img src="<?php echo get_option('inkthemes_slideimage2'); ?>" alt=""/></a>
        <?php }  else { } ?>
        <div class="caption presentation">
          <?php if ( get_option('inkthemes_slideheading2') !='' ) {  ?>
          <h2><a href="<?php if ( get_option('inkthemes_slidelink2') !='' ) {  echo get_option('inkthemes_slidelink2'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading2')); ?></a></h2>
          <?php }  else {} ?>
          <?php if ( get_option('inkthemes_slidedescription2') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription2')); ?></p>
          <?php } else { } ?>
        </div>
      </div>
      <!--End Slider-->
      <?php }} ?>
      <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring3 = get_option('inkthemes_slideimage3');
             $check_img_ofset=0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring3))
             {
            $check_img_ofset=1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset==0 && get_option('inkthemes_slideimage3') !='') { ?>
            <div><a href="<?php echo get_option('inkthemes_slidelink3'); ?>"><?php echo get_option('inkthemes_slideimage3'); ?></a></div>
       <?php } else { ?>  
      <!--Start Slider-->
       <?php if ( get_option('inkthemes_slideimage3') !='' ) {  ?>
      <div>
        <?php if ( get_option('inkthemes_slideimage3') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink3'); ?>"><img  src="<?php echo get_option('inkthemes_slideimage3'); ?>" alt=""/></a>
        <?php } ?>
        <div class="caption speaker">
          <?php if ( get_option('inkthemes_slideheading3') !='' ) {  ?>
          <h2><a href="<?php if ( get_option('inkthemes_slidelink3') !='' ) { echo get_option('inkthemes_slidelink3'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading3')); ?></a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_slidedescription3') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription3')); ?></p>
          <?php } ?>
        </div>
      </div>
      <!--End Slider-->
      <?php }} ?>
      <!--Start Slider-->
      <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring4 = get_option('inkthemes_slideimage4');
            $check_img_ofset=0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring4))
             {
            $check_img_ofset=1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset==0 && get_option('inkthemes_slideimage4') !='') { ?>
            <div><a href="<?php echo get_option('inkthemes_slidelink4'); ?>"><?php echo get_option('inkthemes_slideimage4'); ?></a></div>
       <?php } else { ?>  
      <?php if ( get_option('inkthemes_slideimage4') !='' ) {  ?>
      <div>
        <?php if ( get_option('inkthemes_slideimage4') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink4'); ?>"><img  src="<?php echo get_option('inkthemes_slideimage4'); ?>" alt=""/></a>
        <?php } ?>
        <div class="caption speaker">
          <?php if ( get_option('inkthemes_slideheading4') !='' ) {  ?>
          <h2> <a href="<?php if ( get_option('inkthemes_slidelink4') !='' ) { echo get_option('inkthemes_slidelink4'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading4')); ?></a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_slidedescription4') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription4')); ?></p>
          <?php } ?>
        </div>
      </div>
      <?php }} ?>
      <!--End Slider-->
      
      
      
      <!--Start Slider-->
      <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring5 = get_option('inkthemes_slideimage5');
            $check_img_ofset=0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring5))
             {
            $check_img_ofset=1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset==0 && get_option('inkthemes_slideimage5') !='') { ?>
            <div><a href="<?php echo get_option('inkthemes_slidelink5'); ?>"><?php echo get_option('inkthemes_slideimage5'); ?></a></div>
       <?php } else { ?>  
      <?php if ( get_option('inkthemes_slideimage5') !='' ) {  ?>
      <div>
        <?php if ( get_option('inkthemes_slideimage5') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink5'); ?>"><img  src="<?php echo get_option('inkthemes_slideimage5'); ?>" alt=""/></a>
        <?php } ?>
        <div class="caption speaker">
          <?php if ( get_option('inkthemes_slideheading5') !='' ) {  ?>
          <h2> <a href="<?php if ( get_option('inkthemes_slidelink5') !='' ) { echo get_option('inkthemes_slidelink5'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading5')); ?></a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_slidedescription5') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription5')); ?></p>
          <?php } ?>
        </div>
      </div>
      <?php }} ?>
      <!--End Slider-->
      
      
      <!--Start Slider-->
      <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring6 = get_option('inkthemes_slideimage6');
            $check_img_ofset=0;
             foreach($value_img as $get_value)
             {
             if (preg_match("/$get_value/", $mystring6))
             {
            $check_img_ofset=1;
             }
             }   
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
           ?>
      
      <?php if($check_img_ofset==0 && get_option('inkthemes_slideimage6') !='') { ?>
            <div><a href="<?php echo get_option('inkthemes_slidelink6'); ?>"><?php echo get_option('inkthemes_slideimage6'); ?></a></div>
       <?php } else { ?>  
      <?php if ( get_option('inkthemes_slideimage6') !='' ) {  ?>
      <div>
        <?php if ( get_option('inkthemes_slideimage6') !='' ) {  ?>
        <a href="<?php echo get_option('inkthemes_slidelink6'); ?>"><img  src="<?php echo get_option('inkthemes_slideimage6'); ?>" alt=""/></a>
        <?php } ?>
        <div class="caption speaker">
          <?php if ( get_option('inkthemes_slideheading6') !='' ) {  ?>
          <h2> <a href="<?php if ( get_option('inkthemes_slidelink6') !='' ) { echo get_option('inkthemes_slidelink6'); } ?>"><?php echo stripslashes(get_option('inkthemes_slideheading6')); ?></a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_slidedescription6') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_slidedescription6')); ?></p>
          <?php } ?>
        </div>
      </div>
      <?php }} ?>
      
      
      
      
      
      
      <!--End Slider-->
    </div>
  </div>
  <!--End Slider-->
</div>
<!--End Slider Wrapper-->
<div class="clear"></div>
<!--Start Home content wrapper-->
<div class="grid_24 home_content_wrapper">
  <!--Start home content-->
  <div class="home_content">
    <div class="home_text">
      <?php if ( get_option('inkthemes_mainheading') !='' ) {  ?>
      <h1><?php echo stripslashes(get_option('inkthemes_mainheading')); ?></h1>
      <?php } else {  ?>
      <h1><center>Welcome to Our Site. Set this Heading from Themes Option Panel.</center></h1>
      <?php } ?>
      <?php if ( get_option('inkthemes_heading_desc') !='' ) {  ?>
      <p><?php echo stripslashes(get_option('inkthemes_heading_desc')); ?></p>
      <?php } else { ?>
       <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dictum, neq ue ut imperdiet pellentesque, nulla tellus tempus magna, sed consectetur o rci metus a justo. Integer dictum, neque ut imperdiet pellentesque, nullat ellus tempus magna, sed consectetur orci metus a justo.</p>
       <?php } ?>
    </div>
  </div>
  <!--End home content-->
  <hr/>
  <div class="clear"></div>
  <!--Start Feature content-->
  <div class="feature_content">
    <?php
         $two_cols = get_option('two_cols');
         $cols2_on ="on";
         $off ="off";
         if ( $two_cols === $cols2_on ) {  ?>
    <div class="two_third feature_box">
      <div class="feature_inner">
        <?php if ( get_option('inkthemes_col_left_heading') !='' ) {  ?>
        <h2><a href="<?php if ( get_option('inkthemes_col_left_readmore') !='' ) { echo get_option('inkthemes_col_left_readmore'); } ?>"><?php echo stripslashes(get_option('inkthemes_col_left_heading')); ?></a></h2>
        <?php } else {  ?>
        <h2><a href="#">What is this place ?</a></h2>
        <?php } ?> 
        <?php if ( get_option('inkthemes_col_left_image') !='' ) {  ?>
        <img class="feature_image" src="<?php echo get_option('inkthemes_col_left_image'); ?>"/>
        <?php } else { ?>        
        <?php } ?>  
        <?php if ( get_option('inkthemes_col_left_desc') !='' ) {  ?>
        <p><?php echo stripslashes(get_option('inkthemes_col_left_desc')); ?></p>
        <?php } else {  ?>
			<?php echo do_shortcode('[contact-form-7 id="159" title="Contact form 1"]'); ?>
        <?php } ?>
        </div>
    </div>
    <div class="one_third last">
      <div class="feature_inner">
        <?php if ( get_option('inkthemes_col_right_heading') !='' ) {  ?>
        <h2><a href="<?php if ( get_option('inkthemes_col_right_readmore') !='' ) { echo get_option('inkthemes_col_right_readmore'); } ?>"><?php echo stripslashes(get_option('inkthemes_col_right_heading')); ?></a></h2>
        <?php } else {  ?>
        <h2><a href="#">Out Latest Project</a></h2>
        <?php } ?>     
        <?php if ( get_option('inkthemes_col_right_desc') !='' ) {  ?>
        <p><?php echo stripslashes(get_option('inkthemes_col_right_desc')); ?></p>
        <?php } else {  ?>
        			<?php echo do_shortcode('[contact-form-7 id="159" title="Contact form 1"]'); ?>

        <?php } ?>
         </div>
    </div>
    <?php } else { } ?> 
       <div class="clear"></div>
    <?php
         $three_cols = get_option('three_cols');
         $cols3_on ="on";        
         if ( $three_cols === $cols3_on ) {  ?>
    <div class="featured">
      <div class="one_third">
        <div class="feature_inner feature_inner_bottom">
          <?php if ( get_option('inkthemes_headline1') !='' ) {  ?>
          <h2><a href="<?php if ( get_option('inkthemes_link1') !='' ) { echo get_option('inkthemes_link1'); } ?>"><?php echo stripslashes(get_option('inkthemes_headline1')); ?></a></h2>
          <?php } else {  ?>
          <h2><a href="#">Our Products</a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_wimg1') !='' ) {  ?>
          <a href="<?php if ( get_option('inkthemes_link1') !='' ) { echo get_option('inkthemes_link1'); } ?>"><img class="feature-image" src="<?php echo get_option('inkthemes_wimg1'); ?>"/></a>
          <?php } else {  ?>
          <img class="feature-image" src="<?php echo get_template_directory_uri(); ?>/images/f-img1.jpg"/>
          <?php } ?>
          <?php if ( get_option('inkthemes_feature1') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_feature1')); ?></p>
          <?php } else {  ?>
          <p>sed consectetur orci metus a justo. Aliq uam ac congu e nunc. Mauris a tortor ut massa is a egestas tempus. </p>
          <?php } ?>
          <a href="<?php if ( get_option('inkthemes_link1') !='' ) { echo get_option('inkthemes_link1'); } ?>" class="read_more">read more</a> </div>
      </div>
      <div class="one_third">
        <div class="feature_inner feature_inner_bottom">
          <?php if ( get_option('inkthemes_headline2') !='' ) {  ?>
          <h2><a href="<?php if ( get_option('inkthemes_link2') !='' ) { echo get_option('inkthemes_link2'); } ?>"><?php echo stripslashes(get_option('inkthemes_headline2')); ?></a></h2>
          <?php } else {  ?>
          <h2><a href="#">Our Services</a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_fimg2') !='' ) {  ?>
          <a href="<?php if ( get_option('inkthemes_link2') !='' ) { echo get_option('inkthemes_link2'); } ?>"><img class="feature-image" src="<?php echo get_option('inkthemes_fimg2'); ?>"/></a>
          <?php } else {  ?>
          <img class="feature-image" src="<?php echo get_template_directory_uri(); ?>/images/f-img2.jpg"/>
          <?php } ?>
          <?php if ( get_option('inkthemes_feature2') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_feature2')); ?></p>
          <?php } else {  ?>
          <p>sed consectetur orci metus a justo. Aliq uam ac congu e nunc. Mauris a tortor ut massa is a egestas tempus. </p>
          <?php } ?>
          <a href="<?php if ( get_option('inkthemes_link2') !='' ) { echo get_option('inkthemes_link2'); } ?>" class="read_more">read more</a> </div>
      </div>
      <div class="one_third last">
        <div class="feature_inner feature_inner_bottom">
          <?php if ( get_option('inkthemes_headline3') !='' ) {  ?>
          <h2><a href="<?php if ( get_option('inkthemes_link3') !='' ) { echo get_option('inkthemes_link3'); } ?>"><?php echo stripslashes(get_option('inkthemes_headline3')); ?></a></h2>
          <?php } else {  ?>
          <h2><a href="#">Our Clients</a></h2>
          <?php } ?>
          <?php if ( get_option('inkthemes_fimg3') !='' ) {  ?>
          <a href="<?php if ( get_option('inkthemes_link3') !='' ) { echo get_option('inkthemes_link3'); } ?>"><img class="feature-image" src="<?php echo get_option('inkthemes_fimg3'); ?>"/></a>
          <?php } else {  ?>
          <img class="feature-image" src="<?php echo get_template_directory_uri(); ?>/images/f-img3.jpg"/>
          <?php } ?>
          <?php if ( get_option('inkthemes_feature3') !='' ) {  ?>
          <p><?php echo stripslashes(get_option('inkthemes_feature3')); ?></p>
          <?php } else {  ?>
          <p>sed consectetur orci metus a justo. Aliq uam ac congu e nunc. Mauris a tortor ut massa is a egestas tempus. </p>
          <?php } ?>
          <a href="<?php if ( get_option('inkthemes_link3') !='' ) { echo get_option('inkthemes_link3'); } ?>" class="read_more">read more</a> </div>
      </div>
    </div>
    <?php } else { } ?>
  </div>
  <!--End Feature content-->
</div>
<!--End home content wrapper-->
<div class="clear"></div>
</div>
<!--End Container-->
<?php get_footer(); ?>
