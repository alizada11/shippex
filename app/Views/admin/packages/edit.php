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
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="premium-admin-container">
  <!-- Premium Header Section -->
  <div class="premium-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="page-title-section">
          <h1 class="page-title">
            <i class="fas fa-edit me-2"></i>Edit Package
          </h1>
          <p class="page-subtitle">Tracking #<?= esc($package['tracking_number']) ?> • Update package information and contents</p>
        </div>
        <div class="header-actions">
          <a href="<?= base_url('packages/' . $package['virtual_address_id']) ?>" class="btn btn-outline-light me-2">
            <i class="fas fa-arrow-left me-2"></i> Back to Packages
          </a>
          <a href="<?= base_url('packages/show/' . $package['id']) ?>" class="text-white btn btn-outline-shippex-orange">
            <i class="fas fa-eye me-2 "></i> View Package
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Section -->
  <div class="container py-4">
    <form action="<?= base_url('packages/' . $package['id'] . '/update') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <!-- Package Information Card -->
      <div class="premium-card mb-4">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-info-circle me-2"></i>Package Information
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Retailer <span class="text-danger">*</span></label>
                <div class="input-group">

                  <input type="text" class="form-control" name="retailer" value="<?= esc($package['retailer']) ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Tracking Number <span class="text-danger">*</span></label>
                <div class="input-group">

                  <input type="text" class="form-control" name="tracking_number" value="<?= esc($package['tracking_number']) ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Value ($) <span class="text-danger">*</span></label>
                <div class="input-group">

                  <input type="number" step="0.01" class="form-control" name="value" value="<?= esc($package['value']) ?>" required>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Weight (Kg) <span class="text-danger">*</span></label>
                <div class="input-group">

                  <input type="number" step="0.01" class="form-control" name="weight" value="<?= esc($package['weight']) ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Dimensions (L × W × H)</label>
                <div class="input-group">

                  <input type="text" class="form-control" name="width" value="<?= esc($package['width']) ?>" placeholder="Width">
                  <input type="text" class="form-control" name="height" value="<?= esc($package['height']) ?>" placeholder="Height">
                  <input type="text" class="form-control" name="length" value="<?= esc($package['length']) ?>" placeholder="Length">
                </div>

              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <div class="input-group">

                  <select name="status" class="form-select" required>
                    <option value="incoming" <?= $package['status'] === 'incoming' ? 'selected' : '' ?>>Incoming</option>
                    <option value="ready" <?= $package['status'] === 'ready' ? 'selected' : '' ?>>Ready</option>
                    <option value="shipped" <?= $package['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="returned" <?= $package['status'] === 'returned' ? 'selected' : '' ?>> Returned</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4 pt-3 border-top">
            <a href="<?= base_url('packages/' . $package['virtual_address_id']) ?>" class="btn btn-outline-secondary me-3">
              <i class="fas fa-times me-2"></i> Cancel
            </a>
            <button type="submit" class="btn btn-shippex">
              <i class="fas fa-save me-2"></i> Save Changes
            </button>
          </div>
        </div>
      </div>
    </form>

    <!-- Package Items Card -->


    <!-- Files Card -->
    <div class="premium-card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-paperclip me-2"></i>Attached Files
        </h3>
        <div class="card-actions">
          <span class="badge bg-shippex-purple"><?= count($files) ?> files</span>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($files)): ?>
          <div class="empty-state small">
            <div class="empty-icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <h5>No Files Attached</h5>
            <p>This package doesn't have any files attached yet.</p>
          </div>
        <?php else: ?>
          <div class="row">
            <?php foreach ($files as $file): ?>
              <div class="col-md-6 mb-3">
                <div class="file-card">
                  <div class="file-icon">
                    <?php
                    $fileExtension = pathinfo($file['file_path'], PATHINFO_EXTENSION);
                    $iconClass = 'fas fa-file';
                    if (in_array($fileExtension, ['pdf'])) {
                      $iconClass = 'fas fa-file-pdf text-danger';
                    } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                      $iconClass = 'fas fa-file-word text-primary';
                    } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                      $iconClass = 'fas fa-file-excel text-success';
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                      $iconClass = 'fas fa-file-image text-warning';
                    }
                    ?>
                    <i class="<?= $iconClass ?>"></i>
                  </div>
                  <div class="file-info">
                    <div class="file-name"><?= ucfirst($file['file_type']) ?> Document</div>
                    <div class="file-path"><?= esc(basename($file['file_path'])) ?></div>
                  </div>
                  <div class="file-actions">
                    <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="btn btn-icon" title="View File">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="<?= base_url($file['file_path']) ?>" download class="btn btn-icon" title="Download File">
                      <i class="fas fa-download"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Upload File Form -->
        <div class="upload-section mt-4 pt-4 border-top">
          <h5 class="mb-3"><i class="fas fa-upload me-2"></i>Upload New File</h5>

          <form action="<?= base_url('packages/' . $package['id'] . '/files/upload') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="package_id" value="<?= $package['id'] ?>">

            <div class="row g-3 align-items-start">
              <div class="col-md-3">
                <label class="form-label fw-semibold">File Type</label>
                <select name="file_type" class="form-select">
                  <option value="invoice">Invoice</option>
                  <option value="photo">Photo</option>
                  <option value="label">Label</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Select File <span class="text-danger">*</span></label>
                <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                <small class="form-text text-muted">Supported formats: PDF, DOC, XLS, JPG, PNG, GIF</small>
              </div>

              <div class="col-md-3 mt-5">
                <button type="submit" class="btn btn-shippex w-100">
                  <i class="fas fa-upload me-2"></i> Upload File
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Premium Admin Styling - Building on existing auth_style.css */
  :root {
    --border-radius: 8px;
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
  }

  .premium-admin-container {
    background-color: var(--shippex-light);
    min-height: 100vh;
  }

  /* Header Styling */
  .premium-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a0d6b 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 10px 10px;
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

  /* Main Card */
  .premium-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }

  .card-header {
    background-color: var(--primary-color);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.25rem 1.5rem;
  }

  .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0;
    color: white;
  }

  .card-body {
    padding: 1.5rem;
  }

  /* Form Styling */
  .form-label {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
  }

  .form-control,
  .form-select {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    transition: var(--transition);
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(77, 20, 140, 0.25);
  }

  .bg-shippex-purple {
    background-color: var(--primary-color) !important;
  }

  .bg-shippex-light {
    background-color: var(--shippex-light) !important;
    color: var(--primary-color) !important;
    border: 1px solid var(--primary-color);
  }

  .bg-shippex-light:hover {
    background-color: var(--primary-color) !important;
    color: white !important;
  }

  /* Table Styling */
  .table {
    margin-bottom: 0;
  }

  .table thead th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    color: var(--primary-color);
    padding: 1rem 0.75rem;
    background-color: rgba(77, 20, 140, 0.05);
  }

  .table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-color: rgba(77, 20, 140, 0.1);
  }

  .table-hover tbody tr:hover {
    background-color: rgba(77, 20, 140, 0.03);
  }

  .table tfoot {
    background-color: rgba(77, 20, 140, 0.05);
  }

  /* Item Description */
  .item-description {
    line-height: 1.3;
  }

  .quantity-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: rgba(77, 20, 140, 0.1);
    color: var(--primary-color);
    border-radius: 4px;
    font-weight: 600;
  }

  .value-amount {
    font-weight: 600;
    color: var(--shippex-success);
  }

  .total-amount {
    color: var(--primary-color);
  }

  .hs-code {
    font-family: monospace;
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.85rem;
  }

  /* File Cards */
  .file-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid rgba(77, 20, 140, 0.1);
    border-radius: var(--border-radius);
    transition: var(--transition);
  }

  .file-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
  }

  .file-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background-color: rgba(77, 20, 140, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    margin-right: 1rem;
    font-size: 1.2rem;
  }

  .file-info {
    flex-grow: 1;
  }

  .file-name {
    font-weight: 600;
    color: var(--primary-color);
  }

  .file-path {
    font-size: 0.8rem;
    color: var(--secondary-color);
    word-break: break-all;
  }

  .file-actions {
    display: flex;
    gap: 0.25rem;
  }

  /* Action Buttons */
  .action-buttons {
    display: flex;
    gap: 0.5rem;
  }

  .btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--shippex-light);
    color: var(--primary-color);
    border: none;
    transition: var(--transition);
  }

  .btn-icon:hover {
    background-color: var(--secondary-color);
    color: white;
  }

  .btn-danger {
    background-color: var(--shippex-accent);
    color: white;
  }

  .btn-danger:hover {
    background-color: #c82333;
    color: white;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 2rem 1rem;
  }

  .empty-state.small {
    padding: 1.5rem 1rem;
  }

  .empty-state.small .empty-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
  }

  .empty-state.small h5 {
    font-size: 1.1rem;
  }

  .empty-icon {
    font-size: 3rem;
    color: rgba(77, 20, 140, 0.2);
    margin-bottom: 1.5rem;
  }

  .empty-state h4,
  .empty-state h5 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
  }

  .empty-state p {
    color: var(--secondary-color);
    max-width: 300px;
    margin: 0 auto;
  }

  /* Form Sections */
  .add-item-section,
  .upload-section {
    background-color: rgba(77, 20, 140, 0.02);
    border-radius: var(--border-radius);
    padding: 1.5rem;
  }

  /* Alert Styling */
  .alert {
    border-radius: var(--border-radius);
    border: none;
  }

  .alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .premium-header {
      padding: 1.5rem 0;
    }

    .page-title {
      font-size: 1.5rem;
    }

    .header-actions {
      margin-top: 1rem;
      width: 100%;
    }

    .header-actions .btn {
      width: 100%;
      margin-bottom: 0.5rem;
    }

    .file-card {
      flex-direction: column;
      text-align: center;
    }

    .file-icon {
      margin-right: 0;
      margin-bottom: 0.5rem;
    }

    .file-actions {
      margin-top: 0.5rem;
      justify-content: center;
    }

    .action-buttons {
      flex-direction: column;
      gap: 0.25rem;
    }
  }
</style>

<?= $this->endSection() ?>