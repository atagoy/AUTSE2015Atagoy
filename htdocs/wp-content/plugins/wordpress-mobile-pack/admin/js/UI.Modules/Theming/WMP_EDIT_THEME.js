/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'EDIT DISPLAY MODE'		                                     */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_EDIT_THEME(){

    var JSObject = this;

    this.type = "wmp_edittheme";

    this.form;
    this.DOMDoc;

    this.send_btn;
    this.enableCustomSelects = 0;
	
	
	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                              FUNCTION INIT - called from WMPJSInterface                              */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.init = function(){

        // save a reference to WMPJSInterface Object
        WMPJSInterface = window.parent.WMPJSInterface;

        // save a reference to "SEND" Button
        this.send_btn = jQuery('#'+this.type+'_send_btn',this.DOMDoc).get(0);
       
        // save a reference to the FORM and remove the default submit action
        this.form = this.DOMDoc.getElementById(this.type+'_form');

        // add actions to send, cancel, ... buttons
        this.addButtonsActions();

        if (this.form == null){
            return;
        }
        
        this.initCustomSelects();
    }
    
    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION ENABLE CUSTOM SELECTS                                   */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.initCustomSelects = function(){

        if (this.enableCustomSelects == 1){
            
            jQuery("#" + JSObject.type + "_fontheadlines").selectBoxIt();
            jQuery("#" + JSObject.type + "_fontsubtitles").selectBoxIt();
            jQuery("#" + JSObject.type + "_fontparagraphs").selectBoxIt();
        }
    }
    

	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION ADD BUTTONS ACTIONS                                     */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.addButtonsActions = function(){

        /*******************************************************/
        /*                     SEND "BUTTON"                   */
        /*******************************************************/
        jQuery(this.send_btn).unbind("click");
        jQuery(this.send_btn).bind("click",function(){
            JSObject.disableButton(this);
            JSObject.sendData();
        })
        JSObject.enableButton(this.send_btn);

    }


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                 FUNCTION ENABLE BUTTON                                            */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.enableButton = function(btn){
        jQuery(btn).css('cursor','pointer');
        jQuery(btn).animate({opacity:1},100);
    }


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                 FUNCTION DISABLE BUTTON                                           */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.disableButton = function(btn){
        jQuery(btn).unbind("click");
        jQuery(btn).animate({opacity:0.4},100);
        jQuery(btn).css('cursor','default');
    }


    /*****************************************************************************************************/
	/*                                                                                                   */
	/*                       FUNCTION SUBMIT FORM  THROUGH an IFRAME as target                           */          
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.submitForm = function(){
		return WMPJSInterface.AjaxUpload.dosubmit(JSObject.form, {'onStart' : JSObject.startUploadingData, 'onComplete' : JSObject.completeUploadingData});
	}
	
	
	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                      FUNCTION SEND DATA                                           */          
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.sendData = function(){
		
		jQuery("#"+this.form.id,this.DOMDoc).unbind("submit");
		jQuery("#"+this.form.id,this.DOMDoc).bind("submit",function(){JSObject.submitForm();});
		jQuery("#"+this.form.id,this.DOMDoc).submit();
		
		JSObject.disableButton(JSObject.send_btn);
	}
	
	
	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                FUNCTION START UPLOADING DATA                                      */          
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.startUploadingData = function(){

		WMPJSInterface.Preloader.start();
		
		//disable form elements
		setTimeout(function(){
						var aElems = JSObject.form.elements;
						nElems = aElems.length;
						
						for (j=0; j<nElems; j++) {
							aElems[j].disabled = true;
						}
					},300);
		
		return true;
	}
	
	
	
	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                              FUNCTION COMPLETE UPLOADING DATA                                     */          
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.completeUploadingData = function(response){
		
		jQuery("#"+JSObject.form.id,JSObject.DOMDoc).unbind("submit");
		jQuery("#"+JSObject.form.id,JSObject.DOMDoc).bind("submit",function(){return false;});
	
		// remove preloader
		WMPJSInterface.Preloader.remove(100);
		
		response = Boolean(Number(String(response)));
  
		if (response == true){
			
            // show message
            var message = 'Your app has been successfully modified!';
            WMPJSInterface.Loader.display({message: message});
            
		} else {
			
			var message = 'There was an error. Please reload the page and try again.';
			WMPJSInterface.Loader.display({message: message});
		}

		//enable form elements
		setTimeout(function(){
						var aElems = JSObject.form.elements;
						nElems = aElems.length;
						for (j=0; j<nElems; j++) {
							aElems[j].disabled = false;
						}
					},300);
		
		//enable buttons
		JSObject.addButtonsActions();
		
	}

}