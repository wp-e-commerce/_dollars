<?php
/**
 * Custom aditions to _s functions file
 */

//gandalf support
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header' );
	
/**
 * get the header added via gandalf
 */
function _d_get_custom_header(){

// Check to see if the header image has been removed
$header_image = get_header_image();
if ( ! empty( $header_image ) ) :
?> <a id='custom-header-image' href="<?php echo esc_url( home_url( '/' ) ); ?>"> <?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH ) ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else :
?><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /> <?php endif; // end check for featured image or standard header
?></a>
<?php
endif; // end check for removed header image
}