/**
 * Карта географии работ (#29).
 *
 * Карта здесь — не картинка, а управляющий элемент: клик по району оставляет
 * в списке ниже только его объекты. Работает в двух режимах:
 *
 *  1. SVG-схема. Отдаётся сервером сразу, кликабельна без всякого API — то есть
 *     фильтрация по району работает и без ключа, и при отвалившемся интернете.
 *  2. Яндекс.Карта. Если задан ключ, API подгружается ЛЕНИВО (когда блок
 *     доскроллили) и заменяет схему. Не загрузился — остаётся схема, никаких
 *     сообщений об ошибке.
 *
 * Что сделано ради удобства:
 *  - границы подбираются по геометрии (setBounds), а не «на глаз» зумом;
 *  - на тач-устройствах карта не перехватывает скролл страницы, пока по ней
 *    не тапнули: иначе страницу невозможно пролистать пальцем через карту;
 *  - колесо мыши не зумит карту по той же причине;
 *  - районы с объектами закрашены, соседние регионы Поволжья обведены
 *    пунктиром как зона выезда;
 *  - выбранный район подсвечивается и в списке, и на карте, сбросить можно
 *    кнопкой «Все районы».
 */
(function () {
    'use strict';

    var data = window.izexGeoMap || {};
    var regions = Array.isArray(data.regions) ? data.regions : [];
    var outline = Array.isArray(data.outline) ? data.outline : [];
    var neighbours = Array.isArray(data.neighbours) ? data.neighbours : [];

    var host = null;
    var grid = null;
    var resetBtn = null;
    var active = '';       // выбранный район
    var ymap = null;
    var shapes = {};       // название района => полигон Яндекса

    // Основы названий для сопоставления считает PHP (см. izex_geo_stem):
    // у «города Канаш» и «Канашского района» они разные, а в поле объекта
    // пишут как придётся. Здесь только достаём их — из данных карты или из
    // атрибута на схеме.
    function stemsFor(name) {
        for (var i = 0; i < regions.length; i++) {
            if (regions[i].name === name && regions[i].stems) {
                return regions[i].stems;
            }
        }
        var g = host && host.querySelector('.geo-map__district[data-place="' + name + '"]');
        var raw = g && g.getAttribute('data-stems');

        return raw ? raw.split('|') : [name.toLowerCase()];
    }

    // ===== Фильтрация списка объектов =====

    function applyFilter(name) {
        active = name || '';

        if (grid) {
            var stems = active ? stemsFor(active) : [];
            Array.prototype.forEach.call(grid.querySelectorAll('.work-card'), function (card) {
                var loc = card.getAttribute('data-location') || '';
                var match = stems.some(function (st) { return st && loc.indexOf(st) !== -1; });
                card.hidden = stems.length > 0 && !match;
            });
        }

        // Подсветка на схеме.
        if (host) {
            Array.prototype.forEach.call(host.querySelectorAll('.geo-map__district'), function (g) {
                g.classList.toggle('is-selected', !!active && g.getAttribute('data-place') === active);
            });
        }

        // Подсветка на карте Яндекса.
        Object.keys(shapes).forEach(function (name) {
            shapes[name].options.set('fillColor', name === active ? '#1f9f27bb' : '#31b93999');
        });

        if (resetBtn) {
            resetBtn.hidden = !active;
            resetBtn.textContent = active ? 'Показать все районы (сейчас: ' + active + ')' : 'Все районы';
        }

        if (grid && active) {
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function buildReset() {
        if (!host) return;
        resetBtn = document.createElement('button');
        resetBtn.type = 'button';
        resetBtn.className = 'geo-map__reset';
        resetBtn.hidden = true;
        resetBtn.textContent = 'Все районы';
        resetBtn.addEventListener('click', function () { applyFilter(''); });
        host.appendChild(resetBtn);
    }

    // ===== Яндекс.Карта =====

    function loadApi(cb) {
        if (window.ymaps) { cb(); return; }
        var s = document.createElement('script');
        s.src = 'https://api-maps.yandex.ru/2.1/?apikey=' + encodeURIComponent(data.apiKey) + '&lang=ru_RU';
        s.async = true;
        s.onload = cb;
        s.onerror = function () {
            // Молча остаёмся на схеме: она уже на экране и кликается.
        };
        document.head.appendChild(s);
    }

    function renderMap() {
        var box = document.createElement('div');
        box.className = 'geo-map__interactive';
        host.insertBefore(box, host.querySelector('.geo-map__caption'));

        window.ymaps.ready(function () {
            var isTouch = window.matchMedia('(pointer: coarse)').matches;

            ymap = new window.ymaps.Map(box, {
                center: [55.6, 47.0],
                zoom: 7,
                controls: ['zoomControl', 'fullscreenControl']
            }, {
                suppressMapOpenBlock: true
            });

            // Колесо мыши не зумит карту, иначе страница «залипает» при прокрутке.
            ymap.behaviors.disable('scrollZoom');

            // На телефоне карта во всю ширину — если оставить перетаскивание
            // включённым, палец двигает карту вместо страницы. Включаем его
            // только после тапа по карте, а до этого показываем подсказку.
            if (isTouch) {
                ymap.behaviors.disable('drag');
                box.classList.add('is-locked');

                var hint = document.createElement('button');
                hint.type = 'button';
                hint.className = 'geo-map__unlock';
                hint.textContent = 'Нажмите, чтобы двигать карту';
                hint.addEventListener('click', function () {
                    ymap.behaviors.enable('drag');
                    box.classList.remove('is-locked');
                    hint.remove();
                });
                box.appendChild(hint);
            }

            var bounds = [];

            // Контур республики — тонкой линией под заливками: он даёт рамку,
            // внутри которой читаются закрашенные районы.
            if (outline.length) {
                ymap.geoObjects.add(new window.ymaps.Polygon([outline], {}, {
                    fillColor: '#31b93911',
                    strokeColor: '#157f1c',
                    strokeWidth: 2,
                    interactivityModel: 'default#transparent'
                }));
                outline.forEach(function (p) { bounds.push(p); });
            }

            // Соседние регионы Поволжья — зона выезда: контур без заливки,
            // чтобы их не путали с районами, где объекты уже стоят.
            neighbours.forEach(function (n) {
                ymap.geoObjects.add(new window.ymaps.Polygon(n.rings, {
                    hintContent: n.name + ' — выезжаем'
                }, {
                    fillColor: '#94a3b820',
                    strokeColor: '#94a3b8',
                    strokeWidth: 1,
                    strokeStyle: 'shortdash',
                    interactivityModel: 'default#transparent'
                }));
            });

            // Районы с объектами — заливка. Клик оставляет в списке ниже
            // только объекты этого района.
            regions.forEach(function (r) {
                var poly = new window.ymaps.Polygon(r.rings, {
                    hintContent: r.name + ': объектов — ' + r.count
                }, {
                    fillColor: '#31b93999',
                    strokeColor: '#157f1c',
                    strokeWidth: 2,
                    cursor: 'pointer'
                });

                poly.events.add('click', function () {
                    applyFilter(active === r.name ? '' : r.name);
                });

                shapes[r.name] = poly;
                ymap.geoObjects.add(poly);

                r.rings.forEach(function (ring) {
                    ring.forEach(function (p) { bounds.push(p); });
                });
            });

            // Границы по геометрии, а не подобранный вручную зум: карта всегда
            // вписана целиком, на любом размере экрана.
            if (bounds.length) {
                ymap.setBounds(window.ymaps.util.bounds.fromPoints(bounds), {
                    checkZoomRange: true,
                    zoomMargin: 24
                });
            }

            host.classList.add('is-interactive');
        });
    }

    // ===== Старт =====

    document.addEventListener('DOMContentLoaded', function () {
        host = document.querySelector('[data-geo-map]');
        if (!host) return;

        grid = document.querySelector('[data-works-grid]');
        buildReset();

        // Схема кликабельна сразу, до и вместо всякого API.
        host.addEventListener('click', function (e) {
            var g = e.target.closest('.geo-map__district');
            if (!g) return;
            var name = g.getAttribute('data-place');
            applyFilter(active === name ? '' : name);
        });

        if (!data.apiKey || (!regions.length && !outline.length)) {
            return; // штатный режим: только схема
        }

        var start = function () { loadApi(renderMap); };

        if (!('IntersectionObserver' in window)) {
            start();
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                io.disconnect();
                start();
            }
        }, { rootMargin: '200px' });

        io.observe(host);
    });
})();
