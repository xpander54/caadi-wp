<div id="acx_help_page">
<?php
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}
?>
<h2><?php _e('Simple Slideshow Manager - Wordpress Plugin - Help/Support','simple-slideshow-manager'); ?></h2>

<p><?php _e('Thank you for using Simple Slideshow Manager For Your Wordpress Slideshow Need.','simple-slideshow-manager'); ?></p>

<h3><a href="http://clients.acurax.com/link.php?id=13" target="_blank"><?php _e('Click here to open the FAQ and Help Page','simple-slideshow-manager'); ?></a></h3>
</div> <!-- acx_help_page -->
<hr/>
<?php
if($acx_slideshow_misc_hide_advert == "no")
{
acx_slideshow_comparison(1); 
} ?>
<br>
<p class="widefat" style="padding:8px;width:99%;">
Something Not Working Well? Have a Doubt? Have a Suggestion? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a> | Need a Custom Designed Theme For your Blog or Website? Need a Custom Header Image? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a>
</p>