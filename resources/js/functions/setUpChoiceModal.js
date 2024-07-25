window.setUpChoiceModal = function (name, modalContentUrl, inputSelector) {
    var capitalizingName = capitalize(name);
    var launchModalBtnSelector = '#' + 'launch' + capitalizingName + 'Modal';
    var modalSelector = '#' + name + 'Modal';

    $(launchModalBtnSelector).click(function () {
        $(modalSelector + ' .modal-body').load(modalContentUrl, function () {
            $(modalSelector).on('dblclick', 'tr', function () {
                var inputValue = $(this).find('td:first').text();
                $(inputSelector).val(inputValue);
            });
        });
    });
}
