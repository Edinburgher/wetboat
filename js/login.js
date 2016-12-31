$(document).ready(function () {
    $('#loginForm').submit(function () {
        event.preventDefault();
        var f = $("#loginForm");

        //TODO: not safe unless SSL.. lol
        $.ajax({
            type: 'POST',
            url: "php/login.php",
            data: f.serialize(),
            success: function (data) {
                //alert(data);
                window.location.href = './admin.php';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(thrownError);
                $("#errorMessage").fadeIn().removeClass('hidden');
                $("#errorMessage > p").text(thrownError);
                $(".form-control").val('');
            }
        });
    });
});