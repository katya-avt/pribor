window.setUpChoiceModalWithFilterForm = function (name, modalContentUrl, inputSelector) {
    var modalSelector = '#' + name + 'Modal';

    $(modalSelector).on('show.bs.modal', function () {
        $(modalSelector + ' .modal-body').load(modalContentUrl);
    });

    $(modalSelector + ' .modal-body').on('dblclick', 'tr', function () {
        var inputValue = $(this).find('td:first').text();
        $(inputSelector).val(inputValue);
    });

    $(modalSelector).on('submit', 'form', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'GET',
            url: modalContentUrl,
            data: formData,
            success: function (data) {
                $(modalSelector + ' .modal-body').html(data);
            }
        });
    });

    $(modalSelector).on('click', '.filter-clean', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: modalContentUrl,
            success: function (data) {
                $(modalSelector + ' .modal-body').html(data);
            }
        });
    });

    $(modalSelector).on('click', '.page-link', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var formData = $("#" + name + "FilterForm").serialize();
        url += (url.indexOf('?') !== -1 ? '&' : '?') + formData;
        $.ajax({
            type: 'GET',
            url: url,
            data: formData,
            success: function (data) {
                $(modalSelector + ' .modal-body').html(data);
            }
        });
    });
}
