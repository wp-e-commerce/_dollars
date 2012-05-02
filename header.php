<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!-- get the google webfonts -->
<link href='http://fonts.googleapis.com/css?family=Lato|Arvo|Muli|Play|OswaldCondiment|Droid+Sans|Cabin|Arimo|Josefin+Sans|Bitter|Rokkitt|Droid+Serif|Open+Sans|Pacifico|Cardo|Lobster+Two|Inconsolata' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php _d_get_link_colors(); ?>
<?php _d_get_custom_fonts(); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
				<?php _d_get_logo_image(); ?>
				<?php if ( 'blank' != get_header_textcolor() ) : $header_color = "#".get_header_textcolor();?>
				<span id='title-desc-wrapper'>
					<h1 class="site-title"><a style="color:<?php echo $header_color;?>" href="<?php echo home_url('/');?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"><?php bloginfo('name');?></a></h1>
					<h2 style="color:<?php echo $header_color;?>" class="site-description"><?php bloginfo('description');?></h2>
				</span>
				<?php endif;?>
				<?php
				//show the seach bar if preferrred  
				if(get_option('_d_header_search'))
				get_search_form(true);
				?>
		</hgroup>	
		<?php 
		/**
		 *  functions found in _d_functions.php
		 */
		_d_get_custom_header(); //loads the header image via settings 
		
		?>
		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text"><?php _e( 'Menu', '_s' ); ?></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', '_s' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			<?php _d_cart_total();?>
		</nav>
	</header><!-- #masthead .site-header -->

	<div id="main">