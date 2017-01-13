$(document).ready(function () {

    //gets delay via callback async
    getDelay(function (delay) {
        $("#divSubmit").html("Der Delay beträgt zurzeit " + delay / 1000 + " Sekunden.");
    });

    //form to set delay handling
    $('#delayForm').submit(function (event) {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $('#delayForm');
        $.ajax({
            type: "POST",
            url: "php/users/setDelay.php",
            data: f.serialize(),
            processData: false,
            success: function (data) {
                $("#delayForm")[0].reset();
                $("#divSubmit").html("Der Delay beträgt jetzt " + data + " Sekunden.");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#delayForm")[0].reset();
                $("#divSubmit").html(" " + thrownError);
            }
        });
    });

    //gets user table from SQL
    $.post("php/users/getUsers.php", function (data) {
        $("#userTable").html(data);
    });

    //listener listens to all elements which have userid attributes (delete user)
    //deletes user with id which was clicked
    $('body').on("click", '[userid]', function () {
        const userid = $(this).attr("userid");

        $.ajax({
            type: "POST",
            url: "php/users/deleteUser.php",
            data: 'id=' + userid,
            processData: false,
            success: function () {
                $.post("php/users/getUsers.php", function (data) {
                    $("#userTable").html(data);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
                $("#alertUserForm").fadeIn().removeClass('hidden')
                    .find("> p").text(thrownError);
            }
        });
    });

    //form to create users handling
    $('#userForm').submit(function () {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#userForm");
        $.ajax({
            type: "POST",
            url: "php/users/createUser.php",
            data: f.serialize(),
            processData: false,
            success: function () {
                $("#userForm")[0].reset();
                $("#alertUserForm").fadeOut().addClass('hidden')
                    .find("> p").text('');
                $.post("php/users/getUsers.php", function (data) {
                    $("#userTable").html(data);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#userForm")[0].reset();
                $("#alertUserForm").fadeIn().removeClass('hidden')
                    .find("> p").text(thrownError);
            }
        });
    });

    //form to change password handling
    $('#changePwdForm').submit(function () {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#changePwdForm");
        $.ajax({
            type: "POST",
            url: "php/users/changePassword.php",
            data: f.serialize(),
            processData: false,
            success: function (data) {
                $("#changePwdForm")[0].reset();
                $("#alertChangePwdForm").fadeIn(300).removeClass('hidden alert-danger').addClass('alert-success')
                    .find("> p").text(data);
                setTimeout(function () {
                    $("#alertChangePwdForm").fadeOut(300).addClass('hidden').removeClass('alert-success')
                        .find("> p").text('');
                }, 5000);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#changePwdForm")[0].reset();
                $("#alertChangePwdForm").fadeIn().removeClass('hidden').addClass('alert-danger')
                    .find("> p").text(thrownError);
            }
        });
    });
});