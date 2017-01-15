$(document).ready(function () {
    $('#loginForm').submit(function (event) {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#loginForm");

        //not safe unless you're using https
        $.ajax({
            type: 'POST',
            url: "php/login.php",
            data: f.serialize(),
            success: function () {
                window.location.href = '/admin';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#errorMessage").fadeIn().removeClass('hidden')
                    .find("> p").text(xhr.responseText);
                $(".form-control").val('');
            }
        });
    });
});

