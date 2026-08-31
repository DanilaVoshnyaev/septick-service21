<?php
$id = get_the_ID();
$title = get_the_title();
//$description = get_the_excerpt();
$date = get_the_date('', $id);
$phone = carbon_get_the_post_meta('contact_phone');
$clearPhone = '+' . preg_replace("/[^0-9]/", '', $phone);;
//$link = get_the_permalink();
//$img = !empty(get_the_post_thumbnail_url()) ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/assets/images/no-photo.png';
?>
<div class="vacancies__item vacancies-item" data-id="<?php echo $id; ?>">
    <div class="vacancies-item__block">
        <div class="vacancies-item__text-block">
            <div class="vacancies-item__date"><?= $date ?></div>
            <h2 class="vacancies-item__title"><?= $title ?></h2>
            <div class="vacancies-item__description"><?= the_content() ?></div>
            <div class="contact contact_type_phone">
                <div class="contact__title">Отдел кадров</div>
                <a href="tel:<?=$clearPhone?>" class="contact__value"><?=$phone?></a>
            </div>
        </div>
        <div class="vacancies-item__btn btn" data-title="<?= $title ?>">Откликнуться</div>
    </div>
    <?php if (get_edit_post_link()) : ?>
        <?php
        edit_post_link(sprintf(wp_kses(__('Редактировать <span class="screen-reader-text">%s</span>', 'izex'), array('span' => array('class' => array(),),)), wp_kses_post(get_the_title())), '<span class="edit-link">', '</span>');
        ?>
    <?php endif; ?>
</div>

