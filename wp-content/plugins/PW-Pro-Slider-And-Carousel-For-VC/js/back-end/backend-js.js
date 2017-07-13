// JavaScript Document
jQuery(document).ready(function() {
	//jQuery( document ).on( "click", ".heading_type",function(){
    jQuery(".heading_type").live("change",function(){
		var heading_type='';
		heading_type=jQuery(this).val();
		//confirm(heading_type);
		switch(heading_type){
			case 'manual_heading':
				jQuery(".vc_categories_field").prop("disabled",false);
				jQuery(".vc_tags_field").prop("disabled",false);
				jQuery(".vc_tax_query_field").prop("disabled",false);
				jQuery(".vc_by_id_field").prop("disabled",false);
			break;
				
			case 'adv_heading':
				jQuery(".vc_by_id_field").prop("disabled",true);
				jQuery(".heading_base").trigger("change");
			break;
				
			case 'simple_heading':
				jQuery(".heading_base").trigger("change");
			break;	
		}
	});
	
	jQuery(".heading_base").live("change",function(){
		var heading_base='';
		heading_base=jQuery(this).val();
		//confirm(heading_base);
		
		switch(heading_base){
			case "category_base":
				jQuery(".vc_categories_field").prop("disabled",false);
				jQuery(".vc_tags_field").prop("disabled",true);
				jQuery(".vc_tax_query_field").prop("disabled",true);
				//jQuery(".vc_by_id_field").prop("disabled",true);
			break;
			
			case "tag_base" :
				jQuery(".vc_categories_field").prop("disabled",true);
				jQuery(".vc_tags_field").prop("disabled",false);
				jQuery(".vc_tax_query_field").prop("disabled",true);
			break;
			
			case "tax_base":
				jQuery(".vc_categories_field").prop("disabled",true);
				jQuery(".vc_tags_field").prop("disabled",true);
				jQuery(".vc_tax_query_field").prop("disabled",false);
			break;
		}
	});
	
	
	
	jQuery(".pw_query_button").live("click",function(){

		setTimeout(function(){
			
			jQuery("input[type=\'checkbox\']").live("change", function() { 
				value=jQuery(".pw_query").val();
				var tax_arr=Array();
				query_arr=value.split("|");
				query_arr.forEach(function(entry) {
					post_arr=entry.split(":");
					if(post_arr[0]=="post_type")
					{
						if(post_arr[1].indexOf(",")!=-1){
							confirm("Please, Select Just One Post!");	
						}
						return false;
					}
				});
			});
			
		},2000);
	});	
	
});