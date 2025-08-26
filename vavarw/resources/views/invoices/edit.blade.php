@include('inc.navbar')
@extends('layouts.app')

@section('content')
	@if(Auth::user()->role_id == 1 || 'Invoices' )
	
	

	<section style="padding-left: 60px; padding-top: 100px; padding-bottom: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-left mb-3">EDIT INVOICE</h3>
                
               <form action="{{ action('InvoiceController@update', [$invoice->id]) }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <input type="hidden" name="_method" value="put">
                      
                      <div class="form-group">
                        <label for="inputState">Institution</label>
                          <select id="client" class="form-control dynamic" name="institution">
                              <option selected="" value="{{ $invoice->institution }}">{{ $invoice->institution }}</option>

                              @foreach($institutions as $institution)
                              <option value="{{$institution->name}}">{{$institution->name}}</option>
                              <!--<input type="text" class="form-control" id="member" placeholder="Enter Customer" value="" name="adescription">-->
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="inputState">Contractor</label>
                          <select id="client" class="form-control dynamic" name="contractor_id">
                              <option selected="" value="{{ $invoice->contractor_id }}">{{ $invoice->contractor_id }}</option>

                              @foreach($contractors as $contractor)
                              <option value="{{$contractor->id}}">{{$contractor->name}}</option>
                              <!--<input type="text" class="form-control" id="member" placeholder="Enter Customer" value="" name="adescription">-->
                              @endforeach
                          </select>
                      </div>
                  

                      
                      <div class="form-group">
                        <label for="title">Invoice Number</label>
                        <input type="text" class="form-control" id="title" placeholder="Invoice Number" name="invoiceNumber" value="{{ $invoice->invoiceNumber }}">
                      </div>
                     
                      <div class="form-group">
                        <label for="title">P.O Number</label>
                        <input type="text" class="form-control" id="title" placeholder="Purchase Order Number" name="purchase_order">
                      </div>

                      <div class="form-group">
                        <label for="start_date">Created On</label>
                        <input type="date" name="created_on" id="start_date" class="form-control" value="{{ $invoice->created_on }}">
                      </div>

                      <div class="form-group">
                        <label for="end_date">Received On</label>
                        <input type="date" name="received_on" id="end_date" class="form-control" value="{{ $invoice->received_on }}">
                      </div>
                      <div class="form-group">
                        <label for="title">EBM Number</label>
                        <input type="text" class="form-control" id="title" placeholder="EBM Number" name="ebm_number" value="{{$invoice->ebm_number}}">
                      </div>                     
                      <div class="form-group">
                        <label for="amount">Gross Amount</label>
                        <input type="number" step="0.01" class="form-control" id="gross_amount" name="amount" value="{{ $invoice->amount }}">
                      </div>
        
                      @php
                        $deductions = explode(',', $invoice->deductions ?? '');
                      @endphp
                      <div class="form-group">
                        <label>Deductions</label><br>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="withholding" name="deductions[]" value="withholding"
                                 {{ in_array('withholding', $deductions) ? 'checked' : '' }}>
                          <label class="form-check-label" for="withholding">Withholding 3%</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="vat" name="deductions[]" value="vat"
                                 {{ in_array('vat', $deductions) ? 'checked' : '' }}>
                          <label class="form-check-label" for="vat">VAT 18%</label>
                        </div>
                      </div>
        
                      <div class="form-group">
                        <label for="net_amount">Net To Bank</label>
                        <input type="number" step="0.01" class="form-control" id="net_amount" name="net_amount" value="{{ $invoice->net_amount }}" readonly>
                      </div>
        
                      <div class="form-group">
                        <label for="bank_account">Bank Account</label>
                        <select class="form-control" id="bank_account" name="bank_account">
                          <option value="Bank of Kigali - 11234343434" {{ $invoice->bank_account == 'Bank of Kigali - 11234343434' ? 'selected' : '' }}>Bank of Kigali - 11234343434</option>
                          <option value="Equity Bank - 2211223344" {{ $invoice->bank_account == 'Equity Bank - 2211223344' ? 'selected' : '' }}>Equity Bank - 2211223344</option>
                          <option value="GTBank - 5566778899" {{ $invoice->bank_account == 'GTBank - 5566778899' ? 'selected' : '' }}>GTBank - 5566778899</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="title">Files(invoices, etc)</label>  
                        <input type="file" class="form-control" name="files[]" placeholder="address" multiple>
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
                          <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const grossInput = document.getElementById('gross_amount');
                                const withholding = document.getElementById('withholding');
                                const vat = document.getElementById('vat');
                                const netInput = document.getElementById('net_amount');
                            
                                function calculateNet() {
                                    let gross = parseFloat(grossInput.value) || 0;
                                    let net = gross;
                            
                                    if (withholding.checked) {
                                        net -= gross * 0.03;
                                    }
                                    if (vat.checked) {
                                        net -= gross * 0.18;
                                    }
                            
                                    netInput.value = net.toFixed(2);
                                }
                            
                                grossInput.addEventListener('input', calculateNet);
                                withholding.addEventListener('change', calculateNet);
                                vat.addEventListener('change', calculateNet);
                            
                                calculateNet();
                            });
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