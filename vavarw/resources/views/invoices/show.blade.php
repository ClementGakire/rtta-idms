@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Invoice #{{ $invoice->invoiceNumber }}</h3>
    <p><strong>Institution:</strong> {{ $invoice->institution }}</p>
    <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }}</p>
    <p><strong>Created On:</strong> {{ $invoice->created_on }}</p>
    <p><strong>Received On:</strong> {{ $invoice->received_on }}</p>

    <h4>Uploaded Files</h4>
    @if($files && count($files) > 0)
        <ul>
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
</div>
@endsection
