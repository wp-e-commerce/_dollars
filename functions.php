<?php
/**-------------------------------------
 * Custom additions to _s functions file
 --------------------------------------*/
define('_DOLLARS_DIR', get_bloginfo('template_directory'));
/**----------------------------------
 *  add theme support for WPEC-Theme-Customizer
 ----------------------------------*/
add_theme_support( 'custom-background' );
add_theme_support( 'custom-header' );
/**----------------------------------
 *  enqueue scripts
 ----------------------------------*/
function _d_enqueue_scripts() {
	wp_enqueue_script(
		'masonry',
		get_bloginfo('template_directory').'/js/jquery.masonry.min.js',
		array('jquery')
	);
	wp_enqueue_script(
		'imagesLoaded',
		get_bloginfo('template_directory').'/js/jquery.imagesloaded.min.js',
		array('masonry')
	);
	wp_enqueue_script(
		'wpsc-masonry',
		get_bloginfo('template_directory').'/js/wpsc-masonry.js',
		array('masonry','imagesLoaded')
	);
	wp_enqueue_script( 
		'_d_utilities',
		get_bloginfo('template_directory').'/js/_d_utilities.js',
		array('jquery')
	);

}    
 
add_action('wp_enqueue_scripts', '_d_enqueue_scripts');
/**----------------------------------
 * get the header added via gandalf
 ----------------------------------*/
function _d_get_custom_header(){

// Check to see if the header image has been removed
$header_image = get_header_image();
if ( ! empty( $header_image ) ) :
?> <a id='custom-header-image' href="<?php echo esc_url(home_url('/'));?>"> <?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH ) ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else :
?><img src="<?php header_image();?>" width="<?php echo HEADER_IMAGE_WIDTH;?>" height="<?php echo HEADER_IMAGE_HEIGHT;?>" alt="" /> <?php endif; // end check for featured image or standard header?></a>
<?php
endif; // end check for removed header image
}
/**----------------------------------
 *  add sidebars
 ----------------------------------*/
function _d_sidebar_register(){
register_sidebar(array(
  'name' => __( 'Footer Widget Area' ),
  'id' => 'footer-widget-area',
  'description' => __( 'Widgets in this area will be shown in the footer.' ),
  'before_title' => '<h1 class="widget-title">',
  'after_title' => '</h1>'
)); 
}
add_action( 'widgets_init', '_d_sidebar_register' );	
/**------------------------------------------
 * 	Register Sidebars
 -------------------------------------------*/
function _d_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Right', '_d' ),
		'id' => 'right',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => __( 'Sidebar', '_s' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', '_d_widgets_init' );
/**------------------------------------------
* get a buy now button for current product
*  in loop
-------------------------------------------*/
function _d_get_buy_now(){
	global $post;
	
	$id = $post->ID;
	$price = get_post_meta( $id, '_wpsc_price', true );

	$action =  wpsc_product_external_link(wpsc_the_product_id());
	$action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' );
	
	
	$buynow = '
	<form class="product_form" enctype="multipart/form-data" action="'.$action.'" method="post" name="product_'.$id.'" id="product_'.$id.'">
	<!-- THIS IS THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
	<div class="wpsc_product_price">
	<p class="pricedisplay product_'.$id.'">
	Price: <span id="product_price_'.$id.'" class="currentprice pricedisplay">'.$price.'</span>
	</p>
	<!-- multi currency code -->
	<p class="pricedisplay" style="display:none;">
	Shipping:<span class="pp_price"><span class="pricedisplay">'.$price.'</span></span>
	</p>
	</div><!--close wpsc_product_price-->
	<input type="hidden" value="add_to_cart" name="wpsc_ajax_action">
	<input type="hidden" value="'.$id.'" name="product_id">
	<!-- END OF QUANTITY OPTION -->
	<div class="wpsc_buy_button_container">
	<div class="wpsc_loading_animation">
	<img title="Loading" alt="Loading" src="http://jackmahoney.co.nz/npr/wp-content/plugins/wp-e-commerce/wpsc-theme/wpsc-images/indicator.gif">
	Updating cart…
	</div><!--close wpsc_loading_animation-->
	<input type="submit" value="Add To Cart" name="Buy" class="wpsc_buy_button" id="product_'.$id.'_submit_button">
	</div><!--close wpsc_buy_button_container-->
	<div class="entry-utility wpsc_product_utility"></div>
	</form>
	';

return $buynow;
}
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since _s 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( '_d_setup' ) ):
function _d_setup() {
	
	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_s' to the name of your theme in all the template files
	 */
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', '_s' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // _d_setup
add_action( 'after_setup_theme', '_d_setup' );

/**
 * Enqueue scripts and styles
 */
function _d_scripts() {
	global $post;

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', '_d_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
