<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 $(document).ready(function() {
  // Form validation
  (function() {
   'use strict'
   var forms = document.querySelectorAll('.needs-validation')
   Array.prototype.slice.call(forms)
    .forEach(function(form) {
     form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
       event.preventDefault()
       event.stopPropagation()
      }
      form.classList.add('was-validated')
     }, false)
    })
  })()
  $(document).on('change', '#category_slug', function() {
   let hsCode = $(this).find(':selected').data('hs-code');
   let slug = $(this).find(':selected').val();
   $("#hs_code_input").val(hsCode); // update hidden input
  });
  // Handle form submission
  $("#shippingForm").on("submit", function(e) {
   e.preventDefault();

   // Front-end validation: bootstrap style
   if (!this.checkValidity()) {
    $(this).addClass('was-validated');
    return;
   }

   // UI: show loading
   $("button[type='submit']").prop('disabled', true);
   $("button[type='submit'] .spinner-border").removeClass('d-none');
   $("#bookingLoader").removeClass('d-none');
   $("#rateContainer,#errorContainer").addClass('d-none').empty();

   // Build payload compliant with Easyship OpenAPI
   const payload = {
    origin_address: {
     line_1: $('#origin_line_1').val().trim(),
     state: $('#origin_state').val().trim() || null,
     city: $('#origin_city').val().trim(),
     postal_code: $('#origin_postal_code').val().trim() || null,
     country_alpha2: $('#origin_country').val().trim()
    },
    destination_address: {
     line_1: $('#dest_line_1').val().trim(),
     state: $('#dest_state').val().trim() || null,
     city: $('#dest_city').val().trim(),
     postal_code: $('#dest_postal_code').val().trim(),
     country_alpha2: $('#dest_country').val().trim(),
     set_as_residential: $('#set_as_residential').val() === 'true'
    },
    incoterms: $('#incoterms').val() || 'DDU',
    insurance: {
     is_insured: $('#is_insured').val() === 'true',
     insured_amount: parseFloat($('#insured_amount').val() || 0) || undefined,
     insured_currency: $('#declared_currency').val() || undefined
    },
    parcels: [{
     total_actual_weight: parseFloat($('#weight').val() || 0) || 1,
     box: {
      length: parseFloat($('#length').val() || 0),
      width: parseFloat($('#width').val() || 0),
      height: parseFloat($('#height').val() || 0)
     },
     items: [{
      quantity: 1,
      declared_currency: $('#declared_currency').val() || 'USD',
      declared_customs_value: parseFloat($('#declared_customs_value').val() || 0) || 0,
      actual_weight: parseFloat($('#weight').val() || 0) || 1,
      hs_code: $('#hs_code_input').val() || undefined
     }]
    }],
    shipping_settings: {} // required by API, empty object is valid
   };


   // Send to your controller endpoint
   $.ajax({
    url: "<?= site_url('shipping/getRates') ?>",
    type: "POST",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(payload),
    dataType: "json",
    success: function(res) {
     console.log('ajax called');
     if (res.rates && res.rates.length > 0) {
      let html = '';
      let availableServices = res.rates.length;
      res.rates.forEach(function(rate) {

       let logoPath = '<?= base_url('logos/') ?>';
       let courierIcon = '';
       let logoFile = 'default.png'; // fallback

       switch ((rate.courier_service?.umbrella_name || '').trim()) {
        case 'DHL':
         logoFile = 'dhl.svg';
         break;
        case 'UPS':
         logoFile = 'ups.svg';
         break;
        case 'Aramex':
         logoFile = 'aramex.svg';
         break;
        case 'FlatExportRate':
         logoFile = 'flatexportrate.svg';
         break;
        case 'SFExpress':
         logoFile = 'sf-express.svg';
         break;
        case 'Asendia':
         logoFile = 'asendia.svg';
         break;
        case 'Passport':
         logoFile = 'passport.svg';
         break;
        case 'FedEx':
         logoFile = 'fedex.svg';
         break;
        case 'USPS':
         logoFile = 'usps.svg';
         break;
        case 'Sendle':
         logoFile = 'sendle.svg';
         break;
        case 'Purolator':
         logoFile = 'purolator.svg';
         break;
        case 'Canada Post':
         logoFile = 'canada-post.svg';
         break;
        case 'Canpar':
         logoFile = 'canpar.svg';
         break;
        case 'StarTrack':
         logoFile = 'star-track.png';
         break;
        case 'CouriersPlease':
         logoFile = 'couriers-please.svg';
         break;
        case 'AlliedExpress':
         logoFile = 'alliedexpress.svg';
         break;
        case 'TNT':
         logoFile = 'tnt.svg';
         break;
        case 'Quantium':
         logoFile = 'quantium.svg';
         break;
        case 'Toll':
         logoFile = 'toll.svg';
         break;
        case 'HKPost':
         logoFile = 'hong-kong-post.svg';
         break;
        case 'APG':
         logoFile = 'apg.svg';
         break;
        case 'Hubbed':
         logoFile = 'hubbed.svg';
         break;
        case 'ePostGlobal':
         logoFile = 'ePostGlobal.svg';
         break;
        default:
         courierIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#2b6cb0" stroke-width="1.5">
              <rect x="2" y="7" width="20" height="12" rx="2" fill="#e6f2ff" />
              <path d="M12 3v4" />
              <path d="M7 7l5 4 5-4" />
            </svg>`;
       }

       if (!courierIcon) {
        courierIcon = `<img class="rounded-3 me-2" src="${logoPath + logoFile}" alt="${rate.courier_service?.umbrella_name}" width="auto" height="40">`;
       }

       // Tracking rating
       let trackingIcons = '';
       for (let i = 0; i < 5; i++) {
        trackingIcons += `<span style="color:${i < rate.tracking_rating ? '#00c853' : '#ccc'};">●</span>`;
       }

       const chargeWithTax = rate.total_charge * 1.15;
       const importTaxDuty = (rate.estimated_import_tax || 0) + (rate.estimated_import_duty || 0);


       // Service options with simple icons
       let serviceOptions = '';
       if (rate.available_handover_options && rate.available_handover_options.length > 0) {
        serviceOptions = rate.available_handover_options.map(opt => {
         let icon = '';
         let label = opt.replace(/_/g, ' ');
         label = label.charAt(0).toUpperCase() + label.slice(1);

         if (opt === 'dropoff') icon = '📦';
         if (opt === 'paid_pickup' || opt === 'free_pickup') icon = '🏠';

         return `<div>${icon} ${label}</div>`;
        }).join('');
       } else {
        serviceOptions = '-';
       }

       html += `
        <div class="card shadow-sm mb-3 p-3 rounded-3">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
               ${courierIcon}
              <div>
                <h6 class="mb-0 fw-bold">${rate.courier_service?.umbrella_name || '-'}</h6>
                <small class="text-muted">${rate.courier_service?.name || '-'}</small>
              </div>
            </div>
            <div class="text-end">
              <h5 class="mb-0 fw-bold">${rate.currency} ${chargeWithTax.toFixed(2)}</h5>
            </div>
          </div>

          <hr class="my-2">

          <div class="row align-items-center text-muted small">
            <div class="col-md-2 col-6">
              <strong>Delivery Time:</strong><br>
              ${rate.min_delivery_time} - ${rate.max_delivery_time} working days
            </div>
            <div class="col-md-1 col-6">
              <strong>Tracking:</strong><br>
              ${trackingIcons}
            </div>
            <div class="col-md-2 col-6">
              <strong>Remarks:</strong><br>
              ${rate.courier_remarks || '—'}
            </div>
            <div class="col-md-2 col-6">
              <strong>Import Tax & Duty:</strong><br>
              ${rate.currency} ${importTaxDuty.toFixed(2)}<br>
              to be paid by receiver
            </div>
            <div class="col-md-2 col-6">
              <strong>Rating :</strong><br>
              ${rate.tracking_rating}/5 <i class="fas fa-star" style="color:yellow"></i>
            </div>
            <div class="col-md-2 col-6">
              <strong>Service Options:</strong><br>
              ${serviceOptions}
            </div>
            <div class="col-md-1 col-12 text-end mt-2 mt-md-0">
              <a class="btn btn-sm btn-primary book-btn"
                href="javascript:void(0);" 
                data-courier="${rate.courier_service?.umbrella_name}" 
                data-service="${rate.courier_service?.name}" 
                data-delivery="${rate.min_delivery_time} - ${rate.max_delivery_time} days" 
                data-description="${rate.full_description}" 
                data-currency="${rate.currency}" 
                data-charge="${chargeWithTax}"
                data-taxduty="${importTaxDuty}">
                Book
              </a>
            </div>
          </div>
        </div>
      `;
      });

      let available = `
            <div class="row text-center py-3">
            <h3>Available Services (${availableServices})</h3>
            </div>
            `;
      $("#rateContainer").removeClass('d-none');
      $("#bookingLoader").addClass('d-none');
      $("button[type='submit'] .spinner-border").addClass('d-none');
      $("#rateContainer").html(available + html);

     } else {
      showError("No shipping rates available for your destination.");
     }
    },
    error: function(xhr) {
     $("#bookingLoader").addClass('d-none');
     let errorMsg = "An error occurred while calculating rates.";
     if (xhr.responseJSON && xhr.responseJSON.error) {
      errorMsg = xhr.responseJSON.error.message || JSON.stringify(xhr.responseJSON.error);
     }
     showError(errorMsg);
    },
    complete: function() {
     $("#bookingLoader").addClass('d-none');
     $("button[type='submit']").prop('disabled', false);
     $("button[type='submit'] .spinner-border").addClass('d-none');
    }
   });
  });

  // helper to show errors
  function showError(msg) {
   $("#errorContainer").removeClass('d-none').text(msg);
   $("button[type='submit']").prop('disabled', false);
   $("button[type='submit'] .spinner-border").addClass('d-none');
  }
 });

 $(document).on('click', '.book-btn', function() {
  let payload = $("#shippingForm").serializeArray();

  // Add rate info
  payload.push({
   name: "courier_name",
   value: $(this).data("courier")
  });
  payload.push({
   name: "service_name",
   value: $(this).data("service")
  });
  payload.push({
   name: "delivery_time",
   value: $(this).data("delivery")
  });
  payload.push({
   name: "description",
   value: $(this).data("description")
  });
  payload.push({
   name: "currency",
   value: $(this).data("currency")
  });
  payload.push({
   name: "total_charge",
   value: $(this).data("charge")
  });
  payload.push({
   name: "category",
   value: $("#category_slug").val()
  });
  payload.push({
   name: "tax_duty",
   value: $(this).data("taxduty")
  });

  // Show loader
  // $(".book-btn").prop("disabled", true); // disable all booking buttons

  Swal.fire({
   title: 'Confirm Booking',
   html: 'Are you sure you want to confirm this booking?',
   icon: 'question',
   showCancelButton: true,
   confirmButtonText: 'Yes, Confirm',
   cancelButtonText: 'Cancel',
   reverseButtons: true
  }).then((result) => {
   if (result.isConfirmed) {
    // Show loader and disable button
    $("#bookingLoader").removeClass("d-none");
    $(".book-btn").prop("disabled", true);

    // Process booking request
    $.post("<?= site_url('shipping/book') ?>", payload, function(res) {
     // Hide loader and enable button
     $("#bookingLoader").addClass("d-none");
     $(".book-btn").prop("disabled", false);

     if (res.status === "success") {
      Swal.fire({
       icon: 'success',
       title: 'Booking Confirmed!',
       html: `✅ Your booking has been confirmed.<br><b>Booking ID:</b> ${res.booking_id}`,
       showConfirmButton: false,
       timer: 7000,
       timerProgressBar: true,
       didClose: () => {
        let redirectUrl = '';
        if (res.role === 'customer') {
         redirectUrl = "<?= site_url('customer/shipping/details/') ?>" + res.booking_id;
        } else {
         redirectUrl = "<?= site_url('shipping/details/') ?>" + res.booking_id;
        }
        window.location.href = redirectUrl;
       }
      });
     } else {
      Swal.fire({
       icon: 'error',
       title: 'Booking Failed',
       html: res.message || 'Something went wrong while booking.',
       confirmButtonColor: '#d33'
      });
     }
    }, "json").fail(function() {
     $("#bookingLoader").addClass("d-none");
     $(".book-btn").prop("disabled", false);
     Swal.fire({
      icon: 'error',
      title: 'Error',
      html: 'An unexpected error occurred while booking.',
      confirmButtonColor: '#d33'
     });
    });
   }
  });

 });
</script>

<style>
 /* Make all logos grayscale by default */
 .logo-img {
  filter: grayscale(100%);
  transition: filter 0.3s ease;
 }

 /* On hover, show original color */
 .logo-img:hover {
  filter: grayscale(0%);
 }

 .bg-gradient-primary {
  background: linear-gradient(135deg, #2c3e50, #3498db);
 }

 .form-control,
 .form-select {
  border-radius: 8px;
  border: 1px solid #ced4da;
  transition: all 0.3s;
 }

 .form-control:focus,
 .form-select:focus {
  border-color: #3498db;
  box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
 }

 .input-group-text {
  background-color: #f8f9fa;
 }

 .card {
  border-radius: 12px;
  overflow: hidden;
 }

 .table th {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 0.5px;
 }

 .table td {
  vertical-align: middle;
 }

 span.input-group-text {
  border-radius: 0 8px 8px 0 !important;
 }
</style>