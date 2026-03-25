$(document).ready(function() {
    $('#pwd').on('focusin', function() {
        const inputField = $(this);

        $('#userLabel').addClass('u3bW4e');
        if (inputField.val() === '') {
            $('#userLabel').addClass('CDELXb');
        }
    });

    $('#pwd').on('focusout', function() {
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

        var pwd = $('#pwd').val();

        if (pwd.length == 0) {
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

    $(".show-hide").click(function(){
        var pwdField = $("#pwd");
        var type = pwdField.attr("type");
        
        if (type === "password") {
        pwdField.attr("type", "text");
        } else {
        pwdField.attr("type", "password");
        }
    });
   
});