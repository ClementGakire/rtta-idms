@include('inc.navbar')
@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Clients'))
	<section style="padding-left: 60px; padding-top: 100px;">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row align-items-center">
              <div class="col-xl-11 col-12 mb-4 mb-xl-0">
                <h3 class="text-muted text-center mb-3">Institutions</h3>
                <div style="padding-bottom: 20px;">
                 <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

</div>

<div class="text-center">
  <a href="/institutions/create" class="btn btn-info btn-rounded mb-4">Create New Institution</a>
</div>
                 </div>
                <table id="" class="display" style="width:100%" style="padding-left: 60px">
        <thead>
            <tr>
                <th>Name</th>
                <th>Tin</th>
                
                @if(Auth::user()->id == 1)
                <th>action</th>
                @endif
            </tr>
        </thead>
        
        <tbody>

        	@foreach($institutions as $institution)
        	<tr>
        		
        		<td>{{ $institution->name }}</td>
        		<td>{{ $institution->tin }}</td>
        		@if(Auth::user()->id == 1)
        		<td class="text-left pl-4"><a href="/institutions/{{$institution->id}}"><i class="fas fa-eye" style="padding-right: 4px;"></i></a><a href="/institutions/{{$institution->id}}/edit"><i class="fas fa-edit text-success" style="padding-left: 4px;"></i></a></td>@endif
        	</tr>
        	@endforeach
        </tbody>
        	
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Tin</th>
                
                @if(Auth::user()->id == 1)
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
    </table>
    
              </div>  
            </div>    
          </div>      
        </div>        
      </div>
    </section>
       <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.8/js/mdb.min.js"></script>
    <script src="script.js"></script>

    <script>
      $(document).ready(function() {
    $('table.display').DataTable();
} );
    </script>
    @endif
@endsection