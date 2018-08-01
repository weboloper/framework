$(document).ready(function () {
    /**
     * Sidebar Dropdown
     */
    $('.nav-dropdown-toggle').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });

    // open sub-menu when an item is active.
    $('ul.nav').find('a.active').parent().parent().parent().addClass('open');

    /**
     * Sidebar Toggle
     */
    $('.sidebar-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-hidden');
    });

    /**
     * Mobile Sidebar Toggle
     */
    $('.sidebar-mobile-toggle').on('click', function () {
        $('body').toggleClass('sidebar-mobile-show');
    });
});


$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    var row =  $(this).closest('tr');

    swal({
      title: "Are you sure?",
      text: "Object will be sent to trash!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
      animation: false
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
            console.log(data);
            // row.addClass('table-danger');
            // setTimeout(function(){  row.remove() ; }, 500);
            
        },
        error: function( xhr, status ) {
            console.log(xhr);
        } 
    });
});

function add_meta(formData, row , textArea ){
    $.ajax({
        type: "POST",
        url: "/admin/posts/add_meta",
        data: formData,
        dataType: 'json',
        // async: false,
        XMLHttpRequest:  true,
        success: function (json) {
            // json returns class
            console.log(json);
            // $('#object-meta-table').append(html);
            if (!json || typeof json.meta_id != 'string') {
 
              textArea.val('');
              row.addClass( 'table-danger' );
              setTimeout(function() {
                  row.removeClass( 'table-danger'  );
              }, 600);
              return;
            }

            
            row.addClass( 'table-success' );
            setTimeout(function() {
                row.removeClass( 'table-success'  );
            }, 600);
         }
    });
}
$(document).on('click', '.add_meta_btn', function (e) {
    e.preventDefault();
    var btn = $(this).val();
    var formId = $(this).attr('form');
    var row = $(this).parents('tr:first');
    var textArea = $(row).find('textarea');
    var form =  $('#' + formId);
    var formData = $(form).serialize() + "&btn_clicked=" + btn;
    add_meta(formData, row , textArea );
});



var buttonpressed;
$('input[type=submit]').click(function() {
    buttonpressed = $(this).val();
});
$(document).on('submit', '.object-meta-form-deprecated', function (e) {
    e.preventDefault();
    var row =  $(this).find('tr');
    var formData = $(this).serialize() +"&buttonpressed="+ buttonpressed;
    $.ajax({
        type: "POST",
        url: "/admin/posts/add_meta",
        data: formData,
        dataType: 'json',
        // async: false,
        XMLHttpRequest:  true,
        success: function (json) {
            // json returns class
            console.log(json);
            // $('#object-meta-table').append(html);
            row.addClass( "table-success" );
            setTimeout(function() {
                row.removeClass( json );
            }, 1100);
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
        // "bLengthChange": false ,
        // "searching": false ,
        "order": [[ 0, "desc" ]],
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
      console.log('did');
        // if is checked
        if($(this).is(':checked')){

            $(this).parents('.form-check').each(function() {
                $(this).children('input').prop('checked', true);
            });

        } else {

            // uncheck all children
            $(this).parent().find('.form-check input[type=checkbox]').prop('checked', false);

        }

    });

 

    $('input.slug-in').keyup(function () {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        str = string_to_slug(str);

        // Remove all non alpha-nums from str and store it back in the str variable
        // str = str.replace(/[^a-zA-Z0-9]+/g, '-');
        // Get the user input from "this" (yes, again) and put it in the txtClone variable
        var txtClone = $(this).val();
        // Set your other textbox to be the value in txtClone
        $('input.slug-out').val(str);
    });


   


$(document).on('click', '.uploader-wrapper .uploader-delete', function (e) {
   e.preventDefault();
   var id = $(this).data('id');
   var previewer = $(this).parent().find('.uploader-preview');

   $(".uploader-preview").empty();
   $.ajax({
          url: "/admin/posts/" + id + "/delete_thumbnail",
          type: "POST",
          dataType: 'json',
          success: function (json) {
   $(previewer).html("Hello World");
          },
          error: function (data) {
              console.log(data);
              return data;
          }

      });

});
    

$(document).on('change', '.uploader-wrapper .uploader-input', function (e) {

        var previewer = $(this).parent().find('.uploader-preview');
        var objectId  = $(this).parent().find('.uploader-object-id').val();

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

                      $(previewer).html('<img class="img-thumbnail" src="'+ json.key +'"/> <a href="#" class="btn btn-danger uploader-delete" data-id="'+ objectId +'"><i class="fas fa-trash"></i></a>');

                      $.ajax({
                            type: "POST",
                            url: "/admin/posts/add_meta",
                            data: { meta_key : 'thumbnail' , meta_value : json.key , objectId : objectId , objectType : 'post' }  ,
                            // async: false,
                            XMLHttpRequest:  true,
                            success: function (json) {
                                console.log('done!')
                            }
                        });

 
                  },
                  error: function (data) {
                      console.log(data);
                      return data;
                  }

              });
      
          } );

 

} );