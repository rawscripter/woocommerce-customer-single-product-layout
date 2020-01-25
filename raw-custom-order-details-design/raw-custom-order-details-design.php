<?php
/**
 * Plugin Name: WooCommerce single Product  details design layout
 * Plugin URI: https://github.com/rawscripter/
 * Description: WooCommerce single Product single Product  details design layout
 * Version: 1.0
 * Author: RawScripter
 * Author URI: https://www.rawscripters.dev/
 */

add_action('woocommerce_single_product_summary', 'wpa83367_price_html');
function wpa83367_price_html()
{
    global $product;
    global $woocommerce;
    $currency = ' ' . get_woocommerce_currency_symbol() . '.';
    if ($product->is_type('simple') || $product->is_type('external') || $product->is_type('grouped')) {
        $regular_price = get_post_meta($product->get_id(), '_regular_price', true);
        $sale_price = get_post_meta($product->get_id(), '_sale_price', true);
        $priceOutPutHtml = '<div id="raw-product-price-section">
                                <div class="normal-price-section">
                                <span class="price-label"><font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Normalpris</font></font>
                                </span><span class="normal-price">' . $regular_price . $currency . '</span></div>
                                <div class="offer-price-section"><span class="price-label">
                                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Medlemspris</font></font></span>
                                <span class="offer-price">' . $sale_price . $currency . '</span></div></div><div class="clear-fix"></div>';
    }
    echo $priceOutPutHtml;
}

// for adding content before add to cart form
add_action('woocommerce_before_add_to_cart_form', 'add_content_before_addtocart_button_func');
/*
 * Content below "Add to cart" Button.
 */
function add_content_before_addtocart_button_func()
{
    // Echo content.
    $html = '<div class="usp-single-top">
                <ul>
                  <li><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Leveringstid1</font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">1-3 hverdage</font></font></span></li>
                  <li><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ForsendelseFri </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Fri levering</font></font></span></li>
                  <li><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Du sparer </font></font><span><em><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' . ts_you_save() . '</font></font></em></span></li>     </ul >
              </div > <div class="clear-fix"></div>';
    echo $html;
}

function ts_you_save()
{
    global $product;
    global $woocommerce;
    $currency = ' ' . get_woocommerce_currency_symbol() . '.';
    if ($product->is_type('simple') || $product->is_type('external') || $product->is_type('grouped')) {
        $regular_price = get_post_meta($product->get_id(), '_regular_price', true);
        $sale_price = get_post_meta($product->get_id(), '_sale_price', true);
        if (!empty($sale_price)) {
            $amount_saved = $regular_price - $sale_price;
            return ($amount_saved) . $currency;
        }
    }
}

//for hide quantity field
function wc_remove_all_quantity_fields($return, $product)
{
    return true;
}

// for adding content before add to cart form
add_action('woocommerce_after_add_to_cart_form', 'add_content_after_addtocart_button_func');
/*
 * Content below "Add to cart" Button.
 */
function add_content_after_addtocart_button_func()
{
    // Echo content.
    $html = '<div id="raw-product-stock-section">
                <p><img class="stock-img" src="https://luxclub.dk/themes/shopv2/assets/images/check.png">
                <span class="stock-text">På lager - Levering 1-3 hverdage</span>
              </div > ';
    $html .= '<div class="raw-product-stock-section">
                <ul>
                  <li><img src="https://luxclub.dk/themes/shopv2/assets/img/icon-trolley.png"><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Free delivery</font></font></span></li>
                  <li><img src="https://luxclub.dk/themes/shopv2/assets/img/icon-package.png"><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">1-3 business days delivery</font></font></span></li>
                  <li><img src="https://luxclub.dk/themes/shopv2/assets/img/icon-shopping-bag-moving.png"><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">30 day return policy</font></font></span></li>
                  <li><img src="https://luxclub.dk/themes/shopv2/assets/img/icon-piggy-bank.png"><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Save money on well-known brands</font></font></span></li>
                </ul>
              </div><div class="clear-fix"></div>';
    echo $html;
}

add_filter('woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2);
// to register the css
// register jquery and style on initialization
add_action('init', 'register_script');
function register_script()
{
    wp_register_style('new_style', plugins_url('/style.css', __FILE__), false, '1.0.0', 'all');
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_style');
function enqueue_style()
{
    wp_enqueue_style('new_style');
}

add_action('woocommerce_single_product_summary', 'customizing_single_product_summary_hooks', 2);
function customizing_single_product_summary_hooks()
{
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
}

add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text', 50);
function woo_custom_cart_button_text()
{
    return 'Læg i kurv';
}

// to get product brand name
function wc_get_product_brand($product_id)
{
    return wp_get_post_terms($product_id, 'pwb-brand', ['fields' => 'all']);
}

add_action('woocommerce_single_product_summary', 'insert_description', 8);
function insert_description()
{
    global $product;
    global $woocommerce;
    $product_id = $product->get_id();
    $brands = wc_get_product_brand($product_id);
    $html = '<div id="product_brands">';
    foreach ($brands as $brand) {
        $html .= '<a href="' . get_term_link($brand->term_id) . '">' . $brand->name . '</a>';
    };
    $html .= '</div>';
    echo $html;
}