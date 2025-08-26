@include('inc.navbar')
@extends('layouts.app')

@section('content')

	
	

	<section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">CREATE CAR</h3>
                
               <form action="{{ action('CarsController@store') }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label for="title">Plate Number</label>
                        <input type="text" class="form-control" id="title" placeholder="Plate Number" name="plate_number">
                      </div>
                      <div class="form-group">
                        <label for="title">Manufacturing Year</label>
                        <input type="text" class="form-control" id="title" placeholder="Manufacturing Year" name="manufacturing_year">
                      </div>
                      <div class="form-group">
                        <label for="title">Model</label>
                        <input type="text" class="form-control" id="title" placeholder="Model" name="model">
                      </div>
                      <div class="form-group">
                        <label for="title">Owner</label>
                        <input type="" name="owner_id" list="owners" class="form-control" placeholder="Search Owner" required="" id="txts">
                        <datalist id="owners">  
                          @foreach($suppliers as $supplier)
                          <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                          @endforeach
                        </datalist>
                      </div>
                      <div class="form-group">
                        <label for="title">Files(invoices, receipts, etc)</label>  
                        <input type="file" class="form-control" name="files[]" placeholder="address" multiple>
                      </div>
                      <button type="submit" class="btn btn-primary">Submit</button>                 
               </form>
    
              </div>  
            </div>    
          </div>      
        </div>        
      </div>
    </section>
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