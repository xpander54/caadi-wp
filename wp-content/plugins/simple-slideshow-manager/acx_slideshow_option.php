<?php 	
//************ create array for save all image data **************	
if(get_option('acx_slideshow_imageupload_complete_data') == "")
{	 
	$acx_slideshow_imageupload_complete_data = array(
													 "default" => array()	
													);
	update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
}		
//**************** create array for save all image data ***************
	else
	{
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}
//***************Form data sent starts ****************
	if($_POST['my_plugin_hidden'] == 'Y') 
	{	
	if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
	if($acx_slideshow_imageupload_complete_data=="")
	{
		$acx_slideshow_imageupload_complete_data=array();
	}
		$acx_slideshow_galleryname = trim($_POST['acx_slideshow_galleryname']);
		
		
		if (preg_match('/[\'^£$%`!&*()}{@#~?><>,|=_+¬-]/', $acx_slideshow_galleryname))
		{
			// one or more of the 'special characters' found in $string
						$acx_slideshow_galleryname = "";
		}
		
		if($acx_slideshow_galleryname=="")
		{
echo "<script type = text/javascript>";
echo "alert('".__('Please enter a valid name','simple-slideshow-manager')."');";
echo "</script>";
		}
		foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
		{
			if($acx_slideshow_galleryname == $key)
			{
echo "<script type = text/javascript>";
echo "alert('".__('Gallery Name Already Exist, Add Another One','simple-slideshow-manager')."');";
echo "</script>";
			}
		}
		if($acx_slideshow_galleryname!="")
		{
			$acx_slideshow_galleryname = trim($acx_slideshow_galleryname);
			if (!array_key_exists($acx_slideshow_galleryname,$acx_slideshow_imageupload_complete_data))
			{
				$number_of_galleries = count($acx_slideshow_imageupload_complete_data);
				if($number_of_galleries == "") { $number_of_galleries = 0; }
				$acx_slideshow_imageupload_complete_data[$acx_slideshow_galleryname] = array();
echo "<script type = text/javascript>";
echo "alert('".__('Gallery','simple-slideshow-manager') ." ".__($acx_slideshow_galleryname,'simple-slideshow-manager')." ". __('Added Successfully','simple-slideshow-manager')."');";
echo "</script>";
			}
		}
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
?>
<?php
	}
//**************** Form data sent ends *******************
//**************** Normal page display starts *************
	else
	{
	
		$acx_slideshow_galleryname = array();
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
		//---------------- check version and update type
		$acx_slideshow_version = get_option('acx_slideshow_version');
		if($acx_slideshow_version < '1.2.2') // Current Version
		{
			$acx_slideshow_version = '1.2.2'; // Current Version
			foreach($acx_slideshow_imageupload_complete_data as $key=>$values)
			{
					$i=0;
				foreach($values as $value)
				{
					if($value['type']=="")
					{
						$value['type'] = "image";
						$acx_slideshow_imageupload_complete_data[$key][$i] = $value;
					}
					$i=$i+1;
				}
			}
			update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
			update_option('acx_slideshow_version',$acx_slideshow_version);
		}
//---------------- check version and update type
	}
//*************** Normal page display ends *****************
//*************** Delete Gallery Option *******************	
    if (isset($_GET['del'])) 
	{
       $acx_del_gallery_name = $_GET['del'];
		if($acx_del_gallery_name != "")
		{
		$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
			unset($acx_slideshow_imageupload_complete_data[$acx_del_gallery_name]);
			unset($acx_slideshow_gallery_data[$acx_del_gallery_name]);
			update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
			update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));
		}
    }
//************* Delete Gallery Option ******************		
?>
<?php
//####################################### MAIN PAGE DISPLAY ################################
if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
if($acx_slideshow_imageupload_complete_data=="")
{
	$acx_slideshow_imageupload_complete_data=array();
}
?>
<div class="wrap">
<?php
echo "<h2>" . __( 'Simple Slideshow Manager Settings', 'simple-slideshow-manager' ) . "</h2>"; 
?>	<form name="acurax_si_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="my_plugin_hidden" value="Y">
		<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e('New Gallery Name','simple-slideshow-manager' ); ?>
			<input type="text" name="acx_slideshow_galleryname" value="" size="60">
			<input type="submit" name="acx_slideshow_addgallery"  value="<?php _e('Add Gallery', 'simple-slideshow-manager') ?>" id="acx_ex_add_gallery" class="button"/>
		</p>
        <div class="widefat" style="padding:8px;width:99%;margin-top:8px;">
		<h4 style="padding:5px;margin:3px;"><?php _e('Existing Galleries','simple-slideshow-manager'); ?></h4>
<?php			
		echo "<ul id='acx_ex_gall' style='display:inline-block;'>";
			foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
			{
				echo "<li><div class='acx_left'>";
				echo $key;
				?>
		        </div><div class="acx_right"> 
				<a id="acx_slideshow_delete_gallery" class="del_gallery button" href="?page=Acurax-Slideshow-Settings&del=<?php echo $key; ?>" alt = "Click to Delete this Gallery" onclick="return confirm('Are you sure? \n\nNOTE: You Cant Undo This Action Once Processed');"><?php _e('Delete this Gallery','simple-slideshow-manager'); ?></a>
				<a id="acx_slideshow_manage_gallery" class="manage_gallery button"  value = "<?php echo $key?>" href="admin.php?page=Acurax-Slideshow-Add-Images&name=<?php echo $key;?>" alt = "Click to Manage this Gallery"><?php _e('Manage this Gallery','simple-slideshow-manager'); ?></a></br></br>
				<?php
				echo "</div></li>";			
			}
			echo "</ul>";
?>		</div>
	</form>
<?php
if($_GET['status']=="updated")
{
	?>
	<div id="acx_slideshow_updation_notice" name="acx_slideshow_updation_notice">
	<?php _e('You have successfully completed the updating processs','simple-slideshow-manager'); ?>
	<a name="updated"></a>
	</div>
	<?php
}
?>
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
</div>