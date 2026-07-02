/**
 * Калькулятор подбора и расчёта стоимости ТОПАС «под ключ» (ТЗ 4.1).
 *
 * Данные станций и настройки приходят из PHP через wp_localize_script (topasCalc).
 * Подбор модели — по числу проживающих (ёмкость = «номер» модели), стоимость —
 * оборудование + доставка + монтаж с надбавками; на выходе вилка (коэффициенты
 * грунта/удалённости расширяют верхнюю границу). Финальная смета — после выезда.
 */
(function () {
    'use strict';

    var data = window.topasCalc || { stations: [], settings: {} };
    var stations = Array.isArray(data.stations) ? data.stations : [];
    var s = data.settings || {};

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
        note: s.note || 'Это ориентировочный расчёт. Точная смета — после бесплатного выезда инженера.'
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

    function initCalc(root) {
        var form = root.querySelector('[data-calc-form]');
        if (!form) return;

        var steps = form.querySelectorAll('.calc-step');
        var progress = form.querySelector('[data-calc-progress]');
        var backBtn = form.querySelector('[data-calc-back]');
        var restartBtn = form.querySelector('[data-calc-restart]');
        var resultBox = form.querySelector('[data-calc-result]');
        var totalSteps = 4;

        var answers = {};
        var current = 1;

        function showStep(step) {
            each(steps, function (el) {
                el.classList.toggle('is-active', el.getAttribute('data-step') === String(step));
                if (el.getAttribute('data-step') === 'result') {
                    el.hidden = step !== 'result';
                }
            });
            if (progress) {
                var pct = step === 'result' ? 100 : Math.round(((step - 1) / totalSteps) * 100);
                progress.style.width = pct + '%';
            }
            backBtn.hidden = (step === 1);
            restartBtn.hidden = (step !== 'result');
            current = step;
        }

        function computeResult() {
            var st = pickStation(parseInt(answers.people, 10) || 1);

            // Надбавки монтажа по вариантам.
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
            var delivery = settings.delivery;
            var low = equipment + delivery + install;
            var mult = 1 + (settings.soilCoeff + settings.remotenessCoeff) / 100;
            var high = low * mult;

            var modelName = st
                ? st.title + (suffix.length ? ' ' + suffix.join(' ') : '')
                : 'Модель по запросу';

            return {
                station: st,
                modelName: modelName,
                equipment: equipment,
                delivery: delivery,
                install: install,
                low: roundNice(low),
                high: roundNice(high),
                people: answers.people === '11' ? 'более 10' : answers.people,
                disposalLabel: answers.disposal === 'forced' ? 'принудительное' : 'самотёк',
                depthLabel: answers.depth === 'long' ? 'Лонг' : (answers.depth === 'longus' ? 'Лонг Ус' : 'стандарт'),
                ugvLabel: answers.ugv === 'yes' ? 'да' : 'нет'
            };
        }

        function renderResult() {
            var r = computeResult();

            var img = r.station && r.station.img
                ? '<img class="calc-res__img" src="' + r.station.img + '" alt="' + r.modelName + '" loading="lazy">'
                : '';

            var equipmentRow = r.equipment > 0
                ? '<li><span>Оборудование</span><b>' + money(r.equipment) + '</b></li>'
                : '<li><span>Оборудование</span><b>по запросу</b></li>';

            var deliveryRow = '<li><span>Доставка</span><b>' + (r.delivery > 0 ? money(r.delivery) : 'бесплатно') + '</b></li>';
            var installRow = '<li><span>Монтаж «под ключ»</span><b>' + money(r.install) + '</b></li>';

            var moreLink = r.station
                ? '<a class="calc-res__link" href="' + r.station.url + '">Подробнее о модели →</a>'
                : '';

            var params = 'Проживающих: ' + r.people +
                '; водоотведение: ' + r.disposalLabel +
                '; глубина: ' + r.depthLabel +
                '; высокий УГВ: ' + r.ugvLabel;

            resultBox.innerHTML =
                '<div class="calc-res">' +
                    '<div class="calc-res__model">' +
                        img +
                        '<div class="calc-res__meta">' +
                            '<span class="calc-res__eyebrow">Рекомендуем</span>' +
                            '<h3 class="calc-res__name">' + r.modelName + '</h3>' +
                            moreLink +
                        '</div>' +
                    '</div>' +
                    '<ul class="calc-res__breakdown">' + equipmentRow + deliveryRow + installRow + '</ul>' +
                    '<div class="calc-res__total">' +
                        '<span class="calc-res__total-label">Ориентировочно «под ключ»</span>' +
                        '<span class="calc-res__total-value">' + money(r.low) + ' – ' + money(r.high) + '</span>' +
                    '</div>' +
                    '<button type="button" class="calc-res__cta" data-calc-cta ' +
                        'data-product="' + r.modelName + '" data-params="' + params.replace(/"/g, '&quot;') +
                        '" data-range="' + money(r.low) + ' – ' + money(r.high) + '">Получить точный расчёт</button>' +
                    '<p class="calc-res__note">' + settings.note + '</p>' +
                '</div>';

            showStep('result');
        }

        // Выбор варианта на шаге.
        each(form.querySelectorAll('.calc-opt'), function (btn) {
            btn.addEventListener('click', function () {
                var name = btn.getAttribute('data-name');
                var value = btn.getAttribute('data-value');
                answers[name] = value;

                // Подсветка выбора в пределах шага.
                var group = btn.closest('.calc-options');
                each(group.querySelectorAll('.calc-opt'), function (el) {
                    el.classList.toggle('is-selected', el === btn);
                });

                if (current < totalSteps) {
                    showStep(current + 1);
                } else {
                    renderResult();
                }
            });
        });

        backBtn.addEventListener('click', function () {
            if (current === 'result') {
                showStep(totalSteps);
            } else if (current > 1) {
                showStep(current - 1);
            }
        });

        restartBtn.addEventListener('click', function () {
            answers = {};
            each(form.querySelectorAll('.calc-opt'), function (el) {
                el.classList.remove('is-selected');
            });
            showStep(1);
        });

        // CTA «Получить точный расчёт» — предзаполняем и открываем модалку заказа.
        form.addEventListener('click', function (e) {
            var cta = e.target.closest('[data-calc-cta]');
            if (!cta) return;
            openOrderModal(cta.getAttribute('data-product'), cta.getAttribute('data-params'), cta.getAttribute('data-range'));
        });

        showStep(1);
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
        each(document.querySelectorAll('.calc'), initCalc);
    });
})();
