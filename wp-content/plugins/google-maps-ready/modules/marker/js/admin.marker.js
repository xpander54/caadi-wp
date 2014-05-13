function gmpRemoveMarkerItem(markerId){
    var sendData ={
        mod     :   'marker',
        action  :   'removeMarker',
        reqType :   'ajax',
        id:   markerId
    }
    var respElem = jQuery("#gmpMarkerListTableLoader_"+markerId);
    jQuery.sendFormGmp({
        msgElID:"gmpMarkerListTableLoader_"+markerId,
        data:sendData,
        onSuccess:function(res){
            if(!res.error){
                setTimeout(function(){
                    jQuery("tr#markerRow_"+markerId).hide('500');
                },800);
                           
            }else{
                respElem.html(res.errors.join(","));
            }
        }
    })   
}
function gmpEditMarkerItem(markerId){
    gmpChangeTab(jQuery(".gmpMainTab li.gmpMarkerList a"));
    gmpCurrentMarkerForm = jQuery("#gmpEditMarkerForm");
    var refreshButton = gmpCurrentMarkerForm.find("a#gmpUpdateEditedMarker");
    var submitButton  = gmpCurrentMarkerForm.find("button#gmpSaveEditedMarkerItem");
    var removeBtn    =   gmpCurrentMarkerForm.find(".gmpDeleteMarker");
     //refreshButton.removeAttr("onclick");
     submitButton.removeAttr("onclick");
     removeBtn.removeAttr("onclick");
     //refreshButton.attr("onclick","gmpDrawEditedMarker("+markerId+")");
     submitButton.attr("onclick","return gmpSaveUpdatedMarker("+markerId+")");
     removeBtn.attr("onclick","return gmpDeleteMarker("+markerId+")");

    jQuery(".markerListConOpts").removeClass('active');
    jQuery(".gmpMarkerEditForm").addClass('active');
    
    
    if(typeof(gmpExistsMarkers) =="undefined"){
        return false;
    }
    var currentMarker;
    var currentMarkerForm=jQuery("#gmpEditMarkerForm")
   
    for(var i in gmpExistsMarkers){
        if(gmpExistsMarkers[i].id==markerId){
            currentMarker=gmpExistsMarkers[i];
        }
    }
    gmpCurrentMarkerForm.find("legend").html("Edit Marker : "+currentMarker.title);
    

    var formParams={
            gmpMarkerGroupOpt:currentMarker.marker_group_id,
            gmpMarkerTitleOpt:currentMarker.title,
            gmpMarkerAddressOpt:currentMarker.address,
            gmpMarkerCoordXOpt:currentMarker.coord_x,
            gmpMarkerCoordYOpt:currentMarker.coord_y,
            gmpMarkerTitleIsLinkOpt:(currentMarker.titleLink.linkEnabled?currentMarker.titleLink.link:false),
            gmpMarkerSelectedIconOpt:currentMarker.icon.id,
            marker_optsanimation:currentMarker.animation
    }

       gmpSetEditorContent(currentMarker.description,1)
    
    gmpDrawMap({
        mapContainerId:"gmpMapForMarkerEdit",
        options:{
            center:{
                lat     :   currentMarker.coord_y,
                lng     :   currentMarker.coord_x
            },
            zoom        :   15
        }
    })

    drawMarker({
        icon:currentMarker.icon,
        position:{
            coord_x:currentMarker.coord_x,
            coord_y:currentMarker.coord_y,
        },
        title:currentMarker.title,
        desc:currentMarker.description,
        id:currentMarker.id,
        group_id:currentMarker.marker_group_id,
        animation:currentMarker.animation,
        titleLink:currentMarker.titleLink
    })
    for(var selector in formParams){
        gmpAdminOpts.forms.gmpEditMarkerForm.params[selector].setVal(formParams[selector]);
    }
  
    gmpIsMapEditing.state=false;
    gmpIsMapEditing.markerData  = jQuery(gmpAdminOpts.forms.gmpEditMarkerForm.formObj).serialize();
    jQuery("#gmpEditMarkerForm").find("input,select,textarea").change(function(){
            gmpIsMapEditing.state = true;
    })
}
function gmpRefreshMarkerList(){
    var sendData ={
        mod     :   'marker',
        action  :   'refreshMarkerList',
        reqType :   'ajax'
    }
    jQuery("#GmpTableMarkers").remove();
    jQuery(".gmpMTablecon").addClass("gmpMapsTableListLoading");
    jQuery.sendFormGmp({
        msgElID:"",
        data:sendData,
        onSuccess:function(res){
            if(!res.error){
                jQuery(".gmpMTablecon").removeClass("gmpMapsTableListLoading")
                jQuery(".gmpMTablecon").html(res.html);
                datatables.reCreateTable("GmpTableMarkers");
            }else{
               
            }
        }
    })     
}
function gmpGetEditMarkerFormData(form){
    if(typeof(form)=='undefined'){
        form=jQuery("#gmpEditMarkerForm");
    }
    var params={
        goup_id     :   form.find("#gmp_marker_group").val(),
        title       :   form.find("#gmp_marker_title").val(),
        address     :   form.find("#gmp_marker_address").val(),
        desc        :   tinyMCE.activeEditor.getContent(),
        position    :{
                        coord_x     :   form.find("#gmp_marker_coord_x").val(),
                        coord_y     :   form.find("#gmp_marker_coord_y").val(),            
                    },
        animation   :   form.find("input[type='hidden'].marker_opts_animation").val()
    }
    
         params.titleLink={
               linkEnabled:false
         }
        if(form.find('.title_is_link').is(":checked")){
             params.titleLink.linkEnabled = true;
             params.titleLink.link = form.find(".marker_title_link").val();
        }
    
        params.icon={
             id:form.find("#gmpSelectedIcon").val()
            }
        try{
            params.icon.path=gmpExistsIcons[params.icon.id].path;
        }catch(e){
            //console.log(e);
        }
    
    return params;
}
function gmpDrawEditedMarker(markerId){
        var params = gmpGetEditMarkerFormData();
        var currentMarker = markerArr[markerId];
        updateMarker({
            icon:params.icon,
            id  :markerId,
            coord_x:params.position.coord_x,
            coord_y:params.position.coord_y,
            animation:params.animation,
            icon:params.icon.id,
            title:params.title,
            description:params.desc,
            group_id:params.group_id,
            titleLink:params.titleLink,
     },jQuery("#gmpEditMarkerForm"),true);    
}
function gmpSaveUpdatedMarker(markerId){
//    var params = gmpGetEditMarkerFormData();
    var params = gmpAdminOpts.getMarkerFormData("gmpEditMarkerForm");
    params.desc = gmpGetEditorContent(1);
    params.goup_id = params.group_id;
	var icon_id = params.icon;
	params.icon={
		id:icon_id
	} 
    params.id=markerId;
    params.address = gmpAdminOpts.forms.gmpEditMarkerForm.formObj.find(".gmpMarkerAddressOpt").val();
    var sendData={
                 mod            : "marker",
                 action         : "updateMarker",
                 reqType        : "ajax",
                 markerParams   : params
    }

    var respElem = jQuery("#gmpEditMarkerForm").find("#gmpUpdateMarkerItemMsg");
    jQuery.sendFormGmp({
        msgElID:respElem,
        data:sendData,
        onSuccess:function(res){
            if(res.error){
                respElem.html(res.errors.join(","));
            }else{
                //setTimeout(function(){
                   // jQuery(".markerListConOpts").toggleClass('active');
                   // clearMarkerForm(jQuery("#gmpEditMarkerForm"));
                   // gmpRefreshMarkerList();
                   // respElem.empty();
                //},800);
            }
        }
    })
    return false;
}
function cancelEditMarkerItem(params){
   
   
    clearMarkerForm(jQuery("#gmpEditMarkerForm"));
    gmpRefreshMarkerList();
     if(typeof(params)!="undefined" && params.changeTab){
    
    }else{
                 jQuery(".markerListConOpts").toggleClass('active');
    }
    return false;
}
function gmpDeleteMarker(markerId){
        var marker=markerArr[markerId];
        gmpRemoveMarkerObj(marker);
        cancelEditMarkerItem();
}
var gmpTypeInterval;                //timer identifier
var gmpDoneTypingInterval = 5000;  //time in ms, 5 second for example

jQuery(".gmp_marker_address").keydown(function(){
        clearTimeout(gmpTypeInterval);
        
})
jQuery(".gmp_marker_address").keyup(function(){
    var addr = jQuery(this).val();
    var form;
   if(jQuery(this).parents("form").attr("id")=="gmpEditMarkerForm"){
        form = jQuery("form#gmpEditMarkerForm");
    }
     gmpTypeInterval = setTimeout(function(){
          startSearchAddress(addr,form)
      },1200);
})
function gmpIsMarkerFormEditing(){
    if(gmpCurrentMarkerForm.find(".gmpMarkerTitleOpt").val()!=""){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".gmpMarkerTitleIsLinkOpt").prop("checked")){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".marker_optsanimation").prop("checked")){
        return true;
    }
    if(tinyMCE.activeEditor.getContent()!=""){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".gmpMarkerAddressOpt").val()!=""){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".gmpMarkerTitleOpt").val()!=""){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".gmpMarkerCoordXOpt").val()!=""){
        return true;
    }
    if(gmpCurrentMarkerForm.find(".gmpMarkerCoordYOpt").val()!=""){
        return true;
    }
    return false;  
}
function gmpAddNewMarker(param){

    if(gmpIsMarkerFormEditing()){
        if(confirm("Cancel Editind And Add New Marker")){
            clearMarkerForm(gmpCurrentMarkerForm);
        }
    }
}