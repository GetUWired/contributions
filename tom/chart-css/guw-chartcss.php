<?php
/**
 * Plugin Name: Chart Css
 * Version: 1.0.0
 * Plugin URI: http://getuwired.com/
 * Description: Sets up the css chart table generator
 * Author: GetUwired
 * Author URI: http://www./getuwired.com/
 *
 * Text Domain: mentalhealthhub
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author GetUwired
 * @since 1.0.0
 */


require_once('chartcss.php');

// chartscss
 function chartcss_custom_scripts() {
    
    // chartscss	
	wp_enqueue_style( 'chartcss', 'https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css');
	
	wp_enqueue_style( 'custom-chartcss', plugin_dir_url( __FILE__ ) . 'chartcss-custom-styles.css');

}
add_action( 'wp_enqueue_scripts', 'chartcss_custom_scripts' );

