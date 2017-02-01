//ajax predefinition
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