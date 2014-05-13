<?php
class  markerGmp extends moduleGmp {
	public function init() {
		dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherGmp::addAction('tplHeaderBegin',array($this,'showFavico'));
		dispatcherGmp::addAction('tplBodyEnd',array($this,'GoogleAnalitics'));
		dispatcherGmp::addAction('in_admin_footer',array($this,'showPluginFooter'));
	}
	public function addOptionsTab($tabs){
		if(frameGmp::isAdminPlugPage()){
			frameGmp::_()->addScript('adminMetaOptions',$this->getModPath().'js/admin.marker.js',array(),false,true);			
		}
		return $tabs;
	}
}