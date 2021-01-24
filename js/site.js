$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    //  <a href="https://unsplash.it/1200/768.jpg?image=251" data-toggle="lightbox">
    //  <img src="https://unsplash.it/600.jpg?image=251" class="img-fluid">
    //  </a>

})