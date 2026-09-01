/**
 * Калькулятор подбора и расчёта стоимости ТОПАС «под ключ».
 *
 * Было: визард из 4 шагов — цена показывалась только на последнем экране.
 * Стало (по прототипу заказчика): один экран, вопросы слева, карточка
 * рекомендации справа, пересчёт на каждый клик.
 *
 * Подбор: сначала ёмкость (ближайшая «сверху» из тех, что есть в каталоге),
 * затем исполнение внутри этой ёмкости — Пр / Лонг / Лонг Ус (см. pickStation).
 * Стоимость — оборудование + доставка + монтаж, надбавки только за опции,
 * которых в выбранной модели нет; вилка расширяется коэффициентами грунта
 * и удалённости.
 *
 * Данные станций и настройки приходят из PHP через wp_localize_script (topasCalc).
 */
(function () {
    'use strict';

    var data = window.topasCalc || { stations: [], settings: {} };
    var stations = Array.isArray(data.stations) ? data.stations : [];
    var s = data.settings || {};

    // Начиная с этого числа проживающих отправляем на индивидуальный расчёт:
    // ТОПАС-С 8 и 10 считаем вручную.
    var BIG_HOUSE_FROM = 7;

    function num(v, def) {
        var n = parseFloat(v);
        return isFinite(n) ? n : def;
    }

    var settings = {
        installBase: num(s.installBase, 35000),
        delivery: num(s.delivery, 0),
        surchargeForced: num(s.surchargeForced, 15000),
        surchargeUgv: num(s.surchargeUgv, 10000),
        surchargeLong: num(s.surchargeLong, 6000),
        surchargeLongUs: num(s.surchargeLongUs, 12000),
        soilCoeff: num(s.soilCoeff, 10),
        remotenessCoeff: num(s.remotenessCoeff, 10),
        note: s.note || 'Это ориентировочный расчёт. Точная смета — после бесплатного выезда инженера.',
        catalogUrl: s.catalogUrl || ''
    };

    function money(v) {
        return Math.max(0, Math.round(v)).toLocaleString('ru-RU') + ' ₽';
    }

    // Округляем «красиво» — до 500 ₽.
    function roundNice(v) {
        return Math.round(v / 500) * 500;
    }

    // ===== Подбор ёмкости и исполнения =====
    //
    // В каталоге каждое исполнение — отдельный пост: «ТОПАС-С 4» и «ТОПАС-С 4 Пр»,
    // «ТОПАС-С 9 Лонг», «ТОПАС-С 9 Лонг Пр», «ТОПАС-С 9 Пр». Прежний подбор брал
    // первый пост с ёмкостью не меньше числа проживающих и дописывал к его
    // названию свой суффикс. Из-за этого 1–4 человека при самотёке получали
    // «ТОПАС-С 4 Пр» (станция с насосом), а 6 человек — «ТОПАС-С 9 Лонг Пр»
    // вместо станции своей ёмкости, то есть +42 % к цене.
    //
    // Теперь подбор двухшаговый: сначала ёмкость (ближайшая «сверху» из тех, что
    // есть в каталоге), затем исполнение внутри этой ёмкости. Надбавки на монтаж
    // начисляются только за те опции, которых в выбранной модели нет.

    // Название модели без исполнения: «ТОПАС-С 9 Лонг Пр» → «ТОПАС-С 9».
    // Нужно, чтобы собирать имя в привычном порядке «<модель> Лонг Пр»,
    // а не приписывать «Лонг» после уже имеющегося «Пр».
    function baseTitle(title) {
        //  в JS — ASCII-граница слова, для кириллицы не работает,
        // поэтому границу проверяем через пробел/конец строки.
        return String(title || '')
            .replace(/\s*(лонг\s*ус|лонг|пр)(?=\s|$)/ig, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    // Разбор исполнения из названия поста.
    function parseVariant(title) {
        var t = String(title || '');
        var longus = /лонг\s*ус/i.test(t);
        return {
            forced: /(^|\s)пр(\s|$)/i.test(t),
            long: !longus && /(^|\s)лонг(\s|$)/i.test(t),
            longus: longus
        };
    }

    // Запрошенное исполнение из ответов.
    function wantedVariant(answers) {
        return {
            forced: answers.disposal === 'forced',
            long: answers.depth === 'long',
            longus: answers.depth === 'longus'
        };
    }

    function depthLevel(v) {
        return v.longus ? 2 : (v.long ? 1 : 0);
    }

    // Лишняя опция в модели (насос при самотёке, удлинённая горловина при
    // стандартном входе) — это другая станция и переплата, поэтому весит намного
    // больше отсутствующей: недостающую опцию добираем надбавкой на монтаж.
    var W_EXTRA_FORCED = 1000;
    var W_EXTRA_DEPTH = 400;
    var W_MISSING = 10;

    function capacities() {
        var list = [];
        for (var i = 0; i < stations.length; i++) {
            if (list.indexOf(stations[i].number) === -1) list.push(stations[i].number);
        }
        list.sort(function (a, b) { return a - b; });
        return list;
    }

    // Ближайшая «сверху» ёмкость из каталога; если таких нет — самая большая.
    function pickCapacity(people) {
        var list = capacities();
        if (!list.length) return null;
        for (var i = 0; i < list.length; i++) {
            if (list[i] >= people) return list[i];
        }
        return list[list.length - 1];
    }

    function pickStation(people, answers) {
        if (!stations.length) return null;
        var cap = pickCapacity(people);
        var want = wantedVariant(answers);
        var wantDepth = depthLevel(want);
        var best = null;
        var bestScore = Infinity;

        for (var i = 0; i < stations.length; i++) {
            var st = stations[i];
            if (st.number !== cap) continue;

            var have = parseVariant(st.title);
            var haveDepth = depthLevel(have);
            var score = 0;

            if (have.forced && !want.forced) score += W_EXTRA_FORCED;
            if (!have.forced && want.forced) score += W_MISSING;
            if (haveDepth > wantDepth) score += W_EXTRA_DEPTH * (haveDepth - wantDepth);
            if (haveDepth < wantDepth) score += W_MISSING * (wantDepth - haveDepth);

            if (score < bestScore ||
                (score === bestScore && best && num(st.price, 0) < num(best.price, 0))) {
                best = st;
                bestScore = score;
            }
        }

        return best;
    }

    // Ёмкости 6 и 8 в каталоге пока нет: подставлять вместо них станцию на 9–10
    // человек — это +42 % к цене и заведомо неверная рекомендация. В таком
    // случае уводим на индивидуальный подбор, а не показываем чужую модель.
    // Как только посты «ТОПАС-С 6» / «ТОПАС-С 8» появятся в каталоге,
    // калькулятор подхватит их сам.
    function capacityTooBig(st, people) {
        if (!st) return false;
        var list = capacities();
        if (!list.length) return false;
        // Для домов меньше самой маленькой станции запас нормален.
        if (people <= list[0]) return false;
        return st.number - people >= 2;
    }

    // Расчёт по ответам: модель, разбивка и вилка цены. Чистая функция —
    // используется и в юнит-тестах (см. tests/calculator.test.js).
    function computeEstimate(answers) {
        var people = parseInt(answers.people, 10) || 0;
        var st = pickStation(people, answers);
        var have = st ? parseVariant(st.title) : { forced: false, long: false, longus: false };
        var want = wantedVariant(answers);

        var install = settings.installBase;
        // Суффикс дописываем только за опции, которых нет в самой модели:
        // «ТОПАС-С 4» + принудительное = «ТОПАС-С 4 Пр», а уже готовое
        // «ТОПАС-С 9 Лонг Пр» остаётся как есть.
        var depthSuffix = '';
        var forcedSuffix = '';
        if (want.forced && !have.forced) {
            install += settings.surchargeForced;
            forcedSuffix = 'Пр';
        }
        if (want.longus && !have.longus) {
            install += settings.surchargeLongUs;
            depthSuffix = 'Лонг Ус';
        } else if (want.long && !have.long && !have.longus) {
            install += settings.surchargeLong;
            depthSuffix = 'Лонг';
        }
        if (answers.ugv === 'yes') {
            install += settings.surchargeUgv;
        }

        // Имя собираем целиком из базового названия и итогового исполнения —
        // модель поста плюс опции, которых в ней не было.
        var finalDepth = have.longus || want.longus ? 'Лонг Ус'
            : (have.long || want.long ? 'Лонг' : '');
        var finalForced = have.forced || want.forced ? 'Пр' : '';
        var modelName = st
            ? [baseTitle(st.title), finalDepth, finalForced].filter(Boolean).join(' ')
            : 'Модель по запросу';

        var equipment = st ? num(st.price, 0) : 0;
        var low = equipment + settings.delivery + install;
        var high = low * (1 + (settings.soilCoeff + settings.remotenessCoeff) / 100);

        return {
            station: st,
            modelName: modelName,
            equipment: equipment,
            install: install,
            low: roundNice(low),
            high: roundNice(high),
            params: 'Проживающих: ' + people +
                '; водоотведение: ' + (answers.disposal === 'forced' ? 'принудительное' : 'самотёк') +
                '; глубина: ' + (answers.depth === 'long' ? 'Лонг' : (answers.depth === 'longus' ? 'Лонг Ус' : 'стандарт')) +
                '; высокий УГВ: ' + (answers.ugv === 'yes' ? 'да' : 'нет')
        };
    }

    function each(list, fn) {
        Array.prototype.forEach.call(list, fn);
    }

    function esc(v) {
        return String(v == null ? '' : v)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function initCalc(root) {
        var form = root.querySelector('[data-calc-form]');
        var resultBox = root.querySelector('[data-calc-result]');
        if (!form || !resultBox) return;

        // Значения по умолчанию совпадают с кнопками, помеченными is-active в разметке:
        // человек не выбран, остальное — самый частый случай.
        var answers = { people: '', disposal: 'gravity', depth: 'standard', ugv: 'no' };

        var sticky = initStickyBar(root);

        function compute() {
            return computeEstimate(answers);
        }

        // Состояние «ещё не выбрано число человек» — без него расчёт бессмысленен.
        function renderPlaceholder() {
            resultBox.innerHTML =
                '<div class="calc-live__placeholder">' +
                    '<span class="calc-live__result-eyebrow">Шаг 1</span>' +
                    '<div class="calc-live__placeholder-title">Выберите количество человек</div>' +
                    '<p class="calc-live__placeholder-text">Слева отметьте, сколько человек живёт в доме — и мы сразу ' +
                    'покажем рекомендованную модель и примерную цену «под ключ».</p>' +
                '</div>';
        }

        // Индивидуальный подбор: модели нужной ёмкости в каталоге нет.
        function renderIndividual(title, text, product, params) {
            resultBox.innerHTML =
                '<div class="calc-live__placeholder">' +
                    '<span class="calc-live__result-eyebrow">Индивидуальный подбор</span>' +
                    '<div class="calc-live__placeholder-title">' + esc(title) + '</div>' +
                    '<p class="calc-live__placeholder-text">' + esc(text) + '</p>' +
                    '<div class="calc-live__actions" style="margin-top:18px">' +
                        '<button type="button" class="calc-live__cta" data-calc-cta ' +
                            'data-product="' + esc(product) + '" ' +
                            'data-params="' + esc(params) + '" data-range="">Рассчитать индивидуально</button>' +
                    '</div>' +
                '</div>';
        }

        function renderResult() {
            var r = compute();
            var st = r.station;

            var img = st && st.img
                ? '<img class="calc-live__result-img" src="' + esc(st.img) + '" alt="' + esc(r.modelName) + '" loading="lazy">'
                : '';

            var specs = '';
            if (st && st.specs && st.specs.length) {
                specs = '<ul class="calc-live__specs">' + st.specs.map(function (row) {
                    return '<li><span>' + esc(row.label) + '</span><b>' + esc(row.value) + '</b></li>';
                }).join('') + '</ul>';
            }

            var breakdown = '<ul class="calc-live__specs">' +
                '<li><span>Станция</span><b>' + (r.equipment > 0 ? money(r.equipment) : 'по запросу') + '</b></li>' +
                '<li><span>Монтаж «под ключ»</span><b>' + money(r.install) + '</b></li>' +
                '<li><span>Доставка по Чувашии</span><b>' + (settings.delivery > 0 ? money(settings.delivery) : 'бесплатно') + '</b></li>' +
                '</ul>';

            var catalogLink = settings.catalogUrl
                ? '<a class="calc-live__link" href="' + esc(settings.catalogUrl) + '">Весь каталог</a>'
                : '';

            var range = money(r.low) + ' – ' + money(r.high);
            sticky.set(r.modelName, range, r.params);

            resultBox.innerHTML =
                '<span class="calc-live__result-eyebrow">Рекомендуем вам</span>' +
                '<h3 class="calc-live__result-name">' + esc(r.modelName) + '</h3>' +
                (st && st.peopleStr ? '<p class="calc-live__result-people">' + esc(st.peopleStr) + '</p>' : '') +
                img +
                specs +
                breakdown +
                '<div class="calc-live__total">' +
                    '<div class="calc-live__total-label">Под ключ</div>' +
                    '<div class="calc-live__total-value">' + range + '</div>' +
                    '<div class="calc-live__total-note">Точную сумму фиксируем в договоре ' +
                        'до начала работ</div>' +
                '</div>' +
                '<div class="calc-live__actions">' +
                    '<button type="button" class="calc-live__cta" data-calc-cta ' +
                        'data-product="' + esc(r.modelName) + '" ' +
                        'data-params="' + esc(r.params) + '" ' +
                        'data-range="' + esc(range) + '">Получить точный расчёт</button>' +
                    catalogLink +
                '</div>' +
                '<p class="calc-live__note">' + esc(settings.note) + '</p>';
        }

        function render() {
            var people = parseInt(answers.people, 10) || 0;
            if (!people) {
                sticky.clear();
                renderPlaceholder();
                return;
            }
            if (people >= BIG_HOUSE_FROM) {
                sticky.clear();
                renderIndividual(
                    'Большой дом на 7+ человек',
                    'Для такого объёма подойдут ТОПАС-С 8 или ТОПАС-С 10. Подберём и рассчитаем ' +
                    'индивидуально — это займёт пару минут.',
                    'Индивидуальный подбор (7+ человек)',
                    'Проживающих: 7 и более'
                );
                return;
            }

            var st = pickStation(people, answers);
            if (!st) {
                sticky.clear();
                renderIndividual(
                    'Подберём модель вручную',
                    'Каталог сейчас обновляется. Оставьте заявку — инженер подберёт станцию и назовёт цену.',
                    'Индивидуальный подбор',
                    'Проживающих: ' + people
                );
                return;
            }
            // Ёмкости 6 и 8 в каталоге нет: показывать вместо них станцию на
            // 9–10 человек нельзя — это другая модель и +42 % к цене.
            if (capacityTooBig(st, people)) {
                sticky.clear();
                renderIndividual(
                    'Дом на ' + people + ' человек',
                    'Для этого объёма нужна ТОПАС-С ' + people + ' — её цену инженер назовёт ' +
                    'индивидуально, вместе с монтажом под ваш участок.',
                    'Индивидуальный подбор (ТОПАС-С ' + people + ')',
                    'Проживающих: ' + people
                );
                return;
            }

            renderResult();
        }

        each(form.querySelectorAll('[data-calc-opt]'), function (btn) {
            btn.addEventListener('click', function () {
                var name = btn.getAttribute('data-name');
                answers[name] = btn.getAttribute('data-value');

                // Подсветка выбора в пределах своей группы.
                var group = btn.parentNode;
                each(group.querySelectorAll('[data-calc-opt]'), function (el) {
                    el.classList.toggle('is-active', el === btn);
                });

                render();
            });
        });

        // CTA «Получить точный расчёт» — предзаполняем и открываем модалку заказа.
        // Слушаем на document: та же кнопка есть в липкой панели, а она лежит
        // в <body>, а не внутри калькулятора.
        document.addEventListener('click', function (e) {
            var cta = e.target.closest('[data-calc-cta]');
            if (!cta) return;
            openOrderModal(
                cta.getAttribute('data-product'),
                cta.getAttribute('data-params'),
                cta.getAttribute('data-range')
            );
        });

        render();
    }

    // ===== Липкая панель с подобранной моделью (задача #26) =====
    //
    // После расчёта пользователь уходит вниз по странице и теряет результат:
    // модель и цену приходится искать заново. Панель закрепляется внизу, когда
    // калькулятор ушёл из виду, и держит при себе кнопку заявки — выбранная
    // модель и параметры уезжают в форму и дальше в CRM тем же путём, что и из
    // самого калькулятора. На мобильных не показываем: там снизу уже стоит
    // .mobile-action-bar.
    function createStickyBar() {
        var el = document.createElement('div');
        el.className = 'calc-sticky';
        el.setAttribute('aria-live', 'polite');
        el.hidden = true;
        el.innerHTML =
            '<div class="calc-sticky__info">' +
                '<span class="calc-sticky__eyebrow">Ваш подбор</span>' +
                '<span class="calc-sticky__model" data-sticky-model></span>' +
            '</div>' +
            '<div class="calc-sticky__price">' +
                '<span class="calc-sticky__eyebrow">Под ключ</span>' +
                '<span class="calc-sticky__value" data-sticky-price></span>' +
            '</div>' +
            '<button type="button" class="calc-sticky__cta" data-calc-cta ' +
                'data-product="" data-params="" data-range="">Получить точный расчёт</button>' +
            '<button type="button" class="calc-sticky__close" aria-label="Скрыть панель">×</button>';
        document.body.appendChild(el);

        el.querySelector('.calc-sticky__close').addEventListener('click', function () {
            el.hidden = true;
            el.dataset.dismissed = '1';
        });

        return el;
    }

    function initStickyBar(root) {
        var bar = null;
        var state = null;   // последний расчёт или null

        // Панель нужна, только когда калькулятор уехал из видимой области.
        var outOfView = false;
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                outOfView = !entries[0].isIntersecting;
                sync();
            }, { threshold: 0 }).observe(root);
        }

        function sync() {
            if (!state) {
                if (bar) bar.hidden = true;
                return;
            }
            if (!bar) bar = createStickyBar();
            if (bar.dataset.dismissed === '1') return;

            bar.querySelector('[data-sticky-model]').textContent = state.model;
            bar.querySelector('[data-sticky-price]').textContent = state.range;
            var cta = bar.querySelector('[data-calc-cta]');
            cta.setAttribute('data-product', state.model);
            cta.setAttribute('data-params', state.params);
            cta.setAttribute('data-range', state.range);
            bar.hidden = !outOfView;
        }

        return {
            // Расчёт готов: модель, вилка цены, параметры.
            set: function (model, range, params) {
                state = { model: model, range: range, params: params };
                // Новый расчёт — панель снова актуальна, даже если её закрывали.
                if (bar) delete bar.dataset.dismissed;
                sync();
            },
            // Числа человек не выбрано или ушли на индивидуальный подбор.
            clear: function () {
                state = null;
                sync();
            }
        };
    }

    // Открытие существующей модалки заказа с предзаполнением выбранных параметров.
    function openOrderModal(product, params, range) {
        var modal = document.getElementById('modal-order');
        if (!modal) return;

        var field = modal.querySelector('.js-order-product-field');
        var label = modal.querySelector('.js-order-product');
        var comment = modal.querySelector('[name="comment"]');

        if (field) field.value = product || '';
        if (label && product) {
            label.textContent = 'Подбор: ' + product;
            label.hidden = false;
        }
        if (comment) {
            comment.value = 'Расчёт из калькулятора. ' + (params || '') +
                (range ? '. Ориентировочно: ' + range : '');
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Экспорт для юнит-тестов: в браузере module не определён.
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = {
            computeEstimate: computeEstimate,
            pickStation: pickStation,
            capacityTooBig: capacityTooBig,
            parseVariant: parseVariant,
            BIG_HOUSE_FROM: BIG_HOUSE_FROM
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        // .calc — разметка прежнего визарда (оставлена для обратной совместимости).
        each(document.querySelectorAll('[data-calc-root], .calc'), initCalc);
    });
})();
