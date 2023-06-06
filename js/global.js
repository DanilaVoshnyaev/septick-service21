$(function () {
    // Прокрутка таблиц для нешироких экранов
//    if ($(window).width() <= 840) {
//        var inner_width = $('.inner').width();
//        $('table').each(function (i, e) {
//            if ($(e).width() > inner_width) {
//                $(e).wrap('<div class="table_wrap table_wrap_' + i + '" style="overflow-y: scroll;overflow-x: visible; padding-top: 25px"></div>');
//                $('.table_wrap_' + i).prepend('<div style="font-size:12px;   line-height:1.3; padding:10px 0 5px;">Для просмотра всей таблицы двигайте пальцем по экрану влево и вправо</div>');
//                $('.table_wrap_' + i).prepend('<div style="display: flex; display: -webkit-flex; display: -webkit-box; position: absolute; top: 0px; left: 50%; -webkit-transform: translateX(-50%); transform: translateX(-50%); align-items: center;"><span style="display:block; -webkit-transform: rotate(90deg); margin-right: 10px;  transform: rotate(90deg); width: 15px; height: 15px; background:url(/i/downwards-pointer_1.svg) no-repeat center;  background-size: 100%;"></span><span style="display:block; width: 20px; height: 20px; background:url(/i/finger-tap.svg) no-repeat center; background-size: 100%;"></span><span style="display:block; margin-left: 10px; -webkit-transform: rotate(-90deg);   transform: rotate(-90deg); width: 15px; height: 15px; background:url(/i/downwards-pointer_1.svg) no-repeat center;  background-size: 100%;"></span> </div>');
//            }
//        });
//    }

    $('.nav-icon-5').click(function (e) {
        e.preventDefault();
        $('#wrapper, nav.main-nav, a.target-burger').toggleClass('toggled');
        //$('.logo, main, footer').toggleClass('toggled');
        $('.nav-icon-5').toggleClass('open');
        $('html').toggleClass('popup_open');
//        $('.bun').toggleClass('white');
        $('.nav-icon-5').css('z-index', '100');

    });

    $(".layzy").lazyload();

    $('.form, .product-form, #home-form').submit(function () {
        var formData = $(this).serialize();
        $.ajax({dataType: "json", type: "POST", url: "/mail.php", data: formData, }).done(function (data) {
            if (data.status == 'OK') {
                alert(data.msg);
                $('.form').each(function () {
                    $(this)[0].reset();
                })
                $('#all_form_bg').hide();
            } else {
                alert(data.msg);
            }
        }).error(function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError)
        });
        return false;
    });


    $('#call_back,.order-btn,.calback-btn').click(function () {
        $('#all_form_bg').fadeIn(150);
    });

    $('.order-btn-new').click(function () {
        $('.count').addClass('active');
        var title = $(this).siblings('.title').text();
        $('#all_form #all_form_title').text('Заказать');
        $('#all_form #all_form_descr').text(title);
        $('#all_form input[name="form_name"]').val('Заказ ' + title);
    })


    $('#all_form_exit').click(function () {
        $('#all_form_bg').fadeOut(100);
    });

    $('#mobile_menu_btn').click(function () {
        $('#menu ul').toggleClass('menu_show');
    });



    if ($(window).width() <= 840) {

        var inner_width = $('.inner').width();
        $('table').each(function (i, e) {
            if ($(e).width() > inner_width) {
                $(e).wrap('<div class="table_wrap table_wrap_' + i + '" style="overflow-y: scroll;"></div>');
                $('.table_wrap_' + i).prepend('<div style="font-size:12px;   line-height:1.3; padding:10px 0 5px;">Для просмотра всей таблицы двигайте пальцем по экрану влево и вправо</div>');
            }
        });
    }


    $('body').on('click', '.hs', function (e) {
        e.preventDefault();
        $("div.hs_bg").remove();
        var fimg = $(this).data("src");
//        console.log(fimg);
        $('body').append('\n\
                        <div class="hs_bg" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7);">\n\
                        <div class="hs_bg_inner" style="display: flex;justify-content: center;align-items: center;height: 100%;width: 100%; ">\n\
                            <img style="display:inline-block;vertical-align:middle; max-height:96%; max-width: 96%; height: auto; float: none; border: 5px solid #FFC62A; cursor: zoom-out; background: #fff;" src="' + fimg + '" alt="" />\n\
                        </div>\n\
                        </div>');
        $("div.hs_bg").fadeIn(400);
        return false;
    });
    $('body').on('click', '.hs_bg', function () {
        $(this).fadeOut(200, function () {
            $(this).remove();
        });
    });


    $('table img').each(function () {
        $(this).wrap('<a class="hs" href="#" data-src="' + $(this).data('original') + '">');
    });

//    СПИСОК ТЕЛЕФОНОВ

    $('.header-phone').hover(function () {
        $(this).addClass('open-phone');

    }, function () {
        $(this).removeClass('open-phone');
    });






//    function GoJsTarget(target_name) {
//        if (typeof yaCounter47041062 !== 'undefined') {
//            yaCounter47041062.reachGoal(target_name);
//        }
//    }

//ОБРАБОТКА POPUP CONSTRUCT
    $('body').on('click', '.popup_form_inner', function (e) {
        e.stopPropagation();
    });

    $('body').on('click', '.popup_form_bg, .popup_exit, .system_popup_ok', function () {
        hideForm();
    });
    $(document).keydown(function (eventObject) {
        if (eventObject.which === 27) { //Esc
            hideForm();
        }
    });


    $('body').on('click', '.checkbox_item', function () {
        var price = $(this).closest('.action').find('.price').text()
        if (price != 0) {
            if ($(this).closest('.action').hasClass('active')) {
                $(this).closest('.action').find('.quantity_val').val(0);
                $(this).closest('.action').removeClass('active');
            } else {
                $(this).closest('.action').find('.quantity_val').val(1);
                $(this).closest('.action').addClass('active');
            }
            calc();
        }
    })


    $('body').on('click', '.quantity_btn', function () {
        var price = $(this).closest('.action').find('.price').text()

        if (price != 0) {

            var data = $(this).closest('.quantity_block').find('.quantity_val').val()
            if (($(this).hasClass('minus')) && data > 0) {
                $(this).closest('.quantity_block').find('.quantity_val').val(round(data * 1 - .1));
            }
            if (($(this).hasClass('plus'))) {
                $(this).closest('.quantity_block').find('.quantity_val').val(round(data * 1 + .1));
            }
            $(this).parent().find('.quantity_val').change();
        } else {

        }

    });

    function round(number) {
        return Math.round(number * 100) / 100;
    }

    $('.integer_input').on('keyup', function () {
        let val = $(this).val();
        $(this).val(val.replace(/[^0-9]/ig, ''));
        val = $(this).val();
    });

    $('input[min], input[max]').on('change', function () {
        let val = $(this).val();

        if ($(this).attr('min') && ($(this).attr('min') * 1) > val) {
            $(this).val($(this).attr('min'));
        }
        if ($(this).attr('max') && ($(this).attr('max') * 1) < val) {
            $(this).val($(this).attr('max'));
        }
    });


    $('.quantity_val').on('change keyup paste', function () {
        let val = $(this).val();
        var price = $(this).closest('.action').find('.price').text()

        if (price != 0) {
            if (val > 0) {
                $(this).parents('.action').addClass('active');
            } else {
                $(this).parents('.action').removeClass('active');
            }
            calc();
        }
    });

    $('.to_basket').click(function () {
        $(this).toggleClass('active');
        calc();
    });


    var total_price = 0;
    var positions = '';
    var items = '';

    function calc() {
//        console.log('calc');
        total_price = 0;
        positions = '';
        items = [];
        $('.action').each(function (i, el) {
            //if ($(el).find('.to_basket').hasClass('active')) {
            if ($(el).find('.quantity_val').val() * 1) {


                var qty = $(el).find('.quantity_val').val() || 1;
                var price = round($(el).find('.price').text().replace(/ /gi, '')) || 0;
                let sum = round(qty * price);
//                console.log(qty, price, sum);
                let item = {
                    id: $(el).parents('tr').find('.item_title').data('id'),
                    price_column: $(el).data('price_column'),
                    qty: qty,
                    price: price,
                    sum: round(qty * price),
                    type_item: $(el).data('type_item'),
                    title: $(el).parents('tr').find('.item_title').text(),
                    price_type: $(el).data('price_type'),
                    price_period: $(el).data('price_period')
                };

                total_price += item.sum;
                items.push(item);
                positions += CreatePositionText(item);

            }
        });
        if (positions) {
            $('.order_block_empty').fadeOut(100, function () {
                $('.order_block').fadeIn(100);
            });
        } else {
            $('.order_block').fadeOut(100, function () {
                $('.order_block_empty').fadeIn(100);
            });
        }
        $('.total_val').text(total_price.toLocaleString('ru'));
        $('.qty_items').text(items.length);
        $('.morhp_items').text(morph(items.length, 'товар', 'товара', 'товаров'));
        $('.positions').html(positions);
//        console.log(items);
    }

    function CreatePositionText(item) {
        return '<li><div class="position"><div class="item-title">' + item.type_item + ' ' + item.title + ' (' + item.price_period + ')</div><div class="item-quant"><span>Количество </span> '
            + item.qty + ' м<sup>3</sup></div><div class="item-summ">  \n\
				 <span>Сумма</span> ' + ((round(item.qty * item.price)).toLocaleString('ru')) + ' ₽ <span>(' + item.price_type + ')</span></div></div></li>';

    }


    var city_list = [];
    $('.delivery_table .locality').each(function (i, locality) {
        city_list.push({id: $(locality).data('id'), title: $(locality).text()});
    });
//    console.log(city_list);


    $('.order_button').click(function () {
        //showForm(title, text, action_buttons, remove_ok, max_width);



        let form = '<form id="order_productions"> \n\
	<div class="input_wrap"> \n\
		<label for="user_name">Имя <sup class="required">*</sup></label> \n\
		<input type="text" name="user_name" id="user_name" required> \n\
	</div> \n\
	<div class="input_wrap"> \n\
		<label for="user_phone">Номер телефона для связи с Вами <sup class="required">*</sup></label> \n\
		<input type="text" name="user_phone" id="user_phone" required> \n\
	</div> \n\
	<div class="input_wrap row"> \n\
		<label>Доставка</label> \n\
<div class="input_wrap_item"> \n\
		<input type="radio" id="company" name="delivery_method" value="company" checked="checked"><label for="company">Доставка нашей компанией</label> \n\
</div> \n\
<div class="input_wrap_item"> \n\
		<input type="radio" id="pickup" name="delivery_method" value="pickup"><label for="pickup">Самовывоз</label> \n\
</div> \n\
</div> \n\
	<div class="input_wrap locality_choose"> \n\
		<label for="locality">Населённый пункт <sup class="required">*</sup></label> \n\
		<select name="city_id" id="locality" required>';
        $.each(city_list, function (i, city) {
            form += '<option value="' + city.id + '">' + city.title + '</option>';
        });
        form += '<option value="0" style="color: #666;">Свой вариант в комментарии</option>';
        form += '</select>\n\
	</div> \n\
	<div class="input_wrap"> \n\
		<label for="user_comment">Ваше сообщение</label> \n\
		<textarea name="user_comment" id="feedback_text"></textarea> \n\
	</div> \n\
	<p class="form_note"> \n\
		Нажимая кнопку "Отправить", Вы соглашаетесь на обработку, хранение и направление  \n\
		Ваших персональных данных в целях рассмотрения обращения. \n\
	</p> \n\
	<button type="submit" class="btn">Отправить</button> \n\
</form>';

        let form_html = positions;
        form_html += '<div class="total_price">Итого ' + total_price + ' ₽</div>';
        form_html += form;

        showForm('Заказать продукцию', form_html, false, false, 500);
    });


    $('.to_delivery_table').click(function () {
        scroll_to($(this), $('.delivery_table'));
    });


    //ОБРАБОТКА  ФОРМЫ
    $("body").on('submit', '#order_productions', function (e) {
        e.preventDefault();

        if ($("#order_productions button").hasClass('wait')) {
            return false;
        }

        var ajax_data = $(this).serializeArray();
        ajax_data.push({name: 'action', value: 'order'});
        ajax_data.push({name: 'form_descr', value: 'Заказ продукции'});
        ajax_data.push({name: 'items', value: JSON.stringify(items)});

        //console.log(ajax_data);
        $("#callback button").addClass('wait');
        $.ajax({
            dataType: "json", type: "POST", url: "/scripts/ajax.php",
            data: ajax_data
        }).done(function (data) {
            if (data.status === 'OK') {
                $('#callback').each(function () {
                    $(this)[0].reset();
                });
                ShowMsgInPopup('success', 'Ваша заявка успешно отправлена!', 'Мы скоро вам позвоним');
            }
            if (data.status === 'ERROR') {
                ShowFailModal(data.msg);
                $("#order_productions button").removeClass('wait');
            }
            $("#order_productions button").removeClass('wait');

        }).error(function () {
            ShowFailModal('Ошибка :/');
            $("#order_productions button").removeClass('wait');
        });
    });


    $('body').change('input[name="delivery_method"]', function (i, radio) {
        let method_delivery = $('input[name="delivery_method"]:checked').val();
        if (method_delivery === 'company') {
            $('.locality_choose').fadeIn(300);
        } else {
            $('.locality_choose').fadeOut(300);
        }
    });



    function morph(n, f1, f2, f5) {
        n = Math.abs(parseInt(n)) % 100;
        if (n > 10 && n < 20)
            return f5;
        n = n % 10;
        if (n > 1 && n < 5)
            return f2;
        if (n == 1)
            return f1;
        return f5;
    }
});

//POPUP CONSTRUCT
function showForm(title, text, action_buttons, remove_ok, max_width) {

    $('#construct_popup.popup_form_bg').remove();
    let pattern_form = '<div class="popup_form_bg" id="construct_popup"> \n\
				<div class="popup_form_inner" ' + (max_width ? 'style="max-width: ' + max_width + 'px"' : '') + '>\n\
				<div class="popup_exit"></div> \n\
                <div class="popup_form_body"> \n\
                    <div class="system_popup_title"></div>\n\
                    <ol class="system_popup_text"></ol>\n\
                </div>\n\
                <div class="system_popup_action">\n\
                </div>\n\
            </div>\n\
        </div>';

    $('body').append(pattern_form);

    $('#construct_popup .system_popup_title').css('opacity', 1).show().text(title);
    $('#construct_popup .system_popup_text').css('opacity', 1).show().show().html(text);
    if (action_buttons !== undefined) {
        $('#construct_popup .system_popup_action').append(action_buttons);
    }
//	if (remove_ok == true) {
//		$('.system_popup_ok').hide();
//	} else {
//		$('.system_popup_ok').show();
//	}

    $('.popup_ajax_result').remove();
    $('#construct_popup .popup_form_body').css('height', 'auto');

    $('#construct_popup .popup_form_inner').css('margin-top', '150px');
    $('#construct_popup.popup_form_bg').fadeIn(100, function () {
        $('#construct_popup .popup_form_inner').fadeIn(100).animate({'margin-top': '0'}, 125);
    });
    $('html').addClass('popup_open');
}


function hideForm() {
    $('#construct_popup .popup_form_inner').fadeOut(2.5 * 50, function () {
        $(this).parents('#construct_popup.popup_form_bg').fadeOut(2.5 * 50, function () {
            $('#construct_popup .popup_form_inner').show();
            $('#construct_popup').remove();
            $('.popup_ajax_result').empty();
            $('html').removeClass('popup_open');
        });
    });
}



function ShowMsgInPopup(type, title, msg) {
    console.log('run ShowMsgInPopup');

//Только кнопка "OK"
    //$('.system_popup_action').html('<div class="action_button system_popup_ok">OK</div>');

    $('.popup_ajax_result').empty();
    if (!$('#construct_popup').length) {
        showForm(title);
    }

    var animation_success = '<svg id="successAnimation" class="" xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 70 70">\n\
            <path id="successAnimationResult" fill="#44aa54" d="M35,60 C21.1928813,60 10,48.8071187 10,35 C10,21.1928813 21.1928813,10 35,10 C48.8071187,10 60,21.1928813 60,35 C60,48.8071187 48.8071187,60 35,60 Z M23.6332378,33.2260427 L22.3667622,34.7739573 L34.1433655,44.40936 L47.776114,27.6305926 L46.223886,26.3694074 L33.8566345,41.59064 L23.6332378,33.2260427 Z"/>\n\
            <circle id="successAnimationCircle" cx="35" cy="35" r="24" stroke="#979797" stroke-width="2" stroke-linecap="round" fill="transparent"/>\n\
            <polyline id="successAnimationCheck" stroke="#979797" stroke-width="2" points="23 34 34 43 47 27" fill="transparent"/>\n\
        </svg>\n\
   ';

    var animate_block = '';
    if (type === 'success') {
        animate_block = animation_success;
    } else {
        animate_block = '<div class="errorAnimation">!</div>';
    }

    var inform_block = '<div class="popup_ajax_result result_' + type + '" style="opacity:0;">\n\
                <div class="popup_ajax_result_title">' + title + '</div>    \n\
                <div class="popup_animation">' + animate_block + '</div>\n\
                <div class="popup_ajax_result_msg">' + msg + '</div>    \n\
                </div>';
    $('#construct_popup .system_popup_title, #construct_popup .system_popup_text').css('opacity', '0');
    //$('.popup_form_body').css('minHeight', $('.popup_form_body').css('height'));
    $('#construct_popup .popup_form_body').append(inform_block);
    var height = $('.popup_ajax_result_msg').height() + 300;
    $('#construct_popup .popup_form_body').animate({'height': height + 'px'}, 600, function () {
        $('#construct_popup .system_popup_title, #construct_popup .system_popup_text').hide();
        $('.popup_ajax_result').css('opacity', 1);

//Анимация при успехе
//alert(type);
        if (type === 'success') {
            var animation = document.getElementById('successAnimation');
            animation.classList.remove('animated');
            void animation.parentNode.offsetWidth;
            animation.classList.add('animated');
        }
    });

}





function scroll_to(from, to) {
    var position_this = from.offset();
    var position = to.offset();
    var scroll_top = position.top - 20;
    var speed = Math.abs((position_this.top - scroll_top) / 3);

    $('body, html').animate({scrollTop: scroll_top}, speed);
    //$('html').animate({scrollTop: scroll_top}, speed);
}


function ShowSuccessModal(text) {
    $('#show_success_modal, #show_fail_modal').remove();

    $('body').append("<div id='show_success_modal' title='Кликните чтобы скрыть'><i class='fa fa-check' style='display:none;'></i> <span>" + text + "</span></div>");
    $('#show_success_modal').fadeIn(420, function () {
        $(this).delay(3500).fadeOut(700);
    });
}
function ShowFailModal(text) {
    $('#show_success_modal, #show_fail_modal').remove();

    $('body').append("<div id='show_fail_modal' title='Кликните чтобы скрыть'> <i class='fa fa-exclamation-circle'></i> <span>" + text + "</span></div>");
    $('#show_fail_modal').fadeIn(420, function () {
        $(this).delay(3500).fadeOut(700);
    });
}


$(function () {
    $('#footer ul li a').each(function () {
        var location = window.location.href;
        var link = this.href;
        if (location == link) {
            $(this).addClass('active');
        }
    });
});


$(function () {
    $(document).ready(function () {
        var location = window.location.href;
        console.log(location);
        let menuElements = $('body.parent_id_117 .sidebar ul li a');
        $(menuElements).each(function (i, el) {
            var link = el.href;
            console.log(location.indexOf(link));

            if (location.indexOf(link) == 0) {
                $(el).addClass('active')
            }
//            if (location.indexOf(link) = 0) {
//                $('body.parent_id_117 .aside nav ul li a.staty').addClass('active');
//            }

        })
    });

    //Стилизация таблиц статей - как обычные таблицы чтобы были:
    $('.parent_id_117 table').addClass('standart_table');
});



