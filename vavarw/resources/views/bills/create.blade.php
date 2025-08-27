@include('inc.navbar')
@extends('layouts.app')

@section('content')
	
	
	

	<section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">CREATE BILL</h3>
                
               <form action="{{ action('BillsController@store') }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label for="title">Supplier</label>
                        <input type="" name="supplier_id" list="institutions" class="form-control" placeholder="Search Supplier" required="" id="txts">
                        <datalist id="institutions">  
                        
                          @foreach($suppliers as $supplier)
                          <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                          @endforeach
                        </datalist>

                      </div>
                      <div class="form-group">
                        <label for="title">Purchase Order</label>
                        <input type="" name="roadmap_id" list="roadmaps" class="form-control" placeholder="Search Purchase Order" required="" id="txts">
                        <datalist id="roadmaps">  
                        
                          @foreach($roadmaps as $roadmap)
                          <option value="{{$roadmap->id}}">{{$roadmap->purchase_order}}</option>
                          @endforeach
                        </datalist>

                      </div>
                      <div class="form-group">
                        <label for="title">Quoted Amount</label>
                        <input type="number" class="form-control" id="title" placeholder="Quoted Amount" name="quoted_amount">
                      </div>
                      <div class="form-group">
                        <label for="title">Paid Amount</label>
                        <input type="number" class="form-control" id="title" placeholder="Paid Amount" name="amount" required>
                      </div>
                      <div class="form-group">
                          <label for="payment_mode">Payment Mode</label><br>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="payment_mode[]" value="MoMo" id="momo">
                            <label class="form-check-label" for="momo">MoMo</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="payment_mode[]" value="Bank Transfer" id="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">Bank Transfer</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="payment_mode[]" value="Cash" id="cash">
                            <label class="form-check-label" for="cash">Cash</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="payment_mode[]" value="Check" id="check">
                            <label class="form-check-label" for="check">Check</label>
                          </div>
                        </div>
                      <div class="form-group">
                        <label for="title">Payment Date</label>
                        <input type="date" class="form-control" id="title" placeholder="Amount" name="payment_date" required>
                      </div>
                      <div class="form-group">
                        <label for="title">EBM</label>
                        <input type="text" class="form-control" id="title" placeholder="EBM" name="ebm" required>
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