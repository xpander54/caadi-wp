<?php
class marker_groupsControllerGmp extends controllerGmp {
     public function refreshGroupsList(){
        $markers = $this->getModel()->getMarkerGroups();
        $data = $this->getView()->showGroupsTab($markers,true);
        $res= new responseGmp();
        $res->setHtml($data);
        return $res->ajaxExec();
    }
    function saveGoup(){
        $data=  reqGmp::get("post");
        $res = new responseGmp();
        if(!isset($data['goupInfo'])){
            $res->pushError(langGmp::_("Nothing To Save"));
            return $res->ajaxExec();
        }
        if($this->getModel()->saveGroup($data['goupInfo'])){
            $res->addMessage(langGmp::_("Done"));
        }else{
            $res->pushError(langGmp::_("Cannot Save Group"));
        }
        return $res->ajaxExec();
    }
    public function removeGroup(){
        $params = reqGmp::get('post');
        $res =new responseGmp();
        if(!isset($params['group_id'])){
            $res->pushError(langGmp::_("Group Not Found"));
            return $res->ajaxExec();
        }    
        if($this->getModel()->removeGroup($params["group_id"])){
           $res->addMessage(langGmp::_("Done")); 
        }else{
            $res->pushError(langGmp::_("Cannot remove group"));
        }
        frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("group.delete");
        return $res->ajaxExec();
    }
}