$(document).on('submit', '#form-grid', function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: baseUri + controller + '/' + 'grid',
        data: $(this).serialize() + '&filter=1',
        dataType: 'html',
        // async: false,
        XMLHttpRequest:  true,
        success: function (html) {

            // console.log(html);
            $('#grid').replaceWith(html);
            $(".chosen-select").length && $(".chosen-select").chosen({disable_search_threshold: 10});
        }
    });
});

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    var row =  $(this).closest('tr');
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
            //console.log(status);
        },
        complete: function( xhr, status ) {
           //console.log(xhr);
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
        "bLengthChange": false 
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
} );