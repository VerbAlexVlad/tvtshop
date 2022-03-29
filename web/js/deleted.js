$(document).on('click', '.vatch-button', function(){
    let id = $(this).data('id');
    let thisElem = this;
    $.ajax({
        url: '/deleted/vathc',
        type: 'get',
        data: {
            id : id
        },
        success: function () {
            thisElem.classList.toggle('btn-success')
        }
    });
});

$(document).on('click', '.good-button', function(){
    let id = $(this).data('id');
    let thisElem = this;
    $.ajax({
        url: '/deleted/good',
        type: 'get',
        data: {
            id : id
        },
        success: function () {
            thisElem.classList.toggle('btn-success')
        }
    });
});

$(document).on('click', '.bad-button', function(){
    let id = $(this).data('id');
    let thisElem = this;
    $.ajax({
        url: '/deleted/bad',
        type: 'get',
        data: {
            id : id
        },
        success: function () {
            thisElem.classList.toggle('btn-danger')
        }
    });
});