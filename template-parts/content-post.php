<?php
$title = get_the_title();
$description = get_the_excerpt();
$link = get_the_permalink();
$img = !empty(get_the_post_thumbnail_url()) ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/assets/images/no-photo.png';
?>
<div class="services__item service-item" data-id="<?php the_ID(); ?>">
    <a href="<?= $link ?>" class="service-item__block">
        <img src="<?= $img ?>" alt="<?= $title ?>" class="service-item__img">
        <div class="service-item__text-block">
            <h2 class="service-item__title"><?= $title ?></h2>
            <div class="service-item__description"><?= $description ?></div>
        </div>
    </a>
    <?php if (get_edit_post_link()) : ?>
        <?php
        edit_post_link(sprintf(wp_kses(__('Редактировать <span class="screen-reader-text">%s</span>', 'izex'), array('span' => array('class' => array(),),)), wp_kses_post(get_the_title())), '<span class="edit-link">', '</span>');
        ?>
    <?php endif; ?>
</div>

