<?= view('layout/header.php'); ?>

<?php
$countries = json_decode(file_get_contents(__DIR__ . '/partials/countries.json'), true);
?>

<div class="container py-5">
  <div class="row d-none d-md-flex mb-5">
    <!-- Shipping Section -->
    <div class="col-12 col-md-6">
      <p class="mb-0 text-purple fw-bold text-uppercase" style="font-size:16px;">We ship worldwide with</p>
      <div class="row mt-3">
        <div class="col-6 col-md-3 mb-2">
          <img src="<?= base_url('images/FedEx-color.png') ?>"
            alt="FedEx" class="img-fluid logo-img">
        </div>
        <div class="col-6 col-md-3 mb-2">
          <img src="<?= base_url('images/UPS-color.png'); ?>"
            alt="UPS" class="img-fluid logo-img">
        </div>
      </div>
    </div>

    <!-- Payment Section -->
    <div class="col-12 col-md-6 text-end">
      <p class="mb-0 text-purple fw-bold text-uppercase" style="font-size:16px;">Pay with confidence</p>
      <div class="row mt-3 justify-content-end">
        <div class="col-6 col-sm-4 col-md-2 mb-2">
          <img src="<?= base_url('images/PayPal-color.png') ?>"
            alt="PayPal" class="img-fluid logo-img">
        </div>
        <!-- <div class="col-6 col-sm-4 col-md-2 mb-2">
          <img src="<?= base_url('images/visa-mastercard.png') ?>"
            alt="Visa/MasterCard" class="img-fluid logo-img">
        </div> -->

      </div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow border-0 overflow-hidden">
        <!-- Card Header with Gradient Background -->
        <div class="card-header py-3">
          <h3 class="fw-bold mb-0">Get Shipping Quote</h3>
        </div>

        <div class="card-body p-2 p-md-3">
          <form id="shippingForm" class="needs-validation" novalidate>
            <!-- Origin Section -->
            <div class="row">

              <div class="col-lg-6">
                <div class="mb-3">
                  <h4 class="fw-bold mb-3 text-purple d-flex align-items-center">
                    <span class="material-icons-outlined me-2">location_on</span>
                    Origin Address
                  </h4>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <input type="text" class="form-control  " id="origin_line_1" name=" origin_line_1" placeholder="Street Address" required>
                      <div class="invalid-feedback">Please provide a street address</div>
                    </div>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="origin_city" name=" origin_city" placeholder="City" required>
                      <div class="invalid-feedback">Please provide a city</div>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control  " id="origin_state" name="origin_state" placeholder="State/Province">
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control  " id="origin_postal_code" name="origin_postal_code" placeholder="Postal Code">
                    </div>
                    <div class="col-md-4">
                      <select class="form-select" id="dest_country" name="origin_country" required>
                        <option value="">--Country--</option>
                        <?php foreach ($countries as $ct): ?>

                          <option <?= $ct['code'] === 'US' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>" ?>
                            <?= esc($ct['name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Please select a country</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <!-- Destination Section -->
                <div class="mb-3">
                  <h4 class="fw-bold mb-3 text-purple d-flex align-items-center">
                    <span class="material-icons-outlined me-2">place</span>
                    Destination Address
                  </h4>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="dest_line_1" name="dest_line_1" placeholder="Street Address" required>
                      <div class="invalid-feedback">Please provide a street address</div>
                    </div>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="dest_city" name=" dest_city" placeholder="City" required>
                      <div class="invalid-feedback">Please provide a city</div>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" id="dest_state" name="dest_state" placeholder="State/Province">
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" id="dest_postal_code" name="dest_postal_code" placeholder="Postal Code">
                    </div>
                    <div class="col-md-4">

                      <select class="form-select" id="dest_country" name="dest_country" required>
                        <option value="">--Country--</option>
                        <?php foreach ($countries as $ct): ?>

                          <option value="<?= esc($ct['code']) ?>" ?>
                            <?= esc($ct['name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Please select a country</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Parcel Details Section -->
            <div class="mb-4">
              <h4 class="fw-bold mb-4 text-purple d-flex align-items-center">
                <span class="material-icons-outlined me-2">inventory</span>
                Parcel Details
              </h4>
              <div class="row g-3">
                <div class="col">
                  <label for="category" class="input-group-label">Category</label>
                  <select class="form-select" required name="category" id="category_slug">
                    <option value="" selected>--select category--</option>
                    <?php foreach ($categories as $cat): ?>
                      <option data-hs-code="<?= $cat['hs_code'] ?>" value="<?= $cat['slug'] ?>">
                        <?= $cat['name'] ?>
                      </option>
                    <?php endforeach; ?>
                    <div class="invalid-feedback">Please enter weight</div>
                  </select>
                </div>
                <input type="hidden" name="hs_code" id="hs_code_input">

                <div class="col">
                  <label class="input-group-label" for="weight">Weight</label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="weight" name="weight" placeholder="0.00" step="0.01" required>
                    <span class="input-group-text">kg</span>
                    <div class="invalid-feedback">Please enter weight</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="lenght">Length</label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="length" name="length" placeholder="0" required>
                    <span class="input-group-text">cm</span>
                    <div class="invalid-feedback">Please enter length</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="width">Width</label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="width" name="width" placeholder="0" required>
                    <span class="input-group-text">cm</span>
                    <div class="invalid-feedback">Please enter width</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="height">Height</label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="height" name="height" placeholder="0" required>
                    <span class="input-group-text">cm</span>
                    <div class="invalid-feedback">Please enter height</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid mt-4">
              <button type="submit" class="btn bg-shippex-purple text-white py-3">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                Calculate Shipping Rates
              </button>
            </div>

          </form>



        </div>
      </div>
    </div>

    <!-- Results Section -->
    <div class="container" style="position: relative;">
      <div id="ratesResult" class="mt-3">


        <div class="d-none" id="errorContainer">
          <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">Unable to Calculate Rates</h5>
            <p id="errorMessage"></p>

          </div>
        </div>

        <!-- Loader for booking -->
        <div id="bookingLoader" class="d-none text-center my-3" style="position: absolute; top:50%; left:50%; z-index:99999;">
          <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Booking...</span>
          </div>
          <p class="mt-2">Processing your booking...</p>
        </div>
      </div>
      <div id="rateContainer" class="mt-3"></div>
    </div>
    <div class="col-lg-6">
      <div class="container my-5">
        <div class="row">
          <div class="col-lg-12">
            <h2 class="text-uppercase fw-bold mb-3 d-block" style="font-size:24px;">Your Benefits</h2>
            <img width="200px" src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/common/separators/title-2.svg" alt="Separator" class=" mb-4">
          </div>
          <div class="col-12 col-md-6">

            <!-- Benefit Item -->
            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="<?= base_url('icons/inventory.svg') ?>" alt="Free Storage" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">30 DAYS FREE STORAGE</p>
            </div>

            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/PPC_PMG_n.svg" alt="Price Match Guarantee" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">PRICE MATCH GUARANTEE</p>
            </div>

            <div class="d-flex align-items-center">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/SiM Return without hex.svg" alt="Free Return To Sender" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">FREE RETURN TO SENDER</p>
            </div>
          </div>

          <div class="col-12 col-md-6 mt-4 mt-md-0">
            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/SiM Combine and repack without hex.svg" alt="Combine and Repack" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">COMBINE AND REPACK</p>
            </div>

            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/Hand_n.svg" alt="GST Free" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">GST FREE</p>
            </div>

            <div class="d-flex align-items-center">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/Call_edit.svg" alt="Friendly Support" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">INDUSTRY LEADING SUPPORT</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

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

      // Show loading state
      $("button[type='submit']").prop('disabled', true);
      $("button[type='submit'] .spinner-border").removeClass('d-none');

      // Show loading indicator
      $("#resultsContainer").addClass('d-none');
      $("#errorContainer").addClass('d-none');

      $.ajax({
        url: "<?= site_url('shipping/getRates') ?>",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res) {
          if (res.rates && res.rates.length > 0) {
            let html = '';
            res.rates.forEach(function(rate) {
              const svgPlaceholder = "data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2245%22%20height%3D%2245%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%232b6cb0%22%20stroke-width%3D%221.5%22%3E%3Crect%20x%3D%222%22%20y%3D%227%22%20width%3D%2220%22%20height%3D%2212%22%20rx%3D%222%22%20fill%3D%22%23e6f2ff%22%20/%3E%3Cpath%20d%3D%22M12%203v4%22/%3E%3Cpath%20d%3D%22M7%207l5%204%205-4%22/%3E%3C/svg%3E";

              // Tracking rating
              let trackingIcons = '';
              for (let i = 0; i < 5; i++) {
                trackingIcons += `<span style="color:${i < rate.tracking_rating ? '#00c853' : '#ccc'};">●</span>`;
              }

              // Service options with simple icons
              let serviceOptions = '';

              if (rate.available_handover_options && rate.available_handover_options.length > 0) {
                serviceOptions = rate.available_handover_options.map(opt => {
                  let icon = '';
                  let label = opt.replace(/_/g, ' '); // replace underscores with spaces
                  label = label.charAt(0).toUpperCase() + label.slice(1); // capitalize first letter

                  if (opt === 'dropoff') icon = '📦'; // dropoff icon
                  if (opt === 'paid_pickup') icon = '🏠'; // pickup icon

                  // each option in a new line
                  return `<div>${icon} ${label}</div>`;
                }).join('');
              } else {
                serviceOptions = '-';
              }


              html += `
                <div class="card shadow-sm mb-3 p-3 rounded-3">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <img src="${rate.courier_service?.logo || svgPlaceholder}" 
                          alt="logo" class="me-3" width="45" height="45">
                      <div>
                        <h6 class="mb-0 fw-bold">${rate.courier_service?.umbrella_name || '-'}</h6>
                        <small class="text-muted">${rate.courier_service?.name || '-'}</small>
                      </div>
                    </div>
                    <div class="text-end">
                      <h5 class="mb-0 fw-bold">${rate.currency} ${rate.total_charge.toFixed(2)}</h5>
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
                      Tax: ${rate.estimated_import_tax || 0}, Duty: ${rate.estimated_import_duty || 0}
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
                        data-charge="${rate.total_charge}">
                        Book
                      </a>
                    </div>
                  </div>
                </div>
              `;
            });

            $("#rateContainer").html(html);
            $("#resultsContainer").removeClass('d-none');

          } else {
            showError("No shipping rates available for your destination.");
          }


        },
        error: function(xhr) {
          console.log(xhr);
          let errorMsg = "An error occurred while calculating rates.";
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
          showError(errorMsg);
        },
        complete: function() {
          $("button[type='submit']").prop('disabled', false);
          $("button[type='submit'] .spinner-border").addClass('d-none');
        }
      });
    });

    function showError(message) {
      $("#loadingIndicator").addClass('d-none');
      $("#errorMessage").html(message);
      $("#errorContainer").removeClass('d-none');
    }

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
        value: $("#hs_code_input").val()
      });

      // Show loader
      $("#bookingLoader").removeClass("d-none");
      $(".book-btn").prop("disabled", true); // disable all booking buttons

      $.post("<?= site_url('shipping/book') ?>", payload, function(res) {
        // Hide loader before showing SweetAlert
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
<?= view('layout/footer.php'); ?>