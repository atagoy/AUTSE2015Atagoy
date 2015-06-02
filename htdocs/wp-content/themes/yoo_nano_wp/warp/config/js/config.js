/* Copyright 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(a){a.fn.profiles=function(h){function f(b){b=="default"?d.addClass("default"):d.removeClass("default");a("[data-profile]").not(a('[data-profile="'+b+'"]').show()).hide()}function i(b){if(b&&!a('option[value="'+b+'"]',e[0]).length){var c=a(h).clone(true).attr("data-profile",b);c.find('[name^="profile_data"]').attr("name",function(j,k){return k.replace("profile_data[default]","profile_data["+b+"]")});c.children("li").each(function(){a(this).addClass("ignore").children(".label").before('<input class="ignore" type="checkbox" />')});
c.appendTo(a(h).parent());e.append('<option value="'+b+'">'+b+"</option>");a(e[0]).val(b).trigger("change")}}function l(b,c){if(c&&b!=c&&!a('option[value="'+c+'"]',e[0]).length){a('[data-profile="'+b+'"]').attr("data-profile",c).find('[name^="profile_data"]').attr("name",function(j,k){return k.replace("profile_data["+b+"]","profile_data["+c+"]")});a('input[name^="profile_map"][value="'+b+'"]',d).attr("value",c);e.find('option[value="'+b+'"]').attr("value",c).html(c)}}function m(b){g.find("option:selected").attr("selected",
false);g.find("option:disabled").attr("disabled",false);a('input[type="hidden"]',d).each(function(){var c=a(this).attr("name").replace(/^profile_map\[(.*)\]$/i,"$1"),j=a(this).val()==b?"selected":"disabled";g.find('[value="'+c+'"]').attr(j,true)});g.show().find("select").focus()}function n(b){a('input[name^="profile_map"][value="'+b+'"]',d).remove();g.find("option:selected").each(function(){d.append('<input type="hidden" name="profile_map['+a(this).val()+']" value="'+b+'" />')})}var d=this.first(),
e=a.merge(a("> select",d),a("select.profile")),g=a(".items",d);a('[data-profile][data-profile!="default"] > li').each(function(){a(this).children(".label").before(a('<input class="ignore" type="checkbox" />').attr("checked",!a(this).hasClass("ignore")))});f("default");a("#config").delegate("input.ignore","change",function(){a(this).attr("checked")?a(this).closest("li").removeClass("ignore"):a(this).closest("li").addClass("ignore")});a(e[0]).bind("change",function(){f(a(this).val())});a("> a.add",
d).bind("click",function(b){b.preventDefault();i(prompt("Please enter a profile name"))});a("> a.rename",d).bind("click",function(b){b.preventDefault();b=a(e[0]).val();var c=prompt("Please enter a profile name",b);l(b,c)});a("> a.remove",d).bind("click",function(b){b.preventDefault();b=a(e[0]).val();a('[data-profile="'+b+'"]').remove();a('input[name^="profile_map"][value="'+b+'"]',d).remove();e.find('option[value="'+b+'"]').remove();a(e[0]).trigger("change")});a("> a.assign",d).bind("click",function(b){b.preventDefault();
m(a(e[0]).val())});a("select",g).bind("blur",function(){n(a(e[0]).val());g.hide()});return this};a.fn.tabs=function(){return this.each(function(){var h=a(this).addClass("content").wrap('<div class="tabs-box" />').before('<ul class="nav" />'),f=a(this).prev("ul.nav");h.children("li").each(function(){f.append("<li><a>"+a(this).hide().attr("data-name")+"</a></li>")});f.children("li").bind("click",function(i){i.preventDefault();i=a("li",f).removeClass("active").index(a(this).addClass("active"));var l=
h.children("li").hide();a(l[i]).show()});f.children("li:first").trigger("click")})}})(jQuery);