var fancybox = 'off';

window.addEventListener("popstate", function (){
    'use strict';
    if (fancybox == 'on'){
        $.fancybox.close();
        fancybox = 'off';
    }
});

$("[data-fancybox]").fancybox({
    afterShow: function() {
        window.location.hash = '#fancybox';
        fancybox = 'on';
    },
    afterClose: function() {
        fancybox = 'off';
    }
});