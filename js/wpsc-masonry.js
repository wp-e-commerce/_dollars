/**---------------
 * jQuery for masonry support in Product Grid View
 ---------------*/

/**
 * initialise masonry grid
 */
_d_initMasonry();
function _d_initMasonry(){
	jQuery(function() {
		jQuery('.masonry-container').masonry({
			// options
			itemSelector : '.product_grid_item',
			singleMode : true,
			isAnimated : true
		});
	});
}
/**
 * reload masonry on window resize
 */
jQuery(window).resize(function(){
	_d_initMasonry();
});


