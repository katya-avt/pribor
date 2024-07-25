window.setUpChoiceModalForCurrentSpecificationsAndRouteOfItem = function (name, modalContentBaseUrl, inputSelector) {
    var capitalizingName = capitalize(name);
    var launchModalBtnSelector = '#' + 'launch' + capitalizingName + 'Modal';
    var modalSelector = '#' + name + 'Modal';

    $(launchModalBtnSelector).click(function () {
        var itemId = $(launchModalBtnSelector).data('item-id');
        var url = modalContentBaseUrl + '/' + itemId;

        $(modalSelector + ' .modal-body').load(url);

        $(modalSelector + ' .modal-body').on('dblclick', 'tr', function () {
            var inputValue = $(this).find('td:first').text();
            $(inputSelector).val(inputValue);
        });
    });
}
