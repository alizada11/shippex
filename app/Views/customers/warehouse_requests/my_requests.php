<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Premium Header Section -->


<!-- Content Section -->
<div class="container">


  <!-- Main Content Card -->
  <div class="premium-card">
    <div class="card-header">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <div class="page-title-section">
            <h1 class="page-title">My Warehouse </h1>
            <p class="page-subtitle">Track and manage your warehouses</p>
          </div>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (empty($requests)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-warehouse"></i>
          </div>
          <h4>No Warehouse Requests</h4>
          <p>You haven't requested any warehouses yet.</p>
          <a href="<?= base_url('profile/warehouse-addresses') ?>" class="btn btn-shippex mt-3">
            <i class="fas fa-plus me-2"></i>Request Your First Warehouse
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive ">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Warehouse</th>
                <th>Status</th>
                <th>Requested On</th>
                <th>Location</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $index => $req): ?>
                <tr>
                  <td class="fw-bold"><?= $index + 1 ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="warehouse-icon me-3">
                        <i class="fas fa-warehouse"></i>
                      </div>
                      <div>
                        <div class="fw-semibold"><?= esc($req['city']) ?>, <?= esc($req['country']) ?></div>
                        <?php if ($req['is_default']): ?>
                          <span class="badge badge-default">Default</span>

                        <?php else: ?>
                          <button class="btn btn-default btn-outline-primary set-default-btn" data-id="<?= $req['warehouse_id'] ?>">Set as Default</button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php if ($req['is_active'] == '1'): ?>
                      <span class="status-badge status-accepted">
                        <i class="fas fa-check me-1"></i>Active
                      </span>
                    <?php else: ?>
                      <span class="status-badge status-rejected">
                        <i class="fas fa-times me-1"></i>Rejected
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="date-cell">
                      <div class="fw-semibold"><?= date('Y-m-d', strtotime($req['created_at'])) ?></div>
                      <small class="text-muted"><?= date('H:i', strtotime($req['created_at'])) ?></small>
                    </div>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="<?= esc($req['map_link'] ?? '#') ?>" target="_blank" class="btn btn-icon" title="View on Map">
                        <i class="fas fa-map-marker-alt"></i>
                      </a>

                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center  p-3">
          <div class="text-muted">
            Showing <?= count($requests) ?> Warehouses
          </div>

        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  /* Premium Styling - Building on existing auth_style.css */
  :root {
    --border-radius: 8px;
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
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

  .stat-icon.pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: var(--warning);
  }

  .stat-icon.accepted {
    background-color: rgba(40, 167, 69, 0.15);
    color: var(--shippex-success);
  }

  .stat-icon.rejected {
    background-color: rgba(220, 53, 69, 0.15);
    color: var(--shippex-accent);
  }

  .stat-icon.total {
    background-color: rgba(77, 20, 140, 0.15);
    color: var(--primary-color);
  }

  .stat-content h3 {
    font-size: 1.8rem;
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
  }

  .card-header {
    background-color: var(--primary-color);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
  }

  .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0;
    color: white;
  }

  .card-body {
    padding: 1.5rem;
  }

  /* Table Styling */
  .table {
    margin-bottom: 0;
  }


  .table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-color: rgba(77, 20, 140, 0.1);
  }

  .table-hover tbody tr:hover {
    background-color: rgba(77, 20, 140, 0.03);
  }

  /* Warehouse Icon */
  .warehouse-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background-color: rgba(77, 20, 140, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
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

  .status-pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
  }

  .status-accepted {
    background-color: rgba(40, 167, 69, 0.15);
    color: #155724;
  }

  .status-rejected {
    background-color: rgba(220, 53, 69, 0.15);
    color: #721c24;
  }

  /* Default Badge */
  .btn-default:hover {
    background-color: var(--secondary-color);
    color: white;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border-color: var(--secondary-color) !important;

  }

  .btn-default {
    background-color: var(--primary-color);
    color: white;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border-color: var(--primary-color) !important;

  }

  .badge-default {
    background-color: var(--secondary-color);
    color: white;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    outline-color: var(--secondary-color) !important;
  }

  /* Reject Reason */
  .reject-reason {
    max-width: 200px;
    font-size: 0.85rem;
  }

  /* Date Cell */
  .date-cell {
    line-height: 1.2;
  }

  /* Action Buttons */
  .action-buttons {
    display: flex;
    gap: 0.5rem;
  }

  .btn-icon {
    width: 36px;
    height: 36px;
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

  /* Pagination */
  .pagination .page-link {
    border-radius: 6px;
    margin: 0 2px;
    color: var(--primary-color);
    border: 1px solid rgba(77, 20, 140, 0.2);
  }

  .pagination .page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
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

    .btn-icon {
      width: 32px;
      height: 32px;
    }

    .card-actions {
      margin-top: 1rem;
    }

    .card-actions .btn {
      margin-bottom: 0.5rem;
    }
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.set-default-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const warehouseId = this.dataset.id;

        fetch("<?= site_url('warehouse-requests/set-default') ?>", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'warehouse_id=' + encodeURIComponent(warehouseId)
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.reload(); // reload to update badges
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: data.message,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Try Again'
              });
            }
          })
          .catch(err => {
            console.error(err);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Something went wrong. Please try again later.',
              confirmButtonColor: '#d33'
            });
          });
      });
    });
  });
</script>

<?= $this->endSection() ?>