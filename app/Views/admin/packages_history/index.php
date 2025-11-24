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

  <div class="container ">
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
        <?php
        // Count incoming and ready packages
        $incomingCount = 0;
        $readyCount = 0;

        $others = 0;
        foreach ($packages as $p) {
          if ($p['status'] === 'incoming') {
            $incomingCount++;
          } else if (
            $p['status'] === 'combined'
            || $p['status'] === 'missing'
            || $p['status'] === 'shipped'
            || $p['status'] === 'returned'
            || $p['status'] === 'disposed'
          ) {
            $others++;
          } else {
            $readyCount++;
          }
        }
        ?>

        <!-- Tabs navigation -->
        <ul class="nav nav-tabs shippex-tabs" id="packageTabs" role="tablist">

          <li class="nav-item" role="presentation">
            <button class="nav-link" id="others-tab" data-bs-toggle="tab" data-bs-target="#others" type="button" role="tab" aria-controls="others" aria-selected="false">
              Others <span class="badge"><?= $others ?></span>
            </button>
          </li>
        </ul>



        <!-- Tabs content -->
        <div class="tab-content mt-3" id="packageTabsContent">


          <!-- others Tab -->
          <div class="tab-pane fade active show" id="others" role="tabpanel" aria-labelledby="others-tab">
            <?php if ($others > 0): ?>
              <div class="table-responsive">
                <table class="table package-table">
                  <thead>
                    <tr>

                      <th scope="col">ID</th>
                      <th scope="col">Retailer & Tracking</th>
                      <th scope="col">Status</th>
                      <th scope="col">Weight</th>
                      <th scope="col">Value</th>
                      <th scope="col">User</th>
                      <th scope="col">New Package</th>
                      <!-- <th scope="col" class="actions-col">Actions</th> -->
                    </tr>
                  </thead>
                  <?php
                  $displayedParents = []; // track parent IDs already shown
                  ?>

                  <tbody>
                    <?php foreach ($packages as $p): ?>
                      <?php
                      // Only show relevant statuses
                      if (!in_array($p['status'], ['combined', 'missing', 'shipped', 'returned', 'disposed'])) continue;

                      // Determine parent ID: if this package has combined_from, treat that as parent
                      $parentId = !empty($p['combined_from']) ? $p['combined_from'] : $p['id'];

                      // Skip if we've already displayed this parent
                      if (in_array($parentId, $displayedParents)) continue;

                      $displayedParents[] = $parentId;

                      // Decode child IDs if this is a combined package
                      $childIds = [];
                      if (!empty($p['combined_from'])) {
                        $childIds = json_decode($p['combined_from'], true);
                        if (!is_array($childIds)) $childIds = [];
                      }

                      $isCombinedParent = !empty($childIds);
                      ?>

                      <tr>
                        <td><?= $p['id'] ?></td>
                        <td>

                          <?php $logoFile = '';
                          $name = $p['retailer'] ?? '';

                          // Map of courier names to logo files
                          $courierLogos = [
                            'DHL' => 'dhl.svg',
                            'UPS' => 'ups.svg',
                            'Aramex' => 'aramex.svg',
                            'FlatExportRate' => 'flatexportrate.svg',
                            'SFExpress' => 'sf-express.svg',
                            'Asendia' => 'asendia.svg',
                            'Passport' => 'passport.svg',
                            'FedEx' => 'fedex.svg',
                            'USPS' => 'usps.svg',
                            'Sendle' => 'sendle.svg',
                            'Purolator' => 'purolator.svg',
                            'Canada Post' => 'canada-post.svg',
                            'Canpar' => 'canpar.svg',
                            'StarTrack' => 'star-track.png',
                            'CouriersPlease' => 'couriers-please.svg',
                            'AlliedExpress' => 'alliedexpress.svg',
                            'TNT' => 'tnt.svg',
                            'Quantium' => 'quantium.svg',
                            'Toll' => 'toll.svg',
                            'HKPost' => 'hong-kong-post.svg',
                            'APG' => 'apg.svg',
                            'Hubbed' => 'hubbed.svg',
                          ];

                          // Check if the courier has a logo
                          if (array_key_exists($name, $courierLogos)) {
                            $logoFile = $courierLogos[$name];
                            // print_r($logoFile);
                          }
                          ?>

                          <div class="retailer-info-table">
                            <?php if ($logoFile): ?>
                              <!-- Show courier logo -->
                              <div class="">
                                <img src="<?= base_url("logos/{$logoFile}") ?>" alt="<?= esc($name) ?>" style="border-radius: 8px; " width="45" height="auto">
                              </div>
                              <div class="retailer-details-table">
                                <h6 class="retailer-name"><?= esc($p['retailer']) ?></h6>
                                <span class="tracking-number-table"><?= esc($p['tracking_number']) ?></span>
                              </div>
                            <?php else: ?>
                              <!-- Default store icon -->
                              <div class="retailer-logo-table"><i class="fas fa-store"></i></div>
                              <div class="retailer-details-table">
                                <h6 class="retailer-name"><?= esc($p['retailer']) ?></h6>
                                <span class="tracking-number-table"><?= esc($p['tracking_number']) ?></span>
                              </div>
                            <?php endif; ?>
                          </div>
                        <td>
                          <?php if ($isCombinedParent): ?>
                            <span class="d-flex align-items-center flex-wrap gap-2 status-badge status-<?= $p['status'] ?>"><i class="fas fa-compress-arrows-alt"></i>
                              <?php foreach ($childIds as $cid): ?>
                                <a href="<?= base_url('packages/show/' . $cid) ?>" class="badge bg-light text-dark text-decoration-none d-flex flex-row align-items-center gap-1"><i class="fas fa-box text-primary"></i><?= $cid ?></a>
                              <?php endforeach; ?>
                            </span>
                          <?php else: ?>
                            <span class="align-items-center  status-badge status-<?= $p['status'] ?>"><?= $p['status'] ?>
                            <?php endif; ?>
                        </td>
                        <td><span class="detail-value"><?= esc($p['weight']) ?> kg</span></td>
                        <td><span class="detail-value">$<?= esc($p['value']) ?></span></td>
                        <td><span class="detail-value"><?= fullname($p['user_id']) ?></span></td>
                        <!-- <td><span class="detail-value"><?= date('M j, Y', strtotime($p['created_at'])) ?></span></td> -->
                        <td class="actions-col">
                          <div class="action-buttons-table">
                            <a href="<?= base_url('packages/show/' . $p['id']) ?>" class="btn btn-action view"><i class="fas fa-eye"></i></a>
                            <!-- <a href="<?= base_url('packages/' . $p['id'] . '/edit') ?>" class="btn btn-action edit"><i class="fas fa-edit"></i></a>
                            <form class="delete-form" action="<?= base_url('packages/' . $p['id'] . '/delete') ?>" method="post" class="d-inline delete-form">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
                            </form> -->
                          </div>
                        </td>

                      </tr>
                    <?php endforeach; ?>
                  </tbody>

                </table>
              </div>
            <?php else: ?>
              <div class="empty-state text-center py-5">
                <div class="empty-icon mb-3">
                  <i class="fas fa-box-open fa-3x text-muted"></i>
                </div>
                <h4>No Packages Found</h4>
                <p>There are no packages incoming.</p>
              </div>
            <?php endif ?>
          </div>



        </div>

      <?php endif; ?>
    </div>

    <div class="row mt-4">
      <?= $pager->links('default', 'bootstrap_full') ?>
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
<?= view('admin/packages/scripts.php') ?>
<?= $this->endSection() ?>
<?= $this->endSection() ?>