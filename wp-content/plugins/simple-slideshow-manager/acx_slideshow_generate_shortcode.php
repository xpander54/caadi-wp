<?php	
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}
$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
if($acx_slideshow_imageupload_complete_data=="")
{
	$acx_slideshow_imageupload_complete_data=array();
}
//print_r($acx_slideshow_imageupload_complete_data);
?>
<script type="text/javascript">
function acx_generate()
{
var acx_gallery_name = document.getElementById("acx_shortcode_gallery").value;
var acx_height = document.getElementById("acx_shortcode_gall_height").value;
var acx_width = document.getElementById("acx_shortcode_gall_width").value;
var acx_height_type = document.getElementById("acx_height_type").value;
var acx_width_type =  document.getElementById("acx_width_type").value;
if(acx_gallery_name!="")
{
if(!isNaN(document.getElementById("acx_shortcode_gall_height").value))
{
acx_height = document.getElementById("acx_shortcode_gall_height").value;
} else
{
acx_height = "";
alert('<?php _e('Height needs to be a number','simple-slideshow-manager'); ?>');
}
if(!isNaN(document.getElementById("acx_shortcode_gall_width").value))
{
acx_width = document.getElementById("acx_shortcode_gall_width").value;
} else
{
acx_width = "";
alert('<?php _e('Width needs to be a number','simple-slideshow-manager'); ?>');
}
jQuery("#acx_shortcode").show();
if(acx_height == "")
{
shortcode_ht = "";
} else
{
shortcode_ht = " height=\""+acx_height+acx_height_type+"\"";
}
if(acx_width == "")
{
shortcode_wth = "";
} else
{
shortcode_wth = " width=\""+acx_width+acx_width_type+"\"";
}
var shortcode_cnt = "[acx_slideshow name=\""+acx_gallery_name+"\""+shortcode_ht+shortcode_wth+"]";
document.getElementById("acx_shortcode_display").value = shortcode_cnt;	
}
else
{
	alert("There is no gallery exist.");
}
}
</script>

<div class="wrap">
	<?php
	echo "<h2>" . __( 'Generate Shortcode', 'simple-slideshow-manager' ) . "</h2>"; 
	?>
	<div style="padding:8px;width:99%;margin-top:8px;" class="widefat">
		<table style="border:0px;" id="acx_short_gen" cellspacing="0">
			<tr>
				<td> <?php _e('Select Gallery','simple-slideshow-manager'); ?></td>
				<td>
				<select id="acx_shortcode_gallery" name="acx_shortcode_gallery">	
				<?php
				foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
				{
				?>
				<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
				<?php
				}
				?>
				</select></td>
			</tr>
			<tr>
				<td><?php _e('Height','simple-slideshow-manager'); ?></td>
				<td>
				<input type="text" name="acx_shortcode_gall_height" id="acx_shortcode_gall_height" value=""/>
				<select id="acx_height_type" name="acx_height_type">
				<option value="px"><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%"><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</td>
			</tr>
			<tr>
				<td>
				<?php _e('Width','simple-slideshow-manager'); ?></td><td>
				<input type="text" name="acx_shortcode_gall_width" id="acx_shortcode_gall_width" value=""/>
				<select id="acx_width_type" name="acx_width_type">
				<option value="px"><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%"><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</td>
			</tr>
			<tr>
				<td></td><td>	<a id="acx_slideshow_generate_shortcode" class="manage_gallery"  href="#" style="background: none repeat scroll 0px 0px lightseagreen; color: white; padding: 0px 5px 1px; text-decoration: none; border: 1px solid gray;" alt = "Click to Manage this Gallery" onclick = "acx_generate()"><?php _e('Generate Shortcode','simple-slideshow-manager'); ?></a></td>
			</tr>
			<tr>
				<td colspan="2">
				<div id="acx_shortcode" style = "display:none">
				<b><?php _e('Shortcode:-','simple-slideshow-manager'); ?></b>
				<input type="text" name="acx_shortcode_display" id="acx_shortcode_display" value="" readonly size="60" onClick="javascript:this.focus();this.select();">
				</div>
				</td>
			</tr>
		</table>
	</div>
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