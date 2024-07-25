window.setUpChangeModal = function (formIdName, baseUrl) {
    var modal = document.getElementById(formIdName);
    if (modal !== null) {
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var itemId = button.getAttribute('data-item-id');
            var urlEdit = baseUrl + '/' + itemId + '/edit';
            var urlPatch = baseUrl + '/' + itemId;
            var modalBody = modal.querySelector('.modal-body');

            $(modalBody).load(urlEdit);

            $(modal).on('submit', 'form', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'PATCH',
                    url: urlPatch,
                    data: formData,
                    statusCode: {
                        405: function (response) {
                            sessionStorage.setItem('message', 'Изменение прошло успешно.');
                            window.location.reload();
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        if (response.status === 422) {
                            var errorDivSelector = '#error-alert';
                            var divWithErrorInsideModalBody = modalBody.querySelector(errorDivSelector);
                            $(divWithErrorInsideModalBody).text(response.responseJSON.message);
                            $(divWithErrorInsideModalBody).removeClass('d-none');
                        }
                    }
                });
            });
        });
    }
}
