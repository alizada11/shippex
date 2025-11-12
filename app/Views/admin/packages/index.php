<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/shipping_style.css'); ?>">
<?= $this->endSection() ?>
<?php
$session = session();
$role = $session->get('role');

// Dynamically pick layout based on role
if ($role === 'admin') {
  $this->extend('admin/layouts/main');
} else {
  $this->extend('customers/layouts/main');
}
?>
<?= $this->section('content') ?>
<div class="premium-package-container">
  <div class="premium-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="page-title-section">
          <h1 class="page-title">
            <i class="fas fa-boxes me-2"></i>Package Inbox
          </h1>
          <p class="page-subtitle">Manage incoming packages and shipments</p>
        </div>
        <div>
          <a href="<?= base_url('packages/create/' . $virtual_address_id) ?>" class="btn btn-shippex-orange">+ Add</a>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="package-table-container">
      <?php if (empty($packages)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-box-open"></i>
          </div>
          <h4>No Packages Found</h4>
          <p>There are no packages in this warehouse yet.</p>
          <a href="<?= base_url('packages/create/' . $virtual_address_id) ?>" class="btn btn-shippex mt-3">
            <i class="fas fa-plus me-2"></i>Add Your First Package
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table package-table">
            <thead>
              <tr>
                <th scope="col" class="select-col">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="select-all">
                  </div>
                </th>
                <th scope="col">Retailer & Tracking</th>
                <th scope="col">Status</th>
                <th scope="col">Weight</th>
                <th scope="col">Value</th>
                <th scope="col">Warehouse</th>
                <th scope="col">Received</th>
                <th scope="col" class="actions-col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($packages as $p): ?>
                <tr data-package-id="<?= $p['id'] ?>">
                  <td class="select-col">
                    <div class="form-check">
                      <input class="form-check-input package-checkbox" type="checkbox" value="<?= $p['id'] ?>">
                    </div>
                  </td>
                  <td>
                    <div class="retailer-info-table">
                      <div class="retailer-logo-table"><i class="fas fa-store"></i></div>
                      <div class="retailer-details-table">
                        <h6 class="retailer-name"><?= esc($p['retailer']) ?></h6>
                        <span class="tracking-number-table"><?= esc($p['tracking_number']) ?></span>
                      </div>
                    </div>
                  </td>
                  <td><span class="status-badge status-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                  <td><span class="detail-value"><?= esc($p['weight']) ?> kg</span></td>
                  <td><span class="detail-value">$<?= esc($p['value']) ?></span></td>
                  <td><span class="detail-value"><?= esc($p['warehouse_name']) ?></span></td>
                  <td><span class="detail-value"><?= date('M j, Y', strtotime($p['created_at'])) ?></span></td>
                  <td class="actions-col">
                    <div class="action-buttons-table">
                      <a href="<?= base_url('packages/show/' . $p['id']) ?>" class="btn btn-action view"><i class="fas fa-eye"></i></a>
                      <a href="<?= base_url('packages/' . $p['id'] . '/edit') ?>" class="btn btn-action edit"><i class="fas fa-edit"></i></a>
                      <form class="delete-form" action="<?= base_url('packages/' . $p['id'] . '/delete') ?>" method="post" class="d-inline delete-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-action delete "><i class="fas fa-trash"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <div class="row mt-4">
      <?= $pager->links('default', 'bootstrap_full') ?>
    </div>

    <div class="bulk-actions-card mt-4" id="bulkActionsCard" style="display: none;">
      <div class="bulk-header">
        <h5>Bulk Actions</h5>
        <span class="selection-count" id="selectionCount">0 packages selected</span>
      </div>
      <div class="bulk-actions">
        <button class="btn bulk-action ship-now" data-action="ship"><i class="fas fa-rocket me-2"></i> Ship Now</button>
        <button class="btn bulk-action combine-repack" data-action="combine"><i class="fas fa-boxes me-2"></i> Combine & Repack</button>
        <button id="bulkDisposeReturnBtn" class="btn btn-danger">
          <i class="fas fa-trash-alt me-1"></i> Dispose / Return Selected
        </button>




      </div>
    </div>
  </div>
</div>

<!-- Enhanced Dispose / Return Bulk Modal -->
<div class="modal fade" id="disposeReturnBulkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content premium-dispose-modal">
      <div class="modal-header premium-modal-header">
        <div class="modal-icon-container">
          <div class="modal-icon bg-warning">
            <i class="fas fa-recycle"></i>
          </div>
        </div>
        <div class="modal-title-content">
          <h5 class="modal-title">Dispose / Return Packages</h5>
          <p class="modal-subtitle">Manage package disposal and return requests</p>
        </div>
        <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form id="disposeReturnBulkForm" class="needs-validation" novalidate>
        <div class="modal-body premium-modal-body">
          <!-- Summary Alert -->
          <div class="alert alert-info summary-alert mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-info-circle me-3 fs-5"></i>
              <div>
                <strong>Action Required</strong>
                <p class="mb-0">Provide action and reason for each selected package</p>
              </div>
            </div>
          </div>

          <!-- Packages Table -->
          <div class="table-container">
            <div class="table-header">
              <h6 class="table-title">
                <i class="fas fa-list-check me-2"></i>
                Selected Packages
                <span class="badge bg-primary ms-2" id="selectedPackagesCount">0</span>
              </h6>
            </div>

            <div class="table-responsive">
              <table class="table table-hover" id="disposeReturnTable">
                <thead class="table-light">
                  <tr>
                    <th width="50">#</th>
                    <th>Package Details</th>
                    <th width="120">Status</th>
                    <th width="150">Action</th>
                    <th>Reason</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Injected by JS -->
                  <tr class="loading-row">
                    <td colspan="5" class="text-center py-5">
                      <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Loading packages...
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Bulk Actions -->
          <div class="bulk-actions-section mt-4">
            <div class="section-header">
              <h6 class="section-title">
                <i class="fas fa-bolt me-2"></i>
                Quick Actions
              </h6>
            </div>
            <div class="bulk-actions-card">
              <div class="row g-3 align-items-center">
                <div class="col-auto">
                  <label class="col-form-label">Apply to all packages:</label>
                </div>
                <div class="col-auto">
                  <select id="bulkApplyType" class="form-select form-select-sm">
                    <option value="">Select Action</option>
                    <option value="dispose">Dispose All</option>
                    <option value="return">Return All</option>
                  </select>
                </div>
                <div class="col-auto">
                  <button type="button" id="applyToAllBtn" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-check me-1"></i>Apply
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer premium-modal-footer">
          <div class="footer-content">
            <div class="selected-count">
              <span class="count-badge" id="footerSelectedCount">0 packages selected</span>
            </div>
            <div class="footer-actions">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-2"></i>Cancel
              </button>
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-paper-plane me-2"></i>Submit Requests
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Combine & Repack Modal -->
<div class="modal fade" id="combineRepackModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content premium-combine-modal">
      <div class="modal-header premium-modal-header">
        <div class="modal-icon-container">
          <div class="modal-icon bg-primary">
            <i class="fas fa-boxes"></i>
          </div>
        </div>
        <div class="modal-title-content">
          <h5 class="modal-title">Combine & Repack Packages</h5>
          <p class="modal-subtitle" id="combineModalSubtitle"><i class="fas fa-exclamation-triangle"></i> All the valuse are estimated values for selected items, the actual value will be inserted after combination and repacking</p>
        </div>
        <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="modal-body premium-modal-body">
        <!-- Package Count Validation Alert -->
        <div class="alert alert-warning package-validation-alert" id="packageValidationAlert" style="display: none;">
          <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fs-5"></i>
            <div>
              <strong>Minimum 2 packages required</strong>
              <p class="mb-0">Please select at least 2 packages to use Combine & Repack feature.</p>
            </div>
          </div>
        </div>

        <!-- Selected Packages Section -->
        <div class="selected-packages-section">
          <div class="section-header">
            <h6 class="section-title">
              <i class="fas fa-list-check me-2"></i>
              Selected Packages
              <span class="badge bg-primary ms-2" id="selectedCountBadge">0</span>
            </h6>
            <div class="section-actions">
              <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSelection">
                <i class="fas fa-times me-1"></i>Clear All
              </button>
            </div>
          </div>

          <div class="packages-grid" id="selectedPackagesGrid">
            <!-- Packages will be dynamically inserted here -->
            <div class="empty-state">
              <i class="fas fa-box-open text-muted mb-3" style="font-size: 2rem;"></i>
              <p class="text-muted mb-0">No packages selected</p>
            </div>
          </div>
        </div>
        <!-- Dimensions Configuration -->
        <div class="dimensions-config-section mt-4">


          <div class="dimensions-form">
            <div class="row g-3" style="display: none;">
              <div class="col-md-3">
                <label class="form-label">Length (cm)</label>
                <div class="input-group">
                  <input type="number" class="form-control dimension-input" id="inputLength" min="1" step="0.1">
                  <span class="input-group-text">cm</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Width (cm)</label>
                <div class="input-group">
                  <input type="number" class="form-control dimension-input" id="inputWidth" min="1" step="0.1">
                  <span class="input-group-text">cm</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Height (cm)</label>
                <div class="input-group">
                  <input type="number" class="form-control dimension-input" id="inputHeight" min="1" step="0.1">
                  <span class="input-group-text">cm</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Weight (kg)</label>
                <div class="input-group">
                  <input type="number" class="form-control dimension-input" id="inputWeight" min="0.1" step="0.1">
                  <span class="input-group-text">kg</span>
                </div>
              </div>
            </div>

            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="autoCalculate">
              <label class="form-check-label" for="autoCalculate">
                Estimate dimensions based on selected packages
              </label>
            </div>
          </div>
        </div>
        <!-- Combined Package Preview -->
        <div class="combined-preview-section mt-4">
          <div class="section-header">
            <h6 class="section-title">
              <i class="fas fa-cube me-2"></i>
              Combined Package Preview
            </h6>
          </div>

          <div class="preview-card">
            <div class="preview-visual">
              <div class="package-3d-preview">
                <div class="package-box">
                  <div class="box-face front"></div>
                  <div class="box-face back"></div>
                  <div class="box-face left"></div>
                  <div class="box-face right"></div>
                  <div class="box-face top"></div>
                  <div class="box-face bottom"></div>
                </div>
              </div>
            </div>

            <div class="preview-details">
              <div class="dimensions-display">
                <div class="dimension-item">
                  <span class="dimension-label">Length</span>
                  <span class="dimension-value" id="previewLength">-- cm</span>
                </div>
                <div class="dimension-item">
                  <span class="dimension-label">Width</span>
                  <span class="dimension-value" id="previewWidth">-- cm</span>
                </div>
                <div class="dimension-item">
                  <span class="dimension-label">Height</span>
                  <span class="dimension-value" id="previewHeight">-- cm</span>
                </div>
                <div class="dimension-item">
                  <span class="dimension-label">Weight</span>
                  <span class="dimension-value" id="previewWeight">-- kg</span>
                </div>
              </div>

              <div class="volume-display mt-3">
                <div class="volume-item">
                  <span class="volume-label">Total Volume</span>
                  <span class="volume-value" id="previewVolume">-- m³</span>
                </div>
              </div>
            </div>
          </div>
        </div>



        <!-- Hidden Fields -->
        <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?= $virtual_address_id; ?>">
      </div>

      <div class="modal-footer premium-modal-footer">
        <div class="footer-content combine">
          <div class="cost-estimate">
            <span class="estimate-label">Estimated Savings:</span>
            <span class="estimate-value text-success">~$15-25</span>
          </div>
          <div class="footer-actions">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-2"></i>Cancel
            </button>
            <button type="button" class="btn btn-primary" id="submitCombineRequest" disabled>
              <i class="fas fa-boxes me-2"></i> Combine and Repack
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content premium-modal">
      <div class="modal-header premium-modal-header">
        <div class="modal-icon">
          <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="modal-title-content">
          <h5 class="modal-title" id="bulkActionModalLabel">Shipping Configuration</h5>
          <p class="modal-subtitle" id="modalSubtitle">Configure shipping details for selected packages</p>
        </div>
        <button type="button" class="btn-shippex-orange btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body premium-modal-body">
        <div class="alert alert-info ">
          <i class="fas fa-info-circle me-2"></i>
          <span id="modalMessage">You are about to ship packages to a destination, from <span id="destinationCountry"></span></span>
        </div>

        <div class="shipping-wizard">
          <!-- Step 1: Destination Selection -->
          <div class="wizard-step active" id="stepDestination">
            <h6 class="step-title">Step 1: Select Destination</h6>
            <div class="destination-options">
              <div class="form-check card-option">
                <input class="form-check-input" type="radio" name="destinationType" id="destWarehouse" value="warehouse" checked>
                <label class="form-check-label" for="destWarehouse">
                  <div class="option-content">
                    <i class="fas fa-warehouse"></i>
                    <div>
                      <strong>Warehouse Address</strong>
                      <p class="mb-0">Ship to one of our warehouse locations</p>
                    </div>
                  </div>
                </label>
              </div>

              <div class="form-check card-option">
                <input class="form-check-input" type="radio" name="destinationType" id="destCustom" value="custom">
                <label class="form-check-label" for="destCustom">
                  <div class="option-content">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                      <strong>Custom Address</strong>
                      <p class="mb-0">Ship to a specific address</p>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Warehouse Selection -->
            <div class="destination-section mt-2" id="warehouseSection">
              <label class="form-label fw-semibold">Select Destination Warehouse</label>
              <div id="warehouseSelectContainer" class="mb-3">
                <div class="loading-placeholder">
                  <i class="fas fa-spinner fa-spin me-2"></i>Loading warehouses...
                </div>
              </div>
            </div>
            <div class="destination-section mt-2 form-group">
              <label class="form-label fw-semibold" for="categorySelect">Item Category</label>
              <select id="categorySelect" class="form-control">
                <option><i class="fas fa-spinner fa-spin me-2"></i> Loading categories...</option>
              </select>
            </div>
            <!-- Custom Address Form -->
            <div class="destination-section mt-2" id="customAddressSection" style="display: none;">
              <label class="form-label fw-semibold">Enter Destination Address</label>
              <div class="address-form-container">
                <form id="customAddressForm" class="needs-validation" novalidate>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label for="dest_line_1" class="form-label">Street Address *</label>
                      <input type="text" class="form-control" id="dest_line_1" name="dest_line_1" placeholder="123 Main Street" required>
                      <div class="invalid-feedback">Please provide a street address</div>
                    </div>
                    <div class="col-md-6">
                      <label for="dest_city" class="form-label">City *</label>
                      <input type="text" class="form-control" id="dest_city" name="dest_city" placeholder="New York" required>
                      <div class="invalid-feedback">Please provide a city</div>
                    </div>
                    <div class="col-md-6">
                      <label for="dest_state" class="form-label">State/Province</label>
                      <input type="text" class="form-control" id="dest_state" name="dest_state" placeholder="NY" required>
                      <div class="invalid-feedback">Please provide a State/Province</div>
                    </div>
                    <div class="col-md-6">
                      <label for="dest_postal_code" class="form-label">Postal Code</label>
                      <input type="text" class="form-control" id="dest_postal_code" name="dest_postal_code" placeholder="10001" required>
                      <div class="invalid-feedback">Please provide a postal code</div>
                    </div>
                    <div class="col-md-6">
                      <label for="dest_country" class="form-label">Country *</label>
                      <select class="form-select" id="dest_country" name="dest_country" required required>

                        <option value="">-- Select Country --</option>
                        <?php
                        $countries = json_decode(file_get_contents(APPPATH . 'Views/partials/countries.json'), true);
                        foreach ($countries as $ct): ?>
                          <option <?= $ct['code'] === 'US' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>" required>
                            <?= esc($ct['name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Please select a country</div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Step 2: Shipping Rates -->
          <div class="wizard-step" id="stepRates" style="display: none;">
            <h6 class="step-title">Step 2: Select Shipping Method</h6>
            <div class="rates-container">
              <div id="calculatedRates">
                <div class="loading-placeholder">
                  <i class="fas fa-spinner fa-spin me-2"></i>Calculating shipping rates...
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer premium-modal-footer">
        <div class="footer-content">
          <button type="button" class="btn btn-outline-secondary" id="prevStep" style="display: none;">
            <i class="fas fa-arrow-left me-2"></i>Back
          </button>
          <div class="ms-auto">
            <button type="button" class="btn btn-shippex-orange" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-shippex" id="nextStep">Next</button>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Config - endpoints (server-side base_url is used)
    const BULK_INFO_URL = '<?= base_url('packages/bulk-info') ?>';
    const SUBMIT_URL = '<?= base_url('packages/dispose-return-submit') ?>';

    // Elements
    const disposeModalEl = document.getElementById('disposeReturnBulkModal');
    if (!disposeModalEl) {
      console.error('#disposeReturnBulkModal not found. Please add the modal HTML.');
      return;
    }
    const disposeForm = document.getElementById('disposeReturnBulkForm');
    const tableBody = document.querySelector('#disposeReturnTable tbody');
    const bulkBtn = document.getElementById('bulkDisposeReturnBtn');
    const applyType = document.getElementById('bulkApplyType');
    const applyBtn = document.getElementById('applyToAllBtn');

    const bsModal = new bootstrap.Modal(disposeModalEl);

    // Helper: collect selected package ids from page checkboxes
    function getSelectedPackageIds() {
      return Array.from(document.querySelectorAll('.package-checkbox'))
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    }

    // Core: open modal for array of ids
    async function openDisposeReturnModalForIds(ids = []) {
      if (!Array.isArray(ids) || ids.length === 0) {
        return alert('Please select at least one package.');
      }

      tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Loading packages…</td></tr>';
      bsModal.show();

      try {
        const res = await fetch(BULK_INFO_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            package_ids: ids
          })
        });

        const json = await res.json();
        if (!json.success) {
          tableBody.innerHTML = `<tr><td colspan="5" class="text-danger">${json.message || 'Failed to load packages'}</td></tr>`;
          return;
        }

        // build rows
        tableBody.innerHTML = '';
        json.packages.forEach((p, idx) => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${idx + 1}</td>
          <td>#${p.id} <div class="small text-muted">${p.retailer ? p.retailer : ''} ${p.tracking_number ? '— ' + p.tracking_number : ''}</div></td>
          <td>${p.status || ''}</td>
          <td>
            <input type="hidden" name="package_ids[]" value="${p.id}">
            <select name="request_type[]" class="form-select form-select-sm">
              <option value="dispose">Dispose</option>
              <option value="return">Return</option>
            </select>
          </td>
          <td><input name="reason[]" type="text" class="form-control form-control-sm" required placeholder="Reason for this package"></td>
        `;
          tableBody.appendChild(tr);
        });

        // focus first reason
        const firstReason = tableBody.querySelector('input[name="reason[]"]');
        if (firstReason) firstReason.focus();

      } catch (err) {
        console.error(err);
        tableBody.innerHTML = '<tr><td colspan="5" class="text-danger">Server error while loading packages.</td></tr>';
      }
    }

    // open modal for currently selected checkboxes
    window.openDisposeReturnModal = function() {
      const ids = getSelectedPackageIds();
      openDisposeReturnModalForIds(ids);
    };

    // wire bulk button
    if (bulkBtn) {
      bulkBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.openDisposeReturnModal();
      });
    }

    // wire per-package buttons (delegated)
    document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.dispose-single-btn');
      if (!btn) return;
      e.preventDefault();
      const id = btn.dataset.packageId || btn.getAttribute('data-package-id');
      if (!id) return console.warn('dispose-single-btn missing data-package-id');
      openDisposeReturnModalForIds([id]);
    });

    // apply action to all rows inside modal
    if (applyBtn) {
      applyBtn.addEventListener('click', function() {
        const val = applyType.value;
        if (!val) return;
        tableBody.querySelectorAll('select[name="request_type[]"]').forEach(s => s.value = val);
      });
    }

    // submit handler
    if (disposeForm) {
      disposeForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // validation: ensure every reason non-empty
        const reasons = Array.from(tableBody.querySelectorAll('input[name="reason[]"]'));
        for (const r of reasons) {
          if (!r.value.trim()) {
            Swal.fire({
              icon: 'warning',
              title: 'Missing reason',
              text: 'Please provide a reason for every package.',
              confirmButtonColor: '#ff6600', // Optional: custom color
            });
            return;
          }
        }

        // build payload arrays
        const package_ids = Array.from(disposeForm.querySelectorAll('input[name="package_ids[]"]')).map(i => i.value);
        const request_type = Array.from(disposeForm.querySelectorAll('select[name="request_type[]"]')).map(s => s.value);
        const reason = reasons.map(i => i.value);

        // send
        try {
          const res = await fetch(SUBMIT_URL, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              package_ids,
              request_type,
              reason
            })
          });
          const json = await res.json();
          if (json.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              html: json.message || 'Requests submitted successfully',
              confirmButtonColor: '#28a745', // green success button
            }).then(() => {
              bsModal.hide();
              // Uncheck selected rows
              document.querySelectorAll('.package-checkbox:checked').forEach(cb => cb.checked = false);
              // Refresh page to reflect new requests/statuses
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops!',
              html: json.message || 'Failed to submit requests',
              confirmButtonColor: '#ff6600', // red error button
            });
          }

        } catch (err) {
          console.error(err);
          Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Server error while submitting requests.',
            confirmButtonColor: '#ff6600'
          });
        }
      });
    }

    // Accessibility: close modal cleanup when hidden
    disposeModalEl.addEventListener('hidden.bs.modal', () => {
      tableBody.innerHTML = '';
      disposeForm.classList.remove('was-validated');
      if (applyType) applyType.value = '';
    });

  });
</script>



<script>
  // Enhanced JavaScript for the combine modal with package count validation
  document.addEventListener('DOMContentLoaded', function() {
    let combineModalInstance = null;
    let selectedPackagesMap = new Map();

    function openCombineModal(packageIds) {
      const modalEl = document.getElementById('combineRepackModal');
      const warehouse_id = document.getElementById('warehouse_id').value;

      if (!combineModalInstance) {
        combineModalInstance = new bootstrap.Modal(modalEl);
      }

      // Populate packages grid
      updatePackagesGrid(packageIds);

      // Update selection count and validation
      updateSelectionCount(packageIds.length);
      validatePackageCount(packageIds.length);

      // Auto-calculate dimensions if enabled and enough packages
      if (document.getElementById('autoCalculate').checked && packageIds.length >= 2) {
        calculateCombinedDimensions(packageIds);
      } else {
        resetDimensions();
      }

      // Submit handler
      document.getElementById('submitCombineRequest').onclick = function() {
        if (packageIds.length < 2) {
          Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Please select at least 2 packages to combine.',
            confirmButtonColor: '#ff6600'
          });
          return;
        }

        const dimensions = getCurrentDimensions();

        fetch('/packages/combine-request', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              package_ids: packageIds,
              warehouse_id: warehouse_id,
              dimensions: dimensions
            })
          })
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Request Sent',
                text: 'Combine & Repack request submitted successfully!',
                confirmButtonColor: '#28a745'
              }).then(() => {
                combineModalInstance.hide();
                // Refresh page to reflect new requests/statuses
                location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Error:' + res.message,
                confirmButtonColor: '#ff6600'
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Oops!',
              text: 'Network error: ' + error.message,
              confirmButtonColor: '#ff6600'
            });
          });
      };

      combineModalInstance.show();
    }

    function updatePackagesGrid(packageIds) {
      const grid = document.getElementById('selectedPackagesGrid');
      grid.innerHTML = '';

      if (packageIds.length === 0) {
        grid.innerHTML = `
        <div class="empty-state">
          <i class="fas fa-box-open text-muted mb-3" style="font-size: 2rem;"></i>
          <p class="text-muted mb-0">No packages selected</p>
        </div>
      `;
        return;
      }

      packageIds.forEach(id => {
        const packageCard = createPackageCard(id);
        grid.appendChild(packageCard);
      });
    }

    function createPackageCard(packageId) {
      const card = document.createElement('div');
      card.className = 'package-card';
      card.innerHTML = `
      <div class="package-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="package-info">
        <div class="package-id">#${packageId}</div>
        <div class="package-details">2.5kg • 30×20×15cm</div>
      </div>
      <button class="remove-package" onclick="removePackage('${packageId}')">
        <i class="fas fa-times"></i>
      </button>
    `;
      return card;
    }

    function updateSelectionCount(count) {
      const badge = document.getElementById('selectedCountBadge');
      badge.textContent = count;

      // Update badge color based on count
      if (count < 2) {
        badge.className = 'badge bg-warning ms-2';
      } else {
        badge.className = 'badge bg-primary ms-2';
      }
    }

    function validatePackageCount(count) {
      const submitButton = document.getElementById('submitCombineRequest');
      const validationAlert = document.getElementById('packageValidationAlert');
      const dimensionsSection = document.querySelector('.dimensions-config-section');
      const previewSection = document.querySelector('.combined-preview-section');

      if (count < 2) {
        // Show validation alert
        validationAlert.style.display = 'block';

        // Disable submit button
        submitButton.disabled = true;

        // Hide dimensions and preview sections
        dimensionsSection.style.display = 'none';
        previewSection.style.display = 'none';
      } else {
        // Hide validation alert
        validationAlert.style.display = 'none';

        // Enable submit button
        submitButton.disabled = false;

        // Show dimensions and preview sections
        dimensionsSection.style.display = 'block';
        previewSection.style.display = 'block';
      }
    }

    function calculateCombinedDimensions(packageIds) {
      // Only calculate if we have at least 2 packages
      if (packageIds.length < 2) {
        resetDimensions();
        return;
      }

      // Mock calculation - in real implementation, you would fetch package details
      const totalLength = packageIds.length * 10 + 20;
      const totalWidth = packageIds.length * 8 + 15;
      const totalHeight = packageIds.length * 5 + 10;
      const totalWeight = packageIds.length * 1.5;

      // Update inputs
      document.getElementById('inputLength').value = totalLength.toFixed(1);
      document.getElementById('inputWidth').value = totalWidth.toFixed(1);
      document.getElementById('inputHeight').value = totalHeight.toFixed(1);
      document.getElementById('inputWeight').value = totalWeight.toFixed(1);

      // Update preview
      updatePreview(totalLength, totalWidth, totalHeight, totalWeight);
    }

    function updatePreview(length, width, height, weight) {
      document.getElementById('previewLength').textContent = length.toFixed(1) + ' cm';
      document.getElementById('previewWidth').textContent = width.toFixed(1) + ' cm';
      document.getElementById('previewHeight').textContent = height.toFixed(1) + ' cm';
      document.getElementById('previewWeight').textContent = weight.toFixed(1) + ' kg';

      const volume = (length * width * height) / 1000000; // Convert to m³
      document.getElementById('previewVolume').textContent = volume.toFixed(3) + ' m³';
    }

    function getCurrentDimensions() {
      return {
        length: parseFloat(document.getElementById('inputLength').value) || 0,
        width: parseFloat(document.getElementById('inputWidth').value) || 0,
        height: parseFloat(document.getElementById('inputHeight').value) || 0,
        weight: parseFloat(document.getElementById('inputWeight').value) || 0
      };
    }



    function resetDimensions() {
      document.getElementById('inputLength').value = '';
      document.getElementById('inputWidth').value = '';
      document.getElementById('inputHeight').value = '';
      document.getElementById('inputWeight').value = '';
      updatePreview(0, 0, 0, 0);
    }

    // Event listeners for dimension inputs
    document.querySelectorAll('.dimension-input').forEach(input => {
      input.addEventListener('input', function() {
        const dimensions = getCurrentDimensions();
        if (dimensions.length && dimensions.width && dimensions.height && dimensions.weight) {
          updatePreview(dimensions.length, dimensions.width, dimensions.height, dimensions.weight);
        }
      });
    });

    // Auto-calculate toggle
    document.getElementById('autoCalculate').addEventListener('change', function() {
      if (this.checked) {
        const selectedPackages = Array.from(selectedPackagesMap.keys());
        if (selectedPackages.length >= 2) {
          calculateCombinedDimensions(selectedPackages);
        }
      } else {
        resetDimensions();
      }
    });

    // Clear selection button
    document.getElementById('clearSelection').addEventListener('click', function() {
      selectedPackagesMap.clear();
      updatePackagesGrid([]);
      updateSelectionCount(0);
      validatePackageCount(0);
      resetDimensions();
    });

    // Make functions available globally for onclick handlers
    window.removePackage = function(packageId) {
      selectedPackagesMap.delete(packageId);
      const selectedPackages = Array.from(selectedPackagesMap.keys());
      updatePackagesGrid(selectedPackages);
      updateSelectionCount(selectedPackages.length);
      validatePackageCount(selectedPackages.length);

      if (document.getElementById('autoCalculate').checked && selectedPackages.length >= 2) {
        calculateCombinedDimensions(selectedPackages);
      } else {
        resetDimensions();
      }
    };

    // Update the existing openCombineModal function to use selectedPackagesMap
    const originalOpenCombineModal = window.openCombineModal;
    window.openCombineModal = function(packageIds) {
      selectedPackagesMap = new Map(packageIds.map(id => [id, true]));
      openCombineModal(packageIds);
    };
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.package-checkbox');
    const bulkCard = document.getElementById('bulkActionsCard');
    const selectionCount = document.getElementById('selectionCount');
    const bulkButtons = document.querySelectorAll('.bulk-action');
    const modalEl = document.getElementById('bulkActionModal');
    const combineModal = document.getElementById('combineRepackModal');
    const modalTitle = document.getElementById('bulkActionModalLabel');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const modalMessage = document.getElementById('modalMessage');

    // Initialize modal
    let modal = null;
    if (modalEl) {
      modal = new bootstrap.Modal(modalEl);
    }
    modalEl.addEventListener('show.bs.modal', () => {
      if (currentAction === 'ship') {
        loadCategories();
      }
    });

    // Wizard elements
    const stepDestination = document.getElementById('stepDestination');
    const stepRates = document.getElementById('stepRates');
    const prevStepBtn = document.getElementById('prevStep');
    const nextStepBtn = document.getElementById('nextStep');
    const destWarehouseRadio = document.getElementById('destWarehouse');
    const destCustomRadio = document.getElementById('destCustom');
    const warehouseSection = document.getElementById('warehouseSection');
    const customAddressSection = document.getElementById('customAddressSection');
    const warehouseContainer = document.getElementById('warehouseSelectContainer');
    const destinationCountry = document.getElementById('destinationCountry');
    const ratesContainer = document.getElementById('calculatedRates');

    let currentStep = 1;
    let selectedPackages = [];
    let currentAction = '';
    let currentOrigin = null;
    let currentRates = [];
    let availableWarehouses = [];
    let currentDestination = null;



    // Selection management
    function getSelectedPackages() {
      return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
    }

    function updateSelectionUI() {
      const count = getSelectedPackages().length;
      selectionCount.textContent = `${count} package${count !== 1 ? 's' : ''} selected`;
      bulkCard.style.display = count > 0 ? 'block' : 'none';
      selectAll.indeterminate = count > 0 && count < checkboxes.length;
      selectAll.checked = count === checkboxes.length;
    }

    selectAll?.addEventListener('change', () => {
      checkboxes.forEach(cb => cb.checked = selectAll.checked);
      updateSelectionUI();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateSelectionUI));

    // Bulk action click handler
    bulkButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        selectedPackages = getSelectedPackages();
        if (selectedPackages.length === 0) {
          alert('Please select at least one package');
          return;
        }

        currentAction = btn.dataset.action || '';
        const action = btn.dataset.action;

        // Reset wizard
        currentStep = 1;
        showStep(currentStep);
        if (action === 'combine') {
          openCombineModal(selectedPackages, btn.textContent.trim());


        } else {
          if (currentAction === 'ship') {
            modalTitle.textContent = 'Shipping Configuration';
            modalSubtitle.textContent = `Configure shipping for ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}`;

            // Load warehouses
            loadWarehouses();
            // load categories
            loadCategories();

          } else {
            modalTitle.textContent = btn.textContent.trim();
            modalSubtitle.textContent = `Confirm action for ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}`;
            modalMessage.textContent = `You are about to ${btn.textContent.trim().toLowerCase()} ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}.`;
          }
          if (modal) modal.show();
        }

      });
    });

    // Wizard navigation
    function showStep(step) {
      // Hide all steps
      stepDestination.style.display = 'none';
      stepRates.style.display = 'none';
      nextStepBtn.style.display = 'inline-block';
      prevStepBtn.style.display = 'none';

      // Show current step
      if (step === 1) {
        stepDestination.style.display = 'block';
        nextStepBtn.textContent = 'Calculate Rates';
        prevStepBtn.style.display = 'none';
      } else if (step === 2) {
        stepRates.style.display = 'block';
        nextStepBtn.style.display = 'none';
        prevStepBtn.style.display = 'inline-block';
        calculateRates();
      }
    }

    nextStepBtn.addEventListener('click', () => {
      if (currentStep === 1) {
        // Validate destination selection
        if (destWarehouseRadio.checked) {
          const warehouseSelect = document.getElementById('destWarehouseSelect');
          if (!warehouseSelect || !warehouseSelect.value) {
            Swal.fire({
              icon: 'error',
              title: 'Oops!',
              text: 'Please select a destination warehouse',
              confirmButtonColor: '#ff6600'
            });

            return;
          }
        } else {
          // Validate custom address form
          const form = document.getElementById('customAddressForm');
          if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
          }
        }

        currentStep = 2;
        showStep(currentStep);
      }
    });

    prevStepBtn.addEventListener('click', () => {
      currentStep = 1;
      showStep(currentStep);
    });

    // Destination type toggle
    destWarehouseRadio.addEventListener('change', () => {
      warehouseSection.style.display = 'block';
      customAddressSection.style.display = 'none';
    });

    destCustomRadio.addEventListener('change', () => {
      warehouseSection.style.display = 'none';
      customAddressSection.style.display = 'block';
    });

    // Load warehouses
    function loadWarehouses() {
      warehouseContainer.innerHTML = '<div class="loading-placeholder"><i class="fas fa-spinner fa-spin me-2"></i>Loading warehouses...</div>';

      fetch('<?= base_url('packages/shipping-data') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            package_ids: selectedPackages
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.error) {
            warehouseContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
          }
          console.log('whs', data);
          currentOrigin = data.currentWarehouse;
          availableWarehouses = data.availableWarehouses || [];
          const totals = data.packageTotals;
          window.packageTotals = totals;

          if (availableWarehouses.length === 0) {
            warehouseContainer.innerHTML = '<div class="alert alert-warning">No warehouses available.</div>';
            return;
          }

          let html = '<select class="form-select" id="destWarehouseSelect" style="padding: 0.75rem 1rem; border-radius: 8px;">';
          availableWarehouses.forEach(w => {
            html += `<option value="${w.id}" ${w.id === currentOrigin.id ? 'style="display:none;"' : ''}>
            ${w.country} - ${w.address_line_1}, ${w.city}, ${w.state}, ${w.postal_code}
          </option>`;
          });
          html += '</select>';
          warehouseContainer.innerHTML = html;

          let desHtml = `<i class="fi fi-${currentOrigin.code.toLowerCase()}"></i> <b>${currentOrigin.country}</b> Warehouse`;

          destinationCountry.innerHTML = desHtml;
        })
        .catch(e => {
          warehouseContainer.innerHTML = '<div class="alert alert-danger">Failed to load warehouses</div>';
          console.error(e);
        });
    }

    // Calculate shipping rates
    function calculateRates() {
      ratesContainer.innerHTML = '<div class="loading-placeholder"><i class="fas fa-spinner fa-spin me-2"></i>Calculating shipping rates...</div>';

      let dest;
      if (destWarehouseRadio.checked) {
        const warehouseSelect = document.getElementById('destWarehouseSelect');
        const selectedWarehouseId = warehouseSelect.value;

        // Find the selected warehouse from availableWarehouses
        const selectedWarehouse = availableWarehouses.find(w => w.id == selectedWarehouseId);

        if (!selectedWarehouse) {
          ratesContainer.innerHTML = '<div class="alert alert-danger">Invalid warehouse selection</div>';
          return;
        }

        dest = {
          line_1: selectedWarehouse.address_line_1,
          city: selectedWarehouse.city,
          state: selectedWarehouse.state,
          postal_code: selectedWarehouse.postal_code,
          country: selectedWarehouse.country
        };

      } else {
        dest = {
          line_1: document.getElementById('dest_line_1').value,
          city: document.getElementById('dest_city').value,
          state: document.getElementById('dest_state').value,
          postal_code: document.getElementById('dest_postal_code').value,
          country: document.getElementById('dest_country').value
        };
      }

      // Store current destination for booking
      currentDestination = dest;

      const totals = window.packageTotals;
      const categorySelect = document.getElementById('categorySelect');
      const selectedCategory = categorySelect.value;
      const hsCode = categorySelect.options[categorySelect.selectedIndex].dataset.hsCode;

      fetch('<?= base_url('packages/getRates') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            origin_line_1: currentOrigin.address_line_1,
            origin_city: currentOrigin.city,
            origin_state: currentOrigin.state,
            origin_postal_code: currentOrigin.postal_code,
            origin_country: currentOrigin.country,
            dest_line_1: dest.line_1,
            dest_city: dest.city,
            dest_state: dest.state,
            dest_postal_code: dest.postal_code,
            dest_country: dest.country,
            weight: totals.weight,
            category: selectedCategory,
            hs_code: hsCode,
            length: totals.length,
            width: totals.width,
            height: totals.height
          })
        })
        .then(r => r.json())
        .then(response => {
          const rates = response?.data?.rates || [];
          currentRates = rates;

          if (!rates.length) {
            ratesContainer.innerHTML = '<div class="alert alert-warning">No shipping rates available for this route.</div>';
            return;
          }

          let tableHTML = `
          <table class="table rates-table">
            <thead>
              <tr>
                <th>Courier</th>
                <th>Service</th>
                <th>Delivery Time</th>
                <th>Rate</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
        `;

          rates.forEach(rate => {
            tableHTML += `
            <tr>
              <td><strong>${rate.courier_service?.umbrella_name || '-'}</strong></td>
              <td>${rate.courier_service?.name || '-'}</td>
              <td class="rate-delivery">${rate.min_delivery_time} - ${rate.max_delivery_time} days</td>
              <td class="rate-price">${rate.currency} ${rate.total_charge.toFixed(2)}</td>
              <td><small class="text-muted">${rate.full_description || '-'}</small></td>
              <td>
                <button class="btn btn-primary btn-sm book-btn" 
                  data-rate='${JSON.stringify(rate).replace(/'/g, "\\'")}'>
                  Book
                </button>
              </td>
            </tr>
          `;
          });

          tableHTML += '</tbody></table>';
          ratesContainer.innerHTML = tableHTML;

          // Add event listeners to book buttons
          document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              const rate = JSON.parse(this.dataset.rate);
              bookShipping(rate);
            });
          });
        })
        .catch(e => {
          ratesContainer.innerHTML = '<div class="alert alert-danger">Failed to calculate rates. Please try again.</div>';
          console.error(e);
        });
    }
    // get categories
    async function loadCategories() {
      const categorySelect = document.getElementById('categorySelect');
      if (!categorySelect) return;

      categorySelect.innerHTML = '<option>Loading...</option>';

      try {
        const response = await fetch('<?= base_url('packages/shipping-categories') ?>');
        const data = await response.json();

        if (data.success && data.categories.length) {
          categorySelect.innerHTML = data.categories
            .map(cat => `
          <option 
            value="${cat.slug || cat.name}" 
            data-hs-code="${cat.hs_code || ''}">
            ${cat.name}
          </option>
        `)
            .join('');
        } else {
          categorySelect.innerHTML = '<option>No categories available</option>';
        }
      } catch (err) {
        console.error('Error loading categories:', err);
        categorySelect.innerHTML = '<option>Error loading categories</option>';
      }
    }



    // Book shipping function
    function bookShipping(rate) {
      const totals = window.packageTotals;

      // Prepare booking data matching your PHP backend expectations
      const bookingData = new URLSearchParams();

      // Origin address
      bookingData.append('origin_line_1', currentOrigin.address_line_1 || '');
      bookingData.append('origin_city', currentOrigin.city || '');
      bookingData.append('origin_state', currentOrigin.state || '');
      bookingData.append('origin_postal_code', currentOrigin.postal_code || '');
      bookingData.append('origin_country', currentOrigin.country || '');

      // Destination address
      bookingData.append('dest_line_1', currentDestination.line_1 || '');
      bookingData.append('dest_city', currentDestination.city || '');
      bookingData.append('dest_state', currentDestination.state || '');
      bookingData.append('dest_postal_code', currentDestination.postal_code || '');
      bookingData.append('dest_country', currentDestination.country || '');

      // Parcel details
      bookingData.append('weight', totals.weight || '');
      bookingData.append('length', totals.length || '');
      bookingData.append('width', totals.width || '');
      bookingData.append('height', totals.height || '');
      bookingData.append('category', 'general');

      // Rate information
      bookingData.append('courier_name', rate.courier_service?.umbrella_name || '');
      bookingData.append('service_name', rate.courier_service?.name || '');
      bookingData.append('delivery_time', `${rate.min_delivery_time} - ${rate.max_delivery_time} days`);
      bookingData.append('currency', rate.currency || '');
      bookingData.append('total_charge', rate.total_charge || '');
      bookingData.append('description', rate.full_description || '');

      // Show loading state
      const bookBtn = event.target;
      const originalText = bookBtn.innerHTML;
      bookBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
      bookBtn.disabled = true;

      // Send booking request as form data
      fetch('<?= base_url('shipping/book') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: bookingData
        })
        .then(response => response.json())
        .then(data => {

          if (data.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Booking Confirmed!',
              html: `✅ Your booking has been confirmed.<br><b>Booking ID:</b> ${data.booking_id}`,
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didClose: () => {
                let redirectUrl = '';
                if (data.role === 'customer') {
                  redirectUrl = "<?= site_url('customer/shipping/details/') ?>" + data.booking_id;
                } else {
                  redirectUrl = "<?= site_url('shipping/details/') ?>" + data.booking_id;
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
        })
        .catch(error => {
          console.error('Booking error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: 'An unexpected error occurred while booking.',
            confirmButtonColor: '#d33'
          });
          // Reset button
          bookBtn.innerHTML = originalText;
          bookBtn.disabled = false;
        });
    }



    // Form validation
    const customAddressForm = document.getElementById('customAddressForm');
    customAddressForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
      }
    }, false);

    // Initialize countries dropdown for custom address
    function initializeCountriesDropdown() {
      const countrySelect = document.getElementById('dest_country');
      if (countrySelect) {
        // This would typically be populated from your countries JSON
        // For now, we'll keep the existing PHP-generated options
      }
    }

    initializeCountriesDropdown();
  });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>