$(document).ready(function () {
	var autoplay = 10000;
	var width = 0;

	function move() {
		let elem = $('.new-slider__thumb-progress')
		let autoplayTime = autoplay / 100;
		let id = setInterval(frame, autoplayTime);

		function frame() {
			if (width >= 100) {
				width = 0
			} else {
				width++;
				elem.width('' + width + '%');
			}
		}
	}

	var mainGallery = new Swiper('#main-slider', {
		slidesPerView: 1,
		pagination: {
			el: '.main-slider__pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '.main-slider__next-btn',
			prevEl: '.main-slider__prev-btn',
		},
		loop: true,
		speed: 600,
		autoplay: {
			delay: autoplay,
			disableOnInteraction: false
		},
		watchSlidesProgress: true,
	});

// Применить высоту ко всем слайдам
	mainGallery.slides.css('height', $('#main-slider').height() + 'px');

	function isMapVisible() {
		var mapElement = document.getElementById('map');
		var rect = mapElement.getBoundingClientRect();
		var windowHeight = window.innerHeight || document.documentElement.clientHeight;

		return rect.top <= windowHeight && rect.bottom >= 0;
	}

	function initMap() {
		ymaps.ready(function () {
			if (document.querySelector('#map')) {
				let center = $(window).width() < 992 ? [56.076182, 47.228092] : [56.075785, 47.233570]
				var myMap = new ymaps.Map('map', {
						center: center,
						zoom: 17,
						controls: []
					}),
					myPlacemark = new ymaps.Placemark([56.076182, 47.228092], {
							balloonContent: 'г. Чебоксары, Лапсарский проезд, 63',
						}, {
							preset: 'islands#redLeisureIcon'
						}
					)
				myMap.geoObjects
					.add(myPlacemark)
				myMap.behaviors.disable('scrollZoom');
			}
		});
	}

	function handleScroll() {
		if (isMapVisible()) {
			initMap();
			window.removeEventListener('scroll', handleScroll);
		}
	}

	window.addEventListener('scroll', handleScroll);


	$(document).on('click', 'button.plus, button.minus', function () {
		var qty = $(this).parent('.quantity').find('.qty');
		var val = parseFloat(qty.val());
		var max = parseFloat(qty.attr('max'));
		var min = parseFloat(qty.attr('min'));
		var step = parseFloat(qty.attr('step'));

		if ($(this).is('.plus')) {
			if (max && (max <= val)) {
				qty.val(max).change();
			} else {
				qty.val(val + step).change();
			}
		} else {
			if (min && (min >= val)) {
				qty.val(min).change();
			} else if (val > 1) {
				qty.val(val - step).change();
			}
		}
		if ($('button[name="update_cart"]')) {
			$('button[name="update_cart"]').trigger("click");
		}
	});

	function closeCheckoutForm() {
		$('.woocommerce-checkout__wrapper').removeClass('opened');
	}

	$('body').on('click', '.show-order-form', function () {
		$('.woocommerce-checkout__wrapper').addClass('opened');
	})
	$('body').on('click', '.woocommerce-checkout__form,.form-content form,.popup-slider__wrapper', function (e) {
		e.stopPropagation();
	})
	$('body').on('click', '.woocommerce-checkout__wrapper,.woocommerce-checkout__close-form', function () {
		closeCheckoutForm();
	})

//    Название файла для input[type='file'] в форме "Откликнуться на вакансию":
	$("#fl_inp").change(function () {
		var filename = $(this).val().replace(/.*\\/, "");
		$("#fl_nm").html(filename);
	});


	$('body').on('click', '.vacancies-item__btn', function () {
		let title = $(this).data('title');
		let formContent = $('.form-content');
		$(formContent).find('#your-vacancy').val(title).change();
		$(formContent).addClass('show-form')
	})

	function closeFormContent() {
		$('.form-content').removeClass('show-form')
	}

	$('body').on('click', '.form-content,.form-close', function () {
		closeFormContent();
	})

	$('body').on('click', '.toggle', function () {
		$(this).toggleClass('clicked');
		$('body').toggleClass('no-scroll')
		let height = $('header').height();
		console.log(height)
		$('.mobile-header').css('height', 'calc(100vh - ' + height + 'px)').toggleClass('opened');
	})

	$('.mobile-header').on('click', '.menu-item.menu-item-has-children', function () {
		$('.mobile-header .menu').toggleClass('show-single-item');
		$(this).toggleClass('show-items');
	})

	$('.wp-block-gallery').on('click', '.wp-block-image', function () {
		const galleryBlock = $(this).parent('.wp-block-gallery');
		const slideIndex = $(this).index();
		const images = getSliderImages(galleryBlock);
		createPopUpSlider(images, slideIndex);
	})

	function getSliderImages(parent) {
		const imagesElements = $(parent).find('img');
		const imgs = [];
		$(imagesElements).each(function (i, el) {
			imgs.push($(el).attr('src'))
		})
		return imgs;
	}

	function createPopUpSlider(images, index) {
		$sliderHtml = `<div class="popup-slider"><div class="popup-slider__wrapper"><div class="popup-slider__close"><svg><use href="#close-btn"></use></svg></div><div class="inner"><div class="popup-slider__slide swiper-container" id="popup-slider">
							<div class="swiper-wrapper">`;
		$(images).each(function (i, el) {
			$sliderHtml += `<div class="swiper-slide popup-slider__slide"><img src="` + el + `" alt=""></div>`;
		})
		$sliderHtml += `</div></div></div>`;
		$sliderHtml += `<div class="popup-slider__pagination"></div><div class="popup-slider__navigation"><div class="popup-slider__btn popup-slider__prev"><svg><use href="#prev"></use></svg></div><div class="popup-slider__btn popup-slider__next"><svg><use href="#next"></use></svg></div></div>`;
		$sliderHtml += `</div></div>`;
		$('body').append($sliderHtml);
		$('body').addClass('no-scroll');
		var progectSlider = new Swiper('#popup-slider', {
			slidesPerView: 1,
			initialSlide: index,
			pagination: {
				el: '.popup-slider__pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.popup-slider__next',
				prevEl: '.popup-slider__prev',
			},
			flipEffect: {
				rotate: 30,
				slideShadows: false,
			},
		})
	}

	function closeGallery() {
		$('.popup-slider').animate({'opacity': 0}, 50, function () {
			$(this).remove();
			$('body').removeClass('no-scroll');
		});
	}

	$('body').on('click', '.popup-slider,.popup-slider__close', function () {
		closeGallery();
	})

	$(document.body).on('added_to_cart removed_from_cart', function() {
		$.ajax({
			url: wc_cart_fragments_params.ajax_url,
			type: 'POST',
			data: {
				action: 'update_header_basket_count'
			},
			success: function(response) {
				$('.header__basket-count').html(response);
			}
		});
	});
});