<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
 <!-- Page Header -->
 <div class="d-flex justify-content-between align-items-center mb-4">
  <div>
   <h1 class="h3 mb-1 text-gray-800">Combine Requests</h1>
   <p class="mb-0 text-muted">Manage package combination requests from users</p>
  </div>
 </div>



 <!-- Requests Table Card -->
 <div class="card shadow-sm border-0">
  <div class="card-header premium-header p-3 d-flex justify-content-between align-items-center">
   <h5 class="card-title mb-0">All Combine Requests</h5>

  </div>
  <div class="card-body p-0">
   <div class="table-responsive">
    <?php if ($requests): ?>
     <table class="table table-hover mb-0">
      <thead class="table-light">
       <tr>
        <th class="ps-4">ID</th>
        <th>User</th>
        <th>Packages</th>
        <th>Status</th>
        <th class="text-end pe-4">Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php

       foreach ($requests as $r): ?>
        <tr>
         <td class="ps-4 fw-medium">#<?= $r['id'] ?></td>
         <td>
          <div class="d-flex align-items-center">
           <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
            <i class="fas fa-user text-primary"></i>
           </div>
           <span><?= fullname($r['user_id']) ?></span>
          </div>
         </td>
         <td>
          <div class="d-flex flex-wrap gap-2">
           <?php
           $packageIds = json_decode($r['package_ids'], true);
           if (!empty($packageIds)) {
            foreach ($packageIds as $id) { ?>
             <a href="<?= base_url('packages/show/' . $id) ?>" class="badge bg-light text-dark text-decoration-none d-flex align-items-center gap-1">
              <i class="fas fa-box text-primary"></i><?= $id ?>
             </a>
           <?php
            }
           } else {
            echo '<span class="text-muted small">No packages</span>';
           }
           ?>
          </div>
         </td>
         <td>
          <?php
          $statusClass = '';
          switch (strtolower($r['status'])) {
           case 'completed':
            $statusClass = 'bg-success';
            break;
           case 'pending':
            $statusClass = 'bg-warning';
            break;
           case 'in_progress':
            $statusClass = 'bg-info';
            break;
           default:
            $statusClass = 'bg-secondary';
          }
          ?>
          <span class="badge <?= $statusClass ?>"><?= ucfirst($r['status']) ?></span>
         </td>
         <td class="text-end pe-4">
          <div class="btn-group btn-group-sm">
           <a href="combine-requests/edit/<?= $r['id'] ?>" class="btn btn-outline-primary">
            <i class="fas fa-edit me-1"></i>Edit
           </a>

           <form action="<?= base_url('admin/combine-requests/delete/' . $r['id']) ?>" method="post">
            <?= csrf_field() ?>
            <button onclick="return confirm('Are you sure you want to delete this request?')" type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i>Delete</button>
           </form>
          </div>
         </td>
        </tr>
       <?php endforeach; ?>
      </tbody>
     </table>
    <?php else: ?>
     <tbody>

      <div class="p-5 text-center">No Request Submitted Yet</div>
     </tbody>
    <?php endif; ?>
   </div>
  </div>

 </div>
</div>

<style>
 .avatar-sm {
  width: 32px;
  height: 32px;
  font-size: 14px;
 }

 .card {
  border-radius: 0.5rem;
 }

 .card-header {
  border-bottom: 1px solid rgba(0, 0, 0, .125);
 }

 .border-left-primary {
  border-left: 4px solid #4e73df !important;
 }

 .border-left-success {
  border-left: 4px solid #1cc88a !important;
 }

 .border-left-warning {
  border-left: 4px solid #f6c23e !important;
 }

 .border-left-info {
  border-left: 4px solid #36b9cc !important;
 }

 .table th {
  border-top: none;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #6c757d;
 }

 .table td {
  vertical-align: middle;
  padding: 1rem 0.75rem;
 }

 .shadow-sm {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
 }

 .badge {
  font-size: 0.75rem;
  padding: 0.35em 0.65em;
 }

 .btn-group .btn {
  border-radius: 0.375rem;
 }

 .btn-group .btn:not(:last-child) {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
 }

 .btn-group .btn:not(:first-child) {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
 }
</style>

<?= $this->endSection() ?>