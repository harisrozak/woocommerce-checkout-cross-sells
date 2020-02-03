<?php
/*
Plugin Name: Woocommerce Checkout Cross Sells
Plugin URI: https://github.com/harisrozak/woocommerce-checkout-cross-sells/
Description: A simple plugin to display WC cross sells function on checkout page
Version: 0.1
Author: harisrozak
Author URI: https://harisrozak.github.io/
*/

add_action( 'woocommerce_after_checkout_form', 'harisrozak_woocommerce_cross_sell_display' );

function harisrozak_woocommerce_cross_sell_display() {
	// Get visible cross sells then sort them at random.
	$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );

	wc_set_loop_prop( 'name', 'cross-sells' );
	wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_cross_sells_columns', $columns ) );

	// Handle orderby and limit results.
	$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
	$order       = apply_filters( 'woocommerce_cross_sells_order', $order );
	$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
	$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
	$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

	wc_get_template(
		'cart/cross-sells.php',
		array(
			'cross_sells'    => $cross_sells,

			// Not used now, but used in previous version of up-sells.php.
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'columns'        => $columns,
		)
	);
}