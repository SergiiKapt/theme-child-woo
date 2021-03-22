<?php

session_start();

if (!function_exists('set_view_count_product')) {
    function set_view_count_product($id)
    {
        $viewCountProduct = 'view_count_product';
        $count = get_post_meta($id, $viewCountProduct, true);
        $count++;

        if (isset($_SESSION['view_count_product'])) {
            if (!in_array($id, $_SESSION['view_count_product'])) {
                $_SESSION['view_count_product'][] = $id;
                update_post_meta($id, $viewCountProduct, $count);
            } else {
            }
        } else {
            $_SESSION['view_count_product'][] = $id;
            update_post_meta($id, $viewCountProduct, $count);
        }
    }
}

if (!function_exists('get_view_count_product')) {
    function get_view_count_product($id)
    {
        $viewCountProduct = 'view_count_product';
        $count = get_post_meta($id, $viewCountProduct, true);

        return $count;
    }
}

if (!function_exists('last_date_product_in_order')) {

    add_action('woocommerce_thankyou', 'last_date_product_in_order', 20);
    function last_date_product_in_order($order_id)
    {
        $order = wc_get_order($order_id);
        $data = $order->get_data();
        $order_data = $data['date_created']->date('Y-m-d ');
        $order_items = $order->get_items();
        foreach ($order_items as $item_id => $item) {
            $product_id = $item->get_product_id();
            update_post_meta($product_id, 'last_order_date_product', $order_data);
        }
    }
}

if (!function_exists('get_last_order_date_product')) {
    function get_last_order_date_product($id)
    {
        return get_post_meta($id, 'last_order_date_product', true) ?: false;
    }
}

if (!function_exists('view_count_single_product')) {

    add_action('woocommerce_single_product_summary', 'view_count_single_product', 20);
    function view_count_single_product()
    {
        global $product;

        $id = $product->get_id();

        set_view_count_product($id);

        if ($count = get_view_count_product($product->get_id())) {
            echo '<div class="view_count_product"><span class="title_vies_count">' . __("Количество просмотров"). ':</span> <span class="counter_view_count">' . $count . '</span></div>';
        }
        if ($count = get_last_order_date_product($product->get_id())) {
            echo '<div class="view_count_product"><span class="title_vies_count">' . __("Дата последней покупки"). ':</span> <span class="counter_view_count">' . $count . '</span></div>';
        }
    }
}

if (!function_exists('view_count_archive_products')) {

    add_action('woocommerce_after_shop_loop_item_title', 'view_count_archive_products', 20);
    function view_count_archive_products()
    {
        global $product;

        if ($count = get_view_count_product($product->get_id())) {
            echo '<div class="view_count_product"><span class="title_vies_count">' . __("Количество просмотров"). ':</span> <span class="counter_view_count">' . $count . '</span></div>';
        }
    }
}