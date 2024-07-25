$(document).ready(function () {
    $('.order-item-details-btn').click(function () {
        var id = $(this).data('id');
        $('.' + id).toggleClass('d-none');
        return false;
    });
});
