jQuery(document).ready(function(){
	
	/*
	 *
	 * NHP_Options_color function
	 * Adds farbtastic to color elements
	 *
	 */
	$colorpicker_inputs = jQuery('input.popup-colorpicker');

	$colorpicker_inputs.each(
	function(){
	var $input = jQuery(this);
	var sIdSelector = "#" + jQuery(this).attr('id') + "picker";
	var oFarb = jQuery.farbtastic(
	sIdSelector,
	function( color ){
	
	$input.css({
	backgroundColor: color,
	color: oFarb.hsl[2] > 0.5 ? '#000' : '#fff'
	}).val( color );
	
	
	if( oFarb.bound == true ){
	$input.change();
	}else{
	oFarb.bound = true;
	}
	}
	);
	oFarb.setColor( $input.val() );
	
	}
	);
	
	$colorpicker_inputs.each(function(e){
	jQuery(this).parent().find('.farb-popup').hide();
	});
	
	
	$colorpicker_inputs.live('focus',function(e){
	jQuery(this).parent().find('.farb-popup').show();
	jQuery(this).parents('li').css({
	position : 'relative',
	zIndex : '9999'
	})
	jQuery('#tabber').css({overflow:'visible'});
	});
	
	$colorpicker_inputs.live('blur',function(e){
	jQuery(this).parent().find('.farb-popup').hide();
	jQuery(this).parents('li').css({
	zIndex : '0'
	})
	});
	
	
	
	
	
	
	
	
	
	/*
	 *
	 * NHP_Options_upload function
	 * Adds media upload functionality to the page
	 *
	 */
	jQuery('.nhp-opts-upload').click(function() {
		formfield = jQuery(this).attr('rel-id');
		preview = jQuery(this).prev('img');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#' + formfield).val(imgurl);
		jQuery('#' + formfield).next().next().fadeOut('slow');
		jQuery('#' + formfield).next().next().next().fadeIn('slow');
		jQuery(preview).attr('src' , imgurl);
		tb_remove();
	}
	
	jQuery('.nhp-opts-upload-remove').click(function(){
		$relid = jQuery(this).attr('rel-id');
		jQuery('#'+$relid).val('');
		jQuery(this).prev().fadeIn('slow');
		jQuery(this).prev().prev().fadeOut('slow', function(){jQuery(this).remove();});
		jQuery(this).fadeOut('slow', function(){jQuery(this).remove();});
	});
	
	
	
	
	
	jQuery('.nhp-opts-datepicker').datepicker();
	
	jQuery('.buttonset').buttonset();
	
	
	
	
	
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
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#nhp-opts-save-warn').slideDown('slow');
	});
	
	
	
});



/*
 *
 * NHP_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function nhp_radio_img_select(relid){
	jQuery(this).prev('input[type="radio"]').prop('checked');
	jQuery('.nhp-radio-img').removeClass('nhp-radio-img-selected');
	jQuery('label[for="'+relid+'"]').addClass('nhp-radio-img-selected');
}//function


