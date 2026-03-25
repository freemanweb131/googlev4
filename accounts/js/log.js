$(document).ready(function() {
    $('#username').on('focusin', function() {
        const inputField = $(this);

        $('#userLabel').addClass('u3bW4e');
        if (inputField.val() === '') {
            $('#userLabel').addClass('CDELXb');
        }
    });

    $('#username').on('focusout', function() {
        $('#userLabel').removeClass('u3bW4e');
        if ($(this).val() === '') {
            $('#userLabel').removeClass('CDELXb').removeClass('u3bW4e');
        }
    });

    $('#nextToPassword').on('click', function() {
        $('form').submit();
    });

    $('form').on('submit', function(event) {
        event.preventDefault();

        var username = $('#username').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phoneRegex = /^[\d\s]{8,}$/;
        if (!emailRegex.test(username) && !phoneRegex.test(username)) {
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