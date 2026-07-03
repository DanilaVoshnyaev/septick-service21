/**
 * Сравнение моделей ТОПАС (ТЗ 4.2).
 *
 * Выбор станций сохраняется в localStorage, снизу показывается плавающая панель,
 * по кнопке открывается оверлей с таблицей характеристик и подсветкой различий.
 * Данные станций приходят из PHP (topasCompare.stations, ключ — ID записи).
 */
(function () {
    'use strict';

    var STORE_KEY = 'topas_compare';
    var MAX = 4;

    var data = window.topasCompare || { stations: {} };
    var stations = data.stations || {};

    function load() {
        try {
            var raw = localStorage.getItem(STORE_KEY);
            var arr = raw ? JSON.parse(raw) : [];
            // Оставляем только существующие в датасете id.
            return arr.filter(function (id) { return stations[id]; });
        } catch (e) {
            return [];
        }
    }

    function save(ids) {
        try { localStorage.setItem(STORE_KEY, JSON.stringify(ids)); } catch (e) {}
    }

    var selected = load();

    var bar, overlay;

    function each(list, fn) { Array.prototype.forEach.call(list, fn); }

    function isSelected(id) { return selected.indexOf(String(id)) !== -1; }

    function syncToggles() {
        each(document.querySelectorAll('.js-compare-toggle'), function (btn) {
            var on = isSelected(btn.getAttribute('data-compare-id'));
            btn.classList.toggle('is-active', on);
            btn.setAttribute('aria-pressed', on ? 'true' : 'false');
            var label = btn.querySelector('.station-compare__label');
            if (label) label.textContent = on ? 'В сравнении' : 'Сравнить';
        });
    }

    function toggle(id) {
        id = String(id);
        var idx = selected.indexOf(id);
        if (idx === -1) {
            if (selected.length >= MAX) {
                flashBar();
                return;
            }
            selected.push(id);
        } else {
            selected.splice(idx, 1);
        }
        save(selected);
        syncToggles();
        renderBar();
    }

    function clearAll() {
        selected = [];
        save(selected);
        syncToggles();
        renderBar();
        closeOverlay();
    }

    // ===== Плавающая панель =====
    function buildBar() {
        bar = document.createElement('div');
        bar.className = 'cmp-bar';
        bar.hidden = true;
        bar.innerHTML =
            '<div class="cmp-bar__items"></div>' +
            '<div class="cmp-bar__actions">' +
                '<button type="button" class="cmp-bar__open">Сравнить <span class="cmp-bar__count"></span></button>' +
                '<button type="button" class="cmp-bar__clear" aria-label="Очистить сравнение">Очистить</button>' +
            '</div>';
        document.body.appendChild(bar);

        bar.querySelector('.cmp-bar__open').addEventListener('click', openOverlay);
        bar.querySelector('.cmp-bar__clear').addEventListener('click', clearAll);
        bar.querySelector('.cmp-bar__items').addEventListener('click', function (e) {
            var rm = e.target.closest('[data-remove]');
            if (rm) toggle(rm.getAttribute('data-remove'));
        });
    }

    function renderBar() {
        if (!bar) buildBar();
        if (!selected.length) { bar.hidden = true; return; }

        var items = bar.querySelector('.cmp-bar__items');
        items.innerHTML = selected.map(function (id) {
            var st = stations[id];
            if (!st) return '';
            var img = st.img
                ? '<img src="' + st.img + '" alt="' + esc(st.title) + '">'
                : '<span class="cmp-chip__noimg">ТОПАС</span>';
            return '<div class="cmp-chip">' + img +
                '<span class="cmp-chip__name">' + esc(st.title) + '</span>' +
                '<button type="button" class="cmp-chip__rm" data-remove="' + id + '" aria-label="Убрать">×</button>' +
                '</div>';
        }).join('');

        bar.querySelector('.cmp-bar__count').textContent = '(' + selected.length + ')';
        var openBtn = bar.querySelector('.cmp-bar__open');
        openBtn.disabled = selected.length < 2;
        openBtn.title = selected.length < 2 ? 'Выберите минимум 2 модели' : '';
        bar.hidden = false;
    }

    function flashBar() {
        if (!bar) return;
        bar.classList.remove('cmp-bar--flash');
        // reflow для перезапуска анимации
        void bar.offsetWidth;
        bar.classList.add('cmp-bar--flash');
    }

    // ===== Оверлей с таблицей =====
    function buildOverlay() {
        overlay = document.createElement('div');
        overlay.className = 'cmp-overlay';
        overlay.hidden = true;
        overlay.innerHTML =
            '<div class="cmp-overlay__backdrop"></div>' +
            '<div class="cmp-overlay__panel" role="dialog" aria-label="Сравнение моделей" aria-modal="true">' +
                '<div class="cmp-overlay__head">' +
                    '<h3 class="cmp-overlay__title">Сравнение моделей</h3>' +
                    '<button type="button" class="cmp-overlay__close" aria-label="Закрыть">×</button>' +
                '</div>' +
                '<div class="cmp-overlay__scroll"></div>' +
            '</div>';
        document.body.appendChild(overlay);

        overlay.querySelector('.cmp-overlay__backdrop').addEventListener('click', closeOverlay);
        overlay.querySelector('.cmp-overlay__close').addEventListener('click', closeOverlay);
        overlay.querySelector('.cmp-overlay__scroll').addEventListener('click', function (e) {
            var rm = e.target.closest('[data-remove]');
            if (rm) {
                toggle(rm.getAttribute('data-remove'));
                if (selected.length < 2) { closeOverlay(); }
                else { renderTable(); }
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeOverlay();
        });
    }

    function renderTable() {
        var cols = selected.map(function (id) { return stations[id]; }).filter(Boolean);
        if (cols.length < 2) return;

        // Заголовок: модели.
        var head = '<tr><th class="cmp-table__corner"></th>';
        cols.forEach(function (st) {
            var img = st.img ? '<img class="cmp-table__img" src="' + st.img + '" alt="' + esc(st.title) + '">' : '';
            head += '<th class="cmp-table__model">' +
                img +
                '<a class="cmp-table__name" href="' + st.url + '">' + esc(st.title) + '</a>' +
                '<button type="button" class="cmp-table__rm" data-remove="' + st.id + '" aria-label="Убрать">× убрать</button>' +
                '</th>';
        });
        head += '</tr>';

        // Строки характеристик (порядок берём из первой модели).
        var specs = cols[0].specs || [];
        var body = '';
        specs.forEach(function (row, i) {
            var values = cols.map(function (st) {
                return (st.specs && st.specs[i]) ? st.specs[i].value : '—';
            });
            var allSame = values.every(function (v) { return v === values[0]; });
            body += '<tr class="' + (allSame ? '' : 'is-diff') + '">' +
                '<th class="cmp-table__label">' + esc(row.label) + '</th>' +
                values.map(function (v) { return '<td>' + esc(v) + '</td>'; }).join('') +
                '</tr>';
        });

        overlay.querySelector('.cmp-overlay__scroll').innerHTML =
            '<table class="cmp-table"><thead>' + head + '</thead><tbody>' + body + '</tbody></table>' +
            '<p class="cmp-table__hint">Различающиеся характеристики выделены.</p>';
    }

    function openOverlay() {
        if (selected.length < 2) return;
        if (!overlay) buildOverlay();
        renderTable();
        overlay.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeOverlay() {
        if (overlay) overlay.hidden = true;
        document.body.style.overflow = '';
    }

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ===== Инициализация =====
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            var t = e.target.closest('.js-compare-toggle');
            if (t) {
                e.preventDefault();
                toggle(t.getAttribute('data-compare-id'));
            }
        });
        syncToggles();
        renderBar();
    });
})();
