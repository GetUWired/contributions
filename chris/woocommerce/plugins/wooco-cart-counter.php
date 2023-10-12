<?php
/*
    Plugin Name: Custom Cart Counter
    Description: Displays cart count
    Author: GetUWired - Chris Clynes
    Version: 3.0
    */
// =============================================================================



//new WP update does not load this by default if not using mini cart or widget
function enqueue_wc_cart_fragments() { wp_enqueue_script( 'wc-cart-fragments' ); }
add_action( 'wp_enqueue_scripts', 'enqueue_wc_cart_fragments' );

// Add cart count shortcode [cart_count]
function display_cart_count() {
    return mb_cart_count();
}
add_shortcode( 'cart_count', 'display_cart_count' );

//build elements for count display
function mb_cart_count(){
	if( ! WC()->cart->is_empty() ) {
		return '<span id="cart-count-display">' . WC()->cart->get_cart_contents_count() . '</span>';
	}else {
        return '<span id="cart-count-display"></span>';
    }
}

//ajax call refresh cart count without refreshing page
add_filter( 'woocommerce_add_to_cart_fragments', 'refresh_cart_count', 10, 1 );
function refresh_cart_count( $fragments ){
    $fragments['#cart-count-display'] = mb_cart_count();
    

    return $fragments;
}
