<?php
class  iconsGmp extends moduleGmp {
    public function init(){
        parent::init();
        $gmpExistsIcons = frameGmp::_()->getModule("icons")->getModel()->getIcons();
		if(frameGmp::isAdminPlugPage()){
			frameGmp::_()->addJSVar('iconOpts', 'gmpExistsIcons', $gmpExistsIcons);
			frameGmp::_()->addScript('iconOpts', $this->getModPath() .'js/iconOpts.js');			
		}
    }
    
}