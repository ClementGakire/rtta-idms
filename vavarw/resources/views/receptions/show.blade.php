@include('inc.navbar')
@extends('layouts.app')

@section('content')
<section style="padding-left: 190px; padding-top: 100px;">
  <div class="container-fluid">
    <h3 class="text-center">Reception Files</h3>
    <dl class="row text-center justify-content-center">
      <dt class="col-sm-3">Files</dt>
      <dd class="col-sm-9">
        @if(!empty($files))
          @foreach($files as $f)
            @php
              $publicPath = public_path('images/' . $f);
              $storagePath = storage_path('app/public/images/' . $f);
            @endphp
            @if(file_exists($publicPath) && is_file($publicPath))
              <a href="{{ asset('images/' . $f) }}" target="_blank">{{ $f }}</a><br/>
            @elseif(file_exists($storagePath) && is_file($storagePath))
              <a href="{{ asset('storage/images/' . $f) }}" target="_blank">{{ $f }}</a><br/>
            @else
              <span class="text-warning">{{ $f }} — Missing file</span><br/>
            @endif
          @endforeach
        @else
          <span class="text-danger">No files</span>
        @endif
      </dd>
    </dl>
  </div>
</section>
@endsection
