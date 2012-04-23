<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _s
 * @since _s 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
				<div id="footer-widget" class="widget-area">
					<ul class="xoxo">
						<?php dynamic_sidebar( 'footer-widget-area' ); ?>
					</ul>
				</div>
		<?php endif; ?>
		<div class="site-info">
			<?php echo get_bloginfo('name', 'display')." &copy; ".date("Y");?> 
			<span class="sep"> | </span>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', '_s' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', '_s' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', '_dollars' ), '_dollars', '<a href="http://www.getshopped.org" rel="designer">Instinct</a>' ); ?>
			<?php _d_get_return_to_top(); ?> 
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>