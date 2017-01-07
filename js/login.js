$(document).ready(function () {
    $('#loginForm').submit(function (event) {
        //stop page from reloading after submit
        event.preventDefault();
        var f = $("#loginForm");

        //not safe unless you're using https
        $.ajax({
            type: 'POST',
            url: "php/login.php",
            data: f.serialize(),
            success: function (data) {
                //alert(data);
                window.location.href = './admin';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#errorMessage").fadeIn().removeClass('hidden');
                $("#errorMessage").find("> p").text(thrownError);
                $(".form-control").val('');
            }
        });
    });
});