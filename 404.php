<?php
/**
 * Шаблон страницы 404 (страница не найдена) — премиум-стиль.
 *
 * @package izex
 */

get_header();
$company = function_exists('getCompanyContacts') ? getCompanyContacts() : array();
?>

    <main id="primary" class="site-main">
        <section class="error-404">
            <div class="container">
                <div class="error-404__inner">

                    <div class="error-404__code">404</div>

                    <h1 class="error-404__title">Страница не найдена</h1>
                    <p class="error-404__text">
                        Возможно, страница была перемещена или удалена, либо в адресе опечатка.
                        Давайте вернёмся к подбору станции ТОПАС.
                    </p>

                    <form role="search" method="get" class="error-404__search" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="search" name="s" placeholder="Поиск по сайту…" value="<?php echo get_search_query(); ?>">
                        <button type="submit">Найти</button>
                    </form>

                    <div class="error-404__actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--gold">На главную</a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>" class="btn btn--outline">Каталог станций</a>
                    </div>

                    <?php if (!empty($company['phone'])) : ?>
                        <p class="error-404__phone">
                            Нужна помощь? Звоните:
                            <a href="tel:<?php echo esc_attr($company['phone_clean'] ?? ''); ?>"><?php echo esc_html($company['phone']); ?></a>
                        </p>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    </main>

    <style>
        .error-404 { padding: clamp(60px, 10vw, 110px) 0; background: var(--bg-secondary, #f8fafc); }
        .error-404__inner { max-width: 620px; margin: 0 auto; text-align: center; }
        .error-404__code {
            font-size: clamp(90px, 22vw, 180px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            background: linear-gradient(135deg, var(--header-green, #21b224) 0%, #16a34a 100%);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        .error-404__title {
            font-size: clamp(1.5rem, 4vw, 2.1rem);
            font-weight: 700;
            color: var(--text, #1e293b);
            margin: 0 0 0.75rem;
        }
        .error-404__text {
            color: var(--text-muted, #64748b);
            font-size: 1.05rem;
            line-height: 1.6;
            margin: 0 auto 1.75rem;
            max-width: 520px;
        }
        .error-404__search {
            display: flex;
            gap: 0.5rem;
            max-width: 440px;
            margin: 0 auto 1.75rem;
        }
        .error-404__search input {
            flex: 1;
            padding: 0.85rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #fff;
        }
        .error-404__search input:focus {
            outline: none;
            border-color: var(--header-green, #21b224);
            box-shadow: 0 0 0 3px rgba(33, 178, 36, 0.15);
        }
        .error-404__search button {
            padding: 0.85rem 1.5rem;
            border: none;
            border-radius: 10px;
            background: var(--header-green, #21b224);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }
        .error-404__search button:hover { opacity: 0.9; }
        .error-404__actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .error-404__actions .btn { width: auto; }
        .error-404__phone { color: var(--text-muted, #64748b); font-size: 0.95rem; margin: 0; }
        .error-404__phone a { color: var(--header-green, #21b224); font-weight: 700; text-decoration: none; }
        .error-404__phone a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            .error-404__search { flex-direction: column; }
            .error-404__actions .btn { width: 100%; }
        }
    </style>

<?php
get_footer();
