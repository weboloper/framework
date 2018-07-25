//some global ajax event listeners
$(document).ajaxStart(function () {
    $('body').append('<div class="backdrop fade in"><h1 class="text-center">Loading...</h1></div>');
});

$(document).ajaxComplete(function (event, xhr, settings) {
    $('.backdrop').remove();
    if (xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty('messages')) {
        for (var key in xhr.responseJSON.messages) {

            swal({
              // title: xhr.responseJSON.messages[key].type,
              icon:   xhr.responseJSON.messages[key].type,
              text: xhr.responseJSON.messages[key].content ,
              timer: 1300,
              buttons: false,
              animation: false
            });
 
        }
    }
});

$(document).ajaxError(function () {
    $('.backdrop').remove();
});

$(document).ajaxSuccess(function () {
    $('.backdrop').remove();
});

$(document).ajaxStop(function () {
    $('.backdrop').remove();
});
