<?php

define("WPSC_CART_DIR", get_bloginfo('template_directory')."/widgets/wpsc_cart_total");
/**
 * Register the widget
 */
add_action( 'widgets_init', create_function('', 'return register_widget("WPSC_Cart_Total");') );

function wpsc_cart_total_dom_elements($label = ''){
	$count = wpsc_cart_item_count();
	echo "
	<div id='cart-total-widget' class='with-icon'>
		<span class='item-count'>$count</span><span class='item-label'>$label</span>
	</div>
	"; 
}

/**
 * Register shortcodes
 */
//[wpsc-cart-total]
function wpsc_cart_total_shortcode( $atts ){
	return wpsc_cart_total_dom_elements();
}
add_shortcode( 'wpsc-cart-total', 'wpsc_cart_total_shortcode' );

function _d_cart_total(){
	echo wpsc_cart_total_dom_elements();
}
 
class WPSC_Cart_Total extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'WPSC_Cart_Total', // Base ID
			'WPSC_Cart_Total', // Name
			array( 'description' => __( 'Displays the cart total', 'text_domain' ), ) // Args
		);
		add_action('wp_enqueue_scripts', array($this, 'cart_total_scripts'));
		add_action('wp_ajax_wpsc_cart_count_ajax', array($this,'cart_total_ajax'));
		add_action('wp_ajax_nopriv_wpsc_cart_count_ajax', array($this,'cart_total_ajax'));
	}
	
	public function cart_total_ajax(){
		echo wpsc_cart_item_count();
		die();
	}

	public function cart_total_scripts(){
		//enque js	
		wp_enqueue_script( 
	     'wpsc-cart-total-js'
	     ,WPSC_CART_DIR."/wpsc-cart-total-js.js" 
	    ,array('jquery'));
		//enque style
		wp_enqueue_style('cart-total-css'
		,WPSC_CART_DIR."/cart-total.css" 
		);
		
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$label = $instance['count_label'] ;
		
		wpsc_cart_total_dom_elements($label);
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['count_label'] = strip_tags( $new_instance['count_label'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'count_label' ] ) ) {
			$label = $instance[ 'count_label' ];
		}
		else {
			$label = __( '', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'count_label' ); ?>"><?php _e( 'Count Label:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'count_label' ); ?>" name="<?php echo $this->get_field_name( 'count_label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
		</p>
		<?php 
	}

} // class WPSC_Cart_Total
?>