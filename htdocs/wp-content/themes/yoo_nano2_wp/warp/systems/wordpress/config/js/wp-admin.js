/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

jQuery(function(c){if(c("#update-nav-menu").length){var e=c("#update-nav-menu"),g=false;e.find("#post-body");var f=[],h=function(){c(".menu-item-settings").each(function(){var d=c(this),b=d.attr("id").split("-"),b=b[b.length-1],a=[];c.inArray(b,f)==-1&&(f.push(b),a.push('<p class="description description-thin">'),a.push('<label for="edit-menu-item-attr-columns-'+b+'">'),a.push("Columns<br>"),a.push('<select data-warp="menu" id="menu-item-'+b+'-columns" style="width:100%;" name="menu-item['+b+'][columns]"><option>1</option><option>2</option><option>3</option><option>4</option></select>'),
a.push("</label>"),a.push("</p>"),a.push('<p class="description description-thin">'),a.push('<label for="edit-menu-item-attr-columns-'+b+'">'),a.push("Column Width<br>"),a.push('<input data-warp="menu" type="text" id="menu-item-'+b+'-columnwidth" style="width:100%;" name="menu-item['+b+'][columnwidth]" />'),a.push("</label>"),a.push("</p>"),a.push('<p class="description description-thin">'),a.push('<label for="edit-menu-item-attr-columns-'+b+'">'),a.push("Image<br>"),a.push('<input data-warp="menu" type="text" id="menu-item-'+
b+'-image" style="width:100%;" name="menu-item['+b+'][image]" />'),a.push("</label>"),a.push("</p>"),d.find(".submitbox").before(a.join("\n")))})};h();c(".submit-add-to-menu").bind("click",function(){var d=function(){c(".menu-item-settings").length>f.length?h():window.setTimeout(d,500)};window.setTimeout(d,500)});e.bind("submit",function(){if(g)return true;var d={action:"save_nav_settings"};e.find("[data-warp=menu]").each(function(){var b=c(this);d[b.attr("name")]=b.val()});c.post(ajaxurl+"?action=save_nav_settings",
d,function(){g=true;e.submit()});return false});c.getJSON(ajaxurl+"?action=get_nav_settings",function(d){c(f).each(function(b,a){if(d[a])for(var e in d[a])c("#menu-item-"+a+"-"+e).val(d[a][e])})})}});