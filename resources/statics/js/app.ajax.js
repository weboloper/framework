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


$(document).on('change', '.uploader-wrapper .uploader-input', function (e) {

    var previewer = $(this).parent().find('.uploader-preview');
    var objectId  = $(this).parent().find('.uploader-object-id').val();
    var objectType  = $(this).parent().find('.uploader-object-type').val();

    if(e.target.files[0].size > 2000000){
       swal({
          icon:  'error',
          text: 'File is too big! Max File size: 2MB' ,
          timer: 1300,
          buttons: false,
        });

       return;
    };

    var fileType = e.target.files[0]["type"];
    var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, ValidImageTypes) < 0) {
         // invalid file type code goes here.
         swal({
          icon:  'error',
          text: 'File must be image!' ,
          timer: 1300,
          buttons: false,
        });

       return;
    }

    var myformData = new FormData();        
    myformData.append('file',  e.target.files[0]  );

      $.ajax({
              url: "/media/upload?accept=image",
              type: "POST",
              data: myformData,
              processData: false,
              contentType: false,
              cache: false,
              enctype: 'multipart/form-data',
              // async: false,
              success: function (json) {
                   // json = JSON.parse(data);

            
                  if (!json || typeof json.key != 'string') {
                      // failure('Invalid JSON: ' + data);
                      console.log('Invalid JSON: ' + json);
                      return;
                  }

                  $(previewer).html('<img class="img-thumbnail" src="'+ json.key +'"/> <a href="#" class="btn btn-danger uploader-delete" data-id="'+ objectId +' data-type="'+ objectType +'" ><i class="fas fa-trash"></i></a>');

                  $.ajax({
                        type: "POST",
                        url: "/media/add_meta",
                        data: { meta_key : 'thumbnail' , meta_value : json.key , objectId : objectId , objectType :  objectType }  ,
                        // async: false,
                        XMLHttpRequest:  true,
                        success: function (json) {
                            console.log( json )
                        }
                    });


              },
              error: function (data) {
                  console.log(data);
                  return data;
              }

          });

} );



$(document).on('click', '.uploader-wrapper .uploader-delete', function (e) {
   e.preventDefault();
   var id = $(this).data('id');
   var type = $(this).data('type');
   var previewer = $(this).parent().find('.uploader-preview');

   $(".uploader-preview").empty();
   $.ajax({
          url: "/media/delete_thumbnail",
          type: "POST",
          data: {  objectId : id , objectType :  type },
          dataType: 'json',
          success: function (json) {
            console.log(json);
             $(previewer).html("Hello World");
          },
          error: function (data) {
              console.log(data);
              return data;
          }

      });

});
    
