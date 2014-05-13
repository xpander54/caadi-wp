<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * 
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)
 },i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47446508-1', 'caadi.mx');
  ga('send', 'pageview');

</script>


<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>
<?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'cloriato' ), max( $paged, $page ) );

	?>
</title>
<?php
if(is_front_page()){?>
<?php if(get_option('inkthemes_keyword')!=''){ ?>
<meta name="keywords" content="<?php echo stripslashes(get_option('inkthemes_keyword')); ?>" />
<?php } else{}?>
<?php if(get_option('inkthemes_description')!=''){ ?>
<meta name="description" content="<?php echo stripslashes(get_option('inkthemes_description')); ?>" />
<?php } else{}?>
<?php if(get_option('inkthemes_author')!=''){ ?>
<meta name="author" content="<?php echo stripslashes(get_option('inkthemes_author')); ?>" />
<?php } else{}?>
<?php }?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php if((get_option('inkthemes_altstylesheet')!='') && (get_option('inkthemes_altstylesheet')!='blue')){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/colors/<?php echo get_option('inkthemes_altstylesheet'); ?>.css" />
<?php } else{}?>
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<!--[if gte IE 9]>
	<script type="text/javascript">
		Cufon.set('engine', 'canvas');
	</script>
<![endif]-->
<!--[if IE 7]>
<style type="text/css">
.one_third {margin-right:4%;}
.two_third {margin-right:4%;}
</style>
<![endif]-->

</head>
<body  <?php body_class(); ?> background="<?php if ( get_option('inkthemes_bodybg') !='' ) { echo get_option('inkthemes_bodybg'); } else {?><?php echo get_template_directory_uri(); ?>/images/bodybg.png<?php }?>" >
<div class="top_cornor"></div>
<div class="body-content">
<!--Start Container-->
<div class="container_24">
<!--Start Header Wrapper-->
<div class="grid_24 header_wrapper">
  <!--Start Header-->
  <div class="header">
    <div class="grid_12 alpha">
      <div class="logo"> <a href="<?php echo home_url(); ?>"><img src="<?php if ( get_option('inkthemes_logo') !='' ) {?><?php echo get_option('inkthemes_logo'); ?><?php } else {?><?php echo get_template_directory_uri() ; ?>/images/logo.png<?php }?>" alt="<?php bloginfo('name'); ?>" /></a></div>
    </div>
    <div class="grid_6">
      <div class="top_right_bar">
        
      </div>
    </div>
	<div class="grid_6 omega">
      <div class="top_right_bar">
<a href="http://www.facebook.com/CAADIGITAL"><img src="http://www.caadi.mx/wp-content/uploads/2014/01/facebook.png" alt="Facebook CAADI" width="45" height="51" class="alignleft size-full wp-image-187" /></a>
<a href="http://twitter.com/caadigital"><img src="http://www.caadi.mx/wp-content/uploads/2014/01/twitter.png" alt="Twitter CAADI" width="45" height="51" class="alignleft size-full wp-image-188" /></a>
	  </div>
	  </div>
    <div class="clear"></div>
    <!--Start Menu wrapper-->
    <div class="menu_wrapper">
      <!--Start menu-div-->
     <div id="MainNav">
                        <a href="#" class="mobile_nav closed">Pages Navigation Menu<span></span></a>
						<?php inkthemes_nav(); ?>  <div class="clear"></div>                     
                    </div>
      <!--End menu-div-->
    </div>
    <!--End Menu wrapper-->
	
  </div>
  <!--End Header-->
</div>
<!--End Header Wrapper-->
<div class="clear"></div>
