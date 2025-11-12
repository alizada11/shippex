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
<div class="container py-4">
 <div class="card">
  <div class="card-header">
   <h5><i class="bi bi-clipboard-check"></i> Process Request #SHX-<?= esc($request['id']) ?></h5>
  </div>
  <div class="card-body">
   <div class="request-details mb-4">
    <h6 class="mb-3 fw-bold text-uppercase text-muted">Request Details</h6>
    <table class="request-info-table">
     <tr>
      <th>Package ID</th>
      <td><?= esc($request['package_id']) ?></td>
     </tr>
     <tr>
      <th>User ID</th>
      <td><?= esc($request['user_id']) ?></td>
     </tr>
     <tr>
      <th>Request Type</th>
      <td>
       <span class="badge bg-light text-dark">
        <i class="bi bi-<?= $request['request_type'] === 'return' ? 'arrow-return-left' : 'trash' ?> me-1"></i>
        <?= ucfirst($request['request_type']) ?>
       </span>
      </td>
     </tr>
     <tr>
      <th>Status</th>
      <td>
       <div class="status-indicator status-<?= esc($request['status']) ?>">
        <span class="status-dot"></span>
        <span class="badge badge-<?= esc($request['status']) ?>">
         <?= ucfirst($request['status']) ?>
        </span>
       </div>
      </td>
     </tr>
     <tr>
      <th>Reason</th>
      <td><?= esc($request['reason'] ?? '-') ?></td>
     </tr>
     <tr>
      <th>Created Date</th>
      <td><?= esc($request['created_at']) ?></td>
     </tr>
    </table>
   </div>

   <?php if ($request['status'] === 'pending'): ?>
    <div class="action-form">
     <h6 class="mb-3 fw-bold text-uppercase text-muted">Process Request</h6>
     <form action="<?= site_url('admin/dispose_return/process/' . $request['id']) ?>" method="post">
      <?= csrf_field() ?>

      <div class="row mb-4">
       <div class="col-md-6 mb-3">
        <label class="form-label" for="reason">Reason</label>
        <input type="text" class="form-control" name="reason" value="<?= $request['reason'] ?>" id="reason" placeholder="Enter reason for action">
       </div>
       <div class="col-md-6">
        <label class="form-label" for="request_type">Action Type</label>
        <select name="request_type" class="form-select" id="request_type">
         <option value="">Select Action</option>
         <option value="dispose">Dispose Package</option>
         <option value="return">Return Package</option>
        </select>
       </div>
      </div>

      <div class="d-flex gap-3 flex-wrap btn-group-mobile">
       <button type="submit" name="status" value="approved" class="btn btn-success">
        <i class="bi bi-check-circle"></i> Approve Request
       </button>
       <button type="submit" name="status" value="rejected" class="btn btn-danger">
        <i class="bi bi-x-circle"></i> Reject Request
       </button>
       <a href="<?= site_url('admin/dispose-return') ?>" class="btn btn-secondary ms-auto">
        <i class="bi bi-arrow-left"></i> Back to Requests
       </a>
      </div>
     </form>
    </div>
   <?php else: ?>
    <div class="alert alert-info d-flex align-items-center">
     <i class="bi bi-info-circle-fill me-3 fs-4"></i>
     <div>
      This request has already been <strong><?= esc($request['status']) ?></strong>.
     </div>
    </div>
    <div class="d-flex justify-content-end">
     <a href="<?= site_url('admin/dispose-return') ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to Requests
     </a>
    </div>
   <?php endif; ?>
  </div>
 </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
 :root {
  --primary: #4361ee;
  --primary-light: #4895ef;
  --secondary: #3f37c9;
  --success: #4cc9f0;
  --warning: #f72585;
  --info: #7209b7;
  --light: #f8f9fa;
  --dark: #212529;
  --gray: #6c757d;
  --border-radius: 12px;
  --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
 }

 body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f7fb;
  color: #333;
  line-height: 1.6;
 }

 .card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  transition: var(--transition);
 }

 .card:hover {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
 }

 .card-header {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
  border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
  padding: 1.5rem;
  border-bottom: none;
 }

 .card-header h5 {
  margin: 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
 }

 .card-header h5 i {
  font-size: 1.4rem;
 }

 .card-body {
  padding: 2rem;
 }

 .request-info-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-bottom: 2rem;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
 }

 .request-info-table th {
  background-color: #f1f3f9;
  font-weight: 600;
  padding: 1rem 1.5rem;
  width: 30%;
  border-bottom: 1px solid #e9ecef;
 }

 .request-info-table td {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e9ecef;
  background-color: white;
 }

 .request-info-table tr:last-child th,
 .request-info-table tr:last-child td {
  border-bottom: none;
 }

 .badge {
  padding: 0.5rem 1rem;
  font-weight: 500;
  border-radius: 50px;
 }

 .badge-pending {
  background-color: #fff3cd;
  color: #856404;
 }

 .badge-approved {
  background-color: #d1edff;
  color: #0c5460;
 }

 .badge-rejected {
  background-color: #f8d7da;
  color: #721c24;
 }

 .form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
 }

 .form-control,
 .form-select {
  border-radius: 8px;
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  transition: var(--transition);
 }

 .form-control:focus,
 .form-select:focus {
  border-color: var(--primary-light);
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
 }


 .btn-success {
  background: linear-gradient(135deg, #4d148c, #6d22bdff);
  border: none;
 }

 .btn-success:hover {
  background: linear-gradient(135deg, #4d148c, #883ed8ff);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(58, 134, 255, 0.3);
 }

 .btn-danger {
  background: linear-gradient(135deg, #ff6600, #f7ad7cff);
  border: none;
 }

 .btn-danger:hover {
  background: linear-gradient(135deg, #ff6600, #f69a5cff);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(245, 37, 133, 0.3);
 }

 .btn-secondary {
  background: linear-gradient(135deg, #6c757d, #495057);
  border: none;
 }

 .btn-secondary:hover {
  background: linear-gradient(135deg, #495057, #6c757d);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
 }

 .alert {
  border-radius: var(--border-radius);
  border: none;
  padding: 1.25rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
 }

 .alert-info {
  background-color: #d1edff;
  color: #0c5460;
  border-left: 4px solid #4cc9f0;
 }

 .action-form {
  background-color: #f8f9fa;
  border-radius: var(--border-radius);
  padding: 1.5rem;
  margin-top: 1.5rem;
 }

 .status-indicator {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
 }

 .status-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
 }

 .status-pending .status-dot {
  background-color: #ffc107;
 }

 .status-approved .status-dot {
  background-color: #28a745;
 }

 .status-rejected .status-dot {
  background-color: #dc3545;
 }

 @media (max-width: 768px) {
  .card-body {
   padding: 1.5rem;
  }

  .request-info-table th,
  .request-info-table td {
   padding: 0.75rem 1rem;
  }

  .btn-group-mobile {
   flex-direction: column;
   gap: 10px;
  }

  .btn-group-mobile .btn {
   width: 100%;
   justify-content: center;
  }
 }
</style>


<?= $this->endSection() ?>