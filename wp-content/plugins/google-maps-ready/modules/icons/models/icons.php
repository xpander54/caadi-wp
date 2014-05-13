<?php
class iconsModelGmp extends modelGmp {
    public static $tableObj;
    function __construct() {
        if(empty(self::$tableObj)){
            self::$tableObj=  frameGmp::_()->getTable("icons");
        }
    }
    public function getIcons(){
        if(!get_option('gmp_def_icons_installed') ){
               $iconModule=$this->getModule('icons');  
               $iconModule->getController()->setDefaultIcons();
         }
        $icons =  frameGmp::_()->getTable('icons')->get('*');
        if( empty($icons) ){
              return $icons ;
        }
        $iconsArr=array();
        foreach($icons as $icon){
            $icon['path'] = $this->getIconUrl($icon['path']);
            $iconsArr[$icon['id']]=$icon;
        }
        return $iconsArr;
    }
    public function saveNewIcon($params){
        if(!isset($params['url'])){
            $this->pushError(langGmp::_("Icon no found"));
            return false;
        }
        $url = $params['url'];
        $exists = self::$tableObj->get("*","`path`='".$url."'");
        if(!empty($exists)){
            return $exists[0]['id'];
        }
        return self::$tableObj->insert(array('path'=>$url,'title'=>$params['title'],
                                            'description'=>$params['description']));
        
    }
    public function getIconsPath(){
        return 'icons_files/def_icons/';
    }
    public function getIconsFullDir(){
        $uplDir = wp_upload_dir();
        $modPath = $this->getModule()->getModPath();
        $path  = $modPath.$this->getIconsPath();
        return $path;
    }
    
    public function getIconsFullPath(){
        $uplDir = wp_upload_dir();
        $path = $uplDir['basedir'].$this->getIconsPath();
        return $path;
    }
    public function setDefaultIcons($icons){
        $uplDir = wp_upload_dir();
        if(!is_dir($uplDir['basedir'])){
            @mkdir($uplDir['basedir'],0777);
        }
        $icons_upload_path=$uplDir['basedir'].$this->getIconsPath();
        if(!is_dir($icons_upload_path)){
            @mkdir($icons_upload_path,0777);
        }
        $qItems = array();
        foreach($icons as $icon){
           $qItems[] = "('".$icon['title']."','".$icon['description']."','".$icon['img']."')";               
       }
       $query = "insert into `@__icons` (`title`,`description`,`path`) VALUES ".implode(",",$qItems);       
       dbGmp::query($query);
       update_option("gmp_def_icons_installed",true);
    }
    public function downloadIconFromUrl($url){
        $filename = basename($url);
        if(empty($filename)){
            $this->pushError(langGmp::_('File not found'));
            return false;
        }
        $imageinfo = getimagesize ( $url,$imgProp );
        if(empty($imageinfo)){
            $this->pushError(langGmp::_('Cannot get image'));
            return false;
        }
        $fileExt = str_replace("image/","",$imageinfo['mime']);    
        $filename = utilsGmp::getRandStr(8).".".$fileExt;
        $dest = $this->getIconsFullPath().$filename;
        file_put_contents($dest, fopen($url, 'r')); 
        $newIconId = frameGmp::_()->getTable('icons')->store(array('path'=>$filename),"insert");
        if($newIconId){
           return array('id'=>$newIconId,'path'=>$this->getIconsFullDir().$filename);            
        }else{
            $this->pushError(langGmp::_('cannot insert to table'));
            return false;
        }
    }
    public function getIconFromId($id){
        $res = frameGmp::_()->getTable('icons')->get("*",array('id'=>$id));
        if(empty($res)){
            return $res;
        }
        $icon =$res[0]; 
        $icon['path'] = $this->getIconUrl($icon['path']);
        return $icon;
    }
   function getIconUrl($icon){
     if(!empty($icon)){
         $isUrl = strpos($icon, "http");
         if($isUrl===false){
            return $this->getIconsFullDir().$icon;             
         }
     }
     return $icon;
   } 
}