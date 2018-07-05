// $(document).on('submit', '#form-grid', function (e) {
//     e.preventDefault();
//     $.ajax({
//         type: "POST",
//         url: baseUri + controller + '/' + 'grid',
//         data: $(this).serialize() + '&filter=1',
//         dataType: 'html',
//         // async: false,
//         XMLHttpRequest:  true,
//         success: function (html) {

//             // console.log(html);
//             $('#grid').replaceWith(html);
//             $(".chosen-select").length && $(".chosen-select").chosen({disable_search_threshold: 10});
//         }
//     });
// });

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    var row =  $(this).closest('tr');

    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this object!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        $.ajax({
            type: "POST",
            url: baseUri + controller + '/' + $(this).data('id') + '/' + 'delete',
            dataType: 'json',
            // async: false,
            XMLHttpRequest:  true,
            success: function (data  ) {
                console.log(data);
                row.addClass('table-danger');
                setTimeout(function(){  row.remove() ; }, 500);
                
            },
            error: function( xhr, status ) {
                // console.log(status);
            },
            complete: function( xhr, status ) {
               // console.log(xhr);
            }
        });

        // swal("Poof! Your imaginary file has been deleted!", {
        //   icon: "success",
        //    timer: 1000
        // });
      } else {
        // swal("Your imaginary file is safe!");
      }
    });

    
});


/// meta attributes
$(document).on('click', '.delete-meta-btn', function (e) {
    e.preventDefault();
    console.log(  $(this).data('object-id') );
    var row =  $(this).closest('tr');
    $.ajax({
        type: "POST",
        url: baseUri + controller + '/delete_meta',
        dataType: 'json',
        // async: false,
        data: {
            'object-id': $(this).data('object-id'),
            'object' : $(this).data('object')
        },
        XMLHttpRequest:  true,
        success: function (data) {
            row.addClass('table-danger');
            setTimeout(function(){  row.remove() ; }, 500);
            
        },
        error: function( xhr, status ) {
            console.log(xhr);
        },
        complete: function( xhr, status ) {
           console.log(xhr);
        }
    });
});

$(document).on('submit', '#object-meta-form', function (e) {
    e.preventDefault();
    console.log($(this).serialize());
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'html',
        // async: false,
        XMLHttpRequest:  true,
        success: function (html) {

            // console.log(html);
            $('#object-meta-table').append(html);
         }
    });
});


 $(document).ready(function() {
     
    $('.dashboard-table thead:first th').each( function () {
        // var title = $('#posts thead th').eq( $(this).index() ).text();
        var title =  $(this).text();
        if(title) {
             $('.dashboard-table  thead.searchfilter').append( '<th><input class="form-control" type="text" placeholder="Search '+title+'" /></th>' );
         }  else {
            $('.dashboard-table  thead.searchfilter').append( '<th></th>' );
         }
    } );

 

    // DataTable
    var table = $('.dashboard-table ').DataTable( {
        colReorder: true,
        "bLengthChange": false ,
        "order": [[ 0, "desc" ]]
    } );
    $( table.table().container() )
    .removeClass( 'container-fluid' );
    // Apply the filter
    $(".dashboard-table  .searchfilter input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );


    $('.select2-select').select2();
    $('.select2-select.tagging-true').select2({
        tags: true,
        tokenSeparators: [',', ' ']
    });

    $('input[type=checkbox]').click(function(){

        // if is checked
        if($(this).is(':checked')){

            $(this).parents('li').each(function() {
                $(this).children('input').prop('checked', true);
            });

        } else {

            // uncheck all children
            $(this).parent().find('li input[type=checkbox]').prop('checked', false);

        }

    });


    function string_to_slug(str) {
      str = str.replace(/^\s+|\s+$/g, ""); // trim
      str = str.toLowerCase();

      // remove accents, swap ñ for n, etc
      var from = "çöışüğ";
      var to = "coisug";

      for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
      }

      str = str
        .replace(/[^a-z0-9 -]/g, "-") // remove invalid chars
        .replace(/\s+/g, "-") // collapse whitespace and replace by -
        .replace(/-+/g, "-"); // collapse dashes

      return str;
    }

    $('input.slug-in').keyup(function () {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        str = string_to_slug(str);

        // Remove all non alpha-nums from str and store it back in the str variable
        str = str.replace(/[^a-zA-Z0-9]+/g, '-');
        // Get the user input from "this" (yes, again) and put it in the txtClone variable
        var txtClone = $(this).val();
        // Set your other textbox to be the value in txtClone
        $('input.slug-out').val(str);
    });


    tinymce.init({

        selector: ".wysiwyg-file",theme: "modern", height: 400,
        branding: false,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
             "table contextmenu directionality emoticons paste textcolor  code responsivefilemanager"
       ],
       toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
       toolbar2: " responsivefilemanager image media file |  link unlink | forecolor backcolor  | print preview code ",
        
       external_filemanager_path:"/filemanager/",
       filemanager_title:"Responsive Filemanager" ,
       external_plugins: { "filemanager" : "/filemanager/plugin.min.js"},

        // menubar: "insert",
        // plugins : 'media code image', 
        // toolbar : 'media code image',
        images_upload_url: '/media/upload',

        file_picker_types: 'file media ',
        file_picker_callback: function(cb, value, meta) {

          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          // input.setAttribute('accept', 'image/*');

          input.onchange = function() {

            var _file = this.files[0];
            
            var myformData = new FormData();        
            myformData.append('file',  _file );
 

            $.ajax({
                  url: "/media/upload",
                  type: "POST",
                  data: myformData,
                  processData: false,
                  contentType: false,
                  cache: false,
                  enctype: 'multipart/form-data',
                  // async: false,
                  success: function (data) {

                      json = JSON.parse(data);
            
                      if (!json || typeof json.location != 'string') {
                          failure('Invalid JSON: ' + data);
                          return;
                      }
 
                      cb( json.location , { title: _file.name });
                  },
                  error: function (data) {
                      // console.log(data);
                      return data;
                  }

              });
      
          };
          
          input.click();
           
        },

    });


















    tinymce.init({

        selector: '.wysiwyg',
        plugins : 'code image media', 
        toolbar : 'undo redo | image code',
        // menubar: 'file edit insert view format table tools help',
        // menubar: false,


        images_upload_url: '/media/upload',
        
        // override default upload handler to simulate successful upload
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
          
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/media/upload');
          
            xhr.onload = function() {
                var json;
            
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
            
                json = JSON.parse(xhr.responseText);
            
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
            
                success(json.location);
            };
          
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
          
            xhr.send(formData);
        },

    });

    tinymce.init({
        selector: ".wysiwyg-tinymce",theme: "modern", height: 400,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
             "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
       ],
       toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
       toolbar2: "| responsivefilemanager | link unlink | image media | forecolor backcolor  | print preview code ",
       image_advtab: true ,
       
       external_filemanager_path:"/filemanager/",
       filemanager_title:"Responsive Filemanager" ,
       external_plugins: { "filemanager" : "/filemanager/plugin.min.js"},
     });
    


      var imageFilePicker = function (callback, value, meta) {               
        tinymce.activeEditor.windowManager.open({
            title: 'Image Picker',
            // url: '/media/upload',
            url: '/filemanager/dialog.php',
            width: 650,
            height: 550,
            buttons: [{
                text: 'Insert',
                onclick: function () {
                    //.. do some work
                    tinymce.activeEditor.windowManager.close();
                }
            }, {
                text: 'Close',
                onclick: 'close'
            }],
        }, {
            oninsert: function (url) {
                callback(url);
                console.log("derp");
            },
        });
    };



    tinymce.init({
        selector: '.wysiwyg-all',
        plugins : 'code image media', 
        toolbar : 'undo redo | image code media',
        // menubar: 'file edit insert view format table tools help',
        // menubar: false,

        // images_
        upload_url: '/media/upload',

        file_picker_types: 'file image media',
        // file_browser_callback_types: 'file image media',
        // file_browser_callback: function(field_name, url, type, win) {
        //   win.document.getElementById(field_name).value = 'my browser value';
        // },
        file_picker_callback: function(callback, value, meta) {
          imageFilePicker(callback, value, meta);
        },
        file_picker_callback: function(cb, value, meta) {

          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          // input.setAttribute('accept', 'image/*');

          input.onchange = function() {
            var file = this.files[0];
            
            var reader = new FileReader();
            reader.onload = function () {
              console.log(reader);
              // Note: Now we need to register the blob in TinyMCEs image blob
              // registry. In the next release this part hopefully won't be
              // necessary, as we are looking to handle it internally.
              var id = 'blobid' + (new Date()).getTime();
              var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
              var base64 = reader.result.split(',')[1];
              var blobInfo = blobCache.create(id, file, base64);
              blobCache.add(blobInfo);

              // call the callback and populate the Title field with the file name
              cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
          };
          
          input.click();


          // console.log(meta);
          // // Provide file and text for the link dialog
          // if (meta.filetype == 'file') {
          //   callback('mypage.html', {text: 'My text'});
          // }

          // // Provide image and alt text for the image dialog
          // if (meta.filetype == 'image') {
          //   callback('myimage.jpg', {alt: 'My alt text'});
          // }

          // // Provide alternative source and posted for the media dialog
          // if (meta.filetype == 'media') {
          //   callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
          // }

        }

    });



} );