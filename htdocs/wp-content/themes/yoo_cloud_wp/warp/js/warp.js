/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(e){e.fn.matchHeight=function(f){var a=0,c=[];this.each(function(){var a=f?e(this).find(f+":first"):e(this);c.push(a);a.css("min-height","")});this.each(function(){a=Math.max(a,e(this).outerHeight())});return this.each(function(b){var d=e(this),b=c[b],d=b.height()+(a-d.outerHeight());b.css("min-height",d+"px")})};e.fn.matchWidth=function(f){return this.each(function(){var a=e(this),c=a.children(f),b=0;c.width(function(d,f){return d<c.length-1?(b+=f,f):a.width()-b})})};e.fn.morph=function(f,
a,c,b,d){var g={duration:500,transition:"swing",ignore:null},c=e.extend(g,c),b=e.extend(g,b),h=c.ignore?e(c.ignore):null;h&&(h=h.toArray());return this.each(function(){var g=e(this);if(!(h&&e.inArray(this,h)!=-1)){var j=d?g.find(d).css(a):[g.css(a)];g.bind({mouseenter:function(){e(j).each(function(){var b=e(this).stop();f["background-color"]&&a["background-color"]&&b.attr("background-color")=="transparent"&&b.attr("background-color",a["background-color"]);b.animate(f,c.duration,c.transition)})},mouseleave:function(){e(j).each(function(){e(this).stop().animate(a,
b.duration,b.transition)})}})}})};e.fn.smoothScroller=function(f){f=e.extend({duration:1E3,transition:"easeOutExpo"},f);return this.each(function(){e(this).bind("click",function(){var a=this.hash,c=e(this.hash).offset().top,b=window.location.href.replace(window.location.hash,""),d=e.browser.opera?"html:not(:animated)":"html:not(:animated),body:not(:animated)";if(b+a==this)return e(d).animate({scrollTop:c},f.duration,f.transition,function(){window.location.hash=a.replace("#","")}),!1})})};e.fn.backgroundFx=
function(f){f=e.extend({duration:9E3,transition:"swing",colors:["#FFFFFF","#999999"]},f);return this.each(function(){var a=e(this),c=0,b=f.colors;window.setInterval(function(){a.stop().animate({"background-color":b[c]},f.duration,f.transition);c=c+1>=b.length?0:c+1},f.duration*2)})}})(jQuery);
(function(e){function f(c){var b;return c&&c.constructor==Array&&c.length==3?c:(b=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(c))?[parseInt(b[1]),parseInt(b[2]),parseInt(b[3])]:(b=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(c))?[parseFloat(b[1])*2.55,parseFloat(b[2])*2.55,parseFloat(b[3])*2.55]:(b=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(c))?[parseInt(b[1],16),parseInt(b[2],16),parseInt(b[3],16)]:
(b=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(c))?[parseInt(b[1]+b[1],16),parseInt(b[2]+b[2],16),parseInt(b[3]+b[3],16)]:/rgba\(0, 0, 0, 0\)/.exec(c)?a.transparent:a[e.trim(c).toLowerCase()]}e.each("backgroundColor,borderBottomColor,borderLeftColor,borderRightColor,borderTopColor,color,outlineColor".split(","),function(a,b){e.fx.step[b]=function(a){if(!a.colorInit){var c;c=a.elem;var h=b,i;do{i=e.curCSS(c,h);if(i!=""&&i!="transparent"||e.nodeName(c,"body"))break;h="backgroundColor"}while(c=c.parentNode);
c=f(i);a.start=c;a.end=f(a.end);a.colorInit=!0}a.elem.style[b]="rgb("+[Math.max(Math.min(parseInt(a.pos*(a.end[0]-a.start[0])+a.start[0]),255),0),Math.max(Math.min(parseInt(a.pos*(a.end[1]-a.start[1])+a.start[1]),255),0),Math.max(Math.min(parseInt(a.pos*(a.end[2]-a.start[2])+a.start[2]),255),0)].join(",")+")"}});var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],
darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,
0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);
(function(e){e.easing.jswing=e.easing.swing;e.extend(e.easing,{def:"easeOutQuad",swing:function(f,a,c,b,d){return e.easing[e.easing.def](f,a,c,b,d)},easeInQuad:function(f,a,c,b,d){return b*(a/=d)*a+c},easeOutQuad:function(f,a,c,b,d){return-b*(a/=d)*(a-2)+c},easeInOutQuad:function(f,a,c,b,d){return(a/=d/2)<1?b/2*a*a+c:-b/2*(--a*(a-2)-1)+c},easeInCubic:function(f,a,c,b,d){return b*(a/=d)*a*a+c},easeOutCubic:function(f,a,c,b,d){return b*((a=a/d-1)*a*a+1)+c},easeInOutCubic:function(f,a,c,b,d){return(a/=
d/2)<1?b/2*a*a*a+c:b/2*((a-=2)*a*a+2)+c},easeInQuart:function(f,a,c,b,d){return b*(a/=d)*a*a*a+c},easeOutQuart:function(f,a,c,b,d){return-b*((a=a/d-1)*a*a*a-1)+c},easeInOutQuart:function(f,a,c,b,d){return(a/=d/2)<1?b/2*a*a*a*a+c:-b/2*((a-=2)*a*a*a-2)+c},easeInQuint:function(f,a,c,b,d){return b*(a/=d)*a*a*a*a+c},easeOutQuint:function(f,a,c,b,d){return b*((a=a/d-1)*a*a*a*a+1)+c},easeInOutQuint:function(f,a,c,b,d){return(a/=d/2)<1?b/2*a*a*a*a*a+c:b/2*((a-=2)*a*a*a*a+2)+c},easeInSine:function(f,a,c,b,
d){return-b*Math.cos(a/d*(Math.PI/2))+b+c},easeOutSine:function(f,a,c,b,d){return b*Math.sin(a/d*(Math.PI/2))+c},easeInOutSine:function(f,a,c,b,d){return-b/2*(Math.cos(Math.PI*a/d)-1)+c},easeInExpo:function(f,a,c,b,d){return a==0?c:b*Math.pow(2,10*(a/d-1))+c},easeOutExpo:function(f,a,c,b,d){return a==d?c+b:b*(-Math.pow(2,-10*a/d)+1)+c},easeInOutExpo:function(f,a,c,b,d){return a==0?c:a==d?c+b:(a/=d/2)<1?b/2*Math.pow(2,10*(a-1))+c:b/2*(-Math.pow(2,-10*--a)+2)+c},easeInCirc:function(f,a,c,b,d){return-b*
(Math.sqrt(1-(a/=d)*a)-1)+c},easeOutCirc:function(f,a,c,b,d){return b*Math.sqrt(1-(a=a/d-1)*a)+c},easeInOutCirc:function(f,a,c,b,d){return(a/=d/2)<1?-b/2*(Math.sqrt(1-a*a)-1)+c:b/2*(Math.sqrt(1-(a-=2)*a)+1)+c},easeInElastic:function(f,a,c,b,d){var f=1.70158,g=0,e=b;if(a==0)return c;if((a/=d)==1)return c+b;g||(g=d*0.3);e<Math.abs(b)?(e=b,f=g/4):f=g/(2*Math.PI)*Math.asin(b/e);return-(e*Math.pow(2,10*(a-=1))*Math.sin((a*d-f)*2*Math.PI/g))+c},easeOutElastic:function(f,a,c,b,d){var f=1.70158,e=0,h=b;if(a==
0)return c;if((a/=d)==1)return c+b;e||(e=d*0.3);h<Math.abs(b)?(h=b,f=e/4):f=e/(2*Math.PI)*Math.asin(b/h);return h*Math.pow(2,-10*a)*Math.sin((a*d-f)*2*Math.PI/e)+b+c},easeInOutElastic:function(f,a,c,b,d){var f=1.70158,e=0,h=b;if(a==0)return c;if((a/=d/2)==2)return c+b;e||(e=d*0.3*1.5);h<Math.abs(b)?(h=b,f=e/4):f=e/(2*Math.PI)*Math.asin(b/h);return a<1?-0.5*h*Math.pow(2,10*(a-=1))*Math.sin((a*d-f)*2*Math.PI/e)+c:h*Math.pow(2,-10*(a-=1))*Math.sin((a*d-f)*2*Math.PI/e)*0.5+b+c},easeInBack:function(f,
a,c,b,d,e){e==void 0&&(e=1.70158);return b*(a/=d)*a*((e+1)*a-e)+c},easeOutBack:function(e,a,c,b,d,g){g==void 0&&(g=1.70158);return b*((a=a/d-1)*a*((g+1)*a+g)+1)+c},easeInOutBack:function(e,a,c,b,d,g){g==void 0&&(g=1.70158);return(a/=d/2)<1?b/2*a*a*(((g*=1.525)+1)*a-g)+c:b/2*((a-=2)*a*(((g*=1.525)+1)*a+g)+2)+c},easeInBounce:function(f,a,c,b,d){return b-e.easing.easeOutBounce(f,d-a,0,b,d)+c},easeOutBounce:function(e,a,c,b,d){return(a/=d)<1/2.75?b*7.5625*a*a+c:a<2/2.75?b*(7.5625*(a-=1.5/2.75)*a+0.75)+
c:a<2.5/2.75?b*(7.5625*(a-=2.25/2.75)*a+0.9375)+c:b*(7.5625*(a-=2.625/2.75)*a+0.984375)+c},easeInOutBounce:function(f,a,c,b,d){return a<d/2?e.easing.easeInBounce(f,a*2,0,b,d)*0.5+c:e.easing.easeOutBounce(f,a*2-d,0,b,d)*0.5+b*0.5+c}})})(jQuery);
(function(e){function f(a){var b={},c=/^jQuery\d+$/;e.each(a.attributes,function(a,d){if(d.specified&&!c.test(d.name))b[d.name]=d.value});return b}function a(){var a=e(this);a.val()===a.attr("placeholder")&&a.hasClass("placeholder")&&(a.data("placeholder-password")?a.hide().next().show().focus():a.val("").removeClass("placeholder"))}function c(){var b,c=e(this);if(c.val()===""||c.val()===c.attr("placeholder")){if(c.is(":password")){if(!c.data("placeholder-textinput")){try{b=c.clone().attr({type:"text"})}catch(d){b=
e("<input>").attr(e.extend(f(c[0]),{type:"text"}))}b.removeAttr("name").data("placeholder-password",!0).bind("focus.placeholder",a);c.data("placeholder-textinput",b).before(b)}c=c.hide().prev().show()}c.addClass("placeholder").val(c.attr("placeholder"))}else c.removeClass("placeholder")}var b="placeholder"in document.createElement("input"),d="placeholder"in document.createElement("textarea");e.fn.placeholder=b&&d?function(){return this}:function(){return this.filter((b?"textarea":":input")+"[placeholder]").bind("focus.placeholder",
a).bind("blur.placeholder",c).trigger("blur.placeholder").end()};e(function(){e("form").bind("submit.placeholder",function(){var b=e(".placeholder",this).each(a);setTimeout(function(){b.each(c)},10)})});e(window).bind("unload.placeholder",function(){e(".placeholder").val("")})})(jQuery);