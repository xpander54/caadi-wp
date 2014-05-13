<!-- Map Editing -->
<div class='gmpNewMapContent'>
    <div class='gmpMapOptionsTab'>
        <ul class='gmpNewMapOptsTab nav nav-tabs'>
			<li>
				<a class='' id='gmpTabForNewMapMarkerOpts' href="#gmpEditMapMarkers">
					<button class="btn btn-success  gmpAddNewMarkerBtn" onclick="gmpAddNewMarker()">
						<span class="gmpIcon gmpIconMarker"></span>
						<?php langGmp::_e("Add Marker")?>
					</button>

					<span class="gmpTabElemSimpTxt" disabled="disabled">
						<span class="gmpIconSimpMarker"></span>
						<?php langGmp::_e("Markers");?>
					</span>

					<span class='gmp-tabs-btns'>
					<?php
					 echo htmlGmp::button( 
						 array("attrs"=>'id="AddMаrkerToMap" class="btn btn-success gmpAddSaveMarkerBtn" type="submit"  disabled="disabled"',
						   'value'=>"<span class='gmpIcon gmpIconAdd'></span>".langGmp::_("Save")));
					?>
					<button class='btn btn-danger removeMarkerFromForm' disabled="disabled">
						<span class='gmpIcon gmpIconReset'></span>
						<?php langGmp::_e("Remove");?>											
					</button>
					</span>
				</a>
			</li>
			<li class='active'>
				<a id='gmpTabForNewMapOpts' class="btn btn-primary gmpTabForNewMapOpts"   href="#gmpEditMapProperties"  >
					
					<span class="gmpTabElemSimpTxt" disabled="disabled">
						<span class="gmpIconSimpMarker"></span>
						<?php langGmp::_e("Map Properties");?>
					</span>
					<button class="btn btn-success gmpSaveEditedMapBtn" id="gmpSaveEditedMap"  >
						<span class="gmpIcon gmpIconSuccess"></span>
						<?php langGmp::_e("Save Map"); ?>
					</button>	
				</a>
			</li>
			
        </ul>
    </div>
    <div class='gmpNewMapForms'>
        <div class="gmpNewMapTabs tab-content">
         <div class="" id='newMapSubmitBtn'>
             <div class='gmpExistsMapOperations'>
                <div class='gmpMapOperationsMessages'>
                   
                   
					 <span class="editMapNameShowing text-info">Map: <span class='gmpEditingMapName text-default'></span></span>
                    <p id="gmpSaveEditedMapMsg"></p>
                </div>
                 
             </div>
            
              
           </div>
            <div class='tab-pane active' id="gmpEditMapProperties"><form></form>
	             <?php echo $this->mapForm; ?>
            </div>
           
            
            
            
            
            <div class='tab-pane' id="gmpEditMapMarkers">
				<?php echo $this->markerForm; ?>
            </div>
        </div>   
        
        
         <!-- Map Start -->
               <div class='gmpMapContainer'>

                <div class="clearfix"></div>
                  <div class='gmpDrawedNewMapOpts'>
                     
                  </div>
                  <div class='gmpNewMapPreview' id='gmpEditMapsContainer'></div>
                 
                  <div style='clear:both'></div>
				<div class='gmpUnderMapPic'>
					<div class='gmp-pic-title'>
						<h4><a target="_blank"  href="http://readyshoppingcart.com/product/google-maps-plugin/"><?php langGmp::_e("PRO version img ");?></a></h4>	
					</div>
					<div class='gmp-undermap-pic'>
						<a target="_blank"  href="http://readyshoppingcart.com/product/google-maps-plugin/">
							<img src='<?php echo GMP_IMG_PATH ;?>underMapPic.jpg' />
						</a>
					</div>	
				</div>	
                    <div class='gmpNewMapShortcodePreview'>
                        <pre class='gmpPre'></pre>
                    </div>    
              </div>
        
            <!-- Map End-->


    </div>
</div>    
   