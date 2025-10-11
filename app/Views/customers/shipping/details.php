<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
  <!-- Stats Overview -->
  <div class="row">



    <div class="container">
      <!-- Status Card -->
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h4 class="mb-1">Package Status: <?= statusBadge($request['status']) ?></h4>
              <p class="mb-2">Estimated Delivery: <strong>Oct 28, 2023 by 5:00 PM</strong></p>
              <div class="progress tracking-progress mb-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <p class="text-muted small mb-0">65% complete - Package is in transit to destination facility</p>
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
                    <span class="float-end"><?= $request['category'] ?></span>
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

        <!-- Right Column -->
        <div class="col-lg-4">
          <!-- Shipping Information -->
          <div class="card">
            <div class="card-header">
              <i class="fas fa-info-circle me-2"></i>Shipping Information
            </div>
            <div class="card-body">
              <div class="detail-item">
                <span class="detail-label">Courier:</span>
                <span class="float-end"><?= $request['courier_name'] ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Service:</span>
                <span class="float-end"><?= $request['service_name'] ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Delivery Time:</span>
                <span class="float-end"><?= $request['delivery_time'] ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Total Charge:</span>
                <span class="float-end fw-bold text-success"><?= $request['currency'] . ' ' . $request['total_charge'] ?></span>
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
                  <br>
                  <a href="<?= base_url('images/invoices/' . $request['purchase_invoice']) ?>" target="_blank">
                    View
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($request['status'] == "accepted"): ?>
            <div class="card">
              <div class="card-header">
                <i class="fas fa-pay-circle me-2"></i>Payment
              </div>
              <div class="card-body">
                <h5>Request ID: <?= $request['id'] ?></h5>
                <p>Amount to pay: $<?= $request['total_charge'] ?></p>

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
  </script>
  <?= $this->endSection() ?>
  <?= $this->endSection() ?>