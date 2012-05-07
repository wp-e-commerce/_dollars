/**--------------------------------------
 * jQuery to control cart total widget
 --------------------------------------*/

/**
 * When ready get current cart count and listen for button clicks
 */ 
jQuery(document).ready(function(){
	var item_display = jQuery('#cart-total-widget .item-count').first();//get the first item display

	
	jQuery('.wpsc_buy_button, .cart-widget-remove .remove_button').click(function(){
		var item_count = parseInt(item_display.text());
		wct_get_count(item_count, 0); //on click send item count and 0 for first attempt
	})
});
/** 
 * get current count via ajax, if unchanged try it again until max attempts reached
 */
function wct_get_count(current_count, attempts){
	console.log('current_count = '+current_count+" attempts = "+attempts);
	var max_attempts = 10; //max attempts
	jQuery.ajax({
		url:        wpsc_ajax.ajaxurl,
	    type:       'post',
	    data:       { "action":"wpsc_cart_count_ajax"},
	    success: function(data) {
	    	console.log('current_count = '+current_count+' parseInt(data) = '+parseInt(data));
	    	if(current_count != parseInt(data) || attempts >= max_attempts)
	    	{
	    		wct_set_count(data); //if different set the count display 
	    	}
	    	else
	    	{
	    		wct_get_count(parseInt(data), attempts+1); //if unchanged try again
	    	}
		}
	});	
}
/**
 * set the text of the display span
 */
function wct_set_count(count){
	var item_display = jQuery('#cart-total-widget .item-count');
	item_display.text(count);
} 
