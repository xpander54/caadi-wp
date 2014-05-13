<div class='markerOptsCon'>
	<?php
		echo htmlGmp::formStart($this->params['formName'],array('attrs'=>"id='".$this->params['formId'] ."' class='gmpMarkerFormItm'"));
	?>
	<div class='gmapMarkerFormControlButtons'>
			 <?php 
			 switch($this->params['formId']){
				 case "gmpAddMarkerToNewForm":
					 ?>
					  <div class="gmpEditMarkerOpts">
						  <input type='hidden' id='gmpEditedMarkerLocalId' value='' />
					</div>	 
					<?php
				break;
				case "gmpEditMarkerForm":
					 ?>
						<div class="gmpAddMarkerOpts gmpMarkerEditformBtns ">
							   
						 <?php

						  echo htmlGmp::button( array(
							  "attrs"=>'id="gmpSaveEditedMarkerItem" type="submit" class="btn btn-success"',
							  'value'=>langGmp::_("<span class='gmpIcon gmpIconSuccess'></span>".langGmp::_("Save"))));
						  
						  ?>
						 <a class='btn btn-danger gmpDeleteMarker ' onclick='return gmpDeleteMarker()'>
							 <span class='gmpIcon  gmpIconReset'></span><?php langGmp::_e("Remove");?>
						 </a>	   
							<div id="gmpUpdateMarkerItemMsg"></div>
						 </div>
						 
						 <?php
				break;	
				case "gmpAddMarkerToEditMap":
					/* ?>
					<div class="gmpAddMarkerOpts">
						<br/>
						<br/>
                    <?php
                     echo htmlGmp::submit('drawMarker', 
                             array("attrs"=>'id="AddMarkerToMap" class="btn btn-primary"',
                                   'value'=>langGmp::_("Add Marker")));
                    ?>
					</div>
					<div class="gmpEditMarkerOpts">
						<br/>
						<br/>
						<a class="btn btn-success gmpSaveEditedMarker" id="gmpSaveEditedMarker">
							<?php langGmp::_e("Save Marker")?>
						</a>
						
							  <a class="btn btn-danger gmpCancelMarkerEditing" id="gmpCancelMarkerEditing" >
								  <?php langGmp::_e("Cancel")?>
							  </a> 
						<a class='btn btn-danger removeMarkerFromForm' >
							<span class='gmpIcon gmpIconReset'></span>
							<?php langGmp::_e("Remove");?>											
						</a>
					</div> 
						 <?php
						 */
					 break;
			 
				 
			 }
			 
			 
			 
			 
			 
			 
			 ?>
		<div class="gmpEditMarkerOpts">
			<input type='hidden' id='gmpEditedMarkerLocalId' value='' />
		</div> 
	</div> 
    
	   <div class='gmpFormRow'>
		   <div class='gmpFormElemCon'>
		  <?php
			 echo htmlGmp::input('marker_opts[title]',
				array('attrs'=>" class='gmpInputLarge gmpMarkerTitleOpt gmpHintElem'  id='gmpNewMap_marker_title' required='required' ","hint"=>"Title For Marker"));
		  ?>
			</div>
		  <label for="gmpNewMap_marker_title" class="gmpFormLabel">
				<?php langGmp::_e('Marker Name')?>
		  </label>
	   </div> 
	   <div class='gmpFormRow'>
			<label for="" class="gmpFormLabel">
				<?php langGmp::_e('Set Title as link');?>
			</label>
		   
		  <?php
				echo "<div class='gmpFormElemCon'>";
				echo htmlGmp::checkbox("title_is_link",array('attrs'=>" class='title_is_link gmpMarkerTitleIsLinkOpt gmpHintElem' ","hint"=>  langGmp::_("Set Marker Title As Link")));
				echo '</div>';
				echo "<div class='markerTitleLink_Container'>"; 
				echo htmlGmp::input("marker_title_link",
					array("attrs"=>"type='text' id='marker_title_link' class='marker_title_link gmpMarkerTitleIsLinkOpt gmpHintElem' placeholder='http://domain.com'  " ,'type'=>"text","hint"=>langGmp::_("Link For Title")));
				echo "</div>";
		  ?>
	   </div>  
	<div class='gmpFormRow'>
	   <div class='gmpFormElemCon'>
	  <?php
		  $groupArr=array();
		   foreach($this->marker_opts['groups'] as $item){
			   $groupArr[$item['id']]=$item['title'];
		   }
		  echo htmlGmp::selectbox('marker_opts[group]',
				  array('options'=>$groupArr,
					  'value'=>'1' ,
					  'attrs'=>' id="gmpNewMap_marker_group" class="gmpInputLarge gmpMarkerGroupSelect gmpMarkerGroupOpt gmpHintElem" ',"hint"=>langGmp::_("Choose Marker Group")));
			  ?>
		   </div>
		  <label for="gmpNewMap_marker_group" class="gmpFormLabel">
				<?php langGmp::_e('Group')?>
		  </label>
	   </div> 
	   <div class='gmpFormRow'>
		  <label for="gmpNewMap_marker_desc" class="gmpFormLabel">
				<?php langGmp::_e('Marker Description')?>
		  </label>
		  <?php	
			wp_editor('', 'gmp_marker_desc'.$this->params['formId'] , array('quicktags' => false) );

		  ?>
	   </div>
		 <div class="gmpMarkericonOptions">
			<h3><?php langGmp::_e('Marker Icon')?></h3>
			<div class="gmpFormRow">
				<div class='gmpIconsSearchBar'>
					<div class='lft'>
						<a class='btn btn-default' onclick='clearIconSearch()'>
							<?php langGmp::_e('All Icons');?>
						</a>	
					</div>
					<div class='right gmpSearchFieldContainer'>
						<div class='gmpIconSearchZoomIcon'></div>
							<div class='gmpFormElemCon'><?php
							echo htmlGmp::input("gmpSearchIconField", 
									array("attrs"=>" class='gmpSearchIconField gmpHintElem' ",
										"hint"=>langGmp::_("Search For Icons")));
						?>
						</div> 
					</div> 
			   </div> 
			   <div class='gmpIconsList'>
			 <?php
				 $defIcon = false;
				 $activeClassName = '';
  				 foreach($this->marker_opts['icons'] as $icon){
					   if(!$defIcon){
						   $defIcon=$icon['id'];
						   $activeClassName=' active';
					   }
			   ?>
			   <a class='markerIconItem <?php echo $activeClassName;?>' data_name='<?php echo $icon['title'];?>' data_desc="<?php echo $icon['description']; ?>" title='<?php echo $icon['title'];?>' data_val='<?php echo $icon['id'];?>'>
						<img src="<?php echo $icon['path'];?>" class='gmpMarkerIconFile' />   
				   </a>   
						   <?php
						   $activeClassName="";
						}
					 ?>
				   </div>  
					<input type="hidden" name="marker_opts[icon]" value="<?php echo $defIcon;?>" id="gmpSelectedIcon" class="right gmpMarkerSelectedIconOpt">
   
			</div>   
			<div class="gmpFormRow">
			   <label for=''><?php langGmp::_e('Or Upload your icon');?></label>
				<label for="upload_image" class='right'>
					<input id="gmpUploadIcon" class="gmpUploadIcon button" type="button" value="Upload Image" />
				</label>
			 <div class='gmpUplRes'>
				</div>  
				<div class='gmpFileUpRes'>
					
				</div>   
		</div>
	 </div> 
	<div class='gmpFormRow'>
			<label for='marker_opts[animation]'><?php langGmp::_e("Marker Animation")?></label> 
			<div class='gmpFormElemCon'>
				<?php echo htmlGmp::checkboxHiddenVal("marker_opts[animation]", array('attrs'=>' id="marker_opts_animation" class="gmpHintElem marker_optsanimation" ',"hint"=>langGmp::_("Enable Marker Animation")));?>
				</div>		
			</div>		
		 <div class='clearfix'></div>
			
		 
			<div class='gmpFormRow gmpAddressField'>
				 <label for="gmp_marker_address" class="gmpFormLabel">
					  <?php langGmp::_e('Marker Address')?>
				 </label> <span id="gmpAddressLoader"></span> <br/> 
			
				
			  <?php
				 echo htmlGmp::input('marker_opts[address]',
							array('attrs'=>" class=' gmp_marker_address gmpMarkerAddressOpt gmpHintElem'  id='gmp_marker_address'  placeholder='Type address'"));
			  ?> 
				<div class='gmpAddressAutocomplete'>
					<ul>
					</ul>
				</div>	
				<div class='gmpAddrErrors'></div>
			</div> 
		 
			<div class='gmpFormRow'>
			  <label for="gmpNewMap_marker_coords" class="gmpFormLabel">
					<?php langGmp::_e('Marker Coordinates')?>
			  </label>
			   <br/>
			   <small class="gmplft"><?php echo langGmp::_("if your don't know coordiates, leave this fields blank");?></small>
			   <div class="clearfix"></div>
			   <div style=''>
			  <?php
				echo langGmp::_("Lat.");
				?><div class='gmpFormElemCon'><?php	
				 echo htmlGmp::input('marker_opts[coord_x]',
							array('attrs'=>" class='gmpInputSmall gmpMarkerCoordXOpt gmpHintElem'   id='gmp_marker_coord_x' ",
								"hint"=>"Coordinate X (Longitude)"));
				 ?>
				 </div>
					 </div>
			   <br>
					 <div>
					 <?PHP
				 echo langGmp::_("Lng.");
				 	?><div class='gmpFormElemCon'><?php	
				 echo htmlGmp::input('marker_opts[coord_y]',array('attrs'=>" class='gmpInputSmall gmpMarkerCoordYOpt gmpHintElem'  id='gmp_marker_coord_y'","hint"=>"Coordinate Y(Latitude)"));
			  ?>
				  </div>
				  </div>

		   </div>
		 
	 <?php
		 echo htmlGmp::formEnd();
	 ?>
 </div> 