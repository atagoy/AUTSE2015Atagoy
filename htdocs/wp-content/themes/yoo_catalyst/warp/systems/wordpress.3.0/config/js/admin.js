/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

jQuery(function(a){a("#config").tabs();a("#profile").prependTo("#config li.Profiles").profiles('[data-profile="default"]');var g=a("#config ul.verify").hide();a("#config a.verify-link").bind("click",function(a){a.preventDefault();g.slideToggle()});var h=a("#config ul.systemcheck").hide();a("#config a.systemcheck-link").bind("click",function(a){a.preventDefault();h.slideToggle()});a('#warp.theme-options input[type="submit"]').bind("click",function(b){b.preventDefault();var d={},e=[],b=a("#theme-options"),
f=a(this);f.attr("disabled",!0).parent().addClass("saving");a("#config li.ignore > .field").find("input, select, textarea").each(function(){e.push(a(this).attr("name"))});a.each(b.serializeArray(),function(b,c){if(!(a.inArray(c.name,e)>-1))d[c.name]=c.value});a.ajax({url:ajaxurl,type:"post",data:d,success:function(b){var c=!1;f.attr("disabled",!1).parent().removeClass("saving");try{b=a.parseJSON(b),b.message=="success"&&(c=!0)}catch(d){}c||alert("Save failed!")}})});a("#warp.widget-options ul:first").tabs();
a('#warp.widget-options input[type="submit"]').bind("click",function(){var b=a(this);b.attr("disabled",!0).parent().addClass("saving");a.post(ajaxurl,a("#warp.widget-options form").serialize(),function(){b.attr("disabled",!1).parent().removeClass("saving")});return!1});a("#warp.widget-options select.widget-style").bind("change",function(){var b=a(this),d=b.parent().children("select.widget-color").hide().removeAttr("name");b.val()&&d.filter("."+b.val()).show().attr("name",b.attr("name").replace("[style]",
"[color]"))}).trigger("change");a(".collapsible").prepend('<div class="togglebutton"></div>').filter(".collapsed").children(".content").hide();a(".collapsible .togglebutton").bind("click",function(){a(this).nextAll(".content").slideToggle("fast")})});
