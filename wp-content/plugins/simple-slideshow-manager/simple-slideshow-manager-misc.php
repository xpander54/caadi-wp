<?php
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}
if($_GET['td'] == 'hide') 
{
update_option('acx_ssm_td', "hide");
?>
<style type='text/css'>
#acx_td
{
display:none;
}
</style>
<div class="error" style="background: none repeat scroll 0pt 0pt infobackground; border: 1px solid inactivecaption; padding: 5px;line-height:16px;">
<?php _e('Thanks again for using the plugin. we will not show the mesage again.','simple-slideshow-manager'); ?>
</div>
<?php
}
?>


<?php
if($_POST['acx_slideshow_misc_hidden']=="y")
{//form data send

$acx_slideshow_misc_acx_service_banners = $_POST['acx_slideshow_misc_acx_service_banners'];
update_option('acx_slideshow_misc_acx_service_banners', $acx_slideshow_misc_acx_service_banners);
$acx_slideshow_misc_hide_advert = $_POST['acx_slideshow_misc_hide_advert'];
update_option('acx_slideshow_misc_hide_advert', $acx_slideshow_misc_hide_advert);
$acx_slideshow_misc_user_level = $_POST['acx_slideshow_misc_user_level'];
update_option('acx_slideshow_misc_user_level', $acx_slideshow_misc_user_level);

?>
<div class="updated"><p><strong><?php _e('Simple Slideshow Manager Misc Settings Saved!.','simple-slideshow-manager'  ); ?></strong></p></div>
<?php
}
else
{
	$acx_slideshow_misc_acx_service_banners = get_option('acx_slideshow_misc_acx_service_banners');
	$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
	$acx_slideshow_misc_user_level = get_option('acx_slideshow_misc_user_level');
	
	// setting defaults
	
	if($acx_slideshow_misc_acx_service_banners=="")
	{
		$acx_slideshow_misc_acx_service_banners="yes";
	}
	if($acx_slideshow_misc_hide_advert=="")
	{
	 $acx_slideshow_misc_hide_advert ="no";
	}
}


?>
<div class="wrap">
<?php if ($acx_slideshow_misc_acx_service_banners != "no") { ?>
<p class="widefat" style="padding:8px;width:99%;height: 75px;">
<b>Acurax Services >> </b><br>
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ssm" target="_blank" id="wtd" style="background:url(<?php echo plugins_url('images/wtd.jpg', __FILE__);?>);"></a>

<a href="http://www.acurax.com/services/web-designing.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ssm" target="_blank" id="wd" style="background:url(<?php echo plugins_url('images/wd.jpg', __FILE__);?>);"></a>
<a href="http://www.acurax.com/social-media-marketing-optimization/social-profile-design.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ssm" target="_blank" id="spd" style="background:url(<?php echo plugins_url('images/spd.jpg', __FILE__);?>);"></a>
<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ssm" target="_blank" id="wrd" style="background:url(<?php echo plugins_url('images/wr.jpg', __FILE__);?>);"></a>
</p>
<?php } else { ?>
<p class="widefat" style="padding:8px;width:99%;">
<b><?php _e('Acurax Services >>','simple-slideshow-manager'); ?> </b>
<a href="http://www.acurax.com/services/blog-design.php" target="_blank"><?php _e('Wordpress Theme Design','simple-slideshow-manager'); ?></a> | 
<a href="http://www.acurax.com/services/web-designing.php" target="_blank"><?php _e('Website Design','simple-slideshow-manager'); ?></a> | 
<a href="http://www.acurax.com/social-media-marketing-optimization/social-profile-design.php" target="_blank"><?php _e('Social Profile Design','simple-slideshow-manager'); ?></a> | 
<a href="http://www.acurax.com/social-media-marketing-optimization/twitter-background-design.php" target="_blank"><?php _e('Twitter Background Design','simple-slideshow-manager'); ?></a> | 
<a href="http://www.acurax.com/social-media-marketing-optimization/facebook-page-design.php" target="_blank"><?php _e('Facebook Page Design','simple-slideshow-manager'); ?></a>
</p>
<?php } ?>
<?php if($acx_slideshow_misc_hide_advert == "no")
{ ?>
<?php } ?>
<?php
if($acx_slideshow_misc_hide_advert == "no")
{
?>
<div id="acx_ssm_premium">
<a style="margin: 8px 0px 0px 10px; float: left; font-size: 16px; font-weight: bold;" href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=highlight&utm_campaign=ssm" target="_blank">Fully Featured Plugin - Advanced Slideshow Manager</a>
<a style="margin: -14px 0px 0px 10px; float: left;" href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=highlight_yellow&utm_campaign=ssm" target="_blank"><img src="<?php echo plugins_url('images/yellow.png', __FILE__);?>"></a>
</div> <!-- acx_fsmi_premium -->
<?php } ?>
<?php echo "<h2>" . __( 'Simple Slideshow Manager Misc Settings', 'simple-slideshow-manager' ) . "</h2>"; ?>

<form name="acx_slideshow_misc_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="acx_slideshow_misc_hidden" value="y">

<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e('Acurax Service Banners:','simple-slideshow-manager' ); ?>
<select name="acx_slideshow_misc_acx_service_banners">
<option value="yes"<?php if ($acx_slideshow_misc_acx_service_banners == "yes") { echo 'selected="selected"'; } ?>><?php _e('Yes, Show Them','simple-slideshow-manager'); ?> </option>
<option value="no"<?php if ($acx_slideshow_misc_acx_service_banners == "no") { echo 'selected="selected"'; } ?>><?php _e('No, Hide Them','simple-slideshow-manager'); ?> </option>
</select>
<?php _e("Show Acurax Service Banners On Plugin Settings Page?" ); ?>
</p>
<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e('Who can Manage This:','advanced-slideshow-manager' ); ?>
<select name="acx_slideshow_misc_user_level">
<option value="manage_options"<?php if ($acx_slideshow_misc_user_level == "manage_options"||$acx_slideshow_misc_user_level=="") { echo 'selected="selected"'; } ?>><?php _e('Administrator','advanced-slideshow-manager'); ?> </option>
<option value="delete_others_pages"<?php if ($acx_slideshow_misc_user_level == "delete_others_pages") { echo 'selected="selected"'; } ?>><?php _e('Editor','advanced-slideshow-manager'); ?> </option>
<option value="delete_published_posts"<?php if ($acx_slideshow_misc_user_level == "delete_published_posts") { echo 'selected="selected"'; } ?>><?php _e('Author','advanced-slideshow-manager'); ?> </option>
</select>
<?php _e("Select The User Level Who Can Manage This Plugin" ); ?>
</p>
<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e("Hide Premium Version Adverts: " ); ?>
<select name="acx_slideshow_misc_hide_advert">
<option value="yes"<?php if ($acx_slideshow_misc_hide_advert == "yes") { echo 'selected="selected"'; } ?>><?php _e('Yes','simple-slideshow-manager'); ?> </option>
<option value="no"<?php if ($acx_slideshow_misc_hide_advert == "no") { echo 'selected="selected"'; } ?>><?php _e('No','simple-slideshow-manager'); ?> </option>
</select>
<?php _e("Would you like to hide the feature comparison advertisement of basic and premium version from plugin settings pages?" ); ?>
</p>

<p class="submit">
<input type="submit" name="Submit" class="button" value="<?php _e('Save Settings', 'simple-slideshow-manager' ) ?>" />
</p>

</form>
<p class="widefat" style="padding:8px;width:99%;">
<?php _e('Something Not Working Well? Have a Doubt? Have a Suggestion?','simple-slideshow-manager'); ?> - <a href="http://www.acurax.com/contact.php" target="_blank"><?php _e('Contact us now','simple-slideshow-manager'); ?></a><?php _e(' | Need a Custom Designed Theme For your Blog or Website? Need a Custom Header Image? - ','simple-slideshow-manager'); ?><a href="http://www.acurax.com/contact.php" target="_blank"><?php _e('Contact us now','simple-slideshow-manager'); ?></a>
</p>

<hr/>
<?php
if($acx_slideshow_misc_hide_advert == "no")
{
//acx_slideshow_comparison(1); 
} ?>
</div>