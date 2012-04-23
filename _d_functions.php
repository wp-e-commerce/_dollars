<?php
/**-------------------------------------
 * Custom additions to _s functions file
 --------------------------------------*/
define('_dollars_dir', dirname( get_stylesheet_uri() ));
/**----------------------------------
 *  add theme support for gandalf
 ----------------------------------*/
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header' );
/**----------------------------------
 *  add sidebars
 ----------------------------------*/
function _d_sidebar_register(){
register_sidebar(array(
  'name' => __( 'Footer Widget Area' ),
  'id' => 'footer-widget-area',
  'description' => __( 'Widgets in this area will be shown in the footer.' ),
  'before_title' => '<h1>',
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
 * 	Add theme page for getting started
 -------------------------------------------*/
add_action('admin_menu', '_d_add_pages');

function _d_add_pages() {
	add_theme_page('Getting Started', 'Getting Started', 'administrator', 'getting_started_wpec','_d_page_getting_started');
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
