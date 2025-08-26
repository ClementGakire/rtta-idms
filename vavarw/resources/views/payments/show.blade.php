@include('inc.navbar')
@extends('layouts.app')

@section('content')
@if(Auth::user()->id == 1 || strpos(Auth::user()->role_id, 'Payments') !== false)
<p class="text-center text-primary "><a href="/payments" class="">(Go Back)</a></p>
<form action="{{ action('PaymentController@destroy', [$payment->id]) }}" method="POST" class="text-center">
                      {{ csrf_field() }}
                      <input type="hidden" name="_method" value="delete">
                      <input type="submit" name="" class="btn btn-danger pull right" value="delete">
                </form> 
                <h4 class="text-center">Uploaded Files</h4>
    @if($files && count($files) > 0)
        <ul class="text-center">
            @foreach($files as $file)
                <li>
                    <a href="{{ asset('images/' . $file) }}" target="_blank">
                        {{ $file }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No files uploaded.</p>
    @endif
@endif
@endsection