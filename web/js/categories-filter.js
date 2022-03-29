$(document).ready(function () {
    document.querySelectorAll('input[type="checkbox"].category-checkbox:checked').forEach(function (elem) {
        let container = $(elem).parent();

        container.find('input[type="checkbox"]').prop({
            indeterminate: false,
        });

        function checkSiblings(el) {
            let parent = el.parent().parent(),
                all = true;

            el.siblings().each(function () {
                return all = ($(elem).children('input[type="checkbox"]').prop("checked"));
            });


            if (all) {
                parent.children('input[type="checkbox"]').prop({
                    indeterminate: true,
                });

                checkSiblings(parent);
            } else {
                el.parents("li").children('input[type="checkbox"]').prop({
                    indeterminate: true,
                    checked: false
                });
            }
        }

        checkSiblings(container);
    });

    //Отображаем\скрываем потомков checkbox_ов
    $('.checkbox-area .spoiler').click(function () {
        let block = $(this).closest('li').children('ul');
        if ($(block).is(':hidden')) {
            $(block).slideDown(100);
            $(this).removeClass('closed');
        } else {
            $(block).slideUp(100);
            $(this).addClass('closed');
        }
        return false;
    });

    $('.checkbox-area ul').each(function () {
        let cur_obj = $(this);
        let parent_li = $(cur_obj).parent('li');

        $(parent_li).children('a').addClass('closed');
        $(parent_li).children('ul').hide();
    });

    $('input[type="checkbox"]').change(function (e) {
        let checked = $(this).prop("checked"),
            container = $(this).parent();

        container.find('input[type="checkbox"]').prop({
            indeterminate: false,
            checked: checked
        });

        function checkSiblings(el) {
            let parent = el.parent().parent(),
                all = true;

            el.siblings().each(function () {
                return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
            });

            if (all && checked) {
                parent.children('input[type="checkbox"]').prop({
                    indeterminate: false,
                    checked: checked
                });

                checkSiblings(parent);
            } else if (all && !checked) {
                parent.children('input[type="checkbox"]').prop("checked", checked);
                parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                checkSiblings(parent);
            } else {
                el.parents("li").children('input[type="checkbox"]').prop({
                    indeterminate: true,
                    checked: false
                });
            }
        }

        checkSiblings(container);
    });
});