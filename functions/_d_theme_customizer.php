<?php
/**
 * Contains all the settings for the Theme Customzier (Gandalf)
 */

/**
 * Convenience class for adding settings and controls to Gandalf
 */
class Radagast_The_Brown{
	public $gandalf;  
	/**
	 * constuct with gandalf pointer
	 */
	public function __construct($gandalf){
		$this->gandalf = $gandalf;
	}
	/**
	 * add setting to gandalf
	 */
	public function add_setting($option){
		$this->gandalf->add_setting( $option, array(
		'default'    => get_option( $option ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) );
	}
	/**
	 * add a radio with yes or now label
	 * @param $reverse set to true to reverse values of yes and no
	 */
	public function add_radio($option, $title, $section, $reverse = false){
		$yes = ($reverse == false) ? '1' : '0';
		$no = ($reverse == false) ? '0' : '1';
		
		$this->add_setting($option);
		$this->gandalf->add_control( $option, array(
			'settings' => $option,
			'label'    => __( $title ),
			'section'  => $section,
			'type'    => 'radio',
				'choices' => array(
					$yes => __( 'Yes' ),
					$no  => __( 'No' ))
			) );
	}
	/**
	 * add a checkbox
	 */
	public function add_checkbox($option, $title, $section){
		$this->add_setting($option);
		$this->gandalf->add_control( $option, array(
			'settings' => $option,
			'label'    => __( $title ),
			'section'  => $section,
			'type'    => 'checkbox'
			) );
	}
	/**
	 * add a select for for given choices
	 */
	public function add_select($option, $title, $section, $choices){
		$this->add_setting($option);
		$this->gandalf->add_control( $option, array(
			'settings' => $option,
			'label'    => __( $title ),
			'section'  => $section,
			'type'    => 'select',
			'choices' => $choices
			) );
	}
	/**
	 * add a an input of type text
	 */
	public function add_textfield($option, $title, $section, $choices){
		$this->add_setting($option);
		$this->gandalf->add_control( $option, array(
			'settings' => $option,
			'label'    => __( $title ),
			'section'  => $section,
			) );
	}
}


/**--------------------------------------
 *   Add 'customize' to WP Admin Bar
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
		//--------------------wpec products --------------------//
		$radagast = new Radagast_The_Brown($gandalf);
		
		$gandalf->add_section( 'wpec_product_settings', array(
		'title'          => __( 'WPEC Product Settings' ),
		'priority'       => 1
		) );
		
		$radagast->add_radio( 'product_ratings', 'Product Ratings', 'wpec_product_settings');
		$radagast->add_radio( 'list_view_quantity', 'Show Stock Availability', 'wpec_product_settings');
		$radagast->add_radio( 'fancy_notifications', 'Display Fancy Purchase Notifications', 'wpec_product_settings');
		$radagast->add_radio( 'display_pnp', 'Display per item shipping', 'wpec_product_settings');
		$radagast->add_radio( 'hide_name_link', 'Disable link in Title', 'wpec_product_settings');
		$radagast->add_radio( 'multi_add', 'Add quantity field to each product description', 'wpec_product_settings');
		$radagast->add_radio( 'wpsc_enable_comments', 'Use IntenseDebate Comments', 'wpec_product_settings');
		//--------------------wpec products page--------------------//
		$gandalf->add_section( 'wpec_product_page', array(
		'title'          => __( 'WPEC Product Page' ),
		'priority'       => 1
		) );
		
		$radagast->add_radio( 'hide_addtocart_button', 'Add to cart button', 'wpec_product_page');	
		//button styles		
		$radagast->add_select( 'wpsc_cart_button_style', 'Button Styles', 'wpec_product_page', array(
				'none' => __( 'None' ),
				'silver'  => __( 'Silver' ),
				'blue'  => __( 'Blue' ),
				'matt-green matt-button'  => __( 'Matt Green' ),
				'matt-orange matt-button'  => __( 'Matt Orange' ),
				'yellow'  => __( 'Yellow' ),
				'red'  => __( 'Red' ),
				));			 
		//wpsc_category_grid_view
		$radagast->add_select( 'product_view', 'Product Display Format', 'wpec_product_page', array(
				'list' => __( 'List' ),
				'grid'  => __( 'Grid' )));
		//wpsc_category_grid_view
		$radagast->add_checkbox('display_description','Display grid view description','wpec_product_page');
		$radagast->add_radio( 'wpsc_display_categories', 'Show list of categories', 'wpec_product_page');
		//Sort Product By
		$radagast->add_select( 'wpsc_sort_by', 'Sort Product By', 'wpec_product_page',array(
				'name' => __( 'Name' ),
				'price'  => __( 'Price' ),
				'dragndrop'  => __( 'Drag n Drop' ),
				'id'  => __( 'Id' )
				));
		$radagast->add_radio( 'show_breadcrumbs', 'Show Breadcrumbs', 'wpec_product_page');
		$radagast->add_radio( 'show_advanced_search', 'Show Advanced Search', 'wpec_product_page');
		$radagast->add_radio( 'wpsc_replace_page_title', 'Replace Page Title With Product/Category Name', 'wpec_product_page');
		//--------------------wpec thumbnails--------------------//
		$gandalf->add_section( 'wpec_thumbnails', array(
		'title'          => __( 'WPEC Thumbnails' ),
		'priority'       => 1
		) );
		
		//default image sizes
		$radagast->add_textfield('product_image_width','Default Product Thumbnail Width','wpec_thumbnails');
		$radagast->add_textfield('product_image_height','Default Product Thumbnail Height','wpec_thumbnails');
		//category image sizes
		$radagast->add_textfield('category_image_width','Default Product Group Thumbnail Width','wpec_thumbnails');
		$radagast->add_textfield('category_image_height','Default Product Group Thumbnail Height','wpec_thumbnails');
		//single product group image width
		$gandalf->add_setting( 'single_view_image_width', array(
		'default'    => get_option( 'single_view_image_width' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'single_view_image_width', array(
		'settings' => 'single_view_image_width',
		'label'    => __( 'Single Product Image Width' ),
		'section'  => 'wpec_thumbnails'
		) );
		//single product group image height
		$gandalf->add_setting( 'single_view_image_height', array(
		'default'    => get_option( 'single_view_image_height' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'single_view_image_height', array(
		'settings' => 'single_view_image_height',
		'label'    => __( 'Single Product Image Height' ),
		'section'  => 'wpec_thumbnails'
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
		'section'  => 'wpec_thumbnails',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//show thumbnails
		$gandalf->add_setting( 'show_thumbnails', array(
		'default'    => get_option( 'show_thumbnails' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'show_thumbnails', array(
		'settings' => 'show_thumbnails',
		'label'    => __( 'Show Thumbnails' ),
		'section'  => 'wpec_thumbnails',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//--------------------wpec category section--------------------//
		$gandalf->add_section( 'wpec_categories', array(
		'title'          => __( 'WPEC Categories' ),
		'priority'       => 1
		) );
		
		//category description
		$gandalf->add_setting( 'wpsc_category_description', array(
		'default'    => get_option( 'wpsc_category_description' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'wpsc_category_description', array(
		'settings' => 'wpsc_category_description',
		'label'    => __( 'Show Product Category Description' ),
		'section'  => 'wpec_categories',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//category thumbnails
		$gandalf->add_setting( 'show_category_thumbnails', array(
		'default'    => get_option( 'show_category_thumbnails' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'show_category_thumbnails', array(
		'settings' => 'show_category_thumbnails',
		'label'    => __( 'Show Product Category Thumbnails' ),
		'section'  => 'wpec_categories',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//category gridview
		$gandalf->add_setting( 'wpsc_category_grid_view', array(
		'default'    => get_option( 'wpsc_category_grid_view' ),
		'type'       => 'option',
		'capability' => 'manage_options' 
		) ); 
		$gandalf->add_control( 'wpsc_category_grid_view', array(
		'settings' => 'wpsc_category_grid_view',
		'label'    => __( 'Category Grid View' ),
		'section'  => 'wpec_categories',
		'type'    => 'radio',
			'choices' => array(
				'1' => __( 'Yes' ),
				'0'  => __( 'No' ))
		) );
		//--------------------header section--------------------//
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

		//----------------text section-------------------//
		
		//add section		
		$gandalf->add_section( 'text', array(
		'title'          => __( 'Text Styles' ),
		'priority'       => 2,
		) );
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
		//add font choices
		$font_choices = array(
				'Helvetica Neue' => 'Helvetica Neue',
				'Lato' => 'Lato',
				'Arvo' => 'Arvo',
				'Muli' => 'Muli',
				'Play' => 'Play',
				'Oswald' => 'Oswald'
				);

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
add_action( 'customize_register', '_d_gandalf_hooks' ); //hook for customizer

/**--------------------------------------
 *        Add classes to body
 --------------------------------------*/
function _d_body_classes($classes) {
	$button_style = get_option("wpsc_cart_button_style");
	if($button_style != 'None' && $button_style != null)
		$classes[] = 'wpsc-custom-button-'.$button_style;
	if(is_active_sidebar('Right'))
		$classes[] = 'has-sidebar'; 
	return $classes;
}
add_filter('body_class','_d_body_classes');
?>