<?php
class markerViewGmp extends viewGmp {
    public function showMarkersTab($markerList,$isAjaxRequest=false){
        $this->assign('markerList',$markerList);
        if($isAjaxRequest){
           return parent::getContent('markerTable');            
        }
        $this->assign("tableContent", parent::getContent('markerTable'));
		$markerForm = $this->getMarkerForm(array("page"=>'editMarker','formId'=>"gmpEditMarkerForm",
												  'formName'=>"addMarkerForm"));
		$this->assign("markerForm",$markerForm);
		return parent::getContent('markerList');             
    }
	public function getMarkerForm($params){
		$marker_opts=  frameGmp::_()->getModule('marker')->getModel()->constructMarkerOptions(); 
		$this->assign('marker_opts' , $marker_opts);
		$this->assign("params",$params);
		return parent::getContent("markerForm");
	}
}