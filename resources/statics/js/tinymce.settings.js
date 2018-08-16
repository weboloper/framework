$(document).ready(function() {
  
 

  tinymce.init({

        selector: ".wysiwyg-full",theme: "modern", height: 400,
        branding: false,
        auto_focus: 'editable',
        plugins: [
           "advlist autolink link image lists charmap print preview hr anchor pagebreak",
           "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
           "table contextmenu directionality emoticons paste textcolor code select_uploaded "
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "select_uploaded  image  media |  link unlink | forecolor backcolor  | print preview code ",
 
        // image handling
        images_upload_url_iptal: '/media/upload',
        images_upload_handler_iptal: function (blobInfo, success, failure) {

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
 

        
       
 
        file_picker_type_: 'file media image',
        file_picker_callback_: function(cb, value, meta) {

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

        file_browser_callback: function(field_name, url, type, win) {
          myFileBrowser(field_name, url, type, win);
        },

        
        



        video_template_callback: function (data) {
            console.log(data);
            if(data.source1mime == ""){
              return '<embed src="https://docs.google.com/viewerng/viewer?hl=tr&embedded=true&url=' + data.source1 + '" width="100%" height="600px" allowFullscreen="1"></embed>';
            }
            return '<video width="' + data.width + '" height="' + data.height + '"' + (data.poster ? ' poster="' + data.poster + '"' : '') + ' controls="controls">\n' + '<source src="' + data.source1 + '"' + (data.source1mime ? ' type="' + data.source1mime + '"' : '') + ' />\n' + (data.source2 ? '<source src="' + data.source2 + '"' + (data.source2mime ? ' type="' + data.source2mime + '"' : '') + ' />\n' : '') + '</video>';

        },

 
    });
 

 

    function myFileBrowser (field_name, url, type, win) {

        // alert("Field_Name: " + field_name + "nURL: " + url + "nType: " + type + "nWin: " + win); // debug/testing

        /* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
           the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
           These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */

        // var cmsURL = window.location.toString();    // script URL - use an absolute path!

        var cmsURL = '/media/browser';
        if (cmsURL.indexOf("?") < 0) {
            //add the type as the only query parameter
            cmsURL = cmsURL + "?type=" + type;
        }
        else {
            //add the type as an additional query parameter
            // (PHP session ID is now included if there is one at all)
            cmsURL = cmsURL + "&type=" + type;
        }

        tinyMCE.activeEditor.windowManager.open({
            file : cmsURL,
            title : 'My File Browser',
            width : 600,  // Your dimensions may differ - toy around with them!
            height : 500,
            resizable : true,
            inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
            close_previous : "no",
            buttons: [{
                text: 'Select',
                onclick: function () {
                    //.. do some work
                    tinymce.activeEditor.windowManager.close();
                   
                }
            }, {
                text: 'Close',
                onclick: 'close'
            }],

        }, {
            window : win,
            input : field_name
        });
        return false;
    }



    tinymce.PluginManager.add('select_uploaded',function(editor,url){

          var cmsURL = '/media/browser';
          var type= 'all' ;
          if (cmsURL.indexOf("?") < 0) {
              //add the type as the only query parameter
              cmsURL = cmsURL + "?type=" + type;
          }
          else {
              //add the type as an additional query parameter
              // (PHP session ID is now included if there is one at all)
              cmsURL = cmsURL + "&type=" + type;
          }

          editor.addButton('select_uploaded',{
            title: 'Select from Library',
            text: 'Select from Library',
            icon: 'media',
            onclick: function(){
              var win =  editor.windowManager.open({
                  file : cmsURL,
                  id: 'customBrowser',
                  title : 'Media browser',
                  width : 600,  // Your dimensions may differ - toy around with them!
                  height : 500,
                  resizable : true,
                  inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
                  close_previous : "no",
                  buttons: [{
                      text: 'Select',
                      onclick: function () {
 

                          item = $('#customBrowser iframe').contents().find('.media-item.selected');
                          item_url = item.data("src");

                          var extension = item_url.substr( (item_url.lastIndexOf('.') +1) );

                          switch(extension) {
                              case 'jpg':
                              case 'png':
                              case 'gif':
                              case 'jpeg':
                                 var template = '<img src="'+ item_url +'">';
                              break;                         // the alert ended with pdf instead of gif.
                              case 'mp4':
                                var template = '<video width="300" height="150"  controls="controls">\n' + '<source src="' + item_url + '" />\n <source src="' + item_url + '"   </video>';
                              break;
                              default:
                                var template =  '<embed src="https://docs.google.com/viewerng/viewer?hl=tr&embedded=true&url=' + item_url + '" width="100%" height="600px" allowFullscreen="1"></embed>';
                          }

                          editor.insertContent( template );
                          editor.windowManager.close();
                         
                      }
                  }, {
                      text: 'Close',
                      onclick: 'close'
                  }],

              });
            },
            onsubmit: function(e){
              console.dir(e);
            }
          });
        });



 
} );