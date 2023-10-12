<?php
    /*
    Plugin Name: WooCommerce Cart Limit
    Description: Limits shopping cart to 1 item
    Author: GetUWired - Chris Clynes
    Version: 1.0
    */
    /**
 * When a new item is added to the cart, remove other products
 */
function check_add_empty_cart( $valid, $product_id, $quantity ) {
    //if (!is_plugin_active('../plugins/woocommerce/woocommerce.php')) return;
    if( ! empty ( WC()->cart->get_cart() ) && $valid ){
        WC()->cart->empty_cart();
    }

    return $valid;

}
add_filter( 'woocommerce_add_to_cart_validation', 'check_add_empty_cart', 10, 3 );