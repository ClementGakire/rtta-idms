@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 80px; padding-left: 150px;">
  <div class="row">
    <div class="col-md-8">
      <h3>User Sessions</h3>
    </div>
    <div class="col-md-4 text-right">
      <div class="form-inline" style="justify-content: flex-end;">
        <label for="min" class="mr-2" style="color: #6c757d;">From</label>
        <input id="min" type="text" class="form-control form-control-sm min mr-2" autocomplete="off">
        <label for="max" class="mr-2" style="color: #6c757d;">To</label>
        <input id="max" type="text" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>
  </div>

  <table id="sessions-table" class="table table-striped mt-3">
    <thead>
      <tr>
        <th>Date</th>
        <th>Username</th>
        <th>Session time</th>
        <th>Notes</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sessions as $s)
      <tr>
        <td>{{ $s->date }}</td>
        <td>{{ $s->user->name }}</td>
        <td>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</td>
        <td>{{ $s->notes }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script>
      $(document).ready(function(){
        // initialize jQuery UI datepickers (if available)
        try {
          if ($.fn.datepicker) {
            $("#min").datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' });
            $("#max").datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' });
          }
        } catch (e) {
          console && console.warn && console.warn('Datepicker init failed', e);
        }

        // Custom date-range filter using string parsing (ISO YYYY-MM-DD)
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
          try {
            var minVal = $('#min').val();
            var maxVal = $('#max').val();
            var dateStr = data[0];
            if (!dateStr) return true;
            var rowDate = new Date(dateStr + 'T00:00:00');
            if (!minVal && !maxVal) return true;
            if (minVal && !maxVal) {
              var minDate = new Date(minVal + 'T00:00:00');
              return rowDate >= minDate;
            }
            if (!minVal && maxVal) {
              var maxDate = new Date(maxVal + 'T23:59:59');
              return rowDate <= maxDate;
            }
            var minDate = new Date(minVal + 'T00:00:00');
            var maxDate = new Date(maxVal + 'T23:59:59');
            return rowDate >= minDate && rowDate <= maxDate;
          } catch (e) {
            console && console.warn && console.warn('Date filter error', e);
            return true;
          }
        });

        var table = null;
        try {
          table = $('#sessions-table').DataTable( {
            orderCellsTop: true,
            fixedHeader: true,
            "order": [[0, "desc"]],
            dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
              { extend: 'excelHtml5', title: 'User Sessions', footer: true, header: true },
              { extend: 'pdfHtml5', title: 'User Sessions', footer: true, header: true },
              { extend: 'csvHtml5', title: 'User Sessions', footer: true, header: true },
              { extend: 'print', footer: true, header: true }
            ]
          } );

          // clone header for column search (after table init)
          try {
            $('#sessions-table thead tr').clone(true).appendTo( '#sessions-table thead' );
            $('#sessions-table thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:150px;" placeholder=" '+title+'" />' );
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
          } catch (e) { console && console.warn && console.warn('Header clone failed', e); }

          // redraw on date change
          $('#min, #max').change(function () { if (table) table.draw(); });
        } catch (e) {
          console && console.warn && console.warn('DataTable init failed', e);
        }
      });
    </script>
