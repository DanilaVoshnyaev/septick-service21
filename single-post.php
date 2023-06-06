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
                <style>h1{
                order: 0;
                }
                #bread_crumbs{
                /*    order: 1;*/
                }
                .products{
                order: 2;
                }
                table{
                height: 260px !important;
                }
                table td p{
                /*color: #333 !important;*/
                }
                table thead th{
                height: 10px;
                background: #eee;
                padding: 10px !important;
                }
                table tbody tr{
                height: 30px;
                }
                table tbody td{
                color: #333 !important;
                padding: 0 !important;
                line-height: 20px;
                }


                table tbody tr:nth-child(even){
                background: #eee;
                }
                .text_left{
                width: 70%;
                float: left;
                }

                .articles_block{
                position: relative;
                width: 27%;
                float: right;
                padding-top: -40px;
                }
                .articles_block p:first-of-type{
                //position: absolute;
                top: 0px;
                left: 0;
                font-size: 16px;
                border-bottom: 1px solid #999;
                }
                .articles_block p img{
                margin-left: 0 !important;
                }



                .product-pop-up {
                width: 100%;
                height: 100%;
                position: fixed;
                top: 0;
                left: 0;
                background-color: rgba(70, 67, 67, 0.88);
                overflow: hidden;
                z-index: 10000;
                }

                .pop-up-mess {
                display: flex;
                position: absolute;
                top: 50%;
                left: 50%;
                width: 60%;
                height: auto;
                transform: translate(-50%,-50%);
                }


                .quantity-block{
                display: flex;
                flex-direction: column;
                }
                .quantity-text{
                font-weight: 300;
                font-size: 15px;
                line-height: 1.5;
                color: #000000;
                }
                .quantity-control{
                width: 43.41px;
                height: 38.89px;
                background: #FFFFFF;
                border: 1px solid #CCCCCC;
                box-sizing: border-box;
                display: flex;
                justify-content: center;
                align-items: center;
                font-weight: 300;
                font-size: 18px;
                line-height: 1.5;
                color: #262626;
                cursor: pointer;
                }
                #quantity-num{
                width: 55px;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                border: 1px solid #CCCCCC;
                }
                .count{
                border: 1px solid #ccc;
                width: 151px;
                }
                #basket_table .count_choose{
                margin-right: 0;
                }
                .count_choose{
                width: 340px;
                justify-content: space-between;
                display: flex;
                font-size: 18px;
                margin-bottom: 45px;
                }
                .count_choose .count{
                width: 140px;
                height: 40px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background: #fff;
                }
                .count_gallery_choose{
                position: absolute;
                bottom: 10%;
                left: 38%;
                }
                .count_minus, .count_plus{
                cursor: pointer;
                width: 40px;
                height: 38px;
                line-height: 40px;
                background-color: #fff;
                text-align: center;
                transition: background-color 0.15s ease;
                }
                .count_minus:hover, .count_plus:hover{
                background-color: #f9f9f9;
                }
                .count_minus{
                float: left;
                border-radius: 4px 0 0 4px;
                }
                .count_plus{
                float: right;
                font-size: 24px;
                border-radius: 0 4px 4px 0;
                }
                .qty {
                height: 38px;
                width: 58px;
                text-align: center;
                padding: 0 10px;
                border: none;
                border-left: 1px solid #ccc;
                border-right: 1px solid #ccc;
                }
                .order-btn{
                width: 167px;
                height: 41px;
                background: #FF6600;
                color: #fff;
                font-weight: 600;
                font-size: 15px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                transition: .3s all;
                margin: 0 auto;
                margin-top: 10px;
                }
                .order-btn:hover{
                background: #d25401;
                }

                .text_left div{
                max-width: 100%;
                }</style>
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
