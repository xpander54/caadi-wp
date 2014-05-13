<?php
$new_galleryname = trim($_POST['rename']);
$old_galleryname = trim($_POST['old_name']);
$acx_slideshow_misc_user_level = get_option('acx_slideshow_misc_user_level');
if($acx_slideshow_misc_user_level=="")
{
$acx_slideshow_misc_user_level = "manage_options";
}
$url = $_POST['url'];
// $url=substr($url , 0, -1);
if (current_user_can($acx_slideshow_misc_user_level)) 
{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
	foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
	{
		if($key==$new_galleryname)
		{
			$found="yes";
		}
	}
	if($found!="yes")
	{
	foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
	{
		
		if($key==$old_galleryname)
		{
			$acx_slideshow_imageupload_complete_data[$new_galleryname] = $acx_slideshow_imageupload_complete_data[$old_galleryname];
			unset($acx_slideshow_imageupload_complete_data[$old_galleryname]);
		}
	}
	update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	foreach ($acx_slideshow_gallery_data as $key => $value)
	{
		if($key==$old_galleryname)
		{
			$acx_slideshow_gallery_data[$new_galleryname] = $acx_slideshow_gallery_data[$old_galleryname];
			unset($acx_slideshow_gallery_data[$old_galleryname]);
		}
	}
	update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));

	$new_url = str_replace($old_galleryname, $new_galleryname, $url);
	//$new_url=substr($url , 0, -1);
	$new_url = str_replace("&rename_status=yes", "", $new_url);
	$new_url = str_replace("&rename_status=no", "", $new_url);
	$new_url = $new_url."&rename_status=yes";
	// echo $url."|";
	}
	else
	{
		//$new_url=substr($url , 0, -1);
		$new_url = str_replace("&rename_status=yes", "", $url);
		$new_url = str_replace("&rename_status=no", "", $new_url);
		$new_url = $new_url."&rename_status=no";		
	}
$adminurl =  admin_url();
$new_url = str_replace("/wp-admin/", "", $new_url);
$new_url = $adminurl.$new_url;
header('Location: '.$new_url);
}
?>