<?php
/**
 * Страница выполненной работы (ТЗ 4.3).
 */
get_header();

while (have_posts()) : the_post();
    $id = get_the_ID();
    $model = carbon_get_post_meta($id, 'crb_work_model');
    $location = carbon_get_post_meta($id, 'crb_work_location');
    $before = carbon_get_post_meta($id, 'crb_work_before');
    $after = carbon_get_post_meta($id, 'crb_work_after');
    $gallery = carbon_get_post_meta($id, 'crb_work_gallery');

    $img_url = function ($val, $size = 'large') {
        if (!$val) return '';
        return is_numeric($val) ? wp_get_attachment_image_url((int) $val, $size) : $val;
    };
    ?>
    <main class="work-single">
        <div class="container">

            <nav class="work-single__crumbs" aria-label="Хлебные крошки">
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                <span>/</span>
                <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">Наши работы</a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </nav>

            <header class="work-single__head">
                <h1 class="work-single__title"><?php the_title(); ?></h1>
                <div class="work-single__meta">
                    <?php if ($model) : ?>
                        <span class="work-single__tag">Станция: <strong><?php echo esc_html($model); ?></strong></span>
                    <?php endif; ?>
                    <?php if ($location) : ?>
                        <span class="work-single__tag">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo esc_html($location); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if ($before && $after) : ?>
                <div class="work-ba">
                    <figure class="work-ba__item">
                        <img src="<?php echo esc_url($img_url($before)); ?>" alt="До монтажа" loading="lazy">
                        <figcaption>До</figcaption>
                    </figure>
                    <figure class="work-ba__item">
                        <img src="<?php echo esc_url($img_url($after)); ?>" alt="После монтажа" loading="lazy">
                        <figcaption>После</figcaption>
                    </figure>
                </div>
            <?php elseif (has_post_thumbnail()) : ?>
                <div class="work-single__cover">
                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                </div>
            <?php endif; ?>

            <?php if (get_the_content()) : ?>
                <div class="work-single__content"><?php the_content(); ?></div>
            <?php endif; ?>

            <?php if (!empty($gallery) && is_array($gallery)) : ?>
                <div class="work-gallery">
                    <?php foreach ($gallery as $gid) : $url = $img_url($gid, 'large'); if (!$url) continue; ?>
                        <a class="work-gallery__item" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url($img_url($gid, 'medium_large')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="work-single__cta">
                <button type="button" class="btn-premium btn-primary open-modal" data-modal="engineer">Хочу так же — вызвать инженера</button>
                <a class="btn-premium btn-outline" href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">← Все работы</a>
            </div>

        </div>
    </main>
<?php
endwhile;
get_footer();
