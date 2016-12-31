$(document).ready(function () {

    getDelay(function (delay) {
        $("#divSubmit").html("Der Delay beträgt zurzeit " + delay / 1000 + " Sekunden.");
    });

    $('#delayForm').submit(function () {
        event.preventDefault();
        var f = $("#delayForm");
        console.log(f.serialize());
        $.ajax({
            type: "POST",
            url: "php/users/setDelay.php",
            data: f.serialize(),
            processData: false,
            success: function (data) {
                $("#txtDelay").val('');
                $("#divSubmit").html("Der Delay beträgt jetzt " + data + " Sekunden.");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#txtDelay").val('');
                $("#divSubmit").html(" " + thrownError);
            }

        });
    });

    $.post("php/users/getUsers.php", function (data) {
        $("#userTable").html(data);
    });

    $('body').on("click", '[userid]', function () {
        var userid = $(this).attr("userid");

        $.ajax({
            type: "POST",
            url: "php/users/deleteUser.php",
            data: 'id=' + userid,
            processData: false,
            success: function (data) {
                $.post("php/users/getUsers.php", function (data) {
                    $("#userTable").html(data);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
                $("#errorMessage").fadeIn().removeClass('hidden');
                $("#errorMessage > p").text(thrownError);
            }
        });
    });

    $('#userForm').submit(function () {

        event.preventDefault();
        var f = $("#userForm");
        $.ajax({
            type: "POST",
            url: "php/users/createUser.php",
            data: f.serialize(),
            processData: false,
            success: function (data) {
                $(".form-control").val('');
                $("#errorMessage").fadeOut().addClass('hidden');
                $("#errorMessage > p").text('');
                $.post("php/users/getUsers.php", function (data) {
                    $("#userTable").html(data);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(".form-control").val('');
                $("#errorMessage").fadeIn().removeClass('hidden');
                $("#errorMessage > p").text(thrownError);
            }
        });
    });
});