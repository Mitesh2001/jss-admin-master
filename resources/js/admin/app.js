window.addEventListener('load', function () {
    alertify.set('notifier','position', 'top-right');
});

function showNotice(type, message) {
    var alertifyFunctions = {
        'success': alertify.success,
        'error': alertify.error,
        'info': alertify.message,
        'warning': alertify.warning
    };

    alertifyFunctions[type](message, 10);
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $(document).on('click', '.delete-button', function (event) {
        event.preventDefault();
        var element = $(this).parent();
        var promptMessage = $(this).attr('data-message');

        bootbox.confirm({
            size: "medium",
            message: promptMessage ? promptMessage : "Are you sure?",
            callback: function(result) {
                if (result === true) {
                    element.submit();
                }
            }
        });
    });
});

function showValidationErrors(errors, errorElement = '#error-container') {
    var errorsHtml = '<div class="alert alert-danger"><ul>';
    $.each(errors, function (key, value) {
        errorsHtml += '<li>' + value + '</li>';
    });

    errorsHtml += '</ul></div>';

    $(errorElement).html(errorsHtml);
}

function isNumber(evt, element, isPostCode) {
    evt = (evt) ? evt : window.event;

    if (window.getSelection().toString()) {
        element.value = "";
    }

    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    if (isPostCode && evt.target.value.length == 4) {
        return false;
    }

    return true;
}
