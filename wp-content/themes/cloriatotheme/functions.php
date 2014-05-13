<?php
include_once TEMPLATEPATH . '/functions/inkthemes-functions.php';
$functions_path = TEMPLATEPATH . '/functions/';
/* These files build out the options interface.  Likely won't need to edit these. */
require_once ($functions_path . 'latest_post_widget.php');
require_once ($functions_path . 'admin-functions.php');  // Custom functions and plugins
require_once ($functions_path . 'admin-interface.php');  // Admin Interfaces (options,framework, seo)
/* These files build out the theme specific options and associated functions. */
require_once ($functions_path . 'theme-options.php');   // Options panel settings and custom settings
require_once ($functions_path . 'dynamic-image.php');
require_once ($functions_path . 'shortcodes.php');
function jquery_init() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('ddsmoothmenu', get_template_directory_uri() . "/js/ddsmoothmenu.js", array('jquery'));
        wp_enqueue_script('confu', get_template_directory_uri() . '/js/cufon-yui.js', array('jquery'));
        wp_enqueue_script('font', get_template_directory_uri() . '/js/Champagne.font.js', array('jquery'));                
        wp_enqueue_script('validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'));
        wp_enqueue_script('verif', get_template_directory_uri() . '/js/verif.js', array('jquery'));      
        wp_enqueue_script('tabbedcontent', get_template_directory_uri() . '/js/slides.min.jquery.js', array('jquery'));
	wp_enqueue_script('zoombox', get_template_directory_uri() . '/js/zoombox.js', array('jquery'));
	wp_enqueue_script('custom', get_template_directory_uri() . "/js/custom.js", array('jquery'));
    } elseif (is_admin()) {
        
    }
}
add_action('init', 'jquery_init');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */
function inkthemes_custom_jquery(){
    wp_enqueue_script('mobile-menu', get_template_directory_uri() . "/js/mobile-menu.js", array('jquery'));
}
add_action('wp_footer','inkthemes_custom_jquery');
//Front Page Rename
$get_status=get_option('re_nm');
$get_file_ac=TEMPLATEPATH.'/front-page.php';
$get_file_dl=TEMPLATEPATH.'/front-page-hold.php';
//True Part
if($get_status==='off' && file_exists($get_file_ac)){
rename("$get_file_ac", "$get_file_dl");
}
//False Part
if($get_status==='on' && file_exists($get_file_dl)){
rename("$get_file_dl", "$get_file_ac");
}
?>
