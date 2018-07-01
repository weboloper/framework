//some global ajax event listeners
$(document).ajaxStart(function () {
    $('body').append('<div class="backdrop fade in"><h1 class="text-center">Loading...</h1></div>');
});

$(document).ajaxComplete(function (event, xhr, settings) {
    $('.backdrop').remove();
    if (xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty('messages')) {
        for (var key in xhr.responseJSON.messages) {
             //$.growl[xhr.responseJSON.messages[key].type]({title: '', message: xhr.responseJSON.messages[key].content });

            swal({
              // title: xhr.responseJSON.messages[key].type,
              icon:   xhr.responseJSON.messages[key].type,
              text: xhr.responseJSON.messages[key].content ,
              timer: 1000,
              buttons: false,
            });

            // $.notify({
            //     // options
            //     message: xhr.responseJSON.messages[key].content 
            // },{
            //     // settings
            //     type:  xhr.responseJSON.messages[key].type,
            //     // showProgressbar: true,
            //     delay: 3000,
            //     // icon_type: 'image',
            //     // template: '<div class="alert alert-{0}" role="alert">' +
            //     //     ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> ' +
            //     //     '<span class="alert-heading">{1}</span>' +
            //     //     '<span data-notify="message">{2} </span> ' +
            //     // '</div>'
            // });
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