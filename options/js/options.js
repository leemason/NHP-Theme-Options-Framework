jQuery(document).ready(function(){

	jQuery('#0_section_group').slideDown('fast');
	jQuery('#nhp-opts-group-menu li:first').addClass('active');
	
	
	jQuery('.nhp-opts-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('.nhp-opts-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				jQuery(this).delay(400).fadeIn(1200);
			}else{
				jQuery(this).fadeOut('fast');
			}
			
		});
		
		jQuery('.nhp-opts-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});
	});
	
	
	
	
	if(jQuery('#nhp-opts-save').is(':visible')){
		jQuery('#nhp-opts-save').delay(4000).slideUp('slow');
	}
	
	if(jQuery('#nhp-opts-imported').is(':visible')){
		jQuery('#nhp-opts-imported').delay(4000).slideUp('slow');
	}	
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#nhp-opts-save-warn').slideDown('slow');
	});
	
	
	jQuery('#nhp-opts-export-code-copy').click(function(){
		jQuery('#nhp-opts-export-code').toggle('fade');
	});
	
	

	
	
	
});