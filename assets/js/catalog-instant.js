/**
 * Мгновенный чип-фильтр каталога (по прототипу заказчика).
 *
 * Раньше фильтр на главной был формой с <select> и перезагружал страницу.
 * Теперь чипы фильтруют и сортируют уже отрендеренные карточки без запроса,
 * показывают счётчик найденного и пишут состояние в URL (кнопка «назад» работает).
 *
 * Без JS всё продолжает работать по-старому: чипы — это submit-кнопки формы,
 * сервер отдаёт отфильтрованный каталог (см. front-page.php).
 *
 * Правила совпадения повторяют серверные:
 *  - «до N человек» подходит, если N попадает в диапазон модели [min..max];
 *  - водоотведение сравнивается по основе слова («самот» / «принуд»);
 *  - «По запросу» (цена 0) при сортировке всегда уходит в конец.
 */
(function () {
    'use strict';

    var PREVIEW_LIMIT = 8; // сколько карточек показываем до «Показать все»

    function each(list, fn) {
        Array.prototype.forEach.call(list, fn);
    }

    function initCatalog(root) {
        var grid = root.querySelector('[data-catalog-grid]');
        if (!grid) return;

        var cards = Array.prototype.slice.call(grid.querySelectorAll('.pro-card'));
        if (!cards.length) return;

        var countEl = root.querySelector('[data-catalog-count]');
        var emptyEl = root.querySelector('[data-catalog-empty]');
        var moreEl = root.querySelector('[data-catalog-more]');
        var resetEl = root.querySelector('[data-catalog-reset]');

        var state = { people: '', disposal: '', sort: '' };
        var expanded = false;

        // Начальное состояние — из URL, чтобы совпасть с серверным рендером.
        var params = new URLSearchParams(window.location.search);
        state.people = params.get('capacity') || '';
        state.disposal = params.get('drainage') || '';
        state.sort = params.get('sort') || '';

        // Если фильтр пришёл в URL, сервер отдал ТОЛЬКО совпавшие карточки.
        // Значит клиентом можно лишь сужать выборку: снятие или смену такого
        // условия отдаём обычной ссылке (перезагрузка с полным каталогом).
        var serverState = { people: state.people, disposal: state.disposal };

        function needsReload(next) {
            return (serverState.people && next.people !== serverState.people) ||
                   (serverState.disposal && next.disposal !== serverState.disposal);
        }

        function matches(card) {
            if (state.people) {
                var want = parseInt(state.people, 10);
                var min = parseInt(card.getAttribute('data-people-min'), 10) || 0;
                var max = parseInt(card.getAttribute('data-people-max'), 10) || 0;
                if (!min && !max) return false;
                if (want < min || want > max) return false;
            }
            if (state.disposal) {
                var stem = state.disposal.toLowerCase().indexOf('принуд') !== -1 ? 'принуд' : 'самот';
                var hay = (card.getAttribute('data-disposal') || '').toLowerCase();
                if (hay.indexOf(stem) === -1) return false;
            }
            return true;
        }

        function sortCards(list) {
            var desc = state.sort === 'price_desc';
            return list.slice().sort(function (a, b) {
                var pa = parseInt(a.getAttribute('data-price'), 10) || 0;
                var pb = parseInt(b.getAttribute('data-price'), 10) || 0;
                if (!pa && !pb) return 0;
                if (!pa) return 1;  // «По запросу» — в конец
                if (!pb) return -1;
                return desc ? pb - pa : pa - pb;
            });
        }

        function syncChips() {
            each(root.querySelectorAll('[data-filter]'), function (chip) {
                var key = chip.getAttribute('data-filter');
                var value = chip.getAttribute('data-value') || '';
                chip.classList.toggle('is-active', state[key] === value);
            });
        }

        function syncUrl() {
            if (!window.history || !window.history.replaceState) return;
            var q = new URLSearchParams(window.location.search);
            var map = { capacity: state.people, drainage: state.disposal, sort: state.sort };
            Object.keys(map).forEach(function (name) {
                if (map[name]) {
                    q.set(name, map[name]);
                } else {
                    q.delete(name);
                }
            });
            var qs = q.toString();
            window.history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : '') + '#catalog');
        }

        function apply() {
            var visible = sortCards(cards.filter(matches));
            var hasFilter = !!(state.people || state.disposal);

            // Переставляем в отсортированном порядке — DOM-порядок задаёт вид сетки.
            visible.forEach(function (card) {
                grid.appendChild(card);
            });

            var limit = (expanded || hasFilter) ? visible.length : PREVIEW_LIMIT;
            cards.forEach(function (card) {
                var idx = visible.indexOf(card);
                card.hidden = (idx === -1 || idx >= limit);
            });

            if (countEl) countEl.textContent = 'Найдено: ' + visible.length;
            if (emptyEl) emptyEl.hidden = visible.length > 0;
            if (moreEl) {
                var rest = visible.length - limit;
                moreEl.hidden = rest <= 0;
                if (rest > 0) {
                    moreEl.textContent = 'Показать все модели (' + visible.length + ')';
                }
            }
            if (resetEl) resetEl.hidden = !(hasFilter || state.sort);

            syncChips();
        }

        // Клик по чипу: повторный клик по активному — сброс этого условия.
        each(root.querySelectorAll('[data-filter]'), function (chip) {
            chip.addEventListener('click', function (e) {
                var key = chip.getAttribute('data-filter');
                var value = chip.getAttribute('data-value') || '';
                var next = {
                    people: state.people,
                    disposal: state.disposal,
                    sort: state.sort
                };
                next[key] = (state[key] === value) ? '' : value;

                if (needsReload(next)) {
                    return; // отдаём ссылке — страница перезагрузится с нужным набором
                }

                e.preventDefault();
                state = next;
                expanded = false;
                apply();
                syncUrl();
            });
        });

        if (resetEl) {
            resetEl.addEventListener('click', function (e) {
                // Сброс при серверном фильтре — тоже перезагрузка (нужен полный каталог).
                if (serverState.people || serverState.disposal) {
                    return;
                }
                e.preventDefault();
                state = { people: '', disposal: '', sort: '' };
                expanded = false;
                apply();
                syncUrl();
            });
        }

        if (moreEl) {
            moreEl.addEventListener('click', function (e) {
                // Без JS это ссылка на архив каталога — здесь просто раскрываем сетку.
                e.preventDefault();
                expanded = true;
                apply();
            });
        }

        apply();
    }

    document.addEventListener('DOMContentLoaded', function () {
        each(document.querySelectorAll('[data-catalog]'), initCatalog);
    });
})();
