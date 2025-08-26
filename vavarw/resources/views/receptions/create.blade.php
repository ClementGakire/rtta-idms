@include('inc.navbar')
@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp
@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Receptions'))

  <section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">CREATE Reception</h3>

               <form action="{{ action('ReceptionsController@store') }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}

                      <!--<div class="form-group">-->
                      <!--  <label for="inputState">Purchase Order</label>-->
                      <!--    <select id="purchase_order" class="form-control dynamic" name="purchase_order">-->
                      <!--        <option selected disabled="">Choose...</option>-->
                      <!--        @foreach($roadmaps->unique('purchase_order') as $rm)-->
                      <!--        <option value="{{$rm->purchase_order}}">{{$rm->purchase_order}}</option>-->
                      <!--        @endforeach-->
                      <!--    </select>-->
                      <!--</div>-->

                      <div class="form-group">
                        <label for="inputState">Roadmap Number</label>
                          <select id="purchase_order" class="form-control dynamic" name="roadmap_number">
                              <option selected disabled="">Choose...</option>
                              @foreach($roadmaps as $rm)
                              <option value="{{$rm->roadmap_number}}">{{$rm->roadmap_number}}</option>
                              @endforeach
                          </select>
                      </div>

                      <!--<div class="form-group">-->
                      <!--  <label for="inputState">Contractor</label>-->
                      <!--    <select id="contractor" class="form-control" name="contractor">-->
                      <!--        <option selected disabled="">Choose...</option>-->
                      <!--        @foreach($contractors as $contractor)-->
                      <!--        <option value="{{$contractor->name}}">{{$contractor->name}}</option>-->
                      <!--        @endforeach-->
                      <!--    </select>-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="inputState">Institution</label>-->
                      <!--    <select id="institution" class="form-control dynamic" name="institution">-->
                      <!--        <option selected disabled="">Choose...</option>-->
                      <!--        @foreach($institutions as $institution)-->
                      <!--        <option value="{{$institution->name}}">{{$institution->name}}</option>-->
                      <!--        @endforeach-->
                      <!--    </select>-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="title">Number of Days</label>-->
                      <!--  <input type="number" class="form-control" id="number_of_days" placeholder="Number of Days" name="number_of_days" step="any">-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="start_date">Starting Date</label>-->
                      <!--  <input type="date" name="starting_date" id="start_date" class="form-control">-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="end_date">Ending Date</label>-->
                      <!--  <input type="date" name="ending_date" id="end_date" class="form-control">-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="title">Operator</label>-->
                      <!--  <input type="text" class="form-control" id="operator" placeholder="Operator" name="operator">-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="title">Destination</label>-->
                      <!--  <input type="text" class="form-control" id="destination" placeholder="Destination" name="destination">-->
                      <!--</div>-->

                      <!--<div class="form-group">-->
                      <!--  <label for="title">Plate Number</label>-->
                      <!--  <input type="text" class="form-control" id="plate_number" placeholder="Plate Number" name="plate_number">-->
                      <!--</div>-->

                      <div class="form-group">
                        <label for="title">EBM</label>
                        <input type="text" class="form-control" id="ebm" placeholder="EBM" name="ebm">
                      </div>
                      <div class="form-group">
                        <label for="title">Messenger</label>
                        <input type="text" class="form-control" id="ebm" placeholder="Messenger" name="messenger">
                      </div>
                      <div class="form-group">
                        <label for="title">Messenger Phone Number</label>
                        <input type="text" class="form-control" id="ebm" placeholder="Messenger Phone Number" name="messenger_phone">
                      </div>
                   
                      <div class="form-group">
                        <label for="title">Files (invoices, receipts, etc)</label>
                        <input type="file" class="form-control" name="files[]" placeholder="files" multiple>
                      </div>

                      <button type="submit" class="btn btn-primary">Submit</button>
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
          // optionally fill roadmap_number when purchase_order selected
          var po = $(this).val();
          // you can add ajax if you want to fetch roadmap details
        });
      });
    </script>

@endif

@endsection
