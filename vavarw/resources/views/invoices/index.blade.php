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
                <h3 class="text-muted text-center mb-3">Invoices</h3>
                <div style="padding-bottom: 20px;">
                 <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

</div>

<div class="text-center">
  <a href="/invoices/create" class="btn btn-info btn-rounded mb-4">Create New Invoice</a>
</div>
<table border="0" cellspacing="5" cellpadding="5" style="padding-top: 45px">
                  <tbody><tr>
                        <td>Filter Data by Date of Creation</td>
                          <td>From:</td>
                          <td><input type="text" id="min" name="min" class="min"></td>
                      
                          <td>To:</td>
                          <td><input type="text" id="max" name="max"></td>
                      </tr>
                  </tbody>
                </table>
                 </div>
                <table id="example" class="" style="width:100%" style="padding-left: 50px">
        <thead style="font-size: 14px;">
            <tr>
                <th>Invoice Number</th>
                <th>Contractor</th>
                <th>Institution</th>
                <th>EBM Number</th>
                <th>Created On</th>
                <th>Received On</th>
                <th>P.O Number</th>
                <th>Gross Amount</th>
                <th>Deductions</th>
                <th>VAT Amount</th>
                <th>Withholding Amount</th>
                <th>Net Amount</th>
                <th>Bank Account</th>
                <th>Status</th>
                <th>Attachments</th>
                @if(Auth::user()->id == 1)
                <th>Inserted By</th>
                <th>Action</th>
                @endif
            </tr>
        </thead>

        
        <tbody style="font-size: 12px;">

        	@foreach($invoices as $invoice)
@php
    $gross = $invoice->amount;
    $vatAmount = 0;
    $withholdingAmount = 0;

    if (Str::contains($invoice->deductions, 'vat')) {
        $vatAmount = $gross * 18 / 118;
    }

    if (Str::contains($invoice->deductions, 'withholding')) {
        if (Str::contains($invoice->deductions, 'vat')) {
            $withholdingAmount = ($gross - $vatAmount) * 0.03;
        } else {
            $withholdingAmount = $gross * 0.03;
        }
    }
@endphp
<tr>
    <td>{{ $invoice->invoiceNumber }}</td>
    <td>{{ $invoice->name }}</td>
    <td>{{ $invoice->institution }}</td>
    <td>{{ $invoice->ebm_number }}</td>
    <td>{{ $invoice->created_on }}</td>
    <td>{{ $invoice->received_on }}</td>
    <td>{{ $invoice->purchase_order }}</td>
    <td>{{ number_format($invoice->amount, 2) }}</td>
    
    <!-- Keep badges -->
    <td>
        @if($invoice->deductions)
            @foreach(explode(',', $invoice->deductions) as $deduction)
                <span class="badge badge-info">{{ ucfirst($deduction) }}</span>
            @endforeach
        @else
            <span class="badge badge-secondary">None</span>
        @endif
    </td>

    <!-- New VAT & Withholding columns -->
    <td>{{ $vatAmount > 0 ? number_format($vatAmount, 2) : '-' }}</td>
    <td>{{ $withholdingAmount > 0 ? number_format($withholdingAmount, 2) : '-' }}</td>

    <td>{{ number_format($invoice->net_amount, 2) }}</td>
    <td>{{ $invoice->bank_account }}</td>

    @if($invoice->amounts > 0)
        <td class="text-success">Paid</td>
    @else
        <td class="text-danger">Unpaid</td>
    @endif
        <td><a href="/invoices/{{$invoice->id}}">
                <i class="fas fa-eye" style="padding-right: 4px; width: 20px"></i>View Attached Files
            </a></td>
    @if(Auth::user()->id == 1)
        <td>{{ $invoice->username }}</td>
        <td class="text-left pl-4">
            
            <a href="/invoices/{{$invoice->id}}/edit">
                <i class="fas fa-edit text-success" style="padding-left: 4px; width: 20px"></i>
            </a>
            <form action="{{ action('InvoiceController@destroy', [$invoice->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="delete">
                <button onclick="return confirm('Do you want to delete this invoice?')">
                    <i class="fa fa-trash text-danger"></i>
                </button>
            </form>
        </td>
    @endif
</tr>
@endforeach

        </tbody>
        	
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Total:</th>
                <th></th> <!-- Gross Amount -->
                <th></th> <!-- Deductions -->
                <th></th> <!-- VAT Amount -->
                <th></th> <!-- Withholding Amount -->
                <th></th> <!-- Net Amount -->
                <th colspan="4"></th>
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
       
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
        
            var intVal = function (i) {
                if (typeof i === 'string') {
                    i = i.replace(/[\$,]/g, '');
                    return isNaN(i) || i.trim() === '-' ? 0 : parseFloat(i);
                }
                return typeof i === 'number' ? i : 0;
            };
        
            var formatNum = function (num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };
        
            var colTotal = function (index) {
                return api.column(index, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
            };
        
            // Update footer
            $(api.column(7).footer()).html(formatNum(colTotal(7).toFixed(2)));   // Gross
            $(api.column(9).footer()).html(formatNum(colTotal(9).toFixed(2)));   // VAT
            $(api.column(10).footer()).html(formatNum(colTotal(10).toFixed(2))); // Withholding
            $(api.column(11).footer()).html(formatNum(colTotal(11).toFixed(2))); // Net
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