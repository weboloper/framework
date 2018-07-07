$(document).ready(function() {
  
 

  tinymce.init({

        selector: ".wysiwyg-full",theme: "modern", height: 400,
        branding: false,
        plugins: [
           "advlist autolink link image lists charmap print preview hr anchor pagebreak",
           "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
           "table contextmenu directionality emoticons paste textcolor  code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "image media file |  link unlink | forecolor backcolor  | print preview code ",

 
        // image handling
        images_upload_url: '/media/upload',
        images_upload_handler: function (blobInfo, success, failure) {

          var xhr, formData;
          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', '/media/upload');
          xhr.onload = function() {

          json = JSON.parse(xhr.responseText);

          if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
          }

          if (!json || typeof json.key != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
          }
          success(json.key);
          };
          formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          xhr.send(formData);

        },
        automatic_uploads: false,
        image_list: [
          {title: 'My image 1', value: '/uploads/2018/07/00.jpg'},
        ],
        media_list: [
          {title: 'My image 1', value: '/uploads/2018/07/00.jpg'},
        ],

        // external_image_list_url : "/media/list.js",

        file_picker_types: 'file media ',
        file_picker_callback: function(cb, value, meta) {

          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          // input.setAttribute('accept', 'image/*');
          // console.log(meta.filetype);
          input.onchange = function() {

            var _file = this.files[0];

            console.log(_file);

            if(_file.size > 2000000){
               // alert("File is too big! Max File size: 2MB");
               // Change php ini if you want to increase
               swal({
                  // title: xhr.responseJSON.messages[key].type,
                  icon:  'error',
                  text: 'File is too big! Max File size: 2MB' ,
                  timer: 1300,
                  buttons: false,
                });

               return;
            };


            // console.log(_file);
            
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
                  success: function (json) {
                       // json = JSON.parse(data);

                      console.log(json);
            
                      if (!json || typeof json.key != 'string') {
                          // failure('Invalid JSON: ' + data);
                          console.log('Invalid JSON: ' + json);
                          return;
                      }
 
                      cb( json.key , { alt: json.title });
                  },
                  error: function (data) {
                      console.log(data);
                      return data;
                  }

              });
      
          };
          
          input.click();
           
        },

 
        // media_url_resolver: function (data, resolve/*, reject*/) {
          // console.log(data);
          // if (data.url.indexOf('YOUR_SPECIAL_VIDEO_URL') !== -1) {
          //   var embedHtml = '<iframe src="' + data.url +
          //   '" width="400" height="400" ></iframe>';
          //   resolve({html: embedHtml});
          // } else {
          //   resolve({html: ''});
          // }
        // }


    });

    tinymce.PluginManager.add('test', function(editor, url) {
        editor.addButton('test', {
            text: 'Test',
            icon: true,
            onclick: function() {
                editor.insertContent('This is inserted');
            }
        });
    });






    tinymce.init({

        selector: '.wysiwyg-image-only',
        plugins : 'code image', 
        toolbar : 'undo redo | image code',

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
        selector: ".wysiwyg-responsivefilemanager",theme: "modern", height: 400,
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



 
} );