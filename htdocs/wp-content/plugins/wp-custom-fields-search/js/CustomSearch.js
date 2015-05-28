/*
 * Copyright 2009 Don Benjamin
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at 
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License.
 */
CustomSearch = Class.create( {
	init : function (id,maxInput) {
		this.id=id;
		this.maxInput = maxInput;
		me = this;
		this.namesFor = CustomSearch.sharedOptions;
		if(id!='%i%') this.createFlexboxes();

		jQuery('div.sort-wrapper').sortable();
	},
	createFlexboxes: function(){
		if(this.getForm().length<1){
			setTimeout(function(){CustomSearch[this.id].createFlexboxes();},100);
			return;
		}
		this.getForm().find('.form-field-title-div').each(function(k,el){
			el = jQuery(el);
			var index = el.attr('id').replace(/.*-/,'')
			me.createFlexbox(index);
		});
	},
	fieldExists: function(id){
		if(this.id=='%i%') return false;
		newId = 'config-form-'+this.id+'-'+id;
		return jQuery("#"+newId).attr('id');
	},
	add: function (){
		var html = jQuery('#config-template-'+this.id).html();
		var oldHtml = false;
		var count=0;
		do {
			newId = 'config-form-'+this.id+'-'+(++count);
		} while(this.fieldExists(count));

		html = this.replaceAll(html,'###TEMPLATE_ID###',count);
		html=html.replace('config-template-'+this.id,newId);
		jQuery('<div id="'+newId+'">'+html+"</div>").appendTo('#config-form-'+this.id);
		this.createFlexbox(count);
		if(count>this.maxInput) this.maxInput=count;
		
		return false;
	},
	replaceAll: function(haystack,find,replace){
		do {
			oldHaystack = haystack;
			haystack = haystack.replace(find,replace);
		} while(haystack!=oldHaystack);
		return haystack;
	},

	getForm: function (id){
		var htmId='#config-form-'+this.id;
		if(id) htmId+='-'+id;
		return jQuery(htmId);
	},
	remove: function (id){
		this.getForm(id).remove();
		return false;
	},

	updateAllOptionsFor: function(joiner){
		var i=0;
		for(;i<=this.maxInput;i++){
			if(this.fieldExists(i) && (this.getJoinerFor(i)==joiner)){
				this.updateOptions(i,'joiner');
			}
		}
	},
	getJoinerFor: function(id){
		return this.getForm(id).find('.wpcfs-joiner').val();
	},
	updateOptions: function(id,changed) {
		switch(changed){
		case 'input':
		type = this.getForm(id).find('.wpcfs-input').val();
		template = jQuery('#config-input-templates-'+type+'-'+this.id);
		div = jQuery(hid = '#db_customsearch_widget-'+this.id+'-'+id+'-widget-config');
		html = template.html();
		if(!html) html='';

		html = this.replaceAll(html,'###TEMPLATE_ID###',id);
		name='';
		html = this.replaceAll(html,'###TEMPLATE_NAME###',name);
		div.html(html);
		break;
			case 'joiner':
				var html_id = 'form-field-dbname-'+this.id+'-'+id,
					html_name = 'db_customsearch_widget['+this.id+']['+id+'][name]';
				$el = jQuery('#'+html_id);
				var val;
				val = $el.find('input').val();

				type=this.getJoinerFor(id);		
				if(this.namesFor[type]){
					$el.html("<input type='text' name='"+html_name+"'/>");
					$input = $el.find('input');
					this.flexboxData[id].results = this.namesFor[type];
					if(this.namesFor[type]=='any'){
						console.log(type+" no suggestions");
						$input.autocomplete('destroy');
						//$el.find('input').val(val);
					} else {
						console.log(type+" suggestions",this.namesFor[type]);
						$input.autocomplete({source:this.namesFor[type]});
						//$el.flexbox(this.flexboxData[id],{width:100,name:html_name,maxCacheBytes:0,paging:false,initialValue:val})
					}
					$el.find('input').val(val);
					jQuery('#form-field-dbname-'+this.id+'-'+id).show();
				} else {
					jQuery('#form-field-dbname-'+this.id+'-'+id).hide();
				}
				break;
		}
	},
	flexboxData : {},
	createFlexbox: function(id){
		if(!this.flexboxData[id]) this.flexboxData[id] = {results:[]};
		initVal = jQuery('#form-field-dbname-'+this.id+'-'+id).find("input")[0].value;


		this.updateOptions(id,'joiner');
       },
	toggleOptions: function(id){
		el = jQuery('#form-field-advancedoptions-'+this.id+'-'+id);
		if(el) el.toggle();
		return false;
	}
});
if(!CustomSearch.sharedOptions) CustomSearch.sharedOptions={};
CustomSearch.setOptionsFor = function(joiner,options){
	var converted_options = [];
	for(var option in options){
		var value = options[option];
		converted_options.push({label:value['name'],value:value['id']});
	}
	console.log(converted_options);
	CustomSearch.sharedOptions[joiner] = converted_options;
	var i;
	for(i=0;i<CustomSearch.list.length;i++)
		CustomSearch[CustomSearch.list[i]].updateAllOptionsFor(joiner);
};
CustomSearch.list = [];
CustomSearch.create = function(id,maxInput){
//	jQuery(function(){
		CustomSearch.list[CustomSearch.list.length]=id;
		CustomSearch[id] = new CustomSearch(id,maxInput);
//	});
};
CustomSearch.get = function(id){
	if(!CustomSearch[id]) CustomSearch.create(id);
	return CustomSearch[id];
};

var testing=false;
if(testing)
jQuery(document).ready(function(){
	jQuery('.widget-control-edit').click();
});
	dbg = function(obj){
		output='DEBUG:';
		output+=obj;
		count=0;
		for(prop in obj){
			output+="\n"+(typeof(obj[prop]))+":	"+prop;
			if(count++>=30) {
				if(!confirm(output)) return;
				output="";
				count=0;
			}
		}
		alert(output);
	};
