@include('inc.navbar')
@extends('layouts.app')

@section('content')

@if(Auth::user()->id == 1 || Auth::user()->role_id == 2)

  <section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">EDIT Reception</h3>

        <form action="{{ route('receptions.update', $reception->id) }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          {{ method_field('PUT') }}

                    <div class="form-group">
                      <label for="inputState">Roadmap Number</label>
                      <select id="purchase_order" class="form-control dynamic" name="roadmap_number">
                          <option disabled>Choose...</option>
                          @foreach($roadmaps as $rm)
                              <option value="{{$rm->roadmap_number}}"
                                {{ $reception->roadmap_number == $rm->roadmap_number ? 'selected' : '' }}>
                                {{$rm->roadmap_number}}
                              </option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="title">EBM</label>
                      <input type="text" class="form-control" id="ebm" placeholder="EBM" 
                             name="ebm" value="{{ old('ebm', $reception->ebm) }}">
                    </div>

                    <div class="form-group">
                      <label for="title">Messenger</label>
                      <input type="text" class="form-control" id="messenger" placeholder="Messenger" 
                             name="messenger" value="{{ old('messenger', $reception->messenger) }}">
                    </div>
                    <div class="form-group">
                      <label for="title">Messenger Phone Number</label>
                      <input type="text" class="form-control" id="messenger_phone" placeholder="Messenger Phone Number" 
                             name="messenger_phone" value="{{ old('messenger_phone', $reception->messenger_phone) }}">
                    </div>
                  <div class="form-group">
                      <label for="inputState">Supplier</label>
                        <select id="supplier" class="form-control dynamic" name="supplier">
                            <option disabled>Choose...</option>
                            @foreach($suppliers as $sp)
                            <option value="{{$sp->name}}" {{ $reception->supplier == $sp->name ? 'selected' : '' }}>{{$sp->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                      <label for="title">Files (invoices, receipts, etc)</label>
                      <input type="file" class="form-control" name="files[]" multiple>
                      @if($reception->files)
                        <p class="mt-2">Existing files:</p>
                        <ul>
                          @foreach(explode('|', $reception->files) as $file)
                            <li><a href="{{ asset('images/'.$file) }}" target="_blank">{{ $file }}</a></li>
                          @endforeach
                        </ul>
                      @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="{{asset('jquery.min.js')}}"></script>
    <script src="{{asset('bootstrap.min.js')}}"></script>
    <script>
      $(document).ready(function(){
        $('#purchase_order').change(function(){
          var po = $(this).val();
        });
      });
    </script>

@else

  <section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
    <div class="container-fluid">
      <div class="alert alert-warning">You are not authorized to edit this reception.</div>
      <a href="{{ url('/receptions') }}" class="btn btn-secondary">Back to receptions</a>
    </div>
  </section>

@endif

@endsection
