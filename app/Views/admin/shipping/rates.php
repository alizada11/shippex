<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="card p-4 container my-2">
  <div class="mb-4 d-flex justify-content-between">
    <h3 class="">Manage Shipping Rates</h3>
    <div>
      <button class="btn btn-primary " id="addNewRateBtn">
        <i class="fas fa-plus"></i> Add
      </button>
      <a href="<?= base_url('shipping/details/' . $requestId) ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

  </div>

  <div id="ratePreviewTable">

    <?php foreach ($records as $row): ?>
      <?php
      $features = is_array($row['features'])
        ? $row['features']
        : json_decode($row['features'], true);
      ?>

      <div class="pricing-service card mb-4 shadow-sm p-3" data-id="<?= $row['id'] ?>">

        <div class="prices py-3">
          <div class="container">
            <div class="row align-items-center h-100">

              <!-- LOGO + NAME -->
              <div class="col-sm-2 my-auto">
                <div class="service-logo text-center d-flex flex-column align-items-center">
                  <img src="<?= $row['provider_logo'] ?>" width="120" class="mb-2">

                  <input type="text" class="form-control form-control-sm fw-bold text-center mb-1 edit-service-name"
                    value="<?= $row['provider_name'] . ' ' . $row['service_name'] ?>">
                </div>
              </div>

              <!-- PRICE + TRANSIT -->
              <div class="col-sm-4 text-center">

                <div class="service-price mb-2">
                  <input type="text" class="form-control form-control-sm text-start edit-currency"
                    value="<?= $row['currency'] ?>" style="max-width:60px; display:inline-block">
                  <input type="number" step="0.01" class="form-control form-control-sm text-start edit-price"
                    value="<?= $row['price'] ?>" style="max-width:120px; display:inline-block">
                </div>

                <div class="service-info-rating  mb-2">
                  <strong>Ratings:</strong> <?= str_repeat('<i class="fas fa-star text-warning"></i>', intval($row['rating'])) ?>
                </div>

                <div class="service-info-transit-time">
                  <strong>Transit Days:</strong>
                  <input type="number" class="form-control form-control-sm text-center edit-transit-days"
                    value="<?= $row['transit_days'] ?>" style="max-width:80px; display:inline-block">
                </div>
              </div>

              <!-- FEATURES -->
              <div class="col-sm-6 text-start small">
                <div class="service-details gap-1 d-flex">

                  <div class="mb-1">Tracking:
                    <input type="text" class="form-control form-control-sm edit-feature"
                      data-key="tracking" value="<?= $features['tracking'] ?? '' ?>">
                  </div>

                  <div class="mb-1">Insurance:
                    <input type="text" class="form-control form-control-sm edit-feature"
                      data-key="insurance" value="<?= $features['tracking'] ?? '' ?>">
                  </div>

                  <div class="mb-1">Multi-piece:
                    <input type="text" class="form-control form-control-sm edit-feature"
                      data-key="multi_piece" value="<?= $features['multi_piece'] ?? '' ?>">
                  </div>

                  <div class="mb-1">Combine & Repack:
                    <input type="text" class="form-control form-control-sm edit-feature"
                      data-key="combine_and_repack" value="<?= $features['combine_and_repack'] ?? '' ?>">
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col text-end">
                <button class="btn btn-danger delete-row-btn" data-id="<?= $row['id'] ?>">
                  Delete
                </button>

                <button class="btn btn-primary btn-sm save-rate-btn" data-id="<?= $row['id'] ?>">
                  Save Changes
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

    <?php endforeach; ?>

  </div>

</div>
<div class="modal fade" id="addRateModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add New Shipping Rate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form id="addRateForm">

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Provider Name</label>
              <input type="text" class="form-control" name="provider_name">
            </div>
            <div class="col">
              <label class="form-label">Service Name</label>
              <input type="text" class="form-control" name="service_name">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Provider Logo URL</label>
              <input type="text" class="form-control" name="provider_logo">
            </div>
            <div class="col">
              <label class="form-label">Currency</label>
              <input type="text" class="form-control" name="currency" value="$">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Price</label>
              <input type="number" class="form-control" name="price" step="0.01">
            </div>
            <div class="col">
              <label class="form-label">Rating</label>
              <input type="number" class="form-control" name="rating" min="1" max="5">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Transit Text</label>
              <input type="text" class="form-control" name="transit_text">
            </div>
            <div class="col">
              <label class="form-label">Transit Days</label>
              <input type="number" class="form-control" name="transit_days">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Features (JSON)</label>
            <textarea class="form-control" name="features" rows="3" placeholder='{"tracking":"Yes"}'>{"tracking":"Yes","insurance":"Extra Cost","multi_piece":"Yes","combine_and_repack":"Yes"}</textarea>
          </div>

          <input type="hidden" name="request_id" value="<?= $requestId ?>">

        </form>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveNewRateBtn">Save Rate</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>
<script>
  document.addEventListener('click', async function(e) {
    if (!e.target.classList.contains('save-rate-btn')) return;

    let id = e.target.getAttribute('data-id');
    let wrapper = e.target.closest('.pricing-service');

    let payload = {
      id: id,
      provider_name: wrapper.querySelector('.edit-service-name').value.split(' ')[0],
      service_name: wrapper.querySelector('.edit-service-name').value.replace(wrapper.querySelector('.edit-service-name').value.split(' ')[0], '').trim(),
      currency: wrapper.querySelector('.edit-currency').value,
      price: wrapper.querySelector('.edit-price').value,
      transit_days: wrapper.querySelector('.edit-transit-days').value,
      features: {}
    };

    wrapper.querySelectorAll('.edit-feature').forEach(el => {
      payload.features[el.dataset.key] = el.value;
    });

    Swal.fire({
      title: 'Saving...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    const resp = await fetch('/shipping-services/' + id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    }).then(r => r.json());

    Swal.close();

    if (resp.status !== 'ok' && resp.status !== 'created') {
      Swal.fire('Error', 'Failed to add new rate', 'error');
      return;
    }

    Swal.fire('Saved!', 'Record updated successfully.', 'success');
  });

  document.addEventListener('click', async function(e) {
    if (!e.target.classList.contains('delete-row-btn')) return;

    const id = e.target.getAttribute('data-id');

    const confirmDelete = await Swal.fire({
      title: "Are you sure?",
      text: "This rate will be permanently deleted.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, Delete",
      cancelButtonText: "Cancel"
    });

    if (!confirmDelete.isConfirmed) return;

    // Send DELETE request
    const res = await fetch(`/shipping-services/${id}`, {
      method: "DELETE"
    });

    const json = await res.json();

    if (json.status !== 'ok') {
      Swal.fire("Error", "Failed to delete.", "error");
      return;
    }


    // Remove row visually
    const row = e.target.closest('.pricing-service');
    row.style.transition = "0.3s";
    row.style.opacity = "0";
    setTimeout(() => row.remove(), 300);

    Swal.fire("Deleted!", "The rate has been removed.", "success");
  });

  const addRateModal = new bootstrap.Modal(document.getElementById('addRateModal'));

  document.getElementById('addNewRateBtn').addEventListener('click', function() {
    document.getElementById('addRateForm').reset();
    addRateModal.show();
  });

  document.getElementById('saveNewRateBtn').addEventListener('click', async function() {

    const form = document.getElementById('addRateForm');
    const formData = new FormData(form);

    let data = Object.fromEntries(formData.entries());

    // Parse features JSON if provided
    if (data.features) {
      try {
        data.features = JSON.parse(data.features);
      } catch (err) {
        Swal.fire("Invalid Features", "Features must be valid JSON.", "error");
        return;
      }
    }

    // Send request
    const res = await fetch("/shipping-services", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const json = await res.json();

    if (json.status !== "ok") {
      Swal.fire("Error", "Failed to add new rate.", "error");
      return;
    }

    Swal.fire("Saved!", "New rate has been added.", "success");

    addRateModal.hide();
    window.location.reload();
    // Reload list
    loadShippingServices();
  });
</script>

<?= $this->endSection() ?>