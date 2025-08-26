@include('inc.navbar')
@extends('layouts.app')
@section('content')
@php use Illuminate\Support\Str; @endphp
@if(Auth::user()->role_id == 1 || Str::contains(Auth::user()->role_id, 'Receptions'))

<section style="padding-left: 60px; padding-top: 100px;">
  <div class="container-fluid">
    <div class="row mb-12">
      <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
        <div class="row align-items-center">
          <div class="col-xl-11 col-12 mb-4 mb-xl-0">
            <h3 class="text-muted text-center mb-3">Receptions</h3>
            <div class="text-center" style="padding-bottom:20px;">
              <a href="/receptions/create" class="btn btn-info btn-rounded mb-4">Create Reception</a>
            </div>

            <style>
              /* Make action buttons larger and spaced so each action is easily clickable */
              .action-cell{ display:flex; gap:8px; align-items:center; white-space:nowrap; }
              .action-cell .action-item{ margin:0; }
              .action-cell .action-item .btn{ padding:6px 10px; display:inline-flex; align-items:center; justify-content:center; }
              .action-cell .action-item form{ margin:0; }
              .action-cell .action-item a.btn, .action-cell .action-item button.btn{ min-width:38px; }
            </style>

            <table id="receptions-table" class="display" style="width:100%">
              <thead style="font-size:14px;">
                <tr>
                  <th>P.O Number</th>
                  <th>Roadmap #</th>
                  <th>Contractor</th>
                  <th>Institution</th>
                  <th>Number of Days</th>
                  <th>Starting Date</th>
                  <th>Ending Date</th>
                  <th>Operator</th>
                  <th>Destination</th>
                  <th>Plate</th>
                  <th>EBM</th>
                  <th>Driver</th>
                  <th>Driver's Phone No</th>
                  <th>Messenger</th>
                  <th>Messenger's Phone No</th>
                  <th>Supplier</th>
                  <th>Attachments</th>
                  <th>Controller Status</th>
                  <th>Created At</th>
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
                  <td>{{ $r->ebm_number }}</td>
                  <td>{{ $r->created_on }}</td>
                  <td>{{ $r->received_on }}</td>
                  <td>{{ $r->operator }}</td>
                  <td>{{ $r->destination }}</td>
                  <td>{{ $r->plate_number }}</td>
                  <td>{{ $r->ebm }}</td>
                  <td>{{ $r->driver }}</td>
                  <td>{{ $r->phone_number }}</td>
                  <td>{{ $r->messenger }}</td>
                  <td>{{ $r->messenger_phone }}</td>
                  <td>{{ $r->supplier }}</td>
                  <td>
                    <a href="/receptions/{{ $r->id }}" title="">
                      <i class="fas fa-paperclip"></i>
                      View attachments
                    </a>
                  </td>
                  <td>
                        @if(isset($r->transmission) && $r->transmission === 'Sent')
                            <span class="badge badge-success" style="padding:6px 10px;">Sent</span>
                        @elseif(empty($r->transmission) || $r->transmission === 'Denied')
                            <form action="/receptions/{{ $r->id }}/send-to-controller" method="POST" style="display:inline">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-sm btn-primary">Send to Controller</button>
                            </form>
                        @else
                            <span class="badge badge-warning" style="padding:6px 10px;">{{ $r->transmission }}</span>
                        @endif
                    </td>
                <td>{{ $r->created_at }}</td>
                  <td>
                    <div class="action-cell">
                      <!-- Print button: opens a printable document populated with this row's details -->
                      <div class="action-item">
                        <button type="button" class="btn btn-sm btn-secondary" 
                          onclick="openPrintWindow(this)"
                          data-purchase-order="{{ $r->purchase_order }}"
                          data-received-by="{{ Auth::user()->name }}"
                          data-roadmap-number="{{ $r->roadmap_number }}"
                          data-contractor="{{ $r->contractor }}"
                          data-institution="{{ $r->institution }}"
                          data-ebm="{{ $r->ebm }}"
                          data-created-on="{{ $r->created_on }}"
                          data-received-on="{{ $r->received_on }}"
                          data-operator="{{ $r->operator }}"
                          data-destination="{{ $r->destination }}"
                          data-plate-number="{{ $r->plate_number }}"
                          data-driver="{{ $r->driver }}"
                          data-phone-number="{{ $r->phone_number }}"
                          data-messenger="{{ $r->messenger }}"
                          data-messenger-phone="{{ $r->messenger_phone }}"
                          data-supplier="{{ $r->supplier }}"
                          data-created-at="{{ $r->created_at }}"
                        >
                          <i class="fas fa-print"></i>
                        </button>
                      </div>

                      <!-- View -->
                      <div class="action-item">
                        <a class="btn btn-sm btn-outline-secondary" href="/receptions/{{ $r->id }}" title="View">
                          <i class="fas fa-eye"></i>
                        </a>
                      </div>

                      <!-- Edit -->
                      <div class="action-item">
                        <a class="btn btn-sm btn-success" href="{{ route('receptions.edit', $r->id) }}" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                      </div>

                      <!-- Delete -->
                      <div class="action-item">
                        <form action="/receptions/{{ $r->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reception? This cannot be undone.')">
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                          <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DataTables and initialization similar to po.index -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function(){
    $('#receptions-table').DataTable({
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
<!-- html2pdf for client-side PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<!-- html2canvas and jsPDF for alternate PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<script>
  // Build inner HTML for the printable content (used by both print window and pdf generator)
  function buildPrintContent(d) {
    var content = '' +
        '<div style="font-family: Arial, Helvetica, sans-serif; color:#222">' +
        '<div class="header" style="display:flex;justify-content:space-between;align-items:center; margin-bottom:12px;">' +
          '<div class="logo" style="font-weight:700;font-size:18px;">RTTA</div>' +
          '<div class="company-info" style="text-align:right;font-size:12px;color:#333;">' +
            '<div style="font-weight:700;">RWANDA TOURISM AND TRAVEL AGENCY Ltd</div>' +
            '<div>Delta House, Muhima, route pavée</div>' +
            '<div>Tel: (+250) 788 300 228 - 788303737 - 788385715</div>' +
            '<div>E-mail: rtta1@yahoo.com</div>' +
            '<div>B.P: 4817 Kigali - Rwanda</div>' +
          '</div>' +
        '</div>' +
        '<h3 style="margin:4px 0 12px 0; text-align:center; font-size:18px;">Reception Details</h3>' +
      '<table style="width:100%;border-collapse:collapse;margin-top:20px">' +
        '<tr><th style="padding:8px;border:1px solid #444;">Supplier details</th><th style="padding:8px;border:1px solid #444;">Company details</th></tr>'+
        '<tr><td style="padding:8px;border:1px solid #444;vertical-align:top">' +
          '<strong>Supplier:</strong> ' + (d.supplier || '') + '<br>'+
          '<strong>EBM:</strong> ' + (d.ebm || '') + '<br>'+
        '</td><td style="padding:8px;border:1px solid #444;vertical-align:top">' +
          // Static company block from provided image
          '<div style="font-weight:700;">RWANDA TOURISM AND TRAVEL AGENCY Ltd</div>' +
          '<div>Delta House, Muhima, route pavée</div>' +
          '<div>Tel: (+250) 788 300 228 - 788303737 - 788385715</div>' +
          '<div>E-mail: rtta1@yahoo.com</div>' +
          '<div>B.P: 4817 Kigali - Rwanda</div>' +
        '</td></tr>' +
        '<tr><th colspan="2" style="padding:8px;border:1px solid #444;">Operation details</th></tr>' +
        '<tr><td colspan="2" style="padding:8px;border:1px solid #444;vertical-align:top">' +
          '<strong>Operator:</strong> ' + (d.operator || '') + '<br>'+
          '<strong>PO Number:</strong> ' + (d.purchaseOrder || '') + '<br>'+
          '<strong>Destination:</strong> ' + (d.destination || '') + '<br>'+
          '<strong>Plate:</strong> ' + (d.plateNumber || '') + '<br>'+
          '<strong>Driver:</strong> ' + (d.driver || '') + ' (' + (d.phoneNumber || '') + ')<br>'+
          '<strong>Messenger:</strong> ' + (d.messenger || '') + ' (' + (d.messengerPhone || '') + ')<br>'+
          '<strong>Starting Date:</strong> ' + (d.createdOn || '') + ' &nbsp; <strong>Ending Date:</strong> ' + (d.receivedOn || '') + '<br>'+
        '</td></tr>' +
      '</table>'+
      '<div style="margin-top:20px">' +
        '<strong>Received By:</strong> ' + (d.receivedBy || 'admin') + '<br>'+
        '<strong>Date & Time:</strong> ' + (d.createdAt || (new Date()).toLocaleString()) + '<br>'+
        '<div style="height:80px;border:1px dashed #444;margin-top:8px">&nbsp;</div>'+
      '</div>'+
      '</div>';
    return content;
  }

  // open a print window using the full HTML wrapper
  function openPrintWindow(btn) {
    var d = btn.dataset;
    // normalize keys used in buildPrintContent
    var data = {
      supplier: d.supplier || d.supplier,
      purchaseOrder: d.purchaseOrder || d.purchaseOrder,
  ebm: d.ebm || '',
      contractor: d.contractor || d.contractor,
      institution: d.institution || d.institution,
      roadmapNumber: d.roadmapNumber || d.roadmapNumber,
      operator: d.operator || d.operator,
      destination: d.destination || d.destination,
      plateNumber: d.plateNumber || d.plateNumber,
      driver: d.driver || d.driver,
      phoneNumber: d.phoneNumber || d.phoneNumber,
      messenger: d.messenger || d.messenger,
      messengerPhone: d.messengerPhone || d.messengerPhone,
      createdOn: d.createdOn || d.createdOn,
      receivedOn: d.receivedOn || d.receivedOn,
      createdAt: d.createdAt || d.createdAt,
      receivedBy: d.receivedBy || 'admin',
      printDate: (new Date()).toLocaleString()
    };

    var full = '<!doctype html><html><head><meta charset="utf-8"><title></title>' +
      '<style>@page{size:A4; margin:20mm;} body{font-family: Arial, Helvetica, sans-serif; margin:0; color:#222} .page-wrap{padding:20mm; box-sizing:border-box;}</style>' +
      '</head><body><div class="page-wrap">' + buildPrintContent(data) + '</div></body></html>';

    var w = window.open('', '_blank', 'width=900,height=700');
    if (!w) { alert('Please allow popups for this site to enable printing.'); return; }
    w.document.open();
    w.document.write(full);
    w.document.close();
    // wait briefly then call print
    setTimeout(function(){ w.print(); }, 300);
  }

  // Generate client-side PDF using html2canvas + jsPDF and open it in a new tab (reduces browser header/footer)
  function downloadPdf(btn) {
    var d = btn.dataset;
    var data = {
      supplier: d.supplier,
      purchaseOrder: d.purchaseOrder,
  ebm: d.ebm || '',
      contractor: d.contractor,
      institution: d.institution,
      roadmapNumber: d.roadmapNumber,
      operator: d.operator,
      destination: d.destination,
      plateNumber: d.plateNumber,
      driver: d.driver,
      phoneNumber: d.phoneNumber,
      messenger: d.messenger,
      messengerPhone: d.messengerPhone,
      createdOn: d.createdOn,
      receivedOn: d.receivedOn,
      createdAt: d.createdAt,
      receivedBy: d.receivedBy || 'admin',
      printDate: (new Date()).toLocaleString()
    };

    // create visible container (opacity 0) so html2canvas renders it
    var container = document.createElement('div');
    container.style.position = 'absolute';
    container.style.left = '0';
    container.style.top = '0';
    container.style.width = '800px';
    container.style.background = '#fff';
    container.style.padding = '20px';
    container.style.boxSizing = 'border-box';
    container.style.visibility = 'visible';
    container.style.opacity = '0';
    container.style.zIndex = '9999';
    container.innerHTML = buildPrintContent(data);
    document.body.appendChild(container);

    // use html2canvas to capture the container then create a PDF with jsPDF
    html2canvas(container, { scale: 2 }).then(function(canvas) {
      try {
        var imgData = canvas.toDataURL('image/jpeg', 1.0);
        var pdf = new jsPDF('p', 'mm', 'a4');
        var pageWidth = pdf.internal.pageSize.getWidth();
        var pageHeight = pdf.internal.pageSize.getHeight();
        var imgProps = { width: canvas.width, height: canvas.height };
        var pdfHeight = (imgProps.height * pageWidth) / imgProps.width;
        var marginTop = 10;
        pdf.addImage(imgData, 'JPEG', 10, marginTop, pageWidth - 20, pdfHeight);
        // output as blob and trigger a download (no viewer headers)
        var blob = pdf.output('blob');
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'reception-' + (data.purchaseOrder || 'document') + '.pdf';
        document.body.appendChild(a);
        a.click();
        setTimeout(function(){
          URL.revokeObjectURL(url);
          document.body.removeChild(a);
        }, 1000);
      } catch (err) {
        console.error(err);
        alert('Failed to generate PDF');
      } finally {
        document.body.removeChild(container);
      }
    }).catch(function(err){
      document.body.removeChild(container);
      console.error(err);
      alert('Failed to generate PDF');
    });
  }
</script>

@endif
@endsection
