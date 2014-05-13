<div class="gmpMarkerListTable markerListConOpts tab-pane active">
    <div class='gmpMarkerListsOPerations'>
        <a class='btn btn-success' onclick="gmpRefreshMarkerList()">
            <span class='gmpIcon gmpIconRefresh'></span>
            <?php langGmp::_e("Refresh")?>
        </a>
     </div>
    <div class='gmpMTablecon'>    
        <?php
            echo @$this->tableContent;
        ?>
    </div> 
 </div>
<div class='gmpMarkerEditForm tab-pane markerListConOpts'>

        <?php echo $this->markerForm; ?>
		<div class="return-marker-list">
			<a class="btn btn-link gmpCancelMarkerEditing" id="gmpCancelMarkerEditing">
				<?php langGmp::_e("Back To Markers List")?>
			</a>	
		</div>	
		<div class='gmp-marker-right-block'>
			<div class='gmpMapForMarkerEdit' id='gmpMapForMarkerEdit'>
				
			</div>
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
		</div>
	
				
</div>
     