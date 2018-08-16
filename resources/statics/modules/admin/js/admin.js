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


 

function add_meta(formData, row , textArea ){
    $.ajax({
        type: "POST",
        url: "/media/add_meta",
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



} );