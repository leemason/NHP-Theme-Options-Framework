(function ($) {
	
	if($('#last_tab').val() == ''){

		$('.nhp-opts-group-tab:first').slideDown('fast');
		$('#nhp-opts-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = $('#last_tab').val();
		$('#'+tabid+'_section_group').slideDown('fast');
		$('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
	
	$('.nhp-opts-group-tab-link-a').click(function(){
		relid = $(this).attr('data-rel');
		
		$('#last_tab').val(relid);
		
		$('.nhp-opts-group-tab').each(function(){
			if($(this).attr('id') == relid+'_section_group'){
				$(this).delay(400).fadeIn(1200);
			}else{
				$(this).fadeOut('fast');
			}
			
		});
		
		$('.nhp-opts-group-tab-link-li').each(function(){
				if($(this).attr('id') != relid+'_section_group_li' && $(this).hasClass('active')){
					$(this).removeClass('active');
				}
				if($(this).attr('id') == relid+'_section_group_li'){
					$(this).addClass('active');
				}
		});
	});
	
	
	
	
	if($('#nhp-opts-save').is(':visible')){
		$('#nhp-opts-save').delay(4000).slideUp('slow');
	}
	
	if($('#nhp-opts-imported').is(':visible')){
		$('#nhp-opts-imported').delay(4000).slideUp('slow');
	}	
	
	$('input, textarea, select').change(function(){
		$('#nhp-opts-save-warn').slideDown('slow');
	});
	
	
	$('#nhp-opts-import-code-button').click(function(){
		if($('#nhp-opts-import-link-wrapper').is(':visible')){
			$('#nhp-opts-import-link-wrapper').fadeOut('fast');
			$('#import-link-value').val('');
		}
		$('#nhp-opts-import-code-wrapper').fadeIn('slow');
	});
	
	$('#nhp-opts-import-link-button').click(function(){
		if($('#nhp-opts-import-code-wrapper').is(':visible')){
			$('#nhp-opts-import-code-wrapper').fadeOut('fast');
			$('#import-code-value').val('');
		}
		$('#nhp-opts-import-link-wrapper').fadeIn('slow');
	});
	
	
	
	
	$('#nhp-opts-export-code-copy').click(function(){
		if($('#nhp-opts-export-link-value').is(':visible')){$('#nhp-opts-export-link-value').fadeOut('slow');}
		$('#nhp-opts-export-code').toggle('fade');
	});
	
	$('#nhp-opts-export-link').click(function(){
		if($('#nhp-opts-export-code').is(':visible')){$('#nhp-opts-export-code').fadeOut('slow');}
		$('#nhp-opts-export-link-value').toggle('fade');
	});
	
	

	
	
	
})(jQuery);
