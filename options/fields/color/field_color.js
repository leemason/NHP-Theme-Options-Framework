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
	jQuery(this).next('.farb-popup').hide();
	});
	
	
	$colorpicker_inputs.live('focus',function(e){
	jQuery(this).next('.farb-popup').show();
	jQuery(this).parents('li').css({
	position : 'relative',
	zIndex : '9999'
	})
	jQuery('#tabber').css({overflow:'visible'});
	});
	
	$colorpicker_inputs.live('blur',function(e){
	jQuery(this).next('.farb-popup').hide();
	jQuery(this).parents('li').css({
	zIndex : '0'
	})
	});
});