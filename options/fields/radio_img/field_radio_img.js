/*
 *
 * NHP_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function nhp_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.nhp-radio-img-'+labelclass).removeClass('nhp-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('nhp-radio-img-selected');
}//function