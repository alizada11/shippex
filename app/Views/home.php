<?php include('layout/header.php'); ?>
<?php
$countries = json_decode(file_get_contents(__DIR__ . '/partials/countries.json'), true);

?>
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('<?= base_url($hero['background_image'] ?? 'images/default-bg.jpg') ?>');">
  <div class="overlay"></div>
  <div class="container text-white hero-content">
    <div class=" row">
      <div class="col-md-8">
        <h1 class="display-4 fw-bold"><?= esc($hero['title']) ?></h1>
        <h4 class="mt-3"><?= esc($hero['subtitle']) ?></h4>
        <p class="lead mt-3">
          <?= esc($hero['description']) ?>
        </p>
        <a href="<?= esc($hero['button_link']) ?>" class="btn btn-start text-white mt-4">
          <?= esc($hero['button_text']) ?>
        </a>
      </div>
    </div>

    <div class="row  mt-sm-5 mt-md-5 mt-lg-3 mt-xl-5 mb-xl-0">
      <!-- Left column -->
      <div class="col-5 col-sm-5 col-lg-5 col-xl-5 shipment-col">
        <div>
          <p class="mb-0 text-white fw-bold fs-6">WE SHIP WORLDWIDE</p>
        </div>
        <div class="container-fluid px-0">
          <div class="row">
            <div class="col-12">
              <!-- Show on md+ -->
              <img alt="Image" class="img-fluid d-none d-md-inline-block"
                src="<?= base_url('images/Header-logos-horizontal-ship-white.webp') ?>">

              <!-- Show on lg and smaller (hide xl) -->
              <img alt="Image" class="img-fluid d-xl-none d-lg-none"
                src="<?= base_url('images/Header-logos-vertical-ship-white.webp') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Middle column (button) -->
      <div class="col-3 col-sm-3 col-lg-2 col-xl-1 text-center">
        <a href="#how-it-works" class="btn animated-arrow mt-lg-3 mt-xl-5">
          <svg class="mt-1 svg-inline--fa fa-chevron-down fa-w-14 icon-arrow-jump" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
            <path fill="#ff6600" d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
          </svg>
        </a>
      </div>

      <!-- Right column -->
      <div class="col-4 col-md-4 col-lg-5 col-xl-6 payment-col">
        <div>
          <p class="mb-0 text-end text-white fw-bold fs-6">PAY WITH CONFIDENCE</p>
        </div>
        <div class="container-fluid px-0">
          <div class="row">
            <div class="col-12 text-end">
              <!-- Show on md+ -->
              <img alt="Image" class="img-fluid d-none d-md-inline-block"
                src="<?= base_url('images/Header-logos-horizontal-pay-white-2.webp') ?>">

              <!-- Show on lg and smaller (hide xl) -->
              <img alt="Image" class="img-fluid d-xl-none d-lg-none"
                src="<?= base_url('images/Header-logos-vertical-pay-white-2.webp') ?>">
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  </div>
</section>
<!-- calculator -->
<section class="hero" id="top">
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
<!-- How it works -->
<section id="how-it-works" class="how-it-works py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-3">How Parcel Forwarding Works</h2>
      <p class="lead text-muted">Think parcel forwarding means more steps? We make them fewer...</p>
    </div>

    <div class="row g-4">
      <?php foreach ($steps as $step): ?>
        <div class="col-md-6 col-lg-3">
          <div class="h-100 p-3">
            <div class="d-flex align-items-start mb-3">
              <?php if ($step['icon']): ?>
                <img src="<?= base_url($step['icon']) ?>" alt="Step <?= esc($step['step_number']) ?>" class="me-3" width="40">
              <?php endif; ?>
              <div>
                <p class="text-uppercase small text-muted mb-0"><?= esc($step['subtitle']) ?></p>
                <h4 class="text-shippex-primary fw-bold mb-0"><?= esc($step['title']) ?></h4>
              </div>
            </div>
            <p><?= esc($step['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Where are we located -->
<section id="where-are-we" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-3">Where Are We Located?</h2>
      <p class="lead text-muted">
        We give you access to the world's top shopping destinations...
      </p>
    </div>

    <div class="row g-4">
      <?php foreach ($locations as $loc): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <?php if ($loc['status'] === 'coming_soon'): ?>
            <div class="coming-soon-card">
              <div class="hexagon-logo">
                <img src="<?= base_url('uploads/' . $loc['flag_image']) ?>" alt="<?= esc($loc['name']) ?>">
              </div>
              <div class="coming-soon-content">
                <h3>More<br><?= esc($loc['name']) ?></h3>
              </div>
            </div>
          <?php else: ?>
            <a href="<?= esc($loc['link']) ?>" class="location-card">
              <div class="hexagon-flag">
                <img src="<?= base_url('uploads/' . $loc['flag_image']) ?>" alt="<?= esc($loc['name']) ?>">
              </div>
              <div class="location-content">
                <span class="location-label">WAREHOUSE</span>
                <h3 class="location-name"><?= esc($loc['name']) ?>
                  <img src="<?= base_url('/icons/arrow.png') ?>" alt="arrow">
                </h3>
                <?php if ($loc['location_info']): ?>
                  <p class="location-info"><?= esc($loc['location_info']) ?></p>
                <?php endif; ?>
              </div>
              <img src="<?= base_url('uploads/' . $loc['thumbnail_image']) ?>" alt="<?= esc($loc['name']) ?>" class="location-image">
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- deliverd today -->

<div class="delivered-today-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Text -->
      <div class="col-md-4 mb-4 mb-md-0">
        <h2 class="text-white mb-3">DELIVERED TODAY</h2>
        <p class="text-light">Recent shipments from our customers around the world.</p>

        <!-- Desktop Nav -->
        <div class="d-none d-md-flex mt-4">
          <button class="carousel-prev btn btn-link text-white mr-3">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-next btn btn-link text-white">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>

      <!-- Carousel -->
      <div class="col-md-8">
        <div class="owl-carousel owl-theme delivered-carousel">
          <?php foreach ($items as $item): ?>
            <div class="item">
              <div class="card bg-white rounded-0 p-3 h-100">
                <div class="row align-items-center mb-3">
                  <div class="col-6">
                    <div class="courier-logo" style="background-image: url('<?= base_url($item['courier_logo']) ?>')"></div>
                  </div>
                  <div class="col-6 ">
                    <img src="<?= base_url($item['retailer_logo']) ?>" alt="Retailer" class="retailer-logo">
                  </div>
                </div>

                <div class="hexagon-wrapper mb-3">
                  <div class="hexagon-shape mx-auto">
                    <div class="hexagon-shape-inner">
                      <i class="<?= esc($item['icon']) ?> "></i>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-6">
                    <div class="d-flex align-items-end mb-1 justify-content-start gap-2">
                      <span class="text-muted small mr-2">From</span>
                      <img src="<?= base_url($item['from_flag']) ?>" alt="<?= esc($item['from_country']) ?>" class="country-flag">
                    </div>
                    <span data-bs-toggle="tooltip" title="<?= esc($item['to_country']) ?>" class="country-name"><?= esc($item['from_country']) ?></span>
                  </div>
                  <div class="col-6 text-end">
                    <div class="d-flex align-items-end mb-1 justify-content-end gap-2">
                      <span class="text-muted small mr-2">To</span>
                      <img src="<?= base_url($item['to_flag']) ?>" alt="<?= esc($item['to_country']) ?>" class="country-flag">
                    </div>
                    <span data-bs-toggle="tooltip" title="<?= esc($item['to_country']) ?>" class="country-name"><?= esc($item['to_country']) ?></span>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-6">
                    <span class="text-muted small">Cost</span>
                    <div class="metric"><?= esc($item['cost']) ?></div>
                  </div>
                  <div class="col-6 text-end">
                    <span class="text-muted small">Weight</span>
                    <div class="metric"><?= esc($item['weight']) ?></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Mobile Nav -->
        <div class="d-flex d-md-none mt-3">
          <button class="carousel-prev btn btn-link text-white mr-auto">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-next btn btn-link text-white">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Promo section -->

<section class="promo-section py-5">
  <div class="container">
    <div class="row">

      <?php

      foreach ($promoCards as $card): ?>
        <div class="col-lg-6 mb-4">
          <div class="promo-card <?= strtolower(str_replace(' ', '-', $card['title'])) ?>-card"
            style="background-image: url('<?= base_url('uploads/promo_cards/' . $card['background']) ?>')">


            <div class="promo-content">
              <h2><?= esc($card['title']) ?></h2>
              <p><?= esc($card['description']) ?></p>
              <div class="promo-footer">
                <a href="<?= esc($card['button_url']) ?>" class="btn promo-btn"><?= esc($card['button_text']) ?></a>
                <?php if ($card['image']): ?>
                  <img src="<?= base_url('uploads/promo_cards/' . $card['image']) ?>"
                    alt="<?= esc($card['title']) ?> Logo" class="promo-logo">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

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


<?php include('layout/footer.php'); ?>