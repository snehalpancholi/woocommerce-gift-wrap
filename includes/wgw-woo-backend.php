<?php

// Add Gift Wrap Option to Product
add_action('woocommerce_product_options_general_product_data', 'wgw_woo_add_custom_general_fields');
function wgw_woo_add_custom_general_fields()
{
    global $woocommerce, $post;
    echo '<div class="options_group">';
    woocommerce_wp_checkbox(array('id' => '_gift_wrap', 'label' => __('Gift Wrap', 'woocommerce')));
    echo '</div>';
    echo '<div class="options_group show_if_simple show_if_variable">';
    woocommerce_wp_text_input(array('id' => '_gift_wrap_price', 'label' => __('Gift Wrap Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'desc_tip' => 'true', 'description' => __('Enter the price for gift wrap.', 'woocommerce')));
    echo '</div>';
    //give 2 options,giftwrap per procuct or quantity, dropdown
    echo '<div class="options_group show_if_simple show_if_variable">';
    woocommerce_wp_select(array('id' => '_gift_wrap_option', 'label' => __('Gift Wrap Option', 'woocommerce'), 'options' => array('per_product' => __('Per Product', 'woocommerce'), 'per_quantity' => __('Per Quantity', 'woocommerce'))));
    echo '</div>';
}

// Save Gift Wrap Option to Product
add_action('woocommerce_process_product_meta', 'wgw_woo_add_custom_general_fields_save');
function wgw_woo_add_custom_general_fields_save($post_id)
{
    $woocommerce_checkbox = isset($_POST['_gift_wrap']) ? 'yes' : 'no';
    update_post_meta($post_id, '_gift_wrap', $woocommerce_checkbox);
    $woocommerce_text_field = $_POST['_gift_wrap_price'];
    update_post_meta($post_id, '_gift_wrap_price', esc_attr($woocommerce_text_field));
    $woocommerce_select_field = $_POST['_gift_wrap_option'];
    update_post_meta($post_id, '_gift_wrap_option', esc_attr($woocommerce_select_field));
}