@include('inc.navbar')
@extends('layouts.app')

@section('content')
@if(Auth::user()->id == 1 || Auth::user()->role_id == 'Roadmap Deployment')
	
	

	<section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">EDIT OPERATION</h3>
                
               <form action="{{ action('RoadmapController@update', [$roadmap->id]) }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <input type="hidden" name="_method" value="put">
                      <div class="form-group">
                        <label for="inputState">Contractor</label>
                          <select id="client" class="form-control dynamic" name="contractor_id">
                              <option selected="" value="{{ $roadmap->contractor_id }}">{{ $roadmap->contractor_id }}</option>

                              @foreach($contractors as $contractor)
                              <option value="{{$contractor->id}}">{{$contractor->name}}</option>
                              <!--<input type="text" class="form-control" id="member" placeholder="Enter Customer" value="" name="adescription">-->
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="inputState">Institution</label>
                          <select id="client" class="form-control dynamic" name="institution">
                              <option selected="" value="{{ $roadmap->institution }}">{{ $roadmap->institution }}</option>

                              @foreach($institutions as $institution)
                              <option value="{{$institution->name}}">{{$institution->name}}</option>
                              <!--<input type="text" class="form-control" id="member" placeholder="Enter Customer" value="" name="adescription">-->
                              @endforeach
                          </select>
                      </div>
                      <!--<div class="form-group">-->
                      <!--  <label for="title">Car</label>-->
                      <!--  <input type="" name="car_id" list="institutions" class="form-control" placeholder="Search Car" required="" id="car_id" value="{{$roadmap->car_id}}">-->
                      <!--  <datalist id="institutions">  -->
                        
                      <!--    @foreach($cars as $car)-->
                      <!--    <option value="{{$car->id}}" data-supplier="{{$car->supplier}}" data-unit_price="{{$car->unit_price}}">{{$car->plate_number}} - {{$car->supplier}}</option>-->
                      <!--    @endforeach-->
                      <!--  </datalist>-->

                      <!--</div>-->
                      <div class="form-group">
                        <label for="inputState">Car</label>
                          <select id="client" class="form-control dynamic" name="car_id">
                              <option selected="" value="{{ $roadmap->plate }}">{{ $roadmap->plate_number }}</option>
                              @foreach($cars as $car)
                              <option value="{{$car->id}}">{{$car->plate_number}}</option>
                              <!--<input type="text" class="form-control" id="member" placeholder="Enter Customer" value="" name="adescription">-->
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="title">Operator</label>
                        <input type="text" class="form-control" id="title" placeholder="Operator" name="operator" value="{{ $roadmap->operator }}">
                      </div>
                      <div class="form-group">
                        <label for="title">P.O Number</label>
                        <input type="text" class="form-control" id="title" placeholder="Purchase Order Number" name="purchase_order" value="{{ $roadmap->purchase_order }}">
                      </div>

                      <div class="form-group">
                        <label for="start_date">Starting Date</label>
                        <input type="date" name="created_on" id="start_date" class="form-control" value="{{ $roadmap->created_on }}">
                      </div>

                      <div class="form-group">
                        <label for="end_date">Ending Date</label>
                        <input type="date" name="received_on" id="end_date" class="form-control" value="{{ $roadmap->received_on }}">
                      </div>
                      <div class="form-group">
                        <label for="title">Destination</label>
                        <input type="text" class="form-control" id="title" placeholder="Destination" name="destination" value="{{ $roadmap->destination }}">
                      </div>
                      <div class="form-group">
                        <label for="title">Advance Fuel</label>
                        <input type="text" class="form-control" id="title" placeholder="Fuel" name="advance_fuel" value="{{ $roadmap->advance_fuel }}">
                      </div>
                      <div class="form-group">
                        <label for="title">Advance Cash</label>
                        <input type="text" class="form-control" id="title" placeholder="Cash" name="advance_cash" value="{{ $roadmap->advance_cash }}">
                      </div>
                      <div class="form-group">
                        <label for="title">Number of Days</label>
                        <input type="text" class="form-control" id="title" placeholder="Number of Days" name="ebm_number" value="{{$roadmap->ebm_number}}">
                      </div> 
                     
                      <div class="form-group">
                        <label for="title">Purchase Unit Price</label>
                        <input type="text" class="form-control" id="title" placeholder="Purchase Unit Price" name="amount" value="{{ $roadmap->amount }}">
                      </div>
                      <div class="form-group">
                        <label>Selling Unit Price</label>
                        <input type="number" class="form-control" name="selling_price" placeholder="Selling Price" value="{{ $roadmap->selling_price }}">
                      </div>
                      <div class="form-group">
                        <label for="title">Starting Odometer Reading(KM)</label>
                        <input type="number" class="form-control" id="title" placeholder="Km" name="odometer_start" value="{{$roadmap->odometer_start}}">
                      </div>
                       <div class="form-group">
                        <label for="title">Ending Odometer Reading(KM)</label>
                        <input type="number" class="form-control" id="title" placeholder="Km" name="odometer_end" value="{{$roadmap->odometer_end}}">
                      </div>
                      <div class="form-group">
                        <label for="title">Roadmap Number</label>
                        <input type="number" class="form-control" id="title" placeholder="Roadmap Number" name="roadmap_number" value="{{$roadmap->roadmap_number}}">
                      </div>
                      <div class="form-group">
                        <label for="title">Driver</label>
                        <input type="" name="driver_id" list="drivers" class="form-control" placeholder="Search Driver" id="txts" value="{{ $roadmap->name }}">
                        <datalist id="drivers">  
                        
                          @foreach($drivers as $driver)
                          <option value="{{$driver->id}}">{{$driver->name}}</option>
                          @endforeach
                        </datalist>

                      </div>
                      <div class="form-group">
                        <label for="title">Files(invoices, receipts, etc)</label>  
                        <input type="file" class="form-control" name="files[]" placeholder="address" multiple>
                      </div>
                      <div class="form-group">
                        <label for="inputState">Status</label>
                          <select class="form-control dynamic" name="status">
                              <option selected="" value="{{ $roadmap->status }}">{{ $roadmap->status }}</option>
                              <option value="Closed">Closed</option>
                              <option value="Ongoing">Ongoing</option>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Submit</button>
                 
               </form>
    
              </div>  
            </div>    
          </div>      
        </div>        
      </div>
    </section>@endif
                          <script src="assets/js/jquery.min.js"></script>
                          <script src="assets/bootstrap/js/bootstrap.min.js"></script>
                          <script src="{{asset('jquery.min.js')}}"></script>
                          <script src="{{asset('bootstrap.min.js')}}"></script>
                          <script type="text/javascript">
                          
                          </script>
                          <script type="text/javascript">
    jQuery(document).ready(function ()
    {
            jQuery('select[name="customer"]').on('change',function(){
               var customerID = jQuery(this).val();
               if(customerID)
               {
                  jQuery.ajax({
                     url : 'getstates/' +customerID,
                     type : "GET",
                     dataType : "json",
                     success:function(data)
                     {
                        console.log(data);
                        jQuery('select[name="company_id"]').empty();
                        jQuery.each(data, function(key,value){
                           $('select[name="company_id"]').append('<option value="'+ key +'">'+ key +'-'+ '</option>');
                        });
                     }
                  });
               }
               else
               {
                  $('select[name="company_id"]').empty();
               }
            });
    });
    </script>









@endsection