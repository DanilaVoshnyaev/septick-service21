<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package izex
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

        <?php //izex_post_thumbnail(); ?>

        <div class="entry-content">
            <?php
            the_content();
            ?>
        </div><!-- .entry-content -->

        <?php if (get_edit_post_link()) : ?>
            <?php
            edit_post_link(sprintf(wp_kses(__('Редактировать <span class="screen-reader-text">%s</span>', 'izex'), array('span' => array('class' => array(),),)), wp_kses_post(get_the_title())), '<span class="edit-link">', '</span>');
            ?>
        <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
