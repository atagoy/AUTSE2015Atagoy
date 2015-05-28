/* Copyright 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(d){var f=function(){};d.extend(f.prototype,{name:"search",options:{url:document.location.href,param:"search",method:"post",minLength:3,delay:300,match:":not(li.skip)",skipClass:"skip",loadingClass:"loading",filledClass:"filled",resultClass:"result",resultsHeaderClass:"results-header",moreResultsClass:"more-results",noResultsClass:"no-results",listClass:"results",hoverClass:"selected",msgResultsHeader:"Search Results",msgMoreResults:"More Results",msgNoResults:"No results found"},initialize:function(b,
a){this.options=d.extend({},this.options,a);var c=this;this.value=this.timer=null;this.form=b.parent("form:first");this.input=b;this.input.attr("autocomplete","off");this.input.bind({keydown:function(e){c.form[c.input.val()?"addClass":"removeClass"](c.options.filledClass);if(e&&e.which&&!e.shiftKey)switch(e.which){case 13:c.done(c.selected);e.preventDefault();break;case 38:c.pick("prev");e.preventDefault();break;case 40:c.pick("next");e.preventDefault();break;case 27:case 9:c.hide()}},keyup:function(){c.trigger()},
blur:function(e){c.hide(e)}});this.form.find("button[type=reset]").bind("click",function(){c.form.removeClass(c.options.filledClass);c.value=null;c.input.focus()});this.choices=d("<ul>").addClass(this.options.listClass).hide().insertAfter(this.input)},request:function(b){var a=this;this.form.addClass(this.options.loadingClass);d.ajax(d.extend({url:this.options.url,type:this.options.method,dataType:"json",success:function(c){a.form.removeClass(a.options.loadingClass);a.suggest(c)}},b))},pick:function(b){var a=
null;if(typeof b!=="string"&&!b.hasClass(this.options.skipClass))a=b;if(b=="next"||b=="prev")a=this.selected?this.selected[b](this.options.match):this.choices.children(this.options.match)[b=="next"?"first":"last"]();if(a!=null&&a.length){this.selected=a;this.choices.children().removeClass(this.options.hoverClass);this.selected.addClass(this.options.hoverClass)}},done:function(b){if(b){if(b.hasClass(this.options.moreResultsClass))this.input.parent("form").submit();else if(b.data("choice"))window.location=
b.data("choice").url;this.hide()}else this.input.parent("form").submit()},trigger:function(){var b=this.value,a=this;this.value=this.input.val();if(this.value.length<this.options.minLength)return this.hide();if(this.value!=b){this.timer&&window.clearTimeout(this.timer);this.timer=window.setTimeout(function(){var c={};c[a.options.param]=a.value;a.request({data:c})},this.options.delay,this)}},suggest:function(b){if(b){var a=this,c={mouseover:function(){a.pick(d(this))},click:function(){a.done(d(this))}};
if(b===false)this.hide();else{this.selected=null;this.choices.empty();d("<li>").addClass(this.options.resultsHeaderClass+" "+this.options.skipClass).html(this.options.msgResultsHeader).appendTo(this.choices).bind(c);if(b.results&&b.results.length>0){d(b.results).each(function(){d("<li>").data("choice",this).addClass(a.options.resultClass).append(d("<h3>").html(this.title)).append(d("<div>").html(this.text)).appendTo(a.choices).bind(c)});d("<li>").addClass(a.options.moreResultsClass+" "+a.options.skipClass).html(a.options.msgMoreResults).appendTo(a.choices).bind(c)}else d("<li>").addClass(this.options.resultClass+
" "+this.options.noResultsClass+" "+this.options.skipClass).html(this.options.msgNoResults).appendTo(this.choices).bind(c);this.show()}}},show:function(){if(!this.visible){this.visible=true;this.choices.fadeIn(200)}},hide:function(){if(this.visible){this.visible=false;this.choices.removeClass(this.options.hoverClass).fadeOut(200)}}});d.fn[f.prototype.name]=function(){var b=arguments,a=b[0]?b[0]:null;return this.each(function(){var c=d(this);if(f.prototype[a]&&c.data(f.prototype.name)&&a!="initialize")c.data(f.prototype.name)[a].apply(c.data(f.prototype.name),
Array.prototype.slice.call(b,1));else if(!a||d.isPlainObject(a)){var e=new f;f.prototype.initialize&&e.initialize.apply(e,d.merge([c],b));c.data(f.prototype.name,e)}else d.error("Method "+a+" does not exist on jQuery."+f.name)})}})(jQuery);
