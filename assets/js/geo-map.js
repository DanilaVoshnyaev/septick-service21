/**
 * Интерактивная карта географии работ поверх SVG-схемы (#29).
 *
 * Порядок такой:
 *  1. Сервер сразу отдаёт лёгкую SVG-схему — она видна мгновенно и работает
 *     без JS, без ключа и без интернета до внешнего сервиса.
 *  2. Если в настройках темы задан ключ Яндекс.Карт, скрипт API подгружается
 *     ЛЕНИВО — только когда блок карты доскроллили. API весит ~250 КБ, и
 *     тянуть его на каждый заход в раздел незачем.
 *  3. Когда карта готова, она заменяет схему. Если API не ответил, ключ
 *     неверный или блокировщик срезал запрос — остаётся SVG, и раздел
 *     выглядит так же, как без карты.
 *
 * Данные точек приходят из PHP (izexGeoMap): название райцентра, координаты
 * и число объектов.
 */
(function () {
    'use strict';

    var data = window.izexGeoMap || {};
    var points = Array.isArray(data.points) ? data.points : [];

    if (!data.apiKey || !points.length) {
        return; // без ключа остаётся SVG — это штатный режим, не ошибка
    }

    function loadApi(cb) {
        if (window.ymaps) {
            cb();
            return;
        }
        var s = document.createElement('script');
        s.src = 'https://api-maps.yandex.ru/2.1/?apikey=' + encodeURIComponent(data.apiKey) +
            '&lang=ru_RU';
        s.async = true;
        s.onload = cb;
        s.onerror = function () {
            // Молча остаёмся на SVG: неудачная загрузка карты не должна
            // ломать страницу и не должна показывать пользователю ошибку.
        };
        document.head.appendChild(s);
    }

    function render(host) {
        var box = document.createElement('div');
        box.className = 'geo-map__interactive';
        host.appendChild(box);

        window.ymaps.ready(function () {
            var map = new window.ymaps.Map(box, {
                center: [55.6, 47.0],   // середина Чувашии
                zoom: 7,
                controls: ['zoomControl', 'fullscreenControl']
            }, {
                suppressMapOpenBlock: true
            });

            // Карта иллюстрирует охват, а не служит навигацией: колесо мыши
            // не перехватываем, иначе страница перестаёт скроллиться.
            map.behaviors.disable('scrollZoom');

            points.forEach(function (p) {
                var withWorks = p.count > 0;
                var hint = withWorks
                    ? p.name + ': объектов — ' + p.count
                    : p.name + ' — выезжаем';

                map.geoObjects.add(new window.ymaps.Placemark([p.lat, p.lon], {
                    hintContent: hint,
                    balloonContent: hint
                }, {
                    preset: withWorks ? 'islands#greenDotIconWithCaption' : 'islands#grayCircleDotIcon',
                    iconCaption: withWorks ? p.name : '',
                    iconColor: withWorks ? '#31b939' : '#94a3b8'
                }));
            });

            // Схема больше не нужна — но остаётся в DOM для печати и для случая,
            // когда карта не инициализировалась.
            host.classList.add('is-interactive');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var host = document.querySelector('[data-geo-map]');
        if (!host) return;

        var start = function () {
            loadApi(function () { render(host); });
        };

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
