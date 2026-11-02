(function () {
    'use strict';

    /* ===== Меню на телефоне ===== */
    var menu = document.getElementById('ss-menu');
    var opener = document.querySelector('[data-ss-menu-open]');

    function setMenu(open) {
        if (!menu) return;
        menu.hidden = !open;
        document.body.classList.toggle('ss-locked', open);
        if (opener) opener.setAttribute('aria-expanded', String(open));
        if (open) {
            var first = menu.querySelector('a, button');
            if (first) first.focus();
        } else if (opener) {
            opener.focus();
        }
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-ss-menu-open]')) setMenu(true);
        else if (e.target.closest('[data-ss-menu-close]')) setMenu(false);
        else if (menu && !menu.hidden && e.target.closest('#ss-menu a, #ss-menu .open-modal')) setMenu(false);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && menu && !menu.hidden) setMenu(false);
    });

    /* ===== Окно «Получить расчёт» без модели — без подписи прошлой модели ===== */
    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('.open-modal[data-modal="order"]');
        if (!trigger || trigger.hasAttribute('data-product')) return;
        var modal = document.getElementById('modal-order');
        if (!modal) return;
        modal.querySelectorAll('.js-order-product-field, .js-order-product-id').forEach(function (f) { f.value = ''; });
        var label = modal.querySelector('.js-order-product');
        if (label) { label.textContent = ''; label.hidden = true; }
    }, true);

    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('.open-modal');
        if (!trigger) return;
        var modal = document.getElementById('modal-' + trigger.getAttribute('data-modal'));
        if (!modal) return;
        setTimeout(function () {
            var field = modal.querySelector('input[type="tel"]');
            if (field && modal.classList.contains('active')) field.focus();
        }, 50);
    });

    /* ===== Экранная клавиатура: нижняя панель не закрывает поле ввода ===== */
    var typingTarget = 'input:not([type="radio"]):not([type="checkbox"]):not([type="hidden"]), textarea, select';
    document.addEventListener('focusin', function (e) {
        if (!e.target.matches(typingTarget)) return;
        document.body.classList.add('ss-typing');
        if (window.innerWidth < 768) {
            setTimeout(function () { e.target.scrollIntoView({ block: 'center', behavior: 'smooth' }); }, 300);
        }
    });
    document.addEventListener('focusout', function (e) {
        if (e.target.matches(typingTarget)) document.body.classList.remove('ss-typing');
    });

    /* ===== Отзыв целиком ===== */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-ss-review-more]');
        if (!btn) return;
        var tail = btn.parentNode.querySelector('.ss-review__tail');
        if (tail) tail.hidden = false;
        btn.remove();
    });

    /* ===== Каталог: фильтры без перезагрузки ===== */
    var catalog = document.querySelector('[data-ss-catalog]');
    if (catalog) {
        var form = catalog.querySelector('[data-ss-filters]');
        var grid = catalog.querySelector('[data-ss-grid]');
        var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-ss-card]'));
        var order = cards.slice();
        var empty = catalog.querySelector('[data-ss-empty]');
        var counters = document.querySelectorAll('[data-ss-count]');
        var activeBadge = catalog.querySelector('[data-ss-active]');
        var sortSelect = catalog.querySelector('[data-ss-sort]');
        var openBtn = catalog.querySelector('[data-ss-filters-open]');
        var keys = ['brand', 'users', 'disposal', 'price', 'depth'];
        var priceLow = { '150': 0, '200': 150000, '250': 200000, '999': 250000 };

        var value = function (name) {
            var input = form.querySelector('input[name="' + name + '"]:checked');
            return input ? input.value : '';
        };

        var matches = function (card, state) {
            var d = card.dataset;
            if (state.brand && d.brand !== state.brand) return false;
            if (state.users) {
                var n = +state.users, max = +d.max;
                if (!max || max < n || max > n + 2) return false;
            }
            if (state.disposal && d.disposal !== 'both' && d.disposal !== state.disposal) return false;
            if (state.depth && d.depth !== state.depth) return false;
            if (state.price) {
                var price = +d.price, hi = +state.price * 1000;
                if (!price || price <= priceLow[state.price] || (state.price !== '999' && price > hi)) return false;
            }
            return true;
        };

        var apply = function () {
            var state = {};
            keys.forEach(function (k) { state[k] = value(k); });
            var sort = sortSelect ? sortSelect.value : '';

            var sorted = order.slice();
            if (sort === 'price_desc') {
                sorted.sort(function (a, b) { return (+b.dataset.price || -1) - (+a.dataset.price || -1); });
            } else if (sort === 'users') {
                sorted.sort(function (a, b) { return (+a.dataset.max - +b.dataset.max) || ((+a.dataset.price || 1e9) - (+b.dataset.price || 1e9)); });
            }
            sorted.forEach(function (card) { grid.appendChild(card); });

            var shown = 0;
            cards.forEach(function (card) {
                var ok = matches(card, state);
                card.hidden = !ok;
                shown += ok ? 1 : 0;
            });
            counters.forEach(function (c) { c.textContent = shown; });
            empty.hidden = shown > 0;

            var active = keys.filter(function (k) { return state[k]; }).length;
            if (activeBadge) { activeBadge.textContent = active; activeBadge.hidden = !active; }

            var params = new URLSearchParams();
            keys.forEach(function (k) { if (state[k]) params.set(k, state[k]); });
            if (sort) params.set('sort', sort);
            var qs = params.toString();
            history.replaceState(null, '', location.pathname + (qs ? '?' + qs : ''));
        };

        form.addEventListener('change', apply);
        if (sortSelect) sortSelect.addEventListener('change', apply);
        form.addEventListener('submit', function (e) { e.preventDefault(); apply(); });

        document.querySelectorAll('[data-ss-reset]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                keys.forEach(function (k) {
                    var all = form.querySelector('input[name="' + k + '"][value=""]');
                    if (all) all.checked = true;
                });
                if (sortSelect) sortSelect.value = '';
                apply();
            });
        });

        var setPanel = function (open) {
            form.classList.toggle('is-open', open);
            document.body.classList.toggle('ss-locked', open);
            if (openBtn) openBtn.setAttribute('aria-expanded', String(open));
            if (!open && openBtn) openBtn.focus();
        };
        if (openBtn) openBtn.addEventListener('click', function () { setPanel(true); });
        catalog.querySelectorAll('[data-ss-filters-close]').forEach(function (b) {
            b.addEventListener('click', function () { setPanel(false); });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && form.classList.contains('is-open')) setPanel(false);
        });
    }

    /* ===== Пошаговый подбор ===== */
    document.querySelectorAll('[data-ss-podbor]').forEach(function (form) {
        var steps = Array.prototype.slice.call(form.querySelectorAll('.ss-podbor__step'));
        var bar = form.querySelector('.ss-podbor__bar');
        var models = [];
        try { models = JSON.parse(form.querySelector('[data-podbor-models]').textContent); } catch (e) {}
        var current = 0;

        form.classList.add('is-steps');

        var answer = function (label) {
            var group = form.querySelector('[data-label="' + label + '"]');
            if (!group) return '';
            var checked = group.querySelector('input:checked');
            return checked ? checked.value : (group.value || '');
        };

        var pick = function () {
            var users = { '1–2': 2, '3–4': 4, '5–6': 6, '7–8': 8, '9–10': 10 }[answer('Число пользователей')];
            if (!users) return [];
            var brandSelect = form.querySelector('[data-podbor-brand]');
            var brand = brandSelect && brandSelect.selectedOptions[0] ? brandSelect.selectedOptions[0].getAttribute('data-key') : '';
            var depth = answer('Глубина выхода трубы из дома');
            var variant = depth === '80–140 см' ? 'Лонг' : (depth === 'глубже 140 см' ? 'Лонг Ус' : 'Стандарт');
            var wet = answer('Грунтовые воды').indexOf('Близко') === 0 || answer('Грунт') === 'Глина';

            var fit = models.filter(function (m) {
                if (!m.max || m.max < users || m.max > users + 2) return false;
                if (brand && m.brand !== brand) return false;
                if (m.topas && m.variant !== variant) return false;
                return true;
            });
            fit.sort(function (a, b) {
                var pa = (wet ? (a.disposal === 'gravity') : (a.disposal === 'pump')) ? 1 : 0;
                var pb = (wet ? (b.disposal === 'gravity') : (b.disposal === 'pump')) ? 1 : 0;
                return (pa - pb) || (a.max - b.max) || ((a.price || 1e9) - (b.price || 1e9));
            });
            var out = [], brands = {};
            fit.forEach(function (m) {
                if (out.length < 3 && (!brands[m.brand] || brand)) { out.push(m); brands[m.brand] = 1; }
            });
            return out;
        };

        var money = function (v) { return v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽'; };

        var renderResult = function () {
            var box = form.querySelector('[data-podbor-result]');
            var picked = form.querySelector('[data-podbor-picked]');
            var list = pick();
            box.textContent = '';
            if (!list.length) {
                var p = document.createElement('p');
                p.className = 'ss-podbor__hint';
                p.textContent = 'По этим ответам модель лучше подобрать вместе с инженером: оставьте телефон, предложим 2–3 варианта.';
                box.appendChild(p);
                picked.value = '';
                return;
            }
            var intro = document.createElement('p');
            intro.className = 'ss-podbor__hint';
            intro.textContent = 'Предварительно подходят — без учёта монтажа и доставки:';
            box.appendChild(intro);
            var ul = document.createElement('ul');
            ul.className = 'ss-podbor__models';
            list.forEach(function (m) {
                var li = document.createElement('li');
                var a = document.createElement('a');
                a.href = m.url; a.textContent = m.title; a.target = '_blank';
                var price = document.createElement('span');
                price.textContent = m.price ? 'оборудование ' + money(m.price) : 'цена по запросу';
                li.appendChild(a); li.appendChild(price);
                ul.appendChild(li);
            });
            box.appendChild(ul);
            picked.value = list.map(function (m) { return m.title; }).join(', ');
        };

        var show = function (i) {
            current = Math.max(0, Math.min(steps.length - 1, i));
            steps.forEach(function (s, n) { s.hidden = n !== current; });
            if (bar) bar.style.width = ((current + 1) / steps.length * 100) + '%';
            if (current === steps.length - 1) renderResult();
            var title = steps[current].querySelector('.ss-podbor__title');
            if (title) { title.setAttribute('tabindex', '-1'); title.focus({ preventScroll: true }); }
            var top = form.getBoundingClientRect().top;
            if (top < 0) window.scrollBy({ top: top - 96, behavior: 'smooth' });
        };

        form.addEventListener('click', function (e) {
            if (e.target.closest('[data-podbor-next]')) show(current + 1);
            if (e.target.closest('[data-podbor-back]')) show(current - 1);
        });
        form.addEventListener('change', function (e) {
            var radio = e.target.closest('input[type="radio"]');
            var fieldsets = steps[current].querySelectorAll('fieldset');
            if (radio && fieldsets.length === 1 && !steps[current].querySelector('input[type="text"], select')) {
                setTimeout(function () { show(current + 1); }, 180);
            }
        });
        show(0);
    });
})();
