$(document).ready(function() {
    $('.count-product-in-cart').click(function () {
        let productSize = $(this).parents('div.product-item').data('key');
        let type = $(this).data('type');
        let $input = $(this).parent().find('input');
        let count;
        if(type === 'minus') {
            count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
        } else {
            count = parseInt($input.val()) + 1;
        }

        $.ajax({
            url: '/cart/count-products',
            type: 'get',
            data: {
                product_size_id: productSize,
                count: count
            },
            success: function (res) {
                if (!res) {
                    alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
                    // $(this).show();
                    // $('.product_show_more_loading').hide();
                }

                $input.val(count);
                $input.change();

                $('.product_discount').html(res.products_discount);
                $('.product_price').html(res.products_price);
                $('.summ-discount').html(res.summ_discount);
            },
            error: function () {
                alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
                // $(this).show();
                // $('.product_show_more_loading').hide();
            }
        });


        return false;
    });

    $('.delete-product_product-info').click(function () {
        let productBlock = $(this).parents('div.product-item');
        let productSize = productBlock.data('key');


        $.ajax({
            url: '/cart/delete',
            type: 'get',
            data: {
                size_id: productSize
            },
            success: function (res) {
                if (!res) {
                    alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
                    // $(this).show();
                    // $('.product_show_more_loading').hide();
                }

                productBlock.remove();

                $('.product_discount').html(res.products_discount);
                $('.product_price').html(res.products_price);
                $('.summ-discount').html(res.summ_discount);
            },
            error: function () {
                alert('Не удалось загрузить список. Попробуйте перезагрузить страницу');
                // $(this).show();
                // $('.product_show_more_loading').hide();
            }
        });


        return false;
    });
});