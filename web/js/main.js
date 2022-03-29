$(function () {
    $('nav#menu').mmenu({
        extensions: [
            'position-front', // запрет смещения
            'widescreen', 'pagedim-black', 'fx-panels-none'],
        setSelected: true,
        offCanvas: {
            zposition: "front"
        },
        counters: false,
        navbar: {'title': 'точьВточь'},
        navbars: [
            {
                type: 'tabs',
                content: [
                    '<a href="#panel-menu"><i class="fa fa-bars"></i> <span>Меню</span></a>',
                    '<a href="#panel-account"><i class="fa fa-user"></i> <span>Аккаунт</span></a>',
                ]
            },
            {
                position: 'bottom',
                content: [
                    // "<a href='https://www.instagram.com/tvtshop_ru'><img style='filter: grayscale(1);' width='20' src='/images/ico/iconw-f-inst.png' alt='Страница в instagram'></a>",
                    "<a href='tel:89953288200'><span class='glyphicon glyphicon glyphicon-earphone' aria-hidden='true'></span> <span itemprop='telephone'>8-(995)-328-82-00</span></a>"
                    // "<a href='https://chat.whatsapp.com/LB66wVnwlf18zHlhrvo4ZY'><img style='filter: grayscale(1);' width='20' src='/images/ico/iconw-f-phon.png' alt='Группа в whatsapp'></a>"
                ]
            }
        ]
    }, {
        classNames: {
            selected: "current-page"
        },
        navbars: {
            breadcrumbs: {
                removeFirst: true
            }
        }
    });

    $('nav#menu').css("display", "")
});

var options = {
    url: "/categories/search-queries",
    getValue: "queries_text",
    list: {
        match: {
            enabled: true
        }
    }
};

$(".input_item-search").easyAutocomplete(options);

$(document).on("click", ".product-heart-button", function (e) {
    let favoritesLink = $(this);
    favoritesLink.toggleClass('product-heart-button product-heart-button-active');
    let productBlock = $(this).parents('div.product-key');
    let productId = productBlock.data('key');

    $.ajax({
        url: '/favorites-products/add-product-in-favorites-products',
        type: 'get',
        data: {
            product_id: productId
        },
        success: function (res) {
            if (!res) {
                alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
                // $(this).show();
                // $('.product_show_more_loading').hide();
            }
            $('.header-count-product-in-favorites').html(res)

        },
        error: function () {
            favoritesLink.toggleClass('product-heart-button product-heart-button-active');
            alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
            // $(this).show();
            // $('.product_show_more_loading').hide();
        }
    });

    return false;
});


