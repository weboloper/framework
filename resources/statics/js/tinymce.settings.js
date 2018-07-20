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

        // image_list: [
        //   {title: 'My image 1', value: '/uploads/2018/07/00.jpg'},
        // ],
        // media_list: [
        //   {title: 'My image 1', value: '/uploads/2018/07/00.jpg'},
        // ],

        // external_image_list_url : "/media/list.js",

        // file_browser_callback: function(field_name, url, type, win) {

        //   tinymce.activeEditor.windowManager.open({
        //       title: 'Browse Media',
        //       file: "/admin/posts?type=attachment&mime_type=" + type,
        //       width: 450,
        //       height: 305,
        //       resizable : "no",
        //       inline : "yes",
        //       close_previous : "no",
        //       buttons: [{
        //           text: 'Insert',
        //           classes: 'widget btn primary first abs-layout-item',
        //           disabled: true,
        //           onclick: 'close'
        //       }, {
        //           text: 'Close',
        //           onclick: 'close',
        //           window : win,
        //           input : field_name
        //       }]
        //   });

        //   return false;
        // },


        file_picker_callback2 : function(callback, value, meta) {
          imageFilePicker(callback, value, meta);
        },


        file_picker_types: 'file media image',
        file_picker_callback: function(cb, value, meta) {

          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          // input.setAttribute('accept', 'image/*');
          // console.log(meta.filetype);
          input.onchange = function() {

            var _file = this.files[0];

            // console.log(_file);

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

                      // console.log(json);
                      // console.log(meta);
            
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

        video_template_callback: function (data) {
            console.log(data);
            if(data.source1mime == ""){
              return '<embed src="https://docs.google.com/viewerng/viewer?hl=tr&embedded=true&url=' + data.source1 + '" width="100%" height="600px" allowFullscreen="1"></embed>';
            }
            return '<video width="' + data.width + '" height="' + data.height + '"' + (data.poster ? ' poster="' + data.poster + '"' : '') + ' controls="controls">\n' + '<source src="' + data.source1 + '"' + (data.source1mime ? ' type="' + data.source1mime + '"' : '') + ' />\n' + (data.source2 ? '<source src="' + data.source2 + '"' + (data.source2mime ? ' type="' + data.source2mime + '"' : '') + ' />\n' : '') + '</video>';

        },

 
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


 
   

      var imageFilePicker = function (callback, value, meta) {               
        tinymce.activeEditor.windowManager.open({
            title: 'Image Picker',
            // url: '/media/upload',
            url: '/media/browser?type=attachment',
            width: 650,
            height: 550,
            buttons: [{
                text: 'Insert',
                onclick: function () {
                    //.. do some work
                    var xx = $( ".selected" ).data("id")  ;
                    console.log(xx);
                    // tinymce.activeEditor.windowManager.close();
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