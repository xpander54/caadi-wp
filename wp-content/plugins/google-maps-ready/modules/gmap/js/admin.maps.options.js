var currentMap;
var gmpTempMap;
var gmpMapsArr=[];
var gmpCurrentIcon=1;
var markerArr={}; 
var infoWindows=[];
var gmpNewMapOpts={};
var disableFormCheck=false;
var geocoder;
var markersToSend={};
var gmpDropDownObj={};
var gmpMapEditing=false;
var gmpMapForEdit;
var gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
var gmpAddMapForm ;
var gmpEditMapForm ;
var gmpIsMapEditing = {
	mapData:"",
	markerData:"",
	state:""
};
var datatables={
	tables:{
		"GmpTableGroups"	:   "#GmpTableGroups",
		"gmpMapsListTable"		 :   "#gmpMapsListTable",
		"GmpTableMarkers"   :   "#GmpTableMarkers"
	},
	createDatatables:function(){
		for(var i in this.tables){
			this.datatables[i] = jQuery(this.tables[i]).dataTable(this.default_options)
		}
	},
	reCreateTable:function(tableSelector){
		this.datatables[tableSelector] = jQuery(this.tables[tableSelector]).dataTable(this.default_options);
	},
	datatables:{},
	default_options:{	
					// "bJQueryUI": true,
			"iDisplayLength":7,
			"oLanguage": {
							"sLengthMenu": "Display _MENU_ Orders In Page",
							"sSearch": "Search:",
							"sZeroRecords": "Not found",
							"sInfo": "Show  _START_ to _END_ from _TOTAL_ records",
							"sInfoEmpty": "show 0 to 0 from 0 records",
							"sInfoFiltered": "(filtered from _MAX_ total records)",
						},
				"bProcessing": true ,
				"bPaginate": true,
				"sPaginationType": "full_numbers"
	 }
}
var gmpAdminOpts ={
	forms:{
		
	},
	getFormData :function(formId){
		if(typeof(this.forms[formId])=="undefined"){
			return false;
		}
		var res = {};
		for(var s in gmpAdminOpts.forms[''+formId].params){
			res[s]= gmpAdminOpts.forms[''+formId].params[s].getVal();
		}
		return res;
	},
	getMarkerFormData:function(formId){
		
		var data = this.getFormData(formId);
		
	
		var markerParams = {
			title	 	: data.gmpMarkerTitleOpt,
			desc	  	: data.description,
			group_id 	: data.gmpMarkerGroupOpt,
			animation	: data.marker_optsanimation,
			address		: data.gmpMarkerAddressOpt
 	
		};
		markerParams.titleLink={
			linkEnabled:false
		}
		if(data.gmpMarkerTitleIsLinkOpt){
			 markerParams.titleLink.linkEnabled = true;
			 markerParams.titleLink.link = data.gmpMarkerTitleIsLinkOpt;
		}
		
		markerParams.icon=data.gmpMarkerSelectedIconOpt;
		var lat = data.gmpMarkerCoordYOpt;
		var lng = data.gmpMarkerCoordXOpt;
		if(lat!="" && lng!=""){
			   markerParams.position={
					coord_x:parseFloat(lng),
					coord_y:parseFloat(lat)
				 }
		}
		return markerParams;
	},
	prepareMarkerDataToSet:function(data,type){
		if(type=="map_form"){
		}else{
			var params = {
				"gmpMarkerGroupOpt" :data.groupId,
				"gmpMarkerTitleOpt"	:data.title,
				"gmpMarkerAddressOpt":data.address,
				"gmpMarkerCoordXOpt":data.coord_x,	
				"gmpMarkerCoordYOpt":data.coord_y,	
				"gmpMarkerSelectedIconOpt":data.icon,
				"description":data.description,
			};
			if(typeof(data.titleLink)=="object"){
				params.gmpMarkerTitleIsLinkOpt = data.titleLink.link;
			}else{
				params.gmpMarkerTitleIsLinkOpt = "";			
			}	
			params.marker_optsanimation="";
			if(data.animation>1){
				params.marker_optsanimation=data.animation
			}
		}	
		return params;
	},
	getFormType:function(formId){
		if( formId=="gmpAddMarkerToNewForm" || formId=="gmpAddMarkerToEditMap" || 
				formId=="gmpEditMarkerForm" ){
					return "marker_form";
		}
		return "map_form";
	},
	convertKeys:function(data,revers){
				 
			var exists_keys ={
				"gmpMarkerGroupOpt":"groupId",
				"marker_group_id":"gmpMarkerGroupOpt",
				"gmpMarkerTitleOpt":"title",
				"gmpMarkerAddressOpt":"address",
				"gmpMarkerCoordXOpt":"coord_x",
				"gmpMarkerCoordYOpt":"coord_y",
				"gmpMarkerSelectedIconOpt":"icon",
				"description":"description",
				"gmpMapTitleOpts":"title",
				"gmpMapDescOpts":"desc",
				"gmpMapWidthOpt":"width",
				"gmpMapHeightOpt":"height",
				"gmpMapAlignOpt":"align",
				"gmpMapZoomOpts":"zoom",
				"gmpMapTypeOpt":"type",
				"gmpMapLngOpt":"language",		
				"gmpMapMarginOpt":"margin",
				"gmpMapBorderColorOpt":"border_color",		
				"gmpMapBorderWidthOpt":"border_width",
				"map_optsenable_zoom":"enable_zoom",		
				"map_optsenable_mouse_zoom":"enable_mouse_zoom",
				"gmpMapEnableMouseZoomOpts":"enable_mouse_zoom",
				"gmpMapEnableZoomOpts":"enable_zoom",
				"gmpMapInfoWindowHeightOpt":"infoWindowHeight",					
				"gmpMapInfoWindowWidthOpt":"infoWindowWidth"
			}
			var res =data;
			if(typeof(revers)=="undefined"){
                               
				for(var key in res){
					if(key in exists_keys){
						res[exists_keys[key]] = res[key];
                                                
						delete res[key];
					}
				}
				return res;				
			}
			for(var key in res){
				for(var ex_key in exists_keys){
					if(key==exists_keys[ex_key]){
						res[ex_key] = res[key];
						delete res[key];
					}
				}
			}

			return res;

	},
	construct:function(){
		var forms_list = {
			  "formElems" :{
					"map_form" :{
					"params":['gmpMapTitleOpts','gmpMapDescOpts','gmpMapWidthOpt','gmpMapHeightOpt',
								{"type"	  :   'hiddencheckbox',	"selector"  :   "gmpMapEnableZoomOpts"},
								{"type"	  :   "hiddencheckbox",	"selector"  :   "gmpMapEnableMouseZoomOpts"	},
						"gmpMapTypeOpt","gmpMapLngOpt","gmpMapAlignOpt","gmpMapZoomOpts","gmpMapMarginOpt",
						"gmpMapBorderColorOpt","gmpMapBorderWidthOpt","gmpMapInfoWindowHeightOpt",
						"gmpMapInfoWindowWidthOpt"
					  ]
				 },
				 "marker_form":{ 
						"params":["gmpMarkerGroupOpt","gmpMarkerTitleOpt","gmpMarkerAddressOpt",
									"gmpMarkerCoordXOpt","gmpMarkerCoordYOpt","gmpMarkerSelectedIconOpt",
									{"type":"is_link",	"selector":"gmpMarkerTitleIsLinkOpt"},
									{"type":"description"},
									{"type" : "hiddencheckbox",	"selector":"marker_optsanimation"},
					
								]
				 }
			   },
			 "forms":{
					  "marker_form":["gmpAddMarkerToNewForm","gmpAddMarkerToEditMap","gmpEditMarkerForm"],  
					  "map_form":["gmpAddNewMapForm","gmpEditMapForm"],  
					}
		} 
		for(var formType in forms_list.forms){
			 for(var j=0;j<forms_list.forms[formType].length;j++){
				var currentFormSelector = forms_list.forms[formType][j];
				var currentForm =jQuery("#"+currentFormSelector);
					gmpAdminOpts.forms[currentFormSelector] = {
						formObj:currentForm,
						params:{}
					}					 
				var currentFormElems = forms_list.formElems[formType].params;
					var elem_params ={
					} ;
				for(var k =0;k<currentFormElems.length;k++){
					var formElem = currentFormElems[k];
	  
				switch(typeof(formElem)){
					case "string":
						elem_params[formElem]={
							obj:currentForm.find("."+formElem),
							getVal:function(){
									return this.obj.val()
							},
							setVal:function(val){
									if(this.obj.hasClass("gmpMapBorderColorOpt")){
											this.obj.css("background-color",val);
									}
									if(this.obj.hasClass("gmpMapZoomOpts")){
											if(typeof(currentMap)!="undefined"){
													currentMap.setZoom(parseInt(val));
											}
									}
									return this.obj.val(val).trigger("change");
							}
						}
						break;
						case "object":
							if(formElem.type=="hiddencheckbox"){
								elem_params[formElem.selector]={
										obj:{
												'checkbox':currentForm.find("."+formElem.selector+"[type='checkbox']"),
												'input':currentForm.find("."+formElem.selector+"[type='hidden']"),
											
										},
										getVal:function(){
											
												return this.obj.input.val();
										},
										setVal:function(value){
										   this.obj.checkbox.prop("checked",Boolean(parseInt(value)));
										  
										   
										   this.obj.input.val(Number(value));
										  
										}
								}
							}else if(formElem.type=='is_link'){
									var sel = formElem.selector;
									elem_params[sel]={
											obj:{
													"checkbox":currentForm.find("input[type='checkbox']."+sel),
													"input":currentForm.find("input[type='text']."+sel),
											},
											setVal:function(val){
													if(val==""){
															this.obj.checkbox.prop("checked",false);
															this.obj.input.parents(".markerTitleLink_Container").hide();
													}else{
															this.obj.checkbox.prop("checked",true);
															this.obj.input.parents(".markerTitleLink_Container").show();
													}
													this.obj.input.val(val)
											},
											getVal:function(){
													if(this.obj.checkbox.prop("checked")){
															return this.obj.input.val();
													}
													return false;
											}
									}
							}else if(formElem.type=='description'){
									elem_params["description"]={
											setVal:function(value){
													gmpSetEditorContent(value);
											},
											getVal:function(){
													return gmpGetEditorContent();										
											}
									}
							}

						break;
						default:
						break;
				}
					gmpAdminOpts.forms[currentFormSelector].params=elem_params; 	
					gmpAdminOpts.forms[currentFormSelector].setFormData = function(formData){
						
						var data = gmpAdminOpts.prepareMarkerDataToSet(formData);
						for(var key in data ){
							this.params[""+key].setVal(data[key]);
						}
					}
				}
			}
		} 
	}  
		
}

function gmpIsMapFormIsEditing(){
	if(disableFormCheck){
		return true;
	}
	var items={
		title : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_title").val(),		
		desc  : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_description").val(),
		margin : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_margin").val(),
		bwidth : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_border_width").val(),
		mtitle : jQuery("#gmpAddMarkerToNewForm").find("#gmpNewMap_marker_title").val(),
		maddress : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_address").val(),
		mcoord_x : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_x").val(),
		mcoord_y : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_y").val(),
	}
		try{
			items.mdesc = gmpGetEditorContent();
		}catch(e){
			
		}
  
	for(var i in items){
	  if(items[i]!="" || items[i].length>0){
			return true;
	  }
	}
  return false;
}
function gmpClearMap(mapObj){
	if(typeof(mapObj)=="undefined"){
		mapObj = currentMap;
	}
   for(var i in markerArr){
		markersToSend[i]= markerArr[i];
		markersToSend[i].markerObj.setMap(null);
		markersToSend[i].markerObj=[];
		delete markerArr[i].markerObj;
		delete markerArr[i]["__e3_"];
	}
}
function getInfoWindow(title,content,titleLink){
		if(titleLink.linkEnabled && titleLink.linkEnabled!="false"){
			title= "<a href='"+titleLink.link+"' target='_blank' class='gmpInfoWIndowTitleLink'>"+title+"</a>"
		}
	var text="<div class='gmpMarkerInfoWindow'>";
		text+="<div class='gmpInfoWindowtitle'>"+title;
		text+="</div>";
		text+="<div class='gmpInfoWindowContent'>"+content;
		text+="</div></div>";
	return text;
}
function clearAddNewMapData(mapForm){
	if(mapForm==undefined){
		mapForm = jQuery("#gmpAddNewMapForm");
	}
	/*
	 * clear map form
	 */
	mapForm.clearForm();
	mapForm.find("#gmpNewMap_title").val("");
	mapForm.find("#gmpNewMap_description").val("");
	mapForm.find("#gmpNewMap_width").val("600");
	mapForm.find("#gmpNewMap_height").val("250");
	mapForm.find("#map_optsenable_zoom_check").trigger('click');
	
	mapForm.find("#map_optsenable_zoom_check").attr('checked',true);
	mapForm.find("#map_optsenable_zoom_text").val(1);
	mapForm.find("#map_optsenable_mouse_zoom_check").attr('checked',true);
	mapForm.find("#map_optsenable_mouse_zoom_text").val(1);
	 try{
		mapForm.find("#map_optsenable_zoom_em_check").attr('checked',true);
		mapForm.find("#map_optsenable_zoom_em_text").val(1);
		mapForm.find("#map_optsenable_mouse_zoom_em_check").attr('checked',true);
		mapForm.find("#map_optsenable_mouse_zoom_em_text").val(1);
	}catch(e){
		
	}
	mapForm.find("#gmpMap_zoom").val(8);	
	mapForm.find("#gmpMap_type").val('roadmap');	
	mapForm.find("#gmpMap_language").val('en');	
	mapForm.find("#gmpMap_align").val('top');	
	mapForm.find("input[name='map_opts[display_mode]']").each(function(){
		if(jQuery(this).val()=='map'){
			jQuery(this).attr('checked','checked');
		}
	})	
	 
	/*
	 * Clear marker form
	 */
	jQuery("#gmpAddMarkerToNewForm").clearForm();
	mapForm.find("#gmpNewMap_marker_group").val(1);
	markerArr	   =   new Object; 
	infoWindows	 =   new Array();
	gmpNewMapOpts   =   new Object;
	jQuery("#gmpNewMap_title").css('border','');
}

function gmpGetRandomid(){
	var num = Math.random();
	num=""+num;
	var rand=num.substr(10);
	return 'id'+rand;
}

function gmpDrawMap(params){
	if(params.options !=undefined){
		var lat = params.options.center.lat;
		var lng = params.options.center.lng;
		var map_zoom =parseInt(params.options.zoom);
	}else{
		var lat = 40.1879714881;
		var lng = 44.5234475708;
		var map_zoom = 1;
	}
	 var mapOptions = {
		  center: new google.maps.LatLng(lat,lng),
		  zoom: map_zoom,
		};
		if(typeof(params.mapTypeId)!="undefined"){
			mapOptions.mapTypeId=google.maps.MapTypeId[params.mapTypeId];
		}
		var map = new google.maps.Map(document.getElementById(params.mapContainerId),mapOptions);
		gmpTempMap = currentMap;
		currentMap = map;	
		google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
			 gmpAddLicenzeBlock(params.mapContainerId);
		});
		 
		google.maps.event.addListener(map, 'zoom_changed', function() {
			if(gmpAddMapForm.is(":visible")){
			   gmpAddMapForm.find(".gmpMap_zoom").val(map.getZoom())				
			}
			if(gmpEditMapForm.is(":visible")){
			   gmpEditMapForm.find(".gmpMap_zoom").val(map.getZoom())				
			}
		});
		gmpMapsArr[params.mapContainerId] = map;
		if(geocoder==undefined){
			 geocoder = new google.maps.Geocoder();
		}
	return map;
}
var bbg={};
function gmpEditMap(mapId){
	var editMap;

	if(typeof(existsMapsArr)=="undefined" || existsMapsArr.length<1){
		return false;
	}
	for(var i in existsMapsArr){
		if(existsMapsArr[i].id==mapId){
			editMap=existsMapsArr[i];
		}
	}
	jQuery(".gmpEditingMapName").html(editMap.title)
        jQuery(".gmpSaveEditedMapBtn#gmpSaveEditedMap").attr("current_map_id",mapId);
	jQuery(".gmpExistsMapOperations").show();
	jQuery("#gmpUpdateEditedMap").attr("onclick","gmpSaveEditedMap("+mapId+")")
	gmpChangeTab(jQuery('.nav.nav-tabs li.gmpEditMaps  a'));
	gmpMapEditing=true;
	markerArr = {};
	gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToEditMap");
	

	var mapParams ={
		gmpMapTitleOpts			:   editMap.title, 
		gmpMapDescOpts		   	:   editMap.description,
		gmpMapWidthOpt			:   editMap.html_options.width,
		gmpMapHeightOpt			:   editMap.html_options.height,
		gmpMapEnableZoomOpts	 	:   editMap.params.enable_zoom,
		gmpMapEnableMouseZoomOpts       :   editMap.params.enable_mouse_zoom,
		gmpMapZoomOpts			:   editMap.params.zoom,
		gmpMapTypeOpt			:   editMap.params.type,
		gmpMapLngOpt			:   editMap.params.language,
		gmpMapAlignOpt			:   editMap.html_options.align,
		gmpMapMarginOpt			:   editMap.html_options.margin,
		gmpMapBorderColorOpt		:   editMap.html_options.border_color,
		gmpMapBorderWidthOpt		:   editMap.html_options.border_width,
		gmpMapInfoWindowHeightOpt	:   editMap.params.infoWindowHeight,
		gmpMapInfoWindowWidthOpt	:   editMap.params.infoWindowWidth,
	}

		var newMapParams={
			mapContainerId:"gmpEditMapsContainer",
			options:{
				zoom:parseInt(editMap.params.zoom),
				center:{
					lat:editMap.params.map_center.coord_y,
					lng:editMap.params.map_center.coord_x
				},
				zoom	:parseInt(editMap.params.zoom)
			 },
			mapTypeId:editMap.params.type
		}

		var gmpMapForEdit={
				mapParams   :   editMap,
				mapObj	  :   gmpDrawMap(newMapParams)
		};
		for(var id in mapParams){
			gmpAdminOpts.forms.gmpEditMapForm.params[id].setVal(mapParams[id])
		}
		
		var mapMarkers = [];
		for(var i in editMap.markers){
			editMap.markers[i].group_id =editMap.markers[i].marker_group_id ;
			editMap.markers[i].position={
				coord_x:editMap.markers[i].coord_x,
				coord_y:editMap.markers[i].coord_y,
			};
			drawMarker(editMap.markers[i]);   
		}
		gmpIsMapEditing.state = false;
		gmpIsMapEditing.mapData  = jQuery(gmpAdminOpts.forms.gmpEditMapForm.formObj).serialize();
		gmpIsMapEditing.markerData  = jQuery(gmpAdminOpts.forms.gmpAddMarkerToEditMap.formObj).serialize();
		jQuery("#gmpEditMapForm").find("input,select,checkbox,textarea").change(function(){
			gmpIsMapEditing.state = true;
		})
		jQuery("#gmpAddMarkerToEditMap").find("input,select,checkbox,textarea,radio").change(function(){
			gmpIsMapEditing.state = true;
		})
                
}

function gmpSaveEditedMap(mapId){
	var mapNewParams = gmpAdminOpts.getFormData("gmpEditMapForm");
	var convertedParams = gmpAdminOpts.convertKeys(mapNewParams);
	if(convertedParams.title=='' || convertedParams.title.length<3){
			alert("Map Title must be at least 3 chars");
			jQuery(".gmpTabForMapOpts").trigger("click");
			jQuery(".gmpeditMap_title").css("border", "1px solid red");
		   return false;
	}
	
	for(var i in markerArr){
		delete markerArr[i].markerObj;
		if((markerArr[i].description =="" || typeof(markerArr[i].description)=="undefined")
						&& markerArr[i].desc!==""){
			markerArr[i].description = markerArr[i].desc;		
		}
		delete markerArr[i].desc;
	}
	convertedParams.id = mapId;
	convertedParams.map_center={
				   coord_x:currentMap.getCenter().lng(),
				   coord_y:currentMap.getCenter().lat(),
		};	

	 var sendData={
		markers:markerArr,
		mapOpts:convertedParams,
		mod	:'gmap',
		action  :'saveEditedMap',
		reqType :'ajax'
	}
		
	jQuery.sendFormGmp({
		msgElID: 'gmpSaveEditedMapMsg',
		data:sendData,
		onSuccess:function(res){
			gmpRefreshMarkerList();
			getMapsList(); 
		}
	})
	
	jQuery(".gmpeditMap_title").css("border", "");
         jQuery(".gmpSaveEditedMapBtn#gmpSaveEditedMap").removeAttr("onclick");
}
var paramObj;
function arrayUnique(param) {
	if(typeof(param.concat)=="undefined"){
	   return "";
	}
	var a = param.concat();
	for(var i=0; i<a.length; ++i) {
		for(var j=i+1; j<a.length; ++j) {
			if(a[i] === a[j])
				a.splice(j--, 1);
		}
	}
	if(a!="" || a!=" " || a!=","){
		return a;		
	}
};


function gmpRemoveMarkerObj(marker,formObj){
   	if(confirm("Remove Marker?")){
	   var sendData={
			id	 : marker.id,
			mod	: 'marker',
			action : 'removeMarker',
			reqType: 'ajax'
		}
		jQuery.sendFormGmp({
			data:sendData,
			onSuccess:function(res){
				if(res.error){
					alert(res.errors.join(","));
				}else{
					marker.markerObj.setMap(null);
					delete markerArr[marker.id];
										if(typeof(formObj)!="undefined"){
											clearMarkerForm(formObj);
										}
				}
			}
		})
   }
}
function drawMarker(params){
   var iconId;
   var mIcon;
	if(typeof params.icon =="object"){
		mIcon=params.icon.path;
		iconId=params.icon.id
	}else{
		 mIcon  = gmpExistsIcons[gmpCurrentIcon].path;   
		 iconId = gmpCurrentIcon;			
	}
	var  markerIcon = {
			url: mIcon,
			//size: new google.maps.Size(32, 37),
			origin: new google.maps.Point(0,0),
	};
	var markerTitle='New Marker';
	var markerDesc='';
	var markerLatLng ;
	if(params.position == undefined){
		 markerLatLng = currentMap.getCenter();			 
	}else{
		markerLatLng = new google.maps.LatLng(params.position.coord_y,params.position.coord_x);
	}
	if(params.title!=undefined){
		markerTitle=params.title;
	}else{
		markerTitle="New Marker";
	}
	if(params.desc!=undefined){
		markerDesc=params.desc;
	}else if(params.description!="undefined"){
		markerDesc=params.description;		
	}
	
	if(params.id==undefined || params.id==""){
	   var randId = gmpGetRandomid()+"";	   
	}else{
	   var randId = params.id
	}
	var animType = 2;
	if(params.animation==1){
		 animType = 1;
	}
	markerItem = {
		title		: markerTitle,
		description  : markerDesc,
		id		   : randId,
		coord_y	  : markerLatLng.lat(),
		coord_x	  : markerLatLng.lng(),
		icon		 : iconId,
		groupId	  : params.group_id,
		animation	: animType  ,
	};
		if(params.titleLink){
			markerItem.titleLink = params.titleLink;
		}else{
			markerItem.titleLink = {
				linkEnabled:false
			};			
		}
	if(params.address!='undefined'){
		markerItem.address = params.address; 
	}
	 markerArr[randId]=markerItem;
	 markerArr[randId].markerObj=new google.maps.Marker({
										position	:   markerLatLng,
										icon		:   markerIcon,
										draggable   :   true,
										map		 :   currentMap,
										title	   :   markerTitle,
										zIndex	  :   99999999,
										animation   :   animType,
										id		  :   randId
								   });
	if(typeof(params.address)=='undefined' ||  params.address==""){
		getGmapMarkerAddress(markerItem,randId);
	}
	 google.maps.event.addListener( markerArr[randId].markerObj, 'rightclick', function() {
		   gmpRemoveMarkerObj(markerArr[randId]); 
	 });
	google.maps.event.addListener(markerArr[randId].markerObj, 'dragend', function(e) {
		markerArr[this.id].coord_x=this.position.lng();
		markerArr[this.id].coord_y=this.position.lat();
		gmpChangeTab(gmpActiveTab.submenu,true)
		changeFormParams(this);
		editMarker(markerArr[randId]);
	});
	var countOfInfoWindows = infoWindows.length;	
		infoWindows[randId]= new google.maps.InfoWindow({
							  content   : getInfoWindow(markerTitle,markerDesc,markerItem.titleLink),
							  markerId  : randId 
							});
	google.maps.event.addListener(markerArr[randId].markerObj, 'click', function(){
			for(var i in infoWindows){
				if(typeof(infoWindows[i].close)!='undefined'){
				   infoWindows[i].close();			
			   }
			}
			if(typeof(infoWindows[randId].open)!='undefined'){
				infoWindows[randId].open(currentMap, markerArr[randId].markerObj);			
			}
			var href ="#gmpEditMapMarkers"
			if(gmpActiveTab.mainmenu=="#gmpAddNewMap"){
				href="#gmpAddMarkerToNewMap";
			} 
			gmpChangeTab(jQuery("a[href$='"+href+"']"),true)
			editMarker(markerArr[randId]);
			toggleBounce(markerArr[randId].markerObj,markerArr[randId].animation);
	});
	var bounds = new google.maps.LatLngBounds();
	for(var i in markerArr){
		var mLatLng = new google.maps.LatLng(markerArr[i].coord_y,markerArr[i].coord_x);
		 bounds.extend (mLatLng);	   
	}
  	currentMap.fitBounds (bounds);
	if(currentMap.getZoom()>19){
		currentMap.setZoom(18);
	}
		if(gmpAddMapForm.is(":visible")){
		   gmpAddMapForm.find(".gmpMap_zoom").val(currentMap.getZoom())				
		}
		if(gmpEditMapForm.is(":visible")){
		   gmpEditMapForm.find(".gmpMap_zoom").val(currentMap.getZoom())				
		}
	if(params.address=="undefined" ||  params.address==""){
		getGmapMarkerAddress({
			 coord_x:markerItem.coord_x,
			 coord_y:markerItem.coord_y
		  },markerItem.id);		
	}
        return randId;
}
function changeFormParams(markerObj){
	var newAddress= getGmapMarkerAddress({
		coord_y:markerObj.position.lat(),
		coord_x:markerObj.position.lng()
	},"",true,{
		func:function(params){
			if(typeof(params.address)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_address").val(params.address);
			}
			if(typeof(params.coord_x)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_coord_x").val(params.coord_x);
			}
			if(typeof(params.coord_y)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_coord_y").val(params.coord_y);
			}
		}
	});
 }
 
function editMarker(marker){

	var mapForm	  = jQuery("#gmpAddNewMapForm");
	var markerForm   = jQuery("#gmpAddMarkerToNewForm");
	var markerFormId = "gmpAddMarkerToNewForm";
	if(gmpMapEditing){
		mapForm	 =   jQuery("#gmpEditMapForm");
		markerForm  =   jQuery("#gmpAddMarkerToEditMap");
		markerFormId = "gmpAddMarkerToEditMap";
	}
           
        markerForm = gmpCurrentMarkerForm;
	gmpAdminOpts.forms[markerFormId].setFormData(marker);

	var removeBtn = markerForm.parents(".tab-pane.active").find(".removeMarkerFromForm");
        
        if(typeof(removeBtn)!='undefined'){
		removeBtn.attr('marker_id',marker.id);
	}	
	markerForm.find("#gmpEditedMarkerLocalId").val(marker.id);
//	markerForm.find(".gmpEditMarkerOpts").find("#gmpEditedMarkerLocalId").val(marker.id);
}

jQuery('#gmpMapHtmlOpts').submit(function(){
	gmpNewMapOpts.width	 =   jQuery(this).find('#gmpNewMap_width').val();
	gmpNewMapOpts.height	=   jQuery(this).find('#gmpNewMap_height').val();	
	gmpNewMapOpts.classname =   jQuery(this).find('#gmpNewMapClassname').val();
	gmpNewMapOpts.title	 =   jQuery(this).find('#gmpNewMap_title').val();
	gmpNewMapOpts.desc	 =   jQuery(this).find('#gmpNewMap_description').val();
	jQuery('.gmpOptsCon').hide(300);
	return false;
})
var newShortcodePreview;
jQuery("#gmpSaveNewMap").click(function(){
	jQuery("#gmpAddNewMapForm").trigger("submit");	
})
jQuery("#gmpSaveEditedMap.gmpSaveEditedMapBtn").click(function(){
        var map_id = jQuery(this).attr("current_map_id");
        gmpSaveEditedMap(map_id);
})

function getMapPropertiesFromForm(formObj){
	   if(formObj==undefined){
		   formObj=jQuery("#gmpAddNewMapForm");
		   var zoomId = "#map_optsenable_zoom_check";
		   var zoomMouseId = "#map_optsenable_mouse_zoom_check";
		   
	   } else{
		   var zoomId = "#map_optsenable_zoom_em_text";
		   var zoomMouseId = "#map_optsenable_mouse_zoom_em_text";		   
	   }
	 var gmpNewMapOpts={
				title			   :   formObj.find("#gmpNewMap_title").val(),
				desc				:   formObj.find("#gmpNewMap_description").val(),
				width			   :   formObj.find("#gmpNewMap_width").val(),
				height			  :   formObj.find("#gmpNewMap_height").val(),
				zoom				:   formObj.find("#gmpMap_zoom").val(),
				type				:   formObj.find("#gmpMap_type").val(),
				language			:   formObj.find("#gmpMap_language").val(),
				align			   :   formObj.find("#gmpMap_align").val(),
				margin			  :   formObj.find("#gmpNewMap_margin").val(),
				border_color		:   formObj.find(".map_border_color").val(),
				border_width		:   formObj.find("#gmpNewMap_border_width").val(),
	}
	
	if(formObj.attr('id')=="gmpAddNewMapForm"){
		gmpNewMapOpts.enable_zoom		 =   formObj.find("#map_optsenable_zoom_text").val();
		gmpNewMapOpts.enable_mouse_zoom   =   formObj.find("#map_optsenable_mouse_zoom_text").val();	   
	}else{
		gmpNewMapOpts.enable_zoom		 =   formObj.find("#map_optsenable_zoom_em_text").val();
		gmpNewMapOpts.enable_mouse_zoom   =   formObj.find("#map_optsenable_mouse_zoom_em_text").val();	   
	}
	
	
	gmpNewMapOpts.map_center={
			   coord_x:currentMap.getCenter().lng(),
			   coord_y:currentMap.getCenter().lat(),
	};			 
	gmpNewMapOpts['map_display_mode']="map";
	return gmpNewMapOpts;
}
jQuery("#gmpAddNewMapForm").submit(function(){
    
        
        
        var params = gmpAdminOpts.getFormData("gmpAddNewMapForm");
       //console.log(params)
		var converted = gmpAdminOpts.convertKeys(params);
		//console.log(converted);

		converted.map_center={
				   coord_x:currentMap.getCenter().lng(),
				   coord_y:currentMap.getCenter().lat(),
		};			 
		converted['map_display_mode']="map";

	   if(converted.title=="" || converted.title.length<3){
			jQuery("#gmpTabForNewMapOpts").trigger('click');
			jQuery("#gmpNewMap_title").css('border','1px solid red')
			alert("Map Title must be at least 3 chars");
			return false;
		}
	gmpClearMap();
	var sendData={
		markers:markerArr,
		mapOpts:converted,
		mod	:'gmap',
		action :'saveNewMap',
		reqType:'ajax'
	}

	jQuery.sendFormGmp({
	msgElID: 'gmpNewMapMsg',
		data:sendData,
		onSuccess: function(res) {
			if(!res.error){
			var newShortcodePreview  = "<pre class='gmpPre'>";
				newShortcodePreview += "<h3>New Shortcode</h3>";
				newShortcodePreview += "<h5>Paste this shortcode to preview created map in site</h5>";
				newShortcodePreview +=" [ready_google_map id='"+res.data.map_id+"']";
				newShortcodePreview += "</div></pre>";
				getMapsList({
							'id':res.data.map_id
						});
				gmpRefreshMarkerList();
				clearAddNewMapData();
			}
		}
	})
	return false;
})

function clearMarkerForm(markerForm){
	if(markerForm==undefined){
		var markerForm=jQuery("#gmpAddMarkerToNewForm");
	}
	markerForm.find("#gmpEditedMarkerLocalId").val("");
	markerForm.find("#gmpNewMap_marker_group").val(1);
	markerForm.find("#gmpNewMap_marker_title").val("");
	markerForm.find("#gmp_marker_address").val("");
		markerForm.find(".title_is_link").removeAttr("checked");
		markerForm.find(".markerTitleLink_Container").hide();
		markerForm.find(".marker_title_link").val("");
		
	try{
	 tinyMCE.activeEditor.setContent(" ");		
	}catch(e){
	}
	markerForm.find("#gmp_marker_coord_x").val("");
	markerForm.find("#gmp_marker_coord_y").val("");
	markerForm.find("#gmpIconUrlToDown").val("");
	markerForm.find("#marker_optsanimation_text").val("0");
	markerForm.find("#marker_optsanimation_check").removeAttr('checked');
	markerForm.find("#marker_optsanimation_check").removeAttr('checked');
	markerForm.find(".gmpAddressAutocomplete ul").empty();
}
function updateMarker(newParams,markerForm,leaveForm){
	 
          var currentMarker = markerArr[newParams.id];
	if(typeof(markerForm)=='undefined'){
	   var markerForm   = jQuery("#gmpAddMarkerToNewForm");		
	}		  
	if(gmpMapEditing){
		markerForm  =   jQuery("#gmpAddMarkerToEditMap");
	}
	if(newParams.animation==1){
		currentMarker.markerObj.animation=google.maps.Animation.BOUNCE
	}
         console.log(newParams,currentMarker);
          //debugger;
       // console.log(newParams,markerForm,leaveForm);
	currentMarker.animation=newParams.animation;
	for(var i in newParams){
		if(typeof(currentMarker.i)!=undefined){
			currentMarker[i]=newParams[i];
		} 
	}
	currentMarker.markerObj.setIcon(gmpExistsIcons[currentMarker.icon].path);
	currentMarker.markerObj.title  =  currentMarker.title;
	if(currentMarker.coord_x!="" && currentMarker.coord_y!=""){
	   currentMarker.markerObj.setPosition(new google.maps.LatLng(currentMarker.coord_y,currentMarker.coord_x));
	}
	markerArr[newParams.id]=currentMarker;
	for(var i in infoWindows){
		if(newParams.id==infoWindows[i]["markerId"]){
			infoWindows[i].setContent(getInfoWindow(newParams.title,newParams.desc,newParams.titleLink));
		}
	}
	if(typeof(leaveForm)=='undefined'){
		markerForm.find("#gmpEditedMarkerLocalId").val("");
		clearMarkerForm(markerForm);		
		markerForm.find(".gmpEditMarkerOpts").hide();
		markerForm.find(".gmpAddMarkerOpts").show();
	}
	var bounds = new google.maps.LatLngBounds();
	for(var i in markerArr){
		 var mLatLng = new google.maps.LatLng(markerArr[i].coord_y,markerArr[i].coord_x);
		  bounds.extend (mLatLng);	   
	}
	currentMap.fitBounds(bounds);
	if(currentMap.getZoom()>19){
		currentMap.setZoom(18);
	}
}
function afterMarkerFormSubmit(formId){
	var markerParams = gmpAdminOpts.getMarkerFormData(formId);
        if(markerParams.title.length<3){
            alert("Marker Title must be at 3 character at least");
            jQuery(".gmpMarkerTitleOpt").focus();
            return false;
        }
        clearMarkerForm(jQuery("form#"+formId));	
        var markerId = drawMarker(markerParams);	
	return false;
}
jQuery(document).ready(function(){
   
   
   gmpAdminOpts.construct();
   gmpAddMapForm =  jQuery("#gmpAddNewMapForm");
   gmpEditMapForm =  jQuery("#gmpEditMapForm");
   datatables.createDatatables();
   
   
	jQuery("#gmpHideNewMapPreview").click(function(){
		jQuery("#mapPreviewToNewMap").toggle();
		if(jQuery("#mapPreviewToNewMap").is(":visible")){
			jQuery("#gmpHideNewMapPreview").html("Hide Map Preview");
		}else{
			jQuery("#gmpHideNewMapPreview").html("Show Map Preview");
		}
	})
	jQuery("#gmpAddMarkerToNewForm").submit(function(){
             var edit_marker_id = jQuery(this).find("#gmpEditedMarkerLocalId").val();
                if(typeof(edit_marker_id)!="undefined"  &&  edit_marker_id !=""){
                    var markerNewParams = gmpAdminOpts.getMarkerFormData("gmpAddMarkerToNewForm");
                     markerNewParams.id = edit_marker_id;
                    if(markerNewParams.title==""){
                        alert("Marker title cannot be blank");
                        jQuery(".gmpMarkerTitleOpt").focus();
                        return false;
                    }
                    
                    updateMarker(markerNewParams);
                    return false;
                }else{
                    return afterMarkerFormSubmit("gmpAddMarkerToNewForm");
                }
		
	})
	jQuery("#gmpAddMarkerToEditMap").submit(function(){
                var edit_marker_id = jQuery(this).find("#gmpEditedMarkerLocalId").val();
                if(typeof(edit_marker_id)!="undefined"  &&  edit_marker_id !=""){
                    var form_id = jQuery(this).attr("id");
                    var markerNewParams = gmpAdminOpts.getMarkerFormData(form_id);
                     markerNewParams.id = edit_marker_id;
                     console.log(form_id,markerNewParams);
                     //debugger;
                    updateMarker(markerNewParams);
                    return false;
                }else{
                    return afterMarkerFormSubmit("gmpAddMarkerToEditMap");
                }
		
	})
        jQuery(".gmpAddSaveMarkerBtn").click(function(){
            var elem = jQuery(this).parents(".tab-pane.active").find("form.gmpMarkerFormItm");
            console.log(elem);
            elem.trigger("submit");
        })
	jQuery(".gmpCancelMarkerEditing").click(function(){
		var parentForm =gmpAdminOpts.forms.gmpEditMarkerForm.formObj;
		clearMarkerForm(parentForm);
		parentForm.find("#gmpEditedMarkerLocalId").val("");
                jQuery(".markerListConOpts").toggleClass("active")
	})
	
   jQuery("body").on("click","li.gmpAutoCompRes",function(){
	   var linkElem = jQuery(this).find('a.autoCompRes');
		var latlng=linkElem.attr('id').split("__");
		 if(latlng.length<2){
			return false;
		}
		var selectedAddress =linkElem.text();
		var currentForm = linkElem.parents('form');
		currentForm.find("#gmp_marker_coord_x").val(parseFloat(latlng[1]));
		currentForm.find("#gmp_marker_coord_y").val(parseFloat(latlng[0]));
		currentForm.find("#gmp_marker_address").val(selectedAddress);
		jQuery('.gmpAddressAutocomplete').hide();				
		return false;
	})  
	jQuery(document).click(function(e) { 
		  if(!jQuery(e.target).is('.gmpAddressAutocomplete')){
				if(jQuery(e.target).is(".gmp_marker_address")){
					jQuery('.gmpAddressAutocomplete').toggle();						
				}else{
				  jQuery('.gmpAddressAutocomplete').hide();				  
				}
			}
	})
   jQuery(".map_border_color").click(function(){
	   jQuery(this).val()==""?jQuery(this).val(" "):"";
   })
   
   jQuery(".title_is_link").change(function(){
	   if(this.checked ){
		   jQuery(this).parents(".gmpFormRow").find(".markerTitleLink_Container").show(100);
	   }else{
		   jQuery(this).parents(".gmpFormRow").find(".markerTitleLink_Container").hide(100);		   
	   }
   })
   jQuery(".removeMarkerFromForm").click(function(){
		 var marker= markerArr[jQuery(this).attr('marker_id')];
		 gmpRemoveMarkerObj(marker,jQuery(this).parents("form"));
                 clearMarkerForm();
                 jQuery(this).removeAttr("marker_id");
   })
   
	jQuery(".gmpMarkerSelectedIconOpt").on("change",function(){
		setcurrentIconToForm(jQuery(this).val(),jQuery(this).parents("form"))
	})
	jQuery(".gmpMapTitleOpts").focusout(function(){
		if(jQuery(this).val().length>=3){
			jQuery("#gmpSaveNewMap").removeAttr("disabled");
		}else{
			jQuery("#gmpSaveNewMap").attr("disabled","disabled");		
		}
	})

        
 
})

function gmpRemoveMap(mapId){
	if(!confirm("Remove Map?")){
		return false;
	}
	if(mapId==""){
		return false;
	}
	var sendData={
		map_id:mapId,
		mod	:'gmap',
		action :'removeMap',
		reqType:'ajax'
	}
	jQuery.sendFormGmp({
	msgElID: 'gmpRemoveElemLoader__'+mapId,
		data:sendData,
		onSuccess: function(res) {
			if(!res.error){
				 setTimeout(function(){
					 jQuery(".mapsTable").find('tr#map_row_'+mapId).hide('500');
					 jQuery(".mapsTable").find('tr#map_row_'+mapId).remove();
				 },500);   
			}
		}
	})
}
var resp=""
function getMapsList(showEdit){
	jQuery("#gmpMapsListTable").remove();
	jQuery("#gmpMapsListTable_wrapper").remove();
	jQuery(".gmpMapsContainer").addClass("gmpMapsTableListLoading");
	var sendData={
			mod	:'gmap',
			action :'getMapsList',
			reqType:'ajax'
	}
	jQuery.sendFormGmp({
		data:sendData,
		onSuccess: function(res) {
			if(!res.error){
			   jQuery(".gmpMapsContainer").removeClass("gmpMapsTableListLoading");				 
			   jQuery(".gmpMapsContainer").append(res.html);
				datatables.reCreateTable("gmpMapsListTable")  ;			 
			   if(showEdit!=undefined){
				   gmpEditMap(showEdit.id)
			   }
			}
		}
	})	
}
jQuery(".gmpMap_zoom").change(function(){
	var opts = {
		opt:'zoom',
		val:jQuery(this).val()
	}
	changeMap(opts);
})
jQuery(".gmpMap_type").change(function(){
	var opts = {
		opt:'type',
		val:jQuery(this).val()
	}
	changeMap(opts);
})
jQuery(".gmpMap_language").change(function(){
	var opts = {
		opt:'language',
		val:jQuery(this).val()
	}
	changeMap(opts);
})
jQuery("#map_optsenable_mouse_zoom_check").change(function(){
	if(jQuery(this).is(":checked")){
		var value=1;
	}else{
		var value=0;
	}
	var opts = {
		opt:'mouse_zoom_enable',
		val:value
	}
	changeMap(opts);
})
jQuery("#map_optsenable_zoom_check").change(function(){
	if(jQuery(this).is(":checked")){
		var value=1;
	}else{
		var value=0;
	}
	var opts = {
		opt:'zoom_enable',
		val:value
	}
	changeMap(opts);
})

function changeMap(params){
	var val =(params.val)?true:false;
	switch(params.opt){
		case "mouse_zoom_enable":
		   currentMap.setOptions({
			   disableDoubleClickZoom : !val,
			   scrollwheel			: val
		   });
		break;	
		case "zoom_enable":
		   currentMap.setOptions({
			   zoomControl:val 
		   })
		break;
		case "type":
		 currentMap.setMapTypeId(google.maps.MapTypeId[params.val]);
		break;
		case "zoom":
			if(typeof(currentMap)!="undefined"){
				currentMap.setZoom(parseInt(params.val));			
			}
		break;
	}
}
function drawAutocompleteResult(params,form){
	if(typeof(form)=='undefined'){
		form = gmpCurrentMarkerForm;
	}
	form.find(".gmpAddressAutocomplete ul").empty();
	form.find(".gmpAddressAutocomplete").slideDown();
	for(var i in params){
		var item="<li class='gmpAutoCompRes'><a class='autoCompRes' id='{position}'>{address}</a></li>";
		item = item.replace("{position}",params[i].position.lat+"__"+params[i].position.lng).replace("{address}",params[i].address);
		form.find(".gmpAddressAutocomplete ul").append(item);
	}
}
function startSearchAddress(address,form){
	if(address.length<3){
		return; 
	}	  
	var sendData={
		addressStr:address,
		mod	:'marker',
		action :'findAddress',
		reqType:'ajax'
	}
	jQuery.sendFormGmp({
		msgElID:gmpCurrentMarkerForm.find("#gmpAddressLoader"),
		data:sendData,
		onSuccess: function(res) {
				if(res.error){
				   jQuery(".gmpFormRow.gmpAddressField .gmpAddrErrors").html(res.errors.join(","));
				   return false;
				}
		   drawAutocompleteResult(res.data,form)
		}
	})	
}
	jQuery(".gmpAutocompleteArrow").click(function(){
			jQuery(this).parents("form").find(".gmpAddressAutocomplete").toggle();
		if(jQuery(this).hasClass('gmpDown')){
			jQuery(this).removeClass('gmpDown');
			jQuery(this).addClass('gmpUp');
		}else{
			jQuery(this).addClass('gmpDown');
			jQuery(this).removeClass('gmpUp');		
		}
	})

function gmpCancelMapEdit(params){
		clearMarkerForm(jQuery("#gmpAddMarkerToEditMap"));
		clearMarkerForm();
		clearAddNewMapData(jQuery("#gmpEditMapForm"));
		clearAddNewMapData();
		currentMap=gmpTempMap;
		gmpMapEditing=false;
		jQuery("#gmpEditMapsContainer").empty();
		gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
		markerArr={};	
		if(params!=undefined && !params.changeTab){
				return true;
		}
		gmpChangeTab(jQuery('.nav.nav-tabs li.gmpAddNewMap  a'));
}