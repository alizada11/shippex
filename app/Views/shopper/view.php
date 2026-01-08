<?= $this->extend('customers/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container ">
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-shippex-purple text-white">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Request #<?= esc($request['id']) ?></h3>
        <span class="badge bg-<?= $request['status'] === 'completed' ? 'success' : ($request['status'] === 'processing' ? 'warning' : 'secondary') ?>">
          <?= esc(ucfirst($request['status'])) ?>
        </span>

      </div>
    </div>

    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="info-card bg-light p-3 rounded">
            <h5 class="text-shippex-purple"><i class="fas fa-calendar-alt me-2"></i>Request Details</h5>
            <p class="mb-1"><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($request['created_at'])) ?></p>
            <?php if (strtolower($request['status']) != 'wait_for_payment'): ?>
              <p class="mb-1"><strong>Price:</strong> <?= "$" . $request['price'] ?></p>
            <?php endif; ?>
            <?php if (strtolower($request['status']) == 'wait_for_payment'): ?>
              <p class="mb-1">
                <strong>Price:</strong> <?= "$" . $request['price'] ?> -
                <a href="#" class="btn btn-sm btn btn-shippex-orange" id="showPaymentBtn">
                  Pay now
                </a>

              </p>

              <script src="https://www.paypal.com/sdk/js?client-id=<?= esc($client_id) ?>&currency=USD"></script>
              <script>
                paypal.Buttons({
                  createOrder: function(data, actions) {
                    return fetch('<?= base_url('payment/create-order') ?>', {
                      method: 'post',
                      headers: {
                        'content-type': 'application/json'
                      },
                      body: JSON.stringify({
                        amount: '<?= $request['price'] ?>'
                      })
                    }).then(res => res.json()).then(order => order.id);
                  },
                  onApprove: function(data, actions) {
                    return fetch('<?= base_url('payment/capture-order') ?>', {
                      method: 'post',
                      headers: {
                        'content-type': 'application/json'
                      },
                      body: JSON.stringify({
                        orderID: data.orderID,
                        payFor: 'shopper',
                        reqId: $request['id']
                      })
                    }).then(res => res.json()).then(details => {
                      if (details.status === 'COMPLETED') {
                        window.location.href = '<?= base_url('payment/success') ?>';
                      } else {
                        alert('Payment failed');
                      }
                    });
                  },
                  onError: function(err) {
                    console.error(err);
                    alert('An error occurred');
                  }
                }).render('#paypal-button-container');
              </script>
              <div id="paypal-button-container"></div>
              <p><i class="fas fa-info-circle text-danger"></i> After the payment your package will be shipped to <a href="<?= base_url('warehouse-requests/my-requests') ?>"><?= $default_wh['city'] . ', ' . $default_wh['country'] ?></a>, which is your default warehouse.<br><i><small>If you need to change the default ware house click <a href="<?= base_url('warehouse-requests/my-requests') ?>">here.</a> After payment you can't change the destination.</small></i></p>

              <div id="paymentContainer" class="mt-3" style="display:none;">
                <div id="paypal-button-container"></div>
              </div>

            <?php endif; ?>
            <div id="loader" style="display:none; text-align:center; margin-top:20px;">
              <div class="spinner-border text-primary" role="status"></div>
              <p>Please wait while we process your payment...</p>
            </div>
            <?php
            //  Get payment info JSON from request
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
            <thead class="table-light">
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

    <div class="card-footer flex-row-reverse justify-content-between bg-light">
      <a href="#" onclick="downloadCurrentPage({
                      filename: 'shopper_request',
                      title: 'Personal Shoppr request details',
                      format: 'html'
                  })" class="btn btn-shippex-orange">
        <i class="fas fa-print me-2"></i> Print Details
      </a>
      <a href="<?= base_url('shopper/requests') ?>" class="btn btn-outline-shippex-purple float-end">
        <i class="fas fa-arrow-left me-2"></i> Back to Requests
      </a>
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
</style>
<?= $this->section('script') ?>


<?= $this->endSection() ?>
<?= $this->endSection() ?>