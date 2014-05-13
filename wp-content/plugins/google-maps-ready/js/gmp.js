var gmpActiveTab={};
var gmpExistsTabs=["gmpAddNewMap",'gmpAllMaps','gmpMarkerList','gmpMarkerGroups','gmpPluginSettings'];
var  gmpMapConstructParams={
			 mapContainerId:"mapPreviewToNewMap"
	 };
var nochange = false;	 
 var def_tab_elem;

function gmpChangeTab(elem,sub){
	var tabId;
	try{
		tabId = elem.attr("href");
	}catch(e){
		tabId = elem;
	}
      //  tabId = "#" + tabId.split("#").join("");
	if(gmpActiveTab.mainmenu=="#gmpEditMaps" && tabId!="#gmpEditMaps" && sub==undefined){
		
		if(gmpIsMapEditing.mapData != jQuery(gmpAdminOpts.forms.gmpEditMapForm.formObj).serialize() ||
                        gmpIsMapEditing.markerData != jQuery(gmpAdminOpts.forms.gmpAddMarkerToEditMap.formObj).serialize())
        {
			//console.log("changed");
			if(!confirm("If you leave tab,changes will be lost. \n Leave Tab?")){
				return false;				
			}else{
				gmpIsMapEditing.state	=	false;
				gmpIsMapEditing.data	=	0;
				gmpCancelMapEdit({changeTab:false});
			}
		}
		
	}

	if(gmpActiveTab.mainmenu=="#gmpAddNewMap" && tabId!="#gmpAddNewMap" && sub==undefined){
		if(gmpIsMapFormIsEditing()){
			if(!confirm("If you leave tab,all information will be lost. \n Leave tab?")){
			   return false; 
			}else{
				clearAddNewMapData();
				clearMarkerForm();
			}
		}
	}

        if(gmpActiveTab.mainmenu=="#gmpMarkerList" && tabId !="#gmpMarkerList"){
            if(gmpAdminOpts.forms.gmpEditMarkerForm.formObj.is(":visible")){
                //console.log(gmpIsMapEditing.state )
                if(gmpIsMapEditing.state && (gmpAdminOpts.forms.gmpEditMarkerForm.formObj.serialize() != gmpIsMapEditing.markerData)){
                    if(!confirm("If you leave tab,changes will be lost.\n Leave Tab?")){
                        return false;
                    }else{
                        cancelEditMarkerItem({changeTab:false})
                    }
                }
                
            }
        }
        if(typeof(elem.tab) !='function'){
		 elem = jQuery('a[href$="'+tabId+'"]')	
	}
        if(tabId!="#gmpAddMarkerToNewMap" && gmpActiveTab.submenu=="#gmpAddMarkerToNewMap"){
                jQuery('a[href$="'+gmpActiveTab.submenu+'"]').find("button").attr("disabled","disabled");
        } 
	if(sub!= undefined){
	   gmpActiveTab.submenu=tabId; 
	}else{
            if(tabId=="#gmpAddNewMap"){
                gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
            }
	   gmpActiveTab.mainmenu=tabId;
	}
            
	console.log("changed to ", tabId);
        elem.tab("show");	
        elem.find("button").removeAttr("disabled");
	switch(tabId){
		case "#gmpAddNewMap":
			currentMap = gmpMapsArr['mapPreviewToNewMap'];
		break;
		case "#gmpEditMaps":
			currentMap = gmpMapsArr["gmpEditMapsContainer"];
		break;
		case "#gmpMarkerList":
			currentMap = gmpMapsArr["gmpMapForMarkerEdit"];
		break;
                case '#gmpAddMarkerToNewMap':
                       elem.find('button').removeAttr("disabled"); 
                break;    
	}
        jQuery(".gmpShowNewMapFormBtn").removeAttr("disabled");
        if(tabId=='#gmpAddNewMap'){
                if(jQuery(".gmpNewMapPreview").html() != undefined && 
                        jQuery(".gmpNewMapPreview").html().length<150){
                         
                         gmpDrawMap(gmpMapConstructParams);
                 
                }
         }
	
}	 

function gmp_func_get_args() {
  if (!arguments.callee.caller) {
	  return "";
  }
  return Array.prototype.slice.call(arguments.callee.caller.arguments);
}
function outGmp(){
	console.log(gmp_func_get_args());
}
function gmpGetEditorContent(editorId){
        if(typeof(editorId)=="undefined"){
            return tinyMCE.activeEditor.getContent();            
        }
      	return tinyMCE.editors[editorId].getContent();

}
function gmpSetEditorContent(content,editorId){
	if(content==""){
		content=" ";
	}
        if(typeof(editorId) == "undefined"){
            try{
                tinyMCE.activeEditor.setContent(content);            
            }catch(e){
                console.log(e);
            }            
        }else{
            try{
               tinyMCE.editors[editorId].setContent(content)
            }catch(e){
                console.log(e);
            }            

        }


}
jQuery(document).ready(function(){
	jQuery('.nav.nav-tabs.gmpMainTab a, ul.gmpNewMapOptsTab a').click(function (e) {
		e.preventDefault();
		var href = jQuery(this).attr("href");


		if(jQuery(this).parents('ul').hasClass("gmpMainTab")){
			 gmpChangeTab(jQuery(this));		 
		}else{
			gmpChangeTab(jQuery(this),true);
		}
	})
   
	jQuery(".gmpMapOptionsTab a").click(function(e){
			e.preventDefault();		
	})
	if(jQuery(".gmpNewMapPreview").length>0){
		if(jQuery(".gmpNewMapPreview").html().length<150){
            if(gmpActiveTab.mainmenu=="#gmpAddNewMap"){
                    gmpDrawMap(gmpMapConstructParams);					
            }
		}
	}

	
	jQuery(".gmpNewMapOptsTab a").click(function(e){
		jQuery(".gmpNewMapOptsTab a").removeClass("btn-primary");
		jQuery(this).addClass("btn-primary");
	})
	try{
		
		if(typeof(defaultOpenTab) !="undefined"){

			def_tab_elem = jQuery(".gmpMainTab  li."+defaultOpenTab).find('a');
			if(gmpExistsTabs.indexOf(defaultOpenTab) == -1){
					 def_tab_elem = jQuery(".gmpMainTab li."+gmpExistsTabs[0]).find('a')
			} 
		   gmpChangeTab(def_tab_elem); 
		}else{

			if(existsMapsArr.length>0){
				def_tab_elem = jQuery(".gmpMainTab li.gmpAllMaps").find('a')
				gmpChangeTab(def_tab_elem); 
			}
		}
	}catch(e){
			
	}
        jQuery(".gmpShowNewMapFormBtn").click(function(){
            var tabElem = "#gmpAddNewMap";

            gmpChangeTab(tabElem);
            jQuery(this).attr("disabled","disabled");
        })
})

function gmpFormatAddress(addressObj){

	var finishAddr=[];
	var count =0;
	var codes = ["street_address","route","administrative_area_level_1","country"];
	for(var i in addressObj){
		cur_addr= addressObj[i];
		switch(cur_addr.types[0]){
			case "neighborhood":
				if(cur_addr.types[1]=="political"){
						finishAddr.push(cur_addr.address_components[0].long_name);
				 }
			break;
			case "route":
			case "street_address":
					finishAddr.push(cur_addr.address_components[0].long_name);
					finishAddr.push(cur_addr.address_components[1].long_name);
			break;
			case "sublocalit":
				if(cur_addr.types[1]=="political"){
					finishAddr.push(cur_addr.address_components[0].long_name);
				}
			break;
			case "administrative_area_level_1":
				if(cur_addr.types[1]=="political"){
					finishAddr.push(cur_addr.address_components[0].long_name);
				} 
			break;
			case "locality":
				if(cur_addr.types[1]=="political"){

				}
			break;
			case "country":
				if(cur_addr.types[1]=="political"){
					finishAddr.push(cur_addr.address_components[0].long_name);
				}
			break;
		}
	}
	finishAddr = arrayUnique(finishAddr);
	return finishAddr.join(", ");
}
function getGmapMarkerAddress(params,markerId,ret,callback){
	var latlng = new google.maps.LatLng(params.coord_y,params.coord_x);
	geocoder.geocode({'latLng': latlng}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
			if(results.length>1){
				if(typeof(callback)!="undefined"){
					var fAddress=gmpFormatAddress(results) ;
					callback.func({
						address:fAddress,
						coord_x:params.coord_x,
						coord_y:params.coord_y
					});
					return;
				}else if(ret!=undefined){
					return results[1].formatted_address;
				}
				markerArr[markerId].address=results[1].formatted_address;
			}
		}else{
				markerArr[markerId].address="";			
		}
	});
}