@include('inc.navbar')
@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Invoices'))
	<section style="padding-left: 60px; padding-top: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-center mb-3">Payments</h3>
                <div style="padding-bottom: 20px;">
                 <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

</div>

<div class="text-center">
  <a href="/payments/create" class="btn btn-info btn-rounded mb-4">Create New Payment</a>
</div>
<table border="0" cellspacing="5" cellpadding="5" style="padding-top: 45px">
                  <tbody><tr>
                        <td>Filter Data by Payment Date</td>
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
                <th class="text-center">Voucher Number</th>
                <th class="text-center">Invoice</th>
                <th class="text-center">Contractor</th>
                <th class="text-center">Institution</th>
                <th class="text-center">Payment Date</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Attachments</th>
                @if(Auth::user()->id == 1)
                <th class="text-center">Inserted By</th>
                <th class="text-center">action</th>
                @endif
            </tr>
        </thead>
        
        <tbody>
          @foreach($payments as $payment)
          <tr>
            <td class="text-center">{{ $payment->voucherNo }}</td>
            <td class="text-center">{{ $payment->invoiceNumber }}</td>
            <td class="text-center">{{ $payment->name }}</td>
            <td class="text-center">{{ $payment->institution }}</td>
            <td class="text-center">{{ $payment->payment_date }}</td>
            <td class="text-center">{{ number_format($payment->amounts) }}</td>
            <td class="text-center">
                <a href="/payments/{{$payment->id}}">
                <i class="fas fa-eye" style="padding-right: 4px; width: 20px"></i>View Attached Files
            </a>
            </td>
            @if(Auth::user()->id == 1)
            <td class="text-center">{{ $payment->username }}</td>
            <td class="text-left pl-4"><a href="/payments/{{$payment->id}}"><i class="fas fa-eye" style="padding-right: 4px;"></i></a><a href="/payments/{{$payment->id}}/edit"><i class="fas fa-edit text-success" style="padding-left: 4px;"></i></a></td>@endif
          </tr>
        	@endforeach
        </tbody>
        	
        <tfoot>
            <tr>
                <th class="text-center">Voucher Number</th>
                <th class="text-center">Invoice</th>
                <th class="text-center">Contractor</th>
                <th class="text-center">Institution</th>
                <th class="text-center">Payment Date</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Attachments</th>
                @if(Auth::user()->id == 1)
                <th class="text-center">Inserted By</th>
                <th class="text-center">action</th>
                @endif
                
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
    <script>
        $(document).ready(function(){
          $.fn.dataTable.ext.search.push(
          function (settings, data, dataIndex) {
              var min = $('.min').datepicker("getDate");
              var max = $('#max').datepicker("getDate");
              var startDate = new Date(data[4]);
              if (min == null && max == null) { return true; }
              if (min == null && startDate <= max) { return true;}
              if(max == null && startDate >= min) {return true;}
              if (startDate <= max && startDate >= min) { return true; }
              return false;
          }
          );

         
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
              var table = $('#example').DataTable();

              // Event listener to the two range filtering inputs to redraw on input
              $('#min, #max').change(function () {
                  table.draw();
              });
              
          });
    </script>
    <script type="text/javascript">
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
 
    var table = $('#example').DataTable( {
        
        orderCellsTop: true,
        fixedHeader: true,
        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
  "<'row'<'col-sm-12'tr>>" +
  "<'row'<'col-sm-5'i><'col-sm-7'p>>",
       buttons: [     
            {
                extend: 'excelHtml5',
                title: 'IDMS',
                messageTop: 'IDMS',
                footer: true,
                header: true

            },
            {
                extend: 'pdfHtml5',
                title: 'IDMS',
                messageTop: 'IDMS',
                footer: true,
                header: true

            },
            {
                extend: 'csvHtml5',
                title: 'IDMS',
                messageTop: 'IDMS',
                footer: true,
                header: true

            },
            {
                extend: 'print',
                footer: true,
                header: true
            }
        ],
       
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                pageTotal
            );
        }
    } );

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