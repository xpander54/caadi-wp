/*--------DDsmoothmenu Initialization--------*/
jQuery(document).ready(function() { 
ddsmoothmenu.init({
	mainmenuid: "menu", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
});
//Font replace
Cufon.replace('h1')('h2')('h3')('h4')('h5')('h6');
//Zoombox
  jQuery(function() { 
    jQuery('a.zoombox').zoombox();
});
});
// JavaScript Document
// JavaScript Document
jQuery(window).load(function(){
jQuery('#slides').slides({
	autoHeight: false,
	effect: 'slide, fade',
	container: 'slides_container',
	play: 6000,
	slideSpeed: 600,
	fadeSpeed: 350,
	generateNextPrev: true,
	generatePagination: true,
	crossfade: true,
	hoverPause: true,
	"pause":2500,
		"animationStart":function(){
			jQuery('.caption').animate({
				"bottom":-200
			},100);
		},
		"animationComplete": function(current){
			jQuery('.caption').animate({
				"bottom":30
			},400);
			if (window.console && console.log) {
				console.log(current);
			};
		}
});
 jQuery('.caption').animate({
        "bottom":30
    } , 400 );
	jQuery( '#slides #slider_pag' ).wrap( '<div id="slider_nav" />' );
	jQuery( '#slides .pagination' ).wrap( '<div id="slider_pag" />' );
	
});
//Fade images
 jQuery(document).ready(function(){
    jQuery(".feature_inner img,.sidebar .recent_post li img").hover(function() {
      jQuery(this).stop().animate({opacity: "0.5"}, '500');
    },
    function() {
      jQuery(this).stop().animate({opacity: "1"}, '500');
    });
  });