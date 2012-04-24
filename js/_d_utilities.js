/**
 * Add classes to body if gridview chosen
 */
jQuery(document).ready(function(){
	if(jQuery("#grid_view_products_page_container").length != 0)
	{
		jQuery("body").addClass("product-grid-view");
	}
});