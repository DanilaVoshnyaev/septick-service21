<?php
/**
 * Шаблон страницы «О компании» — премиум-стиль
 * Template Name: О компании
 */

get_header();
?>

    <!-- 🎨 СТИЛИ (цвета из хедера) -->
    <style>
        :root {
            --gold: #d4af37;
            --gold-hover: #f4d03f;
            --blue: #2563eb;
            --blue-hover: #1d4ed8;
            --text: #1e293b;
            --text-muted: #64748b;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .about-page { padding: 2rem 0; background: var(--bg); }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

        /* Hero */
        .about-hero {
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--bg) 100%);
            border-radius: var(--radius); padding: 3rem 2rem; text-align: center;
            margin-bottom: 3rem; box-shadow: var(--shadow); border-top: 4px solid var(--gold);
        }
        .about-hero__title {
            font-size: 2rem; font-weight: 700; color: var(--text); margin: 0 0 1rem;
        }
        .about-hero__subtitle {
            font-size: 1.1rem; color: var(--text-muted); max-width: 700px; margin: 0 auto 1.5rem;
        }
        .about-hero__cta {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.85rem 1.75rem; background: var(--gold); color: #0f172a;
            border-radius: 8px; text-decoration: none; font-weight: 600;
            transition: var(--transition);
        }
        .about-hero__cta:hover {
            background: var(--gold-hover); transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.35);
        }
        .about-hero__cta .icon { width: 18px; height: 18px; }

        /* Секции */
        .about-section {
            background: var(--card-bg); border-radius: var(--radius);
            padding: 2rem; margin-bottom: 1.5rem; box-shadow: var(--shadow);
        }
        .section-title {
            font-size: 1.4rem; font-weight: 600; color: var(--text);
            margin: 0 0 1.25rem; display: flex; align-items: center; gap: 0.6rem;
        }
        .section-title .icon { width: 24px; height: 24px; color: var(--gold); }
        .section-content { color: var(--text-muted); line-height: 1.7; font-size: 1rem; }
        .section-content p { margin: 0 0 1rem; }
        .section-content p:last-child { margin-bottom: 0; }
        .section-content strong { color: var(--text); font-weight: 600; }

        /* Статистика */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem; margin-top: 1.5rem;
        }
        .stat-item {
            text-align: center; padding: 1.25rem; background: var(--bg);
            border-radius: var(--radius); border: 1px solid var(--border);
        }
        .stat-number {
            font-size: 2rem; font-weight: 700; color: var(--gold);
            display: block; margin-bottom: 0.25rem;
        }
        .stat-label { font-size: 0.9rem; color: var(--text-muted); }

        /* География */
        .geo-list {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem; list-style: none; padding: 0; margin: 0;
        }
        .geo-list li {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 0; color: var(--text); font-size: 0.95rem;
        }
        .geo-list .icon { width: 16px; height: 16px; color: var(--blue); flex-shrink: 0; }

        /* Процесс */
        .process-steps {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem; margin-top: 1rem;
        }
        .process-step {
            position: relative; padding-left: 3rem;
        }
        .process-step__number {
            position: absolute; left: 0; top: 0;
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--gold); color: #0f172a;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem;
        }
        .process-step__title {
            font-weight: 600; color: var(--text); margin: 0 0 0.4rem;
        }
        .process-step__desc {
            font-size: 0.9rem; color: var(--text-muted); margin: 0; line-height: 1.5;
        }

        /* Преимущества */
        .benefits-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem; margin-top: 1rem;
        }
        .benefit-item {
            display: flex; gap: 0.75rem; padding: 1rem;
            background: var(--bg); border-radius: var(--radius);
        }
        .benefit-item .icon {
            width: 24px; height: 24px; color: var(--gold); flex-shrink: 0; margin-top: 2px;
        }
        .benefit-item__title {
            font-weight: 600; color: var(--text); margin: 0 0 0.3rem; font-size: 1rem;
        }
        .benefit-item__desc {
            font-size: 0.9rem; color: var(--text-muted); margin: 0; line-height: 1.5;
        }

        /* CTA блок */
        .about-cta {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            border-radius: var(--radius); padding: 2.5rem 2rem; text-align: center;
            margin-top: 2rem;
        }
        .about-cta h2 {
            color: #0f172a; font-size: 1.5rem; margin: 0 0 0.75rem;
        }
        .about-cta p {
            color: rgba(15, 23, 42, 0.85); margin: 0 0 1.5rem; max-width: 600px; margin-left: auto; margin-right: auto;
        }
        .about-cta .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.85rem 1.75rem; background: #0f172a; color: #fff;
            border-radius: 8px; text-decoration: none; font-weight: 600;
            transition: var(--transition); border: none; cursor: pointer;
        }
        .about-cta .btn:hover {
            background: #1e293b; transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.3);
        }
        .about-cta .btn .icon { width: 18px; height: 18px; }
        .about-cta__note {
            color: rgba(15, 23, 42, 0.7); font-size: 0.85rem; margin-top: 1rem;
        }

        /* Адаптив */
        @media (max-width: 768px) {
            .about-hero { padding: 2rem 1.5rem; }
            .about-hero__title { font-size: 1.5rem; }
            .about-section { padding: 1.5rem; }
            .section-title { font-size: 1.2rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .geo-list { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .process-steps { grid-template-columns: 1fr; }
        }

        /* SVG */
        .icon { display: inline-block; vertical-align: middle; }
    </style>

    <!-- 🔷 SVG СПРАЙТ -->
    <svg style="display:none">
        <symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
        <symbol id="icon-map" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
        <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
        <symbol id="icon-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></symbol>
        <symbol id="icon-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
        <symbol id="icon-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
        <symbol id="icon-leaf" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></symbol>
        <symbol id="icon-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></symbol>
        <symbol id="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
    </svg>

    <main class="about-page">
        <div class="container">

            <!-- Hero -->
            <section class="about-hero">
                <h1 class="about-hero__title">О компании</h1>
                <p class="about-hero__subtitle">
                    Профессиональная установка автономных канализаций ТОПАС в Чувашской Республике.
                    Надёжно • Быстро • С гарантией
                </p>
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', getCarbonFields('theme_phones')[0]['phone_numbers'][0]['phone_number'] ?? '+74998400555'); ?>" class="about-hero__cta">
                    <svg class="icon"><use href="#icon-phone"/></svg>
                    +7 (499) 840-05-55
                </a>
            </section>

            <!-- О нас -->
            <section class="about-section">
                <h2 class="section-title">
                    <svg class="icon"><use href="#icon-tool"/></svg>
                    Чем мы занимаемся
                </h2>
                <div class="section-content">
                    <p>Наша компания специализируется на <strong>продаже и профессиональном монтаже автономных канализаций «ТОПАС»</strong> на территории Чувашской Республики и соседних регионов.</p>
                    <p>Мы работаем напрямую с заводом-производителем, что позволяет предлагать нашим клиентам <strong>официальную гарантию</strong>, сертифицированное оборудование и честные цены без посредников.</p>
                    <p>Каждый объект для нас — это не просто установка, а комплексное решение: от бесплатного выезда инженера и подбора модели до запуска системы и обучения эксплуатации.</p>
                </div>
            </section>

            <!-- Статистика -->
            <section class="about-section">
                <h2 class="section-title">
                    <svg class="icon"><use href="#icon-star"/></svg>
                    Наши результаты
                </h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Установленных станций</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">7+</span>
                        <span class="stat-label">Лет на рынке</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Довольных клиентов</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">21</span>
                        <span class="stat-label">Район в Чувашии</span>
                    </div>
                </div>
            </section>

            <!-- География -->
            <section class="about-section">
                <h2 class="section-title">
                    <svg class="icon"><use href="#icon-map"/></svg>
                    Работаем по Чувашской Республике
                </h2>
                <div class="section-content">
                    <p>Мы обслуживаем частные дома, коттеджные посёлки и небольшие предприятия по всей республике. Выезд инженера и монтаж возможен в любой населённый пункт.</p>
                </div>
                <ul class="geo-list">
                    <li><svg class="icon"><use href="#icon-check"/></svg> Чебоксары</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Новочебоксарск</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Канаш</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Алатырь</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Шумерля</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Цивильск</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Мариинский Посад</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Козловка</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Ядрин</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Вурнары</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Ибреси</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Урмары</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Комсомольское</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Красные Четаи</li>
                    <li><svg class="icon"><use href="#icon-check"/></svg> Аликово</li>
                </ul>
            </section>

            <!-- Как мы работаем -->
            <section class="about-section">
                <h2 class="section-title">
                    <svg class="icon"><use href="#icon-clock"/></svg>
                    Этапы работы
                </h2>
                <div class="process-steps">
                    <div class="process-step">
                        <span class="process-step__number">1</span>
                        <h4 class="process-step__title">Заявка</h4>
                        <p class="process-step__desc">Вы оставляете заявку или звоните нам</p>
                    </div>
                    <div class="process-step">
                        <span class="process-step__number">2</span>
                        <h4 class="process-step__title">Выезд инженера</h4>
                        <p class="process-step__desc">Бесплатный замер участка и подбор модели</p>
                    </div>
                    <div class="process-step">
                        <span class="process-step__number">3</span>
                        <h4 class="process-step__title">Монтаж</h4>
                        <p class="process-step__desc">Установка за 1 день, подключение, запуск</p>
                    </div>
                    <div class="process-step">
                        <span class="process-step__number">4</span>
                        <h4 class="process-step__title">Гарантия</h4>
                        <p class="process-step__desc">Инструкция, сервис, поддержка 24/7</p>
                    </div>
                </div>
            </section>

            <!-- Преимущества -->
            <section class="about-section">
                <h2 class="section-title">
                    <svg class="icon"><use href="#icon-shield"/></svg>
                    Почему выбирают нас
                </h2>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Официальный дилер</h4>
                            <p class="benefit-item__desc">Работаем напрямую с заводом, полная гарантия</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Монтаж за 1 день</h4>
                            <p class="benefit-item__desc">Собственная бригада, без субподрядчиков</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Честная цена</h4>
                            <p class="benefit-item__desc">Фиксируем стоимость в договоре, без доплат</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Сервис 24/7</h4>
                            <p class="benefit-item__desc">Консультации, обслуживание, запчасти</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Эко-стандарт</h4>
                            <p class="benefit-item__desc">Очистка 98%, безопасно для почвы и воды</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <div>
                            <h4 class="benefit-item__title">Рассрочка 0%</h4>
                            <p class="benefit-item__desc">Возможность оплаты частями без переплат</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="about-cta">
                <h2>Готовы обсудить ваш проект?</h2>
                <p>Оставьте заявку — инженер бесплатно проконсультирует и подберёт оптимальное решение для вашего участка в Чувашии</p>
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', getCarbonFields('theme_phones')[0]['phone_numbers'][0]['phone_number'] ?? '+74998400555'); ?>" class="btn">
                    <svg class="icon"><use href="#icon-phone"/></svg>
                    Позвонить сейчас
                </a>
                <p class="about-cta__note">Или напишите в мессенджер — ответим в течение 15 минут</p>
            </section>

        </div>
    </main>

<?php get_footer(); ?>