<?php
/*
Plugin Name: WooCommerce Gift Wrap
Plugin URI: http://www.snehalpancholi.com/woocommerce-gift-wrap/
Description: This plugin allows you to add gift wrap option to your products.
Author: Snehal Pancholi
Version: 1.0
Author URI: http://www.snehalpancholi.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//plugin path constant
define('WGW_PLUGIN_PATH', plugin_dir_path(__FILE__));

//display error message if woocommerce is not installed and activated
add_action('admin_notices', 'wgw_woo_admin_notice');
function wgw_woo_admin_notice()
{
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        echo '<div class="error"><p>' . __('WooCommerce Gift Wrap requires WooCommerce to be installed and activated.', 'woocommerce') . '</p></div>';
    }
}



//include backend, frontend and custom options with plugin path
include_once WGW_PLUGIN_PATH . 'includes/wgw-woo-backend.php';
include_once WGW_PLUGIN_PATH . 'includes/wgw-woo-frontend.php';

