$(function () {

    $('.vj-header_clickable, .visualijoper__row_clickable .vj-row__header').click(function () {
        $(this).parent().children('.vj-body').children('.vj-body__content').toggle();
    });

});