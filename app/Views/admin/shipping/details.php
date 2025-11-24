  <?= $this->extend('admin/layouts/main') ?>

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
                    <?php if (($request['set_rate']) == 1):
                      if ($role === 'admin'):
                    ?>

                        <!-- Font Awesome info icon -->
                        <a class="btn btn-shippex-orange" data-bs-toggle="modal"
                          data-bs-target="#setRateModal"
                          style="cursor: pointer;"
                          title="Click to view payment details">
                          <i class="fas fa-dollar-sign "></i>
                          Add Prices
                        </a>
                      <?php
                      endif;
                    else: ?>
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
        <!-- Payment Details Modal -->
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
        <!--Set Rate Modal -->
        <div class="modal fade" id="setRateModal" tabindex="-1" aria-labelledby="setRateModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="setRateModalLabel">Available Rates For this Request</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="manualMode">
                  <label class="form-check-label" for="manualMode">
                    Enter Manually Instead of HTML
                  </label>
                </div>

                <form id="importForm">
                  <div class="form-group mb-4">
                    <label for="htmlInput">HTML</label>
                    <textarea class="form-control" id="htmlInput" name="html" rows="10" placeholder="Paste HTML here..."></textarea>
                  </div>
                  <div class="form-group">
                    <button type="button" id="previewBtn" class="btn btn-primary"><i class="fas fa-eye"></i> Preview</button>
                    <button type="button" id="importBtn" class="btn btn-shippex-orange"><i class="fas fa-save"></i> Import</button>
                  </div>
                </form>
                <div id="manualForm" style="display:none;">
                  <form id="manualInsertForm">

                    <div class="form-group mb-2">
                      <label>Provider Name</label>
                      <input type="text" class="form-control" name="provider_name">
                    </div>

                    <div class="form-group mb-2">
                      <label>Service Name</label>
                      <input type="text" class="form-control" name="service_name">
                    </div>

                    <div class="form-group mb-2">
                      <label>Price</label>
                      <input type="number" class="form-control" name="price">
                      <input type="hidden" class="form-control" name="request_id" value="<?= $request['id'] ?>">
                    </div>

                    <div class="form-group mb-2">
                      <label>Currency</label>
                      <input type="text" class="form-control" name="currency">
                    </div>

                    <div class="form-group mb-2">
                      <label>Transit Text</label>
                      <input type="text" class="form-control" name="transit_text">
                    </div>

                    <div class="form-group mb-2">
                      <label>Transit Days</label>
                      <input type="number" class="form-control" name="transit_days">
                    </div>

                    <div class="form-group mb-2">
                      <label>Features (key:value, comma separated)</label>
                      <textarea class="form-control" name="features"></textarea>
                    </div>

                    <button type="button" id="submitManualBtn" class="btn btn-success mt-3">
                      <i class="fas fa-save"></i> Save Manually
                    </button>

                  </form>
                </div>

                <div id="previewArea" class="mt-4" style="display:none">
                  <hr class="mb-4">
                  <h3 class="mb-2">Preview</h3>
                  <div id="previewSummary" class="mb-3"></div>
                  <div id="previewTableWrapper" style="max-height:400px; overflow:auto">
                    <table class="table table-sm table-bordered" id="previewTable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Provider</th>
                          <th>Service</th>
                          <th>Price</th>
                          <th>Transit Days</th>
                          <th>Valid</th>
                          <th>Errors</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Rates Prevview Modal -->
        <div class="modal fade " id="viewRatesModal" tabindex="-1" aria-labelledby="viewRatesModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="viewRatesModalLabel">Set Rate For this Request</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <!-- Shipping Information -->
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
  <script>
    async function postJson(url, body) {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
      });
      return res.json();
    }

    document.getElementById('previewBtn').addEventListener('click', async function() {
      const html = document.getElementById('htmlInput').value;
      if (!html) {
        Swal.fire({
          icon: 'error',
          title: 'Please paste HTML...',
          text: 'Please paste HTML to we should be able to process it.',
          confirmButtonColor: '#d33',

          timer: 2500
        });
        // alert('Please paste HTML');
        return;
      }
      const id = <?= $request['id'] ?>;

      const resp = await postJson(`/shipping-services/import-preview/${id}`, {
        html
      });
      if (resp.status !== 'ok') {
        alert('Preview failed');
        return;
      }
      const preview = resp.preview;
      const tbody = document.querySelector('#previewTable tbody');
      tbody.innerHTML = '';
      preview.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
                <td>${item.index}</td>
                <td>${(item.record.provider_name||'')}</td>
                <td>${(item.record.service_name||'')}</td>
                <td>${(item.record.currency||'')}${(item.record.price!=null?item.record.price:'')}</td>
                <td>${(item.record.transit_days!=null?item.record.transit_days:'')}</td>
                <td>${item.valid?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'}</td>
                <td>${item.errors.length?item.errors.join(', '):''}</td>
            `;
        tbody.appendChild(tr);
      });
      document.getElementById('previewSummary').innerText = `Found ${preview.length} records`;
      document.getElementById('previewArea').style.display = 'block';
    });

    document.getElementById('importBtn').addEventListener('click', async function() {

      const confirmResult = await Swal.fire({
        title: 'Import parsed records?',
        text: 'Do you want to import the parsed records now?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, import',
        cancelButtonText: 'Cancel'
      });

      if (!confirmResult.isConfirmed) return;

      const html = document.getElementById('htmlInput').value;
      const id = <?= $request['id'] ?>;

      // Show loader
      Swal.fire({
        title: 'Importing...',
        html: 'Please wait while the records are being imported.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      try {
        const res = await postJson(`/shipping-services/import-html/${id}`, {
          html
        });

        Swal.close(); // Close loader

        if (res.status === 'ok') {
          await Swal.fire({
            title: 'Import Completed',
            html: `
                    <strong>Inserted:</strong> ${res.inserted ? res.inserted.length : 0}<br>
                    <strong>Errors:</strong> ${res.errors ? res.errors.length : 0}
                `,
            icon: 'success'
          });
          location.reload();
        } else {
          Swal.fire({
            title: 'Import Failed',
            text: 'An error occurred while importing.',
            icon: 'error'
          });
        }
      } catch (error) {
        Swal.close();
        Swal.fire({
          title: 'Import Failed',
          text: 'An unexpected error occurred.',
          icon: 'error'
        });
        console.error('Import error:', error);
      }

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
          const res = await fetch(`/shipping-services/get_all/${id}`);
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
                    <div class="pricing-service mb-4 border rounded" data-service="${row.service_name}">
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
                    </div>`;
          });

          tableDiv.innerHTML = html;

        } catch (error) {
          console.error('Error fetching shipping services:', error);
          tableDiv.innerHTML = '<p class="text-center text-danger">Error fetching data</p>';
        }
      }

      loadShippingServices();
    });
  </script>
  <script>
    const manualCheckbox = document.getElementById('manualMode');
    const htmlForm = document.getElementById('importForm');
    const manualForm = document.getElementById('manualForm');

    manualCheckbox.addEventListener('change', function() {
      if (this.checked) {
        htmlForm.style.display = "none";
        manualForm.style.display = "block";
      } else {
        htmlForm.style.display = "block";
        manualForm.style.display = "none";
      }
    });

    document.getElementById('submitManualBtn').addEventListener('click', () => {

      // 1️⃣ Show loading first
      Swal.fire({
        title: 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      const formData = new FormData(document.getElementById('manualInsertForm'));

      fetch('/shipping-services/manual-insert', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {

          // 2️⃣ After request completes, show result alert
          Swal.fire({
            title: data.success ? 'Saved!' : 'Error!',
            text: data.message,
            icon: data.success ? 'success' : 'error',
            timer: 3000,
            timerProgressBar: true,
            willClose: () => location.reload()
          });

        })
        .catch(() => {

          // 3️⃣ Handle fetch error
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            timer: 3000,
            timerProgressBar: true
          });

        });
    });
  </script>
  <?= $this->endSection() ?>
  <?= $this->endSection() ?>