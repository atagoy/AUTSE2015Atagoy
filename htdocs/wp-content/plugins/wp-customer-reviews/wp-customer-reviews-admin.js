jQuery(document).ready(function() {
    
    jQuery("#comments-form").submit(function(e) {
       var hasbip = jQuery(this).find('input.bip');
       if (hasbip.size() > 0) {
           return false;
       }
    });
    
    jQuery(".best_in_place").best_in_place();
    
    jQuery("#require_fname, #show_fname").click(function(){
        if ( jQuery(this).is(":checked") ) {
            jQuery("#ask_fname").attr('checked','checked');
        }
		else if ( jQuery("#ask_fname").not(":checked") ) {
			jQuery("#show_fname").removeAttr('checked');
			jQuery("#require_fname").removeAttr('checked');
		}
    });
    
    jQuery("#require_femail, #show_femail").click(function(){
        if ( jQuery(this).is(":checked") ) {
            jQuery("#ask_femail").attr('checked','checked');
        }
		else if ( jQuery("#ask_femail").not(":checked") ) {
			jQuery("#show_femail").removeAttr('checked');
			jQuery("#require_femail").removeAttr('checked');
		}
    });
    
    jQuery("#require_fwebsite, #show_fwebsite").click(function(){
        if ( jQuery(this).is(":checked") ) {
            jQuery("#ask_fwebsite").attr('checked','checked');
        }
		else if ( jQuery("#ask_fwebsite").not(":checked") ) {
			jQuery("#show_fwebsite").removeAttr('checked');
			jQuery("#require_fwebsite").removeAttr('checked');
		}
    });
    
    jQuery("#require_ftitle, #show_ftitle").click(function(){
        if ( jQuery(this).is(":checked") ) {
            jQuery("#ask_ftitle").attr('checked','checked');
        }
		else if ( jQuery("#ask_ftitle").not(":checked") ) {
			jQuery("#show_ftitle").removeAttr('checked');
			jQuery("#require_ftitle").removeAttr('checked');
		}
    });
	
	jQuery("#ask_fname, #ask_femail, #ask_fwebsite, #ask_ftitle").click(function(){
		if ( jQuery(this).not(":checked") ) {
			var datawhat = jQuery(this).attr('data-what');
			jQuery("#show_"+datawhat).removeAttr('checked');
			jQuery("#require_"+datawhat).removeAttr('checked');
		}
	});

	jQuery(".custom_req, .custom_show").each(function(){
		jQuery(this).click(function(){
			var dataid = jQuery(this).attr('data-id');
			
			if ( jQuery(this).is(":checked") ) {
				jQuery("#ask_custom"+dataid).attr('checked','checked');
			}
			else if ( jQuery("#ask_custom"+dataid).not(":checked") ) {
				jQuery("#show_custom"+dataid).removeAttr('checked');
				jQuery("#require_custom"+dataid).removeAttr('checked');
			}
		});
	});
	
	jQuery(".custom_ask").click(function(){
		if ( jQuery(this).not(":checked") ) {
			var dataid = jQuery(this).attr('data-id');
			jQuery("#show_custom"+dataid).removeAttr('checked');
			jQuery("#require_custom"+dataid).removeAttr('checked');
		}
	});
});

function wpcr_strip_tags(html){
 
	//PROCESS STRING
	if(arguments.length < 3) {
		html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
	} else {
		var allowed = arguments[1];
		var specified = eval("["+arguments[2]+"]");
		if(allowed){
			var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
			html=html.replace(new RegExp(regex, 'gi'), '');
		} else{
			var regex='</?(' + specified.join('|') + ')\b[^>]*>';
			html=html.replace(new RegExp(regex, 'gi'), '');
		}
	}

	//CHANGE NAME TO CLEAN JUST BECAUSE 
	var clean_string = html;

	//RETURN THE CLEAN STRING
	return clean_string;
}

function wpcr_nl2br(str)
{
	return str.replace(/(\r|\n|\r\n)/ig, "<br />");
}

function wpcr_br2nl(str)
{
	return str.replace(/(<br \/>|<br>|<br >|<br\/>|<p>)/ig, "\r\n");
}

function callback_review_text(me) {
	var mehtml = jQuery(me).html();
	mehtml = wpcr_nl2br(mehtml);
	jQuery(me).html(mehtml);
}

function make_stars_from_rating(me) {
    
    var w = '';
    
    switch (me.html()) {
        case 'Rated 1 Star':
            w = '20';
            break;
        case 'Rated 2 Stars':
            w = '40';
            break;
        case 'Rated 3 Stars':
            w = '60';
            break;
        case 'Rated 4 Stars':
            w = '80';
            break;
        case 'Rated 5 Stars':
            w = '100';
            break;
    }
    
    var out = '<div class="sp_rating"><div class="base"><div class="average" style="width:'+w+'%"></div></div></div>';
    me.html(out);
}

/**
 * jquery.purr.js
 * Copyright (c) 2008 Net Perspective (net-perspective.com)
 * Licensed under the MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author R.A. Ray
 * @projectDescription  jQuery plugin for dynamically displaying unobtrusive messages in the browser. Mimics the behavior of the MacOS program "Growl."
 * @version 0.1.0
 *
 * @requires jquery.js (tested with 1.2.6)
 *
 * @param fadeInSpeed           int - Duration of fade in animation in miliseconds
 *                          default: 500
 *  @param fadeOutSpeed         int - Duration of fade out animationin miliseconds
                            default: 500
 *  @param removeTimer          int - Timeout, in miliseconds, before notice is removed once it is the top non-sticky notice in the list
                            default: 4000
 *  @param isSticky             bool - Whether the notice should fade out on its own or wait to be manually closed
                            default: false
 *  @param usingTransparentPNG  bool - Whether or not the notice is using transparent .png images in its styling
                            default: false
 */

(function($) {

  $.purr = function(notice, options)
  {
    // Convert notice to a jQuery object
    notice = $(notice);

    // Add a class to denote the notice as not sticky
    notice.addClass('purr');

    // Get the container element from the page
    var cont = document.getElementById('purr-container');

    // If the container doesn't yet exist, we need to create it
    if (!cont)
    {
      cont = '<div id="purr-container"></div>';
    }

    // Convert cont to a jQuery object
    cont = $(cont);

    // Add the container to the page
    $('body').append(cont);

    notify();

    function notify ()
    {
      // Set up the close button
      var close = document.createElement('a');
      $(close).attr({
          className: 'close',
          href: '#close'
          }).appendTo(notice).click(function() {
              removeNotice();
              return false;
          });

      // If ESC is pressed remove notice
      $(document).keyup(function(e) {
        if (e.keyCode == 27) {
          removeNotice();
        }
      });

      // Add the notice to the page and keep it hidden initially
      notice.appendTo(cont).hide();

      if (jQuery.browser.msie && options.usingTransparentPNG)
      {
        // IE7 and earlier can't handle the combination of opacity and transparent pngs, so if we're using transparent pngs in our
        // notice style, we'll just skip the fading in.
        notice.show();
      }
      else
      {
        //Fade in the notice we just added
        notice.fadeIn(options.fadeInSpeed);
      }

      // Set up the removal interval for the added notice if that notice is not a sticky
      if (!options.isSticky)
      {
        var topSpotInt = setInterval(function() {
          // Check to see if our notice is the first non-sticky notice in the list
          if (notice.prevAll('.purr').length == 0)
          {
            // Stop checking once the condition is met
            clearInterval(topSpotInt);

            // Call the close action after the timeout set in options
            setTimeout(function() {
                removeNotice();
              }, options.removeTimer);
          }
        }, 200);
      }
    }

    function removeNotice()
    {
      // IE7 and earlier can't handle the combination of opacity and transparent pngs, so if we're using transparent pngs in our
      // notice style, we'll just skip the fading out.
      if (jQuery.browser.msie && options.usingTransparentPNG)
      {
        notice.css({ opacity: 0 }).animate({ height: '0px'},
            {
              duration: options.fadeOutSpeed,
              complete: function ()
              {
                notice.remove();
              }
            }
          );
      }
      else
      {
        // Fade the object out before reducing its height to produce the sliding effect
        notice.animate({ opacity: '0' },
          {
            duration: options.fadeOutSpeed,
            complete: function ()
              {
                notice.animate({ height: '0px' },
                  {
                    duration: options.fadeOutSpeed,
                    complete: function()
                      {
                        notice.remove();
                      }
                  }
                );
              }
          }
        );
      }
    };
  };

  $.fn.purr = function(options)
  {
    options = options || {};
    options.fadeInSpeed = options.fadeInSpeed || 500;
    options.fadeOutSpeed = options.fadeOutSpeed || 500;
    options.removeTimer = options.removeTimer || 4000;
    options.isSticky = options.isSticky || false;
    options.usingTransparentPNG = options.usingTransparentPNG || false;

    this.each(function()
      {
        new $.purr( this, options );
      }
    );

    return this;
  };
})( jQuery );

/*
    BestInPlace (for jQuery)
    version: 0.1.9 (02/12/2011)
    @requires jQuery >= v1.4
    @requires jQuery.purr to display pop-up windows

    By Bernat Farrero based on the work of Jan Varwig.
    Examples at http://bernatfarrero.com

    Licensed under the MIT:
      http://www.opensource.org/licenses/mit-license.php

    Usage:

    Attention.
    The format of the JSON object given to the select inputs is the following:
    [["key", "value"],["key", "value"]]
    The format of the JSON object given to the checkbox inputs is the following:
    ["falseValue", "trueValue"]
*/

function BestInPlaceEditor(e) {
  this.element = jQuery(e);
  this.initOptions();
  this.bindForm();
  this.initNil();
  jQuery(this.activator).bind('click', {editor: this}, this.clickHandler);
}

BestInPlaceEditor.prototype = {
  // Public Interface Functions //////////////////////////////////////////////

  activate : function() {
    var elem = this.isNil ? "" : this.element.html();
    this.oldValue = elem;
    jQuery(this.activator).unbind("click", this.clickHandler);
    this.activateForm();
  },

  abort : function() {
    if (this.isNil) this.element.html(this.nil);
    else            this.element.html(this.oldValue);
    jQuery(this.activator).bind('click', {editor: this}, this.clickHandler);
    if (this.callback != '') { window[this.callback](this.element); } /* AQ */
  },

  update : function() {
    var editor = this;
    if (this.formType in {"input":1, "textarea":1} && this.getValue() == this.oldValue)
    { // Avoid request if no change is made
      this.abort();
      return true;
    }
    this.isNil = false;
    editor.ajax({
      "type"       : "post",
      "dataType"   : "text",
      "data"       : editor.requestData(),
      "success"    : function(data){ editor.loadSuccessCallback(data); },
      "error"      : function(request, error){ editor.loadErrorCallback(request, error); }
    });
    if (this.formType == "select") {
      var value = this.getValue();
      jQuery.each(this.values, function(i, v) {
        if (value == v[0]) {
          editor.element.html(v[1]);
        }
      }
    );
    } else if (this.formType == "checkbox") {
      editor.element.html(this.getValue() ? this.values[1] : this.values[0]);
    } else {
      editor.element.html(this.getValue());
    }
  },

  activateForm : function() {
    alert("The form was not properly initialized. activateForm is unbound");
  },

  // Helper Functions ////////////////////////////////////////////////////////

  initOptions : function() {
    // Try parent supplied info
    var self = this;
    self.element.parents().each(function(){
      self.url           = self.url           || jQuery(this).attr("data-url");
      self.collection    = self.collection    || jQuery(this).attr("data-collection");
      self.formType      = self.formType      || jQuery(this).attr("data-type");
      self.objectName    = self.objectName    || jQuery(this).attr("data-object");
      self.attributeName = self.attributeName || jQuery(this).attr("data-attribute");
      self.nil           = self.nil           || jQuery(this).attr("data-nil");
      self.callback      = self.callback      || jQuery(this).attr("data-callback");
    });

    // Try Rails-id based if parents did not explicitly supply something
    self.element.parents().each(function(){
      var res = this.id.match(/^(\w+)_(\d+)$/i);
      if (res) {
        self.objectName = self.objectName || res[1];
      }
    });

    // Load own attributes (overrides all others)
    self.url           = self.element.attr("data-url")          || self.url      || document.location.pathname;
    self.collection    = self.element.attr("data-collection")   || self.collection;
    self.formType      = self.element.attr("data-type")         || self.formtype || "input";
    self.objectName    = self.element.attr("data-object")       || self.objectName;
    self.attributeName = self.element.attr("data-attribute")    || self.attributeName;
    self.activator     = self.element.attr("data-activator")    || self.element;
    self.nil           = self.element.attr("data-nil")          || self.nil      || "----------";
    self.callback      = self.element.attr("data-callback")     || self.callback || "";

    if (!self.element.attr("data-sanitize")) {
      self.sanitize = true;
    }
    else {
      self.sanitize = (self.element.attr("data-sanitize") == "true");
    }

    if ((self.formType == "select" || self.formType == "checkbox") && self.collection !== null)
    {
      self.values = jQuery.parseJSON(self.collection);
    }
  },

  bindForm : function() {
    this.activateForm = BestInPlaceEditor.forms[this.formType].activateForm;
    this.getValue     = BestInPlaceEditor.forms[this.formType].getValue;
  },

  initNil: function() {
    if (this.element.html() == "")
    {
      this.isNil = true
      this.element.html(this.nil)
    }
  },

  getValue : function() {
    alert("The form was not properly initialized. getValue is unbound");
  },

  // Trim and Strips HTML from text
  sanitizeValue : function(s) {
    if (this.sanitize)
    {	
	  var news = wpcr_br2nl(s);
	  return jQuery.trim(wpcr_strip_tags(s));
    }
    return jQuery.trim(s);
  },

  /* Generate the data sent in the POST request */
  requestData : function() {
    // To prevent xss attacks, a csrf token must be defined as a meta attribute
    csrf_token = jQuery('meta[name=csrf-token]').attr('content');
    csrf_param = jQuery('meta[name=csrf-param]').attr('content');

    var data = "_method=put";
    data += "&" + this.objectName + '[' + this.attributeName + ']=' + encodeURIComponent(this.getValue());

    if (csrf_param !== undefined && csrf_token !== undefined) {
      data += "&" + csrf_param + "=" + encodeURIComponent(csrf_token);
		}
    return data;
  },

  ajax : function(options) {
    options.url = this.url;
    options.beforeSend = function(xhr){ xhr.setRequestHeader("Accept", "application/json"); };
    return jQuery.ajax(options);
  },

  // Handlers ////////////////////////////////////////////////////////////////

  loadSuccessCallback : function(data) {
    this.element.html(data[this.objectName]);
    // Binding back after being clicked
    jQuery(this.activator).bind('click', {editor: this}, this.clickHandler);
    if (this.callback != '') { window[this.callback](this.element); } /* AQ */
  },

  loadErrorCallback : function(request, error) {
    this.element.html(this.oldValue);

    // Display all error messages from server side validation
    jQuery.each(jQuery.parseJSON(request.responseText), function(index, value) {
      var container = jQuery("<span class='flash-error'></span>").html(value);
      container.purr();
    });

    // Binding back after being clicked
    jQuery(this.activator).bind('click', {editor: this}, this.clickHandler);
    if (this.callback != '') { window[this.callback](this.element); } /* AQ */
  },

  clickHandler : function(event) {
    event.data.editor.activate();
  }
};


BestInPlaceEditor.forms = {
  "input" : {
    activateForm : function() {
      var output = '<form class="form_in_place" action="javascript:void(0)" style="display:inline;">';
      output += '<input class="bip" type="text" value="' + this.sanitizeValue(this.oldValue) + '"></form>';
      this.element.html(output);
      this.element.find('input')[0].select();
      this.element.find("form").bind('submit', {editor: this}, BestInPlaceEditor.forms.input.submitHandler);
      this.element.find("input").bind('blur',   {editor: this}, BestInPlaceEditor.forms.input.inputBlurHandler);
      this.element.find("input").bind('keyup', {editor: this}, BestInPlaceEditor.forms.input.keyupHandler);
    },

    getValue :  function() {
      return this.sanitizeValue(this.element.find("input").val());
    },

    inputBlurHandler : function(event) {
      event.data.editor.update();
    },

    submitHandler : function(event) {
      event.data.editor.update();
      return false;
    },

    keyupHandler : function(event) {
      if (event.keyCode == 27) {
        event.data.editor.abort();
      }
      
      if (event.keyCode == 10 || event.keyCode == 13) {
          event.data.editor.update();
      }
    }
  },

  "select" : {
    activateForm : function() {
      var output = "<form action='javascript:void(0)' style='display:inline;'><select>";
      var selected = "";
      var oldValue = this.oldValue;
      jQuery.each(this.values, function(index, value) {
        selected = (value[1] == oldValue ? "selected='selected'" : "");
        output += "<option value='" + value[0] + "' " + selected + ">" + value[1] + "</option>";
       });
      output += "</select></form>";
      this.element.html(output);
      this.element.find("select").bind('change', {editor: this}, BestInPlaceEditor.forms.select.blurHandler);
      this.element.find("select").bind('blur', {editor: this}, BestInPlaceEditor.forms.select.blurHandler);
      this.element.find("select").bind('keyup', {editor: this}, BestInPlaceEditor.forms.select.keyupHandler);
      this.element.find("select")[0].focus();
    },

    getValue : function() {
      return this.sanitizeValue(this.element.find("select").val());
    },

    blurHandler : function(event) {
      event.data.editor.update();
    },

    keyupHandler : function(event) {
      if (event.keyCode == 27) event.data.editor.abort();
    }
  },

  "checkbox" : {
    activateForm : function() {
      var newValue = Boolean(this.oldValue != this.values[1]);
      var output = newValue ? this.values[1] : this.values[0];
      this.element.html(output);
      this.update();
    },

    getValue : function() {
      return Boolean(this.element.html() == this.values[1]);
    }
  },

  "textarea" : {
    activateForm : function() {
      // grab width and height of text
      width = this.element.css('width');
      height = this.element.css('height');
	  
      // construct the form
      var output = '<form action="javascript:void(0)" style="display:inline;"><textarea>';
      //output += this.sanitizeValue(this.oldValue); /* fix for IE 8 issues */
	  output += wpcr_br2nl(this.oldValue); /* fix for IE 8 issues */
      output += '</textarea></form>';
      this.element.html(output);

      // set width and height of textarea
      jQuery(this.element.find("textarea")[0]).css({ 'min-width': width, 'min-height': height });
      jQuery(this.element.find("textarea")[0]).elastic();

      this.element.find("textarea")[0].focus();
      this.element.find("textarea").bind('blur', {editor: this}, BestInPlaceEditor.forms.textarea.blurHandler);
      this.element.find("textarea").bind('keyup', {editor: this}, BestInPlaceEditor.forms.textarea.keyupHandler);
    },

    getValue :  function() {
	  //var sanval = this.sanitizeValue(this.element.find("textarea").val());
	  var sanval = this.sanitizeValue(this.element.find("textarea").val());
	  sanval = wpcr_nl2br(sanval);
      return sanval;
    },

    blurHandler : function(event) {
      event.data.editor.update();
    },

    keyupHandler : function(event) {
      if (event.keyCode == 27) {
        BestInPlaceEditor.forms.textarea.abort(event.data.editor);
      }
    },

    abort : function(editor) {
      if (confirm("Are you sure you want to discard your changes?")) {
        editor.abort();
      }
    }
  }
};

jQuery.fn.best_in_place = function() {
  this.each(function(){
    jQuery(this).data('bestInPlaceEditor', new BestInPlaceEditor(this));
  });
  return this;
};

/**
*	@name							Elastic
*	@descripton						Elastic is Jquery plugin that grow and shrink your textareas automaticliy
*	@version						1.6.5
*	@requires						Jquery 1.2.6+
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*/

(function(jQuery){
	jQuery.fn.extend({
		elastic: function() {
			//	We will create a div clone of the textarea
			//	by copying these attributes from the textarea to the div.
			var mimics = [
				'paddingTop',
				'paddingRight',
				'paddingBottom',
				'paddingLeft',
				'fontSize',
				'lineHeight',
				'fontFamily',
				'width',
				'fontWeight'];

			return this.each( function() {

				// Elastic only works on textareas
				if ( this.type != 'textarea' ) {
					return false;
				}

				var $textarea	=	jQuery(this),
					$twin		=	jQuery('<div />').css({'position': 'absolute','display':'none'}),
					lineHeight	=	parseInt($textarea.css('line-height'),10) || parseInt($textarea.css('font-size'),'10'),
					minheight	=	parseInt($textarea.css('height'),10) || lineHeight*3,
					maxheight	=	parseInt($textarea.css('max-height'),10) || Number.MAX_VALUE,
					goalheight	=	0,
					i 			=	0;

				// Opera returns max-height of -1 if not set
				if (maxheight < 0) { maxheight = Number.MAX_VALUE; }

				// Append the twin to the DOM
				// We are going to meassure the height of this, not the textarea.
				$twin.appendTo($textarea.parent());

				// Copy the essential styles (mimics) from the textarea to the twin
				var i = mimics.length;
				while(i--){
					$twin.css(mimics[i].toString(),$textarea.css(mimics[i].toString()));
				}


				// Sets a given height and overflow state on the textarea
				function setHeightAndOverflow(height, overflow){
					curratedHeight = Math.floor(parseInt(height,10));
					if($textarea.height() != curratedHeight){
						$textarea.css({'height': curratedHeight + 'px','overflow':overflow});

					}
				}


				// This function will update the height of the textarea if necessary
				function update() {

					// Get curated content from the textarea.
					var textareaContent = $textarea.val().replace(/&/g,'&amp;').replace(/  /g, '&nbsp;').replace(/<|>/g, '&gt;').replace(/\n/g, '<br />');

					// Compare curated content with curated twin.
					var twinContent = $twin.html().replace(/<br>/ig,'<br />');

					if(textareaContent+'&nbsp;' != twinContent){

						// Add an extra white space so new rows are added when you are at the end of a row.
						$twin.html(textareaContent+'&nbsp;');

						// Change textarea height if twin plus the height of one line differs more than 3 pixel from textarea height
						if(Math.abs($twin.height() + lineHeight - $textarea.height()) > 3){

							var goalheight = $twin.height()+lineHeight;
							if(goalheight >= maxheight) {
								setHeightAndOverflow(maxheight,'auto');
							} else if(goalheight <= minheight) {
								setHeightAndOverflow(minheight,'hidden');
							} else {
								setHeightAndOverflow(goalheight,'hidden');
							}

						}

					}

				}

				// Hide scrollbars
				$textarea.css({'overflow':'hidden'});

				// Update textarea size on keyup, change, cut and paste
				$textarea.bind('keyup change cut paste', function(){
					update();
				});

				// Compact textarea on blur
				// Lets animate this....
				$textarea.bind('blur',function(){
					if($twin.height() < maxheight){
						if($twin.height() > minheight) {
							$textarea.height($twin.height());
						} else {
							$textarea.height(minheight);
						}
					}
				});

				// And this line is to catch the browser paste event
				$textarea.live('input paste',function(e){ setTimeout( update, 250); });

				// Run update once when elastic is initialized
				update();

			});

        }
    });
})(jQuery);