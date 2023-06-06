<?php
$id = get_the_ID();
$title = get_the_title();
//$description = get_the_excerpt();
$button_text = carbon_get_the_post_meta('button_text');
$link = carbon_get_the_post_meta('button_link');
$img = !empty(get_the_post_thumbnail_url()) ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/assets/images/no-photo.png';
?>

<div class="swiper-slide main-slider__slide main-slide"
     style="background-image: url(<?= $img ?>)" data-no-lazy="1">
    <div class="inner">
        <div class="main-slider__text-block">
            <div class="main-slider__title"><?= $title ?></div>
            <div class="main-slider__text"><?php the_content() ?>
            </div>
            <?php
            if ($link) { ?>
                <a href="<?= $link ?>" class="main-slider__btn btn"><?= $button_text ?></a>
            <?php } ?>
            <?php if (get_edit_post_link()) : ?>
                <?php
                edit_post_link(sprintf(wp_kses(__('Редактировать <span class="screen-reader-text">%s</span>', 'izex'), array('span' => array('class' => array(),),)), wp_kses_post(get_the_title())), '<span class="edit-link" style="color: #fff">', '</span>');
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<!--<div class="vacancies__item vacancies-item" data-id="--><?php //echo $id; ?><!--">-->
<!--    <div class="vacancies-item__block">-->
<!--        <div class="vacancies-item__text-block">-->
<!--            <div class="vacancies-item__date">--><?php //= $date ?><!--</div>-->
<!--            <h2 class="vacancies-item__title">--><?php //= $title ?><!--</h2>-->
<!--            <div class="vacancies-item__description">--><?php //= the_content() ?><!--</div>-->
<!--            <div class="contact contact_type_phone">-->
<!--                <div class="contact__title">Отдел кадров</div>-->
<!--                <a href="tel:--><?php //= $clearPhone ?><!--" class="contact__value">--><?php //= $phone ?><!--</a>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="vacancies-item__btn btn" data-title="--><?php //= $title ?><!--">Откликнуться</div>-->
<!--    </div>-->

<!--</div>-->

