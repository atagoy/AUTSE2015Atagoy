/* Copyright  2007 - 2010 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(c){var b=function(){};c.extend(b.prototype,{name:"accordionMenu",initialize:function(d,a){a=c.extend({mode:"default",display:null},a);var e=d.find("ul.accordion"),g=d.find("li.toggler");g.length&&g.each(function(i){var h=c(this),j=h.find("span:first"),f=c(e[i]).parent().css("overflow","hidden"),k=f.height();h.hasClass("active")||i==a.display?f.show():f.hide().css("height",0);j.bind("click",function(){if(a.mode=="slide")h.hasClass("active")?f.stop().animate({height:0},function(){f.hide()}):
f.stop().show().animate({height:k});else f.toggle();j.toggleClass("active");h.toggleClass("active")})})}});c.fn[b.prototype.name]=function(){var d=arguments,a=d[0]?d[0]:null;return this.each(function(){var e=c(this);if(b.prototype[a]&&e.data(b.prototype.name)&&a!="initialize")e.data(b.prototype.name)[a].apply(e.data(b.prototype.name),Array.prototype.slice.call(d,1));else if(!a||c.isPlainObject(a)){var g=new b;b.prototype.initialize&&g.initialize.apply(g,c.merge([e],d));e.data(b.prototype.name,g)}else c.error("Method "+
a+" does not exist on jQuery."+b.name)})}})(jQuery);
