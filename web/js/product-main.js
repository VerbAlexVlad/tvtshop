// Форма оформления заказа в один клик
// $(document).on('submit', 'form.form-one-click', function(event){
//     $(this).find('.order_in_one_click').text('Загружается');
//     var data = $(this).serialize();
//
//     $.ajax({
//         url: '/order/order-one-click',
//         type: 'post',
//         data: data,
//         success: function (res) {
//             if (!res) alert('Ошибка!!!');
//             $('.error-one-click').html(res);
//         },
//         error: function () {
//             alert('Ошибка!');
//         },
//     });
//
//     $(this).find('.order_in_one_click').text('Жду звонка');
//
//     return false;
// }).on('submit', 'form.form-one-click', function(e){
//     e.preventDefault();
// });
$(function () {
    var tab = $('#tabs .tabs-items > div');
    tab.hide().filter(':first').show();

    // Клики по вкладкам.
    $('#tabs .tabs-nav a').click(function () {
        tab.hide();
        tab.filter(this.hash).show();
        $('#tabs .tabs-nav a').removeClass('active');
        $(this).addClass('active');
        return false;
    }).filter(':first').click();

    // Клики по якорным ссылкам.
    $('.tabs-target').click(function () {
        $('#tabs .tabs-nav a[href=' + $(this).data('id') + ']').click();
    });
});

$('form.product-cart-form').on('change', 'input:radio', function (event) {
// $('input:radio[name="ProductSizes[id]"]').on('change', function () {
    let cartButton = $('button.product-cart-button')[0];
    cartButton.classList.remove('hidden');
    let cartLink = $('a.product-cart-button')[0];
    if (cartLink != undefined) {
        cartLink.classList.add('hidden');
    }
    let param_id = $(this).data('param-id');

    $.ajax({
        url: '/products/product-size-param-list',
        type: 'get',
        data: {
            param_id: param_id
        },
        success: function (res) {
            if (!res) alert('Ошибка!!!');

            $('.table-param-section').html(res);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});

// Форма оформления заказа в один клик
$(document).on('submit', 'form.product-cart-form', function (event) {
    let form = this;
    let checked = document.querySelectorAll('form.product-cart-form input:checked');

    if (checked.length > 0) {
        let cartButton = $(form).find('button.product-cart-button')[0];
        let product_alias = cartButton.dataset.product_alias;
        let cartLink = $(form).find('a.product-cart-button')[0];

        let size = $(form).serialize().split('=').pop();

        $.ajax({
            url: '/cart/add-size-in-cart',
            type: 'get',
            data: {
                product_alias: product_alias,
                size: size
            },
            success: function (res) {
                var arr = JSON.parse(res);

                if (arr['error']) {
                    alert(['error']);
                } else {
                    cartButton.classList.add('hidden');
                    cartLink.classList.remove('hidden');

                    $('.header-count-product-in-cart').html(arr['count'])
                    $('.cartFixed').show();
                }
            },
            error: function () {
                alert('Ошибка!');
            }
        });

        // $(this).find('button.product-cart-button').classList.toggle('hidden');
        // $(this).find('a.product-cart-button').classList.toggle('hidden');

        return false;

    } else {
        document.querySelectorAll('form.product-cart-form label').forEach(function (elem) {
            elem.style.borderColor = '#F93C00';
        });

        let removeStyle = function () {
            document.querySelectorAll('form.product-cart-form label').forEach(function (elem) {
                elem.style.borderColor = '';
            });
        }
        setTimeout(removeStyle, 500);
    }
}).on('submit', 'form.product-cart-form', function (e) {
    e.preventDefault();
});


$('form.create-one-click-form').on('change', 'input:radio', function (event) {
// $('input:radio[name="ProductSizes[id]"]').on('change', function () {
    let cartButton = $('button.product-cart-button')[0];
    cartButton.classList.remove('hidden');
    let cartLink = $('a.product-cart-button')[0];
    if (cartLink != undefined) {
        cartLink.classList.add('hidden');
    }
    let param_id = $(this).data('param-id');

    $.ajax({
        url: '/products/product-size-param-list',
        type: 'get',
        data: {
            param_id: param_id
        },
        success: function (res) {
            if (!res) alert('Ошибка!!!');

            $('.table-param-section').html(res);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});
// Форма оформления заказа в один клик

$(document).on('submit', 'form.create-one-click-form', function (e) {
    const self = $(this);
    const referenceButtonSubmit = 'button[type=submit]';
    const originalMessageSubmitButton = $(referenceButtonSubmit, self).html();

    try {
        let form = $(this);
        let formData = form.serialize();
        let checked = document.querySelectorAll('form.create-one-click-form input:checked');

        if (checked.length > 0) {
            $.ajax({
                url: '/orders/create-one-click',
                type: 'get',
                dataType: 'json',
                data: formData,
                complete: function (data) {
                    console.log(data.responseText);
                    $(form).html(data.responseText);
                },
            });
        } else {
            document.querySelectorAll('form.create-one-click-form label').forEach(function (elem) {
                elem.style.borderColor = '#F93C00';
            });

            let removeStyle = function () {
                document.querySelectorAll('form.create-one-click-form label').forEach(function (elem) {
                    elem.style.borderColor = '';
                });
            }
            setTimeout(removeStyle, 500);
        }
    } catch (err) {
        console.log("Покупка в один клик:", err);
    }

    e.preventDefault();
}).on('submit', 'form.create-one-click-form', function (e) {
    e.preventDefault();
});

// $('form#product-cart-form').on('click', '.product-cart-button', function(event){
//     var data = $(this).serialize();
//
// }).on('submit', 'form.product-cart-form', function(e){
//     alert(1);
//     e.preventDefault();
// });
