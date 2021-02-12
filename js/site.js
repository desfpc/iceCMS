$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $('.person-round').click(function () {

        var round = $(this);
        if(round.hasClass('passive')){
            round.children('.person-info').show(300, function() {
                round.removeClass('passive');
                round.addClass('active');
            });
        }
    });

    $('.person-info .close').click(function () {

        var round = $(this).parent().parent();
        if(round.hasClass('active')){
            round.children('.person-info').hide(300, function () {
                round.removeClass('active');
                round.addClass('passive');
            });
        }
    });

})