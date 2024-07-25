window.setUpChoiceModalWithNestedOptions = function (name, modalContentUrl, inputSelector) {
    var capitalizingName = capitalize(name);
    var launchModalBtnSelector = '#' + 'launch' + capitalizingName + 'Modal';
    var modalSelector = '#' + name + 'Modal';
    var detailsBtnSelector = '.' + name + '-details-btn';

    $(launchModalBtnSelector).click(function () {
        $(modalSelector).on('shown.bs.modal', function () {
            $(modalSelector + ' .modal-body').load(modalContentUrl, function () {

                $(detailsBtnSelector).click(function () {
                    var id = $(this).data('id');
                    $('.' + id).toggleClass('d-none');
                    return false;
                });

                $(modalSelector).on('dblclick', 'tr.selectable', function () {
                    var id = $(this).find('td.selectable-attribute, th.selectable-attribute').text();
                    $(inputSelector).val(id);
                });
            });
        });
    });
}
