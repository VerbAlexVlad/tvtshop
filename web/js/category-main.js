(function (a) {
    a.fn.dcAccordion = function (d) {
        var b = {
            classParent: "dcjq-parent",
            classActive: "active",
            classArrow: "dcjq-icon",
            classCount: "dcjq-count",
            classExpand: "dcjq-current-parent",
            eventType: "click",
            hoverDelay: 300,
            menuClose: !0,
            autoClose: !0,
            autoExpand: !1,
            speed: "slow",
            saveState: !0,
            disableLink: !0,
            showCount: !1,
            cookie: "dcjq-accordion"
        };
        d = a.extend(b, d);
        this.each(function (e) {
            function d() {
                $activeLi = a(this).parent("li");
                $parentsLi = $activeLi.parents("li");
                $parentsUl = $activeLi.parents("ul");
                1 == b.autoClose && g($parentsLi,
                    $parentsUl);
                a("> ul", $activeLi).is(":visible") ? (a("ul", $activeLi).slideUp(b.speed), a("a", $activeLi).removeClass(b.classActive)) : (a(this).siblings("ul").slideToggle(b.speed), a("> a", $activeLi).addClass(b.classActive))
            }

            function h() {
            }

            function k() {
            }

            function l() {
                1 == b.menuClose && (a("ul", c).slideUp(b.speed), a("a", c).removeClass(b.classActive))
            }

            function g(f, d) {
                a("ul", c).not(d).slideUp(b.speed);
                a("a", c).removeClass(b.classActive);
                a("> a", f).addClass(b.classActive)
            }

            var c = this;
            (function () {
                $arrow = '<span class="' +
                    b.classArrow + '"></span>';
                var f = b.classParent + "-li";
                a("> ul", c).show();
                a("li", c).each(function () {
                    0 < a("> ul", this).length && (a(this).addClass(f), a("> a", this).addClass(b.classParent).append($arrow))
                });
                a("> ul", c).hide();
                1 == b.showCount && a("li." + f, c).each(function () {
                    var c = 1 == b.disableLink ? parseInt(a("ul a:not(." + b.classParent + ")", this).length) : parseInt(a("ul a", this).length);
                    a("> a", this).append(' <span class="' + b.classCount + '">(' + c + ")</span>")
                })
            })();
            1 == b.autoExpand && a("li." + b.classExpand + " > a").addClass(b.classActive);
            a("ul", c).hide();
            $allActiveLi = a("a." + b.classActive, c);
            $allActiveLi.siblings("ul").show();
            "hover" == b.eventType ? (e = {
                sensitivity: 2,
                interval: b.hoverDelay,
                over: d,
                timeout: b.hoverDelay,
                out: h
            }, a("li a", c).hoverIntent(e), e = {
                sensitivity: 2,
                interval: 1E3,
                over: k,
                timeout: 1E3,
                out: l
            }, a(c).hoverIntent(e), 1 == b.disableLink && a("li a", c).click(function (b) {
                0 < a(this).siblings("ul").length && b.preventDefault()
            })) : a("li a", c).click(function (c) {
                $activeLi = a(this).parent("li");
                $parentsLi = $activeLi.parents("li");
                $parentsUl = $activeLi.parents("ul");
                1 == b.disableLink && 0 < a(this).siblings("ul").length && c.preventDefault();
                1 == b.autoClose && g($parentsLi, $parentsUl);
                a("> ul", $activeLi).is(":visible") ? (a("ul", $activeLi).slideUp(b.speed), a("a", $activeLi).removeClass(b.classActive)) : (a(this).siblings("ul").slideToggle(b.speed), a("> a", $activeLi).addClass(b.classActive))
            })
        })
    }
})(jQuery);

$('.left-category').dcAccordion({
    speed: 100
});

let index = false;
$('.left-category a').each(function () {
    let getCategoryAlias = decodeURIComponent(window.location.pathname)

    let sPageURL = $(this).attr("href"),
        sURLVariables = sPageURL.split('?');

    if (sURLVariables[0] === getCategoryAlias) {
        $(this).children('span').css('color', '#F93C00');
        $(this).parents('a.dcjq-parent').addClass('active');
        $(this).parents('ul.category-products-list').css('display', 'block');
        index = true;
        return true;
    }
});
if (index === false) {
    $('.left-category li.main-depth').each(function () {
        $(this).children('a').addClass('active');
        $(this).children('ul').css('display', 'block');
    });
}


$(document).on("click", ".filter-btn-on", function (e) {
    let els = document.querySelectorAll(
        '.filter-block input'
    );

    Array.prototype.forEach.call(els, function (cb) {
        cb.checked = true;
    });
});

$(document).on("click", ".filter-btn-off", function (e) {
    let els = document.querySelectorAll(
        '.filter-block input'
    );

    Array.prototype.forEach.call(els, function (cb) {
        cb.checked = false;
    });
});

$(document).on("keyup", ".input_filter-search", function () {
    let value = $(this).val().toLowerCase();

    $(".filter-block li").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

function getUrlParameter(sParam) {
    let sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sResultParameter = [],
        sParameterName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0].includes(sParam)) {
            if (sParameterName[1] !== undefined) {
                sResultParameter.push(sParameterName[1])
            }
        }
    }

    return sResultParameter;
}

function getPage() {
    let page = getUrlParameter('page');

    if (page.length == 0) {
        return 1;
    }

    return Number(page[0]);
}

$(document).on('click', '.show-more_products', function () {
    if ($(this).val() == '???????????????? ??????') {
        $(this).val('????????????????...');
        $(this).attr('disabled', true);
    }

    let getCategoryAlias = this.dataset.categoryAlias;
    let getAll = this.dataset.all;
    let getSort = getUrlParameter('sort');
    let page = getPage() + 1;
    let getPerPage = getUrlParameter('per-page');
    let getColors = getUrlParameter('colors');
    let getSizes = getUrlParameter('sizes');
    let getBrands = getUrlParameter('brands');
    let getSeasons = getUrlParameter('seasons');
    let getPriceFrom = getUrlParameter('product_price_from');
    let getPriceTo = getUrlParameter('product_price_to');
    let getCategories = getUrlParameter('categories');

    $.ajax({
        url: '/categories/view',
        type: 'get',
        data: {
            category_alias: getCategoryAlias,
            sort: getSort[0],
            page: page,
            per_page: getPerPage[0],
            all: getAll,
            colors: getColors,
            sizes: getSizes,
            brands: getBrands,
            seasons: getSeasons,
            product_price_from: getPriceFrom,
            product_price_to: getPriceTo,
            categories: getCategories
        },
        success: function (res) {
            if (!res) {
                alert('???? ?????????????? ?????????????????? ????????????. ???????????????????? ?????????????????????????? ????????????????');
                // $(this).show();
                // $('.product_show_more_loading').hide();
            }

            let urlParam = window.location.search.split('&');
            let newUrlParam = '';
            let pageExist = false;

            $.each(urlParam, function (i, elem) {
                if (elem) {
                    if (elem.match('page=') && !elem.match('per-page=')) {
                        urlParam[i] = 'page=' + page;
                        pageExist = true;
                    }
                    newUrlParam = newUrlParam + '&' + urlParam[i];
                }
            });
            if (!pageExist && newUrlParam != '') {
                newUrlParam = newUrlParam + '&' + 'page=' + page;
            } else if (!pageExist && newUrlParam == '') {
                newUrlParam = 'page=' + page;
            }

            if (newUrlParam.charAt(0) == '&') {
                newUrlParam = newUrlParam.slice(1);
            }

            if (newUrlParam.charAt(0) == '?') {
                newUrlParam = newUrlParam.slice(1);
            }

            let newUrl = window.location.pathname + '?' + newUrlParam;


            history.pushState('', '', newUrl);

            $(".navigation-block_product-list").replaceWith(res);


            $(this).val('????????????????...');
            // location.href = newUrl;
            //
            // lazy = document.getElementsByClassName('lazy');
        },
        error: function () {
            $(this).val('????????????????...');
            alert('???? ?????????????? ?????????????????? ????????????. ???????????????????? ?????????????????????????? ????????????????');
            // $(this).show();
            // $('.product_show_more_loading').hide();
        }
    });
    return false;
});


let lastPos = 0;
window.addEventListener('scroll', () => {
    let textContent = window.pageYOffset < lastPos ? 1 : 0;

    let filterBlock = $('.products-category .filter')[0];
    let filterHeight = filterBlock.offsetHeight;

    if (textContent === 1) {
        filterBlock.style.top = '50px'
    } else {
        filterBlock.style.top = '-' + filterHeight + 'px'
    }

    lastPos = window.pageYOffset;
});










