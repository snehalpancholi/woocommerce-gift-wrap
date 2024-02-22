<?php

//add gift wrap option to product single page frontend
add_action('woocommerce_before_add_to_cart_button', 'wgw_woo_gift_wrap_option');

function wgw_woo_gift_wrap_option()
{
    global $product;
    $product_id = $product->get_id();
    $gift_wrap_option = get_post_meta($product_id, '_gift_wrap', true);
  
    if ($gift_wrap_option == 'yes') {
        ?>
        <div class="wgw-woo-gift-wrap-option">            
            <p>
                <label for="wgw_woo_gift_wrap">
                   
                       <?php
                        $gift_wrap_price = get_post_meta($product_id, '_gift_wrap_price', true);
                        ?>
                        
                        <input type="checkbox" name="wgw_woo_gift_wrap" value="<?php echo get_woocommerce_currency_symbol() . $gift_wrap_price; ?>" />
                    

                        <?php
                        if ($gift_wrap_price) {
                            echo __('Gift Wrap', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . $gift_wrap_price . ')';
                        } else {
                            echo __('Gift Wrap', 'woocommerce');
                        }
                        ?>                    
                </label>
            </p>
        </div>
        <?php
    }
}

//save gift wrap option to cart item
add_filter('woocommerce_add_cart_item_data', 'wgw_woo_save_gift_wrap_option');

function wgw_woo_save_gift_wrap_option($cart_item_data)
{
    if (isset($_POST['wgw_woo_gift_wrap'])) {
        //price to be added to cart item
        $cart_item_data['wgw_woo_gift_wrap'] = $_POST['wgw_woo_gift_wrap'];
    }
    return $cart_item_data;
}

//display gift wrap option in cart
add_filter('woocommerce_get_item_data', 'wgw_woo_display_gift_wrap_option_in_cart', 10, 2);

function wgw_woo_display_gift_wrap_option_in_cart($item_data, $cart_item)
{
    if (isset($cart_item['wgw_woo_gift_wrap'])) {

        //value based on gift wrap per product or per quantity

        $gift_wrap_option = get_post_meta($cart_item['product_id'], '_gift_wrap_option', true);
        if($gift_wrap_option == 'per_product'){
            $item_data[] = array(
                'key' => __('Gift Wrap', 'woocommerce'),
                'value' => $cart_item['wgw_woo_gift_wrap']
            );
        }
        if($gift_wrap_option == 'per_quantity'){
            $item_data[] = array(
                'key' => __('Gift Wrap', 'woocommerce'),
                'value' => $cart_item['wgw_woo_gift_wrap'].' x '.$cart_item['quantity']
            );
        }
       
    }
    return $item_data;
}

//add gift wrap price to cart item price
add_action('woocommerce_before_calculate_totals', 'wgw_woo_add_gift_wrap_price_to_cart_item');

function wgw_woo_add_gift_wrap_price_to_cart_item($cart_object)
{
    foreach ($cart_object->cart_contents as $key => $value) {
        if (isset($value['wgw_woo_gift_wrap'])) {
           //get product id
            $product_id = $value['product_id'];
            //get gift wrap price
            $price = get_post_meta($product_id, '_gift_wrap_price', true);
            //get product name
            $product_name = $value['data']->get_name();
            //get product quantity
            $quantity = $value['quantity'];
            
            //add gift wrap as separate line after total

            //giftwrap per product
            $gift_wrap_option = get_post_meta($product_id, '_gift_wrap_option', true);
            if($gift_wrap_option == 'per_product'){
                $cart_object->add_fee(__('Gift Wrap  - '.$product_name, 'woocommerce'), $price);
            }
            //giftwrap per quantity
            if($gift_wrap_option == 'per_quantity'){
                $cart_object->add_fee(__('Gift Wrap '.get_woocommerce_currency_symbol().$price.' x '.$quantity.' - '.$product_name, 'woocommerce'), $price*$quantity);
            }
        }
    }
}

//add gift wrap option to order
add_action('woocommerce_checkout_create_order_line_item', 'wgw_woo_add_gift_wrap_option_to_order', 10, 4);

function wgw_woo_add_gift_wrap_option_to_order($item, $cart_item_key, $values, $order)
{
            //value based on gift wrap per product or per quantity

            $gift_wrap_option = get_post_meta($values['product_id'], '_gift_wrap_option', true);
            if($gift_wrap_option == 'per_product'){
                $item->add_meta_data(__('Gift Wrap', 'woocommerce'), $values['wgw_woo_gift_wrap']);
            }
            if($gift_wrap_option == 'per_quantity'){
                $item->add_meta_data(__('Gift Wrap', 'woocommerce'), $values['wgw_woo_gift_wrap'].' x '.$values['quantity']);
            }
}

//add gift wrap option to order email
add_filter('woocommerce_order_item_get_formatted_meta_data', 'wgw_woo_add_gift_wrap_option_to_order_email', 10, 2);

function wgw_woo_add_gift_wrap_option_to_order_email($formatted_meta, $item)
{
    $gift_wrap = $item->get_meta('Gift Wrap');
    if ($gift_wrap) {
        $formatted_meta['Gift Wrap'] = array(
            'display_key' => __('Gift Wrap', 'woocommerce'),
            'display_value' => $gift_wrap
        );
    }
    return $formatted_meta;
}
