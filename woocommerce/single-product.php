<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $product;
if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
get_header('shop'); ?>
    <main id="primary" class="site-main product-page">
        <div class="inner">
            <h1 class=""> <?php the_title(); ?></h1>
            <?php woocommerce_output_all_notices(); ?>
            <div class="product-page__wrapper">
                <div class="product-page__info">
                    <div class="product-page__gallery">
                        <?php woocommerce_show_product_images(); ?>
                    </div>
                    <div class="product-page__text">
                        <div class="product-page__content-block">
                            <h2>Описание</h2>
                            <div class="product-page__content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <div class="product-page__characters-block">
                            <h2>Характеристики</h2>
                            <div class="product-page__characters">
                                <?php
                                do_action('woocommerce_product_additional_information', $product);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-page__buy-column">
                    <div class="product-page__buy">
                        <div class="product-page__category">
                            <?php
                            // Вывод категории товара
                            $categories = get_the_terms($product->ID, 'product_cat');
                            if ($categories) {
                                foreach ($categories as $category) {
                                    echo esc_html($category->name);
                                }
                            }
                            ?>
                        </div>
                        <div class="product-page__title">
                            <?php the_title(); ?>
                        </div>
                        <div class="product-page__quantity-block">
                            <div class="product-page__quantity-title">Количество</div>
                            <div class="product-page__quantity">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        </div>
                        <div class="product-page__send-question btn btn_border_red btn_color_transparent">Задать
                            вопрос
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
