<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package izex
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
    <?php if(get_the_ID()==1878) { ?>

        <div id="secondary" class="aside" style="display: none">
            <nav class="sidebar" style="display: none">
                <ul>
                    <li style="padding-bottom: 0"> <a href="/produkciya/beton/" title="Бетон">Бетон</a></li>
                    <li style="padding-bottom: 0"> <a href="/produkciya/cementnie-rastvory/" title="Цементный раствор">Цементный раствор</a></li>
                    <li style="padding-bottom: 0"><a href="/produkciya/vzveshivanie-gruzovih-avto/" title="Взвешивание авто">Взвешивание авто</a></li>
                    <li style="padding-bottom: 0"><a href="/produkciya/avtobetononasos/" title="Услуги автобетононасоса">Услуги автобетононасоса</a></li>
                    <li style="padding-bottom: 0"><a href="/staty/" title="Статьи" >Статьи</a></li>
                    <li style="padding-bottom: 0"><a href="/sitemap/" title="Карта сайта">Карта сайта</a></li>
                </ul>
                <div class="for-sticky"></div>
            </nav>

            <?php
            }else{?>
            <div id="secondary" class="aside">
                <nav class="sidebar">
                    <ul>
                        <li style="padding-bottom: 0"> <a href="/produkciya/beton/" title="Бетон">Бетон</a></li>
                        <li style="padding-bottom: 0"> <a href="/produkciya/cementnie-rastvory/" title="Цементный раствор">Цементный раствор</a></li>
                        <li style="padding-bottom: 0"><a href="/produkciya/vzveshivanie-gruzovih-avto/" title="Взвешивание авто">Взвешивание авто</a></li>
                        <li style="padding-bottom: 0"><a href="/produkciya/avtobetononasos/" title="Услуги автобетононасоса">Услуги автобетононасоса</a></li>
                        <li style="padding-bottom: 0"><a href="/staty/" title="Статьи" >Статьи</a></li>
                        <li style="padding-bottom: 0"><a href="/sitemap/" title="Карта сайта">Карта сайта</a></li>
                    </ul>
                    <div class="for-sticky"></div>
                </nav>
           <?php }
            ?>

    <?php
    //Последние статьи Страницы Бетон и Цементный раствор
    if (get_the_ID() == 4107 ||get_the_ID() == 4170) {
        echo '<div id="articles">';
        echo '<p>Последние статьи</p>';
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $latest_posts_query = new WP_Query($args);
        if ($latest_posts_query->have_posts()) {
            echo '<ul>';
            while ($latest_posts_query->have_posts()) {
                $latest_posts_query->the_post();
                echo '<li>'."<a href=".get_permalink().">" . get_the_title() .'</a>'. '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo 'Посты не найдены.';
        }
    } // Закрывающая скобка для условия
    ?>

</div>
</div><!-- #secondary -->
