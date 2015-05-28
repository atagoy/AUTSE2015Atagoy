/* Copyright 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(d){var e=function(){};d.extend(e.prototype,{name:"follower",options:{activeClass:"active",hoveredClass:"isfollowing",slider:{"class":"fancyfollower",html:"<div></div>"},effect:{transition:"easeOutBack",duration:200}},initialize:function(a,c){this.options=d.extend({},this.options,c);var b=this;a.css("position","relative");this.current=null;d(a.children()).each(function(){d(this).bind({mouseenter:function(){b.slider.stop();b.slideTo(d(this),"enter")},mouseleave:function(){b.slideTo(b.current,
"leave")},click:function(){b.setCurrent(d(this),true)}}).css({position:"relative"})});var f=a.children()[0].tagName.toLowerCase();a.append(d("<"+f+">").addClass(this.options.slider["class"]).html(this.options.slider.html));this.slider=a.find(">"+f+":last");this.setCurrent(a.find("."+this.options.activeClass+":first"));if(this.current)this.startElement=this.current},setCurrent:function(a,c){if(a.length&&!this.current){var b=a.position();this.slider.css({left:b.left,width:a.width(),height:a.height(),
top:b.top,opacity:1});c?this.slider.fadeIn():this.slider.show()}this.current&&this.current.removeClass(this.options.hoveredClass);if(a.length)this.current=a.addClass(this.options.hoveredClass);return this},slideTo:function(a,c){this.current||this.setCurrent(a);this.slider.stop().animate({left:a.position().left+"px",width:a.outerWidth()+"px",top:a.position().top+"px",height:a.outerHeight()+"px"},this.options.effect.duration,this.options.effect.transition);this.isHovered=c=="leave"?false:true;if(c==
"leave"&&!this.startElement){var b=this;window.setTimeout(function(){if(!b.isHovered){b.slider.fadeOut();b.current=false}},200)}else this.slider.css("opacity",1).fadeIn();return this}});d.fn[e.prototype.name]=function(){var a=arguments,c=a[0]?a[0]:null;return this.each(function(){var b=d(this);if(e.prototype[c]&&b.data(e.prototype.name)&&c!="initialize")b.data(e.prototype.name)[c].apply(b.data(e.prototype.name),Array.prototype.slice.call(a,1));else if(!c||d.isPlainObject(c)){var f=new e;e.prototype.initialize&&
f.initialize.apply(f,d.merge([b],a));b.data(e.prototype.name,f)}else d.error("Method "+c+" does not exist on jQuery."+e.name)})}})(jQuery);
