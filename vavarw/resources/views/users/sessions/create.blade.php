@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 80px;">
  <h3>Record a User Session</h3>

  <form method="POST" action="/user-sessions">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" class="form-control" id="date" name="date" required>
    </div>
    <div class="form-group">
      <label for="user_id">Username</label>
      <select name="user_id" id="user_id" class="form-control" required>
        @foreach($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="start_time">Start time</label>
        <input type="time" class="form-control" id="start_time" name="start_time" required>
      </div>
      <div class="form-group col-md-6">
        <label for="end_time">End time</label>
        <input type="time" class="form-control" id="end_time" name="end_time" required>
      </div>
    </div>
    <div class="form-group">
      <label for="notes">Notes</label>
      <input type="text" class="form-control" id="notes" name="notes">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
@endsection
