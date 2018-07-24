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

$(document).on('submit', '.object-meta-form', function (e) {
    e.preventDefault();
    var formData = $(this).serialize() ;
    $.ajax({
        type: "POST",
        url: "/admin/posts/add_meta",
        data: formData,
        dataType: 'json',
        // async: false,
        XMLHttpRequest:  true,
        success: function (json) {

            console.log(json);
            // $('#object-meta-table').append(html);
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

        // var fileType = e.target.files[0]["type"];
        // var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
        // if ($.inArray(fileType, ValidImageTypes) < 0) {
        //      // invalid file type code goes here.
        //      swal({
        //       icon:  'error',
        //       text: 'File must be image!' ,
        //       timer: 1300,
        //       buttons: false,
        //     });

        //    return;
        // }

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

                      $(previewer).html('<img class="img-thumbnail" src="'+ json.key +'"/>');

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