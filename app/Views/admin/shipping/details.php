  <?= $this->extend('admin/layouts/main') ?>

  <?= $this->section('content') ?>


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
                  <div class="d-flex gap-3">
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
                      title: 'Shipping requests',
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
                      <span class="float-end">
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


            <!-- Shipping Timeline -->
            <div class="card">
              <div class="card-header">
                <i class="fas fa-history me-2"></i>Shipping Timeline
              </div>
              <div class="card-body">
                <div class="timeline">
                  <?php if (!$history): ?>
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
          <div class="col-lg-4"> <!-- Actions Card -->
            <div class="card">
              <div class="card-header">
                <i class="fas fa-tag me-2"></i>Label Information
              </div>
              <div class="card-body">
                <?php if (empty($request['label'])): ?>
                  <form action="<?= base_url('shipping/update-label/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="label" class="form-label text-muted mb-4">Upload the label for this request.</label>
                      <input type="file" name="label" id="label" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-shippex-orange">Upload label</button>
                  </form>
                <?php else: ?>
                  <div class="text-center ">
                    <img src="<?= base_url('images/labels/') . $request['label'] ?>" alt="" height="120px" width="auto">
                  </div>
                  <hr>
                  <div class="d-flex justify-content-center gap-3">

                    <a class="btn btn-action view" href="<?= base_url('images/labels/' . $request['label']) ?>" target="_blank"><i class="fas fa-eye"></i></a>
                    <a class="btn btn-action edit" href="<?= base_url('images/labels/' . $request['label']) ?>" download><i class="fas fa-download"></i></a>
                    <form class="delete-form" action="<?= base_url('shipping/delete-label/' . $request['id']) ?>" method="post" class="d-inline delete-form">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-action delete "><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                <?php endif; ?>
              </div>
            </div>


            <!-- purchase invoice Details -->
            <div class="card">
              <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Purchase Invoic
              </div>
              <div class="card-body">
                <?php if ($request['purchase_invoice']): ?>

                  <div class="text-center ">
                    <img src="<?= base_url('images/invoices/') . $request['purchase_invoice'] ?>" alt="" height="120px" width="auto">
                    <br>
                    <a href="<?= base_url('images/invoices/' . $request['purchase_invoice']) ?>" target="_blank">
                      View
                    </a>
                  </div>
                <?php else: ?>
                  <div class="text-center py-4">
                    <h4>No Purchase invoice uploaded</h4>
                    <a href="<?= base_url('shipping/notify_user/' . $request['id']) ?>" class="btn btn-status-update ">
                      <i class="fas fa-bell me-2"></i>Notify User
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
              <div class="card-header">
                <h5><i class="fas fa-exchange-alt me-2"></i>Update Booking Status</h5>

              </div>
              <div class="card-body">
                <div class="current-status mb-4">
                  <p class="mb-1">Current Status:</p>
                  <span class="status-badge status-shipping" id="currentStatusDisplay"><?= $request['status'] ?></span>
                </div>

                <div class="status-options">
                  <div class="status-option" data-status="pending">
                    <span class="status-indicator indicator-pending"></span>
                    <span class="status-text">Pending</span>
                  </div>

                  <div class="status-option" data-status="accepted">
                    <span class="status-indicator indicator-accepted"></span>
                    <span class="status-text">Accepted</span>
                  </div>

                  <div class="status-option" data-status="shipping">
                    <span class="status-indicator indicator-shipping"></span>
                    <span class="status-text">Shipping</span>
                  </div>

                  <div class="status-option" data-status="shipped">
                    <span class="status-indicator indicator-shipped"></span>
                    <span class="status-text">Shipped</span>
                  </div>

                  <div class="status-option" data-status="delivered">
                    <span class="status-indicator indicator-delivered"></span>
                    <span class="status-text">Delivered</span>
                  </div>

                  <div class="status-option" data-status="canceled">
                    <span class="status-indicator indicator-canceled"></span>
                    <span class="status-text">Canceled</span>
                  </div>
                </div>
              </div>

              <div class="mb-4 px-4">
                <button class="btn btn-status-update w-100" id="updateStatusBtn">
                  <i class="fas fa-save me-2"></i>Update Status
                </button>
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
      let selectedStatus = '<?= $request['status'] ?>';

      document.querySelector(`.status-option[data-status="${selectedStatus}"]`)?.classList.add('selected');

      statusOptions.forEach(option => {
        option.addEventListener('click', function() {
          statusOptions.forEach(opt => opt.classList.remove('selected'));
          this.classList.add('selected');
          selectedStatus = this.getAttribute('data-status');
        });
      });

      const updateBtn = document.getElementById('updateStatusBtn');
      updateBtn.addEventListener('click', function() {
        const bookingId = <?= $request['id'] ?? 'null' ?>;
        if (!bookingId) {
          Swal.fire('Error', 'Booking ID not found!', 'error');
          return;
        }

        Swal.fire({
          title: 'Updating status...',
          text: 'Please wait while the update is processed.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        fetch(`/shipping/update-status/${bookingId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              new_status: selectedStatus
            })
          })
          .then(res => res.json())
          .then(data => {
            Swal.close();

            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Status Updated!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
              }).then(() => {
                window.location.reload();
              });
            } else {
              Swal.fire('Error', data.message, 'error');
            }
          })
          .catch(err => {
            Swal.close();
            Swal.fire('Error', 'Network error: ' + err.message, 'error');
          });
      });
    });
  </script>

  <?= $this->endSection() ?>
  <?= $this->endSection() ?>