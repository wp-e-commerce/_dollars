<?php _d_file_header(__FILE__);?>
<?php
global $wp_query;

?>
<?php if ( wpsc_have_products() ) : ?>
	<?php if( get_option('wpec_toapi_show_breadcrumbs') ==1 ) wpsc_breadcrumb(); ?>
	<?php wpsc_product_pagination( 'top' ); ?>
		<?php if( get_option('wpec_toapi_taxonomy_view') =='grid' ):?>
			<div id='wpec-product-grid'>
			<?php wpsc_get_template_part( 'loop', 'grid-products' ); ?>
			</div>
		<?php else:?>
			<?php wpsc_get_template_part( 'loop', 'products' ); ?>
		<?php endif;?>
	<?php wpsc_product_pagination( 'bottom' ); ?>
<?php else : ?>
	<?php wpsc_get_template_part( 'feedback', 'no-products' ); ?>
<?php endif; ?>