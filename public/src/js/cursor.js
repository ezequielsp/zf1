$(document).ajaxStart(function () {
    $(document.body).css({ 'cursor': 'wait' })
});

$(document).ajaxStop(function () {
    $(document.body).css({ 'cursor': 'default' })
});
