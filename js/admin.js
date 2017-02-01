//ajax prefinition
function userAction(options) {
    $.ajax({
        type: "POST",
        url: "php/userActionHandler.php",
        data: options.data,
        processData: false,
        success: options.success,
        error: options.error
    });
}
$(document).ready(function () {

    //gets user table from SQL
    function writeUserTable(){
        userAction({
            data: "action=getUsers",
            success: function (data) {
                $("#userTable").html(data);
            }
        });
    }
    writeUserTable();

    //gets delay via callback async
    getDelay(function (delay) {
        $("#divSubmit").html("Der Delay beträgt zurzeit " + delay / 1000 + " Sekunden.");
    });

    //form to set delay handling
    $('#delayForm').submit(function (event) {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $('#delayForm');
        userAction({
            data: f.serialize() + "&action=setDelay",
            success: function (data) {
                f[0].reset();
                $("#divSubmit").html("Der Delay beträgt jetzt " + data + " Sekunden.");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                f[0].reset();
                $("#divSubmit").html(" " + xhr.responseText);
            }
        });
    });



    //listener listens to all elements which have userid attributes (delete user)
    //deletes user with id which was clicked
    $('body').on("click", '[userid]', function () {
        const userid = $(this).attr("userid");

        userAction({
            data: 'id=' + userid + "&action=deleteUser",
            success: function () {
                writeUserTable();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                $("#alertChangeUserForm").fadeIn().removeClass('hidden')
                    .find("> p").text(xhr.responseText);
            }
        });
    });

    //form to create users handling
    $('#createUser').submit(function () {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#createUser");
        userAction({
            data: f.serialize() + "&action=" + f.attr('id'),
            success: function () {
                f[0].reset();
                $("#alertChangeUserForm").fadeOut().addClass('hidden')
                    .find("> p").text('');
                writeUserTable();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                f[0].reset();
                $("#alertChangeUserForm").fadeIn().removeClass('hidden')
                    .find("> p").text(xhr.responseText);
            }
        });
    });

    //form to change password handling
    $('#changePassword').submit(function () {
        //stop page from reloading after submit
        event.preventDefault();
        const f = $("#changePassword");
        userAction({
            data: f.serialize() + "&action=" + f.attr('id'),
            success: function (data) {
                f[0].reset();
                $("#alertChangePwdForm").fadeIn(300).removeClass('hidden alert-danger').addClass('alert-success')
                    .find("> p").text(data);
                setTimeout(function () {
                    $("#alertChangePwdForm").fadeOut(300).addClass('hidden').removeClass('alert-success')
                        .find("> p").text('');
                }, 5000);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                f[0].reset();
                $("#alertChangePwdForm").fadeIn().removeClass('hidden').addClass('alert-danger')
                    .find("> p").text(xhr.responseText);
            }
        });
    });
});