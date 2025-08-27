@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 80px;">
  <div class="row">
    <div class="col-md-12">
      <h3>User Sessions</h3>
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.8/js/mdb.min.js"></script>
    <script src="script.js"></script>

    <script>
      $(document).ready(function() {
        $('#sessions-table').DataTable({
          "order": [[0, "desc"]]
        });
      } );
    </script>
