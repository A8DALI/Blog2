$(function () {
    $('.btn-delete').click(function (event) {
        event.preventDefault();

        var link = $(this).attr('href');
        var $modal = $('#modal_delete');

        $modal.modal('show');

        $modal.find('.btn-confirm-delete').click(function () {
            window.location.href = link;

        });
    });

});