<?= view('layout/header.php'); ?>

<?php
$countries = json_decode(file_get_contents(__DIR__ . '/../partials/countries.json'), true);

?>
<div class="container-fluid py-5" style=" background-image: url(<?= base_url('images/uk_new_banner.webp') ?>);  background-position: 265px 25%; background-size: 100%;">
  <div class="container">
    <div class="row align-items-center">

      <!-- LEFT COLUMN -->
      <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
        <h2 class="fw-bold mb-4">UK PARCEL FORWARDING SERVICE</h2>
        <p>
          Shop in the UK and forward your parcels all around the world. Our UK forwarding warehouse lets you shop at the best UK shops like Amazon.co.uk, Nike, eBay, John Lewis and other household names then ship them to you wherever you are.
        </p>
        <p>
          Get your very own UK shipping address and shop like a local, but ship worldwide. As a UK parcel forwarder we have years of experience sending parcels and packages of all shapes and sizes from the UK to Europe, America, Australia, Japan and the rest of the world. Your UK forwarding address is completely free to sign up for.
        </p>
        <a href="<?= base_url('services') ?>" class="btn shippex-btn btn-lg px-4">START SAVING</a>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="col-lg-7 col-md-12 text-center">
        <!-- Background map with US flag -->
        <div class="position-relative">

          <!-- Statue of Liberty overlay -->
          <img src="<?= base_url('images/uk_warehouse_object.webp') ?>"
            class="img-fluid position-absolute top-50 start-50 translate-middle"
            style="max-height: 400px;"
            alt="Statue of Liberty" />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="brands container-fluid py-5">
  <img class="bg-element-1 bg" src="<?= base_url('images/bg-element1.png') ?>" alt="">
  <img class="bg-element-2 bg" src="<?= base_url('images/bg-element2.png') ?>" alt="">
  <!-- LEFT COLUMN -->
  <div class="container">
    <div class="row align-items-center">
      <h3 class="fw-bold mb-4 text-center text-lg-start">Shop Online in the UK and Ship Worldwide</h3>
      <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">

        We offer a package forwarding service that puts the best brands at your fingertips.
        You can buy from the trending brands in beauty, like Rare Beauty or
        <a href="#" class="text-success fw-semibold">Glossier</a> or buy from tiny independent
        sellers on Etsy and eBay who wouldn’t normally offer international shipping.
        Package forwarding services let you buy the latest tech from brands like Apple
        and GameStop or stock up on US fashion from Hollister, Vans, or Nordstrom Rack.
        </p>


      </div>
      <div class="col-lg-6 col-md-12 text-center">
        <p>

          Why not shop tax free? We also offer a tax free UK warehouse to let you shop in the United Kingdom without having to pay the UK’s value added tax (VAT), perfect for particularly high value items.

        </p>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN -->

</div>
</div>
</div>
<div class="container py-5">
  <div class="row d-none d-md-flex mb-5">
    <!-- Shipping Section -->
    <div class="col-12 col-md-6">
      <p class="mb-0 text-success fw-bold text-uppercase" style="font-size:16px;">We ship worldwide with</p>
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
      <p class="mb-0 text-success fw-bold text-uppercase" style="font-size:16px;">Pay with confidence</p>
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

  <!-- calculator -->
  <section class="heros" id="top">
    <div class="hero-wrap wrap">
      <div>
        <h1>Shop Worldwide. <br />Ship Anywhere.</h1>
        <p>Parcel forwarding & Personal Shopper with an <strong>Instant Shipping Calculator</strong>. We buy, receive, <strong>consolidate</strong>, and ship from USA • UK • UAE • China • Oman to 190+ countries.</p>
        <div class="badges">
          <span class="pill">30‑day Free Storage</span>
          <span class="pill">Consolidation & Repack</span>
          <span class="pill">Tracked Delivery</span>
          <span class="pill">VIP Discounts (Beta)</span>
        </div>
        <div class="cta-row">
          <a href="<?= base_url('/shopper') ?>" class="btn btn-outline">Use Personal Shopper</a>
          <a href="#warehouses" class="btn btn-outline">Get Warehouse Address</a>
        </div>
      </div>

      <!-- Calculator Card -->
      <div class="card shadow border-0 overflow-hidden">
        <!-- Card Header with Gradient Background -->
        <div class="card-header py-3">
          <h3 class="fw-bold mb-0">Get Shipping Quote</h3>
        </div>

        <div class="card-body p-2 p-md-3">
          <form id="shippingForm" class="needs-validation" novalidate>
            <!-- Origin Section -->
            <div class="row">

              <div class="col-lg-12">
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

                          <option <?= $ct['code'] === 'UK' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>" ?>
                            <?= esc($ct['name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Please select a country</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
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
                  <label class="input-group-label" for="weight">Weight <small>(kg)</small></label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="weight" name="weight" placeholder="0.00" step="0.01" required>

                    <div class="invalid-feedback">Please enter weight</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="lenght">Length <small>(cm)</small></label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="length" name="length" placeholder="0" required>

                    <div class="invalid-feedback">Please enter length</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="width">Width <small>(cm)</small></label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="width" name="width" placeholder="0" required>

                    <div class="invalid-feedback">Please enter width</div>
                  </div>
                </div>
                <div class="col">
                  <label class="input-group-label" for="height">Height <small>(cm)</small></label>
                  <div class="input-group  ">
                    <input type="number" class="form-control" id="height" name="height" placeholder="0" required>

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

          <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 1055">
            <div id="bookingToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                <div class="toast-body">
                  ✅ Booking confirmed! Your shipment has been saved.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Results Section -->
  <div class="container">
    <div id="ratesResult" class="mt-3">
      <div class="d-none" id="resultsContainer">
        <h4 class="fw-bold mb-4 text-center">Available Shipping Options</h4>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">Courier</th>
                <th scope="col">Service</th>
                <th scope="col">Delivery Time</th>
                <th scope="col">Price</th>
                <th scope="col">Description</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="ratesTableBody">
              <!-- Rates will be inserted here -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="d-none" id="errorContainer">
        <div class="alert alert-danger" role="alert">
          <h5 class="alert-heading">Unable to Calculate Rates</h5>
          <p id="errorMessage"></p>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="row">
    <div class="col-12 text-center">
      <h2 class="fw-bold">Why Use European Parcel Forwarding</h2>
    </div>
  </div>
</div>


<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 text-center">
      <p class="mb-3" style="font-size:16px;">
        Our Europe parcel forwarding service lets you get hold of all kinds of items you never thought you could.
        If you buy goods online from Europe you’ll be able to shop at a wide range of stores and brands that just may not be available where you live.
        Whether you’re after the best in European technology or stylish clothing, we’re here to help.
      </p>
      <p class="mb-4" style="font-size:16px;">
        Get a parcel forwarding address in Europe and shop at
        <a href="https://www.forward2me.com/blog/amazon-germany/">Amazon.de</a>,
        <a href="https://www.forward2me.com/blog/shop-on-ebay-germany/">Ebay.de</a>,
        or from German brands. Get your hands on toys and games from Steiff and Ravensburger,
        <a href="/retailers/hugendubel-de/">books from Hugendubel</a>, and the latest fashion from
        <a href="/retailers/goertz-de/">Görtz</a>.
      </p>
      <a href="https://my.forward2me.com/register/" class="btn btn-primary btn-lg">START SHOPPING NOW</a>
    </div>
  </div>
</div>




<div class="container py-5">
  <div class="row align-items-start">

    <!-- Accordion Section -->
    <div class="col-lg-6 mb-4">
      <h2 class="mb-4">Frequently Asked Questions</h2>
      <?= render_faqs(get_faqs(12), 'shippingFaqs') ?>


      <!-- Read More Button -->
      <div class="mt-4">
        <a href="<?= base_url('faqs') ?>" target="_blank" class="btn btn-primary">Read More</a>
      </div>
    </div>

    <!-- Image Section -->
    <div class="col-lg-6 text-center sticky-top" style="top: 100px;">
      <img src="<?= base_url('images/faq.svg') ?>" alt="FAQ Banner" class="img-fluid">
    </div>

  </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

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
      $("#hs_code_input").val(slug); // update hidden input
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
              html += `
              <tr>
                <td id='curier'>${rate.courier_service?.umbrella_name || '-'}</td>
                <td id='service_name'>${rate.courier_service?.name || '-'}</td>
                <td id='delivery_time'>${rate.min_delivery_time} - ${rate.max_delivery_time} days</td>
                <td id='currency' data-total='${rate.total_charge}' class="text-end fw-bold">${rate.currency} ${rate.total_charge}</td>
                <td id='full_description' class="text-end text-muted">${rate.full_description}</td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary book-btn" 
                  href="javascript:void(0);" 
                  data-courier="${rate.courier_service?.umbrella_name}" 
                  data-service="${rate.courier_service?.name}" 
                  data-delivery="${rate.min_delivery_time} - ${rate.max_delivery_time} days" 
                  data-description="${rate.full_description}" 
                  data-currency="${rate.currency}" 
                  data-charge="${rate.total_charge}">
                  Book
                </a>

                </td>
              </tr>
            `;
            });

            $("#ratesTableBody").html(html);
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

      console.log(payload);
      $.post("<?= site_url('shipping/book') ?>", payload, function(res) {
        if (res.status === "success") {
          $("#bookingToast .toast-body").html("✅ Booking confirmed! ID: <b>" + res.booking_id + "</b>");
          var toastEl = document.getElementById('bookingToast');
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
          // reload page after 5 seconds (5000 ms)
          setTimeout(function() {
            location.reload();
          }, 3000);
        } else {
          showError(res.message);
        }
      }, "json");

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