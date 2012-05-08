<?php
/**------------------------------------------------------------
 * Contains all the settings for the Theme Customzier (Gandalf)
 -------------------------------------------------------------*/

$wpec_theme_customizer = new WPEC_Theme_Customizer(); //easily switch out to plugin

/**
 * Class that adds settings to the Wordpress Theme Customizer (Gandalf)
 * Also loads dependancies and creates hooks for capturing option changes
 */
class WPEC_Theme_Customizer {
	public function __construct() {
		//enque scripts
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		//hook for customizer
		add_action('customize_register', array($this, 'populate_gandalf'));
		//body classes filter
		add_filter('body_class', array($this, 'add_body_classes'));
	}

	public function enqueue_scripts() {
		wp_register_style('wpsc-custom-buttons', get_bloginfo('template_directory') . "/theme-customizer/css/custom-buttons.css");
		wp_enqueue_style('wpsc-custom-buttons');
		wp_register_style('wpsc-gandalf-styles', get_bloginfo('template_directory') . "/theme-customizer/css/gandalf-styles.css");
		wp_enqueue_style('wpsc-gandalf-styles');
	}
	
	public function add_body_classes($classes){
		$button_style = get_option("wpec_toapi_button_style");
		if ($button_style != 'None' && $button_style != null)
			$classes[] = 'wpsc-custom-button-' . $button_style;
		if (is_active_sidebar('Right'))
			$classes[] = 'has-sidebar';
		return $classes;
	}

	public function populate_gandalf($gandalf) {
		/**
		 * Action hook for Theme Customizer (Gandalf)
		 * Use Radagast class to add controls in a cleaner way
		 * Add sections with $gandalf
		 * @param $gandalf instance of ThemeCustomizer passed by WPCore
		 */
		//--------------------wpec products --------------------//
		$radagast = new Radagast_The_Brown($gandalf);

		$gandalf -> add_section('wpec_product_settings', array('title' => __('WPEC Product Settings'), 'priority' => 1));

		$radagast -> add_radio('wpec_toapi_product_ratings', 'Product Ratings', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_list_view_quantity', 'Show Stock Availability', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_fancy_notifications', 'Display Fancy Purchase Notifications', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_per_item_shipping', 'Display per item shipping', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_link_in_title', 'Link in Title', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_multi_add', 'Add quantity field to each product description', 'wpec_product_settings');
		$radagast -> add_radio('wpec_toapi_wpsc_enable_comments', 'Use IntenseDebate Comments', 'wpec_product_settings');
		//--------------------wpec products page--------------------//
		$gandalf -> add_section('wpec_product_page', array('title' => __('WPEC Product Page'), 'priority' => 1));

		$radagast -> add_radio('wpec_toapi_add_to_cart_button', 'Add to cart button', 'wpec_product_page');
		//button styles
		$radagast -> add_select('wpec_toapi_button_style', 'Button Styles', 'wpec_product_page', array('none' => __('None'), 'silver' => __('Silver'), 'blue' => __('Blue'), 'matt-green matt-button' => __('Matt Green'), 'matt-orange matt-button' => __('Matt Orange'), 'yellow' => __('Yellow'), 'red' => __('Red'), ));
		//wpsc_category_grid_view
		$radagast -> add_select('wpec_toapi_taxonomy_view', 'Product Display Format', 'wpec_product_page', array('list' => __('List'), 'grid' => __('Grid')));
		//wpsc_category_grid_view
		$radagast -> add_checkbox('display_description', 'Display grid view description', 'wpec_product_page');
		$radagast -> add_radio('wpsc_display_categories', 'Show list of categories', 'wpec_product_page');
		//Sort Product By
		$radagast -> add_select('wpsc_sort_by', 'Sort Product By', 'wpec_product_page', array('name' => __('Name'), 'price' => __('Price'), 'dragndrop' => __('Drag n Drop'), 'id' => __('Id')));
		$radagast -> add_radio('wpec_toapi_show_breadcrumbs', 'Show Breadcrumbs', 'wpec_product_page');
		$radagast -> add_radio('show_advanced_search', 'Show Advanced Search', 'wpec_product_page');
		$radagast -> add_radio('wpsc_replace_page_title', 'Replace Page Title With Product/Category Name', 'wpec_product_page');
		//--------------------wpec thumbnails--------------------//
		$gandalf -> add_section('wpec_thumbnails', array('title' => __('WPEC Thumbnails'), 'priority' => 1));

		//default image sizes
		$radagast -> add_textfield('product_image_width', 'Default Product Thumbnail Width', 'wpec_thumbnails');
		$radagast -> add_textfield('product_image_height', 'Default Product Thumbnail Height', 'wpec_thumbnails');
		//category image sizes
		$radagast -> add_textfield('category_image_width', 'Default Product Group Thumbnail Width', 'wpec_thumbnails');
		$radagast -> add_textfield('category_image_height', 'Default Product Group Thumbnail Height', 'wpec_thumbnails');
		//single product group image sizes
		$radagast -> add_textfield('single_view_image_width', 'Single Product Image Width', 'wpec_thumbnails');
		$radagast -> add_textfield('single_view_image_height', 'Single Product Image Height', 'wpec_thumbnails');
		//crop thumbnails
		$radagast -> add_radio('wpsc_crop_thumbnails', 'Crop Thumbnails', 'wpec_thumbnails');
		$radagast -> add_radio('show_thumbnails', 'Show Thumbnails', 'wpec_thumbnails');
		//--------------------wpec category section--------------------//
		$gandalf -> add_section('wpec_categories', array('title' => __('WPEC Categories'), 'priority' => 1));
		//category description
		$radagast -> add_radio('wpsc_category_description', 'Show Product Category Description', 'wpec_categories');
		//category thumbnails
		$radagast -> add_radio('show_category_thumbnails', 'Show Product Category Thumbnails', 'wpec_categories');
		//category gridview
		$radagast -> add_radio('wpsc_category_grid_view', 'Category Grid View', 'wpec_categories');
		//--------------------header section--------------------//
		//add logo image
		$radagast -> add_image_control('_d_logo_image', 'Logo Image', 'header');
		//display logo checkbox
		$radagast -> add_checkbox('_d_display_logo_image', 'Display Logo', 'header');
		//add search checkbox
		$radagast -> add_checkbox('_d_header_search', 'Show Search Bar', 'header');

		//----------------text section-------------------//
		//add section
		$gandalf -> add_section('text', array('title' => __('Text Styles'), 'priority' => 2, ));
		//add link color
		$radagast -> add_color_control('_d_link_color', 'Link Color', 'text');
		//add link hover
		$radagast -> add_color_control('_d_link_color_hover', 'Link Color Hover', 'text');
		//add link visited
		$radagast -> add_color_control('_d_link_color_visited', 'Link Color Visited', 'text');
		//add font choices
		$font_choices = array('Helvetica Neue' => 'Helvetica Neue', 'Lato' => 'Lato', 'Arvo' => 'Arvo', 'Muli' => 'Muli', 'Play' => 'Play', 'Oswald' => 'Oswald');
		//add header font
		$radagast -> add_select('_d_impact_font', 'Header Font', 'text', $font_choices);
		//add body font
		$radagast -> add_select('_d_body_font', 'Body Font', 'text', $font_choices);
	}
}

/**
 * Convenience class for adding settings and controls to Gandalf
 */
class Radagast_The_Brown {
	public $gandalf;
	/**
	 * constucts Radagast with Gandalf pointer
	 *
	 * construct Radagast inside add_action( 'customize_register', 'your_function' );
	 * 'your_function' will be passed an instance of gandalf by WP Core
	 * ie: function your_function($gandalf)...
	 * and then call appropriate methods.
	 */
	public function __construct($gandalf) {
		$this -> gandalf = $gandalf;
	}

	/**
	 * check for options existence and set with passed value if null
	 */
	public function check_option($option, $default = '') {
		if (get_option($option) == false) {
			add_option($option, $default);
		}
	}

	/**
	 * add setting to gandalf
	 * @param $option does not exist in database if so it will be created
	 */
	public function add_setting($option, $default = '') {
		$this -> check_option($option, $default);
		$this -> gandalf -> add_setting($option, array('default' => get_option($option), 'type' => 'option', 'capability' => 'manage_options'));
	}

	/**
	 * add a radio with yes or now label
	 * @param $reverse set to true to reverse values of yes and no
	 */
	public function add_radio($option, $title, $section, $reverse = false) {
		$yes = ($reverse == false) ? '1' : '0';
		$no = ($reverse == false) ? '0' : '1';
		$this -> add_setting($option, $no);
		$this -> gandalf -> add_control($option, array('settings' => $option, 'label' => __($title), 'section' => $section, 'type' => 'radio', 'choices' => array($yes => __('Yes'), $no => __('No'))));
	}

	/**
	 * add a checkbox
	 */
	public function add_checkbox($option, $title, $section) {
		$this -> add_setting($option);
		$this -> gandalf -> add_control($option, array('settings' => $option, 'label' => __($title), 'section' => $section, 'type' => 'checkbox'));
	}

	/**
	 * add a select for for given choices
	 */
	public function add_select($option, $title, $section, $choices) {
		$this -> add_setting($option);
		$this -> gandalf -> add_control($option, array('settings' => $option, 'label' => __($title), 'section' => $section, 'type' => 'select', 'choices' => $choices));
	}

	/**
	 * add an input of type text
	 */
	public function add_textfield($option, $title, $section) {
		$this -> add_setting($option);
		$this -> gandalf -> add_control($option, array('settings' => $option, 'label' => __($title), 'section' => $section, ));
	}

	/**
	 * add an image control
	 */
	public function add_image_control($option, $title, $section) {
		$this -> add_setting($option);
		$this -> gandalf -> add_control(new WP_Customize_Image_Control($this -> gandalf, $option, array('settings' => $option, 'label' => __($title), 'section' => $section)));
	}

	/**
	 * add color control
	 */
	public function add_color_control($option, $title, $section) {
		$this -> add_setting($option);
		$this -> gandalf -> add_control(new WP_Customize_Color_Control($this -> gandalf, $option, array('settings' => $option, 'label' => __($title), 'section' => $section)));
	}

}

/**--------------------------------------
 *   Add 'customize' to WP Admin Bar
 --------------------------------------*/
function _d_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar -> add_menu(array('id' => '_d_gandalf', 'href' => get_bloginfo('url') . '/wp-admin/admin.php?customize=on&theme=_dollars', 'title' => '<span class="ab-icon ab-gandaf"></span><span class="ab-label">Customize</span>', 'meta' => array('title' => 'Customize theme live', ), ));
}

add_action('wp_before_admin_bar_render', '_d_admin_bar_render');
/**--------------------------------------
 *           Gandalf Hooks
 --------------------------------------*/
function _d_file_header($file, $echo = true) {
	$str = '<!-- using file ' . basename($file) . ' -->';
	if ($echo)
		echo $str;
	else
		return $str;
}


function _d_header_output() {
	_d_get_link_colors();
	_d_get_custom_fonts();
}

add_action('wp_head', '_d_header_output');
?>