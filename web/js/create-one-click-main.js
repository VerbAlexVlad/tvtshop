

// Форма оформления заказа в один клик
$(document).on('submit', 'form.create-one-click-form', function (e) {
    console.log('1');
    let form = this;
    let checked = document.querySelectorAll('form.product-cart-form input:checked');

    if (checked.length <= 0)  {
        document.querySelectorAll('form.product-cart-form label').forEach(function (elem) {
            elem.style.borderColor = '#F93C00';
        });

        let removeStyle = function () {
            document.querySelectorAll('form.product-cart-form label').forEach(function (elem) {
                elem.style.borderColor = '';
            });
        };
        setTimeout(removeStyle, 500);
        return false;
    }

    return false;
}).on('submit', 'form.create-one-click-form', function (e) {
    e.preventDefault();
});
console.log('3');