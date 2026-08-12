/**
 * Калькулятор подбора и расчёта стоимости ТОПАС «под ключ».
 *
 * Было: визард из 4 шагов — цена показывалась только на последнем экране.
 * Стало (по прототипу заказчика): один экран, вопросы слева, карточка
 * рекомендации справа, пересчёт на каждый клик. Логика подбора и надбавок
 * не изменилась: модель — ближайшая по ёмкости «сверху», стоимость —
 * оборудование + доставка + монтаж с надбавками, вилка расширяется
 * коэффициентами грунта и удалённости.
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

    // Подбор станции по числу проживающих: ближайшая по ёмкости «сверху».
    function pickStation(people) {
        if (!stations.length) return null;
        for (var i = 0; i < stations.length; i++) {
            if (stations[i].number >= people) return stations[i];
        }
        return stations[stations.length - 1];
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

        function compute() {
            var people = parseInt(answers.people, 10) || 0;
            var st = pickStation(people);

            var install = settings.installBase;
            var suffix = [];
            if (answers.disposal === 'forced') {
                install += settings.surchargeForced;
                suffix.push('Пр');
            }
            if (answers.depth === 'long') {
                install += settings.surchargeLong;
                suffix.push('Лонг');
            } else if (answers.depth === 'longus') {
                install += settings.surchargeLongUs;
                suffix.push('Лонг Ус');
            }
            if (answers.ugv === 'yes') {
                install += settings.surchargeUgv;
            }

            var equipment = st ? num(st.price, 0) : 0;
            var low = equipment + settings.delivery + install;
            var high = low * (1 + (settings.soilCoeff + settings.remotenessCoeff) / 100);

            return {
                station: st,
                modelName: st ? st.title + (suffix.length ? ' ' + suffix.join(' ') : '') : 'Модель по запросу',
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

        // Большой дом: точную модель (ТОПАС-С 8/10) подбирает инженер.
        function renderBigHouse() {
            resultBox.innerHTML =
                '<div class="calc-live__placeholder">' +
                    '<span class="calc-live__result-eyebrow">Индивидуальный подбор</span>' +
                    '<div class="calc-live__placeholder-title">Большой дом на 7+ человек</div>' +
                    '<p class="calc-live__placeholder-text">Для такого объёма подойдут ТОПАС-С 8 или ТОПАС-С 10. ' +
                    'Подберём и рассчитаем индивидуально — это займёт пару минут.</p>' +
                    '<div class="calc-live__actions" style="margin-top:18px">' +
                        '<button type="button" class="calc-live__cta" data-calc-cta ' +
                            'data-product="Индивидуальный подбор (7+ человек)" ' +
                            'data-params="Проживающих: 7 и более" data-range="">Рассчитать индивидуально</button>' +
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

            resultBox.innerHTML =
                '<span class="calc-live__result-eyebrow">✦ Рекомендуем вам</span>' +
                '<h3 class="calc-live__result-name">' + esc(r.modelName) + '</h3>' +
                (st && st.peopleStr ? '<p class="calc-live__result-people">' + esc(st.peopleStr) + '</p>' : '') +
                img +
                specs +
                breakdown +
                '<div class="calc-live__total">' +
                    '<div class="calc-live__total-label">≈ станция + монтаж</div>' +
                    '<div class="calc-live__total-value">' + range + '</div>' +
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
                renderPlaceholder();
            } else if (people >= BIG_HOUSE_FROM) {
                renderBigHouse();
            } else {
                renderResult();
            }
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
        root.addEventListener('click', function (e) {
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

    document.addEventListener('DOMContentLoaded', function () {
        // .calc — разметка прежнего визарда (оставлена для обратной совместимости).
        each(document.querySelectorAll('[data-calc-root], .calc'), initCalc);
    });
})();
