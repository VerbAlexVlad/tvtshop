$(document).ready(function () {
    $(document).on('click', '.delete-product_product-info', function (event) {
        let productBlock = $(this).parents('div.product-item');
        let productId = productBlock.data('key');
        let e = document.querySelector(".header-count-product-in-favorites");
        let countProductInFavorites = e.textContent || e.innerText;

        $.ajax({
            url: '/favorites-products/delete',
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

                $(".favorites-products-block").html(res);


                $('.header-count-product-in-favorites').html(Number(countProductInFavorites)-1);
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