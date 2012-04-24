/**---------------
 * jQuery for masonry support in Product Grid View
 ---------------*/

/**
 * initialise masonry grid
 */
jQuery(function(){
  jQuery('.masonry-container').masonry({
    // options
    itemSelector : '.product_grid_item',
	singleMode : true,
    isAnimated: true
  });
});
/**
 * set onhover handlers to expand the grid product view
 */
jQuery(document).ready(function(){
	// jQuery('.masonry-container .product_grid_item').mouseover(
		// function(){
			// jQuery(this).find('.grid_hidden_info').show();
		// }).mouseout(
		// function(){ 
			// jQuery(this).find('.grid_hidden_info').hide();
		// }
	// );
});


