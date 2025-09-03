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
  <!-- PDF.js and jsPDF for client-side PDF compression -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
      $(document).ready(function(){
        $('#purchase_order').change(function(){
          // optionally fill roadmap_number when purchase_order selected
          var po = $(this).val();
          // you can add ajax if you want to fetch roadmap details
        });

        // Max size in bytes (2 MB)
        var MAX_BYTES = 2 * 1024 * 1024;

        // Utility: convert canvas to blob as a Promise
        function canvasToBlob(canvas, type, quality) {
          return new Promise(function(resolve) {
            canvas.toBlob(function(blob) {
              resolve(blob);
            }, type, quality);
          });
        }

  // Compress image File to be <= maxBytes. Returns a Promise resolving to a Blob or File (may be original file if already small).
        function compressImageFile(file, maxBytes) {
          return new Promise(function(resolve, reject) {
            if (!file.type.startsWith('image/')) {
              // not an image; cannot compress generically
              return resolve(null);
            }

            if (file.size <= maxBytes) return resolve(file);

            var reader = new FileReader();
            reader.onerror = function() { reject(new Error('Failed to read file')); };
            reader.onload = function(e) {
              var img = new Image();
              img.onerror = function() { reject(new Error('Invalid image')); };
              img.onload = async function() {
                try {
                  var canvas = document.createElement('canvas');
                  var ctx = canvas.getContext('2d');
                  var width = img.width;
                  var height = img.height;
                  canvas.width = width;
                  canvas.height = height;
                  ctx.drawImage(img, 0, 0, width, height);

                  // try progressively reducing quality and dimensions
                  var mime = 'image/jpeg'; // convert to jpeg for better compression
                  var quality = 0.92;
                  var blob = await canvasToBlob(canvas, mime, quality);

                  var iterations = 0;
                  while ((blob.size > maxBytes) && iterations < 12) {
                    iterations++;
                    // reduce quality first
                    quality = Math.max(0.35, quality - 0.08);
                    if (quality <= 0.35) {
                      // reduce dimensions by 90%
                      width = Math.round(width * 0.9);
                      height = Math.round(height * 0.9);
                      canvas.width = width;
                      canvas.height = height;
                      ctx.clearRect(0,0,width,height);
                      ctx.drawImage(img, 0, 0, width, height);
                      // reset quality a bit
                      quality = Math.max(0.45, quality);
                    }
                    blob = await canvasToBlob(canvas, mime, quality);
                  }

                  // if we couldn't compress (still too big), return the best we have
                  if (blob.size > maxBytes) {
                    // still larger than allowed, but return blob so caller can decide
                    resolve(blob);
                  } else {
                    // create a File-like object so backend sees a filename
                    var newFile = new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), { type: mime });
                    resolve(newFile);
                  }
                } catch (err) {
                  reject(err);
                }
              };
              img.src = e.target.result;
            };
            reader.readAsDataURL(file);
          });
        }

        // Compress PDF by rendering pages to images and rebuilding a new PDF via jsPDF
        // Returns Promise<File|Blob|null>
        function compressPdfFile(file, maxBytes) {
          return new Promise(async function(resolve, reject){
            try {
              var arrayBuffer = await file.arrayBuffer();
              // pdfjs global
              var pdfjsLib = window['pdfjs-dist/build/pdf'] || window['pdfjsLib'] || window.pdfjsLib;
              if (!pdfjsLib) {
                // fallback: try window['pdfjsLib']
                pdfjsLib = window['pdfjsLib'];
              }
              if (!pdfjsLib || !pdfjsLib.getDocument) {
                return reject(new Error('PDF.js not available')); 
              }

              // Ensure workerSrc is set if needed
              if (pdfjsLib.GlobalWorkerOptions && !pdfjsLib.GlobalWorkerOptions.workerSrc) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
              }

              var loadingTask = pdfjsLib.getDocument({data: arrayBuffer});
              var pdf = await loadingTask.promise;
              var totalPages = pdf.numPages;

              const { jsPDF } = window.jspdf || window.jspdf || window.jsPDF || {};
              if (!jsPDF) return reject(new Error('jsPDF not available'));

              // prepare jsPDF with default page size (A4) - we'll add pages sized to rendered images
              var pdfDoc = new jsPDF({ unit: 'pt' });

              // for each page render to canvas and add as JPEG image
              for (var p = 1; p <= totalPages; p++) {
                var page = await pdf.getPage(p);
                var viewport = page.getViewport({ scale: 1.0 });

                // choose a scale to control output resolution; we'll lower scale for very large pages
                var scale = 1.0;
                if (viewport.width > 1400) scale = 1400 / viewport.width;
                var renderViewport = page.getViewport({ scale: scale });

                var canvas = document.createElement('canvas');
                canvas.width = Math.round(renderViewport.width);
                canvas.height = Math.round(renderViewport.height);
                var ctx = canvas.getContext('2d');

                await page.render({ canvasContext: ctx, viewport: renderViewport }).promise;

                // reduce image quality moderately
                var mime = 'image/jpeg';
                var quality = 0.78;
                // convert canvas to dataURL (jpeg) and add to jsPDF
                var dataUrl = canvas.toDataURL(mime, quality);

                // compute dimensions in points for jsPDF (1 pt = 1px at default 72 DPI assumption isn't exact, but ok)
                var imgProps = { width: canvas.width, height: canvas.height };

                if (p === 1) {
                  pdfDoc = new jsPDF({ unit: 'pt', format: [imgProps.width, imgProps.height] });
                  pdfDoc.addImage(dataUrl, 'JPEG', 0, 0, imgProps.width, imgProps.height);
                } else {
                  pdfDoc.addPage([imgProps.width, imgProps.height]);
                  pdfDoc.addImage(dataUrl, 'JPEG', 0, 0, imgProps.width, imgProps.height);
                }
              }

              // output as blob
              const outBlob = pdfDoc.output('blob');
              if (outBlob.size <= maxBytes) {
                var outFile = new File([outBlob], file.name.replace(/\.[^.]+$/, '.pdf'), { type: 'application/pdf' });
                resolve(outFile);
              } else {
                // try one more pass with lower image quality per page
                // (advanced tuning omitted for brevity) -> return blob anyway
                resolve(outBlob);
              }
            } catch (err) {
              reject(err);
            }
          });
        }

        // Intercept form submit to compress large files (images) before sending
        $('form').on('submit', function(e){
          e.preventDefault();
          var $form = $(this);
          var action = $form.attr('action');
          var method = ($form.attr('method') || 'POST').toUpperCase();
          var filesInput = $form.find('input[type=file][name="files[]"]')[0];
          var files = filesInput ? Array.from(filesInput.files) : [];
          var otherFields = $form.serializeArray();

          var nonCompressibleLarge = [];
          var compressPromises = [];

          files.forEach(function(f, idx){
            if (f.size > MAX_BYTES) {
              if (f.type && f.type.startsWith('image/')) {
                // compress image
                compressPromises.push(compressImageFile(f, MAX_BYTES).then(function(result){
                  return { index: idx, original: f, result: result };
                }));
              } else if (f.type === 'application/pdf' || f.name.toLowerCase().endsWith('.pdf')) {
                // compress pdf
                compressPromises.push(compressPdfFile(f, MAX_BYTES).then(function(result){
                  return { index: idx, original: f, result: result };
                }));
              } else {
                nonCompressibleLarge.push(f.name);
              }
            }
          });

          if (nonCompressibleLarge.length) {
            alert('These files exceed 2MB and cannot be compressed in the browser: \n' + nonCompressibleLarge.join('\n') + '\n\nPlease remove them or upload smaller versions.');
            return;
          }

          // when all compression promises done, build FormData and send via AJAX
          Promise.all(compressPromises).then(function(results){
            var fd = new FormData();

            // append other form fields (excluding files)
            otherFields.forEach(function(field){
              fd.append(field.name, field.value);
            });

            // append files: if compressed (result.result is File/Blob) use it; otherwise use original
            // We must preserve order; create an array with same length
            var finalFiles = files.slice(); // copy
            results.forEach(function(r){
              if (r.result === null) return; // shouldn't happen for image
              // if compression returned a Blob/File that's smaller, use it
              if (r.result instanceof Blob) {
                // replace at index
                finalFiles[r.index] = r.result;
              }
            });

            finalFiles.forEach(function(f){
              // skip undefined (if any)
              if (!f) return;
              // if a File-like object, append as 'files[]'
              fd.append('files[]', f, f.name || 'file');
            });

            // Add CSRF token if present in form (Laravel)
            var token = $form.find('input[name="_token"]').val();
            if (token) fd.append('_token', token);

            // disable submit button
            var $btn = $form.find('button[type=submit]');
            $btn.prop('disabled', true).text('Uploading...');

            $.ajax({
              url: action,
              method: method,
              data: fd,
              processData: false,
              contentType: false,
              success: function(resp){
                // on success, try to follow typical form behavior: reload or redirect if server sends Location header
                // simplest: reload page to show created resource or redirect to index
                location.reload();
              },
              error: function(xhr){
                var msg = 'Upload failed. Please try again.';
                if (xhr && xhr.responseText) msg += '\n' + xhr.responseText;
                alert(msg);
                $btn.prop('disabled', false).text('Submit');
              }
            });
          }).catch(function(err){
            console.error(err);
            alert('Error compressing images: ' + (err && err.message ? err.message : err));
          });
        });
      });
    </script>

@endif

@endsection
