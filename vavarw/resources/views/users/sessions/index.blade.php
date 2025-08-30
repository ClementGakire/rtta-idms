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
  <th style="display:none">Updated At</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sessions as $s)
      <tr>
        <td>{{ $s->date ?? '' }}</td>
        <td>{{ optional($s->user)->name ?? 'Unknown' }}</td>
        <td>
          {{ $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '-' }}
           - 
          {{ $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '-' }}
        </td>
        <td>{{ $s->notes ?? '' }}</td>
        <td style="display:none">{{ $s->updated_at ?? '' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

  <!-- DataTables scripts are centralized in layouts/app.blade.php -->
    <script>
      // global table var so cloned-header script can access it
      var sessionsTable = null;

      $(document).ready(function(){
          // Date range filter (using jQuery UI datepicker)
          $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                // only apply to the sessions table
                if (settings.nTable.id !== 'sessions-table') return true;
                var min = $('#min').datepicker("getDate");
                var max = $('#max').datepicker("getDate");
                var startDate = data[0] ? new Date(data[0]) : null; // date is in first column
                if (!startDate) return true;
                if (min == null && max == null) { return true; }
                if (min == null && startDate <= max) { return true; }
                if (max == null && startDate >= min) { return true; }
                if (startDate <= max && startDate >= min) { return true; }
                return false;
            }
          );

          // init datepickers
          try {
            $("#min").datepicker({ 
              onSelect: function () { if (sessionsTable) sessionsTable.draw(); },
              changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' 
            });
            $("#max").datepicker({ 
              onSelect: function () { if (sessionsTable) sessionsTable.draw(); },
              changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' 
            });
          } catch (e) { console && console.warn && console.warn('Datepicker init failed', e); }

          // initialize DataTable
          try {
            sessionsTable = $('#sessions-table').DataTable( {
              orderCellsTop: true,
              fixedHeader: true,
              // order by the hidden "Updated At" column (last column) desc
              "order": [[4, "desc"]],
              columnDefs: [
                { targets: [4], visible: false, searchable: false }
              ],
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

            // redraw on date change
            $('#min, #max').change(function () { if (sessionsTable) sessionsTable.draw(); });
          } catch (e) { console && console.warn && console.warn('DataTable init failed', e); }
      });
    </script>

    <script>
      // clone header for per-column search inputs
      try {
        $('#sessions-table thead tr').clone(true).appendTo( '#sessions-table thead' );
        $('#sessions-table thead tr:eq(0) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" style="width:150px;" placeholder=" '+title+'" />' );
            $( 'input', this ).on( 'keyup change', function () {
                if (sessionsTable && sessionsTable.column(i).search() !== this.value ) {
                    sessionsTable
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );
      } catch (e) { console && console.warn && console.warn('Header clone failed', e); }
  </script>
