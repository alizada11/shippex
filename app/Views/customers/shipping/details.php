<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>

<?php
$session = session();
$role = $session->get('role');
?>
<div class="container">
  <!-- Stats Overview -->
  <div class="row">

    <?php
    // Sample data - replace with your actual data
    $shipment = [
      'status' => $request['status'],
      'created_at' => $request['created_at'],
      'destination' => $request['origin_country'],
      'tracking_number' => $request['id']
    ];
    // For debugging

    // Calculate dynamic values
    $progress = calculate_shipment_progress($shipment['status']);
    $progress_message = get_progress_message($shipment['status']);
    $progress_color = get_progress_color($shipment['status']);
    $estimated_delivery = calculate_estimated_delivery(
      $shipment['status'],
      $shipment['created_at'],
      $shipment['destination']
    );
    ?>

    <div class="container">
      <!-- Status Card -->
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-12">

              <div class="d-flex justify-content-between ">
                <h4 class="d-inline mb-1">Package Status: <?= statusBadge($request['status']) ?> </h4>
                <div class="d-flex gap-3 align-items-center">
                  <?php if (isset($request['set_rate']) && $request['set_rate'] == 0): ?>
                    <a class="btn btn-shippex-orange" id="viewRates" data-bs-toggle="modal"
                      data-bs-target="#viewRatesModal"
                      style="cursor: pointer;"
                      title="Click to view payment details">
                      <i class="fas fa-eye"></i>
                      View Shipping Prices
                    </a>
                  <?php endif; ?>


                  <?php
                  // Get payment info JSON from request
                  $paymentTableHtml = '';
                  if ($request['payment_status'] && $request['payment_status'] === 'paid') {

                    // Decode JSON safely
                    $paymentJson = html_entity_decode($request['payment_info']); // convert &quot; to "
                    $payment = json_decode($paymentJson, true); // decode as associative array

                    if ($payment && is_array($payment)) {
                      ob_start(); // start output buffering to capture table HTML
                  ?>
                      <table class="table table-bordered table-sm">
                        <tr>
                          <th>Payment ID</th>
                          <td><?= esc($payment['id'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Intent</th>
                          <td><?= esc($payment['intent'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Status</th>
                          <td><?= esc($payment['status'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Payer Name</th>
                          <td>
                            <?= esc($payment['payment_source']['paypal']['name']['given_name'] ?? '-') ?>
                            <?= esc($payment['payment_source']['paypal']['name']['surname'] ?? '-') ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Payer Email</th>
                          <td><?= esc($payment['payment_source']['paypal']['email_address'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Payer Account ID</th>
                          <td><?= esc($payment['payment_source']['paypal']['account_id'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Account Status</th>
                          <td><?= esc($payment['payment_source']['paypal']['account_status'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Country</th>
                          <td><?= esc($payment['payment_source']['paypal']['address']['country_code'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Amount</th>
                          <td>
                            <?= esc($payment['purchase_units'][0]['amount']['currency_code'] ?? '-') ?>
                            <?= esc($payment['purchase_units'][0]['amount']['value'] ?? '-') ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Payee Email</th>
                          <td><?= esc($payment['purchase_units'][0]['payee']['email_address'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Merchant ID</th>
                          <td><?= esc($payment['purchase_units'][0]['payee']['merchant_id'] ?? '-') ?></td>
                        </tr>
                        <tr>
                          <th>Soft Descriptor</th>
                          <td><?= esc($payment['purchase_units'][0]['soft_descriptor'] ?? '-') ?></td>
                        </tr>
                      </table>
                  <?php
                      $paymentTableHtml = ob_get_clean();
                    }
                  }
                  ?>

                  <?php if (!empty($paymentTableHtml)): ?>
                    <!-- Font Awesome info icon -->
                    <p>
                      <strong>Payment Status:</strong> <?= esc($request['payment_status']) ?>
                      <i class="fas fa-info-circle text-info"
                        data-bs-toggle="modal"
                        data-bs-target="#paymentInfoModal"
                        style="cursor: pointer;"
                        title="Click to view payment details"></i>
                    </p>


                  <?php endif; ?>

                  <a onclick="downloadCurrentPage({
                      filename: 'shipping_request',
                      title: 'Shipping request details',
                      format: 'html'
                  })"
                    href=" #" class="">
                    <i class="fas fa-print me-2"></i>
                  </a>

                </div>

              </div>
            </div>
            <!-- Dynamic Progress Section -->

            <div class="card-body">
              <!-- Estimated Delivery -->
              <div class="mb-3">
                <p class="mb-2">
                  <i class="fas fa-calendar-alt me-2 text-shippex-primary"></i>
                  Estimated Delivery: <strong><?= $estimated_delivery ?></strong>
                </p>
              </div>

              <!-- Progress Bar -->
              <div class="progress tracking-progress mb-2" style="height: 8px;">
                <div class="progress-bar <?= $progress_color ?> progress-bar-striped progress-bar-animated"
                  role="progressbar"
                  style="width: <?= $progress ?>%"
                  aria-valuenow="<?= $progress ?>"
                  aria-valuemin="0"
                  aria-valuemax="100">
                </div>
              </div>

              <!-- Progress Text -->
              <p class="text-muted small mb-0">
                <i class="fas fa-info-circle me-1"></i>
                <?= $progress ?>% complete - <?= $progress_message ?>
              </p>

              <!-- Current Status Badge -->
              <div class="mt-3 d-flex gap-4">
                <span class="badge <?= $progress_color ?> text-white">
                  <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                  <?= ucfirst($shipment['status']) ?>
                </span>

              </div>
            </div>



          </div>
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="paymentInfoModal" tabindex="-1" aria-labelledby="paymentInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="paymentInfoModalLabel">Payment Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?= $paymentTableHtml ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Rates Prevview Modal -->
      <div class="modal fade rounded-xl" id="viewRatesModal" tabindex="-1" aria-labelledby="viewRatesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header card-header flex-column align-items-start">
              <div class="w-100 d-flex justify-content-between align-items-start">
                <div>
                  <h4 class="modal-title mb-1" id="viewRatesModalLabel">Available Rates and Courier Services</h4>
                  <small class="text-white-50">Select one of the services below to ship your request.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>

            <div class="modal-body">


              <div id="ratePreviewTable" style="max-height:500px; overflow:auto">

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
          <!-- Address Information -->
          <div class="row">
            <div class="col-md-6">
              <div class="card address-card">
                <div class="card-header">
                  <i class="fas fa-map-marker-alt me-2"></i>Origin Address
                </div>
                <div class="card-body">

                  <span class="text-secondary">Street Line:</span>
                  <p class="mb-1"><strong><?= $request['origin_line_1'] ?></strong></p>

                  <span class="text-secondary">City:</span>
                  <p class="mb-1"><?= $request['origin_city'] ?></p>
                  <span class="text-secondary">Postal Code:</span>
                  <p class="mb-1"><?= $request['origin_postal'] ?></p>
                  <span class="text-secondary">Country:</span>
                  <p class="mb-1"><?= $request['origin_country'] ?></p>

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card address-card">
                <div class="card-header">
                  <i class="fas fa-flag me-2"></i>Destination Address
                </div>
                <div class="card-body">
                  <span class="text-secondary">Street Line:</span>
                  <p class="mb-1"><strong><?= $request['dest_line_1'] ?></strong></p>
                  <span class="text-secondary">City:</span>
                  <p class="mb-1"><?= $request['dest_city'] ?></p>
                  <span class="text-secondary">Postal Code:</span>
                  <p class="mb-1"><?= $request['dest_postal'] ?></p>
                  <span class="text-secondary">Country:</span>
                  <p class="mb-1"><?= $request['dest_country'] ?></p>

                </div>
              </div>
            </div>
          </div>

          <!-- Package Details -->
          <div class="card">
            <div class="card-header">
              <i class="fas fa-box me-2"></i>Package Details
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-item">
                    <span class="detail-label">Category:</span>
                    <span class="float-end" style="text-transform: capitalize;">
                      <?= ucwords(str_replace('_', ', ', $request['category'])) ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Description:</span>
                    <span class="float-end"><?= $request['description'] ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Weight:</span>
                    <span class="float-end"><?= $request['weight'] ?> <small>kg</small></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="detail-item">
                    <span class="detail-label">Dimensions:</span>
                    <span class="float-end"><?= $request['height'] ?> × <?= $request['width'] ?> × <?= $request['length'] ?> cm</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">User Fullname:</span>
                    <span class="float-end"><?= fullname($request['user_id']) ?>
                    </span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Booking Date:</span>
                    <span class="float-end"><?= $request['created_at'] ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <i class="fas fa-info-circle me-2"></i>Shipping Information
            </div>
            <div class="card-body">
              <div class="detail-item">
                <span class="detail-label">Courier:</span>
                <span class="float-end"><?= ($request['courier_name']) ? $request['courier_name'] : 'N/A'  ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Service:</span>
                <span class="float-end"><?= ($request['service_name']) ? $request['service_name'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Delivery Time:</span>
                <span class="float-end"><?= ($request['delivery_time']) ? $request['delivery_time'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Total Charge:</span>
                <span class="float-end fw-bold text-success"><?= ($request['total_charge']) ? $request['currency'] . ' ' . $request['total_charge'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Tax & Duty:</span>
                <span class="float-end fw-bold text-success"><?= ($request['tax_duty']) ? $request['currency'] . ' ' . $request['tax_duty'] : 'N/A' ?></span>
              </div>
              <hr>
              <div class="detail-item">
                <span class="detail-label">Customs value:</span>
                <span class="float-end fw-bold text-success"><?= ($request['declared_customs_value']) ? $request['declared_currency'] . ' ' . $request['declared_customs_value'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Insurance:</span>
                <span class="float-end fw-bold text-success"><?= ($request['is_insured'] == 1) ?  'Yes' : 'No' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Insurance Ammount:</span>
                <span class="float-end fw-bold text-success"><?= ($request['insured_amount']) ?  $request['insured_amount'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Incoterms:</span>
                <span class="float-end fw-bold text-success"><?= ($request['incoterms']) ?  $request['incoterms'] : 'N/A' ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Residential Add:</span>
                <span class="float-end fw-bold text-success"><?= ($request['set_as_residential'] == 1) ?  'Yes' : 'No' ?></span>
              </div>
              <hr>
              <div class="detail-item">
                <span class="detail-label">Tracking Number:</span>
                <span class="float-end"><?= $request['id'] ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Reference ID:</span>
                <span class="float-end"><?= $request['id'] ?></span>
              </div>
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
          <?php if (!empty($request['label'])): ?>
            <div class="card">
              <div class="card-header">
                <i class="fas fa-tag me-2"></i>Label Information
              </div>
              <div class="card-body">

                <div class="text-center ">
                  <img src="<?= base_url('images/labels/') . $request['label'] ?>" alt="" height="120px" width="auto">
                  <br>
                </div>
                <div class="row d-flex justify-content-center gap-2">
                  <a class="btn btn-action view" href="<?= base_url('images/labels/' . $request['label']) ?>" target="_blank"><i class="fas fa-eye"></i></a>
                  <a class="btn btn-action edit" href="<?= base_url('images/labels/' . $request['label']) ?>" download=""><i class="fas fa-download"></i></a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!-- Shipping Information -->

          <?php if ($request['status'] == "accepted" && $request['purchase_invoice'] !== null): ?>
            <div class="card noprint">
              <div class="card-header">
                <i class="fas fa-pay-circle me-2"></i>Payment
              </div>
              <div class="card-body">
                <div class="detail-item">
                  <span class="detail-label">Request ID</span>
                  <span class="float-end"> <?= $request['id'] ?></span>

                </div>
                <div class="detail-item">
                  <span class="detail-label">Amount to pay:</span>
                  <span class="float-end fw-bold text-success"> $<?= $request['total_charge'] ?></span>

                </div>

                <!-- PayPal Buttons -->
                <div id="paypal-button-container"></div>

                <script src="https://www.paypal.com/sdk/js?client-id=AR_PoU6NaXw2h4y9qwFGoYMBMpw9_I0AzvNGSARRucV84VoZA_x1OHH9781pe1E4rdiW7uvr7st4lX4j&currency=USD"></script>
                <script>
                  paypal.Buttons({
                    createOrder: function(data, actions) {
                      return fetch('<?= base_url("shipping/createOrder/" . $request['id']) ?>', {
                        method: 'post'
                      }).then(res => res.json()).then(order => order.id);
                    },
                    onApprove: function(data, actions) {
                      return fetch('<?= base_url("shipping/captureOrder/" . $request['id']) ?>', {
                        method: 'post',
                        headers: {
                          'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'orderID=' + data.orderID
                      }).then(res => res.json()).then(details => {
                        alert('Payment Completed!');
                        location.reload();
                      });
                    }
                  }).render('#paypal-button-container');
                </script>
              </div>
            </div>
          <?php endif; ?>

          <div class="card" id="purchaseInvoice">
            <div class="card-header">
              <i class="fas fa-info-circle me-2"></i>Purchase Invoic
            </div>
            <div class="card-body">
              <?php if (empty($request['purchase_invoice'])): ?>
                <form action="<?= base_url('shipping/updateInvoice/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="purchase_invoice" class="form-label">Upload the purchase invoice for your payment to be accepted.</label>
                    <input type="file" name="purchase_invoice" id="purchase_invoice" class="form-control" required>
                  </div>
                  <button type="submit" class="btn shippex-btn">Upload Invoice</button>
                </form>
              <?php else: ?>
                <div class="text-center ">
                  <img src="<?= base_url('images/invoices/') . $request['purchase_invoice'] ?>" alt="" height="120px" width="auto">
                </div>
                <div class="d-flex justify-content-center mt-3 gap-3">

                  <a class="btn btn-action view" href="<?= base_url('images/invoices/' . $request['purchase_invoice']) ?>" target="_blank"><i class="fas fa-eye"></i></a>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <!-- Shipping Timeline -->
          <div class="card">
            <div class="card-header">
              <i class="fas fa-history me-2"></i>Shipping Timeline
            </div>
            <div class="card-body">
              <div class="timeline">
                <?php

                use CodeIgniter\Database\BaseUtils;

                if (!$history): ?>
                  <div class="timeline-item completed">
                    <h6 class="mb-1"><?= $request['status'] ?> </h6>
                    <p class="text-muted mb-0"><?= $request['created_at'] ?> </p>
                  </div>
                <?php else: ?>
                  <?php foreach ($history as $hist): ?>
                    <div class="timeline-item completed">
                      <h6 class="mb-1"><?= $hist['new_status'] ?> </h6>
                      <p class="text-muted mb-0"><?= $hist['changed_at'] ?> </p>
                    </div>
                <?php endforeach;
                endif;
                ?>

              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
  <?= $this->section('script') ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const statusOptions = document.querySelectorAll('.status-option');
      let selectedStatus = '<?= $request['status'] ?>'; // Default selected status

      // Set initial selected status
      document.querySelector(`.status-option[data-status="${selectedStatus}"]`).classList.add('selected');

      // Add click event to status options
      statusOptions.forEach(option => {
        option.addEventListener('click', function() {
          // Remove selected class from all options
          statusOptions.forEach(opt => opt.classList.remove('selected'));

          // Add selected class to clicked option
          this.classList.add('selected');

          // Update selected status
          selectedStatus = this.getAttribute('data-status');
        });
      });

      // Update status button click event
      document.getElementById('updateStatusBtn').addEventListener('click', function() {
        // Get the booking ID (you'll need to set this value appropriately)
        const bookingId = <?= $request['id'] ?? 'null' ?>; // Replace with your actual booking ID

        if (!bookingId) {
          alert('Error: Booking ID not found');
          return;
        }

        // Show loading state
        const updateBtn = document.getElementById('updateStatusBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        updateBtn.disabled = true;

        // Send AJAX request to update status
        fetch(`/shipping/update-status/${bookingId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              new_status: selectedStatus,
              // Include CSRF token if needed
              // '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Update current status display
              const statusDisplay = document.getElementById('currentStatusDisplay');
              statusDisplay.textContent = selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1);
              statusDisplay.className = 'status-badge';
              statusDisplay.classList.add(`status-${selectedStatus}`);

              // Update status history
              const historyContainer = document.querySelector('.status-history');
              let historyHtml = '<h6 class="mb-3"><i class="fas fa-history me-2"></i>Status History</h6>';

              data.history.forEach(item => {
                // Format date nicely
                const date = new Date(item.changed_at);
                const formattedDate = date.toLocaleString('en-US', {
                  month: 'short',
                  day: 'numeric',
                  year: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
                });

                historyHtml += `
                <div class="history-item">
                    <span class="history-status status-${item.new_status}">${item.new_status.charAt(0).toUpperCase() + item.new_status.slice(1)}</span>
                    <span class="history-date">${formattedDate}</span>
                </div>
            `;
              });

              historyContainer.innerHTML = historyHtml;

              // Show success message
              window.location.reload();
            } else {
              throw new Error(data.message || 'Unknown error occurred');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error updating status: ' + error.message);
          })
          .finally(() => {
            // Restore button state
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
          });
      });
    });

    document.getElementById('viewRates').addEventListener('click', async function() {
      const id = "<?= htmlspecialchars($request['id'], ENT_QUOTES) ?>";
      const tableDiv = document.getElementById('ratePreviewTable');

      async function loadShippingServices() {
        // Show Bootstrap loading spinner
        tableDiv.innerHTML = `
            <div class="d-flex justify-content-center py-5">
                <div class="spinner-border text-primary me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="fw-bold align-self-center">Loading...</span>
            </div>
            `;

        try {
          const res = await fetch(`/shipping-services/get_all/${id}`, {
            headers: {
              "X-Requested-With": "XMLHttpRequest",
              "Accept": "application/json"
            }
          });
          const json = await res.json();

          if (json.status !== 'ok') {
            tableDiv.innerHTML = '<p class="text-center text-danger">Error loading records</p>';
            return;
          }

          const data = json.data;

          if (!data.length) {
            tableDiv.innerHTML = '<p class="text-center">No shipping services found.</p>';
            return;
          }

          // Function to generate star rating HTML
          function generateStarRating(rating) {
            if (!rating) return 'N/A';

            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            let starsHtml = '';

            for (let i = 0; i < fullStars; i++) starsHtml += '<i class="fas fa-star text-warning"></i>';
            if (hasHalfStar) starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
            const emptyStars = 5 - Math.ceil(rating);
            for (let i = 0; i < emptyStars; i++) starsHtml += '<i class="far fa-star text-warning"></i>';

            return starsHtml;
          }

          // Function to format features
          function formatFeatures(featuresObj) {
            if (!featuresObj) return 'Features not available';

            const featuresHtml = [];
            if (featuresObj.tracking) featuresHtml.push(`Tracking: ${featuresObj.tracking}`);
            if (featuresObj.insurance) featuresHtml.push(`Insurance: ${featuresObj.insurance}`);
            if (featuresObj.multi_piece) featuresHtml.push(`Multi-piece: ${featuresObj.multi_piece}`);
            if (featuresObj.combine_and_repack) featuresHtml.push(`Combine and Repack: ${featuresObj.combine_and_repack}`);

            return featuresHtml.join('<br>') || 'Features not available';
          }

          // Generate HTML for the rates display
          let html = '';
          data.forEach(row => {
            html += `
            
                    <div style="cursor:pointer" class="pricing-service mb-4 border rounded"  data-service-id="${row.id}" data-service-request-id="${row.request_id}"  data-service="${row.service_name}">
                        <div class="prices py-4">
                            <div class="container">
                            <div class="row align-items-center h-100">
                            <div class="col-sm-3 my-auto">
                            <div class="service-logo text-center d-flex flex-column align-items-center">
                            <img src="${row.provider_logo}" class="me-3" width="140" alt="${row.provider_name}">
                            <span class="service-name text-center fw-bold">${row.provider_name} ${row.service_name}</span>
                            </div>
                            </div>
                            <div class="col-sm-6 text-center">
                            <div class="service-price mb-2">
                            <span class="h4 fw-bold">${row.currency} ${row.price}</span>
                            <small class="text-muted">+ VAT</small>
                            </div>
                            <div class="service-info-rating mb-2">
                            <strong>Ratings:</strong>
                            <span class="ms-2">${generateStarRating(row.rating)}</span>
                            </div>
                            <div class="service-info-transit-time">
                                            <strong>Transit Time:</strong>
                                            <span class="ms-2">${row.transit_text} (${row.transit_days} days)</span>
                                            </div>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                    <div class="service-details small">
                                    ${formatFeatures(row.features)}
                                    </div>
                                    </div>
                                    </div>
                                    <div class="row d-block d-sm-none mt-3">
                                    <div class="col-8 m-auto">
                                    <button type="button" class="btn btn-block btn-danger lock-prices-button-btn" aria-label="lock-prices" data-lock-prices="${row.quote_key}">
                                    Lock Prices
                                        </button>
                                        </div>
                                </div>
                                </div>
                                </div>
                                </div>
                    `;
          });

          tableDiv.innerHTML = html;

        } catch (error) {
          console.error('Error fetching shipping services:', error);
          tableDiv.innerHTML = '<p class="text-center text-danger">Error fetching data</p>';
        }
      }

      loadShippingServices();

      tableDiv.addEventListener('click', function(e) {
        const row = e.target.closest('.pricing-service');
        if (!row) return;

        const serviceId = row.dataset.serviceId;
        const requestId = row.dataset.serviceRequestId;

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to proceed with this service?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, confirm!',
          cancelButtonText: 'Cancel'
        }).then(result => {
          if (result.isConfirmed) {
            fetch('/shipping-services/set-price/', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  service_id: serviceId,
                  request_id: requestId
                })
              })
              .then(res => res.json())
              .then(data => {
                Swal.fire({
                  title: data.success ? 'Confirmed!' : 'Error!',
                  text: data.message || '',
                  icon: data.success ? 'success' : 'error',
                  timer: 3000, // 3 seconds
                  timerProgressBar: true, // show progress bar
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  },
                  willClose: () => {
                    if (data.success) {
                      location.reload(); // reload page after timer
                    }
                  }
                });
              })
              .catch(() => Swal.fire('Error!', 'Request failed. Try again.', 'error'));
          }
        });
      });

    });

    // After rendering the rows
  </script>
  <?= $this->endSection() ?>
  <?= $this->endSection() ?>