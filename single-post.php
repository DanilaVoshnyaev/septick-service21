<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package izex
 */

get_header();
?>

	<!--<main id="primary" class="site-main">
        <div class="inner" style="display: flex;justify-content: space-between;flex-direction: row;">
            <div class="text" style="font-weight: 500;max-width: 810px">
            <article  class="text" id="post-<?//php /*the_ID(); */*/?>" --><?/*//php /*post_class(); */?>
                <?//php
/*                if (is_singular()) :
                    the_title('<h1 class="entry-title">', '</h1>');
                else :
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                endif;
                */?>

                <?//php //izex_post_thumbnail(); */?>

       <!--         <div class="entry-content">
                    <?/*php
                        the_content(
                            sprintf(
                                wp_kses(
                             translators: %s: Name of current post. Only visible to screen readers
                                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'izex'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post(get_the_title())
                            )
                        );

                    */?>
                </div>
                <?php /*//izex_entry_footer(); */?>
            </div>   </article> #post-<?//php /*the_ID(); */?> -->

            <?//php /*get_sidebar(); */?>
       <!-- </div>
	</main> -->

        <?php  if (get_the_ID()==4108){  ?>
            <div class="text" id="text">
                <?php the_content() ?>
                <?php /* Стили вынесены в legacy-product.css (подключается для is_single(4108)) */ ?>
            </div>
        <?php } elseif(get_the_id()==4190){?>

            <div id="wrap">
                <div class="text" id="text"  style="margin-bottom: 600px;">
                    <?php the_content(); ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        <?php }else{?>
            <div id="wrap">
                <div class="text" id="text"  style="margin-bottom: 600px;">
                    <?php the_content(); ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        <?php }?>
    </div>
    </div>


<?php

get_footer();
