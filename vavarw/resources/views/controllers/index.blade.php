@extends('layouts.app')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp
@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Controllers'))
<section style="padding-left: 60px; padding-top: 100px;">
  <div class="container-fluid">
    <div class="row mb-12">
      <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
        <div class="row align-items-center">
          <div class="col-xl-11 col-12 mb-4 mb-xl-0">
            <h3 class="text-muted text-center mb-3">Controllers - Receptions to Review</h3>

            <style>
              /* Table visual improvements */
              #controllers-table { font-size:13px; border-collapse:collapse; }
              #controllers-table thead th { background:#fafafa; color:#444; font-weight:700; padding:12px 10px; border-bottom:2px solid #e6e6e6; }
              #controllers-table tbody td { padding:14px 10px; vertical-align:middle; border-bottom:1px solid #f0f0f0; }
              #controllers-table tbody tr:nth-child(odd) { background:#ffffff }
              #controllers-table tbody tr:nth-child(even) { background:#fbfbfb }
              .attachment-link { color:#1e88e5; text-decoration:none; font-weight:600 }
              .attachment-link .fas { margin-right:6px; }
              .action-cell{ display:flex; gap:8px; align-items:center; white-space:nowrap; }
              .action-cell .action-item{ margin:0; }
              .action-cell .action-item .btn{ padding:7px 10px; display:inline-flex; align-items:center; justify-content:center; }
              .action-cell .action-item form{ margin:0; }
              .controller-status { font-weight:600; color:#666 }
              .badge-approved{ background:#1976d2; color:#fff; padding:6px 10px; border-radius:4px }
              .badge-sent{ background:#2e7d32; color:#fff; padding:6px 10px; border-radius:4px }
              .badge-denied{ background:#c62828; color:#fff; padding:6px 10px; border-radius:4px }
            </style>

            <table id="controllers-table" class="display" style="width:100%">
              <thead style="font-size:14px;">
                <tr>
                  <th>P.O Number</th>
                  <th>Roadmap #</th>
                  <th>Contractor</th>
                  <th>Institution</th>
                  <th>Operator</th>
                  <th>Destination</th>
                  <th>Plate</th>
                  <th>Messenger</th>
                  <th>EBM</th>
                  <th>Total Purchase Price</th>
                  <th>Total Selling Price</th>
                  <th>Attachments</th>
                  <th>Controller Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody style="font-size:12px;">
                @foreach($receptions as $r)
                <tr>
                  <td>{{ $r->purchase_order }}</td>
                  <td>{{ $r->roadmap_number }}</td>
                  <td>{{ $r->contractor }}</td>
                  <td>{{ $r->institution }}</td>
                  <td>{{ $r->operator }}</td>
                  <td>{{ $r->destination }}</td>
                  <td>{{ $r->plate_number }}</td>
                  <td>{{ $r->messenger }} ({{ $r->messenger_phone }})</td>
                  <td>{{ $r->ebm }}</td>
                  <td>{{ number_format($r->total_amount) }}</td>
                  <td>{{ number_format($r->ebm_number * $r->selling_price) }}</td>
                  <td>
                    <a class="attachment-link" href="/receptions/{{ $r->id }}" title="View attachments">
                      <i class="fas fa-paperclip"></i>
                      View attachments
                    </a>
                  </td>
                  <td>
                        @if(isset($r->transmission) && $r->transmission === 'Sent')
                            <span class="badge-sent">Received</span>
                        @elseif(isset($r->transmission) && $r->transmission === 'Approved')
                            <span class="badge-approved">Approved</span>
                        @elseif(isset($r->transmission) && $r->transmission === 'Denied')
                            <span class="badge-denied">Denied</span>
                        @else
                            <span class="controller-status">{{ $r->transmission }}</span>
                        @endif
                    </td>
                    <td>
                      <div class="action-cell">
                        <div class="action-item">
                          <form action="/receptions/{{ $r->id }}/approve" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-success btn-sm" @if(empty($r->transmission)) disabled @endif>
                              <i class="fas fa-check" aria-hidden="true" style="margin-right:6px;"></i>Approve
                            </button>
                          </form>
                        </div>

                        <div class="action-item">
                          <form action="/receptions/{{ $r->id }}/deny" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" @if(empty($r->transmission)) disabled @endif>
                              <i class="fas fa-ban" aria-hidden="true" style="margin-right:6px;"></i>Deny
                            </button>
                          </form>
                        </div>

                        @if(!empty($r->transmission) && $r->transmission === 'Approved')
                          <div class="action-item">
                            <button type="button" class="btn btn-primary btn-sm" onclick="printApproved('{{ $r->id }}')">
                              <i class="fas fa-print" aria-hidden="true" style="margin-right:6px;"></i>Print
                            </button>
                          </div>
                        @endif
                      </div>
                    </td>

                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">

<!-- jQuery and DataTables JS + Buttons extensions -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<script>
  $(document).ready(function(){
    $('#controllers-table').DataTable({
      orderCellsTop: true,
      fixedHeader: true,
      dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      buttons: [ 'excelHtml5','pdfHtml5','csvHtml5','print' ],
      lengthMenu: [[10, 25, 50, 100, -1], [10,25,50,100,'All']]
    });
  });
</script>
<script>
  // Print helper: opens a popup with the printable template and triggers print
  function printApproved(id) {
    var tpl = document.getElementById('printable-' + id);
    if (!tpl) {
      alert('Printable content not found');
      return;
    }
    var w = window.open('', '_blank', 'width=900,height=700');
    var html = `<!doctype html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>Reception Print</title>
      <style>
  @page { size: A4; margin: 18mm }
  body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 18px; color: #222; }
  .print-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
  .logo { font-weight:700; font-size:18px; }
  .company-info { text-align:right; font-size:12px; color:#333; }
  .label { font-weight:700; display:inline-block; min-width:160px; }
  .row { margin-bottom:8px; font-size:13px; }
  .section-table { width:100%; border-collapse:collapse; margin-top:12px; font-size:13px }
  .section-table th, .section-table td { padding:10px; border:1px solid #ddd; vertical-align:top; }
  .section-title { background:#f5f5f5; font-weight:700; }
  .totals { margin-top:20px; font-size:14px; display:flex; gap:20px; }
  .totals .box { padding:12px 16px; border:1px solid #e0e0e0; background:#fafafa; border-radius:6px; }
  .totals .amount { font-weight:700; font-size:1.1em; color:#111; }
  .status-badge { display:inline-block; padding:8px 12px; background:#2e7d32; color:#fff; border-radius:4px; font-weight:700; margin-top:12px; }
  .watermark { position:fixed; top:45%; left:50%; transform:translate(-50%, -50%) rotate(-28deg); font-size:8rem; color: rgba(0,0,0,0.04); pointer-events:none; z-index:9999; }
      </style>
    </head>
    <body>
      <div class="watermark">APPROVED</div>
      ${tpl.innerHTML}
    </body>
    </html>`;

    w.document.open();
    w.document.write(html);
    w.document.close();
    // wait for resources to load then print
    setTimeout(function(){
      w.focus();
      w.print();
      // do not auto-close to let user see print preview if they want
    }, 300);
  }
</script>

<!-- Hidden printable templates for Approved receptions -->
@foreach($receptions as $r)
  @if(!empty($r->transmission) && $r->transmission === 'Approved')
    <div id="printable-{{ $r->id }}" style="display:none">
      <div style="max-width:820px;margin:0 auto;">
        <div class="print-header">
          <div class="logo">Internal Control</div>
          <div class="company-info">
            <div style="font-weight:700;">RWANDA TOURISM AND TRAVEL AGENCY Ltd</div>
            <div>Delta House, Muhima, route pavée</div>
            <div>Tel: (+250) 788 300 228 - 788303737 - 788385715</div>
            <div>E-mail: rtta1@yahoo.com</div>
            <div>B.P: 4817 Kigali - Rwanda</div>
          </div>
        </div>

        <h3 style="margin:4px 0 12px 0; text-align:center; font-size:18px;">Reception / Approved Report</h3>

        <table class="section-table">
          <tr><th class="section-title">Supplier</th><th class="section-title">Operation</th></tr>
          <tr>
            <td>
              <div><strong>Supplier:</strong> {{ $r->supplier ?? '' }}</div>
              <div><strong>EBM:</strong> {{ $r->ebm ?? '' }}</div>
            </td>
            <td>
              <div><strong>P.O Number:</strong> {{ $r->purchase_order }}</div>
              <div><strong>Roadmap #:</strong> {{ $r->roadmap_number }}</div>
              <div><strong>Contractor:</strong> {{ $r->contractor }}</div>
              <div><strong>Institution:</strong> {{ $r->institution }}</div>
              <div><strong>Operator:</strong> {{ $r->operator }}</div>
              <div><strong>Destination:</strong> {{ $r->destination }}</div>
              <div><strong>Plate:</strong> {{ $r->plate_number }}</div>
              <div><strong>Driver:</strong> {{ $r->driver ?? '' }} ({{ $r->phone_number ?? '' }})</div>
              <div><strong>Messenger:</strong> {{ $r->messenger ?? '' }} ({{ $r->messenger_phone ?? '' }})</div>
              <div><strong>Starting Date:</strong> {{ $r->created_on ?? '' }} &nbsp; <strong>Ending Date:</strong> {{ $r->received_on ?? '' }}</div>
            </td>
          </tr>
        </table>

        <div class="totals">
          <div class="box">
            <div style="font-size:12px;color:#666">Total Purchase Price</div>
            <div class="amount">{{ number_format($r->total_amount ?? 0) }}</div>
          </div>
          <div class="box">
            <div style="font-size:12px;color:#666">Total Selling Price</div>
            <div class="amount">{{ number_format(($r->ebm_number ?? 0) * ($r->selling_price ?? 0)) }}</div>
          </div>
        </div>

        <div style="margin-top:18px; display:flex; justify-content:space-between; align-items:center;">
          <div>
            <span class="status-badge">APPROVED</span>
          </div>
          <div style="text-align:right; font-size:13px; color:#333;">
            <div><strong>Approved By:</strong> {{ $r->approver_name ?? '—' }}</div>
            <div><strong>Approved At:</strong> {{ isset($r->approved_at) ? \Carbon\Carbon::parse($r->approved_at)->format('d-m-Y H:i') : '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  @endif
@endforeach
@endif
@endsection
