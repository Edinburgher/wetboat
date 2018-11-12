$(document).ready(function () {
    $("#loginForm").submit(function (event) {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#loginForm");

        //not safe unless you're using https
        userAction({
            data: f.serialize() + "&action=login",
            success: function () {
                window.location.href = "admin.php";
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#errorMessage").fadeIn().removeClass("hidden")
                    .find("> p").text(xhr.responseText);
                $(".form-control").val("");
            }
        });
    });
});

