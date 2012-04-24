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
		'wpsc-masonry',
		get_bloginfo('template_directory').'/js/wpsc-masonry.js',
		array('masonry')
	);
}    
 
add_action('wp_enqueue_scripts', '_d_enque_scripts');
/**----------------------------------
 *  add cart counts
 ----------------------------------*/
function _d_cart_total(){

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
/**----------------------------------
 * get the site title and description
 * and apply a header color if selected
 * via Gandalf
 ----------------------------------*/
function _d_get_hgroup(){
	?>
	<hgroup>
			<?php if ( 'blank' != get_header_textcolor() ) : $header_color = "#".get_header_textcolor();?>
			<h1 class="site-title"><a style="color:<?php echo $header_color;?>" href="<?php echo home_url('/');?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"><?php bloginfo('name');?></a></h1>
			<h2 style="color:<?php echo $header_color;?>" class="site-description"><?php bloginfo('description');?></h2>
			<?php endif;?>
			<?php get_search_form(true);?>
		</hgroup>
	<?php
}
/**------------------------------------------
 * 	Store styles
 -------------------------------------------*/

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
* Get the comment form
-------------------------------------------*/
function _d_get_comment_form() {
$commenter = wp_get_current_commenter();

$fields =  array(
 'author' => '<p>' . '<input id="author" name="author" type="text" value="' 
 . esc_attr( $commenter['comment_author'] ) . '" size="30"' 
 . $aria_req . ' /><label for="author">' . __( 'Name' ) . ( $req ? '<span>*</span>' : '' ) . '</label> '  .
 '</p>',
 'email'  => '<p><input id="email" name="email" type="text" value="' 
 . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' 
 . $aria_req . ' /><label for="email">' . __( 'Email' ). ( $req ? '<span>*</span>' : '' ) . '</label> '  .
 '</p>',
 'url'    => '<p><input id="url" name="url" type="text" value="' 
 . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /><label for="url">' . __( 'Website' ) . '</label>' .
 '</p>',
);

$defaults = array(
 'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
);
echo "<table id='comment_form'>";
comment_form($defaults);
echo "</table>";
}
/**------------------------------------------
* Add admin style sheet to style theme pages
-------------------------------------------*/
function _d_admin_head_styles() {
echo '<link rel="stylesheet" type="text/css" href="'._dollars_dir.'/admin-styles.css'.'">';
}

add_action('admin_head', '_d_admin_head_styles');
/**------------------------------------------
* add a link to return to top
-------------------------------------------*/
function _d_get_return_to_top(){
	?>
	<a class='return-to-top' href='#'>Return to top</a>
	<script>
	/*script added by _d_get_return_to_top() in _d_functions.php*/
		jQuery('.return-to-top').click(function(e){
			//e.preventDefault();
			$('body,html').animate({
				scrollTop: 0
			}, 'slow', function(){});

			return false;

		});
	</script>
	<?php
}
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
	