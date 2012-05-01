<?php
echo "<!-- see wpsc-cart_count.php -->"; 
printf( _n('<span id="cart-total-digit">%d</span> <span id="cart-total-label">item</span>', '<span id="cart-total-digit">%d</span> <span id="cart-total-label">items</span>', wpsc_cart_item_count(), 'wpsc'), wpsc_cart_item_count() ); 
?>