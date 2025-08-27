@include('inc.navbar')
@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Suppliers Payment'))
	<section style="padding-left: 60px; padding-top: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-center mb-3">Suppliers Bill Payment</h3>
                <div style="padding-bottom: 20px;">
                 <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

</div>

<div class="text-center">
  <a href="/bills/create" class="btn btn-info btn-rounded mb-4">Create New Bill Payment</a>
</div>
<table border="0" cellspacing="5" cellpadding="5" style="padding-top: 45px">
                  <tbody><tr>
                        <td>Filter Data by Starting Date</td>
                          <td>From:</td>
                          <td><input type="text" id="min" name="min" class="min"></td>
                      
                          <td>To:</td>
                          <td><input type="text" id="max" name="max"></td>
                      </tr>
                  </tbody>
                </table>
                 </div>
                <table id="example" class="display" style="width:100%" style="padding-left: 60px">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Starting Date</th>
                <th>Ending Date</th>
                <th>Company</th>
                <th>Plate Number</th>
                <th>Days</th>
                <th>Destination</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Expenses</th>
                <!-- Adjusted Total removed per request -->
                <th>Advance</th>
                <th>Balance</th>
                @if(Auth::user()->role_id == 1)
                <th>action</th>
                @endif
            </tr>
        </thead>
        
        <tbody>

        	@foreach($roadmaps as $roadmap)
            <tr>
             <td>{{$roadmap->supplier}}</td>
             <td>{{ $roadmap->created_on }}</td>
             <td>{{ $roadmap->received_on }}</td>
            <td>{{ $roadmap->institution }}</td>
            <td>{{$roadmap->plate_number}}</td>
            
            <td>{{ $roadmap->ebm_number }}</td>
            <td>{{$roadmap->destination}}</td>
            <td>{{ number_format($roadmap->amount) }}</td>
            <td>{{ number_format($roadmap->ebm_number * $roadmap->amount) }}</td>
            <td>{{ number_format($roadmap->charge_amount ?? 0) }}</td>
            <!-- Balance reflects total minus expenses and advances -->
            <td>{{ number_format($roadmap->advance_cash) }}</td>
            <td>{{number_format((($roadmap->ebm_number * $roadmap->amount) - ($roadmap->charge_amount ?? 0)) - ($roadmap->advance_cash + $roadmap->advance_fuel))}}</td>
            @if(Auth::user()->role_id == 1)
            <td></td>
            @endif
            </tr>
         	@endforeach
        </tbody>
        	
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
              </div>  
            </div>    
          </div>      
        </div>        
      </div>
    </section>
       <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.8/js/mdb.min.js"></script>
    <script src="script.js"></script>
    <script src="{{asset('script.js')}}"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script> 
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script type="text/javascript">
  $(document).ready(function(){
      var supplierSearch = '';
      $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
          var min = $('.min').datepicker("getDate");
          var max = $('#max').datepicker("getDate");
          var startDate = new Date(data[1]);
          if (min == null && max == null) { return true; }
          if (min == null && startDate <= max) { return true;}
          if(max == null && startDate >= min) {return true;}
          if (startDate <= max && startDate >= min) { return true; }
          return false;
      });

      $("#min").datepicker({ 
        onSelect: function () { table.draw(); 
        }, 
        changeMonth: true, 
        changeYear: true,
        dateFormat: 'dd-mm-yy' 
      });
      $("#max").datepicker({ 
        onSelect: function () { table.draw(); 
        }, 
        changeMonth: true, 
        changeYear: true,
        dateFormat: 'dd-mm-yy'
      });
      
        $('#example thead tr').clone(true).appendTo( '#example thead' );
    $('#example thead tr:eq(0) th').each( function (i) {

        var title = $(this).text();
        $(this).html( '<input type="text" style="width:150px;" name="entries" placeholder=" '+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

      var table = $('#example').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                extend: 'excelHtml5',
                title: function () {
                    return 'Suppliers Bill Payment - Date: ' + new Date().toLocaleDateString();
                },
                messageTop: function () {
                    return 'Supplier: ' + (supplierSearch || 'All Suppliers');
                },
                footer: true,
                header: true,
                exportOptions: {
                    columns: ':not(:first-child)',  // Exclude first column
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: function () {
                    return 'Suppliers Bill Payment - Date: ' + new Date().toLocaleDateString();
                },
                messageTop: function () {
                    return 'Supplier: ' + (supplierSearch || 'All Suppliers');
                },
                footer: true,
                header: true,
                exportOptions: {
                    columns: ':not(:first-child)',  // Exclude first column
                    format: {
                        header: function (data, columnIdx) {
                            return $('#example').DataTable().column(columnIdx).header().textContent; // Retrieve the column header title dynamically
                        }
                    }
                }
            },
            {
                extend: 'csvHtml5',
                title: function () {
                    return 'Suppliers Bill Payment - Date: ' + new Date().toLocaleDateString();
                },
                messageTop: function () {
                    return 'Supplier: ' + (supplierSearch || 'All Suppliers');
                },
                footer: true,
                header: true,
                exportOptions: {
                    columns: ':not(:first-child)',  // Exclude first column
                    format: {
                        header: function (data, columnIdx) {
                            return $('#example').DataTable().column(columnIdx).header().textContent; // Retrieve the column header title dynamically
                        }
                    }
                }
            },
            
            {
                extend: 'print',
                title: function () {
                    return 'Suppliers Bill Payment - Date: ' + new Date().toLocaleDateString();
                },
                messageTop: function () {
                    return 'Supplier: ' + (supplierSearch || 'All Suppliers');
                },
                footer: true,
                header: true,
                exportOptions: {
                    columns: ':not(:first-child)',  // Exclude first column
                }
            }
        ],
        columnDefs: [
            {
                targets: '_all',
                defaultContent: '',
                className: 'dt-body-center'
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ? i : 0;
            };

            var formatNumber = function (num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };

            // List of column indexes for which to calculate totals
            // We want to show totals for Expenses (index 10) and Balance (index 12) instead of raw Total Price
            var columns = [10, 12]; // Expenses, Balance

            columns.forEach(function (column) {
                var total = api
                    .column(column)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var pageTotal = api
                    .column(column, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(column).footer()).html(formatNumber(pageTotal));
            });
        }
      });

      // Update "Supplier" title based on typed input in the first column
      $('#example thead tr:eq(0) th input').on('keyup change', function () {
          supplierSearch = this.value || '';
          $('#supplier-info').text('Supplier: ' + (supplierSearch || 'All Suppliers'));

          if (table.column(0).search() !== supplierSearch) {
              table.column(0).search(supplierSearch).draw();
          }
      });

      $('#min, #max').change(function () {
          table.draw();
      });
  });
</script>

    
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    @endif
@endsection