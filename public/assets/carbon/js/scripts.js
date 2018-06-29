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


    

} );