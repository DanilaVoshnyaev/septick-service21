document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('catalogFilters');
    if (!form) return;

    // AJAX-фильтрация для чекбоксов
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            // Дебаунс 300мс
            clearTimeout(cb._timeout);
            cb._timeout = setTimeout(() => {
                submitFilters(form);
            }, 300);
        });
    });

    // Сортировка без перезагрузки
    const sortSelect = form.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            submitFilters(form);
        });
    }

    function submitFilters(form) {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        fetch(`${form.action}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.text())
            .then(html => {
                // Обновляем только сетку товаров
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('.catalog-grid');
                const oldGrid = document.querySelector('.catalog-grid');
                if (newGrid && oldGrid) {
                    oldGrid.innerHTML = newGrid.innerHTML;
                    // Обновляем пагинацию
                    const newPagination = doc.querySelector('.pagination');
                    const oldPagination = document.querySelector('.pagination');
                    if (newPagination && oldPagination) {
                        oldPagination.replaceWith(newPagination);
                    }
                    // Скролл к началу каталога
                    document.querySelector('.catalog-grid').scrollIntoView({ behavior: 'smooth' });
                } else {
                    // Fallback: полная перезагрузка
                    window.location.href = `${form.action}?${params.toString()}`;
                }
            })
            .catch(err => {
                console.error('Filter error:', err);
                // Fallback
                form.submit();
            });
    }
});