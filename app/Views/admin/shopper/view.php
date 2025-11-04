<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
  <!-- Stats Overview -->
  <div class="row">
    <div class="container py-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-shippex-purple text-white">
          <div class="d-flex justify-content-between align-items-center">
            <span class="align-items-center gap-5 ">
              <h3 class="mb-0 d-inline ">Request #<?= esc($request['id']) ?></h3>
              <?= statusBadge($request['status']) ?>
            </span>
            <!-- Trigger Button -->
            <?php if (strtolower($request['status']) === 'pending'): ?>
              <button type="button"
                class="btn btn-shippex-orange"
                data-bs-toggle="modal"
                data-bs-target="#setPriceModal"
                data-request-id="<?= esc($request['id']) ?>">
                Set Price and Accept
              </button>
            <?php endif; ?>

            <?php if ($request['status'] === 'processing'): ?>

              <a type="button"
                class="btn btn-sm btn-shippex-orange mark-as-completed"
                data-request-id="<?= esc($request['id']) ?>">
                <i class="fas fa-lg fa-check-circle"></i> Mark as Shipped
              </a>

            <?php endif ?>
            <?php if ($request['status'] === 'completed'): ?>

              <span>
                <i class="fas fa-check-circle"></i>
                Shipped
              </span>

            <?php endif ?>
          </div>
        </div>


        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="info-card bg-light p-3 rounded">
                <h5 class="text-shippex-purple"><i class="fas fa-calendar-alt me-2"></i>Request Details</h5>
                <p class="mb-1"><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($request['created_at'])) ?></p>
                <?php if (strtolower($request['status']) != 'pending'): ?>
                  <p class="mb-1"><strong>Price:</strong> <?= "$" . $request['price'] ?>

                    <?php if (strtolower($request['payment_status']) != 'paid'): ?>
                      <a href=""
                        data-bs-toggle="modal"
                        data-bs-target="#setPriceModal"
                        data-request-id="<?= esc($request['id']) ?>">
                        <i class="fas fa-pencil"></i></a>
                    <?php endif; ?>
                  </p>
                <?php endif; ?>
                <?php
                // 1️⃣ Get payment info JSON from request
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
                <?php endif;

                if ($request['status'] === 'completed'):
                ?>


                  <p><i class="fas fa-info-circle"></i>This request was shipped to
                    <a href="<?= base_url('warehouse/edit/' . $request['warehouse_id']) ?>"><b><?= warehouse_name($request['warehouse_id'])['city'] . ',' .
                                                                                                  warehouse_name($request['warehouse_id'])['country'] ?></b></a>
                    warehouse.
                  </p>

                <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-card bg-light p-3 rounded">
                <h5 class="text-shippex-purple"><i class="fas fa-shipping-fast me-2"></i>Shipping Preferences</h5>
                <p class="mb-1"><strong>Option:</strong> <?= esc($request['delivery_description']) ?: 'N/A' ?></p>
                <p class="mb-1"><strong>Preference:</strong> <?= esc($request['delivery_notes']) ?: 'N/A' ?></p>
                <p class="mb-0"><strong>Allow alternate retailers:</strong>
                  <span class="badge bg-<?= $request['use_another_retailer'] ? 'success' : 'secondary' ?>">
                    <?= $request['use_another_retailer'] ? 'Yes' : 'No' ?>
                  </span>
                </p>
              </div>
            </div>
          </div>

          <h4 class="text-shippex-purple mb-3"><i class="fas fa-boxes me-2"></i>Items</h4>

          <?php if (!empty($items)): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="bg-shippex-purple text-white">
                  <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>URL</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Qty</th>
                    <th>Instructions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($items as $index => $item): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= esc($item['name']) ?></td>
                      <td>
                        <a href="<?= esc($item['url']) ?>" target="_blank" class="btn btn-sm btn-outline-shippex-orange">
                          View <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                        <span class="url d-none"><?= $item['url'] ?></span>
                      </td>
                      <td><?= esc($item['size']) ?: '-' ?></td>
                      <td>
                        <?php if (!empty($item['color'])): ?>
                          <span class="color-dot" style="background-color: <?= esc($item['color']) ?>;"></span>
                          <?= esc($item['color']) ?>
                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                      <td><?= esc($item['quantity']) ?></td>
                      <td><?= esc($item['instructions']) ?: '-' ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> No items found for this request.
            </div>
          <?php endif; ?>
        </div>

        <div class="card-footer bg-light">
          <a href="<?= base_url('admin/shopper/requests') ?>" class="btn btn-outline-shippex-purple float-end">
            <i class="fas fa-arrow-left me-2"></i> Back to Requests
          </a>
          <a onclick="downloadCurrentPage({
            filename: 'shopper_request',
            title: 'Shopper requests',
            format: 'html'
        })" href=" #" class="btn btn-shippex-orange">
            <i class="fas fa-print me-2"></i> Print Details
          </a>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="noprint modal fade" id="paymentInfoModal" tabindex="-1" aria-labelledby="paymentInfoModalLabel" aria-hidden="true">
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
<!-- Modal -->
<div class="noprint modal fade" id="setPriceModal" data-bs-backdrop="static" data-bs-keyboard="false"
  aria-labelledby="setPriceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="setPriceModalLabel">Set Price</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="setPriceForm" method="post" action="<?= base_url('admin/shopper/requests/set_price') ?>">
          <input type="hidden" name="request_id" id="request_id" value="<?= esc($request['id']) ?>">
          <div class="mb-3">
            <label for="new_price" class="form-label">New Price</label>
            <input type="number" class="form-control" value="<?= isset($request['price']) ? esc($request['price']) : '' ?>"
              name="new_price" id="new_price" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-shippex-purple" id="savePriceBtn">
              <span class="spinner-border spinner-border-sm me-2 d-none" id="savePriceSpinner" role="status" aria-hidden="true"></span>
              Save & Accept
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Mark as Shipped Modal -->
<div class="modal fade" id="markShippedModal" tabindex="-1" aria-labelledby="markShippedModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="markShippedForm">
        <div class="modal-header">
          <h5 class="modal-title" id="markShippedModalLabel">Mark as Shipped</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="request_id" id="modal_request_id">

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Retailer *</label>
              <select class="form-select" name="retailer" required>
                <option value="">Select Retailer</option>
                <option value="amazon">Amazon</option>
                <option value="ebay">eBay</option>
                <option value="walmart">Walmart</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Tracking Number *</label>
              <input type="text" class="form-control" name="tracking_number" id="tracking_number" required>
            </div>



            <div class="col-md-12">
              <label class="form-label">Status *</label>
              <select class="form-select" name="status" required>
                <option value="ready">Ready</option>
                <option value="shipped">Shipped</option>
                <option value="disposed">Disposed</option>
                <option value="canceled">Canceled</option>
              </select>
            </div>
          </div>
          <div id="itemsContainer">
            <h6 class="fw-bold mb-2">Items in Request</h6>
            <div class="table-responsive">
              <table class="table table-bordered align-middle" id="itemsTable">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Weight (Kg)</th>
                    <th>Value ($)</th>
                    <th>Dimensions (L×W×H cm)</th>
                  </tr>
                </thead>
                <tbody id="itemsTableBody">
                  <!-- Filled dynamically by JS -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-shippex-orange">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>



<style>
  .bg-shippex-purple {
    background-color: #4E148C !important;
  }

  .text-shippex-purple {
    color: #4E148C !important;
  }

  .btn-shippex-orange {
    background-color: #FF6600;
    color: white;
    border-color: #FF6600;
  }

  .btn-shippex-orange:hover {
    background-color: #e05c00;
    border-color: #e05c00;
  }

  .btn-shippex-purple {
    background-color: #4E148C;
    color: #FFF;
    border-color: #4E148C;
  }

  .btn-shippex-purple:hover {
    background-color: #4E148C;
    color: white;
    opacity: 0.9;
  }

  .btn-outline-shippex-purple {
    color: #4E148C;
    border-color: #4E148C;
  }

  .btn-outline-shippex-purple:hover {
    background-color: #4E148C;
    color: white;
  }

  .color-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
    vertical-align: middle;
  }

  .info-card {
    border-left: 3px solid #FF6600;
  }

  #loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const setPriceModal = document.getElementById('setPriceModal');
    const setPriceForm = document.getElementById('setPriceForm');
    const requestInput = document.getElementById('request_id');

    const saveBtn = document.getElementById('savePriceBtn');
    const saveSpinner = document.getElementById('savePriceSpinner');

    setPriceModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const requestId = button.getAttribute('data-request-id');
      requestInput.value = requestId;
    });

    setPriceForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Disable button & show spinner
      saveBtn.disabled = true;
      saveSpinner.classList.remove('d-none');

      const formData = new FormData(this);

      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            bootstrap.Modal.getInstance(setPriceModal).hide();
            alert('Request accepted and user notified.');
            window.location.reload();
          } else {
            alert('Error: ' + (data.message || 'Something went wrong.'));
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error sending request.');
        })
        .finally(() => {
          // Re-enable button & hide spinner
          saveBtn.disabled = false;
          saveSpinner.classList.add('d-none');
        });
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('markShippedModal'));
    const form = document.getElementById('markShippedForm');
    const itemsTableBody = document.getElementById('itemsTableBody');

    document.querySelectorAll('.mark-as-completed').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const requestId = this.dataset.requestId;
        document.getElementById('modal_request_id').value = requestId;

        // Reset tracking number each time
        document.getElementById('tracking_number').value = 'PENDING-' + Math.random().toString(36).substring(2, 10).toUpperCase();

        // 🔹 Fetch the items for this request
        fetch("<?= site_url('admin/shopper-requests/get-items') ?>?request_id=" + requestId)
          .then(res => res.json())
          .then(data => {
            itemsTableBody.innerHTML = '';
            if (data.status === 'success') {
              data.items.forEach((item, index) => {
                const row = `
                <tr>
                  <td>${index + 1}</td>
                  <td>${item.name}</td>
                  <td>${item.quantity}</td>
                  <td><input type="number" class="form-control" name="items[${index}][weight]" step="0.01" required></td>
                  <td><input type="number" class="form-control" name="items[${index}][value]" step="0.01" required></td>
                  <td class="d-flex gap-1">
                    <input type="number" class="form-control" name="items[${index}][length]" placeholder="L" step="0.1" required>
                    <input type="number" class="form-control" name="items[${index}][width]" placeholder="W" step="0.1" required>
                    <input type="number" class="form-control" name="items[${index}][height]" placeholder="H" step="0.1" required>
                  </td>
                  <input type="hidden" name="items[${index}][id]" value="${item.id}">
                </tr>`;
                itemsTableBody.insertAdjacentHTML('beforeend', row);
              });
            } else {
              itemsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No items found</td></tr>`;
            }
            modal.show();
          });
      });
    });

    // Submit via AJAX
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);

      // Optional confirmation
      Swal.fire({
        title: 'Confirm Shipment?',
        text: 'This will mark the request as shipped and create package entries.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ff7b00',
        cancelButtonColor: '#6c757d'
      }).then((result) => {
        if (!result.isConfirmed) return;

        // Show loader
        Swal.fire({
          title: 'Processing...',
          text: 'Saving package data, please wait.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        fetch("<?= site_url('admin/shopper-requests/mark-shipped') ?>", {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            Swal.close();

            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Packages stored successfully.',
                showConfirmButton: false,
                timer: 2000
              });

              // close modal + reload page after short delay
              setTimeout(() => {
                modal.hide();
                window.location.reload();
              }, 2000);

            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to save package data.'
              });
            }
          })
          .catch(err => {
            Swal.close();
            Swal.fire({
              icon: 'error',
              title: 'Request Failed',
              text: 'An error occurred while processing your request.'
            });
            console.error(err);
          });
      });
    });
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);

      // Optional confirmation
      Swal.fire({
        title: 'Confirm Shipment?',
        text: 'This will mark the request as shipped and create package entries.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ff7b00',
        cancelButtonColor: '#6c757d'
      }).then((result) => {
        if (!result.isConfirmed) return;

        // Show loader
        Swal.fire({
          title: 'Processing...',
          text: 'Saving package data, please wait.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        fetch("<?= site_url('admin/shopper-requests/mark-shipped') ?>", {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            Swal.close();

            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Packages stored successfully.',
                showConfirmButton: false,
                timer: 2000
              });

              // close modal + reload page after short delay
              setTimeout(() => {
                modal.hide();
                window.location.reload();
              }, 2000);

            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to save package data.'
              });
            }
          })
          .catch(err => {
            Swal.close();
            Swal.fire({
              icon: 'error',
              title: 'Request Failed',
              text: 'An error occurred while processing your request.'
            });
            console.error(err);
          });
      });
    });
  });
</script>




<?= $this->endSection() ?>