/**
 * Add classes to body if gridview chosen
 */
jQuery(document).ready(function(){
	if(jQuery("#grid_view_products_page_container").length != 0)
	{
		jQuery("body").addClass("product-grid-view");
	}
});

function _d_return_to_top(){
	var scroll_time = 500;
	jQuery("body, html").animate({
		scrollTop : 0
	}, {
		duration : scroll_time,
		easing : 'swing', // the type of easing
		complete : function() {// the callback
		}
	}
	);
	
}