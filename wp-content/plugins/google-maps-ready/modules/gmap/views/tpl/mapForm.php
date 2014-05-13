 <?php
	echo htmlGmp::formStart($this->params['formName'],array('attrs'=>" id='".$this->params['formId']."' class='gmpMapFormItm'"));
?>
	<div class='gmpFormRow'>
	   <div class='gmpFormElemCon'><?php
		  echo htmlGmp::input('map_opts[title]',array('attrs'=>" class='gmpInputLarge gmpMapTitleOpts gmpHintElem' required='required'  id='gmpNewMap_title' ","hint"=>langGmp::_("Title For Map")));
		  
	   ?></div>
			<label for="gmpNewMap_title" class="gmpFormLabel">
				 <?php langGmp::_e('Map Name')?>
		   </label>
		</div>  
		<div class='gmpFormRow'>
		   <label for="gmpNewMap_description" class="gmpFormLabel">
				 <?php langGmp::_e('Map Description')?>
		   </label>
		   <div class='gmpFormElemCon'>
		   <?php
			  echo htmlGmp::textarea('map_opts[description]',
					  array('attrs'=>" class=' gmpMapDescOpts gmpHintElem' id='gmpNewMap_description' ","hint"=>langGmp::_("Description For Map")));
			?>
			
			</div> 
		</div> 

		<div class='gmpFormRow'>
		<div class='gmpFormElemCon'>
		 <?php
		  echo htmlGmp::input('map_opts[width]',
				  array('value'=>'600',
					  'attrs'=>"class='gmpInputSmall gmpMapWidthOpt gmpHintElem'  required='required'   id='gmpNewMap_width' ","hint"=>langGmp::_("Width for map in pixels or percent")));
		 ?>
 		 </div>
		  <label for="gmpNewMap_width" class="gmpFormLabel">
				 <?php langGmp::_e('Map Width')?>
		  </label>

		</div> 
		<div class='gmpFormRow'>
		<div class='gmpFormElemCon'>
		 <?php 
			  echo htmlGmp::input('map_opts[height]',
					  array('value'=>'250',
						  'attrs'=>" class='gmpInputSmall gmpMapHeightOpt gmpHintElem'  required='required'  id='gmpNewMap_height' ","hint"=>langGmp::_("Height For Map In Pixels")));
		 ?>
		</div>
		  <label for="gmpNewMap_height" class="gmpFormLabel">
				 <?php langGmp::_e('Map Height')?>
		  </label>
		</div> 
		<div class='gmpFormRow'>
			<div class='gmpFormElemCon'>
		   <?php
		   $zoomEnableParams =	array('checked'=>'1',
						'attrs'=>" class='gmpHintElem gmpMapEnableZoomOpts' ",
						'hint'=>langGmp::_("Enable Zoom Control In Map"));
			if($this->params['formId']=="gmpAddNewMapForm"){
				$zoomEnableParams["specSelector"] = "gmpMapEnableZoomOpts";
			} 
			 echo htmlGmp::checkboxHiddenVal('map_opts[enable_zoom]',$zoomEnableParams);
		   ?>
		   </div>
		   <label for="map_optsenable_zoom_check" class="gmpFormLabel">
				 <?php langGmp::_e('Enable Zoom/Control  Panel')?>
		   </label>
		</div>
		<div class='gmpFormRow'>
			<div class='gmpFormElemCon'>
		   <?php
			 echo htmlGmp::checkboxHiddenVal('map_opts[enable_mouse_zoom]', array('checked'=>'1','attrs'=>" class='gmpHintElem gmpMapEnableMouseZoomOpts' ","hint"=>langGmp::_("Enable Mouse Zoom In Map"),
			 "specSelector"=>"gmpMapEnableMouseZoomOpts",
			 "isSecForm"=>(bool)($this->params['formId'] != "gmpAddNewMapForm")));
			 
		   ?>
			</div>
		   <label for="map_optsenable_mouse_zoom_check" class="gmpFormLabel">
				 <?php langGmp::_e('Enable Mouse Zoom/Control  Panel')?>
		   </label>
		</div>
	   <div class='gmpFormRow'>
			<div class='gmpFormElemCon'>
		   <?php
				echo htmlGmp::selectbox('map_opts[map_zoom]',
						array('attrs'=>" class='gmpMap_zoom gmpMapZoomOpts gmpInputSmall gmpHintElem' id='gmpMap_zoom' ",
							'options'=>$this->map_opts['zoom'],'value'=>1,
							"hint"=>langGmp::_("Default Zoom For Map")))
		   ?>
		   </div>
		   <label for="gmpMap_zoom" class="gmpFormLabel">
				 <?php langGmp::_e('Map Zoom')?>
		   </label>
	   </div>
	   <div class='gmpFormRow'>
		   <div class='gmpFormElemCon'>
		   <?php
			echo htmlGmp::selectbox('map_opts[map_type]',
					array('attrs'=>" class='gmpMap_type gmpInputSmall gmpMapTypeOpt gmpHintElem' id='gmpMap_type' ",
						'options'=>$this->map_opts['map_type'],
						"hint"=>langGmp::_("Select Map Display Mode")))
		   ?>
			</div>
			<label for="gmpMap_type" class="gmpFormLabel">
				 <?php langGmp::_e('Map Type')?>
		   </label>
	   </div>
	   <div class='gmpFormRow'>
			<div class='gmpFormElemCon'>
			   <?php
					echo htmlGmp::selectbox('map_opts[map_language]',
							array('attrs'=>" class='gmpMap_language gmpInputSmall gmpMapLngOpt gmpHintElem' id='gmpMap_language' ",
								'options'=>$this->map_opts['map_language'],'value'=>'en',"hint"=>langGmp::_("Select Map Display Language")));
			   ?>
			   </div>
		   <label for="gmpMap_language" class="gmpFormLabel">
					 <?php langGmp::_e('Map Language')?>
		   </label>
		   </div>
	   <div class='gmpFormRow'>
			<div class='gmpFormElemCon'>
		   <?php
				echo htmlGmp::selectbox('map_opts[map_align]',
						array('attrs'=>" class='gmpInputSmall gmpMapAlignOpt gmpHintElem' id='gmpMap_align' ",
							'options'=>$this->map_opts['map_align'],"hint"=>langGmp::_("Map Align")));
		   ?>
		   </div>
		   <label for="gmpMap_align" class="gmpFormLabel">
				 <?php langGmp::_e('Map Align')?>
		   </label>
	   </div>
	   <div class='gmpFormRow'>
	   <div class='gmpFormElemCon'>
		<?php
		   echo htmlGmp::hidden("map_opts[display_mode]",array('value'=>'map',
			   'attrs'=>" id='map_display_mode' class='map_display_preview_mode gmpMapDisplayModeOpt' "));
		?>
		</div>
	 </div>
	 <div class='gmpFormRow'>
		<div class='gmpFormElemCon'>
	   <?php
		  echo htmlGmp::input('map_opts[map_margin]',
				  array('attrs'=>" class='gmpInputSmall gmpMapMarginOpt gmpHintElem' id='gmpNewMap_margin' "
					  ,"hint"=>langGmp::_("Select Map Display Mode") ));
		?>
		</div>
		 <label for="gmpNewMap_margin" class="gmpFormLabel">
			 <?php langGmp::_e('Map Margin')?>
		  </label>
	  </div>  
	  <div class='gmpFormRow'>
	  <div class='gmpFormElemCon'>
		   <?php
				echo htmlGmp::colorpicker("border_color",array(
						'attrs' =>  " class='gmpInputSmall map_border_color gmpMapBorderColorOpt gmpHintElem' id='gmpNewMap_border_color_".$this->params['formId']."' ",
											 'value'	=>" ",   
											 'id'   =>  'gmpNewMap_border_color_'.$this->params['formId'],
											 "hint"=>langGmp::_("Select Map Display Mode")			));
		  ?>
		  </div>
		 <label for="gmpNewMap_border_color" class="gmpFormLabel">
				 <?php langGmp::_e('Border Color')?>
		  </label>
		</div>  
 		<div class='gmpFormRow'>
		   <div class='gmpFormElemCon'>
		   <?php
			  echo htmlGmp::input('map_opts[border_width]',
					  array('attrs'=>" class='gmpInputSmall gmpMapBorderWidthOpt gmpHintElem'  id='gmpNewMap_border_width' ","hint"=>langGmp::_("Select Map Display Mode")));
		   ?>
		   </div>
		   <label for="gmpNewMap_border_width" class="gmpFormLabel">
				 <?php langGmp::_e('Border Width')?>
		  </label>
		</div>
		<div class='gmpFormRowsCon'>
			<h3><?php langGmp::_e("Markers Infowindows Size");?></h3>
			<p><small><i><?php langGmp::_e("In Pixels");?></i></small></p>
			<div class='gmpFormRow'>
			   <div class='gmpFormElemCon'>
			   <?php
				  echo htmlGmp::input('map_opts[infowindow_width]',
						  array('attrs'=>" class='gmpInputSmall gmpMapInfoWindowWidthOpt gmpHintElem'  id='gmpNewMap_Infowindow_width' ","hint"=>langGmp::_("InfoWindow Width"),"value"=>"200"));
			   ?>
			   </div>
			   <label for="gmpNewMap_Infowindow_width" class="gmpFormLabel">
					 <?php langGmp::_e('InfoWindow Width')?>
			  </label>
			</div>  

			<div class='gmpFormRow'>
			   <div class='gmpFormElemCon'>
			   <?php
				  echo htmlGmp::input('map_opts[infowindow_height]',
						  array('attrs'=>" class='gmpInputSmall gmpMapInfoWindowHeightOpt gmpHintElem'  id='gmpNewMap_Infowindow_height' ","hint"=>langGmp::_("InfoWindow Height"),"value"=>"100"));
			   ?>
			   </div>
			   <label for="gmpNewMap_Infowindow_height" class="gmpFormLabel">
					 <?php langGmp::_e('InfoWindow Height');?>
			  </label>
			</div>  
		</div>

<?php
	echo htmlGmp::formEnd();
	
?>