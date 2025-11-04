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
        <?php if (session()->get('role') == 'admin'): ?>
          <div>
            <a href="<?= base_url('packages/create') ?>" class="btn btn-outline-shippex-orange">+ Add new Package</a>
          </div>
        <?php endif; ?>
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
          <a href="<?= base_url('packages/create') ?>" class="btn btn-shippex mt-3">
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
                      <form action="<?= base_url('packages/' . $p['id'] . '/delete') ?>" method="post" class="d-inline delete-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
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
          <div class="alert alert-info summary-alert mb-4">
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
        <div class="footer-content">
          <div class="cost-estimate">
            <span class="estimate-label">Estimated Savings:</span>
            <span class="estimate-value text-success">~$15-25</span>
          </div>
          <div class="footer-actions">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-2"></i>Cancel
            </button>
            <button type="button" class="btn btn-primary" id="submitCombineRequest" disabled>
              <i class="fas fa-boxes me-2"></i>Request Combine and Repack
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
        <div class="alert alert-info mb-4">
          <i class="fas fa-info-circle me-2"></i>
          <span id="modalMessage">You are about to ship packages to a destination.</span>
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
            <div class="destination-section mt-4" id="warehouseSection">
              <label class="form-label fw-semibold">Select Destination Warehouse</label>
              <div id="warehouseSelectContainer" class="mb-3">
                <div class="loading-placeholder">
                  <i class="fas fa-spinner fa-spin me-2"></i>Loading warehouses...
                </div>
              </div>
            </div>
            <div class="destination-section mt-4 form-group">
              <label class="form-label fw-semibold" for="categorySelect">Item Category</label>
              <select id="categorySelect" class="form-control">
                <option><i class="fas fa-spinner fa-spin me-2"></i> Loading categories...</option>
              </select>
            </div>
            <!-- Custom Address Form -->
            <div class="destination-section mt-4" id="customAddressSection" style="display: none;">
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
                      <input type="text" class="form-control" id="dest_state" name="dest_state" placeholder="NY">
                    </div>
                    <div class="col-md-6">
                      <label for="dest_postal_code" class="form-label">Postal Code</label>
                      <input type="text" class="form-control" id="dest_postal_code" name="dest_postal_code" placeholder="10001">
                    </div>
                    <div class="col-md-6">
                      <label for="dest_country" class="form-label">Country *</label>
                      <select class="form-select" id="dest_country" name="dest_country" required>

                        <option value="">-- Select Country --</option>
                        <?php
                        $countries = json_decode(file_get_contents(APPPATH . 'views/partials/countries.json'), true);
                        foreach ($countries as $ct): ?>
                          <option <?= $ct['code'] === 'US' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>">
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
            <button type="button" class="btn btn-success" id="confirmBulkAction" style="display: none;">
              <i class="fas fa-check me-2"></i>Confirm Shipping
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  /* package inbox styles */
  :root {
    --border-radius: 12px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
  }

  .premium-package-container {
    background-color: #f8fafc;
    min-height: 100vh;
  }

  /* Header Styling */
  .premium-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a0d6b 100%);
    color: white;
    padding: 1.5rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 15px 15px;
  }

  .page-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
  }

  .page-subtitle {
    opacity: 0.85;
    margin-bottom: 0;
  }

  /* Table Styling */
  .package-table-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
  }

  .package-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
  }

  .package-table thead th {
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 1rem 0.75rem;
    font-weight: 700;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
  }

  .package-table tbody tr {
    transition: var(--transition);
  }

  .package-table tbody tr:hover {
    background-color: rgba(77, 20, 140, 0.03);
  }

  .package-table tbody td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }

  .select-col {
    width: 50px;
    text-align: center;
  }

  .actions-col {
    width: 150px;
  }

  /* Retailer Info in Table */
  .retailer-info-table {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .retailer-logo-table {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary-color), #5e35b1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
  }

  .retailer-details-table h6 {
    margin: 0;
    font-weight: 700;
    color: var(--primary-color);
  }

  .tracking-number-table {
    font-size: 0.8rem;
    color: #64748b;
    font-family: monospace;
  }

  /* Status Badges */
  .status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-ready {
    background-color: rgba(40, 167, 69, 0.15);
    color: var(--shippex-success);
  }

  .status-incoming {
    background-color: rgba(255, 193, 7, 0.15);
    color: #ffc107;
  }

  .status-shipped {
    background-color: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
  }

  /* Action Buttons in Table */
  .action-buttons-table {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
  }

  .btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: var(--transition);
  }

  .btn-action.view {
    background-color: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
  }

  .btn-action.edit {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
  }

  .btn-action.delete {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--shippex-accent);
  }

  .btn-action:hover {
    transform: scale(1.1);
    color: white;
  }

  .btn-action.view:hover {
    background-color: #17a2b8;
  }

  .btn-action.edit:hover {
    background-color: #ffc107;
  }

  .btn-action.delete:hover {
    background-color: var(--shippex-accent);
  }

  /* Bulk Actions */
  .bulk-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
  }

  .bulk-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
  }

  .bulk-header h5 {
    margin: 0;
    color: var(--primary-color);
    font-weight: 700;
  }

  .selection-count {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
  }

  .bulk-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }

  .bulk-action {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    border: 2px solid #e2e8f0;
    background: white;
    color: #64748b;
    transition: var(--transition);
    display: flex;
    align-items: center;
  }

  .bulk-action.ship-now {
    border-color: var(--shippex-success);
    color: var(--shippex-success);
  }

  .bulk-action.combine-repack {
    border-color: var(--primary-color);
    color: var(--primary-color);
  }

  .bulk-action.dispose {
    border-color: var(--shippex-accent);
    color: var(--shippex-accent);
  }

  .bulk-action.return {
    border-color: #ffc107;
    color: #ffc107;
  }

  .bulk-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: white;
  }

  .bulk-action.ship-now:hover {
    background: var(--shippex-success);
  }

  .bulk-action.combine-repack:hover {
    background: var(--primary-color);
  }

  .bulk-action.dispose:hover {
    background: var(--shippex-accent);
  }

  .bulk-action.return:hover {
    background: #ffc107;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 3rem 1rem;
  }

  .empty-icon {
    font-size: 4rem;
    color: rgba(77, 20, 140, 0.2);
    margin-bottom: 1.5rem;
  }

  .empty-state h4 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
  }

  .empty-state p {
    color: var(--secondary-color);
    max-width: 400px;
    margin: 0 auto;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .premium-header {
      padding: 1.5rem 0;
    }

    .page-title {
      font-size: 1.5rem;
    }

    .package-table {
      font-size: 0.9rem;
    }

    .retailer-info-table {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.25rem;
    }

    .bulk-actions {
      flex-direction: column;
    }

    .action-buttons-table {
      flex-direction: column;
      gap: 0.25rem;
    }
  }

  /* Premium Modal Styling */
  .premium-modal {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    overflow: hidden;
  }

  .premium-modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a0d6b 100%);
    color: white;
    padding: 1rem 2rem;
    border-bottom: none;
    position: relative;
  }

  .premium-modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #00ff87, #60efff);
  }

  .premium-modal-header .modal-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
  }

  .premium-modal-header .modal-title-content {
    flex: 1;
  }

  .premium-modal-header .modal-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
  }

  .premium-modal-header .modal-subtitle {
    opacity: 0.9;
    margin-bottom: 0;
    font-size: 0.75rem;
  }

  .premium-modal-body {
    padding: 2rem;
    background: #f8fafc;
  }

  .premium-modal-footer {
    background: white;
    border-top: 1px solid #e9ecef;
    padding: 1rem 2rem;
  }

  .premium-modal-footer .footer-content {
    display: flex;
    align-items: center;
    width: 100%;
  }

  /* Wizard Steps */
  .shipping-wizard {
    /* background: white; */
    /* border-radius: 12px; */
    padding: 1rem;
    /* box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); */
  }

  .wizard-step {
    animation: fadeIn 0.3s ease-in;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .step-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
  }

  /* Destination Options */
  .destination-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .card-option {
    margin: 0;
  }

  .card-option .form-check-input {
    display: none;
  }

  .card-option .form-check-label {
    display: block;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
  }

  .card-option .form-check-input:checked+.form-check-label {
    border-color: var(--primary-color);
    background: rgba(77, 20, 140, 0.05);
    box-shadow: 0 4px 15px rgba(77, 20, 140, 0.1);
  }

  .card-option .form-check-label:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
  }

  .option-content {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .option-content i {
    font-size: 1.5rem;
    color: var(--primary-color);
    width: 40px;
    height: 40px;
    background: rgba(77, 20, 140, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .option-content strong {
    display: block;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
  }

  .option-content p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
  }

  /* Destination Sections */
  .destination-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
  }

  .address-form-container {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
  }

  .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
  }

  .form-control,
  .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(77, 20, 140, 0.1);
  }

  /* Loading Placeholder */
  .loading-placeholder {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    background: white;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
  }

  /* Rates Table */
  .rates-container {
    max-height: 400px;
    overflow-y: auto;
  }

  .rates-table {
    width: 100%;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }

  .rates-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    padding: 1rem;
    text-align: left;
  }

  .rates-table td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
  }

  .rates-table tr:last-child td {
    border-bottom: none;
  }

  .rates-table tr:hover {
    background: #f8f9fa;
  }

  .rate-price {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1.1rem;
  }

  .rate-delivery {
    color: #6c757d;
    font-size: 0.9rem;
  }

  .book-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .book-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .destination-options {
      grid-template-columns: 1fr;
    }

    .premium-modal-header {
      padding: 1rem;
    }

    .premium-modal-body {
      padding: 1rem;
    }

    .shipping-wizard {
      padding: 1rem;
    }
  }

  /* Premium Combine Modal Styles */
  .premium-combine-modal {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .modal-icon-container .modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
  }

  .modal-icon-container .modal-icon i {
    font-size: 1.25rem;
    color: white;
  }

  .modal-title-content .modal-title {
    font-weight: 700;
    margin-bottom: 0.25rem;
    font-size: 1.25rem;
  }

  .modal-title-content .modal-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 0.875rem;
  }

  .btn-close-modal {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.2s ease;
  }

  .btn-close-modal:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }

  .premium-modal-body {
    padding: 1.5rem;
    background: #f8f9fa;
  }

  /* Package Validation Alert */
  .package-validation-alert {
    border-radius: 8px;
    border-left: 4px solid #ffc107;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
  }

  /* Section Headers */
  .section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
  }

  .section-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
  }

  .section-actions {
    margin-left: auto;
  }

  /* Packages Grid */
  .packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.75rem;
    max-height: 200px;
    overflow-y: auto;
    padding: 0.5rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }

  .package-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s ease;
  }

  .package-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
  }

  .package-icon {
    width: 32px;
    height: 32px;
    background: #f7fafc;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
  }

  .package-info {
    flex: 1;
  }

  .package-id {
    font-weight: 600;
    font-size: 0.875rem;
    color: #2d3748;
  }

  .package-details {
    font-size: 0.75rem;
    color: #718096;
  }

  .remove-package {
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
  }

  .remove-package:hover {
    color: #e53e3e;
    background: #fed7d7;
  }

  .empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2rem;
    color: #a0aec0;
  }

  /* Combined Preview */
  .preview-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1.5rem;
    border: 1px solid #e2e8f0;
  }

  .preview-visual {
    flex: 0 0 120px;
  }

  .package-3d-preview {
    perspective: 1000px;
    height: 120px;
  }

  .package-box {
    width: 80px;
    height: 60px;
    position: relative;
    transform-style: preserve-3d;
    transform: rotateX(-15deg) rotateY(15deg);
    margin: 0 auto;
  }

  .box-face {
    position: absolute;
    border: 1px solid #667eea;
    background: rgba(102, 126, 234, 0.1);
  }

  .box-face.front,
  .box-face.back {
    width: 80px;
    height: 60px;
  }

  .box-face.front {
    transform: translateZ(20px);
  }

  .box-face.back {
    transform: translateZ(-20px) rotateY(180deg);
  }

  .box-face.left,
  .box-face.right {
    width: 40px;
    height: 60px;
  }

  .box-face.left {
    transform: translateX(-40px) rotateY(-90deg);
  }

  .box-face.right {
    transform: translateX(40px) rotateY(90deg);
  }

  .box-face.top,
  .box-face.bottom {
    width: 80px;
    height: 40px;
  }

  .box-face.top {
    transform: translateY(-30px) rotateX(90deg);
  }

  .box-face.bottom {
    transform: translateY(30px) rotateX(-90deg);
  }

  .preview-details {
    flex: 1;
  }

  .dimensions-display {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }

  .dimension-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: #f7fafc;
    border-radius: 6px;
  }

  .dimension-label {
    font-size: 0.875rem;
    color: #718096;
  }

  .dimension-value {
    font-weight: 600;
    color: #2d3748;
  }

  .volume-display {
    padding: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    color: white;
  }

  .volume-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .volume-label {
    font-size: 0.875rem;
    opacity: 0.9;
  }

  .volume-value {
    font-weight: 700;
    font-size: 1.125rem;
  }

  /* Dimensions Form */
  .dimensions-config-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
  }

  .dimension-input {
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 0.75rem;
  }

  .dimension-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  /* Footer */
  .premium-modal-footer {
    background: white;
    border-top: 1px solid #e2e8f0;
    padding: 1.25rem 1.5rem;
  }

  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .cost-estimate {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .estimate-label {
    font-size: 0.875rem;
    color: #718096;
  }

  .estimate-value {
    font-weight: 600;
    font-size: 1rem;
  }

  .footer-actions {
    display: flex;
    gap: 0.75rem;
  }

  .btn-primary {
    background-color: var(--primary-color);
    border: none;
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    transition: all 0.2s ease;
  }

  .btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    background-color: var(--secondary-color);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }

  .btn-primary:disabled {
    background: #cbd5e0;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
  }

  .btn-outline-secondary {
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
  }

  /* Scrollbar Styling */
  .packages-grid::-webkit-scrollbar {
    width: 6px;
  }

  .packages-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  .packages-grid::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
  }

  .packages-grid::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
  }

  /* Premium Dispose Modal Styles */
  .premium-dispose-modal {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .premium-modal-header {
    background: linear-gradient(135deg, #f6ad55 0%, #fc8181 100%);
    color: white;
    padding: 1.5rem;
    border-bottom: none;
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .modal-icon-container .modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
  }

  .modal-icon-container .modal-icon i {
    font-size: 1.25rem;
    color: white;
  }

  .modal-title-content .modal-title {
    font-weight: 700;
    margin-bottom: 0.25rem;
    font-size: 1.25rem;
  }

  .modal-title-content .modal-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 0.875rem;
  }

  .btn-close-modal {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.2s ease;
    margin-left: auto;
  }

  .btn-close-modal:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }

  .premium-modal-body {
    padding: 1.5rem;
    background: #f8f9fa;
  }

  /* Summary Alert */
  .summary-alert {
    border-radius: 8px;
    border-left: 4px solid #4299e1;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
  }

  /* Table Styling */
  .table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }

  .table-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f7fafc;
  }

  .table-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
  }

  .table {
    margin: 0;
  }

  .table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    padding: 1rem 0.75rem;
    background: #f8f9fa;
  }

  .table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
  }

  .table tbody tr:last-child td {
    border-bottom: none;
  }

  .table tbody tr:hover {
    background-color: #f8f9fa;
  }

  .loading-row td {
    border-bottom: none !important;
  }

  .loading-spinner {
    color: #6c757d;
    font-weight: 500;
  }

  /* Package Details Styling */
  .package-details-cell {
    min-width: 200px;
  }

  .package-id {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
  }

  .package-meta {
    font-size: 0.8rem;
    color: #718096;
    margin-top: 0.25rem;
  }

  .retailer-name {
    color: #4a5568;
  }

  .tracking-number {
    color: #667eea;
    font-family: monospace;
  }

  /* Status Badge */
  .status-badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    text-transform: capitalize;
  }

  .status-arrived {
    background: #c6f6d5;
    color: #276749;
  }

  .status-stored {
    background: #bee3f8;
    color: #2c5aa0;
  }

  .status-pending {
    background: #fed7d7;
    color: #c53030;
  }

  /* Form Controls */
  .form-select,
  .form-control {
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
  }

  .form-select:focus,
  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .form-select-sm,
  .form-control-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
  }

  .action-select {
    min-width: 120px;
  }

  .reason-input {
    min-width: 200px;
  }

  /* Bulk Actions Section */
  .bulk-actions-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
  }

  .section-header {
    margin-bottom: 1rem;
  }

  .section-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
  }

  .bulk-actions-card {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
  }

  /* Footer */
  .premium-modal-footer {
    background: white;
    border-top: 1px solid #e2e8f0;
    padding: 1.25rem 1.5rem;
  }

  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .selected-count .count-badge {
    background: #f7fafc;
    color: #4a5568;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
  }

  .footer-actions {
    display: flex;
    gap: 0.75rem;
  }

  .btn {
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: none;
  }

  .btn-outline-secondary {
    border: 1px solid #e2e8f0;
    color: #4a5568;
  }

  .btn-outline-secondary:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
  }

  .btn-warning {
    background: linear-gradient(135deg, #f6ad55 0%, #fc8181 100%);
    color: white;
  }

  .btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(246, 173, 85, 0.3);
  }

  .btn-outline-primary {
    border: 1px solid #667eea;
    color: #667eea;
  }

  .btn-outline-primary:hover {
    background: #667eea;
    color: white;
  }

  /* Badge Styling */
  .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    border-radius: 6px;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .table-responsive {
      font-size: 0.8rem;
    }

    .package-details-cell {
      min-width: 150px;
    }

    .footer-content {
      flex-direction: column;
      gap: 1rem;
      align-items: stretch;
    }

    .footer-actions {
      justify-content: flex-end;
    }
  }
</style>
<?= $this->section('script') ?>

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
            return alert('Please provide a reason for every package.');
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
            alert(json.message || 'Requests submitted successfully');
            bsModal.hide();
            // uncheck selected rows
            document.querySelectorAll('.package-checkbox:checked').forEach(cb => cb.checked = false);
            // refresh to reflect new requests/statuses
            location.reload();
          } else {
            alert(json.message || 'Failed to submit requests');
          }
        } catch (err) {
          console.error(err);
          alert('Server error while submitting requests.');
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


<?= $this->endSection() ?>
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
          showErrorMessage('Please select at least 2 packages to combine.');
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
              showSuccessMessage('Combine & Repack request submitted successfully!');
              combineModalInstance.hide();
              setTimeout(() => location.reload(), 1500);
            } else {
              showErrorMessage('Error: ' + res.message);
            }
          })
          .catch(error => {
            showErrorMessage('Network error: ' + error.message);
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

    function showSuccessMessage(message) {
      // You can implement a toast notification here
      alert(message); // Temporary implementation
    }

    function showErrorMessage(message) {
      // You can implement a toast notification here
      alert(message); // Temporary implementation
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
    const confirmBtn = document.getElementById('confirmBulkAction');
    const destWarehouseRadio = document.getElementById('destWarehouse');
    const destCustomRadio = document.getElementById('destCustom');
    const warehouseSection = document.getElementById('warehouseSection');
    const customAddressSection = document.getElementById('customAddressSection');
    const warehouseContainer = document.getElementById('warehouseSelectContainer');
    const ratesContainer = document.getElementById('calculatedRates');

    let currentStep = 1;
    let selectedPackages = [];
    let currentAction = '';
    let currentOrigin = null;
    let currentRates = [];
    let availableWarehouses = [];
    let currentDestination = null;

    // Add toast container for booking notifications
    const toastContainer = document.createElement('div');
    toastContainer.innerHTML = `
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
    `;
    document.body.appendChild(toastContainer);

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
      confirmBtn.style.display = 'none';
      prevStepBtn.style.display = 'none';

      // Show current step
      if (step === 1) {
        stepDestination.style.display = 'block';
        nextStepBtn.textContent = 'Calculate Rates';
        prevStepBtn.style.display = 'none';
      } else if (step === 2) {
        stepRates.style.display = 'block';
        nextStepBtn.style.display = 'none';
        confirmBtn.style.display = 'inline-block';
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
            alert('Please select a destination warehouse');
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
            html += `<option value="${w.id}" ${w.id === currentOrigin.id ? 'selected' : ''}>
            ${w.country} - ${w.address_line_1}, ${w.city}, ${w.state}, ${w.postal_code}
          </option>`;
          });
          html += '</select>';
          warehouseContainer.innerHTML = html;
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
          if (data.status === 'success') {
            // Show success toast
            const toastEl = document.getElementById('bookingToast');
            const toastBody = toastEl.querySelector('.toast-body');
            toastBody.innerHTML = `✅ Booking confirmed! ID: <b>${data.booking_id}</b>`;

            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Close modal after delay and refresh page
            setTimeout(() => {
              if (modal) modal.hide();
              setTimeout(() => {
                location.reload();
              }, 1000);
            }, 2000);
          } else {
            alert('Booking failed: ' + (data.message || 'Unknown error'));
            // Reset button
            bookBtn.innerHTML = originalText;
            bookBtn.disabled = false;
          }
        })
        .catch(error => {
          console.error('Booking error:', error);
          alert('Booking failed. Please try again.');
          // Reset button
          bookBtn.innerHTML = originalText;
          bookBtn.disabled = false;
        });
    }

    // Confirm bulk action for non-shipping actions
    confirmBtn.addEventListener('click', () => {
      if (currentAction === 'ship') {
        // For shipping, we expect user to click "Book" on a specific rate
        return;
      }

      // For other actions, proceed with confirmation
      performBulkAction(currentAction, selectedPackages);
      if (modal) modal.hide();
    });

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