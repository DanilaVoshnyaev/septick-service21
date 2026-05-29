
// ===== header.php =====
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('site-header-premium');
            const headerTop = document.querySelector('.header-top-premium');
            let lastScrollY = window.scrollY;

            // Sticky header logic
            function handleHeaderScroll() {
                const currentScrollY = window.scrollY;
                if (currentScrollY > 30) {
                    header.classList.add('scrolled');
                    headerTop?.classList.add('hidden');
                } else {
                    header.classList.remove('scrolled');
                    headerTop?.classList.remove('hidden');
                }
                lastScrollY = currentScrollY;
            }
            window.addEventListener('scroll', handleHeaderScroll, { passive: true });

            // Phone dropdown
            const phoneToggle = document.querySelector('.top-phone-toggle');
            const phonesDropdown = document.querySelector('.top-phones-dropdown');
            if (phoneToggle && phonesDropdown) {
                phoneToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    phonesDropdown.classList.toggle('active');
                    phoneToggle.classList.toggle('active');
                });
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.top-phones-wrapper')) {
                        phonesDropdown.classList.remove('active');
                        phoneToggle.classList.remove('active');
                    }
                });
            }

            // Search toggle
            const searchToggle = document.querySelector('.search-toggle');
            const searchClose = document.querySelector('.search-close-premium');
            const headerSearch = document.getElementById('header-search');
            const searchInput = headerSearch?.querySelector('input');

            function toggleSearch(show) {
                if (show) {
                    headerSearch?.classList.add('active');
                    searchInput?.focus();
                } else {
                    headerSearch?.classList.remove('active');
                }
            }
            searchToggle?.addEventListener('click', () => toggleSearch(true));
            searchClose?.addEventListener('click', () => toggleSearch(false));

            // Mobile menu
            const mobileToggle = document.querySelector('.mobile-toggle');
            const mobileMenu = document.getElementById('mobile-menu-premium');
            const mobileClose = document.querySelector('.mobile-menu-close');
            const mobileBackdrop = document.querySelector('.mobile-menu-backdrop');

            function openMobileMenu() { mobileMenu?.classList.add('active'); document.body.style.overflow = 'hidden'; }
            function closeMobileMenu() { mobileMenu?.classList.remove('active'); document.body.style.overflow = ''; }

            mobileToggle?.addEventListener('click', openMobileMenu);
            mobileClose?.addEventListener('click', closeMobileMenu);
            mobileBackdrop?.addEventListener('click', closeMobileMenu);

            // Modals
            const modals = document.querySelectorAll('.modal-premium');
            const openModalBtns = document.querySelectorAll('.open-modal');
            const closeModalBtns = document.querySelectorAll('.modal-close-btn, .modal-backdrop');

            function openModal(id) {
                const modal = document.getElementById(`modal-${id}`);
                if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; closeMobileMenu(); }
            }
            function closeModal(modal) { modal?.classList.remove('active'); document.body.style.overflow = ''; }

            openModalBtns.forEach(btn => {
                btn.addEventListener('click', function() { openModal(this.dataset.modal); });
            });
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', e => closeModal(e.target.closest('.modal-premium')));
            });



            // Phone mask
            document.querySelectorAll('input[type="tel"]').forEach(input => {
                input.addEventListener('input', function(e) {
                    let v = e.target.value.replace(/\D/g,'');
                    if (!v) { e.target.value = ''; return; }
                    if (v[0]==='7'||v[0]==='8') v = v.slice(1);
                    let f = '+7';
                    if (v.length>0) f += ' (' + v.slice(0,3);
                    if (v.length>=3) f += ') ' + v.slice(3,6);
                    if (v.length>=6) f += '-' + v.slice(6,8);
                    if (v.length>=8) f += '-' + v.slice(8,10);
                    e.target.value = f;
                });
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerH = header?.offsetHeight || 0;
                        window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - headerH - 20, behavior: 'smooth' });
                        closeMobileMenu();
                    }
                });
            });

            // Init
            window.addEventListener('load', () => header?.classList.add('loaded'));
        });
    

// ===== footer.php =====
    document.addEventListener('DOMContentLoaded', function() {

        // ===== Back to Top Button =====
        const backToTop = document.getElementById('backToTop');

        function toggleBackToTop() {
            if (window.pageYOffset > 400) {
                backToTop?.classList.add('visible');
            } else {
                backToTop?.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', toggleBackToTop, { passive: true });

        if (backToTop) {
            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ===== Modal Functionality =====
        const modals = document.querySelectorAll('.modal-premium');
        const openModalBtns = document.querySelectorAll('.open-modal');
        const closeModalBtns = document.querySelectorAll('.modal-close-btn, .modal-backdrop');

        function openModal(modalId) {
            const modal = document.getElementById(`modal-${modalId}`);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modal) {
            modal?.classList.remove('active');
            document.body.style.overflow = '';
        }

        openModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                openModal(this.getAttribute('data-modal'));
            });
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                closeModal(e.target.closest('.modal-premium'));
            });
        });

        // Подстановка названия товара в модалку заказа (каталог)
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.open-modal[data-product]');
            if (!trigger) return;
            const product = trigger.getAttribute('data-product') || '';
            const orderModal = document.getElementById('modal-order');
            if (!orderModal) return;
            const field = orderModal.querySelector('.js-order-product-field');
            const label = orderModal.querySelector('.js-order-product');
            if (field) field.value = product;
            if (label) {
                if (product) {
                    label.textContent = 'Товар: ' + product;
                    label.hidden = false;
                } else {
                    label.textContent = '';
                    label.hidden = true;
                }
            }
        });

        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modals.forEach(modal => closeModal(modal));
            }
        });

        // ===== Form Submission =====
/*        const callbackForm = document.getElementById('footerCallbackForm');
        if (callbackForm) {
            callbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<span>Отправка...</span>';

                const formData = new FormData(this);
                console.log('Callback form:', Object.fromEntries(formData));

                setTimeout(() => {
                    alert('Спасибо! Мы перезвоним вам в ближайшее время.');
                    this.reset();
                    closeModal(this.closest('.modal-premium'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 600);
            });
        }*/

        // ===== Phone Mask =====
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (!value) { e.target.value = ''; return; }
                if (value[0] === '7' || value[0] === '8') value = value.slice(1);

                let formatted = '+7';
                if (value.length > 0) formatted += ' (' + value.slice(0, 3);
                if (value.length >= 3) formatted += ') ' + value.slice(3, 6);
                if (value.length >= 6) formatted += '-' + value.slice(6, 8);
                if (value.length >= 8) formatted += '-' + value.slice(8, 10);
                e.target.value = formatted;
            });
        });

    });
