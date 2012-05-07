/**---------------
 * jQuery for masonry support in Product Grid View
 ---------------*/

/**
 * initialise masonry grid
 */
jQuery(document).ready(function(){
	jQuery('.masonry-container').imagesLoaded(_d_initMasonry) //init masonry when images have loaded
});

function _d_initMasonry(){
	//init masonry
	jQuery(function() {
		jQuery('.masonry-container').masonry({
			// options
			itemSelector : '.product_grid_item', /*class of grid item*/
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


