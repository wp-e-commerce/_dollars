<?php
/**-------------------------------------
 * Custom additions to _s functions file
 --------------------------------------*/
define('_dollars_dir', get_bloginfo('template_directory'));
/**----------------------------------
 *  add theme support for gandalf
 ----------------------------------*/
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'post-thumbnails' ); 
/**----------------------------------
 *  enque scripts
 ----------------------------------*/
function _d_enque_scripts() {
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
	wp_register_style( 'wpsc-custom-buttons', get_bloginfo('template_directory')."/css/custom-buttons.css" );
    wp_enqueue_style( 'wpsc-custom-buttons' );
}    
 
add_action('wp_enqueue_scripts', '_d_enque_scripts');
/**----------------------------------
 *  add cart counts
 ----------------------------------*/
function wpsc_cart_total_html_function() {
	require_once(dirname(__FILE__)."/widgets/cart_total/cart_count_container.php"); 
	exit();
}
// execute on POST and GET
if ( isset( $_REQUEST['wpsc_action'] ) && ($_REQUEST['wpsc_action'] == 'wpsc_cart_count_total_html') ) {
	add_action( 'init', 'wpsc_cart_total_html_function', 109 );
}
/**
 * Register shortcodes
 */
//[wpsc-cart-total] 
function wpsc_cart_total_shortcode( $atts ){
	wpsc_cart_total_dom_elements();
}
add_shortcode( 'wpsc-cart-total', 'wpsc_cart_total_shortcode' );
function _d_cart_total(){
	//wpsc_cart_total_dom_elements();
}
function wpsc_cart_total_dom_elements(){ 
	global $cache_enabled;

		// Set display state
		$display_state = '';
		if ( ( ( isset( $_SESSION['slider_state'] ) && ( $_SESSION['slider_state'] == 0 ) ) || ( wpsc_cart_item_count() < 1 ) ) && ( get_option( 'show_sliding_cart' ) == 1 ) )
			$display_state = 'style="display: none;"';

		// Output ctart
		$use_object_frame = false;
		if ( ( $cache_enabled == true ) && ( !defined( 'DONOTCACHEPAGE' ) || ( constant( 'DONOTCACHEPAGE' ) !== true ) ) ) {
			echo '<div id="sliding_cart" class="shopping-cart-wrapper overide wpsc_cart_count_total_html first-loop">';
			if ( ( strstr( $_SERVER['HTTP_USER_AGENT'], "MSIE" ) == false ) && ( $use_object_frame == true ) ) {
				?>
				 <h1>Calling object</h1>
				<object codetype="text/html" type="text/html" data="index.php?wpsc_action=wpsc_cart_count_total_html" border="0">
					<p><?php _e( 'Loading...', 'wpsc' ); ?></p>
				</object>
				<?php
			} else {
				?>
				<div class="wpsc_cart_loading"><p><?php _e( 'Loading...', 'wpsc' ); ?></p>
				<?php
			}
			echo '</div>';
		} else {
			echo '<div id="sliding_cart" class="shopping-cart-wrapper overide wpsc_cart_count_total_html second-loop">';
			include( dirname(__FILE__).'/widgets/cart_total/wpsc-cart_count.php' );
			echo '</div>';
		}
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
/**-----------------------------
 *       get custom fonts 
 ------------------------------*/
function _d_get_custom_fonts(){
	$header_font = get_option('_d_impact_font');
	$body_font = get_option('_d_body_font');
	//echo start tag 
	echo "
	<!-- custom font added by _d_get_custom_fonts() -->
	<style type='text/css'>
	";
	//do this first then override with header fonts
	if($body_font!='' && $body_font!=null)
	{
		echo "/*font for body*/";
		//echo elements with body before to override style.css
		$elem_str = "input[type='text'],select,textarea, html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td ";
		$elements = explode(',', $elem_str);
		$count = 0;
		foreach($elements as $element)
		{
			if($count > 0)
				echo ", ";
			echo "html ".$element;
			$count++;
		}
		echo "{font-family: '$body_font', sans-serif;}";
	}
	if($header_font!='' && $header_font!=null)
	{
		echo "/*font for headers*/";
		//echo elements with body before to override style.css
		$elements = array('h1', 'h2', 'h2 a', 'h2 a:visited', 'h3', 'h4', 'h5', 'h6', '.site-title');
		$count = 0;
		foreach($elements as $element)
		{
			if($count > 0)
				echo ", ";
			echo "body $element";
			$count++;
		}
		echo "{font-family: '$header_font', sans-serif;}";
	}
	
	//echo end tag
	echo "
	</style>";
}
/**-----------------------------
 *   get the gandalf supplied 
 *    custom link color
 ------------------------------*/
function _d_get_link_colors(){
	$color = get_option("_d_link_color");
	$color_hover = get_option("_d_link_color_hover");
	$color_visited = get_option("_d_link_color_visited");
	echo "
	<!-- link color added by _d_get_link_color() -->
	<style type='text/css'>
	";
	//if  
	if($color!='' && $color!=null)
	echo "
	body a{
		color: #$color;
	}
	";
	if($color_hover!='' && $color_hover!=null)
	echo "
	body a:hover{
		color: #$color;
	}
	";
	if($color_visited!='' && $color_visited!=null)
	echo "
	body a:visited{
		color: #$color;
	}
	";
	//and finally
	echo "
	</style>
	";
}
/**----------------------------------
 * get the logo image if show logo
 * is checked
 ----------------------------------*/
function _d_get_logo_image(){
	$logo = get_option('_d_logo_image');
	$display = get_option('_d_display_logo_image');
	$url = get_bloginfo('url');
	$name = get_bloginfo('name', 'display');
	//if logo is not null add echo a span
	if($logo!='' && $logo!=null && $display):
		echo "<span id='logo-image'>
				<a href='$url' title='$name'>
		      		<img src='$logo' alt='$name logo'/>
		      	</a>
			  </span>";
	endif;
}
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
}
add_action( 'widgets_init', '_d_widgets_init' );
/**------------------------------------------
 * 	Add theme page for getting started
 -------------------------------------------*/
add_action('admin_menu', '_d_add_pages');

function _d_add_pages() {
	add_theme_page('Getting Started', 'Getting Started', 'administrator', 'getting_started_wpec','_d_page_getting_started');
	add_theme_page('Store Styles', 'Store Styles', 'administrator', 'store_styles','_d_page_store_styles');
	add_settings_field( 'logo_path', __( 'Logo', '_s' ), '_d_settings_field_logo_path', 'store_styles', 'general' );
}
/**
 * Renders the sample textarea setting field.
 */
function _d_page_store_styles() {
?>
 		<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();
}

});
</script>
	<label for="upload_image">
		<input id="upload_image" type="text" size="36" name="_s_theme_options[logo_path]" value="<?php echo $options['logo_path']; ?>" />
		<input id="upload_image_button" class="button" type="button" value="Upload Image" />
		<br />Enter an URL or upload an image for the banner.
	</label>
	<?php
}
function _d_page_getting_started(){
	$tabs = array( 'quickstart' => 'Quick Start', 'functions' => 'Functions' );
	?>
	<div class='wrap'>
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Getting started with the _dollars theme</h2>
		<?php echo '<h2 class="nav-tab-wrapper">';
		$current = isset($_GET['tab']) ? $_GET['tab'] : 'quickstart';
		foreach ($tabs as $tab => $name) {
			$class = ($tab == $current) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=getting_started_wpec&tab=$tab'>$name</a>";

		}
		echo '</h2>';
		global $pagenow;

		if ($pagenow == 'themes.php' && $_GET['page'] == 'getting_started_wpec') {

			if (isset($_GET['tab']))
				$tab = $_GET['tab'];
			else
				$tab = 'quickstart';

			switch ( $tab ) {
				case 'quickstart' :
					_d_page_getting_started_quick_start();
					break;
				case 'functions' :
					_d_page_getting_started_functions();
					break;
			}
			echo '</div>';
			}
		}
function _d_page_getting_started_quick_start(){
?> 
	<div id='guide-wrapper'>
		<!-- theme guide -->
		<div class='info-area'>
		<h3>Understanding the WPEC theme engine</h3>
		<p>After the introduction of WPEC 4 you can now use template parts to customize the
			presentation of you products</p>
		<ol>
			<li>
				<h4>Template Parts</h4>
				<p><img src='<?php echo _dollars_dir . "/guide/wpec-base-template-parts.png";?>' class='guide-img'/>
					Template parts can be included in your theme under '/wp-e-commerce/' in your theme directory</p>
			</li>
			<li>
				<h4>Template Hierachies</h4>
				<p>You can include template files in your theme directory that follow the standard
					Wordpress hierachy.
				</p>
				<p>Possible template structures</p>
				<ul>
					<li><code>archive-wpsc-product.php</code> - for displaying main product catalog.</li>
					<li><code>single-wpsc-product.php</code> - for displaying single product.</li>
				</ul>
					
			</li>
		</ol>
		</div>
	</div>
<?php }
		function _d_page_getting_started_functions(){
?> 
	<div id='guide-wrapper'>
		<!-- theme guide -->
		<div class='info-area'>
		<h3>Funcitons</h3>
		<p>Common functions</p>
		</div>
	</div>
<?php
}

/**------------------------------------------
* Add admin style sheet to style theme pages
-------------------------------------------*/
function _d_admin_head_styles() {
echo '<link rel="stylesheet" type="text/css" href="'._dollars_dir.'/admin-styles.css'.'">';
}

add_action('admin_head', '_d_admin_head_styles');
/**------------------------------------------
* determine if the post is 'new'
-------------------------------------------*/
function _d_is_new($count = 0){
	
global $post;
$post_date = strtotime(get_the_date());
$now = time();
$interval = 60 * 60 * 24; //one day
$max_new_products = 5;
		if (($now - $post_date) < $interval AND $count < $max_new_products)
			return true;
		else
			return false;
}
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

/**--------------------------------------
 *           WP Admin bar
 --------------------------------------*/
function _d_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'id' => '_d_gandalf',
        'href' => get_bloginfo('url').'/wp-admin/admin.php?customize=on&theme=_dollars',
        'title' => '<span class="ab-icon ab-gandaf"></span><span class="ab-label">Customize</span>',
		'meta'  => array(
			'title' => 'Customize theme live',
		),
    ) );
}
add_action( 'wp_before_admin_bar_render', '_d_admin_bar_render' );
/**--------------------------------------
 *           Gandalf Hooks
 --------------------------------------*/
//add options to wp
add_option( 'wpsc_cart_button_style', 'None' ); 
add_option( '_d_logo_image', '' ); 
add_option( '_d_display_logo_image', '' ); 
add_option( '_d_display_header_image', '' ); 
add_option( '_d_link_color', '' ); 
add_option( '_d_link_color_visited', '' ); 
add_option( '_d_link_color_hover', '' ); 
add_option( '_d_impact_font', '' ); 
add_option( '_d_body_font', '' ); 
add_option( '_d_header_search', 'true' ); 
//add the gandalf settings
function _d_gandalf_hooks($gandalf) {
		//--------------------add the wpec section------------------//
		$gandalf->add_section( 'wpec', array(
		'title'          => __( 'WP E-Commerce' ),
		'priority'       => 1
		) );
		
		//hide_addtocart_button
		$gandalf->add_setting( 'hide_addtocart_button', array(
		'default'    => get_option( 'hide_addtocart_button' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( 'hide_addtocart_button', array(
		'settings' => 'hide_addtocart_button',
		'label'    => __( 'Add to cart button' ),
		'section'  => 'wpec',
		'type'    => 'radio',
			'choices' => array(
				'0' => __( 'Show' ),
				'1'  => __( 'Hide' ))
		) );
		
		//Display per item shipping
		$gandalf->add_setting( 'display_pnp', array(
		'default'    => get_option( 'display_pnp' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( 'display_pnp', array(
		'settings' => 'display_pnp',
		'label'    => __( 'Display per item shipping' ),
		'section'  => 'wpec',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Show' ),
				'0'  => __( 'Hide' ))
		) );
		
		//Add quantity field to each product description
		$gandalf->add_setting( 'multi_add', array(
		'default'    => get_option( 'multi_add' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'multi_add', array(
		'settings' => 'multi_add',
		'label'    => __( 'Add quantity field to each product description' ),
		'section'  => 'wpec',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Show' ),
				'0'  => __( 'Hide' ))
		) );
		//button styles
		$gandalf->add_setting( 'wpsc_cart_button_style', array(
		'default'    => get_option( 'wpsc_cart_button_style' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'wpsc_cart_button_style', array(
		'settings' => 'wpsc_cart_button_style',
		'label'    => __( 'Button Styles' ),
		'section'  => 'wpec',
		'type'    => 'select',
			'choices' => array(
				'none' => __( 'None' ),
				'silver'  => __( 'Silver' ),
				'blue'  => __( 'Blue' ),
				'yellow'  => __( 'Yellow' ),
				'red'  => __( 'Red' ),
				)
		) );
		//wpsc_category_grid_view
		$gandalf->add_setting( 'product_view', array(
		'default'    => get_option( 'product_view' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'product_view', array(
		'settings' => 'product_view',
		'label'    => __( 'Grid View' ),
		'section'  => 'wpec',
		'type'    => 'select',
			'choices' => array(
				'list' => __( 'List' ),
				'grid'  => __( 'Grid' ))
		) );
		//wpsc_category_grid_view
		$gandalf->add_setting( 'display_description', array(
		'default'    => get_option( 'display_description' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'display_description', array(
		'settings' => 'display_description',
		'label'    => __( 'Display grid view description' ),
		'section'  => 'wpec',
		'type'    => 'checkbox'

		) );
		//image sizes width
		$gandalf->add_setting( 'product_image_width', array(
		'default'    => get_option( 'product_image_width' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'product_image_width', array(
		'settings' => 'product_image_width',
		'label'    => __( 'Image Width' ),
		'section'  => 'wpec'
		) );
		//image sizes height
		$gandalf->add_setting( 'product_image_height', array(
		'default'    => get_option( 'product_image_height' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'product_image_height', array(
		'settings' => 'product_image_height',
		'label'    => __( 'Image Height' ),
		'section'  => 'wpec'
		) );
		//crop thumbnails
		$gandalf->add_setting( 'wpsc_crop_thumbnails', array(
		'default'    => get_option( 'wpsc_crop_thumbnails' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'wpsc_crop_thumbnails', array(
		'settings' => 'wpsc_crop_thumbnails',
		'label'    => __( 'Crop Thumbnails' ),
		'section'  => 'wpec',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//-------------------header sections -------------------//
		//add logo image
		$gandalf->add_setting( '_d_logo_image', array(
		'default'        => get_option('_d_logo_image'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );

		$gandalf->add_control( new WP_Customize_Image_Control( $gandalf, '_d_logo_image', array(
		'settings' => '_d_logo_image',
		'label'          => __( 'Logo Image' ),
		'section'        => 'header',
		) ) );
		//display logo checkbox
		$gandalf->add_setting( '_d_display_logo_image', array(
		'default'        => get_option('_d_display_logo_image'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( '_d_display_logo_image', array(
		'settings' => '_d_display_logo_image',
		'label'    => __( 'Display Logo' ),
		'section'  => 'header',
		'type'    => 'checkbox',
		) );
		//add search checkbox
		$gandalf->add_setting( '_d_header_search', array(
		'default'        => get_option('_d_header_search'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( '_d_header_search', array(
		'settings' => '_d_header_search',
		'label'    => __( 'Show Search Bar' ),
		'section'  => 'header',
		'type'    => 'checkbox',
		) );

		//---------------add the colors section -------------------//
		//add link color
		$gandalf->add_setting( '_d_link_color', array(
		'default'        => get_option('_d_link_color'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );

		$gandalf->add_control( new WP_Customize_Color_Control( $gandalf, '_d_link_color', array(
		'settings' => '_d_link_color',
		'label'          => __( 'Link Color' ),
		'section'        => 'text',
		) ) );
		//add link hover
		$gandalf->add_setting( '_d_link_color_hover', array(
		'default'        => get_option('_d_link_color_hover'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );

		$gandalf->add_control( new WP_Customize_Color_Control( $gandalf, '_d_link_color_hover', array(
		'settings' => '_d_link_color_hover',
		'label'          => __( 'Link Color Hover' ),
		'section'        => 'text',
		) ) );
		//add link visited
		$gandalf->add_setting( '_d_link_color_visited', array(
		'default'        => get_option('_d_link_color_visited'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );

		$gandalf->add_control( new WP_Customize_Color_Control( $gandalf, '_d_link_color_visited', array(
		'settings' => '_d_link_color_visited',
		'label'          => __( 'Link Color Visited' ),
		'section'        => 'text',
		) ) );
		//---------------add the text section -------------------//
		$font_choices = array(
				'Helvetica Neue' => 'Helvetica Neue',
				'Lato' => 'Lato',
				'Arvo' => 'Arvo',
				'Muli' => 'Muli',
				'Play' => 'Play',
				'Oswald' => 'Oswald'
				);
		//add section		
		$gandalf->add_section( 'text', array(
		'title'          => __( 'Text Styles' ),
		'priority'       => 2,
		) );
		//add header font
		$gandalf->add_setting( '_d_impact_font', array(
		'default'        => get_option('_d_impact_font'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( '_d_impact_font', array(
		'settings' => '_d_impact_font',
		'label'    => __( 'Header Font' ),
		'section'  => 'text',
		'type'    => 'select',
			'choices' => $font_choices
		) );
		//add body font
		$gandalf->add_setting( '_d_body_font', array(
		'default'        => get_option('_d_body_font'),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
		$gandalf->add_control( '_d_body_font', array(
		'settings' => '_d_body_font',
		'label'    => __( 'Body Font' ),
		'section'  => 'text',
		'type'    => 'select',
			'choices' => $font_choices
		) );

} 	
add_action( 'customize_register', '_d_gandalf_hooks' );

add_filter('body_class','_d_body_classes');
function _d_body_classes($classes) {
	$button_style = get_option("wpsc_cart_button_style");
	if($button_style != 'None' && $button_style != null)
		$classes[] = 'wpsc-custom-button-'.$button_style;
	if(is_active_sidebar('Right'))
		$classes[] = 'has-sidebar'; 
	return $classes;
}
/**
 * Enqueue plugin style-file
 */

 
 
 