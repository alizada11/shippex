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
<div class="premium-admin-container">
  <!-- Premium Header Section -->
  <div class="premium-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="page-title-section">
          <h1 class="page-title">
            <i class="fas fa-box-open me-2"></i>Package Details
          </h1>
          <p class="page-subtitle">Tracking #<?= esc($package['tracking_number']) ?> • Complete package information and history</p>
        </div>
        <div class="header-actions">
          <a href="<?= base_url('packages/' . $package['virtual_address_id']) ?>" class="btn btn-outline-light me-2">
            <i class="fas fa-arrow-left me-2"></i> Back to Packages
          </a>
          <a href="<?= base_url('packages/' . $package['id'] . '/edit') ?>" class="btn btn-shippex-orange">
            <i class="fas fa-edit me-2"></i> Edit Package
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Section -->
  <div class="container py-4">
    <!-- Package Overview Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon primary">
            <i class="fas fa-weight-hanging"></i>
          </div>
          <div class="stat-content">
            <h3><?= esc($package['weight']) ?> Kg</h3>
            <p>Weight</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon success">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-content">
            <h3>$<?= esc($package['value']) ?></h3>
            <p>Value</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon warning">
            <i class="fas fa-calendar-day"></i>
          </div>
          <div class="stat-content">
            <h3><?= esc($package['storage_days']) ?></h3>
            <p>Storage Days</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-card-icon">
            <?php if ($package['status'] === 'ready'): ?>
              <div class="stat-icon delivered">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-content">
                <h3>Ready</h3>
                <p>Status</p>
              </div>
            <?php else: ?>
              <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3><?= ucfirst($package['status']) ?></h3>
                <p>Status</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Package Details Card -->
      <div class="col-lg-8">
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
                <div class="detail-item">
                  <div class="detail-label">Tracking Number</div>
                  <div class="detail-value">#<?= esc($package['tracking_number']) ?></div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Retailer</div>
                  <div class="detail-value"><?= esc($package['retailer']) ?></div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Weight</div>
                  <div class="detail-value"><?= esc($package['weight']) ?> kg</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="detail-item">
                  <div class="detail-label">Status</div>
                  <div class="detail-value">
                    <?php if ($package['status'] === 'ready'): ?>
                      <span class="status-badge status-ready">
                        <i class="fas fa-check me-1"></i>Ready
                      </span>
                    <?php else: ?>
                      <span class="status-badge status-<?= $package['status'] ?>">
                        <i class="fas fa-clock me-1"></i><?= ucfirst($package['status']) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Dimensions <small>(L × W × H)</small></div>
                  <div class="detail-value"><?= esc($package['length'] . 'x' . $package['width'] . 'x' . $package['height']) ?></div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Received Date</div>
                  <div class="detail-value"><?= esc($package['created_at']) ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>



        <!-- Files Card -->
        <div class="premium-card mb-4">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-paperclip me-2"></i>Attached Files
            </h3>

          </div>
          <div class="card-body">
            <?php if (empty($files)): ?>
              <div class="empty-state small">
                <div class="empty-icon">
                  <i class="fas fa-file-alt"></i>
                </div>
                <h5>No Files Attached</h5>
                <p>There are no files attached to this package yet.</p>
              </div>
            <?php else: ?>
              <div class="row">
                <?php foreach ($files as $file): ?>
                  <div class="col-md-4 mb-3">
                    <div class="file-card">
                      <div class="file-icon">
                        <?php
                        $fileExtension = pathinfo($file['file_path'], PATHINFO_EXTENSION);
                        $iconClass = 'fas fa-file';
                        if (in_array($fileExtension, ['pdf'])) {
                          $iconClass = 'fas fa-file-pdf';
                        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                          $iconClass = 'fas fa-file-word';
                        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                          $iconClass = 'fas fa-file-excel';
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                          $iconClass = 'fas fa-file-image';
                        }
                        ?>
                        <i class="<?= $iconClass ?>"></i>
                      </div>
                      <div class="file-info">
                        <div class="file-name"><?= ucfirst($file['file_type']) ?> Document</div>
                        <div class="file-type"><?= strtoupper($fileExtension) ?> File</div>
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
          </div>
        </div>
      </div>

      <!-- Sidebar - Actions History -->
      <div class="col-lg-4">
        <div class="premium-card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-history me-2"></i>Actions History
            </h3>
          </div>
          <div class="card-body">
            <?php if (empty($actions)): ?>
              <div class="empty-state small">
                <div class="empty-icon">
                  <i class="fas fa-history"></i>
                </div>
                <h5>No History</h5>
                <p>No actions recorded for this package yet.</p>
              </div>
            <?php else: ?>
              <div class="timeline">
                <?php foreach ($actions as $log): ?>
                  <div class="">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                      <div class="timeline-header">
                        <strong><?= esc($log['action']) ?></strong>
                        <small class="text-muted"><?= esc($log['created_at']) ?></small>
                      </div>
                      <div class="timeline-body">
                        <p class="mb-1"><?= esc($log['notes']) ?></p>
                        <small class="text-muted">by <?= esc(fullname($log['performed_by']) ?? 'System') ?></small>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="premium-card mt-4">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-bolt me-2"></i>Quick Actions
            </h3>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <button class="btn btn-shippex">
                <i class="fas fa-shipping-fast me-2"></i>Process for Shipping
              </button>
              <button class="btn btn-outline-shippex-purple">
                <i class="fas fa-print me-2"></i>Print Label
              </button>
              <button class="btn btn-outline-shippex-orange">
                <i class="fas fa-envelope me-2"></i>Notify Customer
              </button>
              <button class="btn btn-outline-secondary">
                <i class="fas fa-archive me-2"></i>Archive Package
              </button>
            </div>
          </div>
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

  /* Stat Cards */
  .stat-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    transition: var(--transition);
    height: 100%;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  }

  .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
  }

  .stat-icon.primary {
    background-color: rgba(77, 20, 140, 0.15);
    color: var(--primary-color);
  }

  .stat-icon.success {
    background-color: rgba(40, 167, 69, 0.15);
    color: var(--shippex-success);
  }

  .stat-icon.warning {
    background-color: rgba(255, 193, 7, 0.15);
    color: #ffc107;
  }

  .stat-icon.delivered {
    background-color: rgba(40, 167, 69, 0.15);
    color: var(--shippex-success);
  }

  .stat-icon.pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: #ffc107;
  }

  .stat-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--primary-color);
  }

  .stat-content p {
    color: var(--secondary-color);
    margin-bottom: 0;
    font-weight: 500;
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

  /* Detail Items */
  .detail-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(77, 20, 140, 0.1);
  }

  .detail-item:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-weight: 600;
    color: var(--primary-color);
  }

  .detail-value {
    color: var(--dark);
  }

  /* Status Badges */
  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
  }

  .status-ready {
    background-color: rgba(40, 167, 69, 0.15);
    color: #155724;
  }

  .status-pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
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

  .file-type {
    font-size: 0.8rem;
    color: var(--secondary-color);
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

  /* Timeline */
  .timeline {
    position: relative;
    padding-left: 1.5rem;
  }

  .timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: rgba(77, 20, 140, 0.2);
  }

  .timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .timeline-item:last-child {
    margin-bottom: 0;
  }

  .timeline-marker {
    position: absolute;
    left: -5px;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--secondary-color);
    border: 2px solid white;
    box-shadow: 0 0 0 2px var(--secondary-color);
  }

  .timeline-content {
    padding-bottom: 0.5rem;
  }

  .timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
  }

  .timeline-body {
    font-size: 0.9rem;
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

  /* Quick Actions */
  .d-grid {
    display: grid;
  }

  .gap-2 {
    gap: 0.75rem;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .premium-header {
      padding: 1.5rem 0;
    }

    .page-title {
      font-size: 1.5rem;
    }

    .stat-card {
      margin-bottom: 1rem;
    }

    .action-buttons {
      flex-direction: column;
      gap: 0.25rem;
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

    .timeline-header {
      flex-direction: column;
    }

    .timeline-header small {
      margin-top: 0.25rem;
    }
  }
</style>

<?= $this->endSection() ?>