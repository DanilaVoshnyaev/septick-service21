<?php
add_filter('woocommerce_enqueue_styles', '__return_false');

//Отключаем хлебные крошки
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

//отключаем отображение количества товаров
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

//отключаем отображение сортировки
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

//переносим уведомление к описанию
//
remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
//add_action('woocommerce_archive_description', 'woocommerce_output_all_notices', 2);


//Убираем описание категории по умолчанию и добавляем новый блок с миниатюрой
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
function add_category_thumbnail_to_archive_description()
{
    if (is_product_category() || is_product_tag()) {
        $thumbnail_id = get_term_meta(get_queried_object_id(), 'thumbnail_id', true);
        $image = wp_get_attachment_image($thumbnail_id, 'medium');
        $term_description = term_description();
        if ($image && $term_description) {
            echo '<div class="product-category__term-description">';
            if ($image) {
                echo '<div class="product-category__term-thumbnail">' . $image . '</div>';
            }
            if ($term_description) {
                echo '<div class="product-category__term-text">' . $term_description . '</div>';
            }
            echo '</div>';
        }
    }
}

add_action('woocommerce_archive_description', 'add_category_thumbnail_to_archive_description');

//удаляем цену карточки товара в категории
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

/**
 * Override loop template and show quantities next to add to cart buttons
 */
add_filter('woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2);
function quantity_inputs_for_woocommerce_loop_add_to_cart_link($html, $product)
{
    if ($product && $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock() && !$product->is_sold_individually()) {
        $html = '<form action="' . esc_url($product->add_to_cart_url()) . '" class="cart woocommerce-loop-product__cart" method="post" enctype="multipart/form-data">';
        $html .= '<div class="quantity">';
        $html .= woocommerce_quantity_input(array(), $product, false);
        $html .= '</div>';
        $html .= '<button type="submit" class="btn">' . esc_html($product->add_to_cart_text()) . '</button>';
        $html .= '<input type="hidden" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" />';
        $html .= '<input type="hidden" name="product_id" value="' . esc_attr($product->get_id()) . '" />';
        $html .= '<input type="hidden" name="variation_id" class="variation_id" value="0" />';
        $html .= wp_nonce_field('woocommerce-add-to-cart', 'woocommerce-add-to-cart-nonce', true, false);
        $html .= '</form>';
    }
    return $html;
}

/**
 * @snippet       Plus Minus Quantity Buttons @ WooCommerce Product Page & Cart
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

// -------------
// 1. Show plus minus buttons

add_action('woocommerce_after_quantity_input_field', 'bbloomer_display_quantity_plus');

function bbloomer_display_quantity_plus()
{
    echo '<button type="button" class="plus"><svg><use xlink:href="#plus-btn"></use></svg></button>';
}

add_action('woocommerce_before_quantity_input_field', 'bbloomer_display_quantity_minus');

function bbloomer_display_quantity_minus()
{
    echo '<button type="button" class="minus"><svg><use xlink:href="#minus-btn"></use></svg></button>';
}

// -------------
// 2. Trigger update quantity script
//
//add_action('wp_footer', 'bbloomer_add_cart_quantity_plus_minus');
//
//function bbloomer_add_cart_quantity_plus_minus()
//{
//
//    if (!is_product() && !is_cart() && !is_shop()) return;
//    wc_enqueue_js("
//
//
//   ");
//}

function display_product_dimensions()
{
    global $product;
    $attributes = $product->get_attributes();

    $height = '';
    $width = '';
    $length = '';

    foreach ($attributes as $attribute) {
        $attribute_name = $attribute->get_name();
        if ($attribute_name === 'pa_vysota') {
            $terms = $attribute->get_terms();
            if (!empty($terms)) {
                $height = $terms[0]->name;
            }
        } elseif ($attribute_name === 'pa_shirina') {
            $terms = $attribute->get_terms();
            if (!empty($terms)) {
                $width = $terms[0]->name;
            }
        } elseif ($attribute_name === 'pa_dlina') {
            $terms = $attribute->get_terms();
            if (!empty($terms)) {
                $length = $terms[0]->name;
            }
        }
    }
    $string = '<div class="woocommerce-loop-product__dimensions">'.preg_replace('/[^0-9]/', '', $height) . ' x ' . preg_replace('/[^0-9]/', '', $width) . ' x ' . preg_replace('/[^0-9]/', '', $length).'</div>';
    echo $string;

}

add_action('woocommerce_after_shop_loop_item_title', 'display_product_dimensions', 10);


// отображать пустые категории
add_filter('woocommerce_product_subcategories_hide_empty', '__return_false');
// Скрыть категорию Uncategorized со страницы магазина
add_filter('get_terms', 'ts_get_subcategory_terms', 10, 3);
function ts_get_subcategory_terms($terms, $taxonomies, $args)
{
    $new_terms = array();
    if (in_array('product_cat', $taxonomies) && !is_admin() && is_shop()) {
        foreach ($terms as $key => $term) {
            if (!in_array($term->slug, array('misc'))) { //ваш слаг категории
                $new_terms[] = $term;
            }
        }
        $terms = $new_terms;
    }
    return $terms;
}

remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);

add_filter('woocommerce_order_button_text', 'misha_custom_button_text');

function misha_custom_button_text($button_text)
{
    return 'Отправить'; // new text is here
}

function so_39267627_form_field($field, $key, $args, $value)
{
    if ($args['required']) {
        $args['class'][] = 'validate-required';
        $required = ' <abbr class="required" title="' . esc_attr__('required', 'woocommerce') . '">*</abbr>';
    } else {
        $required = '';
    }

    $args['maxlength'] = ($args['maxlength']) ? 'maxlength="' . absint($args['maxlength']) . '"' : '';

    $args['autocomplete'] = ($args['autocomplete']) ? 'autocomplete="' . esc_attr($args['autocomplete']) . '"' : '';

    if (is_string($args['label_class'])) {
        $args['label_class'] = array($args['label_class']);
    }

    if (is_null($value)) {
        $value = $args['default'];
    }

    // Custom attribute handling
    $custom_attributes = array();

    // Custom attribute handling
    $custom_attributes = array();

    if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
        foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
            $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
        }
    }

    $field = '';
    $label_id = $args['id'];
    $field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';

    if ($args['type'] == 'textarea') {
        $field .= '<textarea class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' ' . implode(' ', $custom_attributes) . ' />' . esc_attr($value) . '</textarea>';
    } else {
        $field .= '<input type="' . esc_attr($args['type']) . '" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' />';
    }

    if (!empty($field)) {
        $field_html = '';

        $field_html .= $field;

        if ($args['description']) {
            $field_html .= '<span class="description">' . esc_html($args['description']) . '</span>';
        }

        if ($args['label'] && 'checkbox' != $args['type']) {
            $field_html .= '<label for="' . esc_attr($label_id) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . $args['label'] . $required . '</label>';
        }

        $container_class = 'form-row ' . esc_attr(implode(' ', $args['class']));
        $container_id = esc_attr($args['id']) . '_field';

        $after = !empty($args['clear']) ? '<div class="clear"></div>' : '';

        $field = sprintf($field_container, $container_class, $container_id, $field_html) . $after;
    }
    return $field;
}

add_filter('woocommerce_form_field_password', 'so_39267627_form_field', 10, 4);
add_filter('woocommerce_form_field_text', 'so_39267627_form_field', 10, 4);
add_filter('woocommerce_form_field_email', 'so_39267627_form_field', 10, 4);
add_filter('woocommerce_form_field_tel', 'so_39267627_form_field', 10, 4);
add_filter('woocommerce_form_field_number', 'so_39267627_form_field', 10, 4);
add_filter('woocommerce_form_field_textarea', 'so_39267627_form_field', 10, 4);


function update_header_basket_count() {
    echo WC()->cart->get_cart_contents_count();
    die();
}
add_action('wp_ajax_update_header_basket_count', 'update_header_basket_count');
add_action('wp_ajax_nopriv_update_header_basket_count', 'update_header_basket_count');
