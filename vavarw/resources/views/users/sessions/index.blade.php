@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 80px;">
  <div class="row">
    <div class="col-md-10">
      <h3>User Sessions</h3>
    </div>
    <div class="col-md-2 text-right">
      <a href="/user-sessions/create" class="btn btn-primary">New Session</a>
    </div>
  </div>

  <table class="table table-striped mt-3">
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
