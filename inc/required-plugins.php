<?php
/**
 * TGM.
 */
require get_template_directory() . '/inc/TGM/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'izex_register_required_plugins');

function izex_register_required_plugins()
{
    /*
    * Array of plugin arrays. Required keys are name and slug.
    * If the source is NOT from the .org repo, then source is also required.
    */
    $plugins = array(
        array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'required' => true,
//       'source' => get_template_directory_uri() . '/assets/wordpress-plugins/contact-form.zip',
        ),
        array(
            'name' => 'Flamingo',
            'slug' => 'flamingo',
            'required' => true,
//      'source' => get_template_directory_uri() . '/assets/wordpress-plugins/flamingo.2.4.zip',
        ),
        array(
            'name' => 'Clearfy Cache',
            'slug' => 'clearfy',
            'required' => true,
//      'source' => get_template_directory_uri() . '/assets/wordpress-plugins/clearfy.zip',
        ),
        array(
            'name' => 'hide-login-page',
            'slug' => 'hide-login-page',
            'required' => true,
//      'source' => get_template_directory_uri() . '/assets/wordpress-plugins/hide-login-page.zip',
        ),
        array(
            'name' => 'WP Mail SMTP',
            'slug' => 'wp-mail-smtp',
            'required' => true,
//   'source' => get_template_directory_uri() . '/assets/wordpress-plugins/wp-mail-smtp.3.8.0.zip',
        ),
        array(
            'name' => 'All-in-One WP Migration',
            'slug' => 'all-in-one-wp-migration',
            'required' => true,
//  'source' => get_template_directory_uri() . '/assets/wordpress-plugins/all-in-one-wp-migration.7.75.zip',
        ),
        array(
            'name' => 'Yoast SEO',
            'slug' => 'wordpress-seo',
            'required' => true,
// 'source' => get_template_directory_uri() . '/assets/wordpress-plugins/wordpress-seo.20.8.zip',
        ),
        array(
            'name' => 'LazyLoad',
            'slug' => 'rocket-lazy-load',
            'required' => true,
//'source' => get_template_directory_uri() . '/assets/wordpress-plugins/rocket-lazy-load.2.3.6.zip',
        ),
        array(
            'name' => 'carbon-fields',
            'slug' => 'carbon-fields',
            'required' => true,
            'source' => get_template_directory_uri() . '/inc/TGM/plugins/carbon-fields.zip',
        ),
//        array(
//            'name' => 'Custom Post Type UI',
//            'slug' => 'custom-post-type-ui',
//        ),
//        array(
//            'name' => 'Advanced Custom Fields',
//            'slug' => 'advanced-custom-fields',
//        ),
        array(
            'name' => 'Remove Category URL',
            'slug' => 'remove-category-url',
        ),
        array(
            'name' => 'Disable REST API',
            'slug' => 'disable-json-api',
        ),
        array(
            'name' => 'Ivory Search',
            'slug' => 'add-search-to-menu',
        ),
        array(
            'name' => 'Yoast Duplicate Post',
            'slug' => 'duplicate-post',
        ),
//        array(
//            'name' => 'WooCommerce',
//            'slug' => 'woocommerce',
//        ),
        array(
            'name' => 'TinyMCE Advanced',
            'slug' => 'tinymce-advanced',
        ),
//        array(
//            'name' => 'WooCommerce added to cart popup (Ajax)',
//            'slug' => 'added-to-cart-popup-woocommerce',
//        ),
//       array(
//            'name' => 'Buy one click WooCommerce',
//            'slug' => 'buy-one-click-woocommerce',
//        ),

    );

    $config = array(
        'id' => 'izex',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php',            // Parent menu slug.
        'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true,                    // Show admin notices or not.
        'dismissable' => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message' => '',                      // Message to output right before the plugins table.
    );

    tgmpa($plugins, $config);
}