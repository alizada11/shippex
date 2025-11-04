<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="premium-admin-container">
 <!-- Premium Header Section -->
 <div class="premium-header">
  <div class="container">
   <div class="d-flex justify-content-between align-items-center">
    <div class="page-title-section">
     <h1 class="page-title">Warehouse Requests Management</h1>
     <p class="page-subtitle">Review and manage customer warehouse access requests</p>
    </div>

   </div>
  </div>
 </div>

 <!-- Content Section -->
 <div class="container py-4">
  <!-- Statistics Cards -->
  <div class="row mb-4">
   <div class="col-md-3">
    <div class="stat-card">
     <div class="stat-icon total">
      <i class="fas fa-list"></i>
     </div>
     <div class="stat-content">
      <h3><?= count($requests) ?></h3>
      <p>Total Requests</p>
     </div>
    </div>
   </div>
   <div class="col-md-3">
    <div class="stat-card">
     <div class="stat-icon pending">
      <i class="fas fa-clock"></i>
     </div>
     <div class="stat-content">
      <h3><?= count(array_filter($requests, function ($req) {
           return $req['status'] == 'pending';
          })) ?></h3>
      <p>Pending</p>
     </div>
    </div>
   </div>
   <div class="col-md-3">
    <div class="stat-card">
     <div class="stat-icon accepted">
      <i class="fas fa-check-circle"></i>
     </div>
     <div class="stat-content">
      <h3><?= count(array_filter($requests, function ($req) {
           return $req['status'] == 'accepted';
          })) ?></h3>
      <p>Accepted</p>
     </div>
    </div>
   </div>
   <div class="col-md-3">
    <div class="stat-card">
     <div class="stat-icon rejected">
      <i class="fas fa-times-circle"></i>
     </div>
     <div class="stat-content">
      <h3><?= count(array_filter($requests, function ($req) {
           return $req['status'] == 'rejected';
          })) ?></h3>
      <p>Rejected</p>
     </div>
    </div>
   </div>
  </div>

  <!-- Main Content Card -->
  <div class="premium-card">
   <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
     <h3 class="card-title">
      <i class="fas fa-warehouse me-2"></i>Warehouse Requests
     </h3>

    </div>
   </div>

   <div class="card-body">
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
      <p>There are no warehouse requests to display.</p>
     </div>
    <?php else: ?>
     <div class="table-responsive">
      <table class="table table-hover">
       <thead class="table-light">
        <tr>
         <th>ID</th>
         <th>User</th>
         <th>Warehouse</th>
         <th>Status</th>
         <th>Reason</th>
         <th>Requested On</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php foreach ($requests as $req): ?>
         <tr>
          <td class="fw-bold">#<?= $req['id'] ?></td>
          <td>
           <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
             <i class="fas fa-user"></i>
            </div>
            <div>
             <div class="fw-semibold"><?= $req['firstname'] . ' ' . $req['lastname'] ?></div>
             <small class="text-muted">User ID: <?= $req['user_id'] ?? 'N/A' ?></small>
            </div>
           </div>
          </td>
          <td>
           <div class="warehouse-info">
            <div class="fw-semibold"><?= $req['city'] ?>, <?= $req['country'] ?></div>
            <small class="text-muted">Warehouse ID: <?= $req['warehouse_id'] ?? 'N/A' ?></small>
           </div>
          </td>
          <td>
           <?php if ($req['status'] == 'pending'): ?>
            <span class="status-badge status-pending">
             <i class="fas fa-clock me-1"></i>Pending
            </span>
           <?php elseif ($req['status'] == 'accepted'): ?>
            <span class="status-badge status-accepted">
             <i class="fas fa-check me-1"></i>Accepted
            </span>
           <?php else: ?>
            <span class="status-badge status-rejected">
             <i class="fas fa-times me-1"></i>Rejected
            </span>
           <?php endif; ?>
          </td>
          <td>
           <?php if ($req['rejectation_reason']): ?>
            <div class="reject-reason">
             <i class="fas fa-info-circle text-warning me-1"></i>
             <?= esc($req['rejectation_reason']) ?>
            </div>
           <?php else: ?>
            <span class="text-muted">-</span>
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
            <a href="<?= site_url('warehouse-requests/edit/' . $req['id']) ?>" class="btn btn-icon" title="Edit Request">
             <i class="fas fa-edit"></i>
            </a>
            <?php if ($req['status'] == 'pending'): ?>
             <button class="btn btn-icon btn-success" title="Accept Request">
              <i class="fas fa-check"></i>
             </button>
             <button class="btn btn-icon btn-danger" title="Reject Request">
              <i class="fas fa-times"></i>
             </button>
            <?php endif; ?>
            <a href="<?= site_url('warehouse-requests/delete/' . $req['id']) ?>" class="btn btn-icon btn-danger" title="Delete Request" onclick="return confirm('Are you sure you want to delete this request?')">
             <i class="fas fa-trash"></i>
            </a>
           </div>
          </td>
         </tr>
        <?php endforeach; ?>
       </tbody>
      </table>
     </div>

     <!-- Pagination -->

    <?php endif; ?>
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

 .stat-icon.total {
  background-color: rgba(77, 20, 140, 0.15);
  color: var(--primary-color);
 }

 .stat-icon.pending {
  background-color: rgba(255, 193, 7, 0.15);
  color: #ffc107;
 }

 .stat-icon.accepted {
  background-color: rgba(40, 167, 69, 0.15);
  color: var(--shippex-success);
 }

 .stat-icon.rejected {
  background-color: rgba(220, 53, 69, 0.15);
  color: var(--shippex-accent);
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

 /* User Avatar */
 .user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
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

 .btn-success {
  background-color: var(--shippex-success);
  color: white;
 }

 .btn-success:hover {
  background-color: #218838;
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

  .input-group {
   width: 100% !important;
  }
 }
</style>

<?= $this->endSection() ?>