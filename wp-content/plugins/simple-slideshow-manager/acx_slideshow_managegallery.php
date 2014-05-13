<?php
//************** get array from the database **********
$acx_selected_gallery_name =trim($_GET['name']); 
$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}
?>
<?php
if($_POST['acx_slideshow_hidden'] == 'Y') 
{
//**********get the form data, validate it and update database with new value starts********
	$acx_selected_gallery_name = trim($_GET['name']);
    $gallery_name = trim($_GET['name']);  
	$show_alert = 0;
	if(is_numeric($_POST['acx_slideshow_timespan'])) 
	{	
		$acx_slideshow_timespan = $_POST['acx_slideshow_timespan'];
	} else {
	$acx_slideshow_timespan = 4;
	$show_alert = 1;
	}
	if(is_numeric($_POST['acx_slideshow_fadeintime'])) 
	{
		$acx_slideshow_fadeintime = $_POST['acx_slideshow_fadeintime'];
	} else {
	$acx_slideshow_fadeintime = 1;
	$show_alert = 1;
	}
	if(is_numeric($_POST['acx_slideshow_fadeouttime'])) 
	{
	$acx_slideshow_fadeouttime = $_POST['acx_slideshow_fadeouttime'];
	} else {
	$acx_slideshow_fadeouttime = 1;
	$show_alert = 1;
	}
	if(is_numeric($_POST['acx_slideshow_height']))
	{
		$acx_slideshow_height = $_POST['acx_slideshow_height'];
	}
	else
	{
		$show_alert = 1;
	}
	$acx_slideshow_height_type = $_POST['acx_slideshow_height_type'];
	if(is_numeric($_POST['acx_slideshow_width']))
	{
		$acx_slideshow_width = $_POST['acx_slideshow_width'];
	}
	else
	{
		$show_alert = 1;
	}
	$acx_slideshow_width_type = $_POST['acx_slideshow_width_type'];
	if($acx_slideshow_height != "")
	{
	$acx_slideshow_height = $acx_slideshow_height.$acx_slideshow_height_type;
	} else
	{
	$acx_slideshow_height = "";
	}
	if($acx_slideshow_width != "")
	{
	$acx_slideshow_width = $acx_slideshow_width.$acx_slideshow_width_type;
	} else
	{
	$acx_slideshow_width = "";
	}
	$acx_slideshow_pauseon_hover = $_POST['acx_slideshow_pauseon_hover'];
	if($show_alert!=1)
	{
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_timespan'] = $acx_slideshow_timespan;
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeintime']= $acx_slideshow_fadeintime;
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeouttime'] = $acx_slideshow_fadeouttime;
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_pauseon_hover'] = $acx_slideshow_pauseon_hover;
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_height'] = $acx_slideshow_height;
	$acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_width']  = $acx_slideshow_width;
	
	update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));
	update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	?>
	   <div class="updated"><p><strong><?php _e('Simple Slideshow Settings Saved!.','simple-slideshow-manager' ); ?></strong></p></div>
   <?php	
	}
	if($show_alert == 1)
	{
echo "<script type=\"text/javascript\">";
echo "alert('".__('The text you entered is in incorrect format..please enter a numeric value..','simple-slideshow-manager')." ')";
echo "</script>";
	}
}
//********** get the form data, validate it and update database with new value ends ********

else
{
//********** normal page display ***************

	if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
	$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
	if(($_GET['rename_status'])=="yes")
	{
	?>
<script type="text/javascript">
alert('<?php _e('Gallery Renamed Successfully','simple-slideshow-manager'); ?>');
</script>
	<?php
	}
	elseif(($_GET['rename_status'])=="no")
	{
		?>
<script type="text/javascript">
alert('<?php _e('Gallery name already exist enter another name','simple-slideshow-manager'); ?>');
acx_rename(1);
</script>
		<?php
	}
}

?>
<?php
//############################################################################################################
?>
<div class="wrap" style="background-color:#BDCAC9s;">
<?php
if($acx_slideshow_misc_hide_advert == "no")
{
?>
<div id="acx_ssm_premium">
<a style="margin: 8px 0px 0px 10px; float: left; font-size: 16px; font-weight: bold;" href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=highlight&utm_campaign=ssm" target="_blank">Fully Featured Plugin - Advanced Slideshow Manager</a>
<a style="margin: -14px 0px 0px 10px; float: left;" href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=highlight_yellow&utm_campaign=ssm" target="_blank"><img src="<?php echo plugins_url('images/yellow.png', __FILE__);?>"></a>
</div> <!-- acx_fsmi_premium -->
<?php } ?>
	<?php echo "<h2>" . __( 'Simple Slideshow Manager Settings', 'simple-slideshow-manager' ) . "</h2>"; 
?>
	<form name="acx_slideshow_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="acx_slideshow_hidden" id ="acx_slideshow_hidden" value="Y">
		<?php
		if (isset($_GET['name'])) 
		{
			$acx_selected_gallery_name = trim($_GET['name']);
			$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
			$plugin_file = basename( ( __FILE__ ) );
			?>
			<p class="widefat" style="padding:8px;width:99%;margin-top:8px; ">	<?php _e('Selected Gallery ','simple-slideshow-manager');?>: <b><?php echo $acx_selected_gallery_name; ?></b>
			<input type = "hidden" id = "gallery_selected" name = "gallery_selected" value = "<?php  echo $acx_selected_gallery_name;?>">
			<a class="acx_rename_gallery button acx_rename_slide_button" href="#" onclick = "acx_rename(1);" style="margin-left:10px;"><?php _e('Rename Gallery','simple-slideshow-manager'); ?></a>
			<span id="shortc"><b><?php _e('Shortcode:-','simple-slideshow-manager'); ?></b>
			<input type="text" value='[acx_slideshow name="<?php  echo $acx_selected_gallery_name;?>"]' readonly size="60" onClick="javascript:this.focus();this.select();">
			<a href="admin.php?page=Acurax-Slideshow-Generate-Shortcode"><?php _e('Click Here For Custom Shortcode Generator','simple-slideshow-manager'); ?></a>
			</span> <!-- shortc -->
			
			<?php
			$acx_slideshow_height = $acx_slideshow_gallery_data[$acx_selected_gallery_name]['acx_slideshow_height'];
			$acx_slideshow_width = $acx_slideshow_gallery_data[$acx_selected_gallery_name]['acx_slideshow_width'];
			if($acx_slideshow_height==""&&$acx_slideshow_width=="")
			{
			?>
			<br/>
			<br/>
			<span style="color:red;">You have not yet configured height and width for this slideshow.Go to <a href="#acx_advance" onclick="acx_goto_advsett();">Advanced Settings</a> </span>
			<?php
			}
			?>
			</p>
			<?php
		}
		?>
		</p>
		
		<p class="widefat" style="padding:8px;width:99%;margin-top:8px; ">
			<label for="upload_image">
				<input id="acx_slideshow_upload_image_url" readonly type="hidden" size="78" name="acx_slideshow_upload_image" value="" />
				 <a id="acx_slideshow_upload_image_button" class="button  acx_slide_upload" href="#" ><?php _e('Add Image as Slide ','simple-slideshow-manager'); ?></a>
				 <input type = "hidden" name = "acx_slideshow_title" id = "acx_slideshow_title" />
				<input type = "hidden" name = "acx_slideshow_caption" id = "acx_slideshow_caption" />
				<input type = "hidden" name = "acx_slideshow_alttext" id = "acx_slideshow_alttext"/>
				<input type = "hidden" name = "acx_slideshow_desc" id = "acx_slideshow_desc"/>
				 </label>
				 <label for="upload_video">
				 <a id="acx_slideshow_upload_video_button" class="button acx_video_upload" href="#" onclick="acx_upload_video(1)" ><?php _e('Add Youtube/Vimeo Video as Slide','simple-slideshow-manager'); ?></a>
				 </label>	
		</p>

<?php

//############################################################################################################
?>		
<script type="text/javascript">
var isacx_add_advance = 1;
function acx_goto_advsett()
{
jQuery("#acx_advance").show();
jQuery("#plus_minus").html('[ - ]');
isacx_add_advance = 2;
}
acx_slideshow_media_upload_loader();
var gallery_name = document.getElementById("gallery_selected").value;
//to show the rename gallery field
function acx_rename(status)
{
if(status==1)
{
//display tyhe rename form
jQuery("#rename_gallery").show();
} 
else
{
//close the rename form
jQuery("#rename_gallery").hide();
}	
}
function acx_upload_video(status)
{
if(status==1)
{
//display tyhe rename form
document.getElementById("acx_video_url").value = "";
jQuery("#acx_upload_video").show();
} 
else
{
//close the rename form
jQuery("#acx_upload_video").hide();
}
}
//display the advanced settings field
function acx_add_advance()
{
if(isacx_add_advance == 1)
{
jQuery("#acx_advance").show();
jQuery("#plus_minus").html('[ - ]');
isacx_add_advance = 2;
} else
{
jQuery("#acx_advance").hide();
jQuery("#plus_minus").html('[+]');
isacx_add_advance = 1;
}
}
//********************* send the data to upload_image page to upload new image (Ajax) ********************
function upload_video()
{
var gallery_name = document.getElementById("acx_gall_name").value;
var video_url = document.getElementById("acx_video_url").value;
var type = document.getElementById("acx_video_type").value;
var youtube_regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
var vimeo_regex= /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
if(video_url.match(youtube_regex)||video_url.match(vimeo_regex))
{	
jQuery("#acx_upload_video").hide();
var order ='&gallery_name='+gallery_name+'&video_url='+video_url+'&type='+type+'&action=acx_slideshow_ajax_upload'; 
var acx_load="<div id='acx_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(acx_load);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
jQuery("#acx_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
});
}
else 
{
alert('<?php _e('Enter a video url to upload','simple-slideshow-manager'); ?>');
}
}
function upload_image()
{
var gallery_name = document.getElementById("gallery_selected").value;	
var image_url = document.getElementById("acx_slideshow_upload_image_url").value;
var image_title = document.getElementById("acx_slideshow_title").value;
var image_caption = document.getElementById("acx_slideshow_caption").value;
var image_alttext = document.getElementById("acx_slideshow_alttext").value;
var image_desc = document.getElementById("acx_slideshow_desc").value;	
var type = "image";
if(image_url != "")
{
var acx_load="<div id='acx_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(acx_load);
document.getElementById("acx_slideshow_upload_image_url").value = "";
var order ='&gallery_name='+gallery_name+'&image_url='+image_url+'&image_title='+image_title+'&image_caption='+image_caption+'&image_alttext='+image_alttext+'&image_desc='+image_desc+'&type='+type+'&action=acx_slideshow_ajax_upload'; 
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
});
jQuery("#acx_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
}
else if(image_url=="")
{
alert('<?php _e('Select an image to upload','simple-slideshow-manager'); ?>');
}
}
function acx_change_vediotype()
{
var acx_vedio_url = document.getElementById("acx_video_url").value;
var dd = document.getElementById('acx_video_type');
if ( acx_vedio_url.indexOf( "vimeo" ) > -1 ) 
{
dd.selectedIndex = 1;	
}
else if ( acx_vedio_url.indexOf( "youtube" ) > -1 ) 
{
dd.selectedIndex = 0;	
}
}
</script>
<br/>
<hr/>
	<?php echo "<h4>" . __( 'Drag and Drop to Reorder Slides', 'simple-slideshow-manager' ) . "</h4>"; ?>
		<div id="response" style="padding:8px;width:99%;margin-top:8px;" class="widefat">
		<?php
		if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
		$gallery_name = $acx_selected_gallery_name;
		$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
		echo"<ul id = \"acx_slideshow_sortable\">";
		for($i = 0  ; $i <$slide_count ; $i++)
		{
			if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
			{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
				echo "<div class=\"edit_but\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
				echo "<div class=\"add_link\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";	
				echo "</li>";
			}
			else if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
			{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
			}
			else if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
			{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
			}
		}
		echo"</ul>";
				?>
	</div>	
<script type="text/javascript">
//delete image from the selected gallery
function acx_delete(value)
{
if (confirm('<?php _e('Are You Sure to Delete This Slide?\n\nNOTE: You cannot undo this action.','simple-slideshow-manager'); ?>')) { 
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&action=acx_slideshow_ajax_deleteimage';
var acx_load="<div id='acx_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(acx_load);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
jQuery("#acx_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
});
}
}
function acx_edit(value)
{
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&action=acx_slideshow_ajax_editimage';
var acx_load="<div id='acx_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(acx_load);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#acx_edit_image").show();
jQuery("#edit_image").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
jQuery("#acx_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
});
}
function acx_slideshow_change_edittext(value)
{
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var title = document.getElementById("acx_slideshow_edit_title").value;
var caption = ""; //document.getElementById("acx_slideshow_edit_caption").value;
var alttext = document.getElementById("acx_slideshow_edit_alt").value;
var desc = ""; //document.getElementById("acx_slideshow_edit_desc").value;
var url = document.getElementById("acx_slideshow_edit_url").value;
var target = document.getElementById("acx_link_target").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&title='+title+'&caption='+caption+'&alttext='+alttext+'&desc='+desc+'&url='+url+'&target='+target+'&action=acx_slideshow_ajax_changeedittext';
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#acx_edited").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
var m_edited="<div id='s_s_notice'>Image edited</div>";
jQuery('#response').prepend(m_edited);
acx_slideshow_change_edittext_cancel();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 5000); // <-- time in milliseconds
});
}
function acx_slideshow_change_edittext_cancel()
{
jQuery('#acx_edit_image').hide();
jQuery('#acx_editimage_form').remove();
}
</script>
<script type="text/javascript">
var gallery_name = document.getElementById("gallery_selected").value;
function acx_slideshow_ajax_updateRecordsListings_js()
{
//************ Arrange the order of display of image (drag and drop)************
jQuery(function() 
{
jQuery("#acx_slideshow_sortable").sortable(
{ 
opacity: 0.5, cursor: 'move', update: function() 
{
var order = jQuery(this).sortable("serialize")+'&gallery_name='+gallery_name+'&action=acx_slideshow_ajax_updateRecordsListings'; 
//alert(order);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
acx_slideshow_ajax_updateRecordsListings_js();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
}); 
}								  
});
});
}
jQuery(document).ready(function()
{
acx_slideshow_ajax_updateRecordsListings_js();
});	
</script>

<?php
//###########################################################################################################

	$acx_slideshow_timespan = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_timespan'];
	$acx_slideshow_fadeouttime = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeouttime'];
	$acx_slideshow_fadeintime = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeintime'];
	$acx_slideshow_pauseon_hover = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_pauseon_hover'];
	
	if($acx_slideshow_timespan=="" || $acx_slideshow_timespan==0)
	{
		$acx_slideshow_timespan = 4;
	}
	if($acx_slideshow_fadeintime=="" || $acx_slideshow_fadeintime ==0)
	{
		$acx_slideshow_fadeintime = 1;
	}
	if($acx_slideshow_fadeouttime == "" || $acx_slideshow_fadeouttime == 0)
	{
		$acx_slideshow_fadeouttime = 1;
	}
?>	

		<div class="widefat" style="padding:8px;width:99%;margin-top:8px;" >
			<p><?php _e(' Advanced Settings','simple-slideshow-manager' ); ?> <a href="javascript:acx_add_advance();" class="close"><b id="plus_minus">[+]</b></a></p>
			<br>
			<div id = "acx_advance" style = "display:none" >
				<?php _e('Time span in seconds','simple-slideshow-manager'); ?> <input type = "text" name = "acx_slideshow_timespan" id= "acx_slideshow_timespan" size = "40" value = "<?php echo $acx_slideshow_timespan;  ?>"/></br></br>
				<?php _e('Fadein Time in seconds','simple-slideshow-manager'); ?><input type = "text" name = "acx_slideshow_fadeintime" id= "acx_slideshow_fadeintime" size = "40"  value = "<?php echo $acx_slideshow_fadeintime;?>" /></br></br>
				<?php _e('Fadeout Time in seconds','simple-slideshow-manager'); ?><input type = "text" name = "acx_slideshow_fadeouttime" id= "acx_slideshow_fadeouttime" size = "40"  value = "<?php echo $acx_slideshow_fadeouttime;  ?>" /></br></br>
				<?php _e('Enable Pause on Hover','simple-slideshow-manager'); ?>
				<select id="acx_slideshow_pauseon_hover" name="acx_slideshow_pauseon_hover">
				<option value="true" <?php if($acx_slideshow_pauseon_hover=="true"||$acx_slideshow_pauseon_hover==""){?> selected="selected" <?php } ?> ><?php _e('Enable','simple-slideshow-manager'); ?></option>
				<option value="false" <?php if($acx_slideshow_pauseon_hover=="false"){?> selected="selected" <?php } ?> ><?php _e('Disable','simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<?php 
				$acx_slideshow_height = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_height'];
				$acx_slideshow_width = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_width'];
				if (preg_match('/%/', $acx_slideshow_height))
				{
					$acx_slideshow_height_type = "%";
				}
				else if(preg_match('/px/', $acx_slideshow_height))
				{
					$acx_slideshow_height_type ="px";
				}
				if(preg_match('/%/', $acx_slideshow_width))
				{
					$acx_slideshow_width_type = "%";
				}
				else if(preg_match('/px/', $acx_slideshow_width))
				{
					$acx_slideshow_width_type ="px";
				}
				$temp_height = str_replace("%","",$acx_slideshow_height);
				$temp_height = str_replace("px","",$temp_height);
				$temp_width = str_replace("%","",$acx_slideshow_width);
				$temp_width = str_replace("px","",$temp_width);
				?>
				
				<?php _e('Slide height','simple-slideshow-manager'); ?> <input type = "text" name = "acx_slideshow_height" id= "acx_slideshow_height" size = "40"  value = "<?php echo $temp_height;?>" />
					<select id="acx_slideshow_height_type" name="acx_slideshow_height_type">
				<option value="px" <?php if($acx_slideshow_height_type=="px"){?> selected="selected" <?php } ?> ><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%" <?php if($acx_slideshow_height_type=="%"){?> selected="selected" <?php } ?>><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<?php _e('Slide width','simple-slideshow-manager'); ?><input type = "text" name = "acx_slideshow_width" id= "acx_slideshow_width" size = "40"  value = "<?php echo $temp_width;?>" />
				<select id="acx_slideshow_width_type" name="acx_slideshow_width_type">
				<option value="px" <?php if($acx_slideshow_width_type=="px"){?> selected="selected" <?php } ?> ><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%" <?php if($acx_slideshow_width_type=="%"){?> selected="selected" <?php } ?> ><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<p class="submit">
					<input type="submit" class ="button" name="Submit" value="<?php _e('Save Settings', 'simple-slideshow-manager' ) ?>" />
				</p>			
			</div>
		</div>
	</form>

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
<div id="rename_gallery" class="widefat" style = "display:none">
	<div id="rename_lb">
		<form name="gall_renameform" method="post" action="admin.php?page=Acurax-Slideshow-Rename-Gallery">
			<input type = "text" id = "rename" name = "rename" class="field"value="<?php echo $_GET['name'];  ?>" onblur="if (this.value == '') {this.value = '<?php echo $_GET['name'];  ?>';}" onfocus="if (this.value == '<?php echo $_GET['name'];  ?>') {this.value = '';}" />
			<input type = "hidden" id="old_name" name="old_name" value = "<?php echo $_GET['name'];  ?>"/>
			<input type = "hidden" id = "url" name = "url" value = "<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"/>
			<input type = "submit" id="confirm" name="confirm" value ="Rename"  class="button acx_rename_buttn"/>
		</form>
		<a href="#" class="close" onclick = "acx_rename('2');">X</a>
	</div>
</div>

<div id="acx_upload_video"  class="widefat" style="display:none">
	<div id="upload_vedio">
	
	<?php _e('Video Url','simple-slideshow-manager'); ?>
	<input type="text" name="acx_video_url" id="acx_video_url" onChange="acx_change_vediotype();" /></br>
	<?php _e('Video Type','simple-slideshow-manager'); ?>
			<select name="acx_video_type" id="acx_video_type">
			<option value="youtube_video"><?php _e('Youtube Video','simple-slideshow-manager'); ?> 
			<option value="vimeo_video"><?php _e('Vimeo Video','simple-slideshow-manager'); ?> 
			</select></br></br>
	
    <input type="button" value="Upload Video"  class="button acx_vedio_upload_buttn" onclick="upload_video()" />
	<input type = "hidden" id="acx_gall_name" name="acx_gall_name"  value = "<?php echo $_GET['name'];  ?>"/>
	<a href="#" class="close" onclick = "acx_upload_video('2');">X</a>
	</div>
</div>
<div id="acx_edit_image" style="display:none">
	<div id="edit_image">
		
	</div>
</div>
<div id="acx_edited">
</div>